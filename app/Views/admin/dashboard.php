<!DOCTYPE html>
<html>
<head>
    <!-- la cabecera de todas las paginas -->
    <?= view('layout/base_head') ?>
</head>
<body class="d-flex flex-column min-vh-100">

    <!-- Navbar -->
    <?= view('layout/navbar') ?>

    <main class="container my-4 flex-fill">
        <div class="row">
            <!-- Sidebar de administración -->
            <div class="col-md-3">
                <div class="list-group">
                    <a href="<?= site_url('admin/dashboard') ?>" class="list-group-item list-group-item-action <?= $activo == 'dashboard' ? 'active' : '' ?>">
                        <i class="bi bi-speedometer2 me-2"></i> Dashboard
                    </a>
                    <a href="<?= site_url('admin/users') ?>" class="list-group-item list-group-item-action <?= $activo == 'users' ? 'active' : '' ?>">
                        <i class="bi bi-people me-2"></i> Usuarios
                    </a>
                    <!-- Puedes agregar más opciones aquí, como productos, categorías, pedidos, etc. -->
                </div>
            </div>
            
            <!-- Contenido principal -->
            <div class="col-md-9">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h4><i class="bi bi-speedometer2 me-2"></i> Panel de Administración</h4>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">Bienvenido, <?= session()->get('nombre') ?></h5>
                        <p class="card-text">Este es el panel de administración de Insumos_FAT.</p>
                        
                        <div class="row mt-4">
                            <!-- Tarjeta de estadísticas de usuarios -->
                            <div class="col-md-6 mb-4">
                                <div class="card bg-info text-white h-100">
                                    <div class="card-body text-center">
                                        <h5 class="card-title"><i class="bi bi-people display-4"></i></h5>
                                        <p class="card-text display-6">
                                            <?php 
                                            $userModel = new \App\Models\UserModel();
                                            echo count($userModel->findAll()); 
                                            ?>
                                        </p>
                                        <p>Usuarios registrados</p>
                                        <a href="<?= site_url('admin/users') ?>" class="btn btn-light mt-2">Gestionar usuarios</a>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Espacio para otras tarjetas de estadísticas -->
                            <div class="col-md-6 mb-4">
                                <div class="card bg-success text-white h-100">
                                    <div class="card-body text-center">
                                        <h5 class="card-title"><i class="bi bi-box display-4"></i></h5>
                                        <p class="card-text display-6">0</p>
                                        <p>Productos</p>
                                        <a href="#" class="btn btn-light mt-2">Gestionar productos</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Segunda fila de tarjetas -->
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <div class="card bg-warning text-dark h-100">
                                    <div class="card-body text-center">
                                        <h5 class="card-title"><i class="bi bi-cart display-4"></i></h5>
                                        <p class="card-text display-6">0</p>
                                        <p>Pedidos</p>
                                        <a href="#" class="btn btn-dark mt-2">Ver pedidos</a>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6 mb-4">
                                <div class="card bg-danger text-white h-100">
                                    <div class="card-body text-center">
                                        <h5 class="card-title"><i class="bi bi-tag display-4"></i></h5>
                                        <p class="card-text display-6">0</p>
                                        <p>Categorías</p>
                                        <a href="#" class="btn btn-light mt-2">Gestionar categorías</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <?= view('layout/footer') ?>

    <!-- Scripts generales que se usan en todas las paginas -->
    <?= view('layout/base_scripts') ?>
</body>
</html>