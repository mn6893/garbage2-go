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
        
        if (isset($files['junk_images'])) {
            $uploadPath = WRITEPATH . 'uploads/quote_images/';
            
            // Create directory if it doesn't exist
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
            
            foreach ($files['junk_images'] as $file) {
                if ($file->isValid() && !$file->hasMoved()) {
                    // Generate unique filename
                    $newName = $file->getRandomName();
                    $file->move($uploadPath, $newName);
                    $uploadedImages[] = $newName;
                }
            }
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
            $this->quoteModel->save($data);
            
            // Set success message
            session()->setFlashdata('success', 'Your quote request has been submitted successfully! We will contact you soon.');
            
            return redirect()->to('/quote/success');
        } catch (\Exception $e) {
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
}