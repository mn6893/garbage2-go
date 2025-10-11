<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

/**
 * Test AI Services Command
 * 
 * Test the AI processing pipeline without processing real quotes
 */
class TestAIServices extends BaseCommand
{
    protected $group       = 'AI';
    protected $name        = 'ai:test';
    protected $description = 'Test AI services connectivity and functionality';
    
    protected $usage     = 'ai:test [options]';
    protected $arguments = [];
    protected $options   = [
        '--service' => 'Which service to test: vision, assessment, quote, all (default: all)',
        '--verbose' => 'Show detailed output'
    ];
    
    public function run(array $params)
    {
        $service = CLI::getOption('service', 'all');
        $verbose = CLI::getOption('verbose', false);
        
        CLI::write('AI Services Test Suite', 'green');
        CLI::write('==================');
        
        // Test configuration
        if (!$this->testConfiguration()) {
            CLI::write('❌ Configuration test failed', 'red');
            return;
        }
        CLI::write('✅ Configuration OK', 'green');
        
        // Test services
        if ($service === 'all' || $service === 'vision') {
            $this->testVisionService($verbose);
        }
        
        if ($service === 'all' || $service === 'assessment') {
            $this->testAssessmentService($verbose);
        }
        
        if ($service === 'all' || $service === 'quote') {
            $this->testQuoteService($verbose);
        }
        
        CLI::write('');
        CLI::write('AI Services test completed!', 'green');
    }
    
    private function testConfiguration(): bool
    {
        CLI::write('Testing configuration...');
        
        // Check if constants are defined
        $required = ['OPENAI_API_KEY', 'OPENAI_API_URL', 'OPENAI_MODEL'];
        
        foreach ($required as $constant) {
            if (!defined($constant) || empty(constant($constant))) {
                CLI::write("Missing or empty constant: {$constant}", 'red');
                return false;
            }
        }
        
        // Check API key format
        if (strlen(OPENAI_API_KEY) < 20) {
            CLI::write('OpenAI API key appears to be invalid (too short)', 'red');
            return false;
        }
        
        return true;
    }
    
    private function testVisionService(bool $verbose): void
    {
        CLI::write('Testing AI Vision Service...');
        
        try {
            $visionService = new \App\Services\AIVisionService();
            
            // Test with mock data (no actual API call)
            $mockImagePaths = ['/fake/image/path.jpg'];
            $mockContext = [
                'customer_location' => 'Toronto, ON',
                'service_address' => '123 Test Street',
                'description' => 'Testing AI services'
            ];
            
            if ($verbose) {
                CLI::write('Mock test data prepared', 'yellow');
                CLI::write('Image paths: ' . implode(', ', $mockImagePaths));
                CLI::write('Context: ' . json_encode($mockContext));
            }
            
            CLI::write('✅ AI Vision Service loaded successfully', 'green');
            
        } catch (\Exception $e) {
            CLI::write('❌ AI Vision Service test failed: ' . $e->getMessage(), 'red');
        }
    }
    
    private function testAssessmentService(bool $verbose): void
    {
        CLI::write('Testing Waste Assessment Service...');
        
        try {
            $assessmentService = new \App\Services\WasteAssessmentService();
            
            // Test with mock AI results
            $mockAIResults = [
                'success' => true,
                'wasteType' => 'household_mixed',
                'wasteTypes' => ['furniture', 'appliances', 'general_waste'],
                'volumeEstimate' => ['min' => 2, 'max' => 4, 'unit' => 'cubic_yards'],
                'hazardousItems' => [],
                'recommendations' => ['Standard pickup recommended']
            ];
            
            $mockMetadata = [
                'location' => 'Toronto, ON',
                'service_type' => 'residential'
            ];
            
            $result = $assessmentService->processAnalysis($mockAIResults, $mockMetadata);
            
            if ($verbose) {
                CLI::write('Assessment result: ' . json_encode($result, JSON_PRETTY_PRINT));
            }
            
            CLI::write('✅ Waste Assessment Service test passed', 'green');
            
        } catch (\Exception $e) {
            CLI::write('❌ Waste Assessment Service test failed: ' . $e->getMessage(), 'red');
        }
    }
    
    private function testQuoteService(bool $verbose): void
    {
        CLI::write('Testing Quote Generator Service...');
        
        try {
            $quoteService = new \App\Services\QuoteGeneratorService();
            
            // Test with mock assessment data
            $mockAssessmentData = [
                'success' => true,
                'wasteType' => 'household_mixed',
                'volumeEstimate' => ['min' => 2, 'max' => 4, 'unit' => 'cubic_yards'],
                'hazardousItems' => [],
                'specialRequirements' => [],
                'environmentalFees' => true
            ];
            
            $mockCustomerData = [
                'name' => 'Test Customer',
                'email' => 'test@example.com',
                'address' => '123 Test Street, Toronto, ON',
                'city' => 'Toronto',
                'postal_code' => 'M5V 3A8'
            ];
            
            $result = $quoteService->generateQuote($mockAssessmentData, $mockCustomerData);
            
            if ($verbose) {
                CLI::write('Quote result: ' . json_encode($result, JSON_PRETTY_PRINT));
            }
            
            if (isset($result['quote']['total_amount']) && $result['quote']['total_amount'] > 0) {
                CLI::write('✅ Quote Generator Service test passed - Amount: $' . number_format($result['quote']['total_amount'], 2), 'green');
            } else {
                CLI::write('⚠️  Quote Generator Service returned zero amount', 'yellow');
            }
            
        } catch (\Exception $e) {
            CLI::write('❌ Quote Generator Service test failed: ' . $e->getMessage(), 'red');
        }
    }
}