<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Models\QuoteModel;
use App\Libraries\AIQuoteProcessor;

/**
 * Process AI Quotes Command
 * 
 * Background command to process pending quote requests through AI analysis
 */
class ProcessAIQuotes extends BaseCommand
{
    protected $group       = 'AI';
    protected $name        = 'ai:process-quotes';
    protected $description = 'Process pending quote requests through AI analysis';
    
    protected $usage     = 'ai:process-quotes [options]';
    protected $arguments = [];
    protected $options   = [
        '--limit' => 'Maximum number of quotes to process in this run (default: 10)',
        '--force' => 'Process quotes even if they have been processed before'
    ];
    
    public function run(array $params)
    {
        $limit = (int) CLI::getOption('limit', 10);
        $force = CLI::getOption('force', false);
        
        CLI::write('Starting AI Quote Processing...', 'green');
        CLI::write('Processing limit: ' . $limit);
        
        $quoteModel = new QuoteModel();
        $processor = new AIQuoteProcessor();
        
        // Get pending quotes for AI processing
        $quotes = $this->getPendingQuotes($quoteModel, $limit, $force);
        
        if (empty($quotes)) {
            CLI::write('No quotes found for processing.', 'yellow');
            return;
        }
        
        CLI::write('Found ' . count($quotes) . ' quotes to process.');
        
        $processed = 0;
        $successful = 0;
        $failed = 0;
        
        foreach ($quotes as $quote) {
            CLI::write("Processing Quote #{$quote['id']} for {$quote['name']}...");
            
            $result = $processor->processQuote($quote['id']);
            
            if ($result['success']) {
                $successful++;
                CLI::write("✓ Quote #{$quote['id']} processed successfully. Amount: $" . number_format($result['quote_amount'], 2), 'green');
            } else {
                $failed++;
                CLI::write("✗ Quote #{$quote['id']} failed: " . $result['error'], 'red');
            }
            
            $processed++;
            
            // Small delay to prevent overwhelming the API
            sleep(1);
        }
        
        CLI::write('');
        CLI::write('Processing Summary:', 'yellow');
        CLI::write("Total Processed: {$processed}");
        CLI::write("Successful: {$successful}", 'green');
        CLI::write("Failed: {$failed}", 'red');
        CLI::write('AI Quote Processing completed.', 'green');
    }
    
    /**
     * Get quotes pending AI processing
     */
    private function getPendingQuotes(QuoteModel $quoteModel, int $limit, bool $force): array
    {
        $builder = $quoteModel->builder();
        
        if ($force) {
            // Process all quotes with images
            $builder->where('images IS NOT NULL')
                   ->where('images !=', '')
                   ->where('images !=', '[]');
        } else {
            // Only process quotes that haven't been AI processed yet and aren't currently processing
            $cutoffTime = date('Y-m-d H:i:s', time() - (30 * 60)); // 30 minutes ago
            
            $builder->where('status', 'pending')
                   ->where('images IS NOT NULL')
                   ->where('images !=', '')
                   ->where('images !=', '[]')
                   ->where('ai_processed_at IS NULL')
                   ->groupStart()
                       ->where('processing_lock IS NULL')
                       ->orWhere('ai_processing_started_at <', $cutoffTime)
                   ->groupEnd();
        }
        
        return $builder->orderBy('created_at', 'ASC')
                      ->limit($limit)
                      ->get()
                      ->getResultArray();
    }
}