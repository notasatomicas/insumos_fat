<?php

namespace App\Controllers;

class Terminos extends BaseController
{
    public function index(): void
    {
        $data['activo'] = 'tyc';
        $data['titulo'] = 'Insumos_FAT - Terminos y condiciones';
  
        echo view('terminos', $data);
    }
}
