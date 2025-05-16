<?php

namespace App\Controllers;

class Inicio extends BaseController
{
    public function index(): void
    {
        $data['activo'] = 'inicio';
        $data['titulo'] = 'Insumos_FAT - Inicio';
  
        echo view('inicio', $data);
    }
}
