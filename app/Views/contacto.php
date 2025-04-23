<main class="container-fluid my-4">
  <div class="m-md-3">
    <div class="p-5 m-2 bg-body-secondary rounded-3 border animate animate__fadeInDown">
      <div class="container-fluid py-5 ps-md-5">
        <h3 class="display-5 fw-bold mb-5 mb-md-0">
          Comunicate con nosotros
        </h3>
  
        <p class="col-md-10 fs-4 my-4">
          ¿Tienes alguna pregunta, sugerencia o comentario? Estamos aquí para ayudarte. Comunícate con nuestro equipo de atención al cliente a través de nuestros medios. Tu opinión es importante para nosotros. ¡Respondemos a la brevedad!
        </p>
        <button class="btn btn-primary btn-lg px-5 py-2" type="button" data-bs-toggle="modal" data-bs-target="#modal1">Comunicate</button>
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
                    <p><span class="fw-bold ms-0 ms-md-3">Titular de la empresa:</span> ANTINORI ARIEL</p>
                    <p><span class="fw-bold ms-0 ms-md-3">Razón social:</span> XX-XX.XXX.XXX-XX</p>
                    <p><span class="fw-bold ms-0 ms-md-3">Dirección:</span> 9 de Julio XXXX</p>
                    <p><span class="fw-bold ms-0 ms-md-3">Telefono:</span> +54 9 3794-0000</p>
                    <p><span class="fw-bold ms-0 ms-md-3">Mail:</span> ariel@libreriax.com</p>
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
</main>

