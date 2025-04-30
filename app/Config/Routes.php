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