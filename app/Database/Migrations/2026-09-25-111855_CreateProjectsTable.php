<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateProjectsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'                => ['type' => 'INT', 'constraint' => 10, 'unsigned' => true, 'auto_increment' => true],
            'category_id'       => ['type' => 'INT', 'constraint' => 10, 'unsigned' => true, 'null' => true],
            'slug'              => ['type' => 'VARCHAR', 'constraint' => 120],
            'title'             => ['type' => 'VARCHAR', 'constraint' => 150],
            'client_name'       => ['type' => 'VARCHAR', 'constraint' => 150, 'null' => true],
            'short_description' => ['type' => 'VARCHAR', 'constraint' => 400],
            'overview'          => ['type' => 'TEXT', 'null' => true],
            'problem'           => ['type' => 'TEXT', 'null' => true],
            'challenge'         => ['type' => 'TEXT', 'null' => true],
            'solution'          => ['type' => 'TEXT', 'null' => true],
            'technologies'      => ['type' => 'VARCHAR', 'constraint' => 400, 'null' => true],
            'features'          => ['type' => 'TEXT', 'null' => true],
            'results'           => ['type' => 'TEXT', 'null' => true],
            'image'             => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'project_url'       => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'year'              => ['type' => 'VARCHAR', 'constraint' => 10, 'null' => true],
            'is_featured'       => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
            'is_active'         => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'sort_order'        => ['type' => 'SMALLINT', 'constraint' => 6, 'default' => 0],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('slug', 'uq_projects_slug');
        $this->forge->addKey('category_id', false, false, 'idx_projects_category');
        $this->forge->addForeignKey('category_id', 'project_categories', 'id', 'SET NULL', 'CASCADE', 'fk_projects_category');
        $this->forge->createTable('projects');
    }

    public function down()
    {
        $this->forge->dropTable('projects');
    }
}
