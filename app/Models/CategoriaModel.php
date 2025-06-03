<?php

namespace App\Models;

use CodeIgniter\Model;

class CategoriaModel extends Model
{
    protected $table            = 'categorias';
    protected $primaryKey       = 'id_categoria';
    protected $allowedFields    = ['nombre', 'descripcion', 'estado', 'deleted_at'];
    
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';
    protected $deletedField     = 'deleted_at';

    protected $useSoftDeletes   = true;

    protected $validationRules  = [
        'nombre' => 'required|min_length[3]|max_length[100]',
    ];

    protected $validationMessages = [
        'nombre' => [
            'required' => 'El nombre de la categoría es obligatorio',
            'min_length' => 'El nombre debe tener al menos 3 caracteres',
            'max_length' => 'El nombre no puede exceder los 100 caracteres'
        ]
    ];

    // Método para obtener categorías activas
    public function getCategoriasActivas()
    {
        return $this->where('estado', 1)
                    ->where('deleted_at', null)
                    ->findAll();
    }

    // Método para obtener categorías con conteo de productos
    public function getCategoriasConConteo()
    {
        return $this->select('categorias.*, COUNT(productos.id_producto) as total_productos')
                    ->join('productos', 'productos.categoria_id = categorias.id_categoria', 'left')
                    ->where('categorias.deleted_at', null)
                    ->groupBy('categorias.id_categoria')
                    ->findAll();
    }
}