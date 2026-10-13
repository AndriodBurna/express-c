<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $returnType       = 'array';
    protected $allowedFields    = [
        'username', 'email', 'password_hash', 'full_name', 'role',
        'must_change_password', 'failed_attempts', 'locked_until', 'last_login_at',
    ];

    // Your table only has created_at, not updated_at
    protected $useTimestamps    = true;
    protected $dateFormat       = 'datetime';
    protected $createdField     = 'created_at';
    protected $updatedField     = ''; // no updated_at column

    public function findByUsername(string $username): ?array
    {
        return $this->where('username', $username)->first();
    }
}