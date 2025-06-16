<?php

namespace App\Controllers;

use App\Models\ProductoModel;
use CodeIgniter\Controller;
use CodeIgniter\HTTP\ResponseInterface;

class CarritoController extends BaseController
{
    protected $productoModel;
    protected $validation;

    public function __construct()
    {
        $this->productoModel = new ProductoModel();
        $this->validation = \Config\Services::validation();
    }

    /**
     * Mostrar la página del carrito
     */
    public function index()
    {
        $data = [
            'titulo' => 'Insumos_FAT - Carrito',
            'activo' => 'carrito'
        ];

        return view('carrito/index', $data);
    }

    /**
     * API para obtener productos por IDs (usado por el frontend)
     * Esta función es la que realmente llama el JavaScript de la vista
     */
    public function obtenerPorIds()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400)
                ->setJSON(['success' => false, 'message' => 'Solicitud no válida']);
        }

        try {
            // Obtener datos JSON del request
            $json = $this->request->getJSON(true);
            $ids = $json['ids'] ?? [];
            
            if (empty($ids)) {
                return $this->response->setJSON([
                    'success' => true,
                    'productos' => []
                ]);
            }

            // Obtener productos de la base de datos
            $productos = $this->productoModel
                ->select('id_producto, nombre_prod, precio, stock, imagen_url, active')
                ->whereIn('id_producto', $ids)
                ->where('active', 1)
                ->where('deleted_at', null)
                ->findAll();

            // Procesar productos y agregar URL completa de imagen
            $productosFormateados = [];
            foreach ($productos as $producto) {
                $imagenUrlCompleta = $this->procesarImagenProducto($producto['imagen_url']);
                
                $productosFormateados[] = [
                    'id_producto' => $producto['id_producto'],
                    'nombre_prod' => $producto['nombre_prod'],
                    'precio' => floatval($producto['precio']),
                    'stock' => intval($producto['stock']),
                    'imagen_url_completa' => $imagenUrlCompleta,
                    'active' => $producto['active']
                ];
            }

            return $this->response->setJSON([
                'success' => true,
                'productos' => $productosFormateados
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Error al obtener productos por IDs: ' . $e->getMessage());
            return $this->response->setStatusCode(500)->setJSON([
                'success' => false,
                'message' => 'Error interno del servidor'
            ]);
        }
    }

    /**
     * Obtener productos del carrito via AJAX (método alternativo)
     * Recibe los IDs desde localStorage y devuelve los datos completos
     */
    public function obtenerProductos()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400)
                ->setJSON(['error' => 'Solicitud no válida']);
        }

        try {
            $carrito = $this->request->getJSON(true);
            
            if (empty($carrito)) {
                return $this->response->setJSON([
                    'success' => true,
                    'productos' => [],
                    'resumen' => [
                        'subtotal' => 0,
                        'total' => 0,
                        'cantidad_items' => 0
                    ]
                ]);
            }

            $productosIds = array_keys($carrito);
            $productos = [];
            $subtotal = 0;
            $cantidadTotal = 0;

            // Obtener productos de la base de datos
            $productosDB = $this->productoModel
                ->select('id_producto, nombre_prod, precio, stock, imagen_url, active')
                ->whereIn('id_producto', $productosIds)
                ->where('active', 1)
                ->where('deleted_at', null)
                ->findAll();

            foreach ($productosDB as $producto) {
                $id = $producto['id_producto'];
                $cantidadSolicitada = $carrito[$id]['cantidad'] ?? 1;
                
                // Verificar stock disponible
                $cantidadFinal = min($cantidadSolicitada, $producto['stock']);
                
                // Procesar imagen
                $imagenUrl = $this->procesarImagenProducto($producto['imagen_url']);
                
                $productos[] = [
                    'id' => $id,
                    'nombre' => $producto['nombre_prod'],
                    'precio' => floatval($producto['precio']),
                    'cantidad' => $cantidadFinal,
                    'cantidad_solicitada' => $cantidadSolicitada,
                    'stock' => intval($producto['stock']),
                    'imagen_url' => $imagenUrl,
                    'subtotal' => floatval($producto['precio']) * $cantidadFinal,
                    'disponible' => $producto['stock'] > 0,
                    'stock_insuficiente' => $cantidadSolicitada > $producto['stock']
                ];

                $subtotal += floatval($producto['precio']) * $cantidadFinal;
                $cantidadTotal += $cantidadFinal;
            }

            // Calcular resumen
            $resumen = [
                'subtotal' => $subtotal,
                'total' => $subtotal, // Aquí puedes agregar descuentos, impuestos, etc.
                'cantidad_items' => $cantidadTotal
            ];

            return $this->response->setJSON([
                'success' => true,
                'productos' => $productos,
                'resumen' => $resumen
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Error al obtener productos del carrito: ' . $e->getMessage());
            return $this->response->setStatusCode(500)
                ->setJSON(['error' => 'Error interno del servidor']);
        }
    }

    /**
     * Actualizar cantidad de un producto en el carrito
     */
    public function actualizarCantidad()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400)
                ->setJSON(['error' => 'Solicitud no válida']);
        }

        $rules = [
            'producto_id' => 'required|numeric',
            'cantidad' => 'required|numeric|greater_than[0]'
        ];

        if (!$this->validation->setRules($rules)->run($this->request->getPost())) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Datos inválidos',
                'errors' => $this->validation->getErrors()
            ]);
        }

        try {
            $productoId = $this->request->getPost('producto_id');
            $cantidadNueva = (int)$this->request->getPost('cantidad');

            // Verificar producto y stock
            $producto = $this->productoModel->find($productoId);
            
            if (!$producto || !$producto['active']) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Producto no encontrado'
                ]);
            }

            if ($producto['stock'] < $cantidadNueva) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Stock insuficiente. Disponible: ' . $producto['stock']
                ]);
            }

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Cantidad actualizada',
                'producto' => [
                    'id' => $producto['id_producto'],
                    'stock_disponible' => $producto['stock'],
                    'precio' => $producto['precio']
                ]
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Error al actualizar cantidad: ' . $e->getMessage());
            return $this->response->setStatusCode(500)->setJSON([
                'success' => false,
                'message' => 'Error interno del servidor'
            ]);
        }
    }

    /**
     * Validar disponibilidad de productos del carrito
     */
    public function validarDisponibilidad()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400)
                ->setJSON(['error' => 'Solicitud no válida']);
        }

        try {
            $carrito = $this->request->getJSON(true);
            
            if (empty($carrito)) {
                return $this->response->setJSON([
                    'success' => true,
                    'productos_validos' => [],
                    'productos_invalidos' => []
                ]);
            }

            $productosIds = array_keys($carrito);
            $productosValidos = [];
            $productosInvalidos = [];

            // Verificar productos
            $productosDB = $this->productoModel
                ->select('id_producto, nombre_prod, stock, active')
                ->whereIn('id_producto', $productosIds)
                ->findAll();

            foreach ($productosDB as $producto) {
                $id = $producto['id_producto'];
                $cantidadSolicitada = $carrito[$id]['cantidad'] ?? 1;

                if (!$producto['active']) {
                    $productosInvalidos[] = [
                        'id' => $id,
                        'nombre' => $producto['nombre_prod'],
                        'razon' => 'Producto no disponible'
                    ];
                } elseif ($producto['stock'] < $cantidadSolicitada) {
                    $productosInvalidos[] = [
                        'id' => $id,
                        'nombre' => $producto['nombre_prod'],
                        'razon' => 'Stock insuficiente',
                        'stock_disponible' => $producto['stock'],
                        'cantidad_solicitada' => $cantidadSolicitada
                    ];
                } else {
                    $productosValidos[] = [
                        'id' => $id,
                        'cantidad' => $cantidadSolicitada
                    ];
                }
            }

            return $this->response->setJSON([
                'success' => true,
                'productos_validos' => $productosValidos,
                'productos_invalidos' => $productosInvalidos,
                'es_valido' => empty($productosInvalidos)
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Error al validar disponibilidad: ' . $e->getMessage());
            return $this->response->setStatusCode(500)
                ->setJSON(['error' => 'Error interno del servidor']);
        }
    }

    /**
     * Obtener resumen del carrito
     */
    public function resumen()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400)
                ->setJSON(['error' => 'Solicitud no válida']);
        }

        try {
            $carrito = $this->request->getJSON(true);
            
            if (empty($carrito)) {
                return $this->response->setJSON([
                    'success' => true,
                    'resumen' => [
                        'subtotal' => 0,
                        'descuento' => 0,
                        'impuestos' => 0,
                        'envio' => 0,
                        'total' => 0,
                        'cantidad_items' => 0
                    ]
                ]);
            }

            $productosIds = array_keys($carrito);
            $subtotal = 0;
            $cantidadTotal = 0;

            // Obtener precios actuales de los productos
            $productos = $this->productoModel
                ->select('id_producto, precio, stock, active')
                ->whereIn('id_producto', $productosIds)
                ->where('active', 1)
                ->where('deleted_at', null)
                ->findAll();

            foreach ($productos as $producto) {
                $id = $producto['id_producto'];
                $cantidad = min($carrito[$id]['cantidad'], $producto['stock']);
                
                $subtotal += floatval($producto['precio']) * $cantidad;
                $cantidadTotal += $cantidad;
            }

            // Calcular descuentos y cargos adicionales
            $descuento = 0;
            $impuestos = 0;
            $envio = $subtotal >= 2000 ? 0 : 500; // Envío gratis para compras mayores a $2000

            $total = $subtotal - $descuento + $impuestos + $envio;

            return $this->response->setJSON([
                'success' => true,
                'resumen' => [
                    'subtotal' => $subtotal,
                    'descuento' => $descuento,
                    'impuestos' => $impuestos,
                    'envio' => $envio,
                    'total' => $total,
                    'cantidad_items' => $cantidadTotal,
                    'envio_gratis' => $subtotal >= 2000
                ]
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Error al calcular resumen: ' . $e->getMessage());
            return $this->response->setStatusCode(500)
                ->setJSON(['error' => 'Error interno del servidor']);
        }
    }

    /**
     * Procesar imagen del producto
     */
    private function procesarImagenProducto($imagenUrl): string
    {
        if (empty($imagenUrl)) {
            return '';
        }

        // Si la URL ya es completa
        if (strpos($imagenUrl, 'http') === 0) {
            return $imagenUrl;
        }

        // Construir la ruta completa
        $rutaCompleta = base_url('public') . '/' . ltrim($imagenUrl, '/');
        
        // Verificar si el archivo existe
        $rutaFisica = FCPATH . 'public/' . ltrim($imagenUrl, '/');
        
        if (file_exists($rutaFisica)) {
            return $rutaCompleta;
        }

        return '';
    }
}