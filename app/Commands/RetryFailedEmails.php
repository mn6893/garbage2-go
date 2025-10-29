<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Models\QuoteModel;
use App\Libraries\AIQuoteProcessor;

/**
 * Retry Failed Emails Command
 * 
 * Background command to retry sending failed customer and admin emails
 */
class RetryFailedEmails extends BaseCommand
{
    protected $group       = 'Email';
    protected $name        = 'email:retry-failed';
    protected $description = 'Retry sending failed customer and admin notification emails';
    
    protected $usage     = 'email:retry-failed [options]';
    protected $arguments = [];
    protected $options   = [
        '--limit' => 'Maximum number of quotes to process in this run (default: 50)',
        '--max-attempts' => 'Maximum retry attempts before giving up (default: 3)',
        '--type' => 'Email type to retry: customer, admin, or both (default: both)',
        '--quote-id' => 'Specific quote ID to retry emails for'
    ];
    
    public function run(array $params)
    {
        $limit = (int) CLI::getOption('limit') ?: 50;
        $maxAttempts = (int) CLI::getOption('max-attempts') ?: 3;
        $emailType = CLI::getOption('type') ?: 'both';
        $specificQuoteId = CLI::getOption('quote-id');
        
        CLI::write('Starting Email Retry Process...', 'green');
        CLI::write('Max attempts: ' . $maxAttempts);
        CLI::write('Email type: ' . $emailType);
        CLI::write('Processing limit: ' . $limit);
        
        $quoteModel = new QuoteModel();
        $processor = new AIQuoteProcessor();
        
        // Get quotes with failed emails
        $quotes = $this->getQuotesWithFailedEmails($quoteModel, $limit, $maxAttempts, $emailType, $specificQuoteId);
        
        if (empty($quotes)) {
            CLI::write('No quotes found with failed emails to retry.', 'yellow');
            return;
        }
        
        CLI::write('Found ' . count($quotes) . ' quotes with failed emails to retry.');
        
        $processed = 0;
        $customerRetrySuccess = 0;
        $adminRetrySuccess = 0;
        $customerRetryFailed = 0;
        $adminRetryFailed = 0;
        
        foreach ($quotes as $quote) {
            CLI::write("Processing Quote #{$quote['id']} for {$quote['name']}...");
            
            $results = $this->retryQuoteEmails($quote, $emailType, $processor);
            
            if (isset($results['customer'])) {
                if ($results['customer']['success']) {
                    $customerRetrySuccess++;
                    CLI::write("✓ Customer email retry successful for Quote #{$quote['id']}", 'green');
                } else {
                    $customerRetryFailed++;
                    CLI::write("✗ Customer email retry failed for Quote #{$quote['id']}: " . $results['customer']['error'], 'red');
                }
            }
            
            if (isset($results['admin'])) {
                if ($results['admin']['success']) {
                    $adminRetrySuccess++;
                    CLI::write("✓ Admin email retry successful for Quote #{$quote['id']}", 'green');
                } else {
                    $adminRetryFailed++;
                    CLI::write("✗ Admin email retry failed for Quote #{$quote['id']}: " . $results['admin']['error'], 'red');
                }
            }
            
            $processed++;
            
            // Small delay to prevent overwhelming the email service
            sleep(2);
        }
        
        CLI::write('');
        CLI::write('Email Retry Summary:', 'yellow');
        CLI::write("Total Quotes Processed: {$processed}");
        CLI::write("Customer Email Retries Successful: {$customerRetrySuccess}", 'green');
        CLI::write("Customer Email Retries Failed: {$customerRetryFailed}", 'red');
        CLI::write("Admin Email Retries Successful: {$adminRetrySuccess}", 'green');
        CLI::write("Admin Email Retries Failed: {$adminRetryFailed}", 'red');
        CLI::write('Email retry process completed.', 'green');
    }
    
    /**
     * Get quotes with failed emails that need retry
     */
    private function getQuotesWithFailedEmails(QuoteModel $quoteModel, int $limit, int $maxAttempts, string $emailType, ?string $specificQuoteId): array
    {
        $builder = $quoteModel->builder();
        
        // Only look at quotes that have been AI processed successfully
        $builder->where('status', 'ai_quoted')
               ->where('ai_processed_at IS NOT NULL')
               ->where('generated_quote IS NOT NULL');
        
        if ($specificQuoteId) {
            $builder->where('id', $specificQuoteId);
        } else {
            // Build conditions based on email type
            if ($emailType === 'customer' || $emailType === 'both') {
                $builder->groupStart()
                    ->where('email_sent_to_customer', false)
                    ->where('customer_email_attempts <', $maxAttempts)
                ->groupEnd();
            }
            
            if ($emailType === 'admin' || $emailType === 'both') {
                if ($emailType === 'both') {
                    $builder->orGroupStart();
                } else {
                    $builder->groupStart();
                }
                
                $builder->where('email_sent_to_admin', false)
                       ->where('admin_email_attempts <', $maxAttempts);
                
                $builder->groupEnd();
            }
        }
        
        return $builder->orderBy('created_at', 'ASC')
                      ->limit($limit)
                      ->get()
                      ->getResultArray();
    }
    
