<?php

namespace App\Controllers;

class Comercializacion extends BaseController
{
    public function index(): void
    {
        $data['activo'] = 'comercializacion';
        $data['titulo'] = 'Insumos_FAT - Comercializacion';
  
        echo view('comercializacion', $data);
    }
}
