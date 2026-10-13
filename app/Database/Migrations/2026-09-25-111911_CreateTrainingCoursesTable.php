<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTrainingCoursesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'                => ['type' => 'INT', 'constraint' => 10, 'unsigned' => true, 'auto_increment' => true],
            'slug'              => ['type' => 'VARCHAR', 'constraint' => 120],
            'title'             => ['type' => 'VARCHAR', 'constraint' => 150],
            'icon'              => ['type' => 'VARCHAR', 'constraint' => 80, 'default' => 'fa-solid fa-book-open'],
            'short_description' => ['type' => 'VARCHAR', 'constraint' => 400],
            'long_description'  => ['type' => 'TEXT', 'null' => true],
            'topics'            => ['type' => 'TEXT', 'null' => true],
            'duration'          => ['type' => 'VARCHAR', 'constraint' => 60, 'null' => true],
            'level'             => ['type' => 'VARCHAR', 'constraint' => 60, 'null' => true],
            'sort_order'        => ['type' => 'SMALLINT', 'constraint' => 6, 'default' => 0],
            'is_active'         => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('slug', 'uq_training_slug');
        $this->forge->createTable('training_courses');
    }

    public function down()
    {
        $this->forge->dropTable('training_courses');
    }
}
