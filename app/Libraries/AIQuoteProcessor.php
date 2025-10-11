<?php

namespace App\Libraries;

use App\Models\QuoteModel;
use App\Services\AIVisionService;
use App\Services\WasteAssessmentService;
use App\Services\QuoteGeneratorService;
use App\Services\AIAnalyticsService;
use CodeIgniter\CLI\CLI;
use CodeIgniter\Email\Email;

/**
 * AI Quote Processor
 * 
 * Handles background processing of quote requests using AI services
 * to analyze images, assess waste, and generate automated quotes.
 */
class AIQuoteProcessor
{
    private QuoteModel $quoteModel;
    private AIVisionService $visionService;
    private WasteAssessmentService $assessmentService;
    private QuoteGeneratorService $quoteService;
    private AIAnalyticsService $analyticsService;
    private Email $email;
    
    public function __construct()
    {
        $this->quoteModel = new QuoteModel();
        $this->visionService = new AIVisionService();
        $this->assessmentService = new WasteAssessmentService();
        $this->quoteService = new QuoteGeneratorService();
        $this->analyticsService = new AIAnalyticsService();
        $this->email = service('email');
    }
    
    /**
     * Process a quote request through AI analysis
     */
    public function processQuote(int $quoteId): array
    {
        $startTime = microtime(true);
        
        try {
            // Get quote data
            $quote = $this->quoteModel->find($quoteId);
            if (!$quote) {
                throw new \Exception("Quote not found: {$quoteId}");
            }
            
            // Check if already processing or processed to prevent duplicates
            if ($this->isAlreadyProcessing($quote)) {
                return [
                    'success' => false,
                    'quote_id' => $quoteId,
                    'error' => 'Quote is already being processed or has been processed',
                    'processing_time' => 0
                ];
            }
            
            // Set processing lock to prevent concurrent processing
            $processingLock = $this->setProcessingLock($quoteId);
            
            // Update status to processing
            $this->updateQuoteStatus($quoteId, 'ai_processing', 'AI analysis started');
            
            // Analyze images if available
            $aiAnalysis = $this->analyzeImages($quote);
            
            // Perform waste assessment
            $wasteAssessment = $this->performWasteAssessment($aiAnalysis, $quote);
            
            // Generate quote
            $generatedQuote = $this->generateQuote($wasteAssessment, $quote);
            
            // Save results
            $this->saveQuoteResults($quoteId, $aiAnalysis, $wasteAssessment, $generatedQuote);
            
            // Send email notifications with proper error handling
            $emailResults = $this->sendQuoteNotifications($quote, $generatedQuote);
            
            // Update final status
            $this->updateQuoteStatus($quoteId, 'ai_quoted', 'AI analysis completed and quote generated');
            
            // Clear processing lock
            $this->clearProcessingLock($quoteId);
            
            // Track analytics
            $processingTime = microtime(true) - $startTime;
            $this->trackAnalytics($quoteId, 'success', $processingTime, $aiAnalysis, $generatedQuote);
            
            return [
                'success' => true,
                'quote_id' => $quoteId,
                'processing_time' => $processingTime,
                'quote_amount' => $generatedQuote['quote']['total_amount'],
                'email_results' => $emailResults
            ];
            
        } catch (\Exception $e) {
            $processingTime = microtime(true) - $startTime;
            
            // Clear processing lock on error
            $this->clearProcessingLock($quoteId);
            
            // Update status to error
            $this->updateQuoteStatus($quoteId, 'ai_error', 'AI processing failed: ' . $e->getMessage());
            
            // Track failed analytics
            $this->trackAnalytics($quoteId, 'error', $processingTime, [], [], $e->getMessage());
            
            log_message('error', 'AI Quote Processing Error: ' . $e->getMessage(), [
                'quote_id' => $quoteId,
                'trace' => $e->getTraceAsString()
            ]);
            
            return [
                'success' => false,
                'quote_id' => $quoteId,
                'error' => $e->getMessage(),
                'processing_time' => $processingTime
            ];
        }
    }
    
