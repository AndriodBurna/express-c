<?php

namespace App\Models;

use CodeIgniter\Model;

class TrainingModel extends Model
{
    protected $table         = 'training_courses';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $allowedFields = [
        'title', 'slug', 'icon', 'short_description', 'long_description',
        'topics', 'duration', 'level', 'sort_order', 'is_active',
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