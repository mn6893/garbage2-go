<?php

namespace App\Controllers;

class Admin extends BaseController
{
    // Static admin credentials (defined in code)
    private const ADMIN_USERNAME = 'admin';
    private const ADMIN_PASSWORD = 'admin@2025';
    
    public function __construct()
    {
        // Check if admin is logged in for protected routes
        $this->checkAdminAuth();
    }
    
    /**
     * Admin login page
     */
    public function login()
    {
        // If already logged in, redirect to dashboard
        if (session()->get('admin_logged_in')) {
            return redirect()->to('/admin/dashboard');
        }
        
        return view('admin/login');
    }
    
    /**
     * Process admin login
     */
    public function authenticate()
    {
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');
        
        // Validate credentials
        if ($username === self::ADMIN_USERNAME && $password === self::ADMIN_PASSWORD) {
            // Set session
            session()->set([
                'admin_logged_in' => true,
                'admin_username' => $username,
                'admin_login_time' => time()
            ]);
            
            return redirect()->to('/admin/dashboard')->with('success', 'Welcome to Admin Dashboard!');
        } else {
            return redirect()->to('/admin/login')->with('error', 'Invalid username or password');
        }
    }
    
    /**
     * Admin logout
     */
    public function logout()
    {
        session()->destroy();
        return redirect()->to('/admin/login')->with('success', 'You have been logged out successfully');
    }
    
    /**
     * Admin dashboard
     */
    public function dashboard()
    {
        // Get statistics
        $db = \Config\Database::connect();
        
        // Quote statistics
        $quoteBuilder = $db->table('quotes');
        $quoteStats = [
            'total' => $quoteBuilder->countAll(),
            'pending' => $quoteBuilder->where('status', 'pending')->countAllResults(false),
            'ai_processing' => $quoteBuilder->whereIn('status', ['ai_queued', 'ai_processing'])->countAllResults(false),
            'ai_quoted' => $quoteBuilder->where('status', 'ai_quoted')->countAllResults(false),
            'contacted' => $quoteBuilder->where('status', 'contacted')->countAllResults(false),
            'quoted' => $quoteBuilder->where('status', 'quoted')->countAllResults(false),
            'completed' => $quoteBuilder->where('status', 'completed')->countAllResults(false),
            'today' => $quoteBuilder->where('DATE(created_at)', date('Y-m-d'))->countAllResults(false)
        ];
        
        // Contact statistics
        $contactBuilder = $db->table('contacts');
        $contactStats = [
            'total' => $contactBuilder->countAll(),
            'new' => $contactBuilder->where('status', 'new')->countAllResults(false),
            'replied' => $contactBuilder->where('status', 'replied')->countAllResults(false),
            'resolved' => $contactBuilder->where('status', 'resolved')->countAllResults(false),
            'today' => $contactBuilder->where('DATE(created_at)', date('Y-m-d'))->countAllResults(false)
        ];
        
        // Email statistics
        $emailStats = [
            'customer_failed' => $quoteBuilder->where('email_sent_to_customer', false)
                                            ->where('customer_email_attempts >', 0)
                                            ->countAllResults(false),
            'admin_failed' => $quoteBuilder->where('email_sent_to_admin', false)
                                          ->where('admin_email_attempts >', 0)
                                          ->countAllResults(false),
            'customer_success' => $quoteBuilder->where('email_sent_to_customer', true)->countAllResults(false),
            'admin_success' => $quoteBuilder->where('email_sent_to_admin', true)->countAllResults(false),
            'retry_needed' => $quoteBuilder->where('status', 'ai_quoted')
                                          ->groupStart()
                                              ->where('email_sent_to_customer', false)
                                              ->orWhere('email_sent_to_admin', false)
                                          ->groupEnd()
                                          ->where('customer_email_attempts <', 3)
                                          ->where('admin_email_attempts <', 3)
                                          ->countAllResults(false)
        ];
        
        // Recent quotes
        $recentQuotes = $quoteBuilder->orderBy('created_at', 'DESC')->limit(5)->get()->getResultArray();
        
        // Recent contacts
        $recentContacts = $contactBuilder->orderBy('created_at', 'DESC')->limit(5)->get()->getResultArray();
        
        return view('admin/dashboard', [
            'quoteStats' => $quoteStats,
            'contactStats' => $contactStats,
            'emailStats' => $emailStats,
            'recentQuotes' => $recentQuotes,
            'recentContacts' => $recentContacts
        ]);
    }
    
