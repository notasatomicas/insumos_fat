<nav class="navbar navbar-expand-lg navbar-dark bg-dark" aria-label="Offcanvas navbar large">
  <div class="container-fluid">

    <!-- El titulo del navbar, nada mas -->
    <!-- hasta el domingo a las 1747 le faltaba el logo de mierda, tengo que arreglar esto y las transparencias -->
    <a class="navbar-brand animate__bounceIn" href="<?= base_url('/') ?>">
      <img src="<?= base_url('public/assets/img/icon.png') ?>" alt="Logo" width="57" height="50" class="d-inline-block">
      Insumos_FAT
    </a>

    <!-- Este es el toggler que solo se ve cuando se hace offcanvas -->
    <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar2" aria-controls="offcanvasNavbar2" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <!-- Aca finaliza (esto es solo el boton viejo, nada mas) lo separo por las dudas -->

    <!-- esta parte queda offcanvas -->
    <!-- tengase en cuenta que si a esto le agrego la clase show se va a mostrar el offcanvas permanentemente (para fines de desarrollo obvio) -->
    <div class="offcanvas offcanvas-end text-bg-dark" tabindex="-1" id="offcanvasNavbar2" aria-labelledby="offcanvasNavbar2Label">

      <!-- lo que se muestra en la cabecera en los dispositivos chiquitos -->
      <div class="offcanvas-header">

        <!-- Por el momento voy a comentar el titulo del offcanvas porque no lo veo relevante -->
        <!-- <h5 class="offcanvas-title" id="offcanvasNavbar2Label">Offcanvas</h5> -->

        <button type="button" class="btn" data-bs-dismiss="offcanvas" aria-label="Close" style="color: red;">
          <i class="bi bi-x-circle-fill"></i>
        </button>
      </div>

      <!-- lo que se muestra en el body en los dispositivos chiquitos -->
      <div class="offcanvas-body">

        <!-- clases originales que tenia -> navbar-nav justify-content-end flex-grow-1 pe-3 -->
        <ul class="navbar-nav nav-pills justify-content-end flex-grow-1 pe-3">

          <li class="nav-item mx-5 mx-sm-4 mx-lg-0">
            <a id="btn_nav_inicio" class="hvr-underline-from-center nav-link text-center px-4 active" href="<?= base_url('inicio') ?>">
              <i class="ms-5 ms-md-0 me-1 bi bi-house"></i>
              Inicio
            </a>
          </li>

          <li class="nav-item mx-5 mx-sm-4 mx-lg-0">
            <a id="btn_nav_comercializacion" class="hvr-underline-from-center nav-link text-center px-4" href="<?= base_url('comercializacion') ?>">
              <i class="ms-5 ms-md-0 me-1 bi bi-bag-check"></i>
              Comercializacion
            </a>
          </li>

          <li class="nav-item mx-5 mx-sm-4 mx-lg-0">
            <a id="btn_nav_nosotros" class="hvr-underline-from-center nav-link text-center px-4" href="<?= base_url('nosotros') ?>">
              <i class="ms-5 ms-md-0 me-1 bi bi-person-circle"></i>
              Quienes somos
            </a>
          </li>

          <li class="nav-item mx-5 mx-sm-4 mx-lg-0">
            <a id="btn_nav_contacto" class="hvr-underline-from-center nav-link text-center px-4" href="<?= base_url('contacto') ?>">
              <i class="ms-5 ms-md-0 me-1 bi bi-telephone"></i>
              Contacto
            </a>
          </li>

          <li class="nav-item mx-5 mx-sm-4 mx-lg-0">
            <a id="btn_nav_tyc" class="hvr-underline-from-center nav-link text-center px-4" href="<?= base_url('terminos') ?>">
              <i class="ms-5 ms-md-0 me-1 bi bi-file-earmark-lock"></i>
              Términos y condiciones
            </a>
          </li>

        </ul>

      </div>
    </div>
  </div>
</nav>
