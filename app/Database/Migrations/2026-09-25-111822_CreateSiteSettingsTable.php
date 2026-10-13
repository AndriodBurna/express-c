<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSiteSettingsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'            => ['type' => 'INT', 'constraint' => 10, 'unsigned' => true, 'auto_increment' => true],
            'setting_key'   => ['type' => 'VARCHAR', 'constraint' => 80],
            'setting_value' => ['type' => 'TEXT', 'null' => true],
            'setting_group' => ['type' => 'VARCHAR', 'constraint' => 40, 'default' => 'general'],
            'label'         => ['type' => 'VARCHAR', 'constraint' => 120],
            'input_type'    => ['type' => 'ENUM', 'constraint' => ['text', 'textarea', 'url', 'email', 'tel'], 'default' => 'text'],
            'sort_order'    => ['type' => 'SMALLINT', 'constraint' => 6, 'default' => 0],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('setting_key', 'uq_settings_key');
        $this->forge->createTable('site_settings');
    }

    public function down()
    {
        $this->forge->dropTable('site_settings');
    }
}
