<?php

namespace App\Controllers;

use App\Models\ProductoModel;
use App\Models\CategoriaModel;
use CodeIgniter\Controller;

class ProductoController extends Controller
{
    protected $productoModel;
    protected $categoriaModel;

    public function __construct()
    {
        $this->productoModel = new ProductoModel();
        $this->categoriaModel = new CategoriaModel();
    }

    // Mostrar lista de productos
    public function index()
    {
        $data = [
            'title' => 'Gestión de Productos',
            'productos' => $this->productoModel->getProductosConCategoria()
        ];

        return view('admin/productos/index', $data);
    }

    // Mostrar formulario para crear producto
    public function create()
    {
        $data = [
            'title' => 'Crear Producto',
            'categorias' => $this->categoriaModel->getCategoriasActivas(),
            'validation' => \Config\Services::validation()
        ];

        return view('admin/productos/create', $data);
    }

    // Guardar nuevo producto
    public function store()
    {
        $rules = [
            'nombre_prod' => 'required|min_length[3]|max_length[100]',
            'precio' => 'required|decimal',
            'stock' => 'required|integer',
            'categoria_id' => 'required|is_natural_no_zero',
            'imagen' => 'uploaded[imagen]|is_image[imagen]|max_size[imagen,2048]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }

        $data = [
            'nombre_prod' => $this->request->getPost('nombre_prod'),
            'descripcion' => $this->request->getPost('descripcion'),
            'precio' => $this->request->getPost('precio'),
            'stock' => $this->request->getPost('stock'),
            'categoria_id' => $this->request->getPost('categoria_id'),
            'active' => $this->request->getPost('active') ? 1 : 0
        ];

        // Manejar subida de imagen
        $imagen = $this->request->getFile('imagen');
        if ($imagen && $imagen->isValid() && !$imagen->hasMoved()) {
            $nombreImagen = $imagen->getRandomName();
            $imagen->move(ROOTPATH . 'public/uploads/productos', $nombreImagen);
            $data['imagen_url'] = 'uploads/productos/' . $nombreImagen;
        }

        if ($this->productoModel->insert($data)) {
            session()->setFlashdata('success', 'Producto creado exitosamente');
        } else {
            session()->setFlashdata('error', 'Error al crear el producto');
        }

        return redirect()->to('/admin/productos');
    }

    // Mostrar formulario para editar producto
    public function edit($id)
    {
        $producto = $this->productoModel->find($id);
        
        if (!$producto) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Producto no encontrado');
        }

        $data = [
            'title' => 'Editar Producto',
            'producto' => $producto,
            'categorias' => $this->categoriaModel->getCategoriasActivas(),
            'validation' => \Config\Services::validation()
        ];

        return view('productos/edit', $data);
    }

    // Actualizar producto
    public function update($id)
    {
        $producto = $this->productoModel->find($id);
        
        if (!$producto) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Producto no encontrado');
        }

        $rules = [
            'nombre_prod' => 'required|min_length[3]|max_length[100]',
            'precio' => 'required|decimal',
            'stock' => 'required|integer',
            'categoria_id' => 'required|is_natural_no_zero'
        ];

        // Solo validar imagen si se subió una nueva
        $imagen = $this->request->getFile('imagen');
        if ($imagen && $imagen->getName() !== '') {
            $rules['imagen'] = 'is_image[imagen]|max_size[imagen,2048]';
        }

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }

        $data = [
            'nombre_prod' => $this->request->getPost('nombre_prod'),
            'descripcion' => $this->request->getPost('descripcion'),
            'precio' => $this->request->getPost('precio'),
            'stock' => $this->request->getPost('stock'),
            'categoria_id' => $this->request->getPost('categoria_id'),
            'active' => $this->request->getPost('active') ? 1 : 0
        ];

        // Manejar nueva imagen
        if ($imagen && $imagen->isValid() && !$imagen->hasMoved()) {
            // Eliminar imagen anterior si existe
            if ($producto['imagen_url'] && file_exists(ROOTPATH . 'public/' . $producto['imagen_url'])) {
                unlink(ROOTPATH . 'public/' . $producto['imagen_url']);
            }
            
            $nombreImagen = $imagen->getRandomName();
            $imagen->move(ROOTPATH . 'public/uploads/productos', $nombreImagen);
            $data['imagen_url'] = 'uploads/productos/' . $nombreImagen;
        }

        if ($this->productoModel->update($id, $data)) {
            session()->setFlashdata('success', 'Producto actualizado exitosamente');
        } else {
            session()->setFlashdata('error', 'Error al actualizar el producto');
        }

        return redirect()->to('/productos');
    }

    // Eliminar producto (soft delete)
    public function delete($id)
    {
        $producto = $this->productoModel->find($id);
        
        if (!$producto) {
            session()->setFlashdata('error', 'Producto no encontrado');
            return redirect()->to('/productos');
        }

        if ($this->productoModel->delete($id)) {
            session()->setFlashdata('success', 'Producto eliminado exitosamente');
        } else {
            session()->setFlashdata('error', 'Error al eliminar el producto');
        }

        return redirect()->to('/productos');
    }

    // Ver detalles del producto
    public function show($id)
    {
        $producto = $this->productoModel->getProductoConCategoria($id);
        
        if (!$producto) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Producto no encontrado');
        }

        $data = [
            'title' => 'Detalles del Producto',
            'producto' => $producto
        ];

        return view('productos/show', $data);
    }
}