<?php

namespace App\Models;

use CodeIgniter\Model;

class MessageModel extends Model
{
    protected $table         = 'contact_messages';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $allowedFields = [
        'first_name', 'last_name', 'email', 'phone',
        'subject', 'message', 'ip_address', 'is_read',
    ];
    protected $useTimestamps = false;

    /**
     * Simple pagination helper (renamed to avoid clashing with BaseModel::paginate).
     * Returns ['rows' => [...], 'total' => int].
     */
    public function paginateRows(int $page = 1, int $perPage = 20): array
    {
        $offset = ($page - 1) * $perPage;
        return [
            'rows'  => $this->orderBy('created_at', 'DESC')->findAll($perPage, $offset),
            'total' => $this->countAllResults(),
        ];
    }

    public function markRead(int $id, bool $read = true): bool
    {
        return (bool) $this->update($id, ['is_read' => $read ? 1 : 0]);
    }
}