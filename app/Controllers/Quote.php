<?php

namespace App\Controllers;

use App\Models\QuoteModel;

class Quote extends BaseController
{
    protected $quoteModel;

    public function __construct()
    {
        $this->quoteModel = new QuoteModel();
    }

    /**
     * Display the quote request form
     */
    public function index(): string
    {
        return view('quote/request_form');
    }

    /**
     * Process the quote request submission
     */
    public function submit()
    {
        // Set validation rules
        $validationRules = [
            'name' => [
                'rules' => 'required|min_length[2]|max_length[100]',
                'errors' => [
                    'required' => 'Name is required.',
                    'min_length' => 'Name must be at least 2 characters long.',
                    'max_length' => 'Name cannot exceed 100 characters.'
                ]
            ],
            'email' => [
                'rules' => 'required|valid_email|max_length[255]',
                'errors' => [
                    'required' => 'Email is required.',
                    'valid_email' => 'Please enter a valid email address.',
                    'max_length' => 'Email cannot exceed 255 characters.'
                ]
            ],
            'phone' => [
                'rules' => 'required|min_length[10]|max_length[20]',
                'errors' => [
                    'required' => 'Phone number is required.',
                    'min_length' => 'Phone number must be at least 10 characters long.',
                    'max_length' => 'Phone number cannot exceed 20 characters.'
                ]
            ],
            'address' => [
                'rules' => 'required|min_length[10]|max_length[500]',
                'errors' => [
                    'required' => 'Address is required.',
                    'min_length' => 'Address must be at least 10 characters long.',
                    'max_length' => 'Address cannot exceed 500 characters.'
                ]
            ],
            'description' => [
                'rules' => 'required|max_length[1000]',
                'errors' => [
                    'required' => 'Please tell us about your junk removal needs.',
                    'max_length' => 'Description cannot exceed 1000 characters.'
                ]
            ],
            'city' => [
                'rules' => 'max_length[100]',
                'errors' => [
                    'max_length' => 'City cannot exceed 100 characters.'
                ]
            ],
            'junk_images' => [
                'rules' => 'max_size[junk_images,5120]|is_image[junk_images]|mime_in[junk_images,image/jpg,image/jpeg,image/png]',
                'errors' => [
                    'max_size' => 'Each image must be less than 5MB.',
                    'is_image' => 'Please upload only image files.',
                    'mime_in' => 'Only JPG, JPEG, and PNG files are allowed.'
                ]
            ]
        ];

        // Validate the input
        if (!$this->validate($validationRules)) {
            // Check if this is an AJAX request
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Please correct the errors below.',
                    'errors' => $this->validator->getErrors()
                ]);
            }
            
            // Return to the form that was submitted from
            $referer = $this->request->getHeaderLine('Referer');
            $isFromHome = strpos($referer, 'index') !== false || strpos($referer, '/') === strlen($referer) - 1;
            
            if ($isFromHome) {
                return view('index', [
                    'validation' => $this->validator,
                    'input' => $this->request->getPost()
                ]);
            } else {
                return view('quote/request_form', [
                    'validation' => $this->validator,
                    'input' => $this->request->getPost()
                ]);
            }
        }

        // Handle file uploads
        $uploadedImages = [];
        $files = $this->request->getFiles();
        
        // Debug: Log file information
        log_message('info', 'Files received: ' . print_r($files, true));
        
        if (isset($files['junk_images'])) {
            $uploadPath = WRITEPATH . 'uploads/quote_images/';
            
            // Create directory if it doesn't exist
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
            
            // Check if it's a single file or array of files
            $imageFiles = $files['junk_images'];
            if (!is_array($imageFiles)) {
                $imageFiles = [$imageFiles];
            }
            
            foreach ($imageFiles as $file) {
                if ($file->isValid() && !$file->hasMoved()) {
                    // Generate unique filename
                    $newName = $file->getRandomName();
                    
                    try {
                        $file->move($uploadPath, $newName);
                        $uploadedImages[] = $newName;
                        log_message('info', 'Successfully uploaded file: ' . $newName);
                    } catch (\Exception $e) {
                        log_message('error', 'Error uploading file: ' . $e->getMessage());
                    }
                } else {
                    log_message('error', 'Invalid file or already moved: ' . ($file->getError() ?? 'Unknown error'));
                }
            }
        } else {
            log_message('info', 'No junk_images found in files array');
        }

        // Prepare data for insertion
        $data = [
            'name' => $this->request->getPost('name'),
            'email' => $this->request->getPost('email'),
            'phone' => $this->request->getPost('phone'),
            'address' => $this->request->getPost('address'),
            'city' => $this->request->getPost('city'),
            'description' => $this->request->getPost('description'),
            'images' => !empty($uploadedImages) ? json_encode($uploadedImages) : null,
            'status' => 'pending',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        // Save the quote request
        try {
            $quoteId = $this->quoteModel->insert($data);
            
            // Trigger AI processing if images were uploaded
            if (!empty($uploadedImages)) {
                $successMessage = 'Your quote request with images has been submitted successfully! We will review your images and contact you soon with a detailed quote.';
            } else {
                $successMessage = 'Your quote request has been submitted successfully! We will contact you soon with a detailed quote.';
            }
            
            // Check if this is an AJAX request
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => $successMessage,
                    'quote_id' => $quoteId
                ]);
            }
            
            session()->setFlashdata('success', $successMessage);
            return redirect()->to('/quote/success');
        } catch (\Exception $e) {
            log_message('error', 'Quote submission error: ' . $e->getMessage());
            
            // Check if this is an AJAX request
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'There was an error processing your request. Please try again.',
                    'error' => $e->getMessage()
                ]);
            }
            
            // Set error message
            session()->setFlashdata('error', 'There was an error processing your request. Please try again.');
            
            // Return to the form that was submitted from
            $referer = $this->request->getHeaderLine('Referer');
            $isFromHome = strpos($referer, 'index') !== false || strpos($referer, '/') === strlen($referer) - 1;
            
            if ($isFromHome) {
                return view('index', [
                    'input' => $this->request->getPost(),
                    'error' => 'Failed to submit quote request. Please try again.'
                ]);
            } else {
                return view('quote/request_form', [
                    'input' => $this->request->getPost(),
                    'error' => 'Failed to submit quote request. Please try again.'
                ]);
            }
        }
    }

    /**
     * Display success page after quote submission
     */
    public function success(): string
    {
        return view('quote/success');
    }

    /**
     * Serve uploaded quote images
     */
    public function serveImage($filename)
    {
        $imagePath = WRITEPATH . 'uploads/quote_images/' . $filename;
        
        if (!file_exists($imagePath)) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Image not found');
        }
        
        $mimeType = mime_content_type($imagePath);
        
        return $this->response
            ->setHeader('Content-Type', $mimeType)
            ->setHeader('Content-Length', filesize($imagePath))
            ->setBody(file_get_contents($imagePath));
    }
    
    /**
     * Trigger AI processing for a quote (can be immediate or queued)
     */
    private function triggerAIProcessing(int $quoteId): void
    {
        try {
            // Option 1: Immediate processing (for real-time results)
            if (ENVIRONMENT === 'development' || $this->shouldProcessImmediately()) {
                // Run in background using exec (non-blocking)
                if (function_exists('exec') && !$this->isWindows()) {
                    $command = "cd " . ROOTPATH . " && php spark ai:process-quotes --limit=1 --force > /dev/null 2>&1 &";
                    exec($command);
                } else {
                    // Fallback: process immediately (blocking) - for Windows or when exec is disabled
                    $processor = new \App\Libraries\AIQuoteProcessor();
                    $processor->processQuote($quoteId);
                }
            } else {
                // Option 2: Add to queue for batch processing
                $this->queueForProcessing($quoteId);
            }
        } catch (\Exception $e) {
            // Log error but don't fail the quote submission
            log_message('error', 'Failed to trigger AI processing for quote ' . $quoteId . ': ' . $e->getMessage());
        }
    }
    
    /**
     * Check if we should process immediately or queue for later
     */
    private function shouldProcessImmediately(): bool
    {
        // Process immediately during business hours or if queue is not available
        $hour = (int) date('H');
        return ($hour >= 8 && $hour <= 20); // 8 AM to 8 PM
    }
    
    /**
     * Queue quote for batch processing
     */
    private function queueForProcessing(int $quoteId): void
    {
        // Update quote status to indicate it's queued for AI processing
        $this->quoteModel->update($quoteId, [
            'status' => 'pending',
            'admin_notes' => 'Queued for AI analysis',
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        
        log_message('info', "Quote {$quoteId} queued for AI processing");
    }
    
    /**
     * Check if running on Windows
     */
    private function isWindows(): bool
    {
        return strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';
    }
}