<!-- Botón flotante del carrito con Bootstrap y badge -->
<a href="<?= site_url('carrito') ?>" id="carritoFlotante"
    class="btn btn-primary rounded-pill shadow position-relative d-flex justify-content-center align-items-center"
    title="Ir al carrito" 
    style="width: 55px; height: 55px; position: fixed !important; bottom: 38px !important; right: 38px !important; z-index: 1050;">

    <i id="iconoCarrito" class="fa-solid fa-cart-shopping fs-5"></i> 
</a>

<script>
// Función para actualizar la animación del carrito (compatible con ambos formatos)
function actualizarAnimacionCarrito() {
    const carrito = localStorage.getItem('carrito');
    const icono = document.getElementById('iconoCarrito');

    if (icono) { // Verificar que el icono existe
        if (carrito) {
            try {
                const productos = JSON.parse(carrito);
                // Verificar si es array o objeto y si tiene productos
                const tieneProductos = Array.isArray(productos) 
                    ? productos.length > 0 
                    : Object.keys(productos).length > 0;
                    
                if (tieneProductos) {
                    icono.classList.add('animate__animated', 'animate__wobble', 'animate__infinite');
                } else {
                    icono.classList.remove('animate__animated', 'animate__wobble', 'animate__infinite');
                }
            } catch (e) {
                console.error('Error al parsear localStorage.carrito:', e);
                icono.classList.remove('animate__animated', 'animate__wobble', 'animate__infinite');
            }
        } else {
            icono.classList.remove('animate__animated', 'animate__wobble', 'animate__infinite');
        }
    }
}

// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function () {
    actualizarAnimacionCarrito();
});

// Escuchar cambios desde otras pestañas
window.addEventListener('storage', function (event) {
    if (event.key === 'carrito') {
        actualizarAnimacionCarrito();
    }
});

// Función global para que otras páginas puedan actualizar la animación
window.actualizarAnimacionCarrito = actualizarAnimacionCarrito;
</script>