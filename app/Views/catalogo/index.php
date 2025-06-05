<!DOCTYPE html>
<html>

<head>
    <!-- la cabecera de todas las paginas -->
    <?= view('layout/base_head') ?>
    <style>
        .product-card {
            transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        .product-img {
            height: 200px;
            object-fit: cover;
        }

        .sidebar {
            background-color: #f8f9fa;
            min-height: calc(100vh - 56px);
        }

        .category-item {
            transition: all 0.2s ease;
        }

        .category-item:hover {
            background-color: #e9ecef;
            padding-left: 1.5rem;
        }

        .price {
            font-size: 1.25rem;
            font-weight: bold;
            color: #28a745;
        }

        .original-price {
            text-decoration: line-through;
            color: #6c757d;
            font-size: 0.9rem;
        }

        .discount-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            background-color: #dc3545;
            color: white;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: bold;
        }

        .rating {
            color: #ffc107;
        }
    </style>
</head>

<body>

    <!-- Navbar -->
    <?= view('layout/navbar') ?>

    <!-- Contenido específico de la página -->
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar de Categorías -->
            <div class="col-lg-3 col-md-4 sidebar p-4">
                <h4 class="mb-4">
                    <i class="fas fa-filter me-2"></i>Categorías
                </h4>

                <!-- Lista de Categorías -->
                <div class="list-group list-group-flush">
                    <a href="#" class="list-group-item list-group-item-action category-item border-0 px-3 py-2 active">
                        <i class="fas fa-th-large me-2"></i>Todas las categorías
                        <span class="badge bg-primary rounded-pill float-end">248</span>
                    </a>
                    <a href="#" class="list-group-item list-group-item-action category-item border-0 px-3 py-2">
                        <i class="fas fa-laptop me-2"></i>Electrónicos
                        <span class="badge bg-secondary rounded-pill float-end">45</span>
                    </a>
                    <a href="#" class="list-group-item list-group-item-action category-item border-0 px-3 py-2">
                        <i class="fas fa-tshirt me-2"></i>Ropa y Moda
                        <span class="badge bg-secondary rounded-pill float-end">78</span>
                    </a>
                    <a href="#" class="list-group-item list-group-item-action category-item border-0 px-3 py-2">
                        <i class="fas fa-home me-2"></i>Hogar y Jardín
                        <span class="badge bg-secondary rounded-pill float-end">32</span>
                    </a>
                    <a href="#" class="list-group-item list-group-item-action category-item border-0 px-3 py-2">
                        <i class="fas fa-gamepad me-2"></i>Deportes y Recreación
                        <span class="badge bg-secondary rounded-pill float-end">29</span>
                    </a>
                    <a href="#" class="list-group-item list-group-item-action category-item border-0 px-3 py-2">
                        <i class="fas fa-book me-2"></i>Libros y Educación
                        <span class="badge bg-secondary rounded-pill float-end">41</span>
                    </a>
                    <a href="#" class="list-group-item list-group-item-action category-item border-0 px-3 py-2">
                        <i class="fas fa-car me-2"></i>Automotriz
                        <span class="badge bg-secondary rounded-pill float-end">23</span>
                    </a>
                </div>

                <!-- Filtros adicionales -->
                <hr class="my-4">
                <h5 class="mb-3">
                    <i class="fas fa-sliders-h me-2"></i>Filtros
                </h5>

                <!-- Rango de precios -->
                <div class="mb-4">
                    <label class="form-label"><strong>Rango de Precio</strong></label>
                    <div class="row g-2">
                        <div class="col">
                            <input type="number" class="form-control form-control-sm" placeholder="Mín" value="0">
                        </div>
                        <div class="col">
                            <input type="number" class="form-control form-control-sm" placeholder="Máx" value="1000">
                        </div>
                    </div>
                </div>

                <button class="btn btn-primary w-100">
                    <i class="fas fa-search me-2"></i>Aplicar Filtros
                </button>
            </div>

            <!-- Contenido Principal - Productos -->
            <div class="col-lg-9 col-md-8 p-4">
                <!-- Header del catálogo -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h2 class="mb-1">Catálogo de Productos</h2>
                        <p class="text-muted mb-0">Mostrando 12 de 248 productos</p>
                    </div>
                    <div class="d-flex align-items-center gap-3">
                        <!-- Ordenar por -->
                        <select class="form-select" style="width: auto;">
                            <option>Precio: Menor a Mayor</option>
                            <option>Precio: Mayor a Menor</option>
                            <option>Más Vendidos</option>
                            <option>Más Recientes</option>
                        </select>

                        <!-- Vista de cuadrícula/lista -->
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-outline-secondary active">
                                <i class="fas fa-th"></i>
                            </button>
                            <button type="button" class="btn btn-outline-secondary">
                                <i class="fas fa-list"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Grid de Productos -->
                <div class="row g-4">
                    <!-- Producto 1 -->
                    <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6">
                        <div class="card product-card h-100 border-0 shadow-sm">
                            <div class="position-relative">
                                <img src="https://placehold.co/300x200?text=Item" class="card-img-top product-img"
                                    alt="Laptop Gaming">
                                <div class="discount-badge">-20%</div>
                            </div>
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title">Laptop Gaming RGB Pro</h5>
                                <p class="card-text text-muted flex-grow-1">Intel i7, 16GB RAM, RTX 4060, SSD 1TB.
                                    Perfecta para gaming y trabajo profesional.</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <span class="price">$1,599.99</span>
                                        <br>
                                        <small class="original-price">$1,999.99</small>
                                    </div>
                                    <button class="btn btn-primary">
                                        <i class="fas fa-cart-plus me-1"></i>Agregar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Producto 2 -->
                    <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6">
                        <div class="card product-card h-100 border-0 shadow-sm">
                            <div class="position-relative">
                                <img src="https://placehold.co/300x200?text=Item" class="card-img-top product-img"
                                    alt="Smartphone">
                            </div>
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title">Smartphone 5G Ultra</h5>
                                <p class="card-text text-muted flex-grow-1">Pantalla AMOLED 6.7", Cámara 108MP, Batería
                                    5000mAh, 256GB almacenamiento.</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <span class="price">$899.99</span>
                                    </div>
                                    <button class="btn btn-primary">
                                        <i class="fas fa-cart-plus me-1"></i>Agregar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Producto 3 -->
                    <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6">
                        <div class="card product-card h-100 border-0 shadow-sm">
                            <div class="position-relative">
                                <img src="https://placehold.co/300x200?text=Item" class="card-img-top product-img"
                                    alt="Smart TV">
                                <div class="discount-badge">-15%</div>
                            </div>
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title">Smart TV 4K 55"</h5>
                                <p class="card-text text-muted flex-grow-1">Android TV, HDR10+, Dolby Vision, WiFi 6,
                                    Control por voz, Apps preinstaladas.</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <span class="price">$649.99</span>
                                        <br>
                                        <small class="original-price">$799.99</small>
                                    </div>
                                    <button class="btn btn-primary">
                                        <i class="fas fa-cart-plus me-1"></i>Agregar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Producto 4 -->
                    <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6">
                        <div class="card product-card h-100 border-0 shadow-sm">
                            <div class="position-relative">
                                <img src="https://placehold.co/300x200?text=Item" class="card-img-top product-img"
                                    alt="Auriculares">
                            </div>
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title">Auriculares Bluetooth Pro</h5>
                                <p class="card-text text-muted flex-grow-1">Cancelación de ruido activa, 30h batería,
                                    Audio Hi-Fi, Micrófono integrado.</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <span class="price">$199.99</span>
                                    </div>
                                    <button class="btn btn-primary">
                                        <i class="fas fa-cart-plus me-1"></i>Agregar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Producto 5 -->
                    <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6">
                        <div class="card product-card h-100 border-0 shadow-sm">
                            <div class="position-relative">
                                <img src="https://placehold.co/300x200?text=Item" class="card-img-top product-img"
                                    alt="Camisa">
                                <div class="discount-badge">-30%</div>
                            </div>
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title">Camisa Casual Premium</h5>
                                <p class="card-text text-muted flex-grow-1">100% Algodón, Corte slim fit, Disponible en
                                    varios colores y tallas.</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <span class="price">$49.99</span>
                                        <br>
                                        <small class="original-price">$69.99</small>
                                    </div>
                                    <button class="btn btn-primary">
                                        <i class="fas fa-cart-plus me-1"></i>Agregar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Producto 6 -->
                    <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6">
                        <div class="card product-card h-100 border-0 shadow-sm">
                            <div class="position-relative">
                                <img src="https://placehold.co/300x200?text=Item" class="card-img-top product-img"
                                    alt="Cafetera">
                            </div>
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title">Cafetera Express Digital</h5>
                                <p class="card-text text-muted flex-grow-1">15 bares presión, Pantalla LCD, Espumador
                                    automático, Programable.</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <span class="price">$299.99</span>
                                    </div>
                                    <button class="btn btn-primary">
                                        <i class="fas fa-cart-plus me-1"></i>Agregar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Producto 7 -->
                    <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6">
                        <div class="card product-card h-100 border-0 shadow-sm">
                            <div class="position-relative">
                                <img src="https://placehold.co/300x200?text=Item" class="card-img-top product-img"
                                    alt="Mochila">
                                <div class="discount-badge">-25%</div>
                            </div>
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title">Mochila de Viaje Pro</h5>
                                <p class="card-text text-muted flex-grow-1">40L capacidad, Impermeable, Puerto USB,
                                    Compartimento laptop, Ultra resistente.</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <span class="price">$89.99</span>
                                        <br>
                                        <small class="original-price">$119.99</small>
                                    </div>
                                    <button class="btn btn-primary">
                                        <i class="fas fa-cart-plus me-1"></i>Agregar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Producto 8 -->
                    <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6">
                        <div class="card product-card h-100 border-0 shadow-sm">
                            <div class="position-relative">
                                <img src="https://placehold.co/300x200?text=Item" class="card-img-top product-img"
                                    alt="Libro">
                            </div>
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title">Guía Completa de Programación</h5>
                                <p class="card-text text-muted flex-grow-1">Aprende múltiples lenguajes, 500+ páginas,
                                    Ejemplos prácticos, Ejercicios incluidos.</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <span class="price">$39.99</span>
                                    </div>
                                    <button class="btn btn-primary">
                                        <i class="fas fa-cart-plus me-1"></i>Agregar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Producto 9 -->
                    <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6">
                        <div class="card product-card h-100 border-0 shadow-sm">
                            <div class="position-relative">
                                <img src="https://placehold.co/300x200?text=Item" class="card-img-top product-img"
                                    alt="Planta">
                                <div class="discount-badge">-10%</div>
                            </div>
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title">Planta Decorativa Interior</h5>
                                <p class="card-text text-muted flex-grow-1">Monstera Deliciosa, Maceta incluida, Fácil
                                    cuidado, Purifica el aire.</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <span class="price">$29.99</span>
                                        <br>
                                        <small class="original-price">$34.99</small>
                                    </div>
                                    <button class="btn btn-primary">
                                        <i class="fas fa-cart-plus me-1"></i>Agregar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Paginación -->
                <nav aria-label="Navegación de productos" class="mt-5">
                    <ul class="pagination justify-content-center">
                        <li class="page-item disabled">
                            <a class="page-link" href="#" tabindex="-1">Anterior</a>
                        </li>
                        <li class="page-item active">
                            <a class="page-link" href="#">1</a>
                        </li>
                        <li class="page-item">
                            <a class="page-link" href="#">2</a>
                        </li>
                        <li class="page-item">
                            <a class="page-link" href="#">3</a>
                        </li>
                        <li class="page-item">
                            <a class="page-link" href="#">4</a>
                        </li>
                        <li class="page-item">
                            <a class="page-link" href="#">5</a>
                        </li>
                        <li class="page-item">
                            <a class="page-link" href="#">Siguiente</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <?= view('layout/footer') ?>

    <!-- Scripts generales que se usan en todas las paginas -->
    <?= view('layout/base_scripts') ?>

    <!-- Bootstrap JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>

    <script>
        // Funcionalidad para cambiar vista de cuadrícula
        document.querySelectorAll('.btn-group button').forEach(button => {
            button.addEventListener('click', function () {
                document.querySelectorAll('.btn-group button').forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');
            });
        });

        // Funcionalidad para las categorías del sidebar
        document.querySelectorAll('.category-item').forEach(item => {
            item.addEventListener('click', function (e) {
                e.preventDefault();
                document.querySelectorAll('.category-item').forEach(cat => cat.classList.remove('active'));
                this.classList.add('active');
            });
        });

        // Simular agregar al carrito
        document.querySelectorAll('.btn-primary').forEach(button => {
            if (button.innerHTML.includes('Agregar')) {
                button.addEventListener('click', function () {
                    const originalText = this.innerHTML;
                    this.innerHTML = '<i class="fas fa-check me-1"></i>Agregado';
                    this.classList.remove('btn-primary');
                    this.classList.add('btn-success');

                    setTimeout(() => {
                        this.innerHTML = originalText;
                        this.classList.remove('btn-success');
                        this.classList.add('btn-primary');
                    }, 2000);
                });
            }
        });
    </script>
</body>

</html>