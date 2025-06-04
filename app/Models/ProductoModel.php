<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductoModel extends Model
{
    protected $table            = 'productos';
    protected $primaryKey       = 'id_producto';
    protected $allowedFields    = [
        'nombre_prod', 'descripcion', 'precio', 'stock',
        'categoria_id', 'imagen_url', 'active', 'deleted_at'
    ];
    
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';
    protected $deletedField     = 'deleted_at';

    protected $useSoftDeletes   = true;

    protected $validationRules  = [
        'nombre_prod' => 'required|min_length[3]|max_length[100]',
        'precio'      => 'required|decimal',
        'stock'       => 'required|integer',
        //'categoria_id'=> 'required|is_natural_no_zero',
    ];

    protected $validationMessages = [
        'nombre_prod' => [
            'required' => 'El nombre del producto es obligatorio',
            'min_length' => 'El nombre debe tener al menos 3 caracteres',
            'max_length' => 'El nombre no puede exceder los 100 caracteres'
        ],
        'precio' => [
            'required' => 'El precio es obligatorio',
            'decimal' => 'El precio debe ser un número decimal válido'
        ],
        'stock' => [
            'required' => 'El stock es obligatorio',
            'integer' => 'El stock debe ser un número entero'
        ],
        'categoria_id' => [
            'required' => 'La categoría es obligatoria',
            'is_natural_no_zero' => 'Debe seleccionar una categoría válida'
        ]
    ];

    // Método para obtener productos con sus categorías
    public function getProductosConCategoria()
    {
        return $this->select('productos.*, categorias.nombre as categoria_nombre')
                    ->join('categorias', 'categorias.id_categoria = productos.categoria_id')
                    ->where('productos.deleted_at', null)
                    ->findAll();
    }

    // Método para obtener un producto específico con su categoría
    public function getProductoConCategoria($id)
    {
        return $this->select('productos.*, categorias.nombre as categoria_nombre')
                    ->join('categorias', 'categorias.id_categoria = productos.categoria_id')
                    ->where('productos.id_producto', $id)
                    ->where('productos.deleted_at', null)
                    ->first();
    }
}
