<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUsersTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_usuario'      => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'email'           => ['type' => 'VARCHAR', 'constraint' => 255, 'unique' => true],
            'username'        => ['type' => 'VARCHAR', 'constraint' => 30, 'unique' => true],
            'password_hash'   => ['type' => 'VARCHAR', 'constraint' => 255],
            'nombre'          => ['type' => 'VARCHAR', 'constraint' => 100],
            'apellido'        => ['type' => 'VARCHAR', 'constraint' => 100],
            'dni'             => ['type' => 'VARCHAR', 'constraint' => 20],
            'direccion'       => ['type' => 'VARCHAR', 'constraint' => 255],
            'type'            => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
            'active'          => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'created_at'      => ['type' => 'DATETIME', 'null' => true],
            'updated_at'      => ['type' => 'DATETIME', 'null' => true],
            'deleted_at'      => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id_usuario', true);
        $this->forge->createTable('users');
    }

    public function down()
    {
        $this->forge->dropTable('users');
    }
}
