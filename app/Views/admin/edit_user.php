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
            <?= view('admin/sidebar') ?>
            
            <!-- Contenido principal -->
            <div class="col-md-9">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h4><i class="bi bi-pencil me-2"></i> Editar Usuario</h4>
                    </div>
                    <div class="card-body">
                        <!-- Mensajes flash -->
                        <?php if (session()->getFlashdata('error')) : ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <?php 
                                $error = session()->getFlashdata('error');
                                if (is_array($error)) {
                                    echo implode('<br>', $error);
                                } else {
                                    echo $error;
                                }
                                ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>
                        
                        <form action="<?= site_url('admin/updateUser/' . $user['id_usuario']) ?>" method="post">
                            <?= csrf_field() ?>
                            
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="username" class="form-label">Nombre de Usuario</label>
                                    <input type="text" class="form-control" id="username" name="username" value="<?= old('username', $user['username']) ?>" required>
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="email" class="form-label">Correo Electrónico</label>
                                    <input type="email" class="form-control" id="email" name="email" value="<?= old('email', $user['email']) ?>" required>
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="nombre" class="form-label">Nombre</label>
                                    <input type="text" class="form-control" id="nombre" name="nombre" value="<?= old('nombre', $user['nombre']) ?>" required>
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="apellido" class="form-label">Apellido</label>
                                    <input type="text" class="form-control" id="apellido" name="apellido" value="<?= old('apellido', $user['apellido']) ?>" required>
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="dni" class="form-label">DNI</label>
                                    <input type="text" class="form-control" id="dni" name="dni" value="<?= old('dni', $user['dni']) ?>" required>
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="direccion" class="form-label">Dirección</label>
                                    <input type="text" class="form-control" id="direccion" name="direccion" value="<?= old('direccion', $user['direccion']) ?>" required>
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="password" class="form-label">Nueva Contraseña (dejar en blanco para mantener la actual)</label>
                                    <input type="password" class="form-control" id="password" name="password">
                                    <div class="form-text">Mínimo 6 caracteres</div>
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="password_confirm" class="form-label">Confirmar Nueva Contraseña</label>
                                    <input type="password" class="form-control" id="password_confirm" name="password_confirm">
                                </div>
                            </div>
                            
                            <div class="row mt-4">
                                <div class="col-12">
                                    <a href="<?= site_url('admin/users') ?>" class="btn btn-secondary">
                                        <i class="bi bi-arrow-left me-1"></i> Volver
                                    </a>
                                    <button type="submit" class="btn btn-primary float-end">
                                        <i class="bi bi-save me-1"></i> Guardar Cambios
                                    </button>
                                </div>
                            </div>
                        </form>
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