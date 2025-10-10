<?php

namespace App\Models;

use CodeIgniter\Model;

class QuoteModel extends Model
{
    protected $table = 'quotes';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'name',
        'email', 
        'phone',
        'address',
        'city',
        'description',
        'images',
        'status',
        'admin_notes',
        'quote_amount',
        'created_at',
        'updated_at'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    // Validation
    protected $validationRules = [
        'name' => 'required|min_length[2]|max_length[100]',
        'email' => 'required|valid_email|max_length[255]',
        'phone' => 'required|min_length[10]|max_length[20]',
        'address' => 'required|min_length[10]|max_length[500]',
        'description' => 'required|max_length[1000]',
        'status' => 'in_list[pending,contacted,quoted,accepted,rejected,completed]'
    ];

    protected $validationMessages = [
        'name' => [
            'required' => 'Name is required.',
            'min_length' => 'Name must be at least 2 characters long.',
            'max_length' => 'Name cannot exceed 100 characters.'
        ],
        'email' => [
            'required' => 'Email is required.',
            'valid_email' => 'Please enter a valid email address.',
            'max_length' => 'Email cannot exceed 255 characters.'
        ],
        'phone' => [
            'required' => 'Phone number is required.',
            'min_length' => 'Phone number must be at least 10 characters long.',
            'max_length' => 'Phone number cannot exceed 20 characters.'
        ],
        'address' => [
            'required' => 'Address is required.',
            'min_length' => 'Address must be at least 10 characters long.',
            'max_length' => 'Address cannot exceed 500 characters.'
        ],
        'description' => [
            'required' => 'Please tell us about your junk removal needs.',
            'max_length' => 'Description cannot exceed 1000 characters.'
        ],
        'status' => [
            'in_list' => 'Status must be one of: pending, contacted, quoted, accepted, rejected, completed.'
        ]
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert = [];
    protected $afterInsert = [];
    protected $beforeUpdate = [];
    protected $afterUpdate = [];
    protected $beforeFind = [];
    protected $afterFind = [];
    protected $beforeDelete = [];
    protected $afterDelete = [];

    /**
     * Get quotes by status
     */
    public function getQuotesByStatus(string $status): array
    {
        return $this->where('status', $status)
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }

    /**
     * Get recent quotes
     */
    public function getRecentQuotes(int $limit = 10): array
    {
        return $this->orderBy('created_at', 'DESC')
                    ->limit($limit)
                    ->findAll();
    }

    /**
     * Get quotes by date range
     */
    public function getQuotesByDateRange(string $startDate, string $endDate): array
    {
        return $this->where('created_at >=', $startDate)
                    ->where('created_at <=', $endDate)
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }

    /**
     * Update quote status
     */
    public function updateStatus(int $id, string $status, string $adminNotes = null): bool
    {
        $data = [
            'status' => $status,
            'updated_at' => date('Y-m-d H:i:s')
        ];

        if ($adminNotes !== null) {
            $data['admin_notes'] = $adminNotes;
        }

        return $this->update($id, $data);
    }

    /**
     * Add quote amount
     */
    public function addQuoteAmount(int $id, float $amount, string $adminNotes = null): bool
    {
        $data = [
            'quote_amount' => $amount,
            'status' => 'quoted',
            'updated_at' => date('Y-m-d H:i:s')
        ];

        if ($adminNotes !== null) {
            $data['admin_notes'] = $adminNotes;
        }

        return $this->update($id, $data);
    }

    /**
     * Search quotes
     */
    public function searchQuotes(string $searchTerm): array
    {
        return $this->groupStart()
                    ->like('name', $searchTerm)
                    ->orLike('email', $searchTerm)
                    ->orLike('phone', $searchTerm)
                    ->orLike('address', $searchTerm)
                    ->orLike('service_type', $searchTerm)
                    ->groupEnd()
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }

    /**
     * Get quote statistics
     */
    public function getQuoteStats(): array
    {
        $stats = [];
        
        // Total quotes
        $stats['total'] = $this->countAll();
        
        // Quotes by status
        $statuses = ['pending', 'contacted', 'quoted', 'accepted', 'rejected', 'completed'];
        foreach ($statuses as $status) {
            $stats[$status] = $this->where('status', $status)->countAllResults(false);
        }
        
        // Quotes this month
        $firstDayOfMonth = date('Y-m-01 00:00:00');
        $stats['this_month'] = $this->where('created_at >=', $firstDayOfMonth)->countAllResults(false);
        
        // Quotes today
        $today = date('Y-m-d 00:00:00');
        $stats['today'] = $this->where('created_at >=', $today)->countAllResults(false);
        
        return $stats;
    }

    /**
     * Get images for a quote
     */
    public function getQuoteImages(int $id): array
    {
        $quote = $this->find($id);
        
        if (!$quote || empty($quote['images'])) {
            return [];
        }
        
        return json_decode($quote['images'], true) ?: [];
    }

    /**
     * Get image URLs for a quote
     */
    public function getQuoteImageUrls(int $id): array
    {
        $imageNames = $this->getQuoteImages($id);
        $urls = [];
        
        foreach ($imageNames as $imageName) {
            $urls[] = base_url('writable/uploads/quote_images/' . $imageName);
        }
        
        return $urls;
    }

    /**
     * Delete quote images from filesystem
     */
    public function deleteQuoteImages(int $id): bool
    {
        $imageNames = $this->getQuoteImages($id);
        $uploadPath = WRITEPATH . 'uploads/quote_images/';
        
        foreach ($imageNames as $imageName) {
            $filePath = $uploadPath . $imageName;
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }
        
        return true;
    }
}