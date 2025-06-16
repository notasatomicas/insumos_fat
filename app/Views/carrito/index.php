<!DOCTYPE html>
<html>
<head>
    <!-- la cabecera de todas las paginas -->
    <?= view('layout/base_head') ?>
    <style>
        .product-img-carrito {
            height: 80px;
            width: 80px;
            object-fit: cover;
        }

        .product-img-container-carrito {
            position: relative;
            overflow: hidden;
            height: 80px;
            width: 80px;
            background-color: #f8f9fa;
            border-radius: 8px;
        }

        .no-image-carrito {
            background-color: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #6c757d;
            font-size: 0.7rem;
            height: 100%;
            width: 100%;
            flex-direction: column;
            border-radius: 8px;
        }

        .no-image-carrito i {
            font-size: 1.5rem;
            margin-bottom: 5px;
            opacity: 0.5;
        }

        .image-error-carrito {
            background-color: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #6c757d;
            font-size: 0.7rem;
            height: 80px;
            width: 80px;
            flex-direction: column;
            border-radius: 8px;
        }

        .image-error-carrito i {
            font-size: 1.2rem;
            margin-bottom: 3px;
            opacity: 0.4;
        }

        .cantidad-input {
            width: 70px;
        }

        .carrito-vacio {
            text-align: center;
            padding: 60px 20px;
        }

        .loading-spinner {
            text-align: center;
            padding: 60px 20px;
        }

        .loading-spinner i {
            font-size: 3rem;
            color: #007bff;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <?= view('layout/navbar') ?>

    <?= view('layout/carrito_button') ?>

    <!-- Contenido específico de la página -->
    <div class="container my-5">
        <div class="row">
            <div class="col-12">
                <h2 class="mb-4">
                    <i class="fas fa-shopping-cart me-2"></i>
                    Mi Carrito de Compras
                </h2>
            </div>
        </div>

        <!-- Loading -->
        <div id="carrito-loading" class="loading-spinner">
            <i class="fas fa-spinner"></i>
            <h4 class="text-muted mt-3">Cargando carrito...</h4>
        </div>

        <!-- Carrito Vacío -->
        <div id="carrito-vacio" class="carrito-vacio" style="display: none;">
            <i class="fas fa-shopping-cart" style="font-size: 4rem; color: #6c757d; margin-bottom: 20px;"></i>
            <h4 class="text-muted">Tu carrito está vacío</h4>
            <p class="text-muted">¡Agrega algunos productos para comenzar a comprar!</p>
            
            <a href="<?= base_url('catalogo') ?>" class="btn btn-primary">
                <i class="fa-solid fa-arrow-left"></i>
                Ir al Catálogo
            </a>
        </div>

        <!-- Contenido del Carrito -->
        <div id="carrito-contenido" class="row" style="display: none;">
            <!-- Productos en el carrito -->
            <div class="col-lg-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            Productos en tu carrito 
                            (<span id="contador-productos">0</span> artículos)
                        </h5>
                    </div>
                    <div class="card-body p-0" id="lista-productos">
                        <!-- Los productos se cargarán aquí dinámicamente -->
                    </div>
                </div>

                <!-- Botones de acción -->
                <div class="row mt-3">
                    <div class="col-6">
                        <a href="<?= base_url('catalogo') ?>" class="btn btn-outline-primary">
                            <i class="fas fa-arrow-left me-2"></i>
                            Seguir Comprando
                        </a>
                    </div>
                    <div class="col-6 text-end">
                        <button class="btn btn-danger" id="btn-vaciar-carrito">
                            <i class="fas fa-trash me-2"></i>
                            Vaciar carrito
                        </button>
                    </div>
                </div>
            </div>

            <!-- Resumen del pedido -->
            <div class="col-lg-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">Resumen del Pedido</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-3">
                            <strong>Total (<span id="resumen-cantidad">0</span> productos):</strong>
                            <strong class="text-success fs-5" id="resumen-total">$0.00</strong>
                        </div>

                        <!-- Botón de checkout -->
                        <button class="btn btn-success btn-lg w-100 mb-2" id="btn-proceder-pago">
                            <i class="fas fa-credit-card me-2"></i>
                            Proceder al Pago
                        </button>

                        <hr>
                        
                        <!-- Garantías -->
                        <div class="text-center">
                            <small class="text-muted">
                                <i class="fas fa-shield-alt text-success me-1"></i>
                                Compra 100% segura
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Error al cargar -->
        <div id="carrito-error" class="text-center py-5" style="display: none;">
            <i class="fas fa-exclamation-triangle fa-4x text-warning mb-3"></i>
            <h4 class="text-muted">Error al cargar el carrito</h4>
            <p class="text-muted">Hubo un problema al obtener los datos de los productos</p>
            <button class="btn btn-primary" onclick="cargarProductosCarrito()">
                <i class="fas fa-redo me-2"></i>
                Reintentar
            </button>
        </div>
    </div>

    <!-- Footer -->
    <?= view('layout/footer') ?>

    <!-- Scripts generales que se usan en todas las paginas -->
    <?= view('layout/base_scripts') ?>

    <!-- Bootstrap JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>

    <!-- Scripts específicos del carrito -->
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

            // Actualizar cantidad de un producto
            actualizarCantidad: function(productoId, nuevaCantidad) {
                const carrito = this.obtenerCarrito();
                const id = productoId.toString();
                
                if (carrito[id] && nuevaCantidad > 0) {
                    carrito[id].cantidad = parseInt(nuevaCantidad);
                    this.guardarCarrito(carrito);
                    return true;
                }
                return false;
            },

            // Eliminar producto del carrito
            eliminarProducto: function(productoId) {
                const carrito = this.obtenerCarrito();
                const id = productoId.toString();
                
                if (carrito[id]) {
                    delete carrito[id];
                    this.guardarCarrito(carrito);
                    return true;
                }
                return false;
            },

            // Vaciar carrito completo
            vaciarCarrito: function() {
                localStorage.removeItem('carrito');
            },

            // Verificar si el carrito está vacío
            estaVacio: function() {
                const carrito = this.obtenerCarrito();
                return Object.keys(carrito).length === 0;
            },

            // Obtener IDs de productos
            obtenerIdsProductos: function() {
                const carrito = this.obtenerCarrito();
                return Object.keys(carrito);
            }
        };

        // Variables globales
        let productosCarrito = [];

        // Función para mostrar loading
        function mostrarLoading() {
            document.getElementById('carrito-loading').style.display = 'block';
            document.getElementById('carrito-vacio').style.display = 'none';
            document.getElementById('carrito-contenido').style.display = 'none';
            document.getElementById('carrito-error').style.display = 'none';
        }

        // Función para mostrar carrito vacío
        function mostrarCarritoVacio() {
            document.getElementById('carrito-loading').style.display = 'none';
            document.getElementById('carrito-vacio').style.display = 'block';
            document.getElementById('carrito-contenido').style.display = 'none';
            document.getElementById('carrito-error').style.display = 'none';
        }

        // Función para mostrar contenido del carrito
        function mostrarCarritoContenido() {
            document.getElementById('carrito-loading').style.display = 'none';
            document.getElementById('carrito-vacio').style.display = 'none';
            document.getElementById('carrito-contenido').style.display = '';//en este caso elimina simplemente el none
            document.getElementById('carrito-error').style.display = 'none';
        }

        // Función para mostrar error
        function mostrarError() {
            document.getElementById('carrito-loading').style.display = 'none';
            document.getElementById('carrito-vacio').style.display = 'none';
            document.getElementById('carrito-contenido').style.display = 'none';
            document.getElementById('carrito-error').style.display = 'block';
        }

        // Función para obtener datos de productos desde el servidor
        async function obtenerDatosProductos(idsProductos) {
            try {
                const response = await fetch('<?= base_url('api/productos/obtener-por-ids') ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({ ids: idsProductos })
                });

                if (!response.ok) {
                    throw new Error('Error en la respuesta del servidor');
                }

                const data = await response.json();
                
                if (data.success) {
                    return data.productos;
                } else {
                    throw new Error(data.message || 'Error al obtener productos');
                }
            } catch (error) {
                console.error('Error al obtener datos de productos:', error);
                throw error;
            }
        }

        // Función para cargar productos del carrito
        async function cargarProductosCarrito() {
            mostrarLoading();
            
            try {
                const carrito = CarritoLocalStorage.obtenerCarrito();
                
                if (CarritoLocalStorage.estaVacio()) {
                    mostrarCarritoVacio();
                    return;
                }

                // Obtener IDs de productos
                const idsProductos = CarritoLocalStorage.obtenerIdsProductos();
                
                // Obtener datos completos de productos desde el servidor
                const productosCompletos = await obtenerDatosProductos(idsProductos);
                
                // Combinar datos del carrito con datos completos del producto
                productosCarrito = productosCompletos.map(producto => {
                    const itemCarrito = carrito[producto.id_producto.toString()];
                    return {
                        id: producto.id_producto.toString(),
                        nombre: producto.nombre_prod,
                        precio: parseFloat(producto.precio),
                        cantidad: parseInt(itemCarrito.cantidad),
                        stock: parseInt(producto.stock),
                        imagen: producto.imagen_url_completa || null,
                        subtotal: parseFloat(producto.precio) * parseInt(itemCarrito.cantidad)
                    };
                });

                if (productosCarrito.length === 0) {
                    mostrarCarritoVacio();
                    return;
                }

                renderizarProductos();
                actualizarResumen();
                mostrarCarritoContenido();
                
            } catch (error) {
                console.error('Error al cargar productos del carrito:', error);
                mostrarError();
            }
        }

        // Función para renderizar productos
        function renderizarProductos() {
            const listaProductos = document.getElementById('lista-productos');
            const contadorProductos = document.getElementById('contador-productos');
            
            contadorProductos.textContent = productosCarrito.length;
            
            listaProductos.innerHTML = '';

            productosCarrito.forEach((producto, index) => {
                const productoElement = document.createElement('div');
                productoElement.className = `row align-items-center p-3 ${index < productosCarrito.length - 1 ? 'border-bottom' : ''}`;

                productoElement.innerHTML = `
                    <div class="col-md-2">
                        <div class="product-img-container-carrito">
                            ${producto.imagen ? 
                                `<img src="${producto.imagen}" 
                                     alt="${producto.nombre}" 
                                     class="product-img-carrito"
                                     onerror="this.parentElement.innerHTML='<div class=&quot;image-error-carrito&quot;><i class=&quot;fas fa-exclamation-triangle&quot;></i><small>Error</small></div>'">` :
                                `<div class="no-image-carrito">
                                    <i class="fas fa-image"></i>
                                    <small>Sin imagen</small>
                                 </div>`
                            }
                        </div>
                    </div>
                    <div class="col-md-4">
                        <h6 class="mb-1">${producto.nombre}</h6>
                        <small class="text-muted">Precio unitario: $${producto.precio.toFixed(2)}</small>
                        ${producto.stock <= 5 && producto.stock > 0 ? 
                            `<br><small class="text-warning"><i class="fas fa-exclamation-triangle me-1"></i>¡Pocos en stock! (${producto.stock})</small>` : 
                            producto.stock === 0 ? `<br><small class="text-danger"><i class="fas fa-times me-1"></i>Sin stock</small>` : ''
                        }
                    </div>
                    <div class="col-md-2">
                        <div class="input-group input-group-sm">
                            <button class="btn btn-outline-secondary btn-decrementar" 
                                    data-producto-id="${producto.id}" 
                                    ${producto.cantidad <= 1 || producto.stock === 0 ? 'disabled' : ''}>-</button>
                            <input type="number" 
                                   class="form-control text-center cantidad-input" 
                                   value="${producto.cantidad}" 
                                   min="1"
                                   max="${producto.stock}"
                                   data-producto-id="${producto.id}"
                                   ${producto.stock === 0 ? 'disabled' : ''}>
                            <button class="btn btn-outline-secondary btn-incrementar" 
                                    data-producto-id="${producto.id}"
                                    ${producto.cantidad >= producto.stock || producto.stock === 0 ? 'disabled' : ''}>+</button>
                        </div>
                    </div>
                    <div class="col-md-2 text-center">
                        <strong>$${producto.subtotal.toFixed(2)}</strong>
                    </div>
                    <div class="col-md-2 text-end">
                        <button class="btn btn-sm btn-outline-danger btn-eliminar" 
                                data-producto-id="${producto.id}"
                                data-producto-nombre="${producto.nombre}">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                `;

                listaProductos.appendChild(productoElement);
            });

            // Agregar event listeners
            agregarEventListeners();
        }

        // Función para actualizar resumen
        function actualizarResumen() {
            const total = productosCarrito.reduce((sum, producto) => sum + producto.subtotal, 0);
            const cantidadTotal = productosCarrito.reduce((sum, producto) => sum + producto.cantidad, 0);

            document.getElementById('resumen-total').textContent = `$${total.toFixed(2)}`;
            document.getElementById('resumen-cantidad').textContent = cantidadTotal;

            // Habilitar botón de pago solo si hay productos
            const btnPago = document.getElementById('btn-proceder-pago');
            btnPago.disabled = productosCarrito.length === 0;
        }

        // Función para agregar event listeners a los botones
        function agregarEventListeners() {
            // Botones decrementar
            document.querySelectorAll('.btn-decrementar').forEach(btn => {
                btn.addEventListener('click', function() {
                    const productoId = this.dataset.productoId;
                    const input = document.querySelector(`input[data-producto-id="${productoId}"]`);
                    const nuevaCantidad = parseInt(input.value) - 1;
                    
                    if (nuevaCantidad >= 1) {
                        actualizarCantidadProducto(productoId, nuevaCantidad);
                    }
                });
            });

            // Botones incrementar
            document.querySelectorAll('.btn-incrementar').forEach(btn => {
                btn.addEventListener('click', function() {
                    const productoId = this.dataset.productoId;
                    const input = document.querySelector(`input[data-producto-id="${productoId}"]`);
                    const producto = productosCarrito.find(p => p.id === productoId);
                    const nuevaCantidad = parseInt(input.value) + 1;
                    
                    if (nuevaCantidad <= producto.stock) {
                        actualizarCantidadProducto(productoId, nuevaCantidad);
                    } else {
                        mostrarMensaje(`Solo hay ${producto.stock} unidades disponibles`, 'warning');
                    }
                });
            });

            // Inputs de cantidad
            document.querySelectorAll('.cantidad-input').forEach(input => {
                input.addEventListener('change', function() {
                    const productoId = this.dataset.productoId;
                    const producto = productosCarrito.find(p => p.id === productoId);
                    const nuevaCantidad = parseInt(this.value);
                    
                    if (nuevaCantidad >= 1 && nuevaCantidad <= producto.stock) {
                        actualizarCantidadProducto(productoId, nuevaCantidad);
                    } else if (nuevaCantidad > producto.stock) {
                        mostrarMensaje(`Solo hay ${producto.stock} unidades disponibles`, 'warning');
                        // Recargar el carrito para restaurar el valor correcto
                        cargarProductosCarrito();
                    } else {
                        // Si el valor es inválido, recargar el carrito
                        cargarProductosCarrito();
                    }
                });
            });

            // Botones eliminar
            document.querySelectorAll('.btn-eliminar').forEach(btn => {
                btn.addEventListener('click', function() {
                    const productoId = this.dataset.productoId;
                    const productoNombre = this.dataset.productoNombre;
                    
                    if (confirm(`¿Estás seguro de que quieres eliminar "${productoNombre}" del carrito?`)) {
                        eliminarProducto(productoId);
                    }
                });
            });
        }

        // Función para actualizar cantidad de producto
        function actualizarCantidadProducto(productoId, nuevaCantidad) {
            if (CarritoLocalStorage.actualizarCantidad(productoId, nuevaCantidad)) {
                // Actualizar el producto en el array local
                const producto = productosCarrito.find(p => p.id === productoId);
                if (producto) {
                    producto.cantidad = nuevaCantidad;
                    producto.subtotal = producto.precio * nuevaCantidad;
                    
                    // Re-renderizar solo si es necesario o actualizar directamente
                    renderizarProductos();
                    actualizarResumen();
                    mostrarMensaje('Cantidad actualizada', 'success');
                }
            } else {
                mostrarError('Error al actualizar la cantidad');
            }
        }

        // Función para eliminar producto
        function eliminarProducto(productoId) {
            if (CarritoLocalStorage.eliminarProducto(productoId)) {
                cargarProductosCarrito();
                mostrarMensaje('Producto eliminado del carrito', 'success');
            } else {
                mostrarMensaje('Error al eliminar el producto', 'error');
            }
        }

        // Función para vaciar carrito
        function vaciarCarrito() {
            if (confirm('¿Estás seguro de que quieres vaciar completamente el carrito?')) {
                CarritoLocalStorage.vaciarCarrito();
                mostrarCarritoVacio();
                mostrarMensaje('Carrito vaciado correctamente', 'success');
            }
        }

        // Función para mostrar mensajes
        function mostrarMensaje(mensaje, tipo = 'info') {
            const iconos = {
                'success': 'check-circle text-success',
                'error': 'exclamation-triangle text-danger',
                'warning': 'exclamation-triangle text-warning',
                'info': 'info-circle text-info'
            };
            
            const toast = document.createElement('div');
            toast.className = 'position-fixed top-0 end-0 p-3';
            toast.style.zIndex = '9999';
            toast.innerHTML = `
                <div class="toast show" role="alert">
                    <div class="toast-header">
                        <i class="fas fa-${iconos[tipo] || iconos.info} me-2"></i>
                        <strong class="me-auto">Carrito</strong>
                        <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
                    </div>
                    <div class="toast-body">
                        ${mensaje}
                    </div>
                </div>
            `;
            document.body.appendChild(toast);
            
            setTimeout(() => {
                if (toast.parentNode) {
                    toast.parentNode.removeChild(toast);
                }
            }, 3000);
        }

        // Event listeners principales
        document.addEventListener('DOMContentLoaded', function() {
            // Cargar productos al iniciar
            cargarProductosCarrito();
            
            // Botón vaciar carrito
            document.getElementById('btn-vaciar-carrito').addEventListener('click', vaciarCarrito);
            
            // Botón proceder al pago
            document.getElementById('btn-proceder-pago').addEventListener('click', function() {
                if (!this.disabled) {
                    // Aquí puedes redirigir al checkout o mostrar formulario
                    window.location.href = '<?= base_url('checkout') ?>';
                }
            });
        });

        // Actualizar carrito cuando cambie localStorage (por ejemplo, desde otra pestaña)
        window.addEventListener('storage', function(e) {
            if (e.key === 'carrito') {
                cargarProductosCarrito();
            }
        });
    </script>
</body>
</html>