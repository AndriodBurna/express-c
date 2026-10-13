<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateProjectCategoriesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'         => ['type' => 'INT', 'constraint' => 10, 'unsigned' => true, 'auto_increment' => true],
            'slug'       => ['type' => 'VARCHAR', 'constraint' => 60],
            'name'       => ['type' => 'VARCHAR', 'constraint' => 80],
            'sort_order' => ['type' => 'SMALLINT', 'constraint' => 6, 'default' => 0],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('slug', 'uq_categories_slug');
        $this->forge->createTable('project_categories');
    }

    public function down()
    {
        $this->forge->dropTable('project_categories');
    }
}
