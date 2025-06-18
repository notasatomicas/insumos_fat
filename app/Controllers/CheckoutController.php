<?php

namespace App\Controllers;

use App\Models\FacturaModel;
use App\Models\DetalleFacturaModel;
use App\Models\ProductoModel;
use App\Models\UserModel;
use CodeIgniter\Controller;

class CheckoutController extends BaseController
{
    protected $facturaModel;
    protected $detalleFacturaModel;
    protected $productoModel;
    protected $userModel;
    protected $validation;

    public function __construct()
    {
        $this->facturaModel = new FacturaModel();
        $this->detalleFacturaModel = new DetalleFacturaModel();
        $this->productoModel = new ProductoModel();
        $this->userModel = new UserModel();
        $this->validation = \Config\Services::validation();
    }

    /**
     * Mostrar la página del checkout y procesar directamente
     */
    public function index()
    {
        // Verificar que el usuario esté logueado
        if (!session()->get('isLoggedIn')) {
            session()->setFlashdata('error', 'Debes iniciar sesión para proceder con la compra');
            return redirect()->to(base_url('auth'));
        }

        $data = [
            'titulo' => 'Insumos_FAT - Checkout',
            'activo' => 'checkout'
        ];

        return view('checkout/index', $data);
    }

    /**
     * Procesar la compra directamente desde el frontend
     */
    public function procesarCompraDirecta()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400)
                ->setJSON(['success' => false, 'message' => 'Solicitud no válida']);
        }

        // Verificar que el usuario esté logueado
        if (!session()->get('isLoggedIn')) {
            return $this->response->setStatusCode(401)
                ->setJSON(['success' => false, 'message' => 'Debes iniciar sesión']);
        }

        try {
            // Obtener datos del carrito desde el request
            $carritoData = $this->request->getJSON(true);
            
            if (empty($carritoData) || empty($carritoData['carrito'])) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'El carrito está vacío'
                ]);
            }

            $carrito = $carritoData['carrito'];
            $userId = session()->get('id_usuario');

            // Obtener datos del usuario para la factura
            $usuario = $this->userModel->find($userId);
            if (!$usuario) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Usuario no encontrado'
                ]);
            }

            // Validar productos y stock
            $validacion = $this->validarCarrito($carrito);
            if (!$validacion['valido']) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Error en la validación del carrito',
                    'errores' => $validacion['errores']
                ]);
            }

            // Crear la factura
            $facturaId = $this->crearFactura($userId, $carrito, $validacion['productos']);
            
            if (!$facturaId) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Error al procesar la compra'
                ]);
            }

            // Actualizar stock de productos
            $this->actualizarStock($carrito, $validacion['productos']);

            // Preparar detalles de los productos comprados para la respuesta
            $productosComprados = [];
            foreach ($carrito as $productoId => $item) {
                $producto = $validacion['productos'][$productoId];
                $productosComprados[] = [
                    'nombre' => $producto['nombre_prod'],
                    'cantidad' => (int)$item['cantidad'],
                    'precio_unitario' => (float)$producto['precio'],
                    'subtotal' => (float)$producto['precio'] * (int)$item['cantidad']
                ];
            }

            // Preparar datos del cliente para la factura
            $clienteData = [
                'nombre' => $usuario['nombre'],
                'apellido' => $usuario['apellido'],
                'email' => $usuario['email'],
                'dni' => $usuario['dni'],
                'direccion' => $usuario['direccion']
            ];

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Compra realizada correctamente',
                'factura_id' => $facturaId,
                'total' => $validacion['total'],
                'fecha' => date('d/m/Y H:i'),
                'productos' => $productosComprados,
                'cliente' => $clienteData
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Error al procesar compra: ' . $e->getMessage());
            return $this->response->setStatusCode(500)->setJSON([
                'success' => false,
                'message' => 'Error interno del servidor'
            ]);
        }
    }

    /**
     * Validar el carrito y verificar stock
     */
    private function validarCarrito($carrito)
    {
        $productosIds = array_keys($carrito);
        $errores = [];
        $total = 0;
        
        // Obtener productos de la base de datos
        $productos = $this->productoModel
            ->select('id_producto, nombre_prod, precio, stock, active')
            ->whereIn('id_producto', $productosIds)
            ->where('active', 1)
            ->where('deleted_at', null)
            ->findAll();

        $productosDB = [];
        foreach ($productos as $producto) {
            $productosDB[$producto['id_producto']] = $producto;
        }

        // Validar cada producto del carrito
        foreach ($carrito as $productoId => $item) {
            $cantidad = (int)$item['cantidad'];
            
            if (!isset($productosDB[$productoId])) {
                $errores[] = "Producto con ID {$productoId} no encontrado o no disponible";
                continue;
            }

            $producto = $productosDB[$productoId];
            
            if ($producto['stock'] < $cantidad) {
                $errores[] = "Stock insuficiente para {$producto['nombre_prod']}. Disponible: {$producto['stock']}, Solicitado: {$cantidad}";
                continue;
            }

            if ($cantidad <= 0) {
                $errores[] = "Cantidad inválida para {$producto['nombre_prod']}";
                continue;
            }

            $total += $producto['precio'] * $cantidad;
        }

        return [
            'valido' => empty($errores),
            'errores' => $errores,
            'productos' => $productosDB,
            'total' => $total
        ];
    }

    /**
     * Crear la factura con sus detalles
     */
    private function crearFactura($userId, $carrito, $productos)
    {
        $db = \Config\Database::connect();
        $db->transStart();

        try {
            $total = 0;
            $detalles = [];

            // Preparar detalles de la factura
            foreach ($carrito as $productoId => $item) {
                $cantidad = (int)$item['cantidad'];
                $producto = $productos[$productoId];
                $precioUnitario = (float)$producto['precio'];
                $subtotal = $precioUnitario * $cantidad;
                
                $detalles[] = [
                    'id_producto' => $productoId,
                    'cantidad_prod' => $cantidad,
                    'precio_unit' => $precioUnitario,
                    'subtotal' => $subtotal
                ];

                $total += $subtotal;
            }

            // Crear la factura
            $datosFactura = [
                'id_usuario' => $userId,
                'fecha_alta' => date('Y-m-d H:i:s'),
                'precio_total' => $total
            ];

            $facturaId = $this->facturaModel->insert($datosFactura);
            
            if (!$facturaId) {
                throw new \Exception('Error al crear la factura');
            }

            // Insertar los detalles
            foreach ($detalles as $detalle) {
                $detalle['id_factura'] = $facturaId;
                if (!$this->detalleFacturaModel->insert($detalle)) {
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
            log_message('error', 'Error al crear factura: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Actualizar stock de productos después de la compra
     */
    private function actualizarStock($carrito, $productos)
    {
        foreach ($carrito as $productoId => $item) {
            $cantidad = (int)$item['cantidad'];
            $stockActual = $productos[$productoId]['stock'];
            $nuevoStock = $stockActual - $cantidad;
            
            $this->productoModel->update($productoId, ['stock' => $nuevoStock]);
        }
    }

    /**
     * Obtener resumen de compra (opcional, para mostrar confirmación)
     */
    public function obtenerResumen($facturaId)
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to(base_url('auth'));
        }

        $factura = $this->facturaModel->getFacturaConUsuario($facturaId);
        
        if (!$factura || $factura['id_usuario'] != session()->get('id_usuario')) {
            session()->setFlashdata('error', 'Factura no encontrada');
            return redirect()->to(base_url('carrito'));
        }

        $detalles = $this->detalleFacturaModel->getDetallesPorFactura($facturaId);

        $data = [
            'titulo' => 'Insumos_FAT - Resumen de Compra',
            'factura' => $factura,
            'detalles' => $detalles
        ];

        return view('checkout/resumen', $data);
    }
}