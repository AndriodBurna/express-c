<?php

namespace App\Models;

use CodeIgniter\Model;

class ServiceModel extends Model
{
    protected $table         = 'services';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $allowedFields = [
        'title', 'slug', 'label', 'icon', 'accent',
        'short_description', 'long_description', 'features',
        'sort_order', 'is_active',
    ];
    protected $useTimestamps = false;

    public function ordered(bool $activeOnly = false): array
    {
        $b = $this->orderBy('sort_order', 'ASC')->orderBy('id', 'ASC');
        if ($activeOnly) $b->where('is_active', 1);
        return $b->findAll();
    }

    public function slugInUse(string $slug, int $excludeId = 0): bool
    {
        return (bool) $this->where('slug', $slug)->where('id !=', $excludeId)->first();
    }
}