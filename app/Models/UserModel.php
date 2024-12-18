<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    
    protected $allowedFields = ['name', 'phone', 'role', 'password'];
    
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    
    protected $validationRules = [
        'name'  => 'required',
        'phone' => 'required|min_length[10]|max_length[15]',
        'role'  => 'required',
        'password' => 'required',
    ];

    // Hash password before insert
    protected function beforeInsert(array $data): array
    {
        if (isset($data['data']['password'])) {
            $data['data']['password'] = password_hash($data['data']['password'], PASSWORD_DEFAULT);
        }
        if (isset($data['data']['role'])) {
            $data['data']['role'] = ucfirst(strtolower($data['data']['role']));
        }
        return $data;
    }

    // Hash password before update
    protected function beforeUpdate(array $data): array
    {
        if (isset($data['data']['password'])) {
            $data['data']['password'] = password_hash($data['data']['password'], PASSWORD_DEFAULT);
        }
        if (isset($data['data']['role'])) {
            $data['data']['role'] = ucfirst(strtolower($data['data']['role']));
        }
        return $data;
    }
} 