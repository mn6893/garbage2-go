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
 * to analyze images, assess waste, a nd generate quotes.
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
            
            // Only process quotes with 'pending' status
            if ($quote['status'] !== 'pending') {
                return [
                    'success' => false,
                    'quote_id' => $quoteId,
                    'error' => "Quote status is '{$quote['status']}'. Only 'pending' quotes can be processed.",
                    'processing_time' => 0
                ];
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
            log_message('info', "AI Analysis result: " . json_encode($aiAnalysis));
            
            // Perform waste assessment
            $wasteAssessment = $this->performWasteAssessment($aiAnalysis, $quote);
            log_message('info', "Waste Assessment result: " . json_encode($wasteAssessment));
            
            // Generate quote
            $generatedQuote = $this->generateQuote($wasteAssessment, $quote);
            log_message('info', "Generated Quote result: " . json_encode($generatedQuote));
            
            // Format quote according to standardized structure
            $formattedQuote = $this->formatQuoteResult($quoteId, $generatedQuote, $wasteAssessment);
            log_message('info', "Formatted Quote result: " . json_encode($formattedQuote));
            
            // Save results
            $this->saveQuoteResults($quoteId, $aiAnalysis, $wasteAssessment, $generatedQuote);
            
            // Send email notifications with proper error handling (use formatted quote)
            $emailResults = $this->sendQuoteNotifications($quote, $formattedQuote);
            
            // Clear processing lock
            $this->clearProcessingLock($quoteId);
            
            // Track analytics
            $processingTime = microtime(true) - $startTime;
            $this->trackAnalytics($quoteId, 'success', $processingTime, $aiAnalysis, $formattedQuote);
            
            return [
                'success' => true,
                'quote_id' => $quoteId,
                'processing_time' => $processingTime,
                'quote_amount' => $formattedQuote['breakdown']['total'] ?? $formattedQuote['estimatedCost']['max'] ?? 0,
                'email_results' => $emailResults,
                'formatted_quote' => $formattedQuote
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
            $imagePath = WRITEPATH . 'uploads/quote_images/' . $image;
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
        // Format the generated quote according to the new JSON structure
        $formattedQuote = $this->formatQuoteResult($quoteId, $generatedQuote, $wasteAssessment);
        
        $updateData = [
            'ai_analysis' => json_encode($aiAnalysis),
            'waste_assessment' => json_encode($wasteAssessment),
            'generated_quote' => json_encode($formattedQuote),
            'ai_processed_at' => date('Y-m-d H:i:s'),
            'status' => 'quoted', // Update status to 'quoted' (valid status)
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        // Extract and store confidence score from the new data structure
        if (isset($formattedQuote['details']['confidence'])) {
            $updateData['ai_confidence_score'] = $formattedQuote['details']['confidence'] / 100;
        } elseif (isset($aiAnalysis['confidence'])) {
            $updateData['ai_confidence_score'] = $aiAnalysis['confidence'] / 100;
        } elseif (isset($wasteAssessment['confidence'])) {
            $updateData['ai_confidence_score'] = $wasteAssessment['confidence'] / 100;
        }
        
        // Add estimated amounts to main quote record for easy access - updated for new structure
        if (isset($formattedQuote['breakdown']['total'])) {
            $updateData['estimated_amount'] = $formattedQuote['breakdown']['total'];
            $updateData['base_amount'] = $formattedQuote['breakdown']['baseCost'] ?? 0;
            $updateData['additional_fees'] = ($formattedQuote['breakdown']['specialFees'] ?? 0) + 
                                           ($formattedQuote['breakdown']['environmentalFee'] ?? 0) + 
                                           ($formattedQuote['breakdown']['disposalFee'] ?? 0);
        } elseif (isset($formattedQuote['estimatedCost']['max'])) {
            // Fallback to estimated cost if breakdown not available
            $updateData['estimated_amount'] = $formattedQuote['estimatedCost']['max'];
            $updateData['base_amount'] = $formattedQuote['breakdown']['baseCost'] ?? 0;
        }
        
        // Log before update for debugging
        log_message('info', "Updating Quote #{$quoteId} with AI results. Status: quoted, Amount: $" . ($updateData['estimated_amount'] ?? 'N/A'));
        log_message('debug', "Update data keys: " . implode(', ', array_keys($updateData)));
        log_message('debug', "AI Analysis size: " . strlen($updateData['ai_analysis']) . " bytes");
        log_message('debug', "Waste Assessment size: " . strlen($updateData['waste_assessment']) . " bytes");
        log_message('debug', "Generated Quote size: " . strlen($updateData['generated_quote']) . " bytes");
        
        // Validate JSON before update
        $aiAnalysisValid = json_decode($updateData['ai_analysis']) !== null;
        $wasteAssessmentValid = json_decode($updateData['waste_assessment']) !== null;
        $generatedQuoteValid = json_decode($updateData['generated_quote']) !== null;
        
        log_message('debug', "JSON validation - AI Analysis: " . ($aiAnalysisValid ? 'valid' : 'invalid'));
        log_message('debug', "JSON validation - Waste Assessment: " . ($wasteAssessmentValid ? 'valid' : 'invalid'));
        log_message('debug', "JSON validation - Generated Quote: " . ($generatedQuoteValid ? 'valid' : 'invalid'));
        
        // Perform database update with error handling
        try {
            log_message('debug', "Starting database update for Quote #{$quoteId}");
            
            // Use the specialized AI update method
            $updateResult = $this->quoteModel->updateAIProcessingResults($quoteId, $updateData);
            
            log_message('debug', "Database update result: " . ($updateResult ? 'true' : 'false'));
            
            if ($updateResult) {
                log_message('info', "Quote #{$quoteId} AI processing data saved successfully. Status updated to 'quoted'");
                
                // Verify the update by checking what was actually saved
                $updatedQuote = $this->quoteModel->find($quoteId);
                if ($updatedQuote) {
                    log_message('debug', "Verification - Status: " . ($updatedQuote['status'] ?? 'null'));
                    log_message('debug', "Verification - Estimated Amount: " . ($updatedQuote['estimated_amount'] ?? 'null'));
                    log_message('debug', "Verification - AI Analysis saved: " . (!empty($updatedQuote['ai_analysis']) ? 'yes' : 'no'));
                    log_message('debug', "Verification - Waste Assessment saved: " . (!empty($updatedQuote['waste_assessment']) ? 'yes' : 'no'));
                    log_message('debug', "Verification - Generated Quote saved: " . (!empty($updatedQuote['generated_quote']) ? 'yes' : 'no'));
                } else {
                    log_message('error', "Could not retrieve updated quote for verification");
                }
            } else {
                log_message('error', "Failed to update Quote #{$quoteId} with AI processing results - update returned false");
                log_message('error', "Quote Model Errors: " . json_encode($this->quoteModel->errors()));
                
                // Log the specific update data that failed
                log_message('debug', "Failed update data: " . json_encode($updateData, JSON_UNESCAPED_UNICODE));
                
                throw new \Exception("Database update failed for quote #{$quoteId}. Model errors: " . json_encode($this->quoteModel->errors()));
            }
        } catch (\Exception $e) {
            log_message('error', "Database update exception for Quote #{$quoteId}: " . $e->getMessage());
            log_message('error', "Exception trace: " . $e->getTraceAsString());
            throw new \Exception("Database update failed for quote #{$quoteId}: " . $e->getMessage());
        }
    }
    
    /**
     * Send quote notifications to both customer and admin with error tracking
     */
    private function sendQuoteNotifications(array $quote, array $generatedQuote): array
    {
        log_message('info', "Starting email notifications for quote #{$quote['id']}");
        
        $results = [
            'customer_email' => ['success' => false, 'error' => null],
            'admin_email' => ['success' => false, 'error' => null]
        ];
        
        // Send customer email
        try {
            log_message('info', "Sending customer email to {$quote['email']} for quote #{$quote['id']}");
            $customerResult = $this->sendCustomerQuoteEmail($quote, $generatedQuote);
            $results['customer_email']['success'] = $customerResult;
            
            // Update database tracking - always update even if method doesn't exist
            try {
                $this->updateEmailTracking($quote['id'], 'customer', $customerResult, null);
            } catch (\Exception $trackingError) {
                log_message('warning', "Failed to update customer email tracking: " . $trackingError->getMessage());
            }
            
            if ($customerResult) {
                log_message('info', "Quote email sent successfully to {$quote['email']} for quote #{$quote['id']}");
            } else {
                log_message('warning', "Quote email failed to send to {$quote['email']} for quote #{$quote['id']} - email service returned false");
                $results['customer_email']['error'] = 'Email service returned false';
            }
        } catch (\Exception $e) {
            $results['customer_email']['error'] = $e->getMessage();
            try {
                $this->updateEmailTracking($quote['id'], 'customer', false, $e->getMessage());
            } catch (\Exception $trackingError) {
                log_message('warning', "Failed to update customer email tracking: " . $trackingError->getMessage());
            }
            log_message('error', "Failed to send quote email to {$quote['email']} for quote #{$quote['id']}: " . $e->getMessage());
        }
        
        // Send admin notification email
        try {
            log_message('info', "Sending admin notification for quote #{$quote['id']}");
            $adminResult = $this->sendAdminNotificationEmail($quote, $generatedQuote);
            $results['admin_email']['success'] = $adminResult;
            
            // Update database tracking - always update even if method doesn't exist
            try {
                $this->updateEmailTracking($quote['id'], 'admin', $adminResult, null);
            } catch (\Exception $trackingError) {
                log_message('warning', "Failed to update admin email tracking: " . $trackingError->getMessage());
            }
            
            if ($adminResult) {
                log_message('info', "Admin notification sent successfully for quote #{$quote['id']}");
            } else {
                log_message('warning', "Admin notification failed to send for quote #{$quote['id']} - email service returned false");
                $results['admin_email']['error'] = 'Email service returned false';
            }
        } catch (\Exception $e) {
            $results['admin_email']['error'] = $e->getMessage();
            try {
                $this->updateEmailTracking($quote['id'], 'admin', false, $e->getMessage());
            } catch (\Exception $trackingError) {
                log_message('warning', "Failed to update admin email tracking: " . $trackingError->getMessage());
            }
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
        // Handle the new quote data structure
        $breakdown = $generatedQuote['breakdown'] ?? [];
        $details = $generatedQuote['details'] ?? [];
        $estimatedCost = $generatedQuote['estimatedCost'] ?? [];
        
        // Fallback values for missing data
        $totalAmount = $breakdown['total'] ?? $estimatedCost['max'] ?? 0;
        $minAmount = $estimatedCost['min'] ?? ($totalAmount * 0.85);
        $baseCost = $breakdown['baseCost'] ?? 0;
        $volumeCost = $breakdown['volumeCost'] ?? 0;
        $specialFees = $breakdown['specialFees'] ?? 0;
        $environmentalFee = $breakdown['environmentalFee'] ?? 0;
        $disposalFee = $breakdown['disposalFee'] ?? 0;
        $seasonalAdjustment = $breakdown['seasonalAdjustment'] ?? 0;
        $gst = $breakdown['gst'] ?? 0;
        $pst = $breakdown['pst'] ?? 0;
        
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
                .min-price { background: #28a745; color: white; padding: 15px; text-align: center; font-size: 20px; font-weight: bold; margin: 10px 0; border-radius: 5px; }
                .special-offer { background: #ffc107; color: #212529; padding: 15px; text-align: center; font-weight: bold; margin: 10px 0; border-radius: 5px; border: 2px solid #ff6b35; }
                .terms { background: #f8f9fa; padding: 15px; margin: 15px 0; border-radius: 5px; border-left: 4px solid #2c5aa0; font-size: 14px; }
                .terms h4 { margin-top: 0; color: #2c5aa0; }
                .terms ul { margin: 10px 0; padding-left: 20px; }
                .terms li { margin: 5px 0; }
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
                    <h2>Dear ' . htmlspecialchars($quote['name']) . ',</h2>
                    <p>Thank you for choosing GarbageToGo for your junk removal needs. We have carefully reviewed your request and are pleased to provide you with a detailed quote for your service.</p>

                    <div class="special-offer">
                        Special Offer Available<br>
                        <strong>Competitive Pricing</strong> - Starting from our minimum service charge
                    </div>
                    
                    <div class="quote-details">
                        <h3>📍 Service Details</h3>
                        <p><strong>Address:</strong> ' . htmlspecialchars($quote['address']) . '</p>
                        <p><strong>Description:</strong> ' . htmlspecialchars($quote['description']) . '</p>';
        
        if (isset($details['wasteType'])) {
            $html .= '<p><strong>Waste Type:</strong> ' . htmlspecialchars($details['wasteType']) . '</p>';
        }
        
        if (isset($details['volume'])) {
            $html .= '<p><strong>Estimated Volume:</strong> ' . htmlspecialchars($details['volume']) . '</p>';
        }
        
        $html .= '
                    </div>
                    
                    <div class="min-price">
                        Minimum Service Charge: $' . number_format($minAmount, 2) . '
                    </div>

                    <div class="quote-details">
                        <h3>Service Breakdown</h3>
                        <table>
                            <tr>
                                <th>Service Description</th>
                                <th>Amount</th>
                            </tr>
                            <tr>
                                <td>Base Service Charge</td>
                                <td>$' . number_format($baseCost, 2) . '</td>
                            </tr>';
        
        if ($volumeCost > 0) {
            $html .= '<tr><td>Volume-based Charge</td><td>$' . number_format($volumeCost, 2) . '</td></tr>';
        }

        if ($specialFees > 0) {
            $html .= '<tr><td>Special Item Handling</td><td>$' . number_format($specialFees, 2) . '</td></tr>';
        }

        if ($environmentalFee > 0) {
            $html .= '<tr><td>Environmental Disposal Fee</td><td>$' . number_format($environmentalFee, 2) . '</td></tr>';
        }

        if ($disposalFee > 0) {
            $html .= '<tr><td>Disposal Fee</td><td>$' . number_format($disposalFee, 2) . '</td></tr>';
        }

        if ($seasonalAdjustment > 0) {
            $html .= '<tr><td>Seasonal Adjustment</td><td>$' . number_format($seasonalAdjustment, 2) . '</td></tr>';
        }
        
        if ($gst > 0) {
            $html .= '<tr><td>GST</td><td>$' . number_format($gst, 2) . '</td></tr>';
        }
        
        if ($pst > 0) {
            $html .= '<tr><td>PST</td><td>$' . number_format($pst, 2) . '</td></tr>';
        }
        
        $html .= '
                        </table>
                    </div>
                    
                    <div class="total">
                        Estimated Total Cost: $' . number_format($totalAmount, 2) . '
                    </div>

                    <div class="special-offer">
                        <strong>Estimated Price Range</strong><br>
                        Your service cost will range between <strong>$' . number_format($minAmount, 2) . ' - $' . number_format($totalAmount, 2) . '</strong><br>
                        <em>Please note: Smaller loads will be charged at our minimum rate of $' . number_format($minAmount, 2) . '</em>
                    </div>';
        
        if (isset($details['validUntil'])) {
            $html .= '
                    <div class="quote-details">
                        <p><strong>Quote Valid Until:</strong> ' . date('F j, Y', strtotime($details['validUntil'])) . '</p>
                    </div>';
        }

        $html .= '
                    <div class="quote-details">
                        <h3>How Would You Like to Proceed?</h3>
                        <p>Please let us know your decision by clicking one of the buttons below:</p>

                        <div style="text-align: center; margin: 30px 0;">
                            <table width="100%" cellpadding="10" cellspacing="0" style="margin: 20px 0;">
                                <tr>
                                    <td style="text-align: center; padding: 10px;">
                                        <a href="' . base_url('quote/response/' . $quote['id'] . '/accept') . '"
                                           style="display: inline-block; padding: 15px 30px; background: #28a745; color: white; text-decoration: none; border-radius: 5px; font-weight: bold; font-size: 16px;">
                                            ✓ Accept Quote
                                        </a>
                                    </td>
                                    <td style="text-align: center; padding: 10px;">
                                        <a href="' . base_url('quote/response/' . $quote['id'] . '/consider') . '"
                                           style="display: inline-block; padding: 15px 30px; background: #ffc107; color: #212529; text-decoration: none; border-radius: 5px; font-weight: bold; font-size: 16px;">
                                            ⏰ Consider Later
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="text-align: center; padding: 10px;">
                                        <a href="' . base_url('quote/response/' . $quote['id'] . '/reject') . '"
                                           style="display: inline-block; padding: 15px 30px; background: #dc3545; color: white; text-decoration: none; border-radius: 5px; font-weight: bold; font-size: 16px;">
                                            ✗ Decline Quote
                                        </a>
                                    </td>
                                    <td style="text-align: center; padding: 10px;">
                                        <a href="tel:+15551234567"
                                           style="display: inline-block; padding: 15px 30px; background: #2c5aa0; color: white; text-decoration: none; border-radius: 5px; font-weight: bold; font-size: 16px;">
                                            📞 Call Manager Now
                                        </a>
                                    </td>
                                </tr>
                            </table>
                        </div>

                        <div style="background: #e7f3ff; padding: 15px; border-radius: 5px; margin: 20px 0;">
                            <p style="margin: 0; text-align: center;"><strong>Or Contact Us Directly:</strong></p>
                            <p style="margin: 5px 0; text-align: center;">
                                Phone: <strong>(555) 123-4567</strong> |
                                Email: <strong>info@garbagetogo.ca</strong>
                            </p>
                        </div>

                        <p style="font-size: 14px; color: #666;"><em>Our team is committed to providing professional, reliable, and eco-friendly service. Final pricing will be determined by the actual volume on-site and will not exceed the quoted maximum.</em></p>
                    </div>

                    <div class="terms">
                        <h4>Terms and Conditions</h4>
                        <ul>
                            <li><strong>Service Changes:</strong> Any changes to the scope of work, additional items, or modifications to the service requested after the quote has been provided may result in additional charges. We will inform you of any extra costs before proceeding with the changes.</li>
                            <li><strong>Covered Materials:</strong> If the junk or waste is covered, wrapped, or otherwise not visible during our initial assessment, uncovering or removing the covering materials may incur additional costs. This includes items stored in bags, boxes, or under tarps that were not visible during the quote preparation.</li>
                            <li><strong>Final Pricing:</strong> The final cost will be based on the actual volume of items removed and will fall within the estimated range provided above, not exceeding the maximum quoted amount unless additional services or items are requested.</li>
                            <li><strong>Quote Validity:</strong> This quote is valid for the period specified above. Prices are subject to change after the expiration date.</li>
                            <li><strong>Payment:</strong> Payment is due upon completion of service. We accept various payment methods for your convenience.</li>
                            <li><strong>Cancellation:</strong> Please provide at least 24 hours notice for cancellations to avoid potential fees.</li>
                        </ul>
                        <p style="margin-top: 10px; font-size: 12px;"><em>By proceeding with our service, you acknowledge and accept these terms and conditions.</em></p>
                    </div>
                </div>

                <div class="footer">
                    <p><strong>Thank you for considering GarbageToGo for your junk removal needs!</strong></p>
                    <p>Visit us at <a href="https://garbagetogo.ca">garbagetogo.ca</a></p>
                    <p style="font-size: 12px; margin-top: 10px;">Professional, Reliable, and Eco-Friendly Service</p>
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
        // Handle the new quote data structure
        $breakdown = $generatedQuote['breakdown'] ?? [];
        $details = $generatedQuote['details'] ?? [];
        $estimatedCost = $generatedQuote['estimatedCost'] ?? [];
        
        // Fallback values for missing data
        $totalAmount = $breakdown['total'] ?? $estimatedCost['max'] ?? 0;
        $minAmount = $estimatedCost['min'] ?? ($totalAmount * 0.85);
        $baseCost = $breakdown['baseCost'] ?? 0;
        $volumeCost = $breakdown['volumeCost'] ?? 0;
        $specialFees = $breakdown['specialFees'] ?? 0;
        $environmentalFee = $breakdown['environmentalFee'] ?? 0;
        $disposalFee = $breakdown['disposalFee'] ?? 0;
        $seasonalAdjustment = $breakdown['seasonalAdjustment'] ?? 0;
        $gst = $breakdown['gst'] ?? 0;
        $pst = $breakdown['pst'] ?? 0;
        
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
        
        if (isset($details['wasteType'])) {
            $html .= '<p><strong>Waste Type:</strong> ' . htmlspecialchars($details['wasteType']) . '</p>';
        }
        
        if (isset($details['volume'])) {
            $html .= '<p><strong>Volume Estimate:</strong> ' . htmlspecialchars($details['volume']) . '</p>';
        }
        
        if (isset($details['confidence'])) {
            $html .= '<p><strong>AI Confidence:</strong> ' . $details['confidence'] . '%</p>';
        }
        
        if (isset($details['validUntil'])) {
            $html .= '<p><strong>Quote Valid Until:</strong> ' . date('F j, Y', strtotime($details['validUntil'])) . '</p>';
        }
        
        $html .= '
                    </div>
                    
                    <div class="amount">
                        Generated Quote Amount: $' . number_format($totalAmount, 2) . '
                    </div>

                    <div class="amount">
                        Generated Quote Amount - Min: $' . number_format($minAmount, 2) . '
                    </div>
                    
                    <div class="info-box">
                        <h3>Quote Breakdown</h3>
                        <table>
                            <tr><th>Service</th><th>Amount</th></tr>
                            <tr><td>Base Cost</td><td>$' . number_format($baseCost, 2) . '</td></tr>';
        
        if ($volumeCost > 0) {
            $html .= '<tr><td>Volume Cost</td><td>$' . number_format($volumeCost, 2) . '</td></tr>';
        }
        
        if ($specialFees > 0) {
            $html .= '<tr><td>Special Fees</td><td>$' . number_format($specialFees, 2) . '</td></tr>';
        }
        
        if ($environmentalFee > 0) {
            $html .= '<tr><td>Environmental Fee</td><td>$' . number_format($environmentalFee, 2) . '</td></tr>';
        }
        
        if ($disposalFee > 0) {
            $html .= '<tr><td>Disposal Fee</td><td>$' . number_format($disposalFee, 2) . '</td></tr>';
        }
        
        if ($seasonalAdjustment > 0) {
            $html .= '<tr><td>Seasonal Adjustment</td><td>$' . number_format($seasonalAdjustment, 2) . '</td></tr>';
        }
        
        if ($gst > 0) {
            $html .= '<tr><td>GST</td><td>$' . number_format($gst, 2) . '</td></tr>';
        }
        
        if ($pst > 0) {
            $html .= '<tr><td>PST</td><td>$' . number_format($pst, 2) . '</td></tr>';
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
    
    /**
     * Format quote result according to the standardized JSON structure
     */
    private function formatQuoteResult(int $quoteId, array $generatedQuote, array $wasteAssessment): array
    {
        // Generate quote ID if not provided
        $quoteReference = "GC-" . date('Ymd') . "-" . strtoupper(substr(md5($quoteId . time()), 0, 6));
        
        // Calculate cost breakdown
        $baseCost = $generatedQuote['breakdown']['baseCost'] ?? $generatedQuote['base_cost'] ?? 50;
        $volumeCost = $generatedQuote['breakdown']['volumeCost'] ?? $generatedQuote['volume_cost'] ?? 100;
        $specialFees = $generatedQuote['breakdown']['specialFees'] ?? $generatedQuote['special_fees'] ?? 0;
        $environmentalFee = $generatedQuote['breakdown']['environmentalFee'] ?? $generatedQuote['environmental_fee'] ?? 20;
        $disposalFee = $generatedQuote['breakdown']['disposalFee'] ?? $generatedQuote['disposal_fee'] ?? 30;
        $seasonalAdjustment = $generatedQuote['breakdown']['seasonalAdjustment'] ?? $generatedQuote['seasonal_adjustment'] ?? 0;
        
        $subtotal = $baseCost + $volumeCost + $specialFees + $environmentalFee + $disposalFee + $seasonalAdjustment;
        
        // Tax calculations (assuming Canadian rates)
        $gst = $subtotal * 0.13; // 13% HST for Ontario, adjust as needed
        $pst = 0; // PST varies by province
        $totalTaxes = $gst + $pst;
        $total = $subtotal + $totalTaxes;
        
        // Calculate estimated cost range (±15% variance)
        $minCost = $total * 0.85;
        $maxCost = $total * 1.15;
        
        return [
            'success' => 1,
            'quoteId' => $quoteReference,
            'estimatedCost' => [
                'min' => round($minCost, 2),
                'max' => round($maxCost, 2),
                'display' => '$' . number_format($minCost, 2) . '-' . number_format($maxCost, 2)
            ],
            'breakdown' => [
                'baseCost' => $baseCost,
                'volumeCost' => $volumeCost,
                'specialFees' => $specialFees,
                'environmentalFee' => $environmentalFee,
                'disposalFee' => $disposalFee,
                'seasonalAdjustment' => $seasonalAdjustment,
                'subtotal' => round($subtotal, 2),
                'gst' => round($gst, 2),
                'pst' => round($pst, 2),
                'totalTaxes' => round($totalTaxes, 2),
                'total' => round($total, 2)
            ],
            'details' => [
                'wasteType' => $wasteAssessment['waste_type'] ?? $generatedQuote['waste_type'] ?? 'General Junk',
                'volume' => $wasteAssessment['volume'] ?? $generatedQuote['volume'] ?? 'Medium load',
                'location' => $generatedQuote['location'] ?? 'ON',
                'serviceType' => $generatedQuote['service_type'] ?? 'junk_removal',
                'confidence' => $wasteAssessment['confidence'] ?? $generatedQuote['confidence'] ?? 80,
                'validUntil' => date('Y-m-d', strtotime('+30 days'))
            ],
            'recommendations' => $generatedQuote['recommendations'] ?? [
                'Book during off-peak months for potential discounts'
            ],
            'terms' => [
                'validFor' => '30 days',
                'paymentTerms' => 'Payment due upon completion of service',
                'cancellationPolicy' => '24-hour notice required for cancellation',
                'priceGuarantee' => 'Prices guaranteed for quoted volume and waste type',
                'additionalFees' => 'Additional fees may apply for extra volume or different waste types'
            ],
            'generatedAt' => date('Y-m-d H:i:s')
        ];
    }
    
    /**
     * Get quotes that are ready for AI processing
     */
    public function getQuotesReadyForProcessing(): array
    {
        return $this->quoteModel->getPendingQuotesForAI();
    }
    
    /**
     * Process multiple quotes in batch
     */
    public function processBatch(array $quoteIds = []): array
    {
        $results = [];
        
        // If no specific quote IDs provided, get all pending quotes
        if (empty($quoteIds)) {
            $pendingQuotes = $this->getQuotesReadyForProcessing();
            $quoteIds = array_column($pendingQuotes, 'id');
        }
        
        foreach ($quoteIds as $quoteId) {
            try {
                $result = $this->processQuote($quoteId);
                $results[$quoteId] = $result;
                
                // Add small delay between processing to avoid overwhelming the system
                usleep(500000); // 0.5 seconds
                
            } catch (\Exception $e) {
                $results[$quoteId] = [
                    'success' => false,
                    'quote_id' => $quoteId,
                    'error' => $e->getMessage()
                ];
                log_message('error', "Batch processing error for quote #{$quoteId}: " . $e->getMessage());
            }
        }
        
        return $results;
    }
}