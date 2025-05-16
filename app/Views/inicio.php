<!DOCTYPE html>
<html>
<head>
    <!-- la cabecera de todas las paginas -->
    <?= view('layout/base_head') ?>
    
    <?= view('adicional/modal_development_estilos.html') ?>
</head>
<body>

    <!-- Navbar -->
    <?= view('layout/navbar') ?>

    <!-- Contenido específico de la página -->
    <?= view('contenido/inicio') ?>

    <!-- El modal "en desarrollo" -->
    <?= view('adicional/modal_development.html') ?>

    <!-- Footer -->
    <?= view('layout/footer') ?>

    <!-- Scripts generales que se usan en todas las paginas -->
    <?= view('layout/base_scripts') ?>

    <!-- Scripts del modal "en desarrollo" -->
    <?= view('adicional/script_development.js') ?>
</body>
</html>