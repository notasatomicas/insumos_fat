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

// Rutas para administradores
$routes->group('admin', ['filter' => 'admin'], function($routes) {
    $routes->get('dashboard', 'Admin\Dashboard::index');
    
    // Gestión de productos
    $routes->get('productos', 'Admin\Productos::index');
    $routes->get('productos/nuevo', 'Admin\Productos::nuevo');
    $routes->post('productos/crear', 'Admin\Productos::crear');
    $routes->get('productos/editar/(:num)', 'Admin\Productos::editar/$1');
    $routes->post('productos/actualizar/(:num)', 'Admin\Productos::actualizar/$1');
    $routes->get('productos/eliminar/(:num)', 'Admin\Productos::eliminar/$1');
    
    // Gestión de usuarios
    $routes->get('usuarios', 'Admin\Usuarios::index');
    $routes->get('usuarios/nuevo', 'Admin\Usuarios::nuevo');
    $routes->post('usuarios/crear', 'Admin\Usuarios::crear');
    $routes->get('usuarios/editar/(:num)', 'Admin\Usuarios::editar/$1');
    $routes->post('usuarios/actualizar/(:num)', 'Admin\Usuarios::actualizar/$1');
    $routes->get('usuarios/eliminar/(:num)', 'Admin\Usuarios::eliminar/$1');
    
    // Gestión de pedidos
    $routes->get('pedidos', 'Admin\Pedidos::index');
    $routes->get('pedidos/detalle/(:num)', 'Admin\Pedidos::detalle/$1');
    $routes->post('pedidos/actualizar-estado/(:num)', 'Admin\Pedidos::actualizarEstado/$1');
});