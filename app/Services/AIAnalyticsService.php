<?php

namespace App\Services;

use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Log\Logger;

/**
 * AI Analytics Service
 * Handles performance metrics, cost tracking, and analytics for AI processing
 */
class AIAnalyticsService
{
    private ConnectionInterface $db;
    private Logger $logger;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->logger = service('logger');
    }

    /**
     * Get dashboard summary data
     */
    public function getDashboardSummary(string $timeframe = '24h'): array
    {
        try {
            $timeCondition = $this->getTimeCondition($timeframe);

            // Get processing summary
            $processingSummary = $this->getProcessingSummary($timeCondition);

            // Get performance metrics
            $performanceMetrics = $this->getPerformanceMetrics($timeCondition);

            // Get cost summary
            $costSummary = $this->getCostSummary($timeCondition);

            return [
                'summary' => [
                    'total_processed' => $processingSummary['total'],
                    'success_rate' => $processingSummary['success_rate'],
                    'avg_processing_time' => $performanceMetrics['avg_processing_time'],
                    'total_cost' => $costSummary['total_cost']
                ],
                'charts' => [
                    'status' => $this->getStatusDistribution($timeCondition),
                    'performance' => $this->getPerformanceChartData($timeCondition),
                    'costs' => $this->getCostChartData($timeCondition)
                ],
                'recent_activity' => $this->getRecentActivity(10),
                'system_status' => $this->getSystemStatus()
            ];

        } catch (\Exception $e) {
            $this->logger->error('Error getting dashboard summary: ' . $e->getMessage());
            return $this->getEmptyDashboardData();
        }
    }

    /**
     * Get processing summary statistics
     */
    private function getProcessingSummary(string $timeCondition): array
    {
        $query = "
            SELECT
                COUNT(*) as total,
                SUM(CASE WHEN ai_status = 'completed' THEN 1 ELSE 0 END) as completed,
                SUM(CASE WHEN ai_status = 'failed' THEN 1 ELSE 0 END) as failed,
                SUM(CASE WHEN ai_status = 'processing' THEN 1 ELSE 0 END) as processing,
                SUM(CASE WHEN ai_status = 'pending' THEN 1 ELSE 0 END) as pending
            FROM quotations
            WHERE ai_processed = 1 {$timeCondition}
        ";

        $result = $this->db->query($query)->getRowArray();

        $total = $result['total'] ?? 0;
        $completed = $result['completed'] ?? 0;

        $successRate = $total > 0 ? round(($completed / $total) * 100, 2) : 0;

        return [
            'total' => $total,
            'completed' => $completed,
            'failed' => $result['failed'] ?? 0,
            'processing' => $result['processing'] ?? 0,
            'pending' => $result['pending'] ?? 0,
            'success_rate' => $successRate
        ];
    }

    /**
     * Get performance metrics
     */
    private function getPerformanceMetrics(string $timeCondition): array
    {
        $query = "
            SELECT
                AVG(ai_processing_time) as avg_processing_time,
                AVG(ai_confidence_score) as avg_confidence,
                MIN(ai_processing_time) as min_processing_time,
                MAX(ai_processing_time) as max_processing_time,
                MIN(ai_confidence_score) as min_confidence,
                MAX(ai_confidence_score) as max_confidence
            FROM quotations
            WHERE ai_processed = 1
            AND ai_status = 'completed'
            {$timeCondition}
        ";

        $result = $this->db->query($query)->getRowArray();

        return [
            'avg_processing_time' => round($result['avg_processing_time'] ?? 0, 2),
            'avg_confidence' => round($result['avg_confidence'] ?? 0, 2),
            'min_processing_time' => round($result['min_processing_time'] ?? 0, 2),
            'max_processing_time' => round($result['max_processing_time'] ?? 0, 2),
            'min_confidence' => round($result['min_confidence'] ?? 0, 2),
            'max_confidence' => round($result['max_confidence'] ?? 0, 2)
        ];
    }

    /**
     * Get cost summary
     */
    private function getCostSummary(string $timeCondition): array
    {
        $query = "
            SELECT
                SUM(ai_cost) as total_cost,
                AVG(ai_cost) as avg_cost,
                COUNT(*) as total_requests
            FROM quotations
            WHERE ai_processed = 1
            AND ai_cost > 0
            {$timeCondition}
        ";

        $result = $this->db->query($query)->getRowArray();

        return [
            'total_cost' => round($result['total_cost'] ?? 0, 4),
            'avg_cost' => round($result['avg_cost'] ?? 0, 4),
            'total_requests' => $result['total_requests'] ?? 0
        ];
    }

    /**
     * Get status distribution for chart
     */
    private function getStatusDistribution(string $timeCondition): array
    {
        $query = "
            SELECT
                ai_status,
                COUNT(*) as count
            FROM quotations
            WHERE ai_processed = 1 {$timeCondition}
            GROUP BY ai_status
        ";

        $results = $this->db->query($query)->getResultArray();

        $distribution = [
            'completed' => 0,
            'processing' => 0,
            'failed' => 0,
            'pending' => 0
        ];

        foreach ($results as $row) {
            if (isset($distribution[$row['ai_status']])) {
                $distribution[$row['ai_status']] = (int)$row['count'];
            }
        }

        return $distribution;
    }

    /**
     * Get performance chart data
     */
    private function getPerformanceChartData(string $timeCondition): array
    {
        $query = "
            SELECT
                DATE_FORMAT(created_at, '%H:00') as hour_label,
                AVG(ai_processing_time) as avg_processing_time,
                AVG(ai_confidence_score) as avg_confidence
            FROM quotations
            WHERE ai_processed = 1
            AND ai_status = 'completed'
            {$timeCondition}
            GROUP BY DATE_FORMAT(created_at, '%Y-%m-%d %H')
            ORDER BY created_at ASC
            LIMIT 24
        ";

        $results = $this->db->query($query)->getResultArray();

        $labels = [];
        $processingTimes = [];
        $confidenceScores = [];

        foreach ($results as $row) {
            $labels[] = $row['hour_label'];
            $processingTimes[] = round($row['avg_processing_time'], 2);
            $confidenceScores[] = round($row['avg_confidence'], 2);
        }

        return [
            'labels' => $labels,
            'processing_times' => $processingTimes,
            'confidence_scores' => $confidenceScores
        ];
    }

    /**
     * Get cost chart data
     */
    private function getCostChartData(string $timeCondition): array
    {
        $query = "
            SELECT
                DATE_FORMAT(created_at, '%m-%d') as date_label,
                SUM(ai_cost) as daily_cost
            FROM quotations
            WHERE ai_processed = 1
            AND ai_cost > 0
            {$timeCondition}
            GROUP BY DATE_FORMAT(created_at, '%Y-%m-%d')
            ORDER BY created_at ASC
            LIMIT 30
        ";

        $results = $this->db->query($query)->getResultArray();

        $labels = [];
        $values = [];

        foreach ($results as $row) {
            $labels[] = $row['date_label'];
            $values[] = round($row['daily_cost'], 4);
        }

        return [
            'labels' => $labels,
            'values' => $values
        ];
    }

    /**
     * Get recent activity
     */
    private function getRecentActivity(int $limit = 10): array
    {
        $query = "
            SELECT
                id,
                customer_name,
                ai_status,
                ai_confidence_score,
                ai_processing_time,
                total_amount,
                created_at,
                updated_at
            FROM quotations
            WHERE ai_processed = 1
            ORDER BY updated_at DESC
            LIMIT {$limit}
        ";

        $results = $this->db->query($query)->getResultArray();
        $activities = [];

        foreach ($results as $row) {
            $activities[] = [
                'title' => "Quotation #{$row['id']} - {$row['customer_name']}",
                'description' => $this->getActivityDescription($row),
                'status' => $row['ai_status'],
                'timestamp' => date('M j, Y g:i A', strtotime($row['updated_at']))
            ];
        }

        return $activities;
    }

    /**
     * Get activity description
     */
    private function getActivityDescription(array $row): string
    {
        $status = $row['ai_status'];
        $confidence = $row['ai_confidence_score'];
        $processingTime = $row['ai_processing_time'];
        $amount = $row['total_amount'];

        switch ($status) {
            case 'completed':
                return "AI analysis completed in {$processingTime}s with {$confidence}% confidence. Quote: \${$amount}";
            case 'processing':
                return "AI analysis in progress...";
            case 'failed':
                return "AI analysis failed after {$processingTime}s";
            case 'pending':
                return "Waiting for AI analysis";
            default:
                return "Status unknown";
        }
    }

    /**
     * Get system status
     */
    private function getSystemStatus(): array
    {
        try {
            // Check API connectivity
            $apiStatus = $this->checkOpenAIAPI() ? 'healthy' : 'error';

            // Check database
            $databaseStatus = $this->checkDatabase() ? 'healthy' : 'error';

            // Check storage
            $storageStatus = $this->checkStorage() ? 'healthy' : 'error';

            // Get queue size
            $queueSize = $this->getQueueSize();

            return [
                'api_status' => $apiStatus,
                'database_status' => $databaseStatus,
                'storage_status' => $storageStatus,
                'queue_size' => $queueSize
            ];

        } catch (\Exception $e) {
            $this->logger->error('Error checking system status: ' . $e->getMessage());
            return [
                'api_status' => 'error',
                'database_status' => 'error',
                'storage_status' => 'error',
                'queue_size' => 0
            ];
        }
    }

    /**
     * Check OpenAI API connectivity
     */
    private function checkOpenAIAPI(): bool
    {
        try {
            // Simple ping to OpenAI API
            $ch = curl_init('https://api.openai.com/v1/models');
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . OPENAI_API_KEY,
                'Content-Type: application/json'
            ]);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            return $httpCode === 200;

        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Check database connectivity
     */
    private function checkDatabase(): bool
    {
        try {
            $this->db->query('SELECT 1')->getRow();
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Check storage availability
     */
    private function checkStorage(): bool
    {
        try {
            $uploadPath = WRITEPATH . 'uploads/';
            return is_dir($uploadPath) && is_writable($uploadPath);
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Get processing queue size
     */
    private function getQueueSize(): int
    {
        try {
            $query = "SELECT COUNT(*) as count FROM quotations WHERE ai_status IN ('pending', 'processing')";
            $result = $this->db->query($query)->getRowArray();
            return (int)($result['count'] ?? 0);
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * Log AI processing metrics
     */
    public function logProcessingMetrics(array $data): bool
    {
        try {
            $logData = [
                'quotation_id' => $data['quotation_id'],
                'processing_time' => $data['processing_time'],
                'confidence_score' => $data['confidence_score'],
                'cost' => $data['cost'],
                'status' => $data['status'],
                'error_message' => $data['error_message'] ?? null,
                'created_at' => date('Y-m-d H:i:s')
            ];

            return $this->db->table('ai_processing_logs')->insert($logData);

        } catch (\Exception $e) {
            $this->logger->error('Error logging processing metrics: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get confidence score distribution
     */
    public function getConfidenceDistribution(string $timeframe = '24h'): array
    {
        try {
            $timeCondition = $this->getTimeCondition($timeframe);

            $query = "
                SELECT
                    CASE
                        WHEN ai_confidence_score >= 90 THEN '90-100%'
                        WHEN ai_confidence_score >= 80 THEN '80-89%'
                        WHEN ai_confidence_score >= 70 THEN '70-79%'
                        WHEN ai_confidence_score >= 60 THEN '60-69%'
                        ELSE 'Below 60%'
                    END as confidence_range,
                    COUNT(*) as count
                FROM quotations
                WHERE ai_processed = 1
                AND ai_status = 'completed'
                {$timeCondition}
                GROUP BY confidence_range
                ORDER BY confidence_range DESC
            ";

            $results = $this->db->query($query)->getResultArray();

            $distribution = [];
            foreach ($results as $row) {
                $distribution[$row['confidence_range']] = (int)$row['count'];
            }

            return $distribution;

        } catch (\Exception $e) {
            $this->logger->error('Error getting confidence distribution: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Apply manual override
     */
    public function applyManualOverride(int $quotationId, array $overrideData): bool
    {
        try {
            $this->db->transStart();

            // Update quotation
            $updateData = [
                'total_amount' => $overrideData['new_amount'],
                'ai_status' => 'overridden',
                'manual_override' => 1,
                'override_reason' => $overrideData['reason'],
                'override_notes' => $overrideData['notes'],
                'override_by' => session('admin_id') ?? 'system',
                'override_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];

            $this->db->table('quotations')
                     ->where('id', $quotationId)
                     ->update($updateData);

            // Log the override
            $logData = [
                'quotation_id' => $quotationId,
                'action' => 'manual_override',
                'old_amount' => $overrideData['old_amount'] ?? null,
                'new_amount' => $overrideData['new_amount'],
                'reason' => $overrideData['reason'],
                'notes' => $overrideData['notes'],
                'performed_by' => session('admin_id') ?? 'system',
                'created_at' => date('Y-m-d H:i:s')
            ];

            $this->db->table('ai_override_logs')->insert($logData);

            $this->db->transComplete();

            return $this->db->transStatus();

        } catch (\Exception $e) {
            $this->logger->error('Error applying manual override: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get time condition for SQL queries
     */
    private function getTimeCondition(string $timeframe): string
    {
        $conditions = [
            '1h' => "AND created_at >= DATE_SUB(NOW(), INTERVAL 1 HOUR)",
            '24h' => "AND created_at >= DATE_SUB(NOW(), INTERVAL 24 HOUR)",
            '7d' => "AND created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)",
            '30d' => "AND created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)",
            '90d' => "AND created_at >= DATE_SUB(NOW(), INTERVAL 90 DAY)"
        ];

        return $conditions[$timeframe] ?? $conditions['24h'];
    }

    /**
     * Get empty dashboard data structure
     */
    private function getEmptyDashboardData(): array
    {
        return [
            'summary' => [
                'total_processed' => 0,
                'success_rate' => 0,
                'avg_processing_time' => 0,
                'total_cost' => 0
            ],
            'charts' => [
                'status' => ['completed' => 0, 'processing' => 0, 'failed' => 0, 'pending' => 0],
                'performance' => ['labels' => [], 'processing_times' => [], 'confidence_scores' => []],
                'costs' => ['labels' => [], 'values' => []]
            ],
            'recent_activity' => [],
            'system_status' => [
                'api_status' => 'unknown',
                'database_status' => 'unknown',
                'storage_status' => 'unknown',
                'queue_size' => 0
            ]
        ];
    }
}