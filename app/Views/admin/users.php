<!DOCTYPE html>
<html>

<head>
    <!-- la cabecera de todas las paginas -->
    <?= view('layout/base_head') ?>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
</head>

<body class="d-flex flex-column min-vh-100">

    <!-- Navbar -->
    <?= view('layout/navbar') ?>

    <main class="container my-4 flex-fill">
        <div class="row">
            <!-- Sidebar de administración -->
            <div class="col-md-3">
                <div class="list-group">
                    <a href="<?= site_url('admin/dashboard') ?>" class="list-group-item list-group-item-action">
                        <i class="fa-solid fa-gauge me-2"></i> Dashboard
                    </a>
                    <a href="<?= site_url('admin/users') ?>" class="list-group-item list-group-item-action active">
                        <i class="fa-solid fa-user me-2"></i> Usuarios
                    </a>
                    <a href="<?= site_url('admin/productos') ?>" class="list-group-item list-group-item-action">
                        <i class="fa-solid fa-box me-2"></i> Productos
                    </a>
                    <a href="<?= site_url('admin/categorias') ?>" class="list-group-item list-group-item-action">
                        <i class="fa-solid fa-table-list me-2"></i> Gestionar categorías
                    </a>
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
                                        <td><?= $user['id_usuario'] ?></td>
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
                                            <div class="btn-group" role="group">
                                                <!-- Botón de editar -->
                                                <a href="<?= site_url('admin/editUser/' . $user['id_usuario']) ?>"
                                                    class="btn btn-sm btn-outline-primary" title="Editar">
                                                    <i class="fa-solid fa-fw fa-pen"></i>
                                                </a>

                                                <!-- Botón para cambiar estado (activar/desactivar) -->
                                                <?php if ($user['id_usuario'] != session()->get('id_usuario')) : ?>
                                                <a href="<?= site_url('admin/toggleActive/' . $user['id_usuario']) ?>"
                                                    class="btn btn-sm <?= $user['active'] == 1 ? 'btn-outline-secondary' : 'btn-outline-success' ?>"
                                                    title="<?= $user['active'] == 1 ? 'Desactivar' : 'Activar' ?>">
                                                    <i
                                                        class="fa-solid fa-fw <?= $user['active'] == 1 ? 'fa-circle-xmark' : 'fa-circle-check' ?>"></i>
                                                </a>
                                                <?php else : ?>
                                                <button class="btn btn-sm btn-outline-secondary" disabled
                                                    title="No puedes desactivar tu propia cuenta">
                                                    <i class="fa-solid fa-fw fa-circle-xmark"></i>
                                                </button>
                                                <?php endif; ?>

                                                <!-- Botón para cambiar tipo (admin/normal) -->
                                                <a href="<?= site_url('admin/toggleType/' . $user['id_usuario']) ?>"
                                                    class="btn btn-sm <?= $user['type'] == 1 ? 'btn-outline-info' : 'btn-outline-success' ?>"
                                                    title="Cambiar a <?= $user['type'] == 1 ? 'Comprador' : 'Administrador' ?>">
                                                    <i
                                                        class="fa-solid fa-fw <?= $user['type'] == 1 ? 'fa-user-tie' : 'fa-user-gear' ?>"></i>
                                                </a>

                                                <!-- Botón de eliminar -->
                                                <?php if ($user['id_usuario'] != session()->get('id_usuario')) : ?>
                                                <button type="button" class="btn btn-sm btn-outline-danger"
                                                    title="Eliminar" data-bs-toggle="modal"
                                                    data-bs-target="#deleteModal<?= $user['id_usuario'] ?>">
                                                    <i class="fa-solid fa-fw fa-trash"></i>
                                                </button>
                                                <?php else : ?>
                                                <button class="btn btn-sm btn-outline-danger"
                                                    title="Inmortal">
                                                    <i class="bi bi-clipboard2-x"></i>
                                                </button>
                                                <?php endif; ?>
                                            </div>

                                            <!-- Modal de confirmación para eliminar -->
                                            <div class="modal fade" id="deleteModal<?= $user['id_usuario'] ?>"
                                                tabindex="-1"
                                                aria-labelledby="deleteModalLabel<?= $user['id_usuario'] ?>"
                                                aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title"
                                                                id="deleteModalLabel<?= $user['id_usuario'] ?>">
                                                                Confirmar eliminación</h5>
                                                            <button type="button" class="btn-close"
                                                                data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            ¿Estás seguro de que deseas eliminar al usuario
                                                            <strong><?= $user['username'] ?></strong>?
                                                            <p class="text-danger mt-2">Esta acción no se puede
                                                                deshacer.</p>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-bs-dismiss="modal">Cancelar</button>
                                                            <a href="<?= site_url('admin/deleteUser/' . $user['id_usuario']) ?>"
                                                                class="btn btn-danger">Eliminar</a>
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