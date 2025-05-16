<?php

namespace App\Controllers;

class Contacto extends BaseController
{
    public function index(): void
    {
        $data['activo'] = 'contacto';
        $data['titulo'] = 'Insumos_FAT - Contacto';
  
        echo view('contacto', $data);
    }
}