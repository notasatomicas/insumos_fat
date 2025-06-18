<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Inicio::index');
$routes->get('/home', 'Inicio::index');
$routes->get('/inicio', 'Inicio::index');

$routes->get('/contacto', 'ContactoController::index');
$routes->get('/contact', 'ContactoController::index');

$routes->get('/nosotros', 'Nosotros::index');
$routes->get('/about', 'Nosotros::index');

$routes->get('/comercializacion', 'Comercializacion::index');

$routes->get('/terminos', 'Terminos::index');
$routes->get('/tyc', 'Terminos::index');
$routes->get('/terminos_y_condiciones', 'Terminos::index');
$routes->get('/privacy', 'Terminos::index');

$routes->get('/en_construccion', 'Construccion::index');

// Rutas de autenticación
$routes->get('/auth', 'Auth::index');
$routes->post('/auth/login', 'Auth::login');
$routes->get('/auth/register', 'Auth::register');
$routes->post('/auth/doRegister', 'Auth::doRegister');
$routes->get('/auth/logout', 'Auth::logout');

// ===============================================
// RUTAS PÚBLICAS DE CONTACTO
// ===============================================

// Rutas alternativas más cortas para contacto público
$routes->get('contacto', 'ContactoController::index');
$routes->post('contacto/enviar-mensaje', 'ContactoController::enviar');

// ===============================================
// RUTAS PARA ADMINISTRADORES
// ===============================================
$routes->group('admin', ['filter' => 'admin'], function($routes) {
    // Dashboard principal
    $routes->get('dashboard', 'Admin::dashboard');
    
    // Gestión de usuarios
    $routes->get('users', 'Admin::users');
    $routes->get('toggleActive/(:num)', 'Admin::toggleActive/$1');
    $routes->get('toggleType/(:num)', 'Admin::toggleType/$1');
    $routes->get('deleteUser/(:num)', 'Admin::deleteUser/$1');
    $routes->get('editUser/(:num)', 'Admin::editUser/$1');
    $routes->post('updateUser/(:num)', 'Admin::updateUser/$1');

    // ===============================================
    // RUTAS PARA GESTIÓN DE PRODUCTOS
    // ===============================================
    
    // Listar todos los productos (GET admin/productos)
    $routes->get('productos', 'ProductoController::index');
    
    // Mostrar formulario para crear nuevo producto (GET admin/productos/create)
    $routes->get('productos/create', 'ProductoController::create');
    
    // Guardar nuevo producto (POST admin/productos)
    $routes->post('productos', 'ProductoController::store');
    
    // RUTAS ESPECÍFICAS PRIMERO (más específicas)
    // Mostrar formulario para editar producto (GET admin/productos/123/edit)
    $routes->get('productos/(:num)/edit', 'ProductoController::edit/$1');
    
    // Eliminar producto (GET admin/productos/123/delete)
    $routes->get('productos/(:num)/delete', 'ProductoController::delete/$1');
    
    // Alternativa para eliminar producto con POST (más seguro)
    $routes->post('productos/(:num)/delete', 'ProductoController::delete/$1');
    
    // RUTAS GENÉRICAS AL FINAL (menos específicas)
    // Actualizar producto específico (POST admin/productos/123)
    $routes->post('productos/(:num)', 'ProductoController::update/$1');
    
    // Ver detalles de un producto específico (GET admin/productos/123)
    $routes->get('productos/(:num)', 'ProductoController::show/$1');

    // Toggle status de producto
    $routes->get('productos/(:num)/toggle/(:num)', 'ProductoController::toggleStatus/$1/$2');

    // ===============================================
    // RUTAS PARA GESTIÓN DE CATEGORÍAS
    // ===============================================

    // Listar todas las categorías (GET admin/categorias)
    $routes->get('categorias', 'CategoriaController::index');

    // Mostrar formulario para crear nueva categoría (GET admin/categorias/create)
    $routes->get('categorias/create', 'CategoriaController::create');

    // Guardar nueva categoría (POST admin/categorias)
    $routes->post('categorias', 'CategoriaController::store');

    // Ver detalles de una categoría específica (GET admin/categorias/123)
    $routes->get('categorias/(:num)', 'CategoriaController::show/$1');

    // Mostrar formulario para editar categoría (GET admin/categorias/123/edit)
    $routes->get('categorias/(:num)/edit', 'CategoriaController::edit/$1');

    // Actualizar categoría específica (POST admin/categorias/123)
    $routes->post('categorias/(:num)', 'CategoriaController::update/$1');

    // Eliminar categoría (GET admin/categorias/123/delete)
    $routes->get('categorias/(:num)/delete', 'CategoriaController::delete/$1');

    // ===============================================
    // RUTAS PARA GESTIÓN DE CONTACTOS/MENSAJERÍA
    // ===============================================
    
    // Página principal de contactos (coincide con tu vista)
    // Página principal de contactos
    $routes->get('contactos', 'ContactoController::listar');
    
    // Rutas específicas para acciones CRUD
    $routes->get('contactos/listar', 'ContactoController::listar');
    $routes->get('contactos/ver/(:num)', 'ContactoController::ver/$1');
    
    // Cambiar estado (debe ser POST)
    $routes->post('contactos/cambiar-estado/(:num)', 'ContactoController::cambiarEstado/$1');
    
    // Eliminar contacto
    $routes->get('contactos/eliminar/(:num)', 'ContactoController::eliminar/$1');
    $routes->post('contactos/eliminar/(:num)', 'ContactoController::eliminar/$1');
});

//carrito rutas rutas
$routes->get('carrito', 'CarritoController::index');

// API Routes para el carrito
$routes->group('api', function($routes) {
    $routes->group('productos', function($routes) {
        $routes->post('obtener-por-ids', 'CarritoController::obtenerPorIds');
    });
    
    $routes->group('carrito', function($routes) {
        $routes->post('obtener-productos', 'CarritoController::obtenerProductos');
        $routes->post('actualizar-cantidad', 'CarritoController::actualizarCantidad');
        $routes->post('validar-disponibilidad', 'CarritoController::validarDisponibilidad');
        $routes->post('resumen', 'CarritoController::resumen');
    });
});

// ===============================================
// RUTAS DEL CATÁLOGO
// ===============================================
$routes->group('catalogo', function($routes) {
    $routes->get('/', 'CatalogoController::index');
    $routes->get('producto/(:num)', 'CatalogoController::producto/$1');
    $routes->post('buscar', 'CatalogoController::buscar');
    $routes->get('categoria/(:num)', 'CatalogoController::porCategoria/$1');
});

//rutas para el checkout
// Agregar estas rutas en tu app/Config/Routes.php:

// Checkout
$routes->get('checkout', 'CheckoutController::index');
$routes->get('checkout/resumen/(:num)', 'CheckoutController::obtenerResumen/$1');
$routes->post('checkout/procesarCompraDirecta', 'CheckoutController::procesarCompraDirecta');