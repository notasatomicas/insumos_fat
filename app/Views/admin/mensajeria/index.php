<!DOCTYPE html>
<html>

<head>
    <!-- la cabecera de todas las paginas -->
    <?= view('layout/base_head') ?>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
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
                    <a href="<?= site_url('admin/productos') ?>" class="list-group-item list-group-item-action">
                        <i class="fa-solid fa-box me-2"></i> Productos
                    </a>
                    <a href="<?= site_url('admin/categorias') ?>" class="list-group-item list-group-item-action">
                        <i class="fa-solid fa-table-list me-2"></i> Categorías
                    </a>
                    <a href="<?= site_url('admin/contactos') ?>" class="list-group-item list-group-item-action active">
                        <i class="fa-solid fa-envelope me-2"></i> Mensajes de Contacto
                    </a>
                </div>
            </div>

            <!-- Contenido principal -->
            <div class="col-md-9">
                <!-- Header con título -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h2><i class="bi bi-envelope-fill me-2 text-primary"></i>Gestión de Mensajes de Contacto</h2>
                        <p class="text-muted mb-0">Administra todos los mensajes y solicitudes recibidas</p>
                    </div>
                    <div>
                        <button class="btn btn-outline-success" onclick="location.reload()">
                            <i class="fa-solid fa-refresh me-1"></i> Actualizar
                        </button>
                    </div>
                </div>

                <!-- Mensajes flash -->
                <?php if (session()->getFlashdata('success')) : ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fa-solid fa-check-circle me-2"></i>
                    <?= session()->getFlashdata('success') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('error')) : ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fa-solid fa-exclamation-triangle me-2"></i>
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
                                        <th>Tipo</th>
                                        <th>Estado</th>
                                        <th>Contenido</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (isset($contactos) && !empty($contactos)) : ?>
                                    <?php foreach ($contactos as $contacto) : ?>
                                    <tr>
                                        <td>
                                            <strong>#<?= $contacto['id'] ?></strong>
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
                                            <?php if ($contacto['tipo_contacto'] == 'mensaje') : ?>
                                            <span class="badge bg-primary">
                                                <i class="bi bi-chat-text"></i> Mensaje
                                            </span>
                                            <?php else : ?>
                                            <span class="badge bg-success">
                                                <i class="bi bi-telephone"></i> Llamada
                                            </span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <select class="form-select form-select-sm estado-select" 
                                                    data-contacto-id="<?= $contacto['id'] ?>" 
                                                    style="width: 120px;">
                                                <option value="nuevo" <?= $contacto['estado'] == 'nuevo' ? 'selected' : '' ?>>
                                                    Nuevo
                                                </option>
                                                <option value="leido" <?= $contacto['estado'] == 'leido' ? 'selected' : '' ?>>
                                                    Leído
                                                </option>
                                                <option value="respondido" <?= $contacto['estado'] == 'respondido' ? 'selected' : '' ?>>
                                                    Respondido
                                                </option>
                                                <option value="cerrado" <?= $contacto['estado'] == 'cerrado' ? 'selected' : '' ?>>
                                                    Cerrado
                                                </option>
                                            </select>
                                        </td>
                                        <td>
                                            <?php if ($contacto['tipo_contacto'] == 'mensaje') : ?>
                                                <span class="text-truncate d-inline-block" style="max-width: 200px;" 
                                                      title="<?= htmlspecialchars($contacto['mensaje']) ?>">
                                                    <?= substr($contacto['mensaje'], 0, 50) ?>...
                                                </span>
                                            <?php else : ?>
                                                <a href="tel:<?= $contacto['telefono'] ?>" class="text-decoration-none">
                                                    <i class="bi bi-telephone"></i> <?= $contacto['telefono'] ?>
                                                </a>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <!-- Botón de ver detalles -->
                                                <button type="button" class="btn btn-sm btn-outline-primary" 
                                                        title="Ver detalles" data-bs-toggle="modal" 
                                                        data-bs-target="#detalleModal<?= $contacto['id'] ?>">
                                                    <i class="fa-solid fa-eye"></i>
                                                </button>

                                                <!-- Botón de responder -->
                                                <a href="mailto:<?= $contacto['correo'] ?>?subject=Re: Consulta desde INSUMOS FAT&body=Estimado/a <?= $contacto['nombre'] ?>,%0D%0A%0D%0AGracias por contactarnos." 
                                                   class="btn btn-sm btn-outline-success" title="Responder por email">
                                                    <i class="fa-solid fa-reply"></i>
                                                </a>

                                                <!-- Botón de eliminar -->
                                                <button type="button" class="btn btn-sm btn-outline-danger" 
                                                        title="Eliminar" data-bs-toggle="modal" 
                                                        data-bs-target="#deleteModal<?= $contacto['id'] ?>">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            </div>

                                            <!-- Modal de detalles -->
                                            <div class="modal fade" id="detalleModal<?= $contacto['id'] ?>" tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog modal-lg">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">
                                                                <i class="bi bi-envelope-open me-2"></i>
                                                                Detalles del Contacto #<?= $contacto['id'] ?>
                                                            </h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <h6 class="fw-bold text-primary">Información Personal</h6>
                                                                    <table class="table table-sm table-borderless">
                                                                        <tbody>
                                                                            <tr>
                                                                                <td class="fw-bold">Nombre:</td>
                                                                                <td><?= $contacto['nombre'] . ' ' . $contacto['apellido'] ?></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td class="fw-bold">Email:</td>
                                                                                <td>
                                                                                    <a href="mailto:<?= $contacto['correo'] ?>" class="text-decoration-none">
                                                                                        <?= $contacto['correo'] ?>
                                                                                    </a>
                                                                                </td>
                                                                            </tr>
                                                                            <?php if (!empty($contacto['telefono'])): ?>
                                                                            <tr>
                                                                                <td class="fw-bold">Teléfono:</td>
                                                                                <td>
                                                                                    <a href="tel:<?= $contacto['telefono'] ?>" class="text-decoration-none">
                                                                                        <?= $contacto['telefono'] ?>
                                                                                    </a>
                                                                                </td>
                                                                            </tr>
                                                                            <?php endif; ?>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <h6 class="fw-bold text-primary">Información del Contacto</h6>
                                                                    <table class="table table-sm table-borderless">
                                                                        <tbody>
                                                                            <tr>
                                                                                <td class="fw-bold">Tipo:</td>
                                                                                <td>
                                                                                    <?php if ($contacto['tipo_contacto'] == 'mensaje') : ?>
                                                                                        <span class="badge bg-primary">Mensaje</span>
                                                                                    <?php else : ?>
                                                                                        <span class="badge bg-success">Solicitud de Llamada</span>
                                                                                    <?php endif; ?>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td class="fw-bold">Estado:</td>
                                                                                <td>
                                                                                    <?php
                                                                                    $badgeClass = [
                                                                                        'nuevo' => 'bg-info',
                                                                                        'leido' => 'bg-warning',
                                                                                        'respondido' => 'bg-success',
                                                                                        'cerrado' => 'bg-secondary'
                                                                                    ];
                                                                                    ?>
                                                                                    <span class="badge <?= $badgeClass[$contacto['estado']] ?>">
                                                                                        <?= ucfirst($contacto['estado']) ?>
                                                                                    </span>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td class="fw-bold">Fecha:</td>
                                                                                <td><?= date('d/m/Y H:i:s', strtotime($contacto['created_at'])) ?></td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                            
                                                            <?php if (!empty($contacto['mensaje'])): ?>
                                                            <div class="row mt-3">
                                                                <div class="col-12">
                                                                    <h6 class="fw-bold text-primary">Mensaje</h6>
                                                                    <div class="bg-light p-3 rounded">
                                                                        <?= nl2br(htmlspecialchars($contacto['mensaje'])) ?>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <?php endif; ?>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                                            <a href="mailto:<?= $contacto['correo'] ?>?subject=Re: Consulta desde INSUMOS FAT&body=Estimado/a <?= $contacto['nombre'] ?>,%0D%0A%0D%0AGracias por contactarnos." 
                                                               class="btn btn-success">
                                                                <i class="fa-solid fa-reply me-1"></i> Responder
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Modal de confirmación de eliminación -->
                                            <div class="modal fade" id="deleteModal<?= $contacto['id'] ?>" tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">
                                                                <i class="bi bi-exclamation-triangle-fill text-danger me-2"></i>
                                                                Confirmar Eliminación
                                                            </h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p>¿Estás seguro de que deseas eliminar este contacto?</p>
                                                            <div class="bg-light p-3 rounded">
                                                                <strong>ID:</strong> #<?= $contacto['id'] ?><br>
                                                                <strong>Nombre:</strong> <?= $contacto['nombre'] . ' ' . $contacto['apellido'] ?><br>
                                                                <strong>Email:</strong> <?= $contacto['correo'] ?><br>
                                                                <strong>Fecha:</strong> <?= date('d/m/Y H:i', strtotime($contacto['created_at'])) ?>
                                                            </div>
                                                            <div class="alert alert-warning mt-3 mb-0">
                                                                <i class="bi bi-exclamation-triangle me-2"></i>
                                                                <strong>Advertencia:</strong> Esta acción no se puede deshacer.
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                            <a href="<?= site_url('admin/contactos/eliminar/' . $contacto['id']) ?>" 
                                                               class="btn btn-danger">
                                                                <i class="fa-solid fa-trash me-1"></i> Eliminar
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                    <?php else : ?>
                                    <tr>
                                        <td colspan="8" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="bi bi-inbox display-1"></i>
                                                <h5 class="mt-3">No hay contactos para mostrar</h5>
                                                <p>Los mensajes de contacto aparecerán aquí cuando los usuarios se comuniquen.</p>
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

    <!-- Scripts generales que se usan en todas las paginas -->
    <?= view('layout/base_scripts') ?>
    
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>

    <!-- Script para DataTables -->
    <script>
        $(document).ready(function() {
            // Inicializar DataTables
            $('#contactosTable').DataTable({
                responsive: true,
                pageLength: 25,
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
                },
                order: [[0, 'desc']], // Ordenar por ID descendente
                columnDefs: [
                    { orderable: false, targets: [7] }, // Columna de acciones no ordenable
                    { responsivePriority: 1, targets: [0, 2, 7] }, // Prioridad para responsive
                    { responsivePriority: 2, targets: [1, 4, 5] }
                ]
            });

            // Manejar cambio de estado via AJAX
            $('.estado-select').on('change', function() {
                const contactoId = $(this).data('contacto-id');
                const nuevoEstado = $(this).val();
                const selectElement = $(this);
                const originalValue = selectElement.data('original-value') || selectElement.find('option:selected').val();

                // Mostrar loading
                selectElement.prop('disabled', true);

                $.ajax({
                    url: '<?= site_url("admin/contactos/cambiar-estado/") ?>' + contactoId,
                    type: 'POST',
                    data: {
                        estado: nuevoEstado,
                        '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
                    },
                    success: function(response) {
                        if (response.success) {
                            selectElement.data('original-value', nuevoEstado);
                            showAlert('success', response.message);
                        } else {
                            selectElement.val(originalValue);
                            showAlert('error', response.message);
                        }
                    },
                    error: function(xhr) {
                        selectElement.val(originalValue);
                        let errorMessage = 'Error al actualizar el estado';
                        
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        
                        showAlert('error', errorMessage);
                    },
                    complete: function() {
                        selectElement.prop('disabled', false);
                    }
                });
            });

            // Guardar valor original al cargar
            $('.estado-select').each(function() {
                $(this).data('original-value', $(this).val());
            });

            // Función para mostrar alertas
            function showAlert(type, message) {
                const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
                const iconClass = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-triangle';
                
                const alertHtml = `
                    <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                        <i class="fa-solid ${iconClass} me-2"></i>
                        ${message}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                `;
                
                // Insertar antes del primer card
                $('.card').first().before(alertHtml);
                
                // Auto-ocultar después de 5 segundos
                setTimeout(function() {
                    $('.alert').fadeOut();
                }, 5000);
            }
        });
    </script>

</body>
</html>