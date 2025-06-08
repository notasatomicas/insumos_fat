<?php

namespace App\Controllers;

use App\Models\ProductoModel;
use App\Models\CategoriaModel;

class Inicio extends BaseController
{
    protected $productoModel;
    protected $categoriaModel;

    public function __construct()
    {
        $this->productoModel = new ProductoModel();
        $this->categoriaModel = new CategoriaModel();
    }

    public function index()
    {
        try {
            // Obtener productos con stock bajo (menos de 10 unidades)
            $productosStockBajo = $this->obtenerProductosStockBajo();

            $data = [
                'activo' => 'inicio',
                'titulo' => 'Insumos_FAT - Inicio',
                'productosStockBajo' => $productosStockBajo
            ];

            return view('inicio', $data);

        } catch (\Exception $e) {
            log_message('error', 'Error en página de inicio: ' . $e->getMessage());
            
            // En caso de error, mostrar página sin productos
            $data = [
                'activo' => 'inicio',
                'titulo' => 'Insumos_FAT - Inicio',
                'productosStockBajo' => []
            ];

            return view('inicio', $data);
        }
    }

    /**
     * Obtener productos con stock bajo (menos de 10 unidades)
     */
    private function obtenerProductosStockBajo(int $limite = 6): array
    {
        try {
            $productos = $this->productoModel
                ->select('productos.*, categorias.nombre as categoria_nombre')
                ->join('categorias', 'categorias.id_categoria = productos.categoria_id', 'left')
                ->where('productos.active', 1)
                ->where('productos.deleted_at', null)
                ->where('productos.stock <', 10)
                ->where('productos.stock >', 0) // Solo productos con stock disponible
                ->orderBy('productos.stock', 'ASC') // Los de menor stock primero
                ->orderBy('productos.nombre_prod', 'ASC')
                ->limit($limite)
                ->get()
                ->getResultArray();

            // Procesar imágenes de productos
            return $this->procesarImagenesProductos($productos);

        } catch (\Exception $e) {
            log_message('error', 'Error al obtener productos con stock bajo: ' . $e->getMessage());
            return [];
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
}