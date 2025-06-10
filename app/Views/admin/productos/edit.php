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
                        <i class="fa-solid fa-gauge me-2"></i> Dashboard
                    </a>
                    <a href="<?= site_url('admin/users') ?>" class="list-group-item list-group-item-action">
                        <i class="fa-solid fa-user me-2"></i> Usuarios
                    </a>
                    <a href="<?= site_url('admin/productos') ?>" class="list-group-item list-group-item-action active">
                        <i class="fa-solid fa-box me-2"></i> Productos
                    </a>
                    <a href="<?= site_url('admin/categorias') ?>" class="list-group-item list-group-item-action">
                        <i class="fa-solid fa-table-list me-2"></i> Categorías
                    </a>
                    <a href="<?= site_url('admin/contactos') ?>" class="list-group-item list-group-item-action">
                        <i class="fa-solid fa-envelope me-2"></i> Mensajes de Contacto
                    </a>
                </div>
            </div>
            
            <!-- Contenido principal -->
            <div class="col-md-9">
                <div class="card">
                    <div class="card-header bg-warning text-dark d-flex justify-content-between align-items-center">
                        <h4><i class="bi bi-pencil me-2"></i> Editar Producto</h4>
                        <div>
                            <a href="<?= site_url('admin/productos/' . $producto['id_producto']) ?>" class="btn btn-info btn-sm me-2">
                                <i class="bi bi-eye me-1"></i> Ver Detalles
                            </a>
                            <a href="<?= site_url('admin/productos') ?>" class="btn btn-light">
                                <i class="bi bi-arrow-left me-1"></i> Volver
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        
                        <?php if (session()->getFlashdata('errors')): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="bi bi-exclamation-triangle me-2"></i>
                                <strong>Se encontraron errores:</strong>
                                <ul class="mb-0 mt-2">
                                    <?php foreach (session()->getFlashdata('errors') as $error): ?>
                                        <li><?= esc($error) ?></li>
                                    <?php endforeach; ?>
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <form action="<?= site_url('admin/productos/' . $producto['id_producto']) ?>" method="post" enctype="multipart/form-data">
                            <?= csrf_field() ?>
                            
                            <div class="row">
                                <!-- Información básica del producto -->
                                <div class="col-md-8">
                                    <div class="mb-3">
                                        <label for="nombre_prod" class="form-label">
                                            <i class="bi bi-tag me-1"></i> Nombre del Producto *
                                        </label>
                                        <input type="text" 
                                               class="form-control <?= session()->getFlashdata('errors.nombre_prod') ? 'is-invalid' : '' ?>" 
                                               id="nombre_prod" 
                                               name="nombre_prod" 
                                               value="<?= old('nombre_prod', $producto['nombre_prod']) ?>"
                                               placeholder="Ingrese el nombre del producto"
                                               required>
                                        <?php if (session()->getFlashdata('errors.nombre_prod')): ?>
                                            <div class="invalid-feedback">
                                                <?= session()->getFlashdata('errors.nombre_prod') ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>

                                    <div class="mb-3">
                                        <label for="descripcion" class="form-label">
                                            <i class="bi bi-text-paragraph me-1"></i> Descripción
                                        </label>
                                        <textarea class="form-control" 
                                                  id="descripcion" 
                                                  name="descripcion" 
                                                  rows="4"
                                                  placeholder="Describe las características del producto"><?= old('descripcion', $producto['descripcion']) ?></textarea>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="precio" class="form-label">
                                                    <i class="bi bi-currency-dollar me-1"></i> Precio *
                                                </label>
                                                <div class="input-group">
                                                    <span class="input-group-text">$</span>
                                                    <input type="number" 
                                                           class="form-control <?= session()->getFlashdata('errors.precio') ? 'is-invalid' : '' ?>" 
                                                           id="precio" 
                                                           name="precio" 
                                                           value="<?= old('precio', $producto['precio']) ?>"
                                                           step="0.01"
                                                           min="0"
                                                           placeholder="0.00"
                                                           required>
                                                    <?php if (session()->getFlashdata('errors.precio')): ?>
                                                        <div class="invalid-feedback">
                                                            <?= session()->getFlashdata('errors.precio') ?>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="stock" class="form-label">
                                                    <i class="bi bi-boxes me-1"></i> Stock *
                                                </label>
                                                <input type="number" 
                                                       class="form-control <?= session()->getFlashdata('errors.stock') ? 'is-invalid' : '' ?>" 
                                                       id="stock" 
                                                       name="stock" 
                                                       value="<?= old('stock', $producto['stock']) ?>"
                                                       min="0"
                                                       placeholder="0"
                                                       required>
                                                <?php if (session()->getFlashdata('errors.stock')): ?>
                                                    <div class="invalid-feedback">
                                                        <?= session()->getFlashdata('errors.stock') ?>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="categoria_id" class="form-label">
                                            <i class="bi bi-collection me-1"></i> Categoría *
                                        </label>
                                        <select class="form-select <?= session()->getFlashdata('errors.categoria_id') ? 'is-invalid' : '' ?>" 
                                                id="categoria_id" 
                                                name="categoria_id" 
                                                required>
                                            <option value="">Seleccione una categoría</option>
                                            <?php foreach ($categorias as $categoria): ?>
                                                <option value="<?= $categoria['id_categoria'] ?>" 
                                                        <?= old('categoria_id', $producto['categoria_id']) == $categoria['id_categoria'] ? 'selected' : '' ?>>
                                                    <?= esc($categoria['nombre']) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                        <?php if (session()->getFlashdata('errors.categoria_id')): ?>
                                            <div class="invalid-feedback">
                                                <?= session()->getFlashdata('errors.categoria_id') ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <!-- Panel lateral para imagen y configuraciones -->
                                <div class="col-md-4">
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <h6 class="card-title">
                                                <i class="bi bi-image me-1"></i> Imagen del Producto
                                            </h6>
                                            
                                            <!-- Imagen actual -->
                                            <?php if (!empty($producto['imagen_url'])): ?>
                                                <div class="mb-3 text-center">
                                                    <label class="form-label">Imagen actual:</label>
                                                    <br>
                                                    <img src="<?= base_url('public') . '/' . $producto['imagen_url']; ?>" 
                                                         alt="<?= esc($producto['nombre_prod']) ?>" 
                                                         class="img-fluid rounded" 
                                                         style="max-height: 150px;">
                                                </div>
                                            <?php endif; ?>
                                            
                                            <div class="mb-3">
                                                <label for="imagen" class="form-label">
                                                    <?= !empty($producto['imagen_url']) ? 'Cambiar imagen:' : 'Subir imagen:' ?>
                                                </label>
                                                <input type="file" 
                                                       class="form-control" 
                                                       id="imagen" 
                                                       name="imagen" 
                                                       accept=".jpg,.jpeg,.png"
                                                       onchange="previewImage(this)">
                                                <div class="form-text">
                                                    Formatos permitidos: JPG, PNG, GIF (Max: 2MB)
                                                    <br>
                                                    <small class="text-muted">Deja vacío para mantener la imagen actual</small>
                                                </div>
                                            </div>

                                            <!-- Preview de la nueva imagen -->
                                            <div id="imagePreview" class="text-center" style="display: none;">
                                                <label class="form-label">Nueva imagen:</label>
                                                <br>
                                                <img id="preview" src="" alt="Preview" class="img-fluid rounded" style="max-height: 150px;">
                                            </div>

                                            <hr>

                                            <h6><i class="bi bi-gear me-1"></i> Configuración</h6>
                                            
                                            <div class="form-check">
                                                <input class="form-check-input" 
                                                       type="checkbox" 
                                                       id="active" 
                                                       name="active" 
                                                       value="1" 
                                                       <?= old('active', $producto['active']) ? 'checked' : '' ?>>
                                                <label class="form-check-label" for="active">
                                                    Producto activo
                                                </label>
                                            </div>
                                            <div class="form-text">
                                                Los productos inactivos no se mostrarán en la tienda
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Información adicional -->
                                    <div class="card mt-3 bg-info text-white">
                                        <div class="card-body">
                                            <h6 class="card-title">
                                                <i class="bi bi-info-circle me-1"></i> Información
                                            </h6>
                                            <small>
                                                <strong>ID:</strong> #<?= $producto['id_producto'] ?><br>
                                                <strong>Creado:</strong> <?= date('d/m/Y', strtotime($producto['created_at'])) ?><br>
                                                <strong>Actualizado:</strong> <?= date('d/m/Y', strtotime($producto['updated_at'])) ?>
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <hr>

                            <div class="d-flex justify-content-between">
                                <a href="<?= site_url('admin/productos') ?>" class="btn btn-secondary">
                                    <i class="bi bi-x-circle me-1"></i> Cancelar
                                </a>
                                <button type="submit" class="btn btn-warning">
                                    <i class="bi bi-check-circle me-1"></i> Actualizar Producto
                                </button>
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
    
    <script>
        function previewImage(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    document.getElementById('preview').src = e.target.result;
                    document.getElementById('imagePreview').style.display = 'block';
                }
                
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>

    <script>
    function previewImage(input) {
        const file = input.files[0];

        if (!file) return;

        const allowedTypes = ['image/jpeg', 'image/png'];
        const maxSize = 2 * 1024 * 1024; // 2MB

        if (!allowedTypes.includes(file.type)) {
            alert("Solo se permiten imágenes en formato JPG o PNG.");
            input.value = ""; // limpiar el input
            return;
        }

        if (file.size > maxSize) {
            alert("El archivo excede el tamaño máximo de 2MB.");
            input.value = "";
            return;
        }

        // Si pasa las validaciones, puedes mostrar la vista previa aquí
        const reader = new FileReader();
        reader.onload = function(e) {
            // Por ejemplo, mostrar la imagen en un <img id="preview">
            document.getElementById('preview').src = e.target.result;
        };
        reader.readAsDataURL(file);
    }
    </script>
</body>
</html>