    /**
     * Retry emails for a specific quote
     */
    private function retryQuoteEmails(array $quote, string $emailType, AIQuoteProcessor $processor): array
    {
        $results = [];
        
        // Get the generated quote data
        $generatedQuote = json_decode($quote['generated_quote'], true);
        if (!$generatedQuote) {
            return [
                'customer' => ['success' => false, 'error' => 'No generated quote data found'],
                'admin' => ['success' => false, 'error' => 'No generated quote data found']
            ];
        }
        
        // Retry customer email if needed
        if (($emailType === 'customer' || $emailType === 'both') && !$quote['email_sent_to_customer']) {
            try {
                $success = $this->sendCustomerEmail($quote, $generatedQuote);
                $results['customer'] = ['success' => $success, 'error' => $success ? null : 'Email send failed'];
                $this->updateEmailTracking($quote['id'], 'customer', $success, $success ? null : 'Retry attempt failed');
            } catch (\Exception $e) {
                $results['customer'] = ['success' => false, 'error' => $e->getMessage()];
                $this->updateEmailTracking($quote['id'], 'customer', false, $e->getMessage());
            }
        }
        
        // Retry admin email if needed
        if (($emailType === 'admin' || $emailType === 'both') && !$quote['email_sent_to_admin']) {
            try {
                $success = $this->sendAdminEmail($quote, $generatedQuote);
                $results['admin'] = ['success' => $success, 'error' => $success ? null : 'Email send failed'];
                $this->updateEmailTracking($quote['id'], 'admin', $success, $success ? null : 'Retry attempt failed');
            } catch (\Exception $e) {
                $results['admin'] = ['success' => false, 'error' => $e->getMessage()];
                $this->updateEmailTracking($quote['id'], 'admin', false, $e->getMessage());
            }
        }
        
        return $results;
    }
    
    /**
     * Send customer email
     */
    private function sendCustomerEmail(array $quote, array $generatedQuote): bool
    {
        $email = service('email');
        $email->clear();
        $email->setTo($quote['email']);
        $email->setFrom('noreply@garbagetogo.ca', 'GarbageToGo');
        $email->setSubject('Your Junk Removal Quote is Ready - Quote #' . $quote['id']);
        
        // Use the same email generation logic as AIQuoteProcessor
        $emailContent = $this->generateQuoteEmailContent($quote, $generatedQuote);
        $email->setMessage($emailContent);
        
        return $email->send();
    }
    
    /**
     * Send admin notification email
     */
    private function sendAdminEmail(array $quote, array $generatedQuote): bool
    {
        $adminEmail = env('ADMIN_EMAIL', 'admin@garbagetogo.ca');
        
        $email = service('email');
        $email->clear();
        $email->setTo($adminEmail);
        $email->setFrom('noreply@garbagetogo.ca', 'GarbageToGo AI System');
        $email->setSubject('AI Quote Generated - Quote #' . $quote['id']);
        
        $emailContent = $this->generateAdminNotificationContent($quote, $generatedQuote);
        $email->setMessage($emailContent);
        
        return $email->send();
    }
    
    /**
     * Update email tracking information
     */
    private function updateEmailTracking(int $quoteId, string $emailType, bool $success, ?string $errorMessage): void
    {
        $quoteModel = new QuoteModel();
        $quote = $quoteModel->find($quoteId);
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
        
        $quoteModel->update($quoteId, $updateData);
    }
    
    /**
     * Generate customer quote email content (same as AIQuoteProcessor)
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
                    <p>Thank you for choosing GarbageToGo for your junk removal needs. Our AI system has carefully analyzed your uploaded images and prepared a detailed quote based on the waste assessment.</p>
                    
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
                    
                    <div class="total">
                        Estimated Total Cost: $' . number_format($quoteData['total_amount'] ?? 0, 2) . '
                    </div>

                    <div class="quote-details">
                        <h3>How to Proceed</h3>
                        <p>This is an automated estimate based on our AI image analysis. We would be delighted to assist you further. To proceed with your service:</p>
                        <ul>
                            <li>Phone: <strong>(555) 123-4567</strong></li>
                            <li>Email: <strong>info@garbagetogo.ca</strong></li>
                            <li>We offer flexible scheduling at your convenience</li>
                            <li>Same-day and next-day service options available upon request</li>
                        </ul>
                        <p><em>Our team is committed to providing professional, reliable, and eco-friendly service. Final pricing will be determined by the actual volume on-site.</em></p>
                    </div>

                    <div class="terms">
                        <h4>Terms and Conditions</h4>
                        <ul>
                            <li><strong>Service Changes:</strong> Any changes to the scope of work, additional items, or modifications to the service requested after the quote has been provided may result in additional charges. We will inform you of any extra costs before proceeding with the changes.</li>
                            <li><strong>Covered Materials:</strong> If the junk or waste is covered, wrapped, or otherwise not visible during our initial assessment, uncovering or removing the covering materials may incur additional costs. This includes items stored in bags, boxes, or under tarps that were not visible during the quote preparation.</li>
                            <li><strong>Final Pricing:</strong> The final cost will be based on the actual volume of items removed. This automated quote is an estimate and may vary based on actual items and access conditions on-site.</li>
                            <li><strong>Quote Validity:</strong> This quote is valid for 30 days from the date issued. Prices are subject to change after the expiration date.</li>
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
     * Generate admin notification email content (same as AIQuoteProcessor)
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
                    </div>
                    
                    <div class="amount">
                        Generated Quote Amount: $' . number_format($quoteData['total_amount'] ?? 0, 2) . '
                    </div>
                    
                    <p><strong>Next Steps:</strong> Customer has been automatically sent their quote. Review in admin dashboard if needed.</p>
                </div>
            </div>
        </body>
        </html>';
        
        return $html;
    }
}
