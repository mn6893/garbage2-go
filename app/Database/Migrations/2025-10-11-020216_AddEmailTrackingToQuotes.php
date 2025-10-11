<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddEmailTrackingToQuotes extends Migration
{
    public function up()
    {
        $fields = [
            'email_sent_to_customer' => [
                'type' => 'BOOLEAN',
                'default' => false,
                'null' => false,
                'comment' => 'Whether quote email was successfully sent to customer'
            ],
            'email_sent_to_admin' => [
                'type' => 'BOOLEAN',
                'default' => false,
                'null' => false,
                'comment' => 'Whether admin notification email was sent'
            ],
            'customer_email_attempts' => [
                'type' => 'INT',
                'constraint' => 3,
                'default' => 0,
                'null' => false,
                'comment' => 'Number of attempts to send customer email'
            ],
            'admin_email_attempts' => [
                'type' => 'INT',
                'constraint' => 3,
                'default' => 0,
                'null' => false,
                'comment' => 'Number of attempts to send admin email'
            ],
            'last_email_attempt' => [
                'type' => 'DATETIME',
                'null' => true,
                'comment' => 'Last time email sending was attempted'
            ],
            'customer_email_error' => [
                'type' => 'TEXT',
                'null' => true,
                'comment' => 'Last error when sending customer email'
            ],
            'admin_email_error' => [
                'type' => 'TEXT',
                'null' => true,
                'comment' => 'Last error when sending admin email'
            ],
            'ai_processing_started_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'comment' => 'When AI processing was started (for duplicate prevention)'
            ],
            'processing_lock' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'comment' => 'Processing lock to prevent concurrent processing'
            ]
        ];
        
        $this->forge->addColumn('quotes', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('quotes', [
            'email_sent_to_customer',
            'email_sent_to_admin',
            'customer_email_attempts',
            'admin_email_attempts',
            'last_email_attempt',
            'customer_email_error',
            'admin_email_error',
            'ai_processing_started_at',
            'processing_lock'
        ]);
    }
}
