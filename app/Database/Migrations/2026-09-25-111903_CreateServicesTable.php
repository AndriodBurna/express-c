<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateServicesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'                => ['type' => 'INT', 'constraint' => 10, 'unsigned' => true, 'auto_increment' => true],
            'slug'              => ['type' => 'VARCHAR', 'constraint' => 120],
            'title'             => ['type' => 'VARCHAR', 'constraint' => 150],
            'label'             => ['type' => 'VARCHAR', 'constraint' => 60, 'null' => true],
            'icon'              => ['type' => 'VARCHAR', 'constraint' => 80, 'default' => 'fa-solid fa-cube'],
            'accent'            => ['type' => 'ENUM', 'constraint' => ['blue', 'light', 'gold'], 'default' => 'blue'],
            'short_description' => ['type' => 'VARCHAR', 'constraint' => 400],
            'long_description'  => ['type' => 'TEXT', 'null' => true],
            'features'          => ['type' => 'TEXT', 'null' => true],
            'sort_order'        => ['type' => 'SMALLINT', 'constraint' => 6, 'default' => 0],
            'is_active'         => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('slug', 'uq_services_slug');
        $this->forge->createTable('services');
    }

    public function down()
    {
        $this->forge->dropTable('services');
    }
}
