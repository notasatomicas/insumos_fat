<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $data = [
            'email'         => 'admin@example.com',
            'username'      => 'admin',
            'password_hash' => password_hash('1234abc', PASSWORD_DEFAULT),
            'nombre'        => 'Administrador',
            'apellido'      => 'Principal',
            'dni'           => '12345678',
            'direccion'     => 'Av. Siempre Viva 123',
            'type'          => 1, // Administrador
            'active'        => 1,
            'created_at'    => date('Y-m-d H:i:s'),
        ];

        // Insert into the database
        $this->db->table('users')->insert($data);
    }
}
