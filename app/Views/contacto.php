<main class="container-fluid my-4">
  <div class="m-md-3">
      <section class="showcase py-5 rounded-4 shadow-lg" style="border: 1px solid black;">
        <div class="container imagenes">
          <div class="row">
            <div class="col-md-6 showcase-img">
              <img src="<?= base_url('public/assets/img/feature-2.jpg') ?>" alt="" class="img-fluid img-feat-1" />
              <img src="<?= base_url('public/assets/img/feature-1.jpg') ?>" alt="" class="img-fluid img-feat-2" />
            </div>
            <div class="col-md-6 showcase-text ps-5">
              <h1 class="display-1 fw-light">
                Siempre la <br />
                <span class="fw-bold">Mejor</span> atención <br />
                al <span class="fw-bold">Cliente</span>
              </h1>
              <p class="lead py-2">
                Nos encanta estar a la vanguardia de las últimas tendencias y tecnologías para brindarte una experiencia
                de compra digital sin igual.
                Consulta sin compromiso, respondemos en menos de 30 minutos!!!
              </p>
              <a href="#" class="btn btn-primary px-5 py-2" data-bs-toggle="modal" data-bs-target="#modal1" data-toggle="tooltip" title="Haz clic para enviarnos un mensaje">Contactar<i
                  class="fa-regular fa-message ms-2 animate__animated animate__bounce animate__infinite animate__tada"></i></a>
            </div>
          </div>
        </div>
      </section>

      <!-- Modal -->
      <div id="modal1" class="modal fade">
        <div class="modal-dialog modal-lg mt-3">
          <div class="modal-content px-5 py-3">
            <div class="row">
              <div class="col-lg-12 contact-form">
                <div class="card border-0">
                  <div class="card-body">
                    <div class="text-end">
                      <button type="button" class="border-0" data-bs-dismiss="modal" aria-label="Close">
                        <i class="fa-solid fa-circle-xmark" style="color: red !important;"></i>
                      </button>
                    </div>

                    <div class="card-title text-center pb-3">
                      <h3>Estamos en contacto!</h3>
                      <p class="lead text-muted fw-light">Cuentanos sobre tí y nos comunicaremos a la brevedad.</p>
                    </div>

                    <form class="needs-validation" novalidate>
                      <div class="mb-3">
                        <label for="modalNombre" class="form-label">Nombre</label>
                        <input type="text" id="modalNombre" placeholder="Nombre" class="form-control" required>
                        <div class="invalid-feedback">Por favor ingresa tu nombre.</div>
                      </div>
                      <div class="mb-3">
                        <label for="modalApellido" class="form-label">Apellido</label>
                        <input type="text" id="modalApellido" placeholder="Apellido" class="form-control" required>
                        <div class="invalid-feedback">Por favor ingresa tu apellido.</div>
                      </div>
                      <div class="mb-3">
                        <label for="modalCorreo" class="form-label">Correo</label>
                        <input type="email" id="modalCorreo" placeholder="Correo" class="form-control" required>
                        <div class="invalid-feedback">Por favor ingresa un correo válido.</div>
                      </div>
                      <div class="mb-3">
                        <label for="modalMensaje" class="form-label">Mensaje</label>
                        <textarea id="modalMensaje" placeholder="Mensaje" class="form-control" rows="6" required></textarea>
                        <div class="invalid-feedback">Por favor ingresa un mensaje.</div>
                      </div>
                      <button type="submit" class="btn btn-primary w-100" id="btnEnviar">Enviar</button>
                    </form>

                      
                  </div>
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
          Comunicate con nosotros
        </h3>
  
        <p class="col-md-10 fs-4 my-4">
          ¿Tienes alguna pregunta, sugerencia o comentario? Estamos aquí para ayudarte. Comunícate con nuestro equipo de atención al cliente a través de nuestros medios. Tu opinión es importante para nosotros. ¡Respondemos a la brevedad!
        </p>
      </div>
    </div>
  </div>

  <div class="m-2 mt-md-3 mx-md-3 row p-4 pe-lg-0 py-lg-1 align-items-center rounded-3 shadow-lg"
    style="border: 1px solid black;">
    <div class="col-lg-6 p-3 p-lg-5 pt-lg-3">
      <h1 class="display-4 fw-bold lh-1 text-body-emphasis">Buscanos en nuestra tienda</h1>
      <p class="lead">O comunícate con nosotros directamente, te dejamos nuestros datos de contacto clickeando en el
        botón de aquí abajo</p>

      <a href="#" class="btn btn-primary px-5 py-2 fa-beat-fade" data-bs-toggle="modal" data-bs-target="#modal2">Datos
        de contacto<i class="fa-regular fa-address-card ms-2"></i></a>
    </div>

    <div class="col-lg-4 offset-lg-1 p-1 shadow-lg overflow-hidden">
      <div class="rounded-3 mt-2" id="map" style="height: 500px !important;"></div>

      <script>
        const map = L.map('map').setView([-27.4666381113767, -58.83231690000313], 13);

        const tiles = L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
          maxZoom: 40,
          attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        }).addTo(map);

        const marker = L.marker([-27.4666381113767, -58.83231690000313]).addTo(map);
      </script>
    </div>

    <div id="modal2" class="modal fade">
      <div class="modal-dialog modal-lg mt-3">
        <div class="modal-content px-5 py-3">
          <div class="row">
            <div class="col-lg-12 contact-form">
              <div class="card border-0">
                <div class="card-body">
                  <div class="text-end">
                    <button type="button" class="btn" data-bs-dismiss="modal"><i class="bi bi-x-circle-fill" style="color: red;"></i></i></button>
                  </div>
                  <div class="card-title text-center pb-3">
                    <h3>DATOS DE CONTACTO</h3>
                  </div>

                  <div class="container">
                    <p><span class="fw-bold ms-0 ms-md-3">Titular de la empresa:</span> INSUMOS FAT S.A.</p>
                    <p><span class="fw-bold ms-0 ms-md-3">Razón social:</span> XX-XX.XXX.XXX-XX</p>
                    <p><span class="fw-bold ms-0 ms-md-3">Dirección:</span> 9 de Julio XXXX</p>
                    <p><span class="fw-bold ms-0 ms-md-3">Telefono:</span> +54 9 3794-0000</p>
                    <p><span class="fw-bold ms-0 ms-md-3">Mail:</span> hola@insumosfat.com</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div id="modal1" class="modal fade">
      <div class="modal-dialog modal-lg mt-3">
        <div class="modal-content px-5 py-3">
          <div class="row">
            <div class="col-lg-12 contact-form">
              <div class="card border-0">
                <div class="card-body">
                  <div class="text-end">
                    <button type="button" class="btn" data-bs-dismiss="modal"><i class="bi bi-x-circle-fill" style="color: red;"></i></button>
                  </div>
                  <div class="card-title text-center pb-1">
                    <h3>DEJA TUS DATOS, NOSOTROS TE LLAMAMOS</h3>
                  </div>

                  
                    <div class="card-body">
                      <form>
                        <div class="mb-3">
                          <input type="text" placeholder="Nombre" class="form-control" />
                        </div>
                        <div class="mb-3">
                          <input type="text" placeholder="Apellido" class="form-control" />
                        </div>
                        <div class="mb-3">
                          <input type="email" placeholder="Correo" class="form-control" />
                        </div>
                        <div class="mb-3">
                          <input type="tel" placeholder="Teléfono" class="form-control" />
                        </div>
                        <input type="submit" value="Llámame" class="btn btn-primary w-100" />
                      </form>
                    </div>
                  

                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script>
    (() => {
      'use strict'
      const forms = document.querySelectorAll('.needs-validation')
      Array.from(forms).forEach(form => {
        form.addEventListener('submit', event => {
          if (!form.checkValidity()) {
            event.preventDefault()
            event.stopPropagation()
          }
          form.classList.add('was-validated')
        }, false)
      })
    })()
  </script>


  <script>
  document.getElementById('btnEnviar').addEventListener('click', function (event) {
    event.preventDefault(); // Prevenir el comportamiento por defecto de submit

    const form = document.querySelector('.needs-validation');

    // Verificar si el formulario es válido
    if (!form.checkValidity()) {
      form.classList.add('was-validated');
      return; // No continuar si el formulario no es válido
    }

    // Si el formulario es válido, continuar con la lógica de modales

    const modal1El = document.getElementById('modal1');
    const modal1 = bootstrap.Modal.getInstance(modal1El);

    const modal2El = document.getElementById('staticBackdrop');
    const modal2 = new bootstrap.Modal(modal2El);

    function cleanupModals() {
      const backdrop = document.querySelector('.modal-backdrop');
      if (backdrop) {
        backdrop.parentNode.removeChild(backdrop);
      }
      document.body.classList.remove('modal-open');
      document.body.style.overflow = 'auto';
    }

    if (modal1) {
      modal1.hide();

      modal1El.addEventListener('hidden.bs.modal', function () {
        cleanupModals();
        modal2.show();
      }, { once: true });
    } else {
      cleanupModals();
      modal2.show();
    }
  });
  </script>


</main>