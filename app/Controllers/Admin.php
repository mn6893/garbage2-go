<?php

namespace App\Controllers;

class Admin extends BaseController
{
    // Static admin credentials (defined in code)
    private const ADMIN_USERNAME = 'admin';
    private const ADMIN_PASSWORD = 'GarbageAdmin2024!';
    
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
        
        // Recent quotes
        $recentQuotes = $quoteBuilder->orderBy('created_at', 'DESC')->limit(5)->get()->getResultArray();
        
        // Recent contacts
        $recentContacts = $contactBuilder->orderBy('created_at', 'DESC')->limit(5)->get()->getResultArray();
        
        return view('admin/dashboard', [
            'quoteStats' => $quoteStats,
            'contactStats' => $contactStats,
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
        if ($status && in_array($status, ['pending', 'contacted', 'quoted', 'accepted', 'rejected', 'completed'])) {
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
            header('Location: ' . site_url('admin/login'));
            exit;
        }
        
        // Check session timeout (4 hours)
        $loginTime = session()->get('admin_login_time');
        if ($loginTime && (time() - $loginTime) > 14400) { // 4 hours
            session()->destroy();
            header('Location: ' . site_url('admin/login?timeout=1'));
            exit;
        }
    }
}