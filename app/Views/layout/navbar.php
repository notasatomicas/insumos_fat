<?php
  $uri = service('uri');
  $segment = $uri->getSegment(1);

  function esActivo($ruta, $segment) {
    return $ruta === $segment || ($ruta === 'inicio' && $segment === '');
  }
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark" aria-label="Offcanvas navbar large">
    <div class="container-fluid">
        <a class="navbar-brand animate__bounceIn" href="<?= base_url('/') ?>">
            <img src="<?= base_url('public/assets/img/icon.png') ?>" alt="Logo" width="57" height="50" class="d-inline-block">
            Insumos_FAT
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar2"
            aria-controls="offcanvasNavbar2" aria-label="Toggle navigation">
            <i class="fa-solid fa-bars"></i>
        </button>

        <div class="offcanvas offcanvas-end text-bg-dark" tabindex="-1" id="offcanvasNavbar2"
            aria-labelledby="offcanvasNavbar2Label">
            <div class="offcanvas-header">
                <button type="button" class="btn" data-bs-dismiss="offcanvas" aria-label="Close" style="color: red;">
                    <i class="fa-solid fa-circle-xmark" style="color: red !important;"></i>
                </button>
            </div>

            <div class="offcanvas-body">
                <ul class="navbar-nav nav-pills justify-content-end flex-grow-1 pe-3">

                    <!-- INICIO -->
                    <?php if (session()->get('id_usuario') && session()->get('isLoggedIn') && session()->get('isAdmin')): ?>
                        <!-- Sesión iniciada como administrador -->
                        <li>
                            <a class="nav-link text-center me-lg-2 px-2 px-xl-4"
                                href="<?= base_url('admin/dashboard') ?>">
                                <i class="ms-md-0 me-1 fa-solid fa-dashboard"></i>
                                Dashboard
                            </a>
                        </li>
                    <?php else: ?>
                        <li class="<?= esActivo('inicio', $segment) ? 'nav-item mx-5 mx-sm-4 mx-lg-0' : 'hvr-underline-from-center nav-item mx-5 mx-sm-4 mx-lg-0' ?>">
                            <a class="nav-link text-center me-lg-2 px-2 px-xl-4 <?= esActivo('inicio', $segment) ? 'active' : '' ?>"
                                href="<?= base_url('inicio') ?>">
                                <i class="ms-md-0 me-1 fa-solid fa-house <?= esActivo('inicio', $segment) ? 'animate__animated animate__heartBeat animate__infinite' : '' ?>"></i>
                                Inicio
                            </a>
                        </li>
                    <?php endif; ?>

                    <!-- COMERCIALIZACION -->
                    <?php if (session()->get('id_usuario') && session()->get('isLoggedIn') && session()->get('isAdmin')): ?>
                        <!-- Sesión iniciada como administrador -->
                         <li>
                            <a class="nav-link text-center me-lg-2 px-2 px-xl-4"
                                href="<?= base_url('admin/users') ?>">
                                <i class="ms-md-0 me-1 fa-solid fa-user"></i>
                                Usuarios
                            </a>
                        </li>
                    <?php else: ?>
                        <li class="<?= esActivo('comercializacion', $segment) ? 'nav-item mx-5 mx-sm-4 mx-lg-0' : 'hvr-underline-from-center nav-item mx-5 mx-sm-4 mx-lg-0' ?>">
                            <a class="nav-link text-center me-lg-2 px-2 px-xl-4 <?= esActivo('comercializacion', $segment) ? 'active' : '' ?>"
                                href="<?= base_url('comercializacion') ?>">
                                <i class="ms-md-0 me-1 fa-solid fa-truck-arrow-right <?= esActivo('comercializacion', $segment) ? 'animate__animated animate__heartBeat animate__infinite' : '' ?>"></i>
                                Comercialización
                            </a>
                        </li>
                    <?php endif; ?>

                    <!-- NOSOTROS -->
                    <?php if (session()->get('id_usuario') && session()->get('isLoggedIn') && session()->get('isAdmin')): ?>
                        <!-- Sesión iniciada como administrador -->
                         <li>
                            <a class="nav-link text-center me-lg-2 px-2 px-xl-4"
                                href="<?= base_url('admin/productos') ?>">
                                <i class="ms-md-0 me-1 fa-solid fa-magnifying-glass-chart"></i>
                                Productos
                            </a>
                        </li>
                    <?php else: ?>
                        <li class="<?= esActivo('nosotros', $segment) ? 'nav-item mx-5 mx-sm-4 mx-lg-0' : 'hvr-underline-from-center nav-item mx-5 mx-sm-4 mx-lg-0' ?>">
                            <a class="nav-link text-center me-lg-2 px-2 px-xl-4 <?= esActivo('nosotros', $segment) ? 'active' : '' ?>"
                                href="<?= base_url('nosotros') ?>">
                                <i class="ms-md-0 me-1 fa-solid fa-face-grin-wink <?= esActivo('nosotros', $segment) ? 'animate__animated animate__heartBeat animate__infinite' : '' ?>"></i>
                                Quienes somos
                            </a>
                        </li>
                    <?php endif; ?>

                    <!-- CONTACTO -->
                    <?php if (session()->get('id_usuario') && session()->get('isLoggedIn') && session()->get('isAdmin')): ?>
                        <!-- Sesión iniciada como administrador -->
                         <li>
                            <a class="nav-link text-center me-lg-2 px-2 px-xl-4"
                                href="<?= base_url('admin/adsfasdfasdf') ?>">
                                <i class="ms-md-0 me-1 fa-solid fa-question"></i>
                                Otro
                            </a>
                        </li>
                    <?php else: ?>
                        <li class="<?= esActivo('contacto', $segment) ? 'nav-item mx-5 mx-sm-4 mx-lg-0' : 'hvr-underline-from-center nav-item mx-5 mx-sm-4 mx-lg-0' ?>">
                            <a class="nav-link text-center me-lg-2 px-2 px-xl-4 <?= esActivo('contacto', $segment) ? 'active' : '' ?>"
                                href="<?= base_url('contacto') ?>">
                                <i class="ms-md-0 me-1 fa-solid fa-phone-volume <?= esActivo('contacto', $segment) ? 'animate__animated animate__heartBeat animate__infinite' : '' ?>"></i>
                                Contacto
                            </a>
                        </li>
                    <?php endif; ?>

                    <!-- LOGIN / LOGOUT -->
                    <li class="nav-item mx-5 mx-sm-4 mx-lg-0">
                        <?php if (session()->get('id_usuario') || session()->get('isLoggedIn')): ?>
                            <!-- Cerrar sesión -->
                            <a class="nav-link text-center me-lg-2 px-2 px-xl-4"
                                href="<?= base_url('auth/logout') ?>"
                                style="background-color: #dc3545 !important; color: white !important;">
                                <i class="ms-md-0 me-1 fa-solid fa-sign-out-alt"></i>
                                Cerrar sesión
                            </a>
                        <?php else: ?>
                            <!-- Iniciar sesión -->
                            <a class="nav-link text-center me-lg-2 px-2 px-xl-4 <?= esActivo('auth', $segment) ? 'active' : '' ?>"
                                href="<?= base_url('auth') ?>"
                                style="background-color:rgb(24, 167, 155) !important; color: white !important;">
                                <i class="ms-md-0 me-1 fa-solid fa-sign-in-alt <?= esActivo('auth', $segment) ? 'animate__animated animate__heartBeat animate__infinite' : '' ?>"></i>
                                Iniciar sesión
                            </a>
                        <?php endif; ?>
                    </li>

                </ul>
            </div>
        </div>
    </div>
</nav>
