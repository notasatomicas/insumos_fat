<!DOCTYPE html>
<html>

<head>
    <!-- la cabecera de todas las paginas -->
    <?= view('layout/base_head') ?>

    <!-- Tanto el estilo de leaflet como el script deben ir juntos, no se por que pero cuando
         se lo saca de ese lugar comienza a dar fallos inesperados    
    -->
    <link rel="stylesheet" href="<?= base_url('public/assets/leaflet/leaflet.css') ?>">
    <script src="<?= base_url('public/assets/leaflet/leaflet.js') ?>"></script>

    <!-- Cargo estilos propios de contacto -->
    <link rel="stylesheet" href="<?= base_url('public/assets/css/contacto.css') ?>">

    <!-- SweetAlert2 para mejores mensajes -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert2/11.10.4/sweetalert2.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert2/11.10.4/sweetalert2.min.css">

    <?= view('adicional/modal_development_estilos.html') ?>
</head>

<body>

    <!-- Navbar -->
    <?= view('layout/navbar') ?>

    <!-- Contenido específico de la página -->
    <main class="container-fluid my-4">
        <div class="m-md-3">
            <section class="showcase py-5 rounded-4 shadow-lg" style="border: 1px solid black;">
                <div class="container imagenes">
                    <div class="row">
                        <div class="col-md-6 showcase-img">
                            <img src="<?= base_url('public/assets/img/feature-2.jpg') ?>" alt=""
                                class="img-fluid img-feat-1" />
                            <img src="<?= base_url('public/assets/img/feature-1.jpg') ?>" alt=""
                                class="img-fluid img-feat-2" />
                        </div>
                        <div class="col-md-6 showcase-text ps-5">
                            <h1 class="display-1 fw-light">
                                Siempre la <br />
                                <span class="fw-bold">Mejor</span> atención <br />
                                al <span class="fw-bold">Cliente</span>
                            </h1>
                            <p class="lead py-2">
                                Nos encanta estar a la vanguardia de las últimas tendencias y tecnologías para brindarte
                                una experiencia
                                de compra digital sin igual.
                                Consulta sin compromiso, respondemos en menos de 30 minutos!!!
                            </p>
                            <a href="#" class="btn btn-primary px-5 py-2" data-bs-toggle="modal"
                                data-bs-target="#modalMensaje" data-toggle="tooltip"
                                title="Haz clic para enviarnos un mensaje">Contactar<i
                                    class="fa-regular fa-message ms-2 animate__animated animate__bounce animate__infinite animate__tada"></i></a>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Modal para Enviar Mensaje -->
            <div id="modalMensaje" class="modal fade">
                <div class="modal-dialog modal-lg mt-3">
                    <div class="modal-content px-5 py-3">
                        <div class="row">
                            <div class="col-lg-12 contact-form">
                                <div class="card border-0">
                                    <div class="card-body">
                                        <div class="text-end">
                                            <button type="button" class="border-0" data-bs-dismiss="modal"
                                                aria-label="Close">
                                                <i class="fa-solid fa-circle-xmark" style="color: red !important;"></i>
                                            </button>
                                        </div>

                                        <div class="card-title text-center pb-3">
                                            <h3>¡Estamos en contacto!</h3>
                                            <p class="lead text-muted fw-light">Cuéntanos sobre ti y nos comunicaremos a
                                                la brevedad.</p>
                                        </div>

                                        <form id="formMensaje" class="needs-validation" novalidate>
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label for="mensajeNombre" class="form-label">Nombre *</label>
                                                    <input type="text" id="mensajeNombre" name="nombre" placeholder="Ingresa tu nombre"
                                                        class="form-control" required minlength="2" maxlength="100">
                                                    <div class="invalid-feedback">Por favor ingresa tu nombre (mínimo 2 caracteres).</div>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label for="mensajeApellido" class="form-label">Apellido *</label>
                                                    <input type="text" id="mensajeApellido" name="apellido" placeholder="Ingresa tu apellido"
                                                        class="form-control" required minlength="2" maxlength="100">
                                                    <div class="invalid-feedback">Por favor ingresa tu apellido (mínimo 2 caracteres).</div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label for="mensajeCorreo" class="form-label">Correo Electrónico *</label>
                                                    <input type="email" id="mensajeCorreo" name="correo" placeholder="tu@email.com"
                                                        class="form-control" required maxlength="150">
                                                    <div class="invalid-feedback">Por favor ingresa un correo válido.</div>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label for="mensajeTelefono" class="form-label">Teléfono</label>
                                                    <input type="tel" id="mensajeTelefono" name="telefono" placeholder="+54 9 3794-000000"
                                                        class="form-control" minlength="8" maxlength="20">
                                                    <div class="invalid-feedback">Por favor ingresa un teléfono válido (mínimo 8 dígitos).</div>
                                                    <div class="form-text">Opcional. Incluye código de área. Ej: +54 9 3794-123456</div>
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label for="mensajeTexto" class="form-label">Mensaje *</label>
                                                <textarea id="mensajeTexto" name="mensaje" placeholder="Escribe tu mensaje aquí..." 
                                                    class="form-control" rows="6" required minlength="10" maxlength="2000"></textarea>
                                                <div class="invalid-feedback">Por favor ingresa un mensaje (mínimo 10 caracteres).</div>
                                                <div class="form-text">
                                                    <span id="mensajeContador">0</span>/2000 caracteres
                                                </div>
                                            </div>
                                            <button type="submit" class="btn btn-primary w-100" id="btnEnviarMensaje">
                                                <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                                                <span class="btn-text">Enviar Mensaje</span>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="m-md-3">
            <div class="p-5 m-2 bg-body-secondary rounded-3 border animate animate__fadeInDown">
                <div class="container-fluid py-5 ps-md-5">
                    <h3 class="display-5 fw-bold mb-5 mb-md-0">
                        Comunícate con nosotros
                    </h3>

                    <p class="col-md-10 fs-4 my-4">
                        ¿Tienes alguna pregunta, sugerencia o comentario? Estamos aquí para ayudarte. Comunícate con
                        nuestro equipo de atención al cliente a través de nuestros medios. Tu opinión es importante para
                        nosotros. ¡Respondemos a la brevedad!
                    </p>
                </div>
            </div>
        </div>

        <div class="m-2 mt-md-3 mx-md-3 row p-4 pe-lg-0 py-lg-1 align-items-center rounded-3 shadow-lg"
            style="border: 1px solid black;">
            <div class="col-lg-6 p-3 p-lg-5 pt-lg-3">
                <h1 class="display-4 fw-bold lh-1 text-body-emphasis">Búscanos en nuestra tienda</h1>
                <p class="lead">O comunícate con nosotros directamente, te dejamos nuestros datos de contacto clickeando
                    en el
                    botón de aquí abajo</p>

                <div class="d-flex gap-3 flex-wrap">
                    <a href="#" class="btn btn-primary px-4 py-2" data-bs-toggle="modal"
                        data-bs-target="#modalDatos">Datos
                        de contacto<i class="fa-regular fa-address-card ms-2"></i></a>
                    
                    <a href="#" class="btn btn-outline-primary px-4 py-2 fa-beat-fade" data-bs-toggle="modal"
                        data-bs-target="#modalLlamada">Solicitar
                        llamada<i class="fa-solid fa-phone ms-2"></i></a>
                </div>
            </div>

            <div class="col-lg-4 offset-lg-1 p-1 shadow-lg overflow-hidden">
                <div class="rounded-3 mt-2" id="map" style="height: 500px !important;"></div>

                <script>
                const map = L.map('map').setView([-27.4666381113767, -58.83231690000313], 13);

                const tiles = L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 40,
                    attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
                }).addTo(map);

                const marker = L.marker([-27.4666381113767, -58.83231690000313]).addTo(map)
                    .bindPopup('<b>INSUMOS FAT S.A.</b><br>9 de Julio XXXX<br>Corrientes, Argentina')
                    .openPopup();
                </script>
            </div>

            <!-- Modal Datos de Contacto -->
            <div id="modalDatos" class="modal fade">
                <div class="modal-dialog modal-lg mt-3">
                    <div class="modal-content px-5 py-3">
                        <div class="row">
                            <div class="col-lg-12 contact-form">
                                <div class="card border-0">
                                    <div class="card-body">
                                        <div class="text-end">
                                            <button type="button" class="border-0" data-bs-dismiss="modal"
                                                aria-label="Close">
                                                <i class="fa-solid fa-circle-xmark" style="color: red !important;"></i>
                                            </button>
                                        </div>
                                        <div class="card-title text-center pb-3">
                                            <h3>DATOS DE CONTACTO</h3>
                                        </div>

                                        <div class="container">
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <div class="d-flex align-items-center">
                                                        <i class="fa-solid fa-building me-3 text-primary"></i>
                                                        <div>
                                                            <strong>Empresa:</strong><br>
                                                            INSUMOS FAT S.A.
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <div class="d-flex align-items-center">
                                                        <i class="fa-solid fa-id-card me-3 text-primary"></i>
                                                        <div>
                                                            <strong>CUIT:</strong><br>
                                                            XX-XX.XXX.XXX-XX
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <div class="d-flex align-items-center">
                                                        <i class="fa-solid fa-location-dot me-3 text-primary"></i>
                                                        <div>
                                                            <strong>Dirección:</strong><br>
                                                            9 de Julio XXXX<br>
                                                            Corrientes, Argentina
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <div class="d-flex align-items-center">
                                                        <i class="fa-solid fa-phone me-3 text-primary"></i>
                                                        <div>
                                                            <strong>Teléfono:</strong><br>
                                                            <a href="tel:+5493794000000" class="text-decoration-none">
                                                                +54 9 3794-0000
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-12 mb-3">
                                                    <div class="d-flex align-items-center">
                                                        <i class="fa-solid fa-envelope me-3 text-primary"></i>
                                                        <div>
                                                            <strong>Email:</strong><br>
                                                            <a href="mailto:hola@insumosfat.com" class="text-decoration-none">
                                                                hola@insumosfat.com
                                                            </a>
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
                </div>
            </div>

            <!-- Modal Solicitar Llamada -->
            <div id="modalLlamada" class="modal fade">
                <div class="modal-dialog modal-lg mt-3">
                    <div class="modal-content px-5 py-3">
                        <div class="row">
                            <div class="col-lg-12 contact-form">
                                <div class="card border-0">
                                    <div class="card-body">
                                        <div class="text-end">
                                            <button type="button" class="border-0" data-bs-dismiss="modal"
                                                aria-label="Close">
                                                <i class="fa-solid fa-circle-xmark" style="color: red !important;"></i>
                                            </button>
                                        </div>
                                        <div class="card-title text-center pb-3">
                                            <h3>SOLICITAR LLAMADA</h3>
                                            <p class="lead text-muted fw-light">Deja tus datos y nosotros te llamamos</p>
                                        </div>

                                        <form id="formLlamada" class="needs-validation" novalidate>
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label for="llamadaNombre" class="form-label">Nombre *</label>
                                                    <input type="text" id="llamadaNombre" name="nombre" placeholder="Ingresa tu nombre" 
                                                        class="form-control" required minlength="2" maxlength="100">
                                                    <div class="invalid-feedback">Por favor ingresa tu nombre (mínimo 2 caracteres).</div>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label for="llamadaApellido" class="form-label">Apellido *</label>
                                                    <input type="text" id="llamadaApellido" name="apellido" placeholder="Ingresa tu apellido" 
                                                        class="form-control" required minlength="2" maxlength="100">
                                                    <div class="invalid-feedback">Por favor ingresa tu apellido (mínimo 2 caracteres).</div>
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label for="llamadaCorreo" class="form-label">Correo Electrónico *</label>
                                                <input type="email" id="llamadaCorreo" name="correo" placeholder="tu@email.com" 
                                                    class="form-control" required maxlength="150">
                                                <div class="invalid-feedback">Por favor ingresa un correo válido.</div>
                                            </div>
                                            <div class="mb-3">
                                                <label for="llamadaTelefono" class="form-label">Teléfono *</label>
                                                <input type="tel" id="llamadaTelefono" name="telefono" placeholder="+54 9 3794-000000" 
                                                    class="form-control" required minlength="8" maxlength="20">
                                                <div class="invalid-feedback">Por favor ingresa un teléfono válido (mínimo 8 dígitos).</div>
                                                <div class="form-text">Incluye código de área. Ej: +54 9 3794-123456</div>
                                            </div>
                                            <button type="submit" class="btn btn-primary w-100" id="btnSolicitarLlamada">
                                                <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                                                <span class="btn-text">Solicitar Llamada</span>
                                            </button>
                                        </form>
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

    <script>
    // Validación de Bootstrap
    (() => {
        "use strict";
        const forms = document.querySelectorAll(".needs-validation");
        Array.from(forms).forEach((form) => {
            form.addEventListener(
                "submit",
                (event) => {
                    event.preventDefault();
                    event.stopPropagation();
                    
                    if (form.checkValidity()) {
                        // Si es válido, enviar formulario
                        if (form.id === 'formMensaje') {
                            enviarMensaje(form);
                        } else if (form.id === 'formLlamada') {
                            solicitarLlamada(form);
                        }
                    }
                    form.classList.add("was-validated");
                },
                false
            );
        });
    })();

    // Contador de caracteres para el mensaje
    document.getElementById('mensajeTexto').addEventListener('input', function() {
        const contador = document.getElementById('mensajeContador');
        contador.textContent = this.value.length;
        
        if (this.value.length > 1900) {
            contador.style.color = 'red';
        } else if (this.value.length > 1500) {
            contador.style.color = 'orange';
        } else {
            contador.style.color = '';
        }
    });

    // Formatear teléfono mientras se escribe (para ambos campos)
    function formatearTelefono(input) {
        input.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            
            // Si empieza con 54, formatear como número argentino
            if (value.startsWith('54')) {
                if (value.length > 2) {
                    value = '+54 ' + value.substring(2);
                }
                if (value.length > 7) {
                    value = value.substring(0, 7) + ' ' + value.substring(7);
                }
                if (value.length > 12) {
                    value = value.substring(0, 12) + '-' + value.substring(12);
                }
            }
            
            e.target.value = value;
        });
    }

    // Aplicar formateo a ambos campos de teléfono
    formatearTelefono(document.getElementById('mensajeTelefono'));
    formatearTelefono(document.getElementById('llamadaTelefono'));

    // Función para enviar mensaje
    async function enviarMensaje(form) {
        const btn = document.getElementById('btnEnviarMensaje');
        const spinner = btn.querySelector('.spinner-border');
        const btnText = btn.querySelector('.btn-text');
        
        // Mostrar loading
        btn.disabled = true;
        spinner.classList.remove('d-none');
        btnText.textContent = 'Enviando...';
        
        try {
            const formData = new FormData(form);
            
            const response = await fetch('<?= base_url('contacto/enviar-mensaje') ?>', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            
            const result = await response.json();
            
            if (result.success) {
                // Cerrar modal
                const modal = bootstrap.Modal.getInstance(document.getElementById('modalMensaje'));
                modal.hide();
                
                // Mostrar mensaje de éxito
                Swal.fire({
                    icon: 'success',
                    title: '¡Mensaje enviado!',
                    text: result.message,
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#0d6efd'
                });
                
                // Limpiar formulario
                form.reset();
                form.classList.remove('was-validated');
                document.getElementById('mensajeContador').textContent = '0';
                
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: result.message || 'Error al enviar el mensaje',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#dc3545'
                });
            }
            
        } catch (error) {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Error de conexión. Por favor, inténtalo nuevamente.',
                confirmButtonText: 'OK',
                confirmButtonColor: '#dc3545'
            });
        } finally {
            // Ocultar loading
            btn.disabled = false;
            spinner.classList.add('d-none');
            btnText.textContent = 'Enviar Mensaje';
        }
    }

    // Función para solicitar llamada
    async function solicitarLlamada(form) {
        const btn = document.getElementById('btnSolicitarLlamada');
        const spinner = btn.querySelector('.spinner-border');
        const btnText = btn.querySelector('.btn-text');
        
        // Mostrar loading
        btn.disabled = true;
        spinner.classList.remove('d-none');
        btnText.textContent = 'Enviando...';
        
        try {
            const formData = new FormData(form);
            
            const response = await fetch('<?= base_url('contacto/solicitar-llamada') ?>', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            
            const result = await response.json();
            
            if (result.success) {
                // Cerrar modal
                const modal = bootstrap.Modal.getInstance(document.getElementById('modalLlamada'));
                modal.hide();
                
                // Mostrar mensaje de éxito
                Swal.fire({
                    icon: 'success',
                    title: '¡Solicitud registrada!',
                    text: result.message,
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#0d6efd'
                });
                
                // Limpiar formulario
                form.reset();
                form.classList.remove('was-validated');
                
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: result.message || 'Error al registrar la solicitud',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#dc3545'
                });
            }
            
        } catch (error) {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Error de conexión. Por favor, inténtalo nuevamente.',
                confirmButtonText: 'OK',
                confirmButtonColor: '#dc3545'
            });
        } finally {
            // Ocultar loading
            btn.disabled = false;
            spinner.classList.add('d-none');
            btnText.textContent = 'Solicitar Llamada';
        }
    }
    </script>

    <!-- Scripts generales que se usan en todas las paginas -->
    <?= view('layout/base_scripts') ?>
</body>

</html>