<!DOCTYPE html>
<html>

<head>
    <!-- la cabecera de todas las paginas -->
    <?= view('layout/base_head') ?>

    <?= view('adicional/modal_development_estilos.html') ?>
</head>

<body>

    <!-- Navbar -->
    <?= view('layout/navbar') ?>

    <!-- Contenido específico de la página -->
    <main class="container my-4">
        <div id="carouselExampleCaptions" class="carousel slide" data-bs-ride="carousel" data-bs-interval="3000">
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="0" class="active"
                    aria-current="true" aria-label="Slide 1"></button>
                <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="1"
                    aria-label="Slide 2"></button>
                <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="2"
                    aria-label="Slide 3"></button>
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
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions"
                data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions"
                data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>

        <div class="products my-4 mx-2">
            <h2 class="text-center my-3">Nuestros productos</h2>
            <!-- aca deben ir un par de cards de productos -->
        </div>
    </main>


    <!-- El modal "en desarrollo" -->
    <?= view('adicional/modal_development.html') ?>

    <!-- Footer -->
    <?= view('layout/footer') ?>

    <!-- Scripts generales que se usan en todas las paginas -->
    <?= view('layout/base_scripts') ?>

    <!-- Scripts del modal "en desarrollo" -->
    <?= view('adicional/script_development.js') ?>
</body>

</html>