<!DOCTYPE html>
<html>
<head>
    <!-- la cabecera de todas las paginas -->
    <?= view('layout/base_head') ?>
    <style>
        .checkout-container {
            max-width: 900px;
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

        .carrito-vacio {
            text-align: center;
            padding: 60px 20px;
        }

        .factura-container {
            background: #fff;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            margin: 20px 0;
            padding: 0;
        }

        .factura-header {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            color: white;
            padding: 30px;
            position: relative;
            overflow: hidden;
        }

        .factura-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20px;
            width: 100px;
            height: 200%;
            background: rgba(255,255,255,0.1);
            transform: rotate(15deg);
        }

        .empresa-info {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        .empresa-logo {
            width: 60px;
            height: 60px;
            margin-right: 20px;
            background: white;
            border-radius: 10px;
            padding: 8px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }

        .empresa-logo img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .empresa-datos h1 {
            margin: 0;
            font-size: 2.2rem;
            font-weight: bold;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }

        .empresa-datos p {
            margin: 5px 0 0 0;
            font-size: 1.1rem;
            opacity: 0.9;
        }

        .factura-numero {
            position: absolute;
            top: 30px;
            right: 30px;
            text-align: right;
        }

        .factura-numero h3 {
            margin: 0;
            font-size: 1.8rem;
            font-weight: bold;
        }

        .factura-numero p {
            margin: 5px 0 0 0;
            font-size: 1rem;
            opacity: 0.9;
        }

        .cliente-info {
            padding: 30px;
            background: #f8f9fa;
            border-bottom: 3px solid #007bff;
        }

        .cliente-info h4 {
            color: #007bff;
            margin-bottom: 15px;
            font-weight: bold;
            display: flex;
            align-items: center;
        }

        .cliente-info h4 i {
            margin-right: 10px;
        }

        .info-row {
            display: flex;
            margin-bottom: 8px;
        }

        .info-label {
            font-weight: bold;
            color: #495057;
            min-width: 120px;
        }

        .info-value {
            color: #212529;
        }

        .productos-table {
            margin: 0;
        }

        .productos-table thead {
            background: linear-gradient(135deg, #343a40 0%, #495057 100%);
            color: white;
        }

        .productos-table thead th {
            border: none;
            padding: 15px 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-size: 0.9rem;
        }

        .productos-table tbody tr {
            border-bottom: 1px solid #dee2e6;
            transition: background-color 0.2s ease;
        }

        .productos-table tbody tr:hover {
            background-color: #f8f9fa;
        }

        .productos-table tbody td {
            padding: 15px 12px;
            vertical-align: middle;
        }

        .producto-nombre {
            font-weight: 600;
            color: #212529;
        }

        .cantidad-badge {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            color: white;
            padding: 6px 12px;
            border-radius: 20px;
            font-weight: bold;
            display: inline-block;
            min-width: 40px;
            text-align: center;
        }

        .precio-texto {
            font-weight: 600;
            color: #28a745;
        }

        .subtotal-texto {
            font-weight: bold;
            color: #212529;
            font-size: 1.1rem;
        }

        .total-section {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            padding: 25px 30px;
            margin: 0;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .total-label {
            font-size: 1.3rem;
            font-weight: 600;
        }

        .total-amount {
            font-size: 2rem;
            font-weight: bold;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.2);
        }

        .error-message {
            text-align: center;
            padding: 60px 20px;
        }

        .acciones-container {
            padding: 30px;
            text-align: center;
            background: #f8f9fa;
        }

        .btn-accion {
            margin: 0 10px;
            padding: 12px 30px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-radius: 25px;
            transition: all 0.3s ease;
        }

        .btn-accion:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }

        @media print {
            .acciones-container, .navbar, .footer {
                display: none !important;
            }
            
            .factura-container {
                box-shadow: none;
                margin: 0;
            }
        }

        @media (max-width: 768px) {
            .factura-header {
                padding: 20px;
            }
            
            .empresa-info {
                flex-direction: column;
                text-align: center;
            }
            
            .empresa-logo {
                margin: 0 0 15px 0;
            }
            
            .factura-numero {
                position: static;
                text-align: center;
                margin-top: 20px;
            }
            
            .cliente-info {
                padding: 20px;
            }
            
            .info-row {
                flex-direction: column;
            }
            
            .info-label {
                min-width: auto;
                margin-bottom: 2px;
            }
            
            .productos-table {
                font-size: 0.9rem;
            }
            
            .total-section {
                padding: 20px;
            }
            
            .acciones-container {
                padding: 20px;
            }
            
            .btn-accion {
                display: block;
                margin: 10px 0 !important;
                width: 100%;
            }
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <?= view('layout/navbar') ?>

    <!-- Contenido específico de la página -->
    <div class="container-fluid my-4">
        <div class="checkout-container">
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
                
                <a href="<?= base_url('catalogo') ?>" class="btn btn-primary btn-accion">
                    <i class="fa-solid fa-arrow-left"></i>
                    Ir al Catálogo
                </a>
            </div>

            <!-- Factura -->
            <div id="compra-exitosa" class="factura-container" style="display: none;">
                <!-- Cabecera de la Factura -->
                <div class="factura-header">
                    <div class="empresa-info">
                        <div class="empresa-logo">
                            <img src="<?= base_url('public/assets/img/icon.png') ?>" alt="Insumos FAT Logo">
                        </div>
                        <div class="empresa-datos">
                            <h1>Insumos FAT</h1>
                            <p>Suministros y Equipamiento Industrial</p>
                        </div>
                    </div>
                    
                    <div class="factura-numero">
                        <h3>FACTURA</h3>
                        <p>N° <span id="numero-factura">-</span></p>
                        <p id="fecha-compra">-</p>
                    </div>
                </div>

                <!-- Información del Cliente -->
                <div class="cliente-info">
                    <h4><i class="fas fa-user"></i> Información del Cliente</h4>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-row">
                                <span class="info-label">Nombre:</span>
                                <span class="info-value" id="cliente-nombre">-</span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Email:</span>
                                <span class="info-value" id="cliente-email">-</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-row">
                                <span class="info-label">DNI:</span>
                                <span class="info-value" id="cliente-dni">-</span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Dirección:</span>
                                <span class="info-value" id="cliente-direccion">-</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tabla de Productos -->
                <div class="table-responsive">
                    <table class="table productos-table mb-0">
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th width="100" class="text-center">Cantidad</th>
                                <th width="120" class="text-end">Precio Unit.</th>
                                <th width="120" class="text-end">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody id="productos-comprados">
                            <!-- Los productos se cargarán aquí -->
                        </tbody>
                    </table>
                </div>

                <!-- Total -->
                <div class="total-section">
                    <div class="total-row">
                        <span class="total-label">
                            <i class="fas fa-receipt me-2"></i>
                            TOTAL A PAGAR:
                        </span>
                        <span class="total-amount" id="total-pagado">$0.00</span>
                    </div>
                </div>

                <!-- Acciones -->
                <div class="acciones-container">
                    <button onclick="window.print()" class="btn btn-secondary btn-accion">
                        <i class="fas fa-print me-2"></i>
                        Imprimir Factura
                    </button>
                    <a href="<?= base_url('catalogo') ?>" class="btn btn-primary btn-accion">
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
                    <button class="btn btn-danger btn-accion" onclick="reintentar()">
                        <i class="fas fa-redo me-2"></i>
                        Reintentar
                    </button>
                    <a href="<?= base_url('carrito') ?>" class="btn btn-outline-secondary btn-accion">
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
        function mostrarExito(facturaId, total, fecha, productos, cliente) {
            document.getElementById('checkout-loading').style.display = 'none';
            document.getElementById('carrito-vacio').style.display = 'none';
            document.getElementById('compra-exitosa').style.display = 'block';
            document.getElementById('compra-error').style.display = 'none';
            
            // Actualizar información de la factura
            document.getElementById('numero-factura').textContent = facturaId.toString().padStart(6, '0');
            document.getElementById('total-pagado').textContent = '$' + total.toFixed(2);
            document.getElementById('fecha-compra').textContent = fecha;
            
            // Actualizar información del cliente
            if (cliente) {
                document.getElementById('cliente-nombre').textContent = `${cliente.nombre} ${cliente.apellido}`;
                document.getElementById('cliente-email').textContent = cliente.email;
                document.getElementById('cliente-dni').textContent = cliente.dni;
                document.getElementById('cliente-direccion').textContent = cliente.direccion;
            }
            
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
                    <tr>
                        <td>
                            <div class="producto-nombre">${producto.nombre}</div>
                        </td>
                        <td class="text-center">
                            <span class="cantidad-badge">${producto.cantidad}</span>
                        </td>
                        <td class="text-end">
                            <span class="precio-texto">$${producto.precio_unitario.toFixed(2)}</span>
                        </td>
                        <td class="text-end">
                            <span class="subtotal-texto">$${producto.subtotal.toFixed(2)}</span>
                        </td>
                    </tr>
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
                    mostrarExito(data.factura_id, data.total, data.fecha, data.productos, data.cliente);
                    
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