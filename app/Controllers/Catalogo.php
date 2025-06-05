<?php

namespace App\Controllers;

class Catalogo extends BaseController
{
    public function index(): void
    {
        $data['activo'] = 'catalogo';
        $data['titulo'] = 'Insumos_FAT - Catalogo';
  
        echo view('catalogo/index', $data);
    }
}