    /**
     * Analyze uploaded images using AI Vision
     */
    private function analyzeImages(array $quote): array
    {
        if (empty($quote['images'])) {
            return [
                'success' => false,
                'message' => 'No images to analyze',
                'wasteType' => 'unknown',
                'volumeEstimate' => ['min' => 0, 'max' => 0, 'unit' => 'cubic_yards']
            ];
        }
        
        $images = json_decode($quote['images'], true);
        $imagePaths = [];
        
        // Build full paths to images
        foreach ($images as $image) {
            $imagePath = FCPATH . 'uploads/quotes/' . $image;
            if (file_exists($imagePath)) {
                $imagePaths[] = $imagePath;
            }
        }
        
        if (empty($imagePaths)) {
            throw new \Exception('No valid image files found');
        }
        
        // Prepare form context for better analysis
        $formContext = [
            'customer_location' => $quote['city'] ?? 'Canada',
            'service_address' => $quote['address'],
            'description' => $quote['description'],
            'estimated_volume' => null // Let AI determine
        ];
        
        return $this->visionService->analyzeImagesWithFormContext($imagePaths, $formContext);
    }
    
    /**
     * Perform waste assessment based on AI analysis
     */
    private function performWasteAssessment(array $aiAnalysis, array $quote): array
    {
        $metadata = [
            'location' => $quote['city'] ?? 'Canada',
            'service_type' => 'residential', // Default, could be enhanced
            'customer_description' => $quote['description'],
            'property_type' => 'residential' // Could be inferred from address
        ];
        
        return $this->assessmentService->processAnalysis($aiAnalysis, $metadata);
    }
    
    /**
     * Generate quote based on waste assessment
     */
    private function generateQuote(array $wasteAssessment, array $quote): array
    {
        $customerData = [
            'name' => $quote['name'],
            'email' => $quote['email'],
            'phone' => $quote['phone'],
            'address' => $quote['address'],
            'city' => $quote['city'],
            'postal_code' => $this->extractPostalCode($quote['address']),
            'service_type' => 'junk_removal',
            'requested_date' => date('Y-m-d', strtotime('+7 days')) // Default 7 days from now
        ];
        
        return $this->quoteService->generateQuote($wasteAssessment, $customerData);
    }
    
