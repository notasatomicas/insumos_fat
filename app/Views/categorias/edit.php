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
                    <div class="card-header bg-warning text-dark d-flex justify-content-between align-items-center">
                        <h4><i class="bi bi-pencil me-2"></i> <?= $title ?></h4>
                        <div>
                            <a href="<?= site_url('admin/categorias/' . $categoria['id_categoria']) ?>" class="btn btn-info me-2">
                                <i class="bi bi-eye me-1"></i> Ver detalles
                            </a>
                            <a href="<?= site_url('admin/categorias') ?>" class="btn btn-secondary">
                                <i class="bi bi-arrow-left me-1"></i> Volver al listado
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Mostrar errores de validación -->
                        <?php if ($validation->hasError('nombre')): ?>
                            <div class="alert alert-danger">
                                <i class="bi bi-exclamation-triangle me-2"></i>
                                <?= $validation->getError('nombre') ?>
                            </div>
                        <?php endif; ?>

                        <form action="<?= site_url('admin/categorias/' . $categoria['id_categoria']) ?>" method="post">
                            <?= csrf_field() ?>
                            
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="mb-3">
                                        <label for="nombre" class="form-label">
                                            <i class="bi bi-tag me-1"></i> Nombre de la Categoría *
                                        </label>
                                        <input type="text" 
                                               class="form-control <?= $validation->hasError('nombre') ? 'is-invalid' : '' ?>" 
                                               id="nombre" 
                                               name="nombre" 
                                               value="<?= old('nombre', $categoria['nombre']) ?>" 
                                               placeholder="Ej: Electrónicos, Herramientas, etc."
                                               required>
                                        <div class="form-text">
                                            Mínimo 3 caracteres, máximo 100 caracteres
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="descripcion" class="form-label">
                                            <i class="bi bi-file-text me-1"></i> Descripción
                                        </label>
                                        <textarea class="form-control" 
                                                  id="descripcion" 
                                                  name="descripcion" 
                                                  rows="4" 
                                                  placeholder="Descripción opcional de la categoría..."><?= old('descripcion', $categoria['descripcion']) ?></textarea>
                                        <div class="form-text">
                                            Descripción opcional para ayudar a identificar la categoría
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="card bg-light">
                                        <div class="card-header">
                                            <h6 class="mb-0"><i class="bi bi-gear me-1"></i> Configuración</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="mb-3">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" 
                                                           type="checkbox" 
                                                           id="estado" 
                                                           name="estado" 
                                                           value="1" 
                                                           <?= old('estado', $categoria['estado']) ? 'checked' : '' ?>>
                                                    <label class="form-check-label" for="estado">
                                                        <i class="bi bi-toggle-on me-1"></i> Categoría activa
                                                    </label>
                                                </div>
                                                <div class="form-text">
                                                    Las categorías inactivas no aparecerán en el catálogo
                                                </div>
                                            </div>

                                            <div class="border-top pt-3">
                                                <h6><i class="bi bi-info-circle me-1"></i> Información</h6>
                                                <small class="text-muted">
                                                    <strong>ID:</strong> <?= $categoria['id_categoria'] ?><br>
                                                    <strong>Creada:</strong> <?= date('d/m/Y H:i', strtotime($categoria['created_at'])) ?><br>
                                                    <?php if ($categoria['updated_at']): ?>
                                                        <strong>Actualizada:</strong> <?= date('d/m/Y H:i', strtotime($categoria['updated_at'])) ?><br>
                                                    <?php endif; ?>
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="border-top pt-3 mt-4">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <a href="<?= site_url('admin/categorias') ?>" class="btn btn-secondary me-2">
                                            <i class="bi bi-x-circle me-1"></i> Cancelar
                                        </a>
                                        <a href="<?= site_url('admin/categorias/' . $categoria['id_categoria']) ?>" class="btn btn-info">
                                            <i class="bi bi-eye me-1"></i> Ver detalles
                                        </a>
                                    </div>
                                    <button type="submit" class="btn btn-warning">
                                        <i class="bi bi-check-circle me-1"></i> Guardar Cambios
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