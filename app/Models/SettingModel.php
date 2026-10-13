<?php

namespace App\Models;

use CodeIgniter\Model;

class SettingModel extends Model
{
    protected $table         = 'site_settings';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $allowedFields = [
        'setting_key', 'setting_value', 'setting_group',
        'label', 'input_type', 'sort_order',
    ];
    protected $useTimestamps = false;

    /**
     * Group all settings by setting_group, ordered by sort_order.
     */
    public function grouped(): array
    {
        $rows   = $this->orderBy('setting_group', 'ASC')
                       ->orderBy('sort_order', 'ASC')
                       ->orderBy('id', 'ASC')
                       ->findAll();

        $groups = [];
        foreach ($rows as $row) {
            $groups[$row['setting_group']][] = $row;
        }
        return $groups;
    }

    public function setValue(string $key, ?string $value): bool
    {
        return (bool) $this->where('setting_key', $key)->set(['setting_value' => $value])->update();
    }

    public function getValue(string $key, string $default = ''): string
    {
        $row = $this->where('setting_key', $key)->first();
        return $row ? (string) $row['setting_value'] : $default;
    }

    public function exists(string $key): bool
    {
        return (bool) $this->where('setting_key', $key)->first();
    }

    public function insertIfMissing(string $key, string $value, string $group, string $label, string $type = 'text', int $order = 0): void
    {
        if ($this->exists($key)) return;
        $this->insert([
            'setting_key'   => $key,
            'setting_value' => $value,
            'setting_group' => $group,
            'label'         => $label,
            'input_type'    => $type,
            'sort_order'    => $order,
        ]);
    }
}