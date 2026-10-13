<?php

namespace App\Models;

use CodeIgniter\Model;

class ProjectModel extends Model
{
    protected $table         = 'projects';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $allowedFields = [
        'category_id', 'slug', 'title', 'client_name', 'short_description',
        'overview', 'problem', 'challenge', 'solution', 'technologies',
        'features', 'results', 'image', 'project_url', 'year',
        'is_featured', 'is_active', 'sort_order',
    ];
    protected $useTimestamps = false;

    /**
     * Admin list — with category name joined.
     */
    public function withCategory(): array
    {
        return $this->select('projects.*, project_categories.name AS category_name')
            ->join('project_categories', 'project_categories.id = projects.category_id', 'left')
            ->orderBy('projects.sort_order', 'ASC')
            ->orderBy('projects.id', 'ASC')
            ->findAll();
    }

    public function findBySlug(string $slug, bool $activeOnly = true): ?array
    {
        $b = $this->where('slug', $slug);
        if ($activeOnly) $b->where('is_active', 1);
        return $b->first();
    }

    public function slugInUse(string $slug, int $excludeId = 0): bool
    {
        return (bool) $this->where('slug', $slug)
                           ->where('id !=', $excludeId)
                           ->first();
    }
}