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
            width: 100%;
        }

        .product-img-container {
            position: relative;
            overflow: hidden;
            height: 200px;
            background-color: #f8f9fa;
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

        .escaso {
            position: absolute;
            top: 10px;
            right: 10px;
            background-color: #dc3545;
            color: white;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: bold;
            z-index: 2;
        }

        .rating {
            color: #ffc107;
        }

        .no-image {
            background-color: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #6c757d;
            font-size: 0.9rem;
            height: 100%;
            flex-direction: column;
        }

        .no-image i {
            font-size: 3rem;
            margin-bottom: 10px;
            opacity: 0.5;
        }

        .image-error {
            background-color: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #6c757d;
            font-size: 0.8rem;
            height: 200px;
            flex-direction: column;
        }

        .image-error i {
            font-size: 2.5rem;
            margin-bottom: 8px;
            opacity: 0.4;
        }

        /* Efecto de hover en las imágenes */
        .product-img-container:hover .product-img {
            transform: scale(1.05);
            transition: transform 0.3s ease;
        }

        /* Loading placeholder */
        .img-loading {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: loading 1.5s infinite;
        }

        @keyframes loading {
            0% {
                background-position: 200% 0;
            }
            100% {
                background-position: -200% 0;
            }
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

                <!-- Formulario de filtros -->
                <form method="GET" action="<?= base_url('catalogo') ?>" id="filtrosForm">
                    <!-- Lista de Categorías -->
                    <div class="list-group list-group-flush">
                        <a href="<?= base_url('catalogo') ?>" class="list-group-item list-group-item-action category-item border-0 px-3 py-2 <?= (!isset($filtros['categoria']) || $filtros['categoria'] == 'all') ? 'active' : '' ?>">
                            <i class="fas fa-th-large me-2"></i>Todas las categorías
                            <span class="badge bg-primary rounded-pill float-end"><?= $totalProductos ?></span>
                        </a>
                        <?php if (isset($categorias) && !empty($categorias)): ?>
                            <?php foreach ($categorias as $categoria): ?>
                                <a href="<?= base_url('catalogo?categoria=' . $categoria['id_categoria']) ?>" 
                                   class="list-group-item list-group-item-action category-item border-0 px-3 py-2 <?= (isset($filtros['categoria']) && $filtros['categoria'] == $categoria['id_categoria']) ? 'active' : '' ?>">
                                    <i class="fas fa-tag me-2"></i><?= esc($categoria['nombre']) ?>
                                    <span class="badge bg-secondary rounded-pill float-end"><?= $categoria['total_productos'] ?></span>
                                </a>
                            <?php endforeach; ?>
                        <?php endif; ?>
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
                                <input type="number" class="form-control form-control-sm" name="precio_min" 
                                       placeholder="Mín" value="<?= $filtros['precio_min'] ?? '' ?>" min="0" step="0.01">
                            </div>
                            <div class="col">
                                <input type="number" class="form-control form-control-sm" name="precio_max" 
                                       placeholder="Máx" value="<?= $filtros['precio_max'] ?? '' ?>" min="0" step="0.01">
                            </div>
                        </div>
                    </div>

                    <!-- Búsqueda -->
                    <div class="mb-4">
                        <label class="form-label"><strong>Buscar</strong></label>
                        <input type="text" class="form-control form-control-sm" name="buscar" 
                               placeholder="Nombre del producto..." value="<?= $filtros['buscar'] ?? '' ?>">
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search me-2"></i>Aplicar Filtros
                    </button>
                </form>
            </div>

            <!-- Contenido Principal - Productos -->
            <div class="col-lg-9 col-md-8 p-4">
                <!-- Header del catálogo -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h2 class="mb-1">Catálogo de Productos</h2>
                        <p class="text-muted mb-0">Mostrando <?= $productosMostrados ?> de <?= $totalProductos ?> productos</p>
                    </div>
                    <div class="d-flex align-items-center gap-3">
                        <!-- Ordenar por -->
                        <form method="GET" action="<?= base_url('catalogo') ?>" class="d-inline">
                            <?php if (isset($filtros)): ?>
                                <?php foreach ($filtros as $key => $value): ?>
                                    <?php if ($key !== 'ordenar' && !empty($value)): ?>
                                        <input type="hidden" name="<?= $key ?>" value="<?= esc($value) ?>">
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            <?php endif; ?>
                            <select name="ordenar" class="form-select" style="width: auto;" onchange="this.form.submit()">
                                <option value="precio_asc" <?= (isset($filtros['ordenar']) && $filtros['ordenar'] == 'precio_asc') ? 'selected' : '' ?>>Precio: Menor a Mayor</option>
                                <option value="precio_desc" <?= (isset($filtros['ordenar']) && $filtros['ordenar'] == 'precio_desc') ? 'selected' : '' ?>>Precio: Mayor a Menor</option>
                                <option value="vendidos" <?= (isset($filtros['ordenar']) && $filtros['ordenar'] == 'vendidos') ? 'selected' : '' ?>>Más Vendidos</option>
                                <option value="recientes" <?= (isset($filtros['ordenar']) && $filtros['ordenar'] == 'recientes') ? 'selected' : '' ?>>Más Recientes</option>
                                <option value="nombre_asc" <?= (!isset($filtros['ordenar']) || $filtros['ordenar'] == 'nombre_asc') ? 'selected' : '' ?>>Nombre A-Z</option>
                                <option value="nombre_desc" <?= (isset($filtros['ordenar']) && $filtros['ordenar'] == 'nombre_desc') ? 'selected' : '' ?>>Nombre Z-A</option>
                            </select>
                        </form>
                    </div>
                </div>

                <!-- Grid de Productos -->
                <div class="row g-4">
                    <?php if (isset($productos) && !empty($productos)): ?>
                        <?php foreach ($productos as $producto): ?>
                            <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6">

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
                                        
                                        <?php if ($producto['stock'] <= 5 && $producto['stock'] > 0): ?>
                                            <div class="escaso">¡Pocos!</div>
                                        <?php elseif ($producto['stock'] == 0): ?>
                                            <div class="escaso" style="background-color: #6c757d;">Sin Stock</div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="card-body d-flex flex-column">
                                        <h5 class="card-title"><?= esc($producto['nombre_prod']) ?></h5>
                                        <p class="card-text text-muted flex-grow-1">
                                            <?= esc(substr($producto['descripcion'] ?? 'Sin descripción disponible', 0, 100)) ?>
                                            <?= strlen($producto['descripcion'] ?? '') > 100 ? '...' : '' ?>
                                        </p>
                                        <?php if (!empty($producto['categoria_nombre'])): ?>
                                            <small class="text-muted mb-2">
                                                <i class="fas fa-tag me-1"></i><?= esc($producto['categoria_nombre']) ?>
                                            </small>
                                        <?php endif; ?>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <span class="price">$<?= number_format($producto['precio'], 2) ?></span>
                                                <br>
                                                <small class="text-muted">Stock: <?= $producto['stock'] ?></small>
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
                    <?php else: ?>
                        <div class="col-12">
                            <div class="text-center py-5">
                                <i class="fas fa-search fa-4x text-muted mb-3"></i>
                                <h4 class="text-muted">No se encontraron productos</h4>
                                <p class="text-muted">Intenta ajustar los filtros de búsqueda</p>
                                <a href="<?= base_url('catalogo') ?>" class="btn btn-primary">Ver todos los productos</a>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Paginación -->
                <?php if (isset($totalPages) && $totalPages > 1): ?>
                    <nav aria-label="Navegación de productos" class="mt-5">
                        <ul class="pagination justify-content-center">
                            <!-- Botón Anterior -->
                            <li class="page-item <?= ($page <= 1) ? 'disabled' : '' ?>">
                                <a class="page-link" href="<?= ($page > 1) ? base_url('catalogo?' . http_build_query(array_merge($filtros, ['page' => $page - 1]))) : '#' ?>" tabindex="-1">Anterior</a>
                            </li>
                            
                            <!-- Números de página -->
                            <?php 
                            $start = max(1, $page - 2);
                            $end = min($totalPages, $page + 2);
                            ?>
                            
                            <?php for ($i = $start; $i <= $end; $i++): ?>
                                <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                                    <a class="page-link" href="<?= base_url('catalogo?' . http_build_query(array_merge($filtros, ['page' => $i]))) ?>"><?= $i ?></a>
                                </li>
                            <?php endfor; ?>
                            
                            <!-- Botón Siguiente -->
                            <li class="page-item <?= ($page >= $totalPages) ? 'disabled' : '' ?>">
                                <a class="page-link" href="<?= ($page < $totalPages) ? base_url('catalogo?' . http_build_query(array_merge($filtros, ['page' => $page + 1]))) : '#' ?>">Siguiente</a>
                            </li>
                        </ul>
                    </nav>
                <?php endif; ?>
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
        // Funcionalidad para las categorías del sidebar
        document.querySelectorAll('.category-item').forEach(item => {
            item.addEventListener('click', function (e) {
                // No prevenir default para permitir navegación
                document.querySelectorAll('.category-item').forEach(cat => cat.classList.remove('active'));
                this.classList.add('active');
            });
        });

        // Funcionalidad para agregar al carrito con AJAX real
        document.querySelectorAll('.btn-agregar-carrito').forEach(button => {
            button.addEventListener('click', function () {
                const productoId = this.dataset.productoId;
                const productoNombre = this.dataset.productoNombre;
                const originalText = this.innerHTML;
                
                // Cambiar apariencia del botón
                this.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Agregando...';
                this.disabled = true;
                
                // Llamada AJAX real
                fetch('<?= base_url('catalogo/agregarCarrito') ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: `producto_id=${productoId}&cantidad=1`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Éxito
                        this.innerHTML = '<i class="fas fa-check me-1"></i>Agregado';
                        this.classList.remove('btn-primary');
                        this.classList.add('btn-success');
                        
                        // Actualizar contador del carrito si existe
                        const carritoCounter = document.querySelector('#carrito-counter');
                        if (carritoCounter && data.carrito) {
                            carritoCounter.textContent = data.carrito.total_items;
                        }
                        
                        // Volver al estado original después de 2 segundos
                        setTimeout(() => {
                            this.innerHTML = originalText;
                            this.classList.remove('btn-success');
                            this.classList.add('btn-primary');
                            this.disabled = false;
                        }, 2000);
                    } else {
                        // Error
                        this.innerHTML = '<i class="fas fa-times me-1"></i>Error';
                        this.classList.remove('btn-primary');
                        this.classList.add('btn-danger');
                        
                        // Mostrar mensaje de error
                        alert(data.message || 'Error al agregar producto al carrito');
                        
                        // Volver al estado original
                        setTimeout(() => {
                            this.innerHTML = originalText;
                            this.classList.remove('btn-danger');
                            this.classList.add('btn-primary');
                            this.disabled = false;
                        }, 2000);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    this.innerHTML = '<i class="fas fa-times me-1"></i>Error';
                    this.classList.remove('btn-primary');
                    this.classList.add('btn-danger');
                    
                    setTimeout(() => {
                        this.innerHTML = originalText;
                        this.classList.remove('btn-danger');
                        this.classList.add('btn-primary');
                        this.disabled = false;
                    }, 2000);
                });
            });
        });

        // Auto-submit del formulario de ordenar cuando cambia
        document.querySelector('select[name="ordenar"]').addEventListener('change', function() {
            this.form.submit();
        });

        // Lazy loading para imágenes (si necesitas mejor rendimiento)
        if ('IntersectionObserver' in window) {
            const imageObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        if (img.dataset.src) {
                            img.src = img.dataset.src;
                            img.classList.remove('img-loading');
                            imageObserver.unobserve(img);
                        }
                    }
                });
            });

            document.querySelectorAll('img[data-src]').forEach(img => {
                img.classList.add('img-loading');
                imageObserver.observe(img);
            });
        }
    </script>
</body>

</html>