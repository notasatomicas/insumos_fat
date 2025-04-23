<?php

namespace App\Controllers;

class Comercializacion extends BaseController
{
    public function index(): void
    {
        $data['cont_adicional'] = '';
        $data['script_adicionales'] = '';
        $data['activo'] = 'comercializacion';
  
        $data['titulo'] = 'Insumos_FAT - Comercializacion';
        
        $data['navbar'] = view('layout/navbar');
        $data['footer'] = view('layout/footer');
  
        
        $data['contenido'] = view('comercializacion');
  
        echo view('plantilla', $data);
    }
}
