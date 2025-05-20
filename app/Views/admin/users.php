<!DOCTYPE html>
<html>
<head>
    <!-- la cabecera de todas las paginas -->
    <?= view('layout/base_head') ?>
</head>
<body>

    <!-- Navbar -->
    <?= view('layout/navbar') ?>

    <main class="container my-4">
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
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h4><i class="bi bi-people me-2"></i> Gestión de Usuarios</h4>
                    </div>
                    <div class="card-body">
                        <!-- Mensajes flash -->
                        <?php if (session()->getFlashdata('success')) : ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <?= session()->getFlashdata('success') ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>
                        
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
                        
                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="usersTable">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Usuario</th>
                                        <th>Email</th>
                                        <th>Nombre</th>
                                        <th>Tipo</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($users as $user) : ?>
                                        <tr>
                                            <td><?= $user['id'] ?></td>
                                            <td><?= $user['username'] ?></td>
                                            <td><?= $user['email'] ?></td>
                                            <td><?= $user['nombre'] . ' ' . $user['apellido'] ?></td>
                                            <td>
                                                <?php if ($user['type'] == 1) : ?>
                                                    <span class="badge bg-danger">Administrador</span>
                                                <?php else : ?>
                                                    <span class="badge bg-info">Comprador</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if ($user['active'] == 1) : ?>
                                                    <span class="badge bg-success">Activo</span>
                                                <?php else : ?>
                                                    <span class="badge bg-secondary">Inactivo</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <!-- Botón de editar -->
                                                    <a href="<?= site_url('admin/editUser/' . $user['id']) ?>" class="btn btn-warning" title="Editar">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                    
                                                    <!-- Botón para cambiar estado (activar/desactivar) -->
                                                    <?php if ($user['id'] != session()->get('id')) : ?>
                                                        <a href="<?= site_url('admin/toggleActive/' . $user['id']) ?>" class="btn <?= $user['active'] == 1 ? 'btn-secondary' : 'btn-success' ?>" title="<?= $user['active'] == 1 ? 'Desactivar' : 'Activar' ?>">
                                                            <i class="bi <?= $user['active'] == 1 ? 'bi-x-circle' : 'bi-check-circle' ?>"></i>
                                                        </a>
                                                    <?php else : ?>
                                                        <button class="btn btn-secondary" disabled title="No puedes desactivar tu propia cuenta">
                                                            <i class="bi bi-x-circle"></i>
                                                        </button>
                                                    <?php endif; ?>
                                                    
                                                    <!-- Botón para cambiar tipo (admin/normal) -->
                                                    <a href="<?= site_url('admin/toggleType/' . $user['id']) ?>" class="btn <?= $user['type'] == 1 ? 'btn-info' : 'btn-danger' ?>" title="Cambiar a <?= $user['type'] == 1 ? 'Comprador' : 'Administrador' ?>">
                                                        <i class="bi <?= $user['type'] == 1 ? 'bi-person' : 'bi-person-fill-gear' ?>"></i>
                                                    </a>
                                                    
                                                    <!-- Botón de eliminar -->
                                                    <?php if ($user['id'] != session()->get('id')) : ?>
                                                        <button type="button" class="btn btn-danger" title="Eliminar" data-bs-toggle="modal" data-bs-target="#deleteModal<?= $user['id'] ?>">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    <?php else : ?>
                                                        <button class="btn btn-danger" disabled title="No puedes eliminar tu propia cuenta">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    <?php endif; ?>
                                                </div>
                                                
                                                <!-- Modal de confirmación para eliminar -->
                                                <div class="modal fade" id="deleteModal<?= $user['id'] ?>" tabindex="-1" aria-labelledby="deleteModalLabel<?= $user['id'] ?>" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="deleteModalLabel<?= $user['id'] ?>">Confirmar eliminación</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                ¿Estás seguro de que deseas eliminar al usuario <strong><?= $user['username'] ?></strong>?
                                                                <p class="text-danger mt-2">Esta acción no se puede deshacer.</p>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                                <a href="<?= site_url('admin/deleteUser/' . $user['id']) ?>" class="btn btn-danger">Eliminar</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
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
    
    <!-- Script para DataTables -->
    <script>
        $(document).ready(function() {
            $('#usersTable').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json"
                }
            });
        });
    </script>
</body>
</html>