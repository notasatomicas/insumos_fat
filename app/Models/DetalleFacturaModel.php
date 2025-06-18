<?php

namespace App\Models;

use CodeIgniter\Model;

class DetalleFacturaModel extends Model
{
    protected $table            = 'detalle_factura';
    protected $primaryKey       = 'id_detalle_factura';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_factura', 'id_producto', 'cantidad_prod', 'precio_unit', 'subtotal'
    ];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';

    // Validation
    protected $validationRules = [
        'id_factura'    => 'required|is_natural_no_zero',
        'id_producto'   => 'required|is_natural_no_zero',
        'cantidad_prod' => 'required|is_natural_no_zero',
        'precio_unit'   => 'required|decimal|greater_than[0]',
        'subtotal'      => 'required|decimal|greater_than[0]'
    ];
    
    protected $validationMessages = [
        'id_factura' => [
            'required' => 'El ID de la factura es obligatorio',
            'is_natural_no_zero' => 'Debe especificar una factura válida'
        ],
        'id_producto' => [
            'required' => 'El ID del producto es obligatorio',
            'is_natural_no_zero' => 'Debe especificar un producto válido'
        ],
        'cantidad_prod' => [
            'required' => 'La cantidad es obligatoria',
            'is_natural_no_zero' => 'La cantidad debe ser mayor a 0'
        ],
        'precio_unit' => [
            'required' => 'El precio unitario es obligatorio',
            'decimal' => 'El precio unitario debe ser un número decimal válido',
            'greater_than' => 'El precio unitario debe ser mayor a 0'
        ],
        'subtotal' => [
            'required' => 'El subtotal es obligatorio',
            'decimal' => 'El subtotal debe ser un número decimal válido',
            'greater_than' => 'El subtotal debe ser mayor a 0'
        ]
    ];
    
    protected $skipValidation = false;

    /**
     * Obtiene todos los detalles de una factura con información del producto
     */
    public function getDetallesPorFactura($facturaId)
    {
        return $this->select('detalle_factura.*, productos.nombre_prod, productos.descripcion, productos.imagen_url')
                    ->join('productos', 'productos.id_producto = detalle_factura.id_producto')
                    ->where('detalle_factura.id_factura', $facturaId)
                    ->findAll();
    }

    /**
     * Obtiene un detalle específico con información del producto
     */
    public function getDetalleConProducto($detalleId)
    {
        return $this->select('detalle_factura.*, productos.nombre_prod, productos.descripcion, productos.precio, productos.stock, productos.imagen_url')
                    ->join('productos', 'productos.id_producto = detalle_factura.id_producto')
                    ->where('detalle_factura.id_detalle_factura', $detalleId)
                    ->first();
    }

    /**
     * Obtiene todos los detalles con información completa (factura y producto)
     */
    public function getDetallesCompletos($facturaId = null)
    {
        $query = $this->select('detalle_factura.*, productos.nombre_prod, productos.descripcion, productos.imagen_url, facturas.fecha_alta, facturas.id_usuario')
                      ->join('productos', 'productos.id_producto = detalle_factura.id_producto')
                      ->join('facturas', 'facturas.id_factura = detalle_factura.id_factura');
        
        if ($facturaId) {
            $query->where('detalle_factura.id_factura', $facturaId);
        }
        
        return $query->findAll();
    }

    /**
     * Inserta un nuevo detalle validando que el producto existe y hay stock suficiente
     */
    public function insertarDetalle($datos)
    {
        $productoModel = new ProductoModel();
        $producto = $productoModel->find($datos['id_producto']);
        
        if (!$producto) {
            $this->errors = ['id_producto' => 'El producto especificado no existe'];
            return false;
        }
        
        if ($producto['stock'] < $datos['cantidad_prod']) {
            $this->errors = ['cantidad_prod' => 'No hay suficiente stock disponible'];
            return false;
        }
        
        // Calcular subtotal automáticamente
        $datos['subtotal'] = $datos['cantidad_prod'] * $datos['precio_unit'];
        
        return $this->insert($datos);
    }

    /**
     * Actualiza un detalle existente
     */
    public function actualizarDetalle($detalleId, $datos)
    {
        // Si se está actualizando la cantidad o precio, recalcular subtotal
        if (isset($datos['cantidad_prod']) || isset($datos['precio_unit'])) {
            $detalleActual = $this->find($detalleId);
            if (!$detalleActual) {
                return false;
            }
            
            $cantidad = $datos['cantidad_prod'] ?? $detalleActual['cantidad_prod'];
            $precio = $datos['precio_unit'] ?? $detalleActual['precio_unit'];
            $datos['subtotal'] = $cantidad * $precio;
        }
        
        return $this->update($detalleId, $datos);
    }

    /**
     * Elimina todos los detalles de una factura
     */
    public function eliminarDetallesPorFactura($facturaId)
    {
        return $this->where('id_factura', $facturaId)->delete();
    }

    /**
     * Obtiene el total de una factura sumando todos sus detalles
     */
    public function getTotalFactura($facturaId)
    {
        $resultado = $this->selectSum('subtotal')
                          ->where('id_factura', $facturaId)
                          ->first();
        
        return $resultado['subtotal'] ?? 0;
    }

    /**
     * Obtiene productos más vendidos
     */
    public function getProductosMasVendidos($limit = 10)
    {
        return $this->select('productos.nombre_prod, productos.id_producto, SUM(detalle_factura.cantidad_prod) as total_vendido, SUM(detalle_factura.subtotal) as total_ingresos')
                    ->join('productos', 'productos.id_producto = detalle_factura.id_producto')
                    ->groupBy('detalle_factura.id_producto')
                    ->orderBy('total_vendido', 'DESC')
                    ->limit($limit)
                    ->findAll();
    }

    /**
     * Obtiene ventas por período
     */
    public function getVentasPorPeriodo($fechaInicio, $fechaFin)
    {
        return $this->select('detalle_factura.*, productos.nombre_prod, facturas.fecha_alta')
                    ->join('productos', 'productos.id_producto = detalle_factura.id_producto')
                    ->join('facturas', 'facturas.id_factura = detalle_factura.id_factura')
                    ->where('facturas.fecha_alta >=', $fechaInicio)
                    ->where('facturas.fecha_alta <=', $fechaFin)
                    ->orderBy('facturas.fecha_alta', 'DESC')
                    ->findAll();
    }

    /**
     * Valida que un detalle sea correcto antes de insertarlo
     */
    public function validarDetalle($datos)
    {
        $errors = [];
        
        // Validar que el producto existe
        $productoModel = new ProductoModel();
        $producto = $productoModel->find($datos['id_producto']);
        
        if (!$producto) {
            $errors['id_producto'] = 'El producto especificado no existe';
        } else {
            // Validar stock disponible
            if ($producto['stock'] < $datos['cantidad_prod']) {
                $errors['cantidad_prod'] = 'Stock insuficiente. Disponible: ' . $producto['stock'];
            }
            
            // Validar que el precio unitario coincida con el precio del producto
            if (abs($producto['precio'] - $datos['precio_unit']) > 0.01) {
                $errors['precio_unit'] = 'El precio unitario no coincide con el precio del producto';
            }
        }
        
        // Validar que el subtotal sea correcto
        $subtotalCalculado = $datos['cantidad_prod'] * $datos['precio_unit'];
        if (abs($subtotalCalculado - $datos['subtotal']) > 0.01) {
            $errors['subtotal'] = 'El subtotal no es correcto';
        }
        
        return empty($errors) ? true : $errors;
    }

    /**
     * Actualiza el stock de productos después de una venta
     */
    public function actualizarStockProductos($facturaId, $revertir = false)
    {
        $detalles = $this->where('id_factura', $facturaId)->findAll();
        $productoModel = new ProductoModel();
        
        foreach ($detalles as $detalle) {
            $producto = $productoModel->find($detalle['id_producto']);
            if ($producto) {
                $nuevoStock = $revertir ? 
                    $producto['stock'] + $detalle['cantidad_prod'] : 
                    $producto['stock'] - $detalle['cantidad_prod'];
                
                $productoModel->update($detalle['id_producto'], ['stock' => $nuevoStock]);
            }
        }
        
        return true;
    }
}