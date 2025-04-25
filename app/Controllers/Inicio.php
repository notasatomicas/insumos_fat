<?php

namespace App\Controllers;

class Inicio extends BaseController
{
    public function index(): void
    {
      //esta es la parte que se usaria en caso de cargar hojas de estilos adicionales
      //o cualquier cosa necesaria en el header que sea necesario solo para este controlador en especifico
      $data['cont_adicional'] = '';
      $data['script_adicionales'] = view('adicional/script_development.js');
      $data['activo'] = 'inicio';

      //el titulo que va a aparecer en la pagina
      $data['titulo'] = 'Insumos_FAT - Inicio';
      /*$data['header'] = view('header', $data);*/

      //Todo lo que esta en la carpeta Views/layout (es decir las plantillas predefinidas estaticas)
      $data['navbar'] = view('layout/navbar');
      $data['footer'] = view('layout/footer', $data);

      //esta es el area donde cargo la vista del contenido
      $data['contenido'] = view('inicio').view('adicional/modal_development.html');



      //edit del dia 06/04 a las 12:14: no se de donde mierda
      //saque la idea para hacer algo asi de modular, ahora que veo es recomplicado

      //edit mas o menos a las 12:26: cambie las cosas de tener
      //esta forma -> $data['footer'] = view('footer', $data);
      // a la que esta actualmente porque no tenia sentido pasarle
      // nuevamente todo el arreglo a las pequeñas vistas si eso no se usa
      echo view('plantilla', $data);

      //para las 21:06 del 06/04 ya termine casi hasta su perfeccion la mierda esta
    }
}
