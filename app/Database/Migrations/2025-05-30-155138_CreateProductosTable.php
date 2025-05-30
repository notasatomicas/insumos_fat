<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateProductosTable extends Migration
{
    protected $DBGroup = 'default';
    
    public function up()
    {
        $this->forge->addField([
            'id_producto'   => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'nombre_prod'   => ['type' => 'VARCHAR', 'constraint' => 100],
            'descripcion'   => ['type' => 'TEXT'],
            'precio'        => ['type' => 'DECIMAL', 'constraint' => '10,2'],
            'stock'         => ['type' => 'INT'],
            'categoria_id'  => ['type' => 'INT', 'unsigned' => true],
            'imagen_url'    => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'created_at'    => ['type' => 'DATETIME', 'null' => true],
            'updated_at'    => ['type' => 'DATETIME', 'null' => true],
            'deleted_at'    => ['type' => 'DATETIME', 'null' => true],
            'active'        => ['type' => 'TINYINT', 'default' => 1],
        ]);
        $this->forge->addKey('id_producto', true);
        $this->forge->addForeignKey('categoria_id', 'categorias', 'id_categoria', 'RESTRICT');
        $this->forge->createTable('productos');
    }

    public function down()
    {
        $this->forge->dropTable('productos');
    }
}