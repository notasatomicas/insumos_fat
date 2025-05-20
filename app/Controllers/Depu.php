<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class Depu extends Controller
{
    public function index()
    {
        echo "<h2>Debug Info</h2>";
        echo "<h3>Routes</h3>";
        $routes = \Config\Services::routes();
        echo "<pre>";
        print_r($routes->getRoutes());
        echo "</pre>";
        
        echo "<h3>Filters</h3>";
        $filters = \Config\Services::filters();
        echo "<pre>";
        print_r(get_class_vars(\Config\Filters::class));
        echo "</pre>";
        
        echo "<h3>Session Data</h3>";
        echo "<pre>";
        print_r(session()->get());
        echo "</pre>";
        
        return;
    }
}