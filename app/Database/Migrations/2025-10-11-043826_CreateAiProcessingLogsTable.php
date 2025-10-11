<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAiProcessingLogsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'quotation_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => false,
            ],
            'processing_time' => [
                'type' => 'DECIMAL',
                'constraint' => '10,4',
                'null' => true,
                'default' => 0,
            ],
            'confidence_score' => [
                'type' => 'DECIMAL',
                'constraint' => '5,4',
                'null' => true,
                'default' => 0,
            ],
            'cost' => [
                'type' => 'DECIMAL',
                'constraint' => '10,4',
                'null' => true,
                'default' => 0,
            ],
            'status' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => false,
                'default' => 'unknown',
            ],
            'error_message' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'tokens_used' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
                'default' => 0,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
        ]);
        
        $this->forge->addKey('id', true);
        $this->forge->addKey('quotation_id');
        $this->forge->addKey('status');
        $this->forge->addKey('created_at');
        
        $this->forge->createTable('ai_processing_logs');
    }

    public function down()
    {
        $this->forge->dropTable('ai_processing_logs');
    }
}
