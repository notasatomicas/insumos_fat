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
                    <a href="<?= site_url('admin/productos') ?>" class="list-group-item list-group-item-action active">
                        <i class="bi bi-box me-2"></i> Productos
                    </a>
                </div>
            </div>
            
            <!-- Contenido principal -->
            <div class="col-md-9">
                <div class="card">
                    <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                        <h4><i class="bi bi-eye me-2"></i> Detalles del Producto</h4>
                        <div>
                            <a href="<?= site_url('admin/productos/' . '/edit/' . $producto['id_producto']) ?>" class="btn btn-warning btn-sm me-2">
                                <i class="bi bi-pencil me-1"></i> Editar
                            </a>
                            <a href="<?= site_url('admin/productos') ?>" class="btn btn-light">
                                <i class="bi bi-arrow-left me-1"></i> Volver
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        
                        <?php if (session()->getFlashdata('success')): ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="bi bi-check-circle me-2"></i>
                                <?= session()->getFlashdata('success') ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <div class="row">
                            <!-- Información principal del producto -->
                            <div class="col-md-8">
                                <div class="mb-4">
                                    <h2 class="text-primary">
                                        <?= esc($producto['nombre_prod']) ?>
                                        <?php if ($producto['active']): ?>
                                            <span class="badge bg-success ms-2">Activo</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary ms-2">Inactivo</span>
                                        <?php endif; ?>
                                    </h2>
                                    <p class="text-muted mb-0">ID: #<?= $producto['id_producto'] ?></p>
                                </div>

                                <!-- Información básica -->
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <div class="card bg-light">
                                            <div class="card-body text-center">
                                                <h3 class="text-success mb-0">$<?= number_format($producto['precio'], 2) ?></h3>
                                                <p class="text-muted mb-0">Precio</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="card bg-light">
                                            <div class="card-body text-center">
                                                <h3 class="mb-0 <?= $producto['stock'] <= 5 ? 'text-danger' : ($producto['stock'] <= 20 ? 'text-warning' : 'text-success') ?>">
                                                    <?= $producto['stock'] ?>
                                                </h3>
                                                <p class="text-muted mb-0">
                                                    Stock disponible
                                                    <?php if ($producto['stock'] <= 5): ?>
                                                        <i class="bi bi-exclamation-triangle text-danger ms-1" title="Stock bajo"></i>
                                                    <?php endif; ?>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Categoría -->
                                <div class="mb-4">
                                    <h5><i class="bi bi-collection me-2"></i>Categoría</h5>
                                    <span class="badge bg-info fs-6"><?= esc($producto['categoria_nombre']) ?></span>
                                </div>

                                <!-- Descripción -->
                                <div class="mb-4">
                                    <h5><i class="bi bi-text-paragraph me-2"></i>Descripción</h5>
                                    <?php if (!empty($producto['descripcion'])): ?>
                                        <div class="card">
                                            <div class="card-body">
                                                <?= nl2br(esc($producto['descripcion'])) ?>
                                            </div>
                                        </div>
                                    <?php else: ?>
                                        <p class="text-muted fst-italic">Sin descripción disponible</p>
                                    <?php endif; ?>
                                </div>

                                <!-- Fechas -->
                                <div class="mb-4">
                                    <h5><i class="bi bi-clock me-2"></i>Información de fechas</h5>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <strong>Creado:</strong><br>
                                            <span class="text-muted"><?= date('d/m/Y H:i:s', strtotime($producto['created_at'])) ?></span>
                                        </div>
                                        <div class="col-md-6">
                                            <strong>Última actualización:</strong><br>
                                            <span class="text-muted"><?= date('d/m/Y H:i:s', strtotime($producto['updated_at'])) ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Panel lateral con imagen y acciones -->
                            <div class="col-md-4">
                                <!-- Imagen del producto -->
                                <div class="card mb-3">
                                    <div class="card-header">
                                        <h6 class="mb-0"><i class="bi bi-image me-1"></i> Imagen</h6>
                                    </div>
                                    <div class="card-body text-center">
                                        <?php if (!empty($producto['imagen_url'])): ?>
                                            <img src="<?= base_url('public') . '/' . $producto['imagen_url']; ?>" 
                                                 alt="<?= esc($producto['nombre_prod']) ?>" 
                                                 class="img-fluid rounded"
                                                 style="max-height: 300px;">
                                        <?php else: ?>
                                            <div class="bg-light d-flex align-items-center justify-content-center rounded" 
                                                 style="height: 200px;">
                                                <div class="text-center">
                                                    <i class="bi bi-image display-1 text-muted"></i>
                                                    <p class="text-muted mt-2">Sin imagen</p>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <!-- Acciones rápidas -->
                                <div class="card">
                                    <div class="card-header">
                                        <h6 class="mb-0"><i class="bi bi-gear me-1"></i> Acciones</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="d-grid gap-2">
                                            <a href="<?= site_url('admin/productos/'  . '/edit/' . $producto['id_producto']) ?>" 
                                               class="btn btn-warning">
                                                <i class="bi bi-pencil me-1"></i> Editar Producto
                                            </a>
                                            
                                            <?php if ($producto['active']): ?>
                                                <button type="button" class="btn btn-secondary" onclick="toggleStatus(<?= $producto['id_producto'] ?>, 0)">
                                                    <i class="bi bi-eye-slash me-1"></i> Desactivar
                                                </button>
                                            <?php else: ?>
                                                <button type="button" class="btn btn-success" onclick="toggleStatus(<?= $producto['id_producto'] ?>, 1)">
                                                    <i class="bi bi-eye me-1"></i> Activar
                                                </button>
                                            <?php endif; ?>
                                            
                                            <hr>
                                            
                                            <button type="button" 
                                                    class="btn btn-danger"
                                                    onclick="confirmarEliminacion(<?= $producto['id_producto'] ?>, '<?= esc($producto['nombre_prod']) ?>')">
                                                <i class="bi bi-trash me-1"></i> Eliminar Producto
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Estadísticas del producto -->
                                <div class="card mt-3">
                                    <div class="card-header">
                                        <h6 class="mb-0"><i class="bi bi-graph-up me-1"></i> Estadísticas</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row text-center">
                                            <div class="col-6">
                                                <div class="border-end">
                                                    <h4 class="text-primary mb-0">0</h4>
                                                    <small class="text-muted">Vendidos</small>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <h4 class="text-info mb-0">0</h4>
                                                <small class="text-muted">En carritos</small>
                                            </div>
                                        </div>
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

    <!-- Modal de confirmación para eliminar -->
    <div class="modal fade" id="modalEliminar" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirmar eliminación</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>¿Estás seguro de que deseas eliminar el producto <strong id="nombreProducto"></strong>?</p>
                    <p class="text-muted">Esta acción no se puede deshacer.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <a href="#" id="btnConfirmarEliminar" class="btn btn-danger">Eliminar</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts generales que se usan en todas las paginas -->
    <?= view('layout/base_scripts') ?>
    
    <script>
        function confirmarEliminacion(id, nombre) {
            document.getElementById('nombreProducto').textContent = nombre;
            document.getElementById('btnConfirmarEliminar').href = '<?= site_url('admin/productos/') ?>' + id + '/delete';
            new bootstrap.Modal(document.getElementById('modalEliminar')).show();
        }

        function toggleStatus(id, status) {
            const action = status ? 'activar' : 'desactivar';
            if (confirm(`¿Estás seguro de que deseas ${action} este producto?`)) {
                // Aquí puedes implementar la llamada AJAX o redirigir a una ruta específica
                window.location.href = `<?= site_url('admin/productos/') ?>${id}/toggle/${status}`;
            }
        }
    </script>
</body>
</html>