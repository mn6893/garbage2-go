<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPreferredDateTimeToQuotes extends Migration
{
    public function up()
    {
        $fields = [
            'preferred_date' => [
                'type' => 'DATE',
                'null' => true,
                'after' => 'description',
            ],
            'preferred_time' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
                'after' => 'preferred_date',
            ],
        ];

        $this->forge->addColumn('quotes', $fields);

        // Add index on preferred_date for faster queries
        $this->db->query('ALTER TABLE quotes ADD INDEX idx_preferred_date (preferred_date)');
    }

    public function down()
    {
        $this->forge->dropColumn('quotes', ['preferred_date', 'preferred_time']);
    }
}
