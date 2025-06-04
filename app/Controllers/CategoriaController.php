<?php

namespace App\Controllers;

use App\Models\CategoriaModel;
use CodeIgniter\Controller;

class CategoriaController extends Controller
{
    protected $categoriaModel;

    public function __construct()
    {
        $this->categoriaModel = new CategoriaModel();
    }

    // Mostrar lista de categorías
    public function index()
    {
        $data = [
            'title' => 'Gestión de Categorías',
            'categorias' => $this->categoriaModel->getCategoriasConConteo()
        ];

        return view('categorias/index', $data);
    }

    // Mostrar formulario para crear categoría
    public function create()
    {
        $data = [
            'title' => 'Crear Categoría',
            'validation' => \Config\Services::validation()
        ];

        return view('categorias/create', $data);
    }

    // Guardar nueva categoría
    public function store()
    {
        $rules = [
            'nombre' => 'required|min_length[3]|max_length[100]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }

        $data = [
            'nombre' => $this->request->getPost('nombre'),
            'descripcion' => $this->request->getPost('descripcion'),
            'estado' => $this->request->getPost('estado') ? 1 : 0
        ];

        if ($this->categoriaModel->insert($data)) {
            session()->setFlashdata('success', 'Categoría creada exitosamente');
        } else {
            session()->setFlashdata('error', 'Error al crear la categoría');
        }

        return redirect()->to('admin/categorias');
    }

    // Mostrar formulario para editar categoría
    public function edit($id)
    {
        $categoria = $this->categoriaModel->find($id);
        
        if (!$categoria) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Categoría no encontrada');
        }

        $data = [
            'title' => 'Editar Categoría',
            'categoria' => $categoria,
            'validation' => \Config\Services::validation()
        ];

        return view('categorias/edit', $data);
    }

    // Actualizar categoría
    public function update($id)
    {
        $categoria = $this->categoriaModel->find($id);
        
        if (!$categoria) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Categoría no encontrada');
        }

        $rules = [
            'nombre' => 'required|min_length[3]|max_length[100]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }

        $data = [
            'nombre' => $this->request->getPost('nombre'),
            'descripcion' => $this->request->getPost('descripcion'),
            'estado' => $this->request->getPost('estado') ? 1 : 0
        ];

        if ($this->categoriaModel->update($id, $data)) {
            session()->setFlashdata('success', 'Categoría actualizada exitosamente');
        } else {
            session()->setFlashdata('error', 'Error al actualizar la categoría');
        }

        return redirect()->to('admin/categorias');
    }

    // Eliminar categoría (soft delete)
    public function delete($id)
    {
        $categoria = $this->categoriaModel->find($id);
        
        if (!$categoria) {
            session()->setFlashdata('error', 'Categoría no encontrada');
            return redirect()->to('admin/categorias');
        }

        // Verificar si tiene productos asociados
        $db = \Config\Database::connect();
        $builder = $db->table('productos');
        $productosAsociados = $builder->where('categoria_id', $id)
                                    ->where('deleted_at', null)
                                    ->countAllResults();

        if ($productosAsociados > 0) {
            session()->setFlashdata('error', 'No se puede eliminar la categoría porque tiene productos asociados');
            return redirect()->to('admin/categorias');
        }

        if ($this->categoriaModel->delete($id)) {
            session()->setFlashdata('success', 'Categoría eliminada exitosamente');
        } else {
            session()->setFlashdata('error', 'Error al eliminar la categoría');
        }

        return redirect()->to('admin/categorias');
    }

    // Ver detalles de la categoría
    public function show($id)
    {
        $categoria = $this->categoriaModel->find($id);
        
        if (!$categoria) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Categoría no encontrada');
        }

        // Obtener productos de esta categoría
        $db = \Config\Database::connect();
        $builder = $db->table('productos');
        $productos = $builder->where('categoria_id', $id)
                            ->where('deleted_at', null)
                            ->get()
                            ->getResultArray();

        $data = [
            'title' => 'Detalles de la Categoría',
            'categoria' => $categoria,
            'productos' => $productos
        ];

        return view('categorias/show', $data);
    }
}