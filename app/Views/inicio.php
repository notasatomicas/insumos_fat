<!DOCTYPE html>
<html>

<head>
    <!-- la cabecera de todas las paginas -->
    <?= view('layout/base_head') ?>

    <?= view('adicional/modal_development_estilos.html') ?>
    
    <style>
        /* Estilos específicos para las cards de productos */
        .product-card {
            transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        }
        
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
        }
        
        .product-img-container {
            position: relative;
            height: 200px;
            overflow: hidden;
        }
        
        .product-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }
        
        .product-card:hover .product-img {
            transform: scale(1.05);
        }
        
        .no-image {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100%;
            background-color: #f8f9fa;
            color: #6c757d;
        }
        
        .no-image i {
            font-size: 3rem;
            margin-bottom: 0.5rem;
        }
        
        .image-error {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100%;
            background-color: #f8f9fa;
            color: #dc3545;
        }
        
        .image-error i {
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }
        
        .escaso {
            position: absolute;
            top: 10px;
            right: 10px;
            background-color: #dc3545;
            color: white;
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 0.75rem;
            font-weight: bold;
            z-index: 2;
        }
        
        .price {
            font-size: 1.25rem;
            font-weight: bold;
            color: #198754;
        }
        
        .btn-agregar-carrito {
            transition: all 0.2s ease;
        }
        
        .btn-agregar-carrito:hover {
            transform: scale(1.05);
        }
        
        .stock-badge {
            background-color: #ffc107;
            color: #000;
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 0.75rem;
            font-weight: bold;
        }

        /* Estilo para botones agregados al carrito */
        .btn-agregado {
            background-color: #28a745 !important;
            border-color: #28a745 !important;
            cursor: not-allowed;
        }

        .btn-agregado:hover {
            background-color: #28a745 !important;
            border-color: #28a745 !important;
        }
    </style>
</head>

