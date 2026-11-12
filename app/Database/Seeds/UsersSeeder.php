<?php
namespace App\Database\Seeds;
use CodeIgniter\Database\Seeder;

class UsersSeeder extends Seeder
{
    public function run()
    {
        $data = [
            'name' => 'Admin1',
            'email' => 'admin1@gmail.com',
            'password_hash' => password_hash('admin123', PASSWORD_DEFAULT),
            'role' => 'admin',
            'is_active' => 'active',
        ];

        $this->db->table('users')->insert($data);
    }
}