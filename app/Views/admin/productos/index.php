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
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h4><i class="bi bi-box me-2"></i> Gestión de Productos</h4>
                        <a href="<?= site_url('admin/productos/create') ?>" class="btn btn-light">
                            <i class="bi bi-plus-circle me-1"></i> Nuevo Producto
                        </a>
                    </div>
                    <div class="card-body">
                        
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

                        <?php if (empty($productos)): ?>
                            <div class="text-center py-5">
                                <i class="bi bi-box display-1 text-muted"></i>
                                <h5 class="mt-3 text-muted">No hay productos registrados</h5>
                                <p class="text-muted">Comienza agregando tu primer producto</p>
                                <a href="<?= site_url('admin/productos/create') ?>" class="btn btn-primary">
                                    <i class="bi bi-plus-circle me-1"></i> Crear Producto
                                </a>
                            </div>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>ID</th>
                                            <th>Imagen</th>
                                            <th>Nombre</th>
                                            <th>Categoría</th>
                                            <th>Precio</th>
                                            <th>Stock</th>
                                            <th>Estado</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($productos as $producto): ?>
                                            <tr>
                                                <td><?= $producto['id_producto'] ?></td>
                                                <td>
                                                    <?php if (!empty($producto['imagen_url'])): ?>
                                                        <img src="<?= base_url('public') . '/' . $producto['imagen_url']; ?>" 
                                                             alt="<?= esc($producto['nombre_prod']) ?>" 
                                                             class="img-thumbnail" style="width: 50px; height: 50px; object-fit: cover;">
                                                    <?php else: ?>
                                                        <div class="bg-light d-flex align-items-center justify-content-center" 
                                                             style="width: 50px; height: 50px;">
                                                            <i class="bi bi-image text-muted"></i>
                                                        </div>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <strong><?= esc($producto['nombre_prod']) ?></strong>
                                                    <br>
                                                    <small class="text-muted">
                                                        <?= esc(mb_strlen($producto['descripcion']) > 50 ? mb_substr($producto['descripcion'], 0, 50) . '…' : $producto['descripcion']) ?>
                                                    </small>

                                                </td>
                                                <td>
                                                    <span class="badge bg-info">
                                                        <?= esc($producto['categoria_nombre']) ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <strong>$<?= number_format($producto['precio'], 2) ?></strong>
                                                </td>
                                                <td>
                                                    <?php if ($producto['stock'] <= 5): ?>
                                                        <span class="badge bg-danger"><?= $producto['stock'] ?></span>
                                                    <?php elseif ($producto['stock'] <= 20): ?>
                                                        <span class="badge bg-warning"><?= $producto['stock'] ?></span>
                                                    <?php else: ?>
                                                        <span class="badge bg-success"><?= $producto['stock'] ?></span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php if ($producto['active']): ?>
                                                        <span class="badge bg-success">Activo</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-secondary">Inactivo</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <a href="<?= site_url('admin/productos/' . $producto['id_producto']) ?>" 
                                                           class="btn btn-sm btn-outline-primary" 
                                                           title="Ver detalles">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <a href="<?= site_url('admin/productos/' . $producto['id_producto'] . '/edit') ?>" 
                                                           class="btn btn-sm btn-outline-secondary" 
                                                           title="Editar">
                                                            <i class="fa-solid fa-pen"></i>
                                                        </a>
                                                        <button type="button" 
                                                                class="btn btn-sm btn-outline-danger" 
                                                                title="Eliminar"
                                                                onclick="confirmarEliminacion(<?= $producto['id_producto'] ?>, '<?= esc($producto['nombre_prod']) ?>')">
                                                            <i class="fa-solid fa-trash"></i>
                                                        </button>
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
    </script>
</body>
</html>