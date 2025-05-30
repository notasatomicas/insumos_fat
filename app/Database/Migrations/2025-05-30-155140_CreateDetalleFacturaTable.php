<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateDetalleFacturaTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_detalle_factura' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'id_factura'         => ['type' => 'INT', 'unsigned' => true],
            'id_producto'        => ['type' => 'INT', 'unsigned' => true],
            'cantidad_prod'      => ['type' => 'INT'],
            'precio_unit'        => ['type' => 'DECIMAL', 'constraint' => '10,2'],
            'subtotal'           => ['type' => 'DECIMAL', 'constraint' => '10,2'],
        ]);
        $this->forge->addKey('id_detalle_factura', true);
        $this->forge->addForeignKey('id_factura', 'facturas', 'id_factura', 'CASCADE');
        $this->forge->addForeignKey('id_producto', 'productos', 'id_producto', 'RESTRICT');
        $this->forge->createTable('detalle_factura');
    }

    public function down()
    {
        $this->forge->dropTable('detalle_factura');
    }
}
