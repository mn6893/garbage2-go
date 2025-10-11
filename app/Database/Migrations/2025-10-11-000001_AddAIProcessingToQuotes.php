<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddAIProcessingToQuotes extends Migration
{
    public function up()
    {
        $fields = [
            'ai_analysis' => [
                'type' => 'TEXT',
                'null' => true,
                'comment' => 'JSON data from AI image analysis'
            ],
            'waste_assessment' => [
                'type' => 'TEXT',
                'null' => true,
                'comment' => 'JSON data from waste assessment'
            ],
            'generated_quote' => [
                'type' => 'TEXT',
                'null' => true,
                'comment' => 'JSON data from generated quote'
            ],
            'estimated_amount' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => true,
                'comment' => 'AI-generated estimated total amount'
            ],
            'base_amount' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => true,
                'comment' => 'Base service cost'
            ],
            'additional_fees' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => true,
                'comment' => 'Additional fees (special handling, environmental, etc.)'
            ],
            'ai_processed_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'comment' => 'When AI processing was completed'
            ],
            'ai_confidence_score' => [
                'type' => 'DECIMAL',
                'constraint' => '3,2',
                'null' => true,
                'comment' => 'AI confidence score (0.00 to 1.00)'
            ]
        ];
        
        $this->forge->addColumn('quotes', $fields);
        
        // Add index for AI processing status queries
        $this->forge->addKey(['status', 'ai_processed_at'], false, false, 'idx_quotes_ai_status');
        $this->db->query('ALTER TABLE quotes ADD INDEX idx_quotes_ai_status (status, ai_processed_at)');
    }

    public function down()
    {
        $this->forge->dropColumn('quotes', [
            'ai_analysis',
            'waste_assessment', 
            'generated_quote',
            'estimated_amount',
            'base_amount',
            'additional_fees',
            'ai_processed_at',
            'ai_confidence_score'
        ]);
        
        $this->forge->dropKey('quotes', 'idx_quotes_ai_status');
    }
}