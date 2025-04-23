<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="./public/assets/css/bootstrap.min.css"><!-- el bootstrap propiamente dicho -->
    <link rel="stylesheet" href="./public/assets/bootstrap-icons/font/bootstrap-icons.min.css"><!-- iconos de bootstrap -->
    <link rel="shortcut icon" href="./public/assets/img/icon.png" type="image/x-icon">

    <!--
      En esta parte pongo esta referencia como acceso directo para ir colocando
      distintas hojas de estilo segun vaya siendo necesario y depende de lo que
      vaya necesitando cargar (aca abajo)
   -->
   <?= $cont_adicional ?>

    <!-- Esta parte hace referencia al titulo, que va a venir desde una
          variable que tengo guardada en el controlador, es decir que
          va a tomar el valor que le vayamos pasando desde el controlador
    -->
    <title><?= $titulo ?></title>

    <link rel="stylesheet" href="./public/assets/css/animate.css">
    <link rel="stylesheet" href="./public/assets/css/hoverme.css">
</head>
<body>
    <!-- Apartado para cargar la vista del navbar que esta definida en Views/navbar.php -->
    <?= $navbar ?>
    <!-- Apartado para cargar la vista de esta pagina en especifico -->
    <?= $contenido?>

    <!-- Apartado para cargar la vista del footer que esta definida en Views/footer.php -->
    <?= $footer ?>

    <!--
      En esta parte hago una referencia como la que se encuentra en el <head>
      con la misma finalidad, salvo que en vez de hojas de estilo, aca voy a ir poniendo
      los scripts que puedan ser necesarios cargarse en una pagina en especifico, pero no en
      otras.

      Edit de mas tarde: lpm como cuesta encontrarle nombre a las variables
     -->

    <script>
          document.addEventListener('DOMContentLoaded', () => {
              // Seleccionar el elemento que tiene la clase 'active' actualmente
              const activeElement = document.querySelector('.nav-link.active');

              // Quitar la clase 'active' del elemento actual
              if (activeElement) {
                  activeElement.classList.remove('active');
              }

              // Seleccionar el elemento con el id 'btn_nav_tyc' y agregar la clase 'active'
              const elemento_activo = document.getElementById('btn_nav_<?= $activo ?>');
              if (elemento_activo) {
                  elemento_activo.classList.add('active');
                  elemento_activo.classList.remove('hvr-underline-from-center');
              }
          });
      </script>

    <?= $script_adicionales ?>
    <script src="./public/assets/js/bootstrap.bundle.js" charset="utf-8"></script>
</body>
</html>
