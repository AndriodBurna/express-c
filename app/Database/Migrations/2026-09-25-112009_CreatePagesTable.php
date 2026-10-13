<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePagesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'          => ['type' => 'INT', 'constraint' => 10, 'unsigned' => true, 'auto_increment' => true],
            'slug'        => ['type' => 'VARCHAR', 'constraint' => 120],
            'title'       => ['type' => 'VARCHAR', 'constraint' => 150],
            'content'     => ['type' => 'LONGTEXT', 'null' => true],
            'sort_order'  => ['type' => 'SMALLINT', 'constraint' => 6, 'default' => 0],
            'is_featured' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
            'is_active'   => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at'  => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('slug', 'uq_pages_slug');
        $this->forge->createTable('pages');
    }

    public function down()
    {
      $this->forge->dropTable('pages');
    }
}
