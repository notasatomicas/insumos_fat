<?php

namespace App\Controllers;

use App\Models\ProductoModel;
use App\Models\CategoriaModel;
use CodeIgniter\Controller;
use CodeIgniter\HTTP\ResponseInterface;

class CatalogoController extends Controller
{
    protected $productoModel;
    protected $categoriaModel;
    protected $validation;

    public function __construct()
    {
        $this->productoModel = new ProductoModel();
        $this->categoriaModel = new CategoriaModel();
        $this->validation = \Config\Services::validation();
    }

    /**
     * Mostrar catálogo de productos con filtros y paginación
     */
    public function index()
    {
        try {
            // Obtener y validar parámetros
            $filtros = $this->obtenerFiltros();
            $page = max(1, (int)($this->request->getGet('page') ?? 1));
            $perPage = 12;

            // Construir query con filtros
            $builder = $this->construirQueryProductos($filtros);
            
            // Obtener total de productos para paginación
            $totalProductos = $builder->countAllResults(false);
            
            // Calcular offset y obtener productos
            $offset = ($page - 1) * $perPage;
            $productos = $builder->limit($perPage, $offset)->get()->getResultArray();

            // Procesar imágenes de productos
            $productos = $this->procesarImagenesProductos($productos);

            // Obtener categorías con conteo
            $categorias = $this->obtenerCategoriasConConteo();

            // Calcular datos de paginación
            $totalPages = ceil($totalProductos / $perPage);

            $data = [
                'title' => 'Catálogo de Productos - Insumos_FAT',
                'activo' => 'catalogo',
                'productos' => $productos,
                'categorias' => $categorias,
                'totalProductos' => $totalProductos,
                'productosMostrados' => count($productos),
                'page' => $page,
                'totalPages' => $totalPages,
                'perPage' => $perPage,
                'filtros' => $filtros,
                'hayFiltrosActivos' => $this->hayFiltrosActivos($filtros)
            ];

            return view('catalogo/index', $data);

        } catch (\Exception $e) {
            log_message('error', 'Error en catálogo: ' . $e->getMessage());
            return redirect()->to('/')->with('error', 'Error al cargar el catálogo');
        }
    }

    /**
     * Ver detalles de un producto específico
     */
    public function producto($id = null)
    {
        if (!$id || !is_numeric($id)) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Producto no encontrado');
        }

