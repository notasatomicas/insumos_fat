<?php

namespace App\Controllers;

class Contacto extends BaseController
{
    public function index(): void
    {
        $data['cont_adicional'] = view('adicional/leaflet.html').view('adicional/contacto_estilos.html').view('adicional/modal_development.html');
        $data['script_adicionales'] = '';
        $data['activo'] = 'contacto';
  
        $data['titulo'] = 'Insumos_FAT - Contacto';
        
        $data['navbar'] = view('layout/navbar');
        $data['footer'] = view('layout/footer');
  
        
        $data['contenido'] = view('contacto');
  
        echo view('plantilla', $data);
    }
}