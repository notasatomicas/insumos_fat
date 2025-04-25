<main class="container my-4">
  <div id="carouselExampleCaptions" class="carousel slide" data-bs-ride="carousel" data-bs-interval="3000">
    <div class="carousel-indicators">
      <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
      <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="1" aria-label="Slide 2"></button>
      <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="2" aria-label="Slide 3"></button>
    </div>
    <div class="carousel-inner">
      <div class="carousel-item active">
        <img src="<?= base_url('public/assets/img/1920x1080_1.jpg') ?>" class="d-block w-100" alt="...">
        <div class="carousel-caption d-none d-md-block">
          <h5>Los mejores periféricos</h5>
          <p>Sobrecarga de calidad en cada uno de nuestros productos.</p>
        </div>
      </div>
      <div class="carousel-item">
        <img src="<?= base_url('public/assets/img/1920x1080_2.jpg') ?>" class="d-block w-100" alt="...">
        <div class="carousel-caption d-none d-md-block">
          <h5>Calidad desde el primer contacto</h5>
          <p>Directamente de la fábrica a tu hogar, satisfacción asegurada</p>
        </div>
      </div>
      <div class="carousel-item">
        <img src="<?= base_url('public/assets/img/1920x1080_3.jpg') ?>" class="d-block w-100" alt="...">
        <div class="carousel-caption d-none d-md-block">
          <h5>Precios imbatibles</h5>
          <p>Nuestra tienda es líder en precios de periféricos</p>
        </div>
      </div>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
      <span class="carousel-control-next-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Next</span>
    </button>
  </div>

  <div class="products my-4 mx-2">
    <h2 class="text-center my-3">Nuestros productos</h2>
    <div class="row">
      <div class="mb-3 col-lg-3 col-md-4 col-sm-6">
        <div class="card">
          <a href="error" target="_blank"><img src="<?= base_url('public/assets/img/productos/p1.jpg') ?>" class="card-img-top" alt="Nike Slim shirt"></a>
          <div class="card-body"><a href="error" target="_blank">
              <div class="card-title h5">Iphone 13</div>
            </a>
            <div class="rating"><span><i class="fas fa-star"></i></span><span><i class="fas fa-star"></i></span><span><i class="far fa-star"></i></span><span><i class="far fa-star"></i></span><span><i class="far fa-star"></i></span><span> 12 reviews</span></div>
            <p class="card-text">$1.000.000,00</p><button type="button" class="btn btn-primary comprar">Añadir al carrito</button>
          </div>
        </div>
      </div>

      <div class="mb-3 col-lg-3 col-md-4 col-sm-6">
        <div class="card"><a href="error" target="_blank"><img src="<?= base_url('public/assets/img/productos/p2.jpg') ?>" class="card-img-top" alt="Nike Slim shirt"></a>
          <div class="card-body"><a href="error" target="_blank">
              <div class="card-title h5">Poco X6</div>
            </a>
            <div class="rating"><span><i class="fas fa-star"></i></span><span><i class="fas fa-star"></i></span><span><i class="far fa-star"></i></span><span><i class="far fa-star"></i></span><span><i class="far fa-star"></i></span><span> 12 reviews</span></div>
            <p class="card-text">$511.000,00</p><button type="button" disabled="" class="btn btn-light">Sin Stock</button>
          </div>
        </div>
      </div>

      <div class="mb-3 col-lg-3 col-md-4 col-sm-6">
        <div class="card"><a href="error" target="_blank"><img src="<?= base_url('public/assets/img/productos/p3.jpg') ?>" class="card-img-top" alt="Nike Slim shirt"></a>
          <div class="card-body"><a href="error" target="_blank">
              <div class="card-title h5">Smartwatch Kanji</div>
            </a>
            <div class="rating"><span><i class="fas fa-star"></i></span><span><i class="fas fa-star"></i></span><span><i class="far fa-star"></i></span><span><i class="far fa-star"></i></span><span><i class="far fa-star"></i></span><span> 12 reviews</span></div>
            <p class="card-text">$15.000,00</p><button type="button" class="btn btn-primary comprar">Añadir al carrito</button>
          </div>
        </div>
      </div>

      <div class="mb-3 col-lg-3 col-md-4 col-sm-6">
        <div class="card"><a href="error" target="_blank"><img src="<?= base_url('public/assets/img/productos/p4.jpg') ?>" class="card-img-top" alt="Nike Slim shirt"></a>
          <div class="card-body"><a href="error" target="_blank">
              <div class="card-title h5">Auricular Redragon</div>
            </a>
            <div class="rating"><span><i class="fas fa-star"></i></span><span><i class="fas fa-star"></i></span><span><i class="far fa-star"></i></span><span><i class="far fa-star"></i></span><span><i class="far fa-star"></i></span><span> 12 reviews</span></div>
            <p class="card-text">$120.000,00</p><button type="button" class="btn btn-primary comprar">Añadir al carrito</button>
          </div>
        </div>
      </div>

      <div class="mb-3 col-lg-3 col-md-4 col-sm-6">
        <div class="card"><a href="error" target="_blank"><img src="<?= base_url('public/assets/img/productos/p5.jpg') ?>" class="card-img-top" alt="Nike Slim shirt"></a>
          <div class="card-body"><a href="error" target="_blank">
              <div class="card-title h5">Jostick Generico</div>
            </a>
            <div class="rating"><span><i class="fas fa-star"></i></span><span><i class="fas fa-star"></i></span><span><i class="far fa-star"></i></span><span><i class="far fa-star"></i></span><span><i class="far fa-star"></i></span><span> 12 reviews</span></div>
            <p class="card-text">$10.200,00</p><button type="button" disabled="" class="btn btn-light">Sin Stock</button>
          </div>
        </div>
      </div>

      <div class="mb-3 col-lg-3 col-md-4 col-sm-6">
        <div class="card"><a href="error" target="_blank"><img src="<?= base_url('public/assets/img/productos/p6.jpg') ?>" class="card-img-top" alt="Nike Slim shirt"></a>
          <div class="card-body"><a href="error" target="_blank">
              <div class="card-title h5">Gabinete Termaltake</div>
            </a>
            <div class="rating"><span><i class="fas fa-star"></i></span><span><i class="fas fa-star"></i></span><span><i class="far fa-star"></i></span><span><i class="far fa-star"></i></span><span><i class="far fa-star"></i></span><span> 12 reviews</span></div>
            <p class="card-text">$200.000,00</p><button type="button" class="btn btn-primary comprar">Añadir al carrito</button>
          </div>
        </div>
      </div>

      <div class="mb-3 col-lg-3 col-md-4 col-sm-6">
        <div class="card"><a href="error" target="_blank"><img src="<?= base_url('public/assets/img/productos/p7.jpg') ?>" class="card-img-top" alt="Nike Slim shirt"></a>
          <div class="card-body"><a href="error" target="_blank">
              <div class="card-title h5">Gabinete Optimus</div>
            </a>
            <div class="rating"><span><i class="fas fa-star"></i></span><span><i class="fas fa-star"></i></span><span><i class="far fa-star"></i></span><span><i class="far fa-star"></i></span><span><i class="far fa-star"></i></span><span> 12 reviews</span></div>
            <p class="card-text">$130.000,00</p><button type="button" class="btn btn-primary comprar">Añadir al carrito</button>
          </div>
        </div>
      </div>

      <div class="mb-3 col-lg-3 col-md-4 col-sm-6">
        <div class="card"><a href="error" target="_blank"><img src="<?= base_url('public/assets/img/productos/p8.jpg') ?>" class="card-img-top" alt="Nike Slim shirt"></a>
          <div class="card-body"><a href="error" target="_blank">
              <div class="card-title h5">Mouse Logi</div>
            </a>
            <div class="rating"><span><i class="fas fa-star"></i></span><span><i class="fas fa-star"></i></span><span><i class="far fa-star"></i></span><span><i class="far fa-star"></i></span><span><i class="far fa-star"></i></span><span> 12 reviews</span></div>
            <p class="card-text">$10.000,00</p><button type="button" class="btn btn-primary comprar">Añadir al carrito</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</main>
