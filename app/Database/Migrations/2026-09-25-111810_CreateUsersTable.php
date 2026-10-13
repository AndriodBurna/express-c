<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUsersTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'                   => ['type' => 'INT', 'constraint' => 10, 'unsigned' => true, 'auto_increment' => true],
            'username'             => ['type' => 'VARCHAR', 'constraint' => 60],
            'email'                => ['type' => 'VARCHAR', 'constraint' => 190],
            'password_hash'        => ['type' => 'VARCHAR', 'constraint' => 255],
            'full_name'            => ['type' => 'VARCHAR', 'constraint' => 120, 'null' => true],
            'role'                 => ['type' => 'ENUM', 'constraint' => ['admin', 'editor'], 'default' => 'admin'],
            'must_change_password' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'failed_attempts'      => ['type' => 'TINYINT', 'constraint' => 3, 'unsigned' => true, 'default' => 0],
            'locked_until'         => ['type' => 'DATETIME', 'null' => true],
            'last_login_at'        => ['type' => 'DATETIME', 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('username', 'uq_users_username');
        $this->forge->addUniqueKey('email', 'uq_users_email');
        $this->forge->createTable('users');
    }

    public function down()
    {
        $this->forge->dropTable('users');
    }
}
