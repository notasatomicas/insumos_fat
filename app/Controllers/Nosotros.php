<?php

namespace App\Controllers;

class Nosotros extends BaseController
{
    public function index(): void
    {
        $data['activo'] = 'nosotros';
        $data['titulo'] = 'Insumos_FAT - Quienes somos';
  
        echo view('nosotros', $data);
    }
}
