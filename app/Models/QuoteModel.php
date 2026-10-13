<?php

namespace App\Models;

use CodeIgniter\Model;

class QuoteModel extends Model
{
    protected $table         = 'quote_requests';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $allowedFields = [
        'first_name', 'last_name', 'email', 'phone', 'whatsapp',
        'company', 'service_id', 'service_needed', 'budget',
        'description', 'ip_address', 'status',
    ];
    protected $useTimestamps = false;

    /**
     * @return array{rows: array, total: int}
     */
    public function paginateRows(?string $status = null, int $page = 1, int $perPage = 20): array
    {
        $builder = $this->builder();

        if ($status !== null && $status !== '') {
            $builder->where('status', $status);
        }

        $total = $builder->countAllResults(false); // keep the where clause
        $rows  = $builder->orderBy('created_at', 'DESC')
                         ->limit($perPage, ($page - 1) * $perPage)
                         ->get()->getResultArray();

        return ['rows' => $rows, 'total' => $total];
    }
}