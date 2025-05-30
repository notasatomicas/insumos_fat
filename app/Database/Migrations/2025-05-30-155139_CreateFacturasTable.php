<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateFacturasTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_factura'   => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'id_usuario'   => ['type' => 'INT', 'unsigned' => true],
            'fecha_alta'   => ['type' => 'TIMESTAMP', 'default' => 'CURRENT_TIMESTAMP'],
            'precio_total' => ['type' => 'DECIMAL', 'constraint' => '10,2'],
        ]);
        $this->forge->addKey('id_factura', true);
        $this->forge->addForeignKey('id_usuario', 'users', 'id_usuario', 'CASCADE');
        $this->forge->createTable('facturas');
    }

    public function down()
    {
        $this->forge->dropTable('facturas');
    }
}
