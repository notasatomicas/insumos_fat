<?php

namespace App\Controllers;

class Nosotros extends BaseController
{
    public function index(): void
    {
        $data['cont_adicional'] = '';
        $data['script_adicionales'] = '';
        $data['activo'] = 'nosotros';
  
        $data['titulo'] = 'Insumos_FAT - Quienes somos';
        
        $data['navbar'] = view('layout/navbar');
        $data['footer'] = view('layout/footer');
  
        
        $data['contenido'] = view('about');
  
        echo view('plantilla', $data);
    }
}