<body>

    <!-- Navbar -->
    <?= view('layout/navbar') ?>

    <?= view('carrito/carrito_button') ?>

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

        <div class="products my-5 mx-2">
            <div class="text-center mb-4">
                <h2 class="display-6 fw-bold">¡Últimas Unidades Disponibles!</h2>
                <p class="lead text-muted">Aprovecha estos productos con stock limitado</p>
            </div>
            
            <?php if (!empty($productosStockBajo)): ?>
                <div class="row g-4">
                    <?php foreach ($productosStockBajo as $producto): ?>
                        <div class="col-lg-4 col-md-6">
                            <div class="card product-card h-100 border-1 shadow-sm rounded-3">
                                <div class="product-img-container">
                                    <?php if (isset($producto['tiene_imagen']) && $producto['tiene_imagen']): ?>
                                        <!-- Usar la URL completa procesada por el controlador -->
                                        <img src="<?= $producto['imagen_url_completa'] ?>" 
                                             class="product-img rounded-top-3" 
                                             alt="<?= esc($producto['nombre_prod']) ?>"
                                             loading="lazy"
                                             onerror="this.parentElement.innerHTML='<div class=&quot;image-error&quot;><i class=&quot;fas fa-exclamation-triangle&quot;></i><small>Error al cargar imagen</small></div>'">
                                    <?php elseif (!empty($producto['imagen_url'])): ?>
                                        <!-- Fallback: usar el método tradicional si no está procesada -->
                                        <img src="<?= base_url('public') . '/' . ltrim($producto['imagen_url'], '/') ?>" 
                                             class="product-img rounded-top-3" 
                                             alt="<?= esc($producto['nombre_prod']) ?>"
                                             loading="lazy"
                                             onerror="this.parentElement.innerHTML='<div class=&quot;image-error&quot;><i class=&quot;fas fa-exclamation-triangle&quot;></i><small>Error al cargar imagen</small></div>'">
                                    <?php else: ?>
                                        <!-- Sin imagen disponible -->
                                        <div class="no-image">
                                            <i class="fas fa-image"></i>
                                            <small>Sin imagen</small>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <!-- Badge de stock bajo -->
                                    <div class="escaso">¡Solo <?= $producto['stock'] ?>!</div>
                                </div>
                                
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title text-truncate" title="<?= esc($producto['nombre_prod']) ?>">
                                        <?= esc($producto['nombre_prod']) ?>
                                    </h5>
                                    
                                    <p class="card-text text-muted flex-grow-1">
                                        <?= esc(substr($producto['descripcion'] ?? 'Sin descripción disponible', 0, 100)) ?>
                                        <?= strlen($producto['descripcion'] ?? '') > 100 ? '...' : '' ?>
                                    </p>
                                    
                                    <?php if (!empty($producto['categoria_nombre'])): ?>
                                        <small class="text-muted mb-2">
                                            <i class="fas fa-tag me-1"></i><?= esc($producto['categoria_nombre']) ?>
                                        </small>
                                    <?php endif; ?>
                                    
                                    <div class="d-flex justify-content-between align-items-center mt-auto">
                                        <div>
                                            <span class="price">$<?= number_format($producto['precio'], 2) ?></span>
                                            <br>
                                            <span class="stock-badge">
                                                <i class="fas fa-exclamation-triangle me-1"></i>
                                                Stock: <?= $producto['stock'] ?>
                                            </span>
                                        </div>
                                        
                                        <?php if ($producto['stock'] > 0): ?>
                                            <button class="btn btn-primary btn-agregar-carrito" 
                                                    data-producto-id="<?= $producto['id_producto'] ?>"
                                                    data-producto-nombre="<?= esc($producto['nombre_prod']) ?>">
                                                <i class="fas fa-cart-plus me-1"></i>Agregar
                                            </button>
                                        <?php else: ?>
                                            <button class="btn btn-secondary" disabled>
                                                <i class="fas fa-times me-1"></i>Sin Stock
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <!-- Enlace para ver más productos -->
                <div class="text-center mt-4">
                    <a href="<?= base_url('catalogo') ?>" class="btn btn-outline-primary btn-lg">
                        <i class="fas fa-eye me-2"></i>Ver Todo el Catálogo
                    </a>
                </div>
                
            <?php else: ?>
                <!-- Mensaje cuando no hay productos con stock bajo -->
                <div class="text-center py-5">
                    <div class="mb-3">
                        <i class="fas fa-check-circle text-success" style="font-size: 4rem;"></i>
                    </div>
                    <h4 class="text-muted">¡Excelente!</h4>
                    <p class="lead text-muted">Todos nuestros productos tienen buen stock disponible</p>
                    <a href="<?= base_url('catalogo') ?>" class="btn btn-primary btn-lg">
                        <i class="fas fa-shopping-bag me-2"></i>Ver Catálogo Completo
                    </a>
                </div>
            <?php endif; ?>
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

    <!-- Script para manejar el carrito -->
    <script>
        // Funciones para manejo del carrito en localStorage
        const CarritoLocalStorage = {
            // Obtener carrito del localStorage
            obtenerCarrito: function() {
                const carrito = localStorage.getItem('carrito');
                return carrito ? JSON.parse(carrito) : {};
            },

            // Guardar carrito en localStorage
            guardarCarrito: function(carrito) {
                localStorage.setItem('carrito', JSON.stringify(carrito));
            },

            // Agregar producto al carrito
            agregarProducto: function(productoId) {
                const carrito = this.obtenerCarrito();
                const id = productoId.toString();

                if (carrito[id]) {
                    // Si ya existe, incrementar cantidad
                    carrito[id].cantidad += 1;
                } else {
                    // Si no existe, agregar nuevo producto
                    carrito[id] = {
                        id: id,
                        cantidad: 1
                    };
                }

                this.guardarCarrito(carrito);
                return carrito;
            },

            // Verificar si un producto está en el carrito
            productoEnCarrito: function(productoId) {
                const carrito = this.obtenerCarrito();
                return carrito.hasOwnProperty(productoId.toString());
            }
        };

        // Función para verificar y actualizar estado de botones al cargar la página
        function actualizarEstadoBotones() {
            document.querySelectorAll('.btn-agregar-carrito').forEach(button => {
                const productoId = button.dataset.productoId;
                if (CarritoLocalStorage.productoEnCarrito(productoId)) {
                    button.innerHTML = '<i class="fas fa-check me-1"></i>En Carrito';
                    button.classList.remove('btn-primary');
                    button.classList.add('btn-success', 'btn-agregado');
                    button.disabled = true;
                }
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Actualizar estado inicial de los botones
            actualizarEstadoBotones();
            
            // Manejar clicks en botones de agregar al carrito
            document.querySelectorAll('.btn-agregar-carrito').forEach(function(button) {
                button.addEventListener('click', function() {
                    const productoId = this.getAttribute('data-producto-id');
                    const productoNombre = this.getAttribute('data-producto-nombre');
                    const originalText = this.innerHTML;
                    
                    // Verificar si ya está en el carrito
                    if (CarritoLocalStorage.productoEnCarrito(productoId)) {
                        return; // No hacer nada si ya está agregado
                    }
                    
                    // Cambiar apariencia del botón mientras se procesa
                    this.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Agregando...';
                    this.disabled = true;
                    
                    // Agregar al localStorage
                    try {
                        const carritoActualizado = CarritoLocalStorage.agregarProducto(productoId);
                        actualizarAnimacionCarrito();
                        
                        // Éxito - cambiar botón a estado "agregado"
                        setTimeout(() => {
                            this.innerHTML = '<i class="fas fa-check me-1"></i>En Carrito';
                            this.classList.remove('btn-primary');
                            this.classList.add('btn-success', 'btn-agregado');
                            
                            // Mostrar feedback temporal con toast
                            const toast = document.createElement('div');
                            toast.className = 'position-fixed top-0 end-0 p-3';
                            toast.style.zIndex = '9999';
                            toast.innerHTML = `
                                <div class="toast show" role="alert">
                                    <div class="toast-header">
                                        <i class="fas fa-shopping-cart text-success me-2"></i>
                                        <strong class="me-auto">Carrito</strong>
                                        <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
                                    </div>
                                    <div class="toast-body">
                                        "${productoNombre}" agregado al carrito
                                    </div>
                                </div>
                            `;
                            document.body.appendChild(toast);
                            
                            // Remover toast después de 3 segundos
                            setTimeout(() => {
                                if (toast.parentNode) {
                                    toast.parentNode.removeChild(toast);
                                }
                            }, 3000);
                            
                            // Agregar evento para cerrar toast manualmente
                            const closeButton = toast.querySelector('.btn-close');
                            if (closeButton) {
                                closeButton.addEventListener('click', () => {
                                    if (toast.parentNode) {
                                        toast.parentNode.removeChild(toast);
                                    }
                                });
                            }
                        }, 500);
                        
                    } catch (error) {
                        console.error('Error al agregar al localStorage:', error);
                        
                        // Error - mostrar mensaje y revertir botón
                        setTimeout(() => {
                            this.innerHTML = '<i class="fas fa-times me-1"></i>Error';
                            this.classList.remove('btn-primary');
                            this.classList.add('btn-danger');
                            
                            // Mostrar mensaje de error
                            alert('Error al agregar producto al carrito. Por favor, intenta nuevamente.');
                            
                            // Volver al estado original después de 2 segundos
                            setTimeout(() => {
                                this.innerHTML = originalText;
                                this.classList.remove('btn-danger');
                                this.classList.add('btn-primary');
                                this.disabled = false;
                            }, 2000);
                        }, 500);
                    }
                });
            });
        });
    </script>
</body>

</html>