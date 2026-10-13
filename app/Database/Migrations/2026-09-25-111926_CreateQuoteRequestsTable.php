<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateQuoteRequestsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'             => ['type' => 'INT', 'constraint' => 10, 'unsigned' => true, 'auto_increment' => true],
            'first_name'     => ['type' => 'VARCHAR', 'constraint' => 80],
            'last_name'      => ['type' => 'VARCHAR', 'constraint' => 80],
            'email'          => ['type' => 'VARCHAR', 'constraint' => 190],
            'phone'          => ['type' => 'VARCHAR', 'constraint' => 40, 'null' => true],
            'whatsapp'       => ['type' => 'VARCHAR', 'constraint' => 40, 'null' => true],
            'company'        => ['type' => 'VARCHAR', 'constraint' => 150, 'null' => true],
            'service_id'     => ['type' => 'INT', 'constraint' => 10, 'unsigned' => true, 'null' => true],
            'service_needed' => ['type' => 'VARCHAR', 'constraint' => 150, 'null' => true],
            'budget'         => ['type' => 'VARCHAR', 'constraint' => 80, 'null' => true],
            'description'    => ['type' => 'TEXT'],
            'ip_address'     => ['type' => 'VARCHAR', 'constraint' => 45, 'null' => true],
            'status'         => ['type' => 'ENUM', 'constraint' => ['new', 'contacted', 'in_progress', 'closed'], 'default' => 'new'],
            'is_read'        => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('status', false, false, 'idx_quote_status');
        $this->forge->addKey('created_at', false, false, 'idx_quote_created');
        $this->forge->addKey('service_id', false, false, 'fk_quote_service');
        $this->forge->addForeignKey('service_id', 'services', 'id', 'SET NULL', 'CASCADE', 'fk_quote_service');
        $this->forge->createTable('quote_requests');
    }

    public function down()
    {
        $this->forge->dropTable('quote_requests');
    }
}
