<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCategoriasTable extends Migration
{
    protected $DBGroup = 'default';
    
    public function up()
    {
        $this->forge->addField([
            'id_categoria' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'nombre'       => ['type' => 'VARCHAR', 'constraint' => 100],
            'descripcion'  => ['type' => 'TEXT', 'null' => true],
            'estado'       => ['type' => 'TINYINT', 'default' => 1],
            'created_at'   => ['type' => 'DATETIME', 'null' => true],
            'updated_at'   => ['type' => 'DATETIME', 'null' => true],
            'deleted_at'   => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id_categoria', true);
        $this->forge->createTable('categorias');
    }

    public function down()
    {
        $this->forge->dropTable('categorias');
    }
}