    /**
     * Quote management
     */
    public function quotes()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('quotes');
        
        // Handle status filter
        $status = $this->request->getGet('status');
        if ($status && in_array($status, ['pending', 'ai_queued', 'ai_processing', 'ai_quoted', 'ai_error', 'contacted', 'quoted', 'accepted', 'rejected', 'completed'])) {
            $builder->where('status', $status);
        }
        
        // Handle search
        $search = $this->request->getGet('search');
        if ($search) {
            $builder->groupStart()
                   ->like('name', $search)
                   ->orLike('email', $search)
                   ->orLike('phone', $search)
                   ->orLike('address', $search)
                   ->groupEnd();
        }
        
        $quotes = $builder->orderBy('created_at', 'DESC')->get()->getResultArray();
        
        return view('admin/quotes', [
            'quotes' => $quotes,
            'currentStatus' => $status,
            'searchTerm' => $search
        ]);
    }
    
    /**
     * Update quote status
     */
    public function updateQuoteStatus()
    {
        $id = $this->request->getPost('id');
        $status = $this->request->getPost('status');
        $notes = $this->request->getPost('notes');

        $db = \Config\Database::connect();
        $builder = $db->table('quotes');

        // Get current quote data before update
        $quote = $builder->where('id', $id)->get()->getRowArray();

        $data = [
            'status' => $status,
            'admin_notes' => $notes,
            'updated_at' => date('Y-m-d H:i:s')
        ];

        if ($builder->where('id', $id)->update($data)) {
            // Send completion email if status changed to completed
            if ($status === 'completed' && $quote) {
                $this->sendCompletionEmail($quote);
            }

            return redirect()->to('/admin/quotes')->with('success', 'Quote status updated successfully');
        } else {
            return redirect()->to('/admin/quotes')->with('error', 'Failed to update quote status');
        }
    }
    
    /**
     * View quote details
     */
    public function quote($id)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('quotes');
        $quote = $builder->where('id', $id)->get()->getRowArray();
        
        if (!$quote) {
            return redirect()->to('/admin/quotes')->with('error', 'Quote not found');
        }
        
        return view('admin/quote_detail', ['quote' => $quote]);
    }
    
    /**
     * Contact management
     */
    public function contacts()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('contacts');
        
        // Handle status filter
        $status = $this->request->getGet('status');
        if ($status && in_array($status, ['new', 'replied', 'resolved'])) {
            $builder->where('status', $status);
        }
        
        // Handle search
        $search = $this->request->getGet('search');
        if ($search) {
            $builder->groupStart()
                   ->like('name', $search)
                   ->orLike('email', $search)
                   ->orLike('phone', $search)
                   ->orLike('service_type', $search)
                   ->orLike('message', $search)
                   ->groupEnd();
        }
        
        $contacts = $builder->orderBy('created_at', 'DESC')->get()->getResultArray();
        
        return view('admin/contacts', [
            'contacts' => $contacts,
            'currentStatus' => $status,
            'searchTerm' => $search
        ]);
    }
    
    /**
     * Update contact status
     */
    public function updateContactStatus()
    {
        $id = $this->request->getPost('id');
        $status = $this->request->getPost('status');
        
        $db = \Config\Database::connect();
        $builder = $db->table('contacts');
        
        $data = [
            'status' => $status,
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        if ($builder->where('id', $id)->update($data)) {
            return redirect()->to('/admin/contacts')->with('success', 'Contact status updated successfully');
        } else {
            return redirect()->to('/admin/contacts')->with('error', 'Failed to update contact status');
        }
    }
    
    /**
     * View contact details
     */
    public function contact($id)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('contacts');
        $contact = $builder->where('id', $id)->get()->getRowArray();
        
        if (!$contact) {
            return redirect()->to('/admin/contacts')->with('error', 'Contact not found');
        }
        
        return view('admin/contact_detail', ['contact' => $contact]);
    }
    
    /**
     * Manually trigger AI processing for a quote
     */
    public function processQuoteAI($id)
    {
        try {
            $quoteModel = new \App\Models\QuoteModel();
            $quote = $quoteModel->find($id);

            if (!$quote) {
                return redirect()->to('/admin/quotes')->with('error', 'Quote not found');
            }

            // Store original status to allow reprocessing regardless of current status
            $originalStatus = $quote['status'];

            // Temporarily set status to 'pending' to allow AI processing
            $quoteModel->update($id, [
                'status' => 'pending',
                'ai_processed_at' => null, // Clear previous processing timestamp
                'processing_lock' => null, // Clear any existing locks
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            log_message('info', 'Admin manually triggered AI processing for quote #' . $id . ' (original status: ' . $originalStatus . ')');

            $processor = new \App\Libraries\AIQuoteProcessor();
            $result = $processor->processQuote($id);

            if ($result['success']) {
                return redirect()->to('/admin/quote/' . $id)->with('success',
                    'AI processing completed successfully. Quote amount: $' . number_format($result['quote_amount'], 2));
            } else {
                // Restore original status if processing failed
                $quoteModel->update($id, [
                    'status' => $originalStatus,
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
                return redirect()->to('/admin/quote/' . $id)->with('error',
                    'AI processing failed: ' . $result['error']);
            }
        } catch (\Exception $e) {
            return redirect()->to('/admin/quote/' . $id)->with('error',
                'Error triggering AI processing: ' . $e->getMessage());
        }
    }

    /**
     * Image upload and AI analysis page
     */
    public function imageAnalysis()
    {
        return view('admin/image_analysis');
    }

    /**
     * Process uploaded image with AI
     */
    public function processImageAnalysis()
    {
        try {
            // Validate image upload
            $validationRule = [
                'images' => [
                    'label' => 'Images',
                    'rules' => 'uploaded[images]|max_size[images,5120]|is_image[images]',
                ],
            ];

            if (!$this->validate($validationRule)) {
                return redirect()->back()->with('error', 'Invalid image upload: ' . implode(', ', $this->validator->getErrors()));
            }

            $files = $this->request->getFiles();

            if (empty($files['images'])) {
                return redirect()->back()->with('error', 'No images uploaded');
            }

            // Create upload directory if it doesn't exist
            $uploadPath = WRITEPATH . 'uploads/admin_analysis/';
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }

            $uploadedImages = [];

            foreach ($files['images'] as $file) {
                if ($file->isValid() && !$file->hasMoved()) {
                    $newName = $file->getRandomName();
                    $file->move($uploadPath, $newName);
                    $uploadedImages[] = $newName;
                }
            }

            if (empty($uploadedImages)) {
                return redirect()->back()->with('error', 'Failed to upload images');
            }

            // Process with AI Vision Service - Full Quote Generation
            $visionService = new \App\Services\AIVisionService();
            $assessmentService = new \App\Services\WasteAssessmentService();
            $quoteService = new \App\Services\QuoteGeneratorService();
            $analysisResults = [];

            foreach ($uploadedImages as $imageName) {
                $imagePath = $uploadPath . $imageName;

                try {
                    // Step 1: Analyze the image with AI Vision
                    $aiAnalysis = $visionService->analyzeImages([$imagePath]);

                    // Step 2: Perform waste assessment based on AI analysis
                    $wasteAssessment = $assessmentService->processAnalysis($aiAnalysis, [
                        'address' => 'Admin Analysis',
                        'description' => 'Admin uploaded image analysis'
                    ]);

                    // Step 3: Generate quote with pricing
                    $generatedQuote = $quoteService->generateQuote($wasteAssessment, [
                        'address' => 'Admin Analysis',
                        'description' => 'Admin uploaded image analysis'
                    ]);

                    // Step 4: Format the complete quote result
                    $formattedQuote = $this->formatQuoteForDisplay($generatedQuote, $wasteAssessment);

                    $analysisResults[] = [
                        'image_name' => $imageName,
                        'image_path' => $imagePath,
                        'ai_analysis' => $aiAnalysis,
                        'waste_assessment' => $wasteAssessment,
                        'quote' => $formattedQuote,
                        'success' => true
                    ];

                } catch (\Exception $e) {
                    log_message('error', 'Image analysis error for ' . $imageName . ': ' . $e->getMessage());
                    $analysisResults[] = [
                        'image_name' => $imageName,
                        'image_path' => $imagePath,
                        'success' => false,
                        'error' => $e->getMessage()
                    ];
                }
            }

            // Store results in session for display
            session()->set('analysis_results', $analysisResults);
            session()->set('uploaded_images', $uploadedImages);

            return redirect()->to('/admin/image-analysis/results')->with('success',
                'Images analyzed successfully! ' . count($uploadedImages) . ' image(s) processed.');

        } catch (\Exception $e) {
            log_message('error', 'Admin image analysis error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error processing images: ' . $e->getMessage());
        }
    }

    /**
     * Display image analysis results
     */
    public function analysisResults()
    {
        $analysisResults = session()->get('analysis_results');
        $uploadedImages = session()->get('uploaded_images');

        if (empty($analysisResults)) {
            return redirect()->to('/admin/image-analysis')->with('error', 'No analysis results found. Please upload images first.');
        }

        return view('admin/analysis_results', [
            'results' => $analysisResults,
            'images' => $uploadedImages
        ]);
    }

    /**
     * Format quote data for display in admin analysis
     */
    private function formatQuoteForDisplay(array $generatedQuote, array $wasteAssessment): array
    {
        // Extract quote breakdown
        $breakdown = $generatedQuote['breakdown'] ?? [];
        $details = $generatedQuote['details'] ?? [];
        $estimatedCost = $generatedQuote['estimatedCost'] ?? [];

        // Calculate totals
        $totalAmount = $breakdown['total'] ?? $estimatedCost['max'] ?? 0;
        $minAmount = $estimatedCost['min'] ?? ($totalAmount * 0.85);

        return [
            'breakdown' => [
                'baseCost' => $breakdown['baseCost'] ?? 0,
                'volumeCost' => $breakdown['volumeCost'] ?? 0,
                'specialFees' => $breakdown['specialFees'] ?? 0,
                'environmentalFee' => $breakdown['environmentalFee'] ?? 0,
                'disposalFee' => $breakdown['disposalFee'] ?? 0,
                'seasonalAdjustment' => $breakdown['seasonalAdjustment'] ?? 0,
                'gst' => $breakdown['gst'] ?? 0,
                'pst' => $breakdown['pst'] ?? 0,
                'subtotal' => $breakdown['subtotal'] ?? 0,
                'total' => $totalAmount
            ],
            'estimatedCost' => [
                'min' => $minAmount,
                'max' => $totalAmount,
                'currency' => 'CAD'
            ],
            'details' => [
                'wasteType' => $wasteAssessment['wasteType'] ?? $details['wasteType'] ?? 'Mixed Waste',
                'volume' => $wasteAssessment['estimatedVolume'] ?? $details['volume'] ?? 'N/A',
                'validUntil' => date('Y-m-d', strtotime('+30 days')),
                'notes' => $details['notes'] ?? ''
            ],
            'items' => $wasteAssessment['items'] ?? [],
            'recommendations' => $wasteAssessment['recommendations'] ?? []
        ];
    }

    /**
     * View uploaded analysis image
     */
    public function viewAnalysisImage($imageName)
    {
        try {
            $imagePath = WRITEPATH . 'uploads/admin_analysis/' . $imageName;

            if (!file_exists($imagePath)) {
                throw new \Exception('Image file does not exist');
            }

            // Get file info
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mimeType = finfo_file($finfo, $imagePath);
            finfo_close($finfo);

            // Set headers for image display
            header('Content-Type: ' . $mimeType);
            header('Content-Length: ' . filesize($imagePath));
            header('Cache-Control: public, max-age=3600');

            // Output image
            readfile($imagePath);
            exit;

        } catch (\Exception $e) {
            log_message('error', 'Analysis image view error: ' . $e->getMessage());
            $this->response->setStatusCode(404);
            return 'Image not found';
        }
    }
    
    /**
     * Check admin authentication
     */
    private function checkAdminAuth()
    {
        $request = service('request');
        $uri = $request->getUri();
        $path = $uri->getPath();
        
        // Skip auth check for public routes
        if (strpos($path, 'admin/login') !== false || strpos($path, 'admin/authenticate') !== false) {
            return;
        }
        
        // Check if admin is logged in
        if (!session()->get('admin_logged_in')) {
            // Redirect to login with intended URL
            session()->setTempdata('intended_url', current_url(), 300);
            header('Location: ' . base_url('admin/login'));
            exit;
        }
        
        // Check session timeout (4 hours)
        $loginTime = session()->get('admin_login_time');
        if ($loginTime && (time() - $loginTime) > 14400) { // 4 hours
            session()->destroy();
            header('Location: ' . base_url('admin/login?timeout=1'));
            exit;
        }
    }
    
    /**
     * Retry sending emails for a specific quote
     */
    public function retryQuoteEmails($id)
    {
        $type = $this->request->getPost('email_type') ?? 'both';
        
        try {
            $db = \Config\Database::connect();
            $quote = $db->table('quotes')->where('id', $id)->get()->getRowArray();
            
            if (!$quote) {
                return redirect()->to('/admin/quote/' . $id)->with('error', 'Quote not found');
            }
            
            if (empty($quote['generated_quote'])) {
                return redirect()->to('/admin/quote/' . $id)->with('error', 'No AI-generated quote found to send');
            }
            
            $generatedQuote = json_decode($quote['generated_quote'], true);
            $email = service('email');
            
            $results = [];
            
            // Retry customer email if requested
            if (($type === 'customer' || $type === 'both') && !$quote['email_sent_to_customer']) {
                try {
                    $email->clear();
                    $email->setTo($quote['email']);
                    $email->setFrom('noreply@garbagetogo.ca', 'GarbageToGo');
                    $email->setSubject('Your Junk Removal Quote is Ready - Quote #' . $quote['id']);
                    
                    // Generate simple email content
                    $quoteData = $generatedQuote['quote'];
                    $emailContent = $this->generateSimpleQuoteEmail($quote, $quoteData);
                    $email->setMessage($emailContent);
                    
                    $success = $email->send();
                    $this->updateEmailTracking($id, 'customer', $success, $success ? null : 'Manual retry failed');
                    $results['customer'] = $success ? 'success' : 'failed';
                } catch (\Exception $e) {
                    $this->updateEmailTracking($id, 'customer', false, $e->getMessage());
                    $results['customer'] = 'failed';
                }
            }
            
            // Retry admin email if requested
            if (($type === 'admin' || $type === 'both') && !$quote['email_sent_to_admin']) {
                try {
                    $adminEmail = env('ADMIN_EMAIL', 'admin@garbagetogo.ca');
                    
                    $email->clear();
                    $email->setTo($adminEmail);
                    $email->setFrom('noreply@garbagetogo.ca', 'GarbageToGo AI System');
                    $email->setSubject('AI Quote Generated - Quote #' . $quote['id']);
                    
                    $quoteData = $generatedQuote['quote'];
                    $emailContent = $this->generateSimpleAdminEmail($quote, $quoteData);
                    $email->setMessage($emailContent);
                    
                    $success = $email->send();
                    $this->updateEmailTracking($id, 'admin', $success, $success ? null : 'Manual retry failed');
                    $results['admin'] = $success ? 'success' : 'failed';
                } catch (\Exception $e) {
                    $this->updateEmailTracking($id, 'admin', false, $e->getMessage());
                    $results['admin'] = 'failed';
                }
            }
            
            // Generate success message
            $messages = [];
            if (isset($results['customer'])) {
                $messages[] = 'Customer email: ' . ($results['customer'] === 'success' ? 'sent successfully' : 'failed to send');
            }
            if (isset($results['admin'])) {
                $messages[] = 'Admin email: ' . ($results['admin'] === 'success' ? 'sent successfully' : 'failed to send');
            }
            
            $message = implode(', ', $messages);
            $hasSuccess = in_array('success', $results);
            
            return redirect()->to('/admin/quote/' . $id)->with(
                $hasSuccess ? 'success' : 'error', 
                'Email retry completed. ' . $message
            );
            
        } catch (\Exception $e) {
            return redirect()->to('/admin/quote/' . $id)->with('error', 
                'Error retrying emails: ' . $e->getMessage());
        }
    }
    
    /**
     * Bulk retry failed emails
     */
    public function bulkRetryEmails()
    {
        $emailType = $this->request->getPost('email_type') ?? 'both';
        $maxAttempts = (int) ($this->request->getPost('max_attempts') ?? 3);
        
        try {
            // Use the CLI command to process emails
            $command = "php spark email:retry-failed --type={$emailType} --max-attempts={$maxAttempts} --limit=20";
            
            // Execute in background
            if (PHP_OS_FAMILY === 'Windows') {
                pclose(popen("start /B {$command}", "r"));
            } else {
                exec("{$command} > /dev/null 2>&1 &");
            }
            
            return redirect()->to('/admin/dashboard')->with('success', 
                'Bulk email retry process started. Check back in a few minutes for results.');
            
        } catch (\Exception $e) {
            return redirect()->to('/admin/dashboard')->with('error', 
                'Error starting bulk email retry: ' . $e->getMessage());
        }
    }
    
    /**
     * Update email tracking information
     */
    private function updateEmailTracking(int $quoteId, string $emailType, bool $success, ?string $errorMessage): void
    {
        $db = \Config\Database::connect();
        $quote = $db->table('quotes')->where('id', $quoteId)->get()->getRowArray();
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
        
        $db->table('quotes')->where('id', $quoteId)->update($updateData);
    }
    
    /**
     * Generate simple customer quote email
     */
    private function generateSimpleQuoteEmail(array $quote, array $quoteData): string
    {
        return '<html><body style="font-family: Arial, sans-serif;">
            <h2>Your Junk Removal Quote - #' . $quote['id'] . '</h2>
            <p>Hello ' . htmlspecialchars($quote['name']) . ',</p>
            <p>Thank you for your request! Here is your automated quote:</p>
            <div style="background: #f0f0f0; padding: 20px; margin: 20px 0;">
                <h3>Quote Details</h3>
                <p><strong>Address:</strong> ' . htmlspecialchars($quote['address']) . '</p>
                <p><strong>Total Amount:</strong> $' . number_format($quoteData['total_amount'] ?? 0, 2) . '</p>
            </div>
            <p>To proceed, please call us at (555) 123-4567 or email info@garbagetogo.ca</p>
            <p>Thank you!</p>
        </body></html>';
    }
    
    /**
     * Generate simple admin notification email
     */
    private function generateSimpleAdminEmail(array $quote, array $quoteData): string
    {
        return '<html><body style="font-family: Arial, sans-serif;">
            <h2>AI Quote Generated - #' . $quote['id'] . '</h2>
            <p><strong>Customer:</strong> ' . htmlspecialchars($quote['name']) . ' (' . htmlspecialchars($quote['email']) . ')</p>
            <p><strong>Address:</strong> ' . htmlspecialchars($quote['address']) . '</p>
            <p><strong>Amount:</strong> $' . number_format($quoteData['total_amount'] ?? 0, 2) . '</p>
            <p>Check admin dashboard for full details.</p>
        </body></html>';
    }
    
    /**
     * Send completion thank you email to customer
     */
    private function sendCompletionEmail(array $quote): void
    {
        try {
            $email = service('email');
            $email->clear();
            $email->setTo($quote['email']);
            $email->setFrom('noreply@garbagetogo.ca', 'GarbageToGo');
            $email->setSubject('Thank You - Service Completed - Quote #' . $quote['id']);

            $emailContent = $this->generateCompletionEmailContent($quote);
            $email->setMessage($emailContent);

            if ($email->send()) {
                log_message('info', 'Completion email sent successfully to: ' . $quote['email'] . ' for quote #' . $quote['id']);
            } else {
                log_message('error', 'Failed to send completion email to: ' . $quote['email'] . ' for quote #' . $quote['id']);
            }
        } catch (\Exception $e) {
            log_message('error', 'Error sending completion email for quote #' . $quote['id'] . ': ' . $e->getMessage());
        }
    }

    /**
     * Generate completion email content
     */
    private function generateCompletionEmailContent(array $quote): string
    {
        $html = '
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: #28a745; color: white; padding: 20px; text-align: center; border-radius: 5px 5px 0 0; }
                .content { padding: 20px; background: #f9f9f9; }
                .highlight-box { background: white; padding: 20px; margin: 15px 0; border-radius: 5px; border-left: 4px solid #28a745; }
                .info-box { background: #e7f4e7; padding: 15px; margin: 15px 0; border-radius: 5px; }
                .footer { text-align: center; padding: 20px; color: #666; background: #f0f0f0; border-radius: 0 0 5px 5px; }
                .btn { display: inline-block; padding: 12px 30px; background: #28a745; color: white; text-decoration: none; border-radius: 5px; margin: 10px 0; }
                ul { padding-left: 20px; }
                li { margin: 8px 0; }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    <h1>Payment Received - Thank You!</h1>
                    <p>Service Completed Successfully</p>
                </div>

                <div class="content">
                    <h2>Dear ' . htmlspecialchars($quote['name']) . ',</h2>

                    <div class="info-box">
                        <p style="margin: 0; font-size: 16px; text-align: center;">
                            <strong>We have received your payment and your service has been completed!</strong>
                        </p>
                    </div>

                    <div class="highlight-box">
                        <h3 style="margin-top: 0; color: #28a745;">Service Details</h3>
                        <p><strong>Quote Number:</strong> #' . $quote['id'] . '</p>
                        <p><strong>Service Address:</strong> ' . htmlspecialchars($quote['address']) . '</p>
                        <p><strong>Status:</strong> Completed & Paid</p>
                    </div>

                    <p>Thank you for choosing GarbageToGo for your junk removal needs. We truly appreciate your business and trust in our services.</p>

                    <div class="highlight-box">
                        <h3 style="margin-top: 0;">We Hope You Were Satisfied!</h3>
                        <p>Your satisfaction is our top priority. We hope that our service met your expectations and that your experience with us was pleasant and professional.</p>
                    </div>

                    <div class="highlight-box">
                        <h3 style="margin-top: 0;">Please Consider Our Services Again</h3>
                        <p>We would be honored to serve you again in the future. Whether you need:</p>
                        <ul>
                            <li>Residential junk removal</li>
                            <li>Commercial waste disposal</li>
                            <li>Estate cleanouts</li>
                            <li>Renovation debris removal</li>
                            <li>Furniture and appliance disposal</li>
                            <li>Yard waste removal</li>
                        </ul>
                        <p>We are always here to help you with professional, reliable, and eco-friendly service.</p>
                    </div>

                    <div class="highlight-box">
                        <h3 style="margin-top: 0;">Share Your Experience</h3>
                        <p>If you were happy with our service, we would greatly appreciate it if you could share your experience with others. Your feedback helps us improve and grow our business.</p>
                        <p>You can also refer friends and family to GarbageToGo for their junk removal needs!</p>
                    </div>

                    <div style="text-align: center; margin: 20px 0;">
                        <p><strong>Need our services again?</strong></p>
                        <p>Contact us anytime:</p>
                        <p>
                            <strong>Phone:</strong> (555) 123-4567<br>
                            <strong>Email:</strong> info@garbagetogo.ca<br>
                            <strong>Website:</strong> <a href="https://garbagetogo.ca">garbagetogo.ca</a>
                        </p>
                    </div>

                    <div class="info-box">
                        <p style="margin: 0; text-align: center;">
                            <strong>Thank you once again for your business!</strong><br>
                            <em>We look forward to serving you in the future.</em>
                        </p>
                    </div>
                </div>

                <div class="footer">
                    <p><strong>GarbageToGo - Your Trusted Junk Removal Partner</strong></p>
                    <p>Professional, Reliable, and Eco-Friendly Service</p>
                    <p style="font-size: 12px; margin-top: 15px;">
                        This is an automated confirmation email. If you have any questions or concerns,<br>
                        please contact us at info@garbagetogo.ca or call (555) 123-4567
                    </p>
                </div>
            </div>
        </body>
        </html>';

        return $html;
    }

    /**
     * View quote image
     */
    public function viewQuoteImage($quoteId, $imageIndex)
    {
        try {
            $quoteModel = new \App\Models\QuoteModel();
            $quote = $quoteModel->find($quoteId);
            
            if (!$quote) {
                throw new \Exception('Quote not found');
            }
            
            $images = json_decode($quote['images'], true);
            if (!is_array($images) || !isset($images[$imageIndex])) {
                throw new \Exception('Image not found');
            }
            
            $filename = $images[$imageIndex];
            $imagePath = WRITEPATH . 'uploads/quote_images/' . $filename;
            
            if (!file_exists($imagePath)) {
                throw new \Exception('Image file does not exist');
            }
            
            // Get file info
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mimeType = finfo_file($finfo, $imagePath);
            finfo_close($finfo);
            
            // Set headers for image display
            header('Content-Type: ' . $mimeType);
            header('Content-Length: ' . filesize($imagePath));
            header('Cache-Control: public, max-age=3600');
            
            // Output image
            readfile($imagePath);
            exit;
            
        } catch (\Exception $e) {
            log_message('error', 'Image view error: ' . $e->getMessage());
            
            // Return a placeholder image or 404
            $this->response->setStatusCode(404);
            return 'Image not found';
        }
    }
    
    /**
     * Download quote image
     */
    public function downloadQuoteImage($quoteId, $imageIndex)
    {
        try {
            $quoteModel = new \App\Models\QuoteModel();
            $quote = $quoteModel->find($quoteId);

            if (!$quote) {
                throw new \Exception('Quote not found');
            }

            $images = json_decode($quote['images'], true);
            if (!is_array($images) || !isset($images[$imageIndex])) {
                throw new \Exception('Image not found');
            }

            $filename = $images[$imageIndex];
            $imagePath = WRITEPATH . 'uploads/quote_images/' . $filename;

            if (!file_exists($imagePath)) {
                throw new \Exception('Image file does not exist');
            }

            // Get file info
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mimeType = finfo_file($finfo, $imagePath);
            finfo_close($finfo);

            // Force download headers
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="quote_' . $quoteId . '_image_' . ($imageIndex + 1) . '_' . $filename . '"');
            header('Content-Length: ' . filesize($imagePath));
            header('Cache-Control: no-cache, must-revalidate');

            // Output file
            readfile($imagePath);
            exit;

        } catch (\Exception $e) {
            log_message('error', 'Image download error: ' . $e->getMessage());

            $this->response->setStatusCode(404);
            return 'Image not found';
        }
    }

    /**
     * Upload additional images to an existing quote from admin panel
     */
    public function uploadQuoteImages($quoteId)
    {
        try {
            $quoteModel = new \App\Models\QuoteModel();
            $quote = $quoteModel->find($quoteId);

            if (!$quote) {
                return redirect()->to('/admin/quote/' . $quoteId)->with('error', 'Quote not found');
            }

            // Validate image upload
            $validationRule = [
                'additional_images' => [
                    'label' => 'Images',
                    'rules' => 'uploaded[additional_images]|max_size[additional_images,5120]|is_image[additional_images]',
                ],
            ];

            if (!$this->validate($validationRule)) {
                return redirect()->back()->with('error', 'Invalid image upload: ' . implode(', ', $this->validator->getErrors()));
            }

            $files = $this->request->getFiles();

            if (empty($files['additional_images'])) {
                return redirect()->back()->with('error', 'No images uploaded');
            }

            // Create upload directory if it doesn't exist
            $uploadPath = WRITEPATH . 'uploads/quote_images/';
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }

            $uploadedImages = [];

            foreach ($files['additional_images'] as $file) {
                if ($file->isValid() && !$file->hasMoved()) {
                    $newName = $file->getRandomName();
                    $file->move($uploadPath, $newName);
                    $uploadedImages[] = $newName;
                }
            }

            if (empty($uploadedImages)) {
                return redirect()->back()->with('error', 'Failed to upload images');
            }

            // Get existing images
            $existingImages = json_decode($quote['images'], true) ?? [];

            // Merge new images with existing ones
            $allImages = array_merge($existingImages, $uploadedImages);

            // Update quote with new images
            $quoteModel->update($quoteId, [
                'images' => json_encode($allImages),
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            log_message('info', 'Admin uploaded ' . count($uploadedImages) . ' new images to quote #' . $quoteId);

            // Check if AI processing is requested
            $processWithAI = $this->request->getPost('process_with_ai');

            if ($processWithAI) {
                // Trigger AI processing with the same logic as existing processQuoteAI
                try {
                    // First, temporarily set status to 'pending' to allow reprocessing
                    $originalStatus = $quote['status'];
                    $quoteModel->update($quoteId, [
                        'status' => 'pending',
                        'ai_processed_at' => null, // Clear previous processing timestamp
                        'processing_lock' => null, // Clear any existing locks
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);

                    log_message('info', 'Admin triggered AI reprocessing for quote #' . $quoteId . ' (original status: ' . $originalStatus . ')');

                    $processor = new \App\Libraries\AIQuoteProcessor();
                    $result = $processor->processQuote($quoteId);

                    if ($result['success']) {
                        return redirect()->to('/admin/quote/' . $quoteId)->with('success',
                            count($uploadedImages) . ' image(s) uploaded successfully and AI processing completed! Quote amount: $' . number_format($result['quote_amount'], 2));
                    } else {
                        // Restore original status if processing failed
                        $quoteModel->update($quoteId, [
                            'status' => $originalStatus,
                            'updated_at' => date('Y-m-d H:i:s')
                        ]);
                        return redirect()->to('/admin/quote/' . $quoteId)->with('warning',
                            count($uploadedImages) . ' image(s) uploaded, but AI processing failed: ' . $result['error']);
                    }
                } catch (\Exception $e) {
                    log_message('error', 'AI processing error after image upload: ' . $e->getMessage());
                    return redirect()->to('/admin/quote/' . $quoteId)->with('warning',
                        count($uploadedImages) . ' image(s) uploaded, but AI processing encountered an error: ' . $e->getMessage());
                }
            } else {
                return redirect()->to('/admin/quote/' . $quoteId)->with('success',
                    count($uploadedImages) . ' image(s) uploaded successfully! You can now process with AI using the button below.');
            }

        } catch (\Exception $e) {
            log_message('error', 'Admin image upload error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error uploading images: ' . $e->getMessage());
        }
    }
}