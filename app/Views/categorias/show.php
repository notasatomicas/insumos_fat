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
                    <a href="<?= site_url('admin/dashboard') ?>" class="list-group-item list-group-item-action">
                        <i class="bi bi-speedometer2 me-2"></i> Dashboard
                    </a>
                    <a href="<?= site_url('admin/users') ?>" class="list-group-item list-group-item-action">
                        <i class="bi bi-people me-2"></i> Usuarios
                    </a>
                    <a href="<?= site_url('admin/productos') ?>" class="list-group-item list-group-item-action">
                        <i class="bi bi-box me-2"></i> Productos
                    </a>
                    <a href="<?= site_url('admin/categorias') ?>" class="list-group-item list-group-item-action active">
                        <i class="bi bi-tag me-2"></i> Categorías
                    </a>
                </div>
            </div>
            
            <!-- Contenido principal -->
            <div class="col-md-9">
                <div class="card">
                    <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                        <h4><i class="bi bi-eye me-2"></i> <?= $title ?></h4>
                        <div>
                            <a href="<?= site_url('admin/categorias/' . $categoria['id_categoria'] . '/edit') ?>" class="btn btn-warning me-2">
                                <i class="bi bi-pencil me-1"></i> Editar
                            </a>
                            <a href="<?= site_url('admin/categorias') ?>" class="btn btn-light">
                                <i class="bi bi-arrow-left me-1"></i> Volver al listado
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!-- Información de la categoría -->
                            <div class="col-md-6">
                                <div class="card h-100">
                                    <div class="card-header">
                                        <h5><i class="bi bi-info-circle me-2"></i> Información General</h5>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-borderless">
                                            <tr>
                                                <td><strong><i class="bi bi-hash me-1"></i> ID:</strong></td>
                                                <td><?= $categoria['id_categoria'] ?></td>
                                            </tr>
                                            <tr>
                                                <td><strong><i class="bi bi-tag me-1"></i> Nombre:</strong></td>
                                                <td><?= esc($categoria['nombre']) ?></td>
                                            </tr>
                                            <tr>
                                                <td><strong><i class="bi bi-toggle-on me-1"></i> Estado:</strong></td>
                                                <td>
                                                    <?php if ($categoria['estado'] == 1): ?>
                                                        <span class="badge bg-success">Activa</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-secondary">Inactiva</span>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><strong><i class="bi bi-calendar me-1"></i> Creada:</strong></td>
                                                <td><?= date('d/m/Y H:i:s', strtotime($categoria['created_at'])) ?></td>
                                            </tr>
                                            <?php if ($categoria['updated_at']): ?>
                                            <tr>
                                                <td><strong><i class="bi bi-pencil me-1"></i> Actualizada:</strong></td>
                                                <td><?= date('d/m/Y H:i:s', strtotime($categoria['updated_at'])) ?></td>
                                            </tr>
                                            <?php endif; ?>
                                        </table>

                                        <!-- Descripción de la categoría -->
                                        <div class="mt-3">
                                            <h6><i class="bi bi-file-text me-1"></i> Descripción:</h6>
                                            <?php if (!empty($categoria['descripcion'])): ?>
                                                <div class="bg-light p-3 rounded">
                                                    <?= nl2br(esc($categoria['descripcion'])) ?>
                                                </div>
                                            <?php else: ?>
                                                <div class="bg-light p-3 rounded text-muted">
                                                    <em>Sin descripción</em>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Estadísticas y acciones -->
                            <div class="col-md-6">
                                <div class="card h-100">
                                    <div class="card-header">
                                        <h5><i class="bi bi-bar-chart me-2"></i> Estadísticas</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="text-center mb-4">
                                            <div class="display-4 text-primary">
                                                <i class="bi bi-box"></i>
                                            </div>
                                            <h2 class="text-primary"><?= count($productos) ?></h2>
                                            <p class="text-muted">Productos en esta categoría</p>
                                        </div>

                                        <div class="d-grid gap-2">
                                            <a href="<?= site_url('admin/categorias/' . $categoria['id_categoria'] . '/edit') ?>" 
                                               class="btn btn-warning">
                                                <i class="bi bi-pencil me-1"></i> Editar Categoría
                                            </a>
                                            
                                            <?php if (count($productos) == 0): ?>
                                                <a href="<?= site_url('admin/categorias/' . $categoria['id_categoria'] . '/delete') ?>" 
                                                   class="btn btn-danger"
                                                   onclick="return confirm('¿Estás seguro de que deseas eliminar esta categoría?')">
                                                    <i class="bi bi-trash me-1"></i> Eliminar Categoría
                                                </a>
                                            <?php else: ?>
                                                <button class="btn btn-danger" disabled title="No se puede eliminar porque tiene productos asociados">
                                                    <i class="bi bi-trash me-1"></i> No se puede eliminar
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Lista de productos asociados -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h5><i class="bi bi-box me-2"></i> Productos en esta Categoría</h5>
                                    </div>
                                    <div class="card-body">
                                        <?php if (empty($productos)): ?>
                                            <div class="text-center py-4">
                                                <i class="bi bi-box display-1 text-muted"></i>
                                                <h4 class="text-muted mt-3">No hay productos en esta categoría</h4>
                                                <p class="text-muted">Los productos que se asignen a esta categoría aparecerán aquí</p>
                                                <a href="<?= site_url('admin/productos/create') ?>" class="btn btn-primary">
                                                    <i class="bi bi-plus-circle me-1"></i> Crear Producto
                                                </a>
                                            </div>
                                        <?php else: ?>
                                            <div class="table-responsive">
                                                <table class="table table-striped">
                                                    <thead class="table-dark">
                                                        <tr>
                                                            <th>ID</th>
                                                            <th>Nombre</th>
                                                            <th>Precio</th>
                                                            <th>Stock</th>
                                                            <th>Estado</th>
                                                            <th>Fecha Creación</th>
                                                            <th>Acciones</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php foreach ($productos as $producto): ?>
                                                            <tr>
                                                                <td><?= $producto['id_producto'] ?></td>
                                                                <td>
                                                                    <strong><?= esc($producto['nombre']) ?></strong>
                                                                </td>
                                                                <td>
                                                                    <?php if (isset($producto['precio'])): ?>
                                                                        $<?= number_format($producto['precio'], 2) ?>
                                                                    <?php else: ?>
                                                                        <span class="text-muted">No definido</span>
                                                                    <?php endif; ?>
                                                                </td>
                                                                <td>
                                                                    <?php if (isset($producto['stock'])): ?>
                                                                        <span class="badge bg-<?= $producto['stock'] > 0 ? 'success' : 'danger' ?>">
                                                                            <?= $producto['stock'] ?> unidades
                                                                        </span>
                                                                    <?php else: ?>
                                                                        <span class="text-muted">No definido</span>
                                                                    <?php endif; ?>
                                                                </td>
                                                                <td>
                                                                    <?php if (isset($producto['estado']) && $producto['estado'] == 1): ?>
                                                                        <span class="badge bg-success">Activo</span>
                                                                    <?php else: ?>
                                                                        <span class="badge bg-secondary">Inactivo</span>
                                                                    <?php endif; ?>
                                                                </td>
                                                                <td>
                                                                    <?= isset($producto['created_at']) ? date('d/m/Y', strtotime($producto['created_at'])) : 'No definida' ?>
                                                                </td>
                                                                <td>
                                                                    <div class="btn-group" role="group">
                                                                        <a href="<?= site_url('admin/productos/' . $producto['id_producto']) ?>" 
                                                                           class="btn btn-sm btn-outline-info" title="Ver producto">
                                                                            <i class="bi bi-eye"></i>
                                                                        </a>
                                                                        <a href="<?= site_url('admin/productos/' . $producto['id_producto'] . '/edit') ?>" 
                                                                           class="btn btn-sm btn-outline-warning" title="Editar producto">
                                                                            <i class="bi bi-pencil"></i>
                                                                        </a>
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