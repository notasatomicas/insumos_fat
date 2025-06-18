<!DOCTYPE html>
<html>
<head>
    <!-- la cabecera de todas las paginas -->
    <?= view('layout/base_head') ?>
    <style>
        .checkout-container {
            max-width: 800px;
            margin: 0 auto;
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

        .success-message {
            text-align: center;
            padding: 60px 20px;
        }

        .error-message {
            text-align: center;
            padding: 60px 20px;
        }

        .carrito-vacio {
            text-align: center;
            padding: 60px 20px;
        }

        .producto-comprado {
            border-bottom: 1px solid #e9ecef;
            padding: 15px 0;
        }

        .producto-comprado:last-child {
            border-bottom: none;
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <?= view('layout/navbar') ?>

    <!-- Contenido específico de la página -->
    <div class="container my-4">
        <div class="checkout-container">
            <div class="row">
                <div class="col-12">
                    <h2 class="mb-4">
                        <i class="fas fa-credit-card me-2"></i>
                        Checkout - Finalizar Compra
                    </h2>
                </div>
            </div>

            <!-- Loading -->
            <div id="checkout-loading" class="loading-spinner">
                <i class="fas fa-spinner"></i>
                <h4 class="text-muted mt-3">Procesando compra...</h4>
            </div>

            <!-- Carrito Vacío -->
            <div id="carrito-vacio" class="carrito-vacio" style="display: none;">
                <i class="fas fa-shopping-cart" style="font-size: 4rem; color: #6c757d; margin-bottom: 20px;"></i>
                <h4 class="text-muted">Tu carrito está vacío</h4>
                <p class="text-muted">No hay productos para procesar</p>
                
                <a href="<?= base_url('catalogo') ?>" class="btn btn-primary">
                    <i class="fa-solid fa-arrow-left"></i>
                    Ir al Catálogo
                </a>
            </div>

            <!-- Mensaje de éxito -->
            <div id="compra-exitosa" class="success-message" style="display: none;">
                <i class="fas fa-check-circle" style="font-size: 4rem; margin-bottom: 20px; color: #28a745;"></i>
                <h3 class="text-success">¡Compra realizada correctamente!</h3>
                <p class="text-muted mb-4">Tu pedido ha sido procesado exitosamente</p>
                
                <div class="card mb-4">
                    <div class="card-body">
                        <h5>Detalles de la compra:</h5>
                        <p><strong>Número de factura:</strong> <span id="numero-factura">-</span></p>
                        <p><strong>Total pagado:</strong> <span id="total-pagado" class="text-success">$0.00</span></p>
                        <p><strong>Fecha:</strong> <span id="fecha-compra">-</span></p>
                        
                        <!-- Lista de productos comprados -->
                        <div class="mt-4">
                            <h6>Productos comprados:</h6>
                            <div id="productos-comprados" class="mt-3">
                                <!-- Los productos se cargarán aquí -->
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-2 justify-content-center">
                    <a href="<?= base_url('catalogo') ?>" class="btn btn-primary">
                        <i class="fas fa-shopping-bag me-2"></i>
                        Seguir Comprando
                    </a>
                </div>
            </div>

            <!-- Mensaje de error -->
            <div id="compra-error" class="error-message" style="display: none;">
                <i class="fas fa-exclamation-triangle" style="font-size: 4rem; color: #dc3545; margin-bottom: 20px;"></i>
                <h3 class="text-danger">Error al procesar la compra</h3>
                <p class="text-muted mb-4" id="mensaje-error">Ocurrió un problema al procesar tu pedido</p>
                
                <div class="d-flex gap-2 justify-content-center">
                    <button class="btn btn-danger" onclick="reintentar()">
                        <i class="fas fa-redo me-2"></i>
                        Reintentar
                    </button>
                    <a href="<?= base_url('carrito') ?>" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>
                        Volver al Carrito
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <?= view('layout/footer') ?>

    <!-- Scripts generales que se usan en todas las paginas -->
    <?= view('layout/base_scripts') ?>

    <!-- Bootstrap JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>

    <!-- Scripts específicos del checkout -->
    <script>
        // Funciones para manejo del carrito en localStorage
        const CarritoLocalStorage = {
            obtenerCarrito: function() {
                const carrito = localStorage.getItem('carrito');
                return carrito ? JSON.parse(carrito) : {};
            },
            vaciarCarrito: function() {
                localStorage.removeItem('carrito');
            },
            estaVacio: function() {
                const carrito = this.obtenerCarrito();
                return Object.keys(carrito).length === 0;
            }
        };

        // Variables globales
        let carritoData = {};

        // Función para mostrar loading
        function mostrarLoading() {
            document.getElementById('checkout-loading').style.display = 'block';
            document.getElementById('carrito-vacio').style.display = 'none';
            document.getElementById('compra-exitosa').style.display = 'none';
            document.getElementById('compra-error').style.display = 'none';
        }

        // Función para mostrar carrito vacío
        function mostrarCarritoVacio() {
            document.getElementById('checkout-loading').style.display = 'none';
            document.getElementById('carrito-vacio').style.display = 'block';
            document.getElementById('compra-exitosa').style.display = 'none';
            document.getElementById('compra-error').style.display = 'none';
        }

        // Función para mostrar éxito
        function mostrarExito(facturaId, total, fecha, productos) {
            document.getElementById('checkout-loading').style.display = 'none';
            document.getElementById('carrito-vacio').style.display = 'none';
            document.getElementById('compra-exitosa').style.display = 'block';
            document.getElementById('compra-error').style.display = 'none';
            
            // Actualizar información de la compra
            document.getElementById('numero-factura').textContent = facturaId;
            document.getElementById('total-pagado').textContent = '$' + total.toFixed(2);
            document.getElementById('fecha-compra').textContent = fecha;
            
            // Mostrar productos comprados
            mostrarProductosComprados(productos);
        }

        // Función para mostrar error
        function mostrarError(mensaje) {
            document.getElementById('checkout-loading').style.display = 'none';
            document.getElementById('carrito-vacio').style.display = 'none';
            document.getElementById('compra-exitosa').style.display = 'none';
            document.getElementById('compra-error').style.display = 'block';
            
            document.getElementById('mensaje-error').textContent = mensaje;
        }

        // Función para mostrar los productos comprados
        function mostrarProductosComprados(productos) {
            const productosContainer = document.getElementById('productos-comprados');
            let html = '';

            productos.forEach(producto => {
                html += `
                    <div class="producto-comprado">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <h6 class="mb-1">${producto.nombre}</h6>
                                <small class="text-muted">Precio unitario: $${producto.precio_unitario.toFixed(2)}</small>
                            </div>
                            <div class="col-md-2 text-center">
                                <span class="badge bg-secondary">${producto.cantidad}</span>
                            </div>
                            <div class="col-md-4 text-end">
                                <strong>$${producto.subtotal.toFixed(2)}</strong>
                            </div>
                        </div>
                    </div>
                `;
            });

            productosContainer.innerHTML = html;
        }

        // Función para procesar la compra directamente
        async function procesarCompraDirecta() {
            mostrarLoading();
            
            try {
                carritoData = CarritoLocalStorage.obtenerCarrito();
                
                if (CarritoLocalStorage.estaVacio()) {
                    mostrarCarritoVacio();
                    return;
                }

                const response = await fetch('<?= base_url('checkout/procesarCompraDirecta') ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({ carrito: carritoData })
                });

                if (!response.ok) {
                    throw new Error('Error en la respuesta del servidor');
                }

                const data = await response.json();
                
                if (data.success) {
                    // Vaciar carrito del localStorage
                    CarritoLocalStorage.vaciarCarrito();
                    
                    // Mostrar mensaje de éxito con todos los detalles
                    mostrarExito(data.factura_id, data.total, data.fecha, data.productos);
                    
                    // Opcional: disparar evento para actualizar el contador del navbar
                    window.dispatchEvent(new Event('storage'));
                } else {
                    mostrarError(data.message || 'Error al procesar la compra');
                }
                
            } catch (error) {
                console.error('Error al procesar compra:', error);
                mostrarError('Error de conexión. Inténtalo de nuevo.');
            }
        }

        // Función para reintentar
        function reintentar() {
            procesarCompraDirecta();
        }

        // Event listeners
        document.addEventListener('DOMContentLoaded', function() {
            // Procesar compra directamente al cargar la página
            procesarCompraDirecta();
        });
    </script>
</body>
</html>