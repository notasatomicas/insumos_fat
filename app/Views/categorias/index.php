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
                    <a href="<?= site_url('admin/dashboard') ?>" class="list-group-item list-group-item-action active">
                        <i class="fa-solid fa-gauge me-2"></i> Dashboard
                    </a>
                    <a href="<?= site_url('admin/users') ?>" class="list-group-item list-group-item-action">
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
                <!-- Mensajes de éxito/error -->
                <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle me-2"></i>
                        <?= session()->getFlashdata('success') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <?= session()->getFlashdata('error') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <div class="card">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h4><i class="bi bi-tag me-2"></i> <?= $title ?></h4>
                        <a href="<?= site_url('admin/categorias/create') ?>" class="btn btn-light">
                            <i class="bi bi-plus-circle me-1"></i> Nueva Categoría
                        </a>
                    </div>
                    <div class="card-body">
                        <?php if (empty($categorias)): ?>
                            <div class="text-center py-5">
                                <i class="bi bi-tag display-1 text-muted"></i>
                                <h3 class="text-muted mt-3">No hay categorías registradas</h3>
                                <p class="text-muted">Comienza creando tu primera categoría</p>
                                <a href="<?= site_url('admin/categorias/create') ?>" class="btn btn-primary">
                                    <i class="bi bi-plus-circle me-1"></i> Crear Categoría
                                </a>
                            </div>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>ID</th>
                                            <th>Nombre</th>
                                            <th>Descripción</th>
                                            <th>Productos</th>
                                            <th>Estado</th>
                                            <th>Fecha Creación</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($categorias as $categoria): ?>
                                            <tr>
                                                <td><?= $categoria['id_categoria'] ?></td>
                                                <td>
                                                    <strong><?= esc($categoria['nombre']) ?></strong>
                                                </td>
                                                <td>
                                                    <?= $categoria['descripcion'] ? esc(substr($categoria['descripcion'], 0, 50)) . (strlen($categoria['descripcion']) > 50 ? '...' : '') : '<em class="text-muted">Sin descripción</em>' ?>
                                                </td>
                                                <td>
                                                    <span class="badge bg-info"><?= $categoria['total_productos'] ?> productos</span>
                                                </td>
                                                <td>
                                                    <?php if ($categoria['estado'] == 1): ?>
                                                        <span class="badge bg-success">Activa</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-secondary">Inactiva</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?= date('d/m/Y', strtotime($categoria['created_at'])) ?>
                                                </td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <a href="<?= site_url('admin/categorias/' . $categoria['id_categoria']) ?>" 
                                                           class="btn btn-sm btn-outline-primary" title="Ver detalles">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <a href="<?= site_url('admin/categorias/' . $categoria['id_categoria'] . '/edit') ?>" 
                                                           class="btn btn-sm btn-outline-secondary" title="Editar">
                                                            <i class="fa-solid fa-pen"></i>
                                                        </a>
                                                        <?php if ($categoria['total_productos'] == 0): ?>
                                                            <a href="<?= site_url('admin/categorias/' . $categoria['id_categoria'] . '/delete') ?>" 
                                                               class="btn btn-sm btn-outline-danger" title="Eliminar"
                                                               onclick="return confirm('¿Estás seguro de que deseas eliminar esta categoría?')">
                                                                <i class="fa-solid fa-trash"></i>
                                                            </a>
                                                        <?php else: ?>
                                                            <button class="btn btn-sm btn-outline-danger" title="No se puede eliminar (tiene productos asociados)">
                                                                <i class="fa-solid fa-trash"></i>
                                                            </button>
                                                        <?php endif; ?>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
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