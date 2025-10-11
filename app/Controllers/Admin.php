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
        
        $data = [
            'status' => $status,
            'admin_notes' => $notes,
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        if ($builder->where('id', $id)->update($data)) {
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
            $processor = new \App\Libraries\AIQuoteProcessor();
            $result = $processor->processQuote($id);
            
            if ($result['success']) {
                return redirect()->to('/admin/quote/' . $id)->with('success', 
                    'AI processing completed successfully. Quote amount: $' . number_format($result['quote_amount'], 2));
            } else {
                return redirect()->to('/admin/quote/' . $id)->with('error', 
                    'AI processing failed: ' . $result['error']);
            }
        } catch (\Exception $e) {
            return redirect()->to('/admin/quote/' . $id)->with('error', 
                'Error triggering AI processing: ' . $e->getMessage());
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
}