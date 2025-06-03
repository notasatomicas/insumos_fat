<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Inicio::index');
$routes->get('/home', 'Inicio::index');
$routes->get('/inicio', 'Inicio::index');

$routes->get('/contacto', 'Contacto::index');
$routes->get('/contact', 'Contacto::index');

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

// Rutas protegidas para usuarios
$routes->group('perfil', ['filter' => 'auth'], function($routes) {
    $routes->get('/', 'Perfil::index');
    $routes->get('editar', 'Perfil::editar');
    $routes->post('actualizar', 'Perfil::actualizar');
});

$routes->group('carrito', ['filter' => 'auth'], function($routes) {
    $routes->get('/', 'Carrito::index');
    $routes->post('agregar', 'Carrito::agregar');
    $routes->post('actualizar', 'Carrito::actualizar');
    $routes->get('eliminar/(:num)', 'Carrito::eliminar/$1');
    $routes->get('vaciar', 'Carrito::vaciar');
    $routes->get('checkout', 'Carrito::checkout');
});

$routes->group('compras', ['filter' => 'auth'], function($routes) {
    $routes->get('/', 'Compras::index');
    $routes->get('detalle/(:num)', 'Compras::detalle/$1');
});

// Rutas para administradores - OPCIÓN 1: Utilizando el controlador Admin.php
$routes->group('admin', ['filter' => 'admin'], function($routes) {
    $routes->get('dashboard', 'Admin::dashboard');
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
    
    // Ver detalles de un producto específico (GET admin/productos/123)
    $routes->get('productos/(:num)', 'ProductoController::show/$1');
    
    // Mostrar formulario para editar producto (GET admin/productos/123/edit)
    $routes->get('productos/(:num)/edit', 'ProductoController::edit/$1');
    
    // Actualizar producto específico (POST admin/productos/123)
    $routes->post('productos/(:num)', 'ProductoController::update/$1');
    
    // Eliminar producto (GET admin/productos/123/delete)
    $routes->get('productos/(:num)/delete', 'ProductoController::delete/$1');
    
    // Alternativa para eliminar producto con POST (más seguro)
    $routes->post('productos/(:num)/delete', 'ProductoController::delete/$1');
});

$routes->get('/debug', 'Depu::index');