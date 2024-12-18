<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        $data = [
            'name' => 'Admin User',
            'phone' => '1234567890',
            'role' => 'Admin',
        ];

        $this->db->table('users')->insert($data);
    }
} 