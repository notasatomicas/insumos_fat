<?php

namespace App\Controllers;

use App\Models\ProductoModel;
use App\Models\CategoriaModel;
use CodeIgniter\Controller;

class CatalogoController extends Controller
{
    protected $productoModel;
    protected $categoriaModel;

    public function __construct()
    {
        $this->productoModel = new ProductoModel();
        $this->categoriaModel = new CategoriaModel();
    }

    // Mostrar catálogo de productos
    public function index()
    {
        // Obtener parámetros de filtrado y búsqueda
        $categoriaId = $this->request->getGet('categoria');
        $precioMin = $this->request->getGet('precio_min');
        $precioMax = $this->request->getGet('precio_max');
        $ordenar = $this->request->getGet('ordenar') ?? 'nombre_asc';
        $buscar = $this->request->getGet('buscar');
        $page = $this->request->getGet('page') ?? 1;
        $perPage = 12; // Productos por página

        // Construir query base
        $builder = $this->productoModel->select('productos.*, categorias.nombre as categoria_nombre')
                                      ->join('categorias', 'categorias.id_categoria = productos.categoria_id', 'left')
                                      ->where('productos.active', 1)
                                      ->where('productos.deleted_at', null);

        // Aplicar filtros
        if ($categoriaId && $categoriaId != 'all') {
            $builder->where('productos.categoria_id', $categoriaId);
        }

        if ($precioMin && is_numeric($precioMin)) {
            $builder->where('productos.precio >=', $precioMin);
        }

        if ($precioMax && is_numeric($precioMax)) {
            $builder->where('productos.precio <=', $precioMax);
        }

        if ($buscar) {
            $builder->groupStart()
                   ->like('productos.nombre_prod', $buscar)
                   ->orLike('productos.descripcion', $buscar)
                   ->orLike('categorias.nombre', $buscar)
                   ->groupEnd();
        }

        // Aplicar ordenamiento
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
                // Por ahora ordenar por stock bajo (simula más vendidos)
                $builder->orderBy('productos.stock', 'ASC');
                break;
            default: // nombre_asc
                $builder->orderBy('productos.nombre_prod', 'ASC');
                break;
        }

        // Contar total de productos para paginación
        $totalProductos = $builder->countAllResults(false);

        // Obtener productos de la página actual
        $productos = $builder->limit($perPage, ($page - 1) * $perPage)->get()->getResultArray();

        // Obtener categorías activas con conteo
        $categorias = $this->categoriaModel->getCategoriasConConteo();

        // Calcular paginación
        $totalPages = ceil($totalProductos / $perPage);

        $data = [
            'title' => 'Insumos_FAT - Catálogo',
            'activo' => 'catalogo',
            'productos' => $productos,
            'categorias' => $categorias,
            'totalProductos' => $totalProductos,
            'productosMostrados' => count($productos),
            'page' => $page,
            'totalPages' => $totalPages,
            'perPage' => $perPage,
            'filtros' => [
                'categoria' => $categoriaId,
                'precio_min' => $precioMin,
                'precio_max' => $precioMax,
                'ordenar' => $ordenar,
                'buscar' => $buscar
            ]
        ];

        return view('catalogo/index', $data);
    }

    // Ver detalles de un producto específico
    public function producto($id)
    {
        $producto = $this->productoModel->getProductoConCategoria($id);
        
        if (!$producto || !$producto['active']) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Producto no encontrado');
        }

        // Obtener productos relacionados de la misma categoría
        $productosRelacionados = [];
        if ($producto['categoria_id']) {
            $productosRelacionados = $this->productoModel->select('productos.*, categorias.nombre as categoria_nombre')
                                                        ->join('categorias', 'categorias.id_categoria = productos.categoria_id', 'left')
                                                        ->where('productos.categoria_id', $producto['categoria_id'])
                                                        ->where('productos.id_producto !=', $id)
                                                        ->where('productos.active', 1)
                                                        ->where('productos.deleted_at', null)
                                                        ->limit(4)
                                                        ->get()
                                                        ->getResultArray();
        }

        $data = [
            'title' => $producto['nombre_prod'] . ' - Insumos_FAT',
            'activo' => 'catalogo',
            'producto' => $producto,
            'productosRelacionados' => $productosRelacionados
        ];

        return view('catalogo/producto', $data);
    }

    // Buscar productos (AJAX)
    public function buscar()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['error' => 'Solicitud no válida']);
        }

        $termino = $this->request->getPost('termino');
        
        if (strlen($termino) < 2) {
            return $this->response->setJSON(['productos' => []]);
        }

        $productos = $this->productoModel->select('productos.id_producto, productos.nombre_prod, productos.precio, productos.imagen_url')
                                        ->where('productos.active', 1)
                                        ->where('productos.deleted_at', null)
                                        ->groupStart()
                                        ->like('productos.nombre_prod', $termino)
                                        ->orLike('productos.descripcion', $termino)
                                        ->groupEnd()
                                        ->limit(10)
                                        ->get()
                                        ->getResultArray();

        return $this->response->setJSON(['productos' => $productos]);
    }

    // Agregar al carrito (por ahora solo simulado)
    public function agregarCarrito()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['error' => 'Solicitud no válida']);
        }

        $productoId = $this->request->getPost('producto_id');
        $cantidad = $this->request->getPost('cantidad') ?? 1;

        // Verificar que el producto existe y está activo
        $producto = $this->productoModel->find($productoId);
        
        if (!$producto || !$producto['active']) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Producto no encontrado'
            ]);
        }

        // Verificar stock disponible
        if ($producto['stock'] < $cantidad) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Stock insuficiente'
            ]);
        }

        // Por ahora solo simular que se agregó al carrito
        // Aquí implementarías la lógica real del carrito (sesión, base de datos, etc.)
        
        return $this->response->setJSON([
            'success' => true,
            'message' => 'Producto agregado al carrito',
            'producto' => [
                'id' => $producto['id_producto'],
                'nombre' => $producto['nombre_prod'],
                'precio' => $producto['precio'],
                'cantidad' => $cantidad
            ]
        ]);
    }

    // Obtener productos por categoría (AJAX)
    public function porCategoria($categoriaId = null)
    {
        if (!$this->request->isAJAX()) {
            return redirect()->to('/catalogo');
        }

        $builder = $this->productoModel->select('productos.*, categorias.nombre as categoria_nombre')
                                      ->join('categorias', 'categorias.id_categoria = productos.categoria_id', 'left')
                                      ->where('productos.active', 1)
                                      ->where('productos.deleted_at', null);

        if ($categoriaId && $categoriaId != 'all') {
            $builder->where('productos.categoria_id', $categoriaId);
        }

        $productos = $builder->orderBy('productos.nombre_prod', 'ASC')->get()->getResultArray();

        return $this->response->setJSON([
            'productos' => $productos,
            'total' => count($productos)
        ]);
    }

    // Helper function para determinar si un producto tiene poco stock
    private function tienePocoStock($stock)
    {
        return $stock <= 5; // Consideramos "poco stock" cuando hay 5 o menos unidades
    }
}