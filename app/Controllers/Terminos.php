<?php

namespace App\Controllers;

class Terminos extends BaseController
{
    public function index(): void
    {
        $data['cont_adicional'] = '';
        $data['script_adicionales'] = '';
        $data['activo'] = 'tyc';
  
        $data['titulo'] = 'Insumos_FAT - Terminos y condiciones';
        
        $data['navbar'] = view('layout/navbar');
        $data['footer'] = view('layout/footer');
  
        
        $data['contenido'] = view('terminos');
  
        echo view('plantilla', $data);
    }
}
