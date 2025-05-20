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
    
    // Si necesitas agregar las rutas para productos y pedidos, puedes agregarlas aquí
    // o crearlas como controladores separados
});

$routes->get('/debug', 'Depu::index');