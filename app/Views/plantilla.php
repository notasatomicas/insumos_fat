<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="<?= base_url('public/assets/css/bootstrap.min.css') ?>"><!-- el bootstrap propiamente dicho -->
    <link rel="shortcut icon" href="<?= base_url('public/assets/img/icon.png') ?>" type="image/x-icon">

    <!-- iconos de fontawesome -->
    <link href="<?= base_url('public/assets/fontawesome/css/all.css') ?>" rel="stylesheet" />

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

    <link rel="stylesheet" href="<?= base_url('public/assets/css/animate.css') ?>">
    <link rel="stylesheet" href="<?= base_url('public/assets/css/hoverme.css') ?>">

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

       // Si hay un enlace activo
       if (activeElement) {
           // Buscar el elemento padre (li) que tiene la clase 'hvr-underline-from-center'
           const parentElement = activeElement.closest('li.hvr-underline-from-center');

           // Si se encuentra el padre, quitarle la clase 'hvr-underline-from-center'
           if (parentElement) {
               parentElement.classList.remove('hvr-underline-from-center');
           }

           // Quitar las clases de animación (si existen) del icono (i) dentro del enlace activo
           const icon = activeElement.querySelector('i');
           if (icon) {
               icon.classList.remove('animate__animated', 'animate__heartBeat', 'animate__infinite');
           }
       }

       // Seleccionar el elemento con el id 'btn_nav_<?= $activo ?>' y agregar la clase 'active'
       const elementoActivo = document.getElementById('btn_nav_<?= $activo ?>');
       if (elementoActivo) {
           // Agregar la clase 'active' al nuevo elemento y quitar la clase 'hvr-underline-from-center' del padre
           elementoActivo.classList.add('active');

           // Buscar el padre (li) del nuevo enlace y quitar la clase 'hvr-underline-from-center'
           const parentElement = elementoActivo.closest('li.hvr-underline-from-center');
           if (parentElement) {
               parentElement.classList.remove('hvr-underline-from-center');
           }

           // Obtener el icono dentro del nuevo elemento activo
           const icon = elementoActivo.querySelector('i');
           if (icon) {
               // Agregar las clases de animación al icono del nuevo enlace activo
               icon.classList.add('animate__animated', 'animate__heartBeat', 'animate__infinite');
           }
       }
   });
</script>



    <?= $script_adicionales ?>
    <script src="<?= base_url('public/assets/js/bootstrap.bundle.js') ?>" charset="utf-8"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