        try {
            $producto = $this->productoModel->getProductoConCategoria($id);
            
            if (!$producto || !$producto['active']) {
                throw new \CodeIgniter\Exceptions\PageNotFoundException('Producto no encontrado');
            }

            // Procesar imagen del producto
            $producto = $this->procesarImagenProducto($producto);

            // Obtener productos relacionados
            $productosRelacionados = $this->obtenerProductosRelacionados($producto['categoria_id'], $id);

            // Registrar vista del producto (opcional)
            $this->registrarVistaProducto($id);

            $data = [
                'title' => esc($producto['nombre_prod']) . ' - Insumos_FAT',
                'activo' => 'catalogo',
                'producto' => $producto,
                'productosRelacionados' => $productosRelacionados,
                'breadcrumb' => $this->generarBreadcrumb($producto)
            ];

            return view('catalogo/producto', $data);

        } catch (\CodeIgniter\Exceptions\PageNotFoundException $e) {
            throw $e;
        } catch (\Exception $e) {
            log_message('error', 'Error al mostrar producto: ' . $e->getMessage());
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Producto no encontrado');
        }
    }

    /**
     * Buscar productos via AJAX
     */
    public function buscar()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400)->setJSON(['error' => 'Solicitud no válida']);
        }

        $termino = trim($this->request->getPost('termino'));
        
        // Validar longitud mínima
        if (strlen($termino) < 2) {
            return $this->response->setJSON(['productos' => []]);
        }

        // Sanitizar término de búsqueda
        $termino = esc($termino);

        try {
            $productos = $this->productoModel
                ->select('productos.id_producto, productos.nombre_prod, productos.precio, productos.imagen_url, productos.stock')
                ->where('productos.active', 1)
                ->where('productos.deleted_at', null)
                ->groupStart()
                    ->like('productos.nombre_prod', $termino)
                    ->orLike('productos.descripcion', $termino)
                ->groupEnd()
                ->orderBy('productos.nombre_prod', 'ASC')
                ->limit(10)
                ->get()
                ->getResultArray();

            // Procesar imágenes
            $productos = $this->procesarImagenesProductos($productos);

            return $this->response->setJSON([
                'productos' => $productos,
                'total' => count($productos)
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Error en búsqueda: ' . $e->getMessage());
            return $this->response->setStatusCode(500)->setJSON(['error' => 'Error en la búsqueda']);
        }
    }

    /**
     * Agregar producto al carrito via AJAX
     */
    public function agregarCarrito()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400)->setJSON(['error' => 'Solicitud no válida']);
        }

        $productoId = $this->request->getPost('producto_id');
        $cantidad = max(1, (int)($this->request->getPost('cantidad') ?? 1));

        // Validar datos
        $rules = [
            'producto_id' => 'required|numeric',
            'cantidad' => 'permit_empty|numeric|greater_than[0]'
        ];

        if (!$this->validation->setRules($rules)->run($this->request->getPost())) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Datos inválidos',
                'errors' => $this->validation->getErrors()
            ]);
        }

        try {
            // Verificar producto
            $producto = $this->productoModel->find($productoId);
            
            if (!$producto || !$producto['active']) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Producto no encontrado'
                ]);
            }

            // Verificar stock
            if ($producto['stock'] < $cantidad) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Stock insuficiente. Disponible: ' . $producto['stock']
                ]);
            }

            // Agregar al carrito (implementar según tu lógica de carrito)
            $carritoData = $this->agregarAlCarritoSession($producto, $cantidad);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Producto agregado al carrito',
                'producto' => [
                    'id' => $producto['id_producto'],
                    'nombre' => $producto['nombre_prod'],
                    'precio' => $producto['precio'],
                    'cantidad' => $cantidad
                ],
                'carrito' => $carritoData
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Error al agregar al carrito: ' . $e->getMessage());
            return $this->response->setStatusCode(500)->setJSON([
                'success' => false,
                'message' => 'Error interno del servidor'
            ]);
        }
    }

    /**
     * Obtener productos por categoría via AJAX
     */
    public function porCategoria($categoriaId = null)
    {
        if (!$this->request->isAJAX()) {
            return redirect()->to('/catalogo');
        }

        try {
            $builder = $this->productoModel
                ->select('productos.*, categorias.nombre as categoria_nombre')
                ->join('categorias', 'categorias.id_categoria = productos.categoria_id', 'left')
                ->where('productos.active', 1)
                ->where('productos.deleted_at', null);

            if ($categoriaId && $categoriaId !== 'all' && is_numeric($categoriaId)) {
                $builder->where('productos.categoria_id', $categoriaId);
            }

            $productos = $builder->orderBy('productos.nombre_prod', 'ASC')->get()->getResultArray();
            $productos = $this->procesarImagenesProductos($productos);

            return $this->response->setJSON([
                'productos' => $productos,
                'total' => count($productos)
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Error al obtener productos por categoría: ' . $e->getMessage());
            return $this->response->setStatusCode(500)->setJSON(['error' => 'Error interno']);
        }
    }

    /**
     * Obtener y validar filtros del request
     */
    private function obtenerFiltros(): array
    {
        $filtros = [
            'categoria' => $this->request->getGet('categoria'),
            'precio_min' => $this->validarPrecio($this->request->getGet('precio_min')),
            'precio_max' => $this->validarPrecio($this->request->getGet('precio_max')),
            'ordenar' => $this->validarOrdenamiento($this->request->getGet('ordenar')),
            'buscar' => trim($this->request->getGet('buscar') ?? '')
        ];

        // Validar categoría
        if ($filtros['categoria'] && !is_numeric($filtros['categoria']) && $filtros['categoria'] !== 'all') {
            $filtros['categoria'] = null;
        }

        // Validar rango de precios
        if ($filtros['precio_min'] && $filtros['precio_max'] && $filtros['precio_min'] > $filtros['precio_max']) {
            $temp = $filtros['precio_min'];
            $filtros['precio_min'] = $filtros['precio_max'];
            $filtros['precio_max'] = $temp;
        }

        return $filtros;
    }

    /**
     * Construir query de productos con filtros
     */
    private function construirQueryProductos(array $filtros)
    {
        $builder = $this->productoModel
            ->select('productos.*, categorias.nombre as categoria_nombre')
            ->join('categorias', 'categorias.id_categoria = productos.categoria_id', 'left')
            ->where('productos.active', 1)
            ->where('productos.deleted_at', null);

        // Filtro por categoría
        if ($filtros['categoria'] && $filtros['categoria'] !== 'all') {
            $builder->where('productos.categoria_id', $filtros['categoria']);
        }

        // Filtros de precio
        if ($filtros['precio_min']) {
            $builder->where('productos.precio >=', $filtros['precio_min']);
        }
        if ($filtros['precio_max']) {
            $builder->where('productos.precio <=', $filtros['precio_max']);
        }

        // Filtro de búsqueda
        if ($filtros['buscar']) {
            $builder->groupStart()
                ->like('productos.nombre_prod', $filtros['buscar'])
                ->orLike('productos.descripcion', $filtros['buscar'])
                ->orLike('categorias.nombre', $filtros['buscar'])
                ->groupEnd();
        }

        // Aplicar ordenamiento
        $this->aplicarOrdenamiento($builder, $filtros['ordenar']);

        return $builder;
    }

    /**
     * Aplicar ordenamiento al query
     */
    private function aplicarOrdenamiento($builder, string $ordenar): void
    {
        switch ($ordenar) {
            case 'precio_asc':
                $builder->orderBy('productos.precio', 'ASC');
                break;
            case 'precio_desc':
                $builder->orderBy('productos.precio', 'DESC');
                break;
            case 'nombre_desc':
                $builder->orderBy('productos.nombre_prod', 'DESC');
                break;
            case 'recientes':
                $builder->orderBy('productos.created_at', 'DESC');
                break;
            case 'vendidos':
                $builder->orderBy('productos.stock', 'ASC');
                break;
            default: // nombre_asc
                $builder->orderBy('productos.nombre_prod', 'ASC');
                break;
        }
    }

    /**
     * Procesar URLs de imágenes de productos
     */
    private function procesarImagenesProductos(array $productos): array
    {
        foreach ($productos as &$producto) {
            $producto = $this->procesarImagenProducto($producto);
        }
        return $productos;
    }

    /**
     * Procesar URL de imagen de un producto
     * Nuevo método que maneja correctamente las URLs de imagen
     */
    private function procesarImagenProducto(array $producto): array
    {
        $producto['imagen_procesada'] = false;
        $producto['imagen_url_completa'] = '';
        $producto['tiene_imagen'] = false;

        if (!empty($producto['imagen_url'])) {
            // Si la URL ya es completa (empieza con http)
            if (strpos($producto['imagen_url'], 'http') === 0) {
                $producto['imagen_url_completa'] = $producto['imagen_url'];
                $producto['imagen_procesada'] = true;
                $producto['tiene_imagen'] = true;
            } else {
                // Construir la ruta completa usando base_url('public')
                $rutaCompleta = base_url('public') . '/' . ltrim($producto['imagen_url'], '/');
                
                // Verificar si el archivo existe físicamente
                $rutaFisica = FCPATH . 'public/' . ltrim($producto['imagen_url'], '/');
                
                if (file_exists($rutaFisica)) {
                    $producto['imagen_url_completa'] = $rutaCompleta;
                    $producto['imagen_procesada'] = true;
                    $producto['tiene_imagen'] = true;
                } else {
                    // El archivo no existe, marcar como sin imagen
                    $producto['imagen_procesada'] = false;
                    $producto['tiene_imagen'] = false;
                    
                    // Log para debugging (opcional)
                    log_message('info', "Imagen no encontrada para producto {$producto['id_producto']}: {$rutaFisica}");
                }
            }
        }

        return $producto;
    }

    /**
     * Obtener categorías con conteo de productos
     */
    private function obtenerCategoriasConConteo(): array
    {
        try {
            return $this->categoriaModel->getCategoriasConConteo();
        } catch (\Exception $e) {
            log_message('error', 'Error al obtener categorías: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Obtener productos relacionados
     */
    private function obtenerProductosRelacionados(int $categoriaId, int $excludeId, int $limit = 4): array
    {
        if (!$categoriaId) {
            return [];
        }

        try {
            $productos = $this->productoModel
                ->select('productos.*, categorias.nombre as categoria_nombre')
                ->join('categorias', 'categorias.id_categoria = productos.categoria_id', 'left')
                ->where('productos.categoria_id', $categoriaId)
                ->where('productos.id_producto !=', $excludeId)
                ->where('productos.active', 1)
                ->where('productos.deleted_at', null)
                ->orderBy('RAND()')
                ->limit($limit)
                ->get()
                ->getResultArray();

            return $this->procesarImagenesProductos($productos);
        } catch (\Exception $e) {
            log_message('error', 'Error al obtener productos relacionados: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Validar valor de precio
     */
    private function validarPrecio($precio)
    {
        if ($precio && is_numeric($precio) && $precio >= 0) {
            return (float)$precio;
        }
        return null;
    }

    /**
     * Validar ordenamiento
     */
    private function validarOrdenamiento($ordenar): string
    {
        $ordenamientosValidos = ['precio_asc', 'precio_desc', 'nombre_asc', 'nombre_desc', 'recientes', 'vendidos'];
        return in_array($ordenar, $ordenamientosValidos) ? $ordenar : 'nombre_asc';
    }

    /**
     * Verificar si hay filtros activos
     */
    private function hayFiltrosActivos(array $filtros): bool
    {
        return !empty($filtros['categoria']) && $filtros['categoria'] !== 'all' ||
               !empty($filtros['precio_min']) ||
               !empty($filtros['precio_max']) ||
               !empty($filtros['buscar']);
    }

    /**
     * Generar breadcrumb para producto
     */
    private function generarBreadcrumb(array $producto): array
    {
        $breadcrumb = [
            ['nombre' => 'Inicio', 'url' => base_url()],
            ['nombre' => 'Catálogo', 'url' => base_url('catalogo')]
        ];

        if (!empty($producto['categoria_nombre'])) {
            $breadcrumb[] = [
                'nombre' => $producto['categoria_nombre'],
                'url' => base_url('catalogo?categoria=' . $producto['categoria_id'])
            ];
        }

        $breadcrumb[] = ['nombre' => $producto['nombre_prod'], 'url' => ''];

        return $breadcrumb;
    }

    /**
     * Registrar vista de producto (opcional)
     */
    private function registrarVistaProducto(int $productoId): void
    {
        // Implementar lógica para registrar vistas de productos
        // Útil para estadísticas y productos más vistos
    }

    /**
     * Agregar producto al carrito en sesión
     */
    private function agregarAlCarritoSession(array $producto, int $cantidad): array
    {
        $session = session();
        $carrito = $session->get('carrito') ?? [];

        $productoId = $producto['id_producto'];

        // Procesar imagen para el carrito
        $imagenCarrito = '';
        if (!empty($producto['imagen_url'])) {
            if (strpos($producto['imagen_url'], 'http') === 0) {
                $imagenCarrito = $producto['imagen_url'];
            } else {
                $imagenCarrito = base_url('public') . '/' . ltrim($producto['imagen_url'], '/');
            }
        }

        if (isset($carrito[$productoId])) {
            $carrito[$productoId]['cantidad'] += $cantidad;
        } else {
            $carrito[$productoId] = [
                'id' => $productoId,
                'nombre' => $producto['nombre_prod'],
                'precio' => $producto['precio'],
                'cantidad' => $cantidad,
                'imagen' => $imagenCarrito
            ];
        }

        $session->set('carrito', $carrito);

        return [
            'total_items' => array_sum(array_column($carrito, 'cantidad')),
            'total_precio' => array_sum(array_map(fn($item) => $item['precio'] * $item['cantidad'], $carrito))
        ];
    }

    /**
     * Determinar si un producto tiene poco stock
     */
    private function tienePocoStock(int $stock): bool
    {
        return $stock <= 5 && $stock > 0;
    }

    /**
     * Determinar si un producto está sin stock
     */
    private function sinStock(int $stock): bool
    {
        return $stock <= 0;
    }
}