    /**
     * Save AI processing results to quote record
     */
    private function saveQuoteResults(int $quoteId, array $aiAnalysis, array $wasteAssessment, array $generatedQuote): void
    {
        $updateData = [
            'ai_analysis' => json_encode($aiAnalysis),
            'waste_assessment' => json_encode($wasteAssessment),
            'generated_quote' => json_encode($generatedQuote),
            'ai_processed_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        // Add estimated amounts to main quote record for easy access
        if (isset($generatedQuote['quote']['total_amount'])) {
            $updateData['estimated_amount'] = $generatedQuote['quote']['total_amount'];
            $updateData['base_amount'] = $generatedQuote['quote']['base_cost'] ?? 0;
            $updateData['additional_fees'] = $generatedQuote['quote']['total_fees'] ?? 0;
        }
        
        $this->quoteModel->update($quoteId, $updateData);
    }
    
    /**
     * Send quote notifications to both customer and admin with error tracking
     */
    private function sendQuoteNotifications(array $quote, array $generatedQuote): array
    {
        $results = [
            'customer_email' => ['success' => false, 'error' => null],
            'admin_email' => ['success' => false, 'error' => null]
        ];
        
        // Send customer email
        try {
            $customerResult = $this->sendCustomerQuoteEmail($quote, $generatedQuote);
            $results['customer_email']['success'] = $customerResult;
            
            // Update database tracking
            $this->updateEmailTracking($quote['id'], 'customer', $customerResult, null);
            
            if ($customerResult) {
                log_message('info', "Quote email sent successfully to {$quote['email']} for quote #{$quote['id']}");
            }
        } catch (\Exception $e) {
            $results['customer_email']['error'] = $e->getMessage();
            $this->updateEmailTracking($quote['id'], 'customer', false, $e->getMessage());
            log_message('error', "Failed to send quote email to {$quote['email']} for quote #{$quote['id']}: " . $e->getMessage());
        }
        
        // Send admin notification email
        try {
            $adminResult = $this->sendAdminNotificationEmail($quote, $generatedQuote);
            $results['admin_email']['success'] = $adminResult;
            
            // Update database tracking
            $this->updateEmailTracking($quote['id'], 'admin', $adminResult, null);
            
            if ($adminResult) {
                log_message('info', "Admin notification sent successfully for quote #{$quote['id']}");
            }
        } catch (\Exception $e) {
            $results['admin_email']['error'] = $e->getMessage();
            $this->updateEmailTracking($quote['id'], 'admin', false, $e->getMessage());
            log_message('error', "Failed to send admin notification for quote #{$quote['id']}: " . $e->getMessage());
        }
        
        return $results;
    }
    
    /**
     * Send automated quote email to customer
     */
    private function sendCustomerQuoteEmail(array $quote, array $generatedQuote): bool
    {
        $this->email->clear();
        $this->email->setTo($quote['email']);
        $this->email->setFrom('noreply@garbagetogo.ca', 'GarbageToGo');
        $this->email->setSubject('Your Junk Removal Quote is Ready - Quote #' . $quote['id']);
        
        // Generate email content
        $emailContent = $this->generateQuoteEmailContent($quote, $generatedQuote);
        $this->email->setMessage($emailContent);
        
        return $this->email->send();
    }
    
    /**
     * Send admin notification email
     */
    private function sendAdminNotificationEmail(array $quote, array $generatedQuote): bool
    {
        $adminEmail = env('ADMIN_EMAIL', 'admin@garbagetogo.ca');
        
        $this->email->clear();
        $this->email->setTo($adminEmail);
        $this->email->setFrom('noreply@garbagetogo.ca', 'GarbageToGo AI System');
        $this->email->setSubject('AI Quote Generated - Quote #' . $quote['id']);
        
        // Generate admin email content
        $emailContent = $this->generateAdminNotificationContent($quote, $generatedQuote);
        $this->email->setMessage($emailContent);
        
        return $this->email->send();
    }
    
    /**
     * Generate HTML email content for quote
     */
    private function generateQuoteEmailContent(array $quote, array $generatedQuote): string
    {
        $quoteData = $generatedQuote['quote'];
        $assessment = $generatedQuote['assessment'] ?? [];
        
        $html = '
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: #2c5aa0; color: white; padding: 20px; text-align: center; }
                .content { padding: 20px; background: #f9f9f9; }
                .quote-details { background: white; padding: 15px; margin: 10px 0; border-radius: 5px; }
                .total { background: #2c5aa0; color: white; padding: 15px; text-align: center; font-size: 24px; font-weight: bold; }
                .footer { text-align: center; padding: 20px; color: #666; }
                table { width: 100%; border-collapse: collapse; margin: 10px 0; }
                th, td { padding: 8px; text-align: left; border-bottom: 1px solid #ddd; }
                th { background-color: #f2f2f2; }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    <h1>Your Junk Removal Quote</h1>
                    <p>Quote #' . $quote['id'] . '</p>
                </div>
                
                <div class="content">
                    <h2>Hello ' . htmlspecialchars($quote['name']) . ',</h2>
                    <p>Thank you for your junk removal request! Our AI system has analyzed your uploaded images and generated a detailed quote based on the waste assessment.</p>
                    
                    <div class="quote-details">
                        <h3>Service Details</h3>
                        <p><strong>Address:</strong> ' . htmlspecialchars($quote['address']) . '</p>
                        <p><strong>Description:</strong> ' . htmlspecialchars($quote['description']) . '</p>';
        
        if (isset($assessment['wasteType'])) {
            $html .= '<p><strong>Waste Type:</strong> ' . htmlspecialchars($assessment['wasteType']) . '</p>';
        }
        
        if (isset($assessment['volumeEstimate'])) {
            $volume = $assessment['volumeEstimate'];
            $html .= '<p><strong>Estimated Volume:</strong> ' . $volume['min'] . ' - ' . $volume['max'] . ' ' . $volume['unit'] . '</p>';
        }
        
        $html .= '
                    </div>
                    
                    <div class="quote-details">
                        <h3>Quote Breakdown</h3>
                        <table>
                            <tr>
                                <th>Service</th>
                                <th>Amount</th>
                            </tr>
                            <tr>
                                <td>Base Service Cost</td>
                                <td>$' . number_format($quoteData['base_cost'] ?? 0, 2) . '</td>
                            </tr>';
        
        if (isset($quoteData['volume_cost']) && $quoteData['volume_cost'] > 0) {
            $html .= '<tr><td>Volume-based Pricing</td><td>$' . number_format($quoteData['volume_cost'], 2) . '</td></tr>';
        }
        
        if (isset($quoteData['special_fees']) && $quoteData['special_fees'] > 0) {
            $html .= '<tr><td>Special Handling Fees</td><td>$' . number_format($quoteData['special_fees'], 2) . '</td></tr>';
        }
        
        if (isset($quoteData['provincial_fees']) && $quoteData['provincial_fees'] > 0) {
            $html .= '<tr><td>Environmental Fees</td><td>$' . number_format($quoteData['provincial_fees'], 2) . '</td></tr>';
        }
        
        if (isset($quoteData['taxes']) && $quoteData['taxes'] > 0) {
            $html .= '<tr><td>Taxes</td><td>$' . number_format($quoteData['taxes'], 2) . '</td></tr>';
        }
        
        $html .= '
                        </table>
                    </div>
                    
                    <div class="total">
                        Total Estimated Cost: $' . number_format($quoteData['total_amount'] ?? 0, 2) . '
                    </div>
                    
                    <div class="quote-details">
                        <h3>Next Steps</h3>
                        <p>This is an automated estimate based on image analysis. To proceed:</p>
                        <ul>
                            <li>Call us at <strong>(555) 123-4567</strong> to confirm your booking</li>
                            <li>Email us at <strong>info@garbagetogo.ca</strong> for any questions</li>
                            <li>Schedule your service at your convenience</li>
                        </ul>
                        <p><em>Note: Final pricing may vary based on actual items and access conditions.</em></p>
                    </div>
                </div>
                
                <div class="footer">
                    <p>Thank you for choosing GarbageToGo!</p>
                    <p>Visit us at <a href="https://garbagetogo.ca">garbagetogo.ca</a></p>
                </div>
            </div>
        </body>
        </html>';
        
        return $html;
    }
    
    /**
     * Update quote status with timestamp and notes
     */
    private function updateQuoteStatus(int $quoteId, string $status, string $notes = ''): void
    {
        $this->quoteModel->update($quoteId, [
            'status' => $status,
            'admin_notes' => $notes,
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }
    
    /**
     * Track analytics for AI processing
     */
    private function trackAnalytics(int $quoteId, string $status, float $processingTime, array $aiAnalysis = [], array $generatedQuote = [], string $errorMessage = ''): void
    {
        try {
            $data = [
                'quote_id' => $quoteId,
                'status' => $status,
                'processing_time' => $processingTime,
                'created_at' => date('Y-m-d H:i:s')
            ];
            
            if ($status === 'success') {
                $data['tokens_used'] = $aiAnalysis['tokens_used'] ?? 0;
                $data['cost'] = $generatedQuote['processing_cost'] ?? 0;
                $data['confidence_score'] = $aiAnalysis['confidence'] ?? 0;
            } else {
                $data['error_message'] = $errorMessage;
            }
            
            // Use analytics service to store data
            $this->analyticsService->trackProcessing($data);
        } catch (\Exception $e) {
            log_message('error', 'Analytics tracking error: ' . $e->getMessage());
        }
    }
    
    /**
     * Extract postal code from address
     */
    private function extractPostalCode(string $address): string
    {
        // Simple Canadian postal code extraction
        if (preg_match('/([A-Z]\d[A-Z]\s?\d[A-Z]\d)/', strtoupper($address), $matches)) {
            return $matches[1];
        }
        return '';
    }
    
    /**
     * Check if quote is already being processed or has been processed
     */
    private function isAlreadyProcessing(array $quote): bool
    {
        // Check if already completed
        if (!empty($quote['ai_processed_at'])) {
            return true;
        }
        
        // Check if currently processing (within last 30 minutes)
        if (!empty($quote['ai_processing_started_at'])) {
            $processStart = strtotime($quote['ai_processing_started_at']);
            $cutoff = time() - (30 * 60); // 30 minutes
            
            if ($processStart > $cutoff) {
                return true;
            }
        }
        
        // Check if processing lock exists and is recent
        if (!empty($quote['processing_lock'])) {
            $lockParts = explode(':', $quote['processing_lock']);
            if (count($lockParts) === 2) {
                $lockTime = (int) $lockParts[1];
                $cutoff = time() - (30 * 60); // 30 minutes
                
                if ($lockTime > $cutoff) {
                    return true;
                }
            }
        }
        
        return false;
    }
    
    /**
     * Set processing lock to prevent concurrent processing
     */
    private function setProcessingLock(int $quoteId): string
    {
        $lockValue = 'ai_process:' . time();
        
        $this->quoteModel->update($quoteId, [
            'processing_lock' => $lockValue,
            'ai_processing_started_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        
        return $lockValue;
    }
    
    /**
     * Clear processing lock
     */
    private function clearProcessingLock(int $quoteId): void
    {
        $this->quoteModel->update($quoteId, [
            'processing_lock' => null,
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }
    
    /**
     * Update email tracking information
     */
    private function updateEmailTracking(int $quoteId, string $emailType, bool $success, ?string $errorMessage): void
    {
        $quote = $this->quoteModel->find($quoteId);
        if (!$quote) return;
        
        $updateData = [
            'last_email_attempt' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        if ($emailType === 'customer') {
            $updateData['customer_email_attempts'] = ($quote['customer_email_attempts'] ?? 0) + 1;
            $updateData['email_sent_to_customer'] = $success;
            if (!$success && $errorMessage) {
                $updateData['customer_email_error'] = $errorMessage;
            }
        } elseif ($emailType === 'admin') {
            $updateData['admin_email_attempts'] = ($quote['admin_email_attempts'] ?? 0) + 1;
            $updateData['email_sent_to_admin'] = $success;
            if (!$success && $errorMessage) {
                $updateData['admin_email_error'] = $errorMessage;
            }
        }
        
        $this->quoteModel->update($quoteId, $updateData);
    }
    
    /**
     * Generate admin notification email content
     */
    private function generateAdminNotificationContent(array $quote, array $generatedQuote): string
    {
        $quoteData = $generatedQuote['quote'];
        $assessment = $generatedQuote['assessment'] ?? [];
        
        $html = '
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: #dc3545; color: white; padding: 20px; text-align: center; }
                .content { padding: 20px; background: #f8f9fa; }
                .info-box { background: white; padding: 15px; margin: 10px 0; border-radius: 5px; border-left: 4px solid #dc3545; }
                .amount { background: #28a745; color: white; padding: 15px; text-align: center; font-size: 20px; font-weight: bold; }
                table { width: 100%; border-collapse: collapse; margin: 10px 0; }
                th, td { padding: 8px; text-align: left; border-bottom: 1px solid #ddd; }
                th { background-color: #f2f2f2; }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    <h1>AI Quote Generated</h1>
                    <p>Quote #' . $quote['id'] . ' - Admin Notification</p>
                </div>
                
                <div class="content">
                    <div class="info-box">
                        <h3>Customer Information</h3>
                        <p><strong>Name:</strong> ' . htmlspecialchars($quote['name']) . '</p>
                        <p><strong>Email:</strong> ' . htmlspecialchars($quote['email']) . '</p>
                        <p><strong>Phone:</strong> ' . htmlspecialchars($quote['phone']) . '</p>
                        <p><strong>Address:</strong> ' . htmlspecialchars($quote['address']) . '</p>
                        <p><strong>Description:</strong> ' . htmlspecialchars($quote['description']) . '</p>
                    </div>
                    
                    <div class="info-box">
                        <h3>AI Analysis Results</h3>';
        
        if (isset($assessment['wasteType'])) {
            $html .= '<p><strong>Waste Type:</strong> ' . htmlspecialchars($assessment['wasteType']) . '</p>';
        }
        
        if (isset($assessment['volumeEstimate'])) {
            $volume = $assessment['volumeEstimate'];
            $html .= '<p><strong>Volume Estimate:</strong> ' . $volume['min'] . ' - ' . $volume['max'] . ' ' . $volume['unit'] . '</p>';
        }
        
        if (isset($assessment['confidence'])) {
            $html .= '<p><strong>AI Confidence:</strong> ' . round($assessment['confidence'] * 100, 1) . '%</p>';
        }
        
        $html .= '
                    </div>
                    
                    <div class="amount">
                        Generated Quote Amount: $' . number_format($quoteData['total_amount'] ?? 0, 2) . '
                    </div>
                    
                    <div class="info-box">
                        <h3>Quote Breakdown</h3>
                        <table>
                            <tr><th>Service</th><th>Amount</th></tr>
                            <tr><td>Base Cost</td><td>$' . number_format($quoteData['base_cost'] ?? 0, 2) . '</td></tr>';
        
        if (isset($quoteData['volume_cost']) && $quoteData['volume_cost'] > 0) {
            $html .= '<tr><td>Volume Cost</td><td>$' . number_format($quoteData['volume_cost'], 2) . '</td></tr>';
        }
        
        if (isset($quoteData['special_fees']) && $quoteData['special_fees'] > 0) {
            $html .= '<tr><td>Special Fees</td><td>$' . number_format($quoteData['special_fees'], 2) . '</td></tr>';
        }
        
        if (isset($quoteData['taxes']) && $quoteData['taxes'] > 0) {
            $html .= '<tr><td>Taxes</td><td>$' . number_format($quoteData['taxes'], 2) . '</td></tr>';
        }
        
        $html .= '
                        </table>
                    </div>
                    
                    <p><strong>Next Steps:</strong> Customer has been automatically sent their quote. Review in admin dashboard if needed.</p>
                </div>
            </div>
        </body>
        </html>';
        
        return $html;
    }
}