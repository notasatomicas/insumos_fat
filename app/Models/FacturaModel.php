<?php

namespace App\Models;

use CodeIgniter\Model;

class FacturaModel extends Model
{
    protected $table            = 'facturas';
    protected $primaryKey       = 'id_factura';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_usuario', 'fecha_alta', 'precio_total'
    ];

    // Dates
    protected $useTimestamps = false; // No usamos timestamps automáticos ya que tienes fecha_alta
    protected $dateFormat    = 'datetime';

    // Validation
    protected $validationRules = [
        'id_usuario'    => 'required|is_natural_no_zero',
        'precio_total'  => 'required|decimal|greater_than[0]'
    ];
    
    protected $validationMessages = [
        'id_usuario' => [
            'required' => 'El ID del usuario es obligatorio',
            'is_natural_no_zero' => 'Debe seleccionar un usuario válido'
        ],
        'precio_total' => [
            'required' => 'El precio total es obligatorio',
            'decimal' => 'El precio total debe ser un número decimal válido',
            'greater_than' => 'El precio total debe ser mayor a 0'
        ]
    ];
    
    protected $skipValidation = false;

    /**
     * Obtiene todas las facturas con información del usuario
     */
    public function getFacturasConUsuario()
    {
        return $this->select('facturas.*, users.nombre, users.apellido, users.email')
                    ->join('users', 'users.id_usuario = facturas.id_usuario')
                    ->orderBy('facturas.fecha_alta', 'DESC')
                    ->findAll();
    }

    /**
     * Obtiene una factura específica con información del usuario
     */
    public function getFacturaConUsuario($id)
    {
        return $this->select('facturas.*, users.nombre, users.apellido, users.email, users.dni, users.direccion')
                    ->join('users', 'users.id_usuario = facturas.id_usuario')
                    ->where('facturas.id_factura', $id)
                    ->first();
    }

    /**
     * Obtiene facturas de un usuario específico
     */
    public function getFacturasPorUsuario($userId)
    {
        return $this->where('id_usuario', $userId)
                    ->orderBy('fecha_alta', 'DESC')
                    ->findAll();
    }

    /**
     * Crea una nueva factura con sus detalles
     */
    public function crearFacturaCompleta($datosFactura, $detalles)
    {
        $db = \Config\Database::connect();
        $db->transStart();

        try {
            // Insertar la factura
            $facturaId = $this->insert($datosFactura);
            
            if (!$facturaId) {
                throw new \Exception('Error al crear la factura');
            }

            // Insertar los detalles
            $detalleModel = new DetalleFacturaModel();
            foreach ($detalles as $detalle) {
                $detalle['id_factura'] = $facturaId;
                if (!$detalleModel->insert($detalle)) {
                    throw new \Exception('Error al insertar detalle de factura');
                }
            }

            $db->transComplete();

            if ($db->transStatus() === false) {
                throw new \Exception('Error en la transacción');
            }

            return $facturaId;

        } catch (\Exception $e) {
            $db->transRollback();
            log_message('error', 'Error al crear factura completa: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Actualiza una factura existente
     */
    public function actualizarFactura($facturaId, $datosFactura, $detalles = [])
    {
        $db = \Config\Database::connect();
        $db->transStart();

        try {
            // Actualizar la factura
            if (!$this->update($facturaId, $datosFactura)) {
                throw new \Exception('Error al actualizar la factura');
            }

            // Si se proporcionan nuevos detalles, eliminar los anteriores y crear los nuevos
            if (!empty($detalles)) {
                $detalleModel = new DetalleFacturaModel();
                
                // Eliminar detalles anteriores
                $detalleModel->where('id_factura', $facturaId)->delete();
                
                // Insertar nuevos detalles
                foreach ($detalles as $detalle) {
                    $detalle['id_factura'] = $facturaId;
                    if (!$detalleModel->insert($detalle)) {
                        throw new \Exception('Error al insertar nuevo detalle de factura');
                    }
                }
            }

            $db->transComplete();

            if ($db->transStatus() === false) {
                throw new \Exception('Error en la transacción de actualización');
            }

            return true;

        } catch (\Exception $e) {
            $db->transRollback();
            log_message('error', 'Error al actualizar factura: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Obtiene estadísticas básicas de facturas
     */
    public function getEstadisticas()
    {
        $totalFacturas = $this->countAll();
        $totalVentas = $this->selectSum('precio_total')->first()['precio_total'] ?? 0;
        
        return [
            'total_facturas' => $totalFacturas,
            'total_ventas' => $totalVentas,
            'promedio_venta' => $totalFacturas > 0 ? round($totalVentas / $totalFacturas, 2) : 0
        ];
    }

    /**
     * Obtiene facturas por rango de fechas
     */
    public function getFacturasPorFechas($fechaInicio, $fechaFin)
    {
        return $this->where('fecha_alta >=', $fechaInicio)
                    ->where('fecha_alta <=', $fechaFin)
                    ->orderBy('fecha_alta', 'DESC')
                    ->findAll();
    }

    /**
     * Elimina una factura y todos sus detalles
     */
    public function eliminarFacturaCompleta($facturaId)
    {
        $db = \Config\Database::connect();
        $db->transStart();

        try {
            // Eliminar detalles primero (por la FK)
            $detalleModel = new DetalleFacturaModel();
            $detalleModel->where('id_factura', $facturaId)->delete();
            
            // Eliminar la factura
            $this->delete($facturaId);

            $db->transComplete();

            if ($db->transStatus() === false) {
                throw new \Exception('Error en la transacción de eliminación');
            }

            return true;

        } catch (\Exception $e) {
            $db->transRollback();
            log_message('error', 'Error al eliminar factura completa: ' . $e->getMessage());
            return false;
        }
    }
}