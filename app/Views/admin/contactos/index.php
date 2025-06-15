<!DOCTYPE html>
<html>

<head>
    <!-- la cabecera de todas las paginas -->
    <?= view('layout/base_head') ?>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
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
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2><i class="bi bi-envelope-fill me-2 text-primary"></i>Mensajes de Contacto</h2>
                    <button class="btn btn-outline-success" onclick="location.reload()">
                        <i class="fa-solid fa-refresh me-1"></i> Actualizar
                    </button>
                </div>

                <!-- Estadísticas -->
                <?php if (isset($stats) && !empty($stats)): ?>
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card bg-info text-white">
                            <div class="card-body text-center">
                                <h3 class="mb-1"><?= $stats['nuevo'] ?? 0 ?></h3>
                                <small>Nuevos</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-warning text-white">
                            <div class="card-body text-center">
                                <h3 class="mb-1"><?= $stats['leido'] ?? 0 ?></h3>
                                <small>Leídos</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-success text-white">
                            <div class="card-body text-center">
                                <h3 class="mb-1"><?= $stats['respondido'] ?? 0 ?></h3>
                                <small>Respondidos</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-secondary text-white">
                            <div class="card-body text-center">
                                <h3 class="mb-1"><?= $stats['cerrado'] ?? 0 ?></h3>
                                <small>Cerrados</small>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Filtro por estado -->
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <label for="estadoFiltro" class="form-label">Filtrar por Estado:</label>
                                <select id="estadoFiltro" class="form-select" onchange="filtrarPorEstado()">
                                    <option value="">Todos los estados</option>
                                    <option value="nuevo" <?= (isset($estado_filtro) && $estado_filtro == 'nuevo') ? 'selected' : '' ?>>Nuevo</option>
                                    <option value="leido" <?= (isset($estado_filtro) && $estado_filtro == 'leido') ? 'selected' : '' ?>>Leído</option>
                                    <option value="respondido" <?= (isset($estado_filtro) && $estado_filtro == 'respondido') ? 'selected' : '' ?>>Respondido</option>
                                    <option value="cerrado" <?= (isset($estado_filtro) && $estado_filtro == 'cerrado') ? 'selected' : '' ?>>Cerrado</option>
                                </select>
                            </div>
                            <div class="col-md-8 text-end">
                                <?php if (isset($stats)): ?>
                                <small class="text-muted">
                                    Total: <?= $stats['total'] ?? 0 ?> | 
                                    Hoy: <?= $stats['hoy'] ?? 0 ?> | 
                                    Semana: <?= $stats['semana'] ?? 0 ?>
                                </small>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Mensajes flash -->
                <?php if (session()->getFlashdata('success')) : ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fa-solid fa-check-circle me-2"></i>
                    <?= session()->getFlashdata('success') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('error')) : ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fa-solid fa-exclamation-triangle me-2"></i>
                    <?= is_array(session()->getFlashdata('error')) ? implode('<br>', session()->getFlashdata('error')) : session()->getFlashdata('error') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php endif; ?>

                <!-- Tabla de contactos -->
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="contactosTable">
                                <thead class="table-dark">
                                    <tr>
                                        <th>ID</th>
                                        <th>Fecha</th>
                                        <th>Nombre</th>
                                        <th>Email</th>
                                        <th>Teléfono</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (isset($contactos) && !empty($contactos)) : ?>
                                    <?php foreach ($contactos as $contacto) : ?>
                                    <tr>
                                        <td>
                                            <strong>#<?= $contacto['id'] ?></strong>
                                            <?php
                                            $estadoBadge = '';
                                            switch($contacto['estado']) {
                                                case 'nuevo':
                                                    $estadoBadge = '<span class="badge bg-info ms-1">Nuevo</span>';
                                                    break;
                                                case 'leido':
                                                    $estadoBadge = '<span class="badge bg-warning text-dark ms-1">Leído</span>';
                                                    break;
                                                case 'respondido':
                                                    $estadoBadge = '<span class="badge bg-success ms-1">Respondido</span>';
                                                    break;
                                                case 'cerrado':
                                                    $estadoBadge = '<span class="badge bg-secondary ms-1">Cerrado</span>';
                                                    break;
                                            }
                                            echo $estadoBadge;
                                            ?>
                                        </td>
                                        <td>
                                            <small>
                                                <?= date('d/m/Y', strtotime($contacto['created_at'])) ?><br>
                                                <span class="text-muted"><?= date('H:i', strtotime($contacto['created_at'])) ?></span>
                                            </small>
                                        </td>
                                        <td>
                                            <strong><?= $contacto['nombre'] . ' ' . $contacto['apellido'] ?></strong>
                                        </td>
                                        <td>
                                            <a href="mailto:<?= $contacto['correo'] ?>" class="text-decoration-none">
                                                <i class="fa-solid fa-envelope me-1"></i>
                                                <?= $contacto['correo'] ?>
                                            </a>
                                        </td>
                                        <td>
                                            <?php if (!empty($contacto['telefono'])): ?>
                                            <a href="tel:<?= $contacto['telefono'] ?>" class="text-decoration-none">
                                                <i class="bi bi-telephone me-1"></i>
                                                <?= $contacto['telefono'] ?>
                                            </a>
                                            <?php else: ?>
                                            <span class="text-muted">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <!-- Botón ver detalles - ahora apunta a modal específico -->
                                                <button type="button" class="btn btn-sm btn-outline-primary" 
                                                        title="Ver detalles" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#detalleModal<?= $contacto['id'] ?>">
                                                    <i class="fa-solid fa-eye"></i>
                                                </button>

                                                <a href="mailto:<?= $contacto['correo'] ?>?subject=Re: Consulta desde INSUMOS FAT&body=Estimado/a <?= $contacto['nombre'] ?>,%0D%0A%0D%0AGracias por contactarnos." 
                                                   class="btn btn-sm btn-outline-success" title="Responder por email">
                                                    <i class="fa-solid fa-reply"></i>
                                                </a>

                                                <button type="button" class="btn btn-sm btn-outline-danger" 
                                                        title="Eliminar" data-bs-toggle="modal" 
                                                        data-bs-target="#deleteModal<?= $contacto['id'] ?>">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            </div>

                                            <!-- Modal de detalles específico para cada contacto -->
                                            <div class="modal fade" id="detalleModal<?= $contacto['id'] ?>" tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog modal-lg">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">
                                                                <i class="bi bi-envelope-open me-2"></i>
                                                                Detalles del Contacto #<?= $contacto['id'] ?>
                                                            </h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="row mb-3">
                                                                <div class="col-md-6">
                                                                    <h6 class="fw-bold text-primary">Información Personal</h6>
                                                                    <div class="mb-2">
                                                                        <strong>ID:</strong> <span class="text-muted">#<?= $contacto['id'] ?></span>
                                                                    </div>
                                                                    <div class="mb-2">
                                                                        <strong>Nombre:</strong> <?= $contacto['nombre'] . ' ' . $contacto['apellido'] ?>
                                                                    </div>
                                                                    <div class="mb-2">
                                                                        <strong>Email:</strong> 
                                                                        <a href="mailto:<?= $contacto['correo'] ?>" class="text-decoration-none">
                                                                            <i class="fa-solid fa-envelope me-1"></i><?= $contacto['correo'] ?>
                                                                        </a>
                                                                    </div>
                                                                    <div class="mb-2">
                                                                        <strong>Teléfono:</strong> 
                                                                        <?php if (!empty($contacto['telefono'])): ?>
                                                                            <a href="tel:<?= $contacto['telefono'] ?>" class="text-decoration-none">
                                                                                <i class="bi bi-telephone me-1"></i><?= $contacto['telefono'] ?>
                                                                            </a>
                                                                        <?php else: ?>
                                                                            <span class="text-muted">No proporcionado</span>
                                                                        <?php endif; ?>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <h6 class="fw-bold text-primary">Estado y Fecha</h6>
                                                                    <div class="mb-2">
                                                                        <strong>Fecha:</strong> <?= date('d/m/Y H:i', strtotime($contacto['created_at'])) ?>
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <strong>Estado:</strong>
                                                                        <form action="<?= site_url('admin/contactos/cambiar-estado/' . $contacto['id']) ?>" method="POST" class="d-inline">
                                                                            <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
                                                                            <div class="d-flex gap-2 mt-2">
                                                                                <select name="estado" class="form-select">
                                                                                    <option value="nuevo" <?= $contacto['estado'] == 'nuevo' ? 'selected' : '' ?>>Nuevo</option>
                                                                                    <option value="leido" <?= $contacto['estado'] == 'leido' ? 'selected' : '' ?>>Leído</option>
                                                                                    <option value="respondido" <?= $contacto['estado'] == 'respondido' ? 'selected' : '' ?>>Respondido</option>
                                                                                    <option value="cerrado" <?= $contacto['estado'] == 'cerrado' ? 'selected' : '' ?>>Cerrado</option>
                                                                                </select>
                                                                                <button type="submit" class="btn btn-primary btn-sm">
                                                                                    <i class="fa-solid fa-save me-1"></i>Actualizar
                                                                                </button>
                                                                            </div>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="row">
                                                                <div class="col-12">
                                                                    <h6 class="fw-bold text-primary">Mensaje</h6>
                                                                    <div class="bg-light p-3 rounded border">
                                                                        <div style="white-space: pre-wrap; min-height: 60px;">
                                                                            <?= nl2br(htmlspecialchars($contacto['mensaje'])) ?>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                                <i class="fa-solid fa-times me-1"></i>Cerrar
                                                            </button>
                                                            <a href="mailto:<?= $contacto['correo'] ?>?subject=<?= urlencode('Re: Consulta desde INSUMOS FAT') ?>&body=<?= urlencode("Estimado/a {$contacto['nombre']},\n\nGracias por contactarnos.\n\n") ?>" 
                                                               class="btn btn-success">
                                                                <i class="fa-solid fa-reply me-1"></i> Responder
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Modal de eliminación -->
                                            <div class="modal fade" id="deleteModal<?= $contacto['id'] ?>" tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Confirmar Eliminación</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p>¿Estás seguro de que deseas eliminar este contacto?</p>
                                                            <div class="bg-light p-3 rounded">
                                                                <strong>ID:</strong> #<?= $contacto['id'] ?><br>
                                                                <strong>Nombre:</strong> <?= $contacto['nombre'] . ' ' . $contacto['apellido'] ?><br>
                                                                <strong>Email:</strong> <?= $contacto['correo'] ?>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                            <a href="<?= site_url('admin/contactos/eliminar/' . $contacto['id']) ?>" 
                                                               class="btn btn-danger">Eliminar</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                    <?php else : ?>
                                    <tr>
                                        <td colspan="6" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="bi bi-inbox display-1"></i>
                                                <h5 class="mt-3">No hay contactos para mostrar</h5>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Paginación -->
                        <?php if (isset($pager) && $pager->getPageCount() > 1): ?>
                        <div class="d-flex justify-content-center mt-4">
                            <?= $pager->links() ?>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <?= view('layout/footer') ?>

    <!-- Scripts generales -->
    <?= view('layout/base_scripts') ?>
    
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <script>
$(document).ready(function() {
    // Inicializar DataTable
    $('#contactosTable').DataTable({
        pageLength: 25,
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
        },
        order: [[0, 'desc']],
        columnDefs: [
            { orderable: false, targets: [5] }
        ]
    });
});

// Función para filtrar por estado
function filtrarPorEstado() {
    const estado = document.getElementById('estadoFiltro').value;
    const url = new URL(window.location);
    
    if (estado) {
        url.searchParams.set('estado', estado);
    } else {
        url.searchParams.delete('estado');
    }
    
    window.location = url;
}
    </script>

</body>
</html>