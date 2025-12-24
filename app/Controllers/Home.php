<?php

namespace App\Controllers;

class Home extends BaseController
{
    /**
     * Get visitor information for logging
     */
    private function getVisitorInfo(): array
    {
        $request = \Config\Services::request();
        return [
            'ip' => $request->getIPAddress(),
            'user_agent' => $request->getUserAgent()->getAgentString(),
            'referrer' => $request->getHeaderLine('Referer') ?: 'Direct',
            'timestamp' => date('Y-m-d H:i:s')
        ];
    }

    /**
     * Log page visit
     */
    private function logPageVisit(string $page): void
    {
        $visitor = $this->getVisitorInfo();
        log_message('info', "[PAGE_VISIT] Page: {$page} | IP: {$visitor['ip']} | User-Agent: {$visitor['user_agent']} | Referrer: {$visitor['referrer']}");
    }

    /**
     * Log form submission
     */
    private function logFormSubmission(string $form, string $email, bool $success, ?string $details = null): void
    {
        $visitor = $this->getVisitorInfo();
        $status = $success ? 'SUCCESS' : 'FAILED';
        $detailStr = $details ? " | Details: {$details}" : '';
        log_message('info', "[FORM_SUBMIT] Form: {$form} | Status: {$status} | Email: {$email} | IP: {$visitor['ip']} | User-Agent: {$visitor['user_agent']}{$detailStr}");
    }

    public function index(): string
    {
        $this->logPageVisit('home');
        return view('index');
    }

    public function about(): string
    {
        return view('about');
    }

    public function services(): string
    {
        return view('services');
    }

    public function contact(): string
    {
        return view('contact');
    }

    /**
     * Handle contact form submission
     */
    public function submitContact()
    {
        // Set validation rules for contact form
        $validationRules = [
            'name' => [
                'rules' => 'required|min_length[2]|max_length[100]',
                'errors' => [
                    'required' => 'First name is required.',
                    'min_length' => 'First name must be at least 2 characters long.',
                    'max_length' => 'First name cannot exceed 100 characters.'
                ]
            ],
            'surname' => [
                'rules' => 'required|min_length[2]|max_length[100]',
                'errors' => [
                    'required' => 'Last name is required.',
                    'min_length' => 'Last name must be at least 2 characters long.',
                    'max_length' => 'Last name cannot exceed 100 characters.'
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
                'rules' => 'max_length[20]',
                'errors' => [
                    'max_length' => 'Phone number cannot exceed 20 characters.'
                ]
            ],
            'location' => [
                'rules' => 'max_length[255]',
                'errors' => [
                    'max_length' => 'Location cannot exceed 255 characters.'
                ]
            ],
            'service_type' => [
                'rules' => 'max_length[50]',
                'errors' => [
                    'max_length' => 'Service type cannot exceed 50 characters.'
                ]
            ],
            'message' => [
                'rules' => 'required|min_length[10]|max_length[1000]',
                'errors' => [
                    'required' => 'Message is required.',
                    'min_length' => 'Message must be at least 10 characters long.',
                    'max_length' => 'Message cannot exceed 1000 characters.'
                ]
            ]
        ];

        // Validate the input
        if (!$this->validate($validationRules)) {
            return view('contact', [
                'validation' => $this->validator,
                'input' => $this->request->getPost()
            ]);
        }

        // Prepare contact data
        $contactData = [
            'name' => $this->request->getPost('name'),
            'surname' => $this->request->getPost('surname'),
            'email' => $this->request->getPost('email'),
            'phone' => $this->request->getPost('phone'),
            'location' => $this->request->getPost('location'),
            'service_type' => $this->request->getPost('service_type'),
            'message' => $this->request->getPost('message'),
            'created_at' => date('Y-m-d H:i:s')
        ];

        try {
            // Save to database (you can create a ContactModel if needed)
            $db = \Config\Database::connect();
            $builder = $db->table('contacts');
            $builder->insert($contactData);

            // Log successful contact form submission
            $this->logFormSubmission('contact', $contactData['email'], true);

            // Send email notification (optional)
            $this->sendContactEmail($contactData);

            // Set success message
            session()->setFlashdata('success', 'Thank you for contacting us! We will get back to you within 24 hours.');

            return redirect()->to('/contact#contact-success');

        } catch (\Exception $e) {
            // Log failed contact form submission
            $this->logFormSubmission('contact', $this->request->getPost('email') ?? 'unknown', false, $e->getMessage());

            // Set error message
            session()->setFlashdata('error', 'There was an error processing your request. Please try again or call us directly.');
            
            return view('contact', [
                'input' => $this->request->getPost(),
                'error' => 'Failed to submit contact form. Please try again.'
            ]);
        }
    }

    /**
     * Send contact email notification
     */
    private function sendContactEmail($data)
    {
        // Load email service
        $email = \Config\Services::email();
        
        // Email configuration (you can set these in app/Config/Email.php)
        $email->setFrom('info@garbagetogo.com', 'GarbageToGo Contact Form');
        $email->setTo('info@garbagetogo.ca');
        $email->setCC('garbage2go.ca@gmail.com');
        $email->setSubject('New Contact Form Submission - ' . $data['service_type']);
        
        // Create email message
        $message = "
        <h3>New Contact Form Submission</h3>
        <p><strong>Name:</strong> {$data['name']} {$data['surname']}</p>
        <p><strong>Email:</strong> {$data['email']}</p>
        <p><strong>Phone:</strong> {$data['phone']}</p>
        <p><strong>Location:</strong> {$data['location']}</p>
        <p><strong>Service Type:</strong> {$data['service_type']}</p>
        <p><strong>Message:</strong></p>
        <p>{$data['message']}</p>
        <p><strong>Submitted:</strong> {$data['created_at']}</p>
        ";
        $email->setMailType('html');
        $email->setMessage($message);
        
        // Send email (handle errors gracefully)
        try {
            $email->send();
        } catch (\Exception $e) {
            // Log error but don't fail the contact submission
            log_message('error', 'Contact email failed to send: ' . $e->getMessage());
        }
    }
}
