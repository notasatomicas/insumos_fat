<!DOCTYPE html>
<html>

<head>
    <!-- la cabecera de todas las paginas -->
    <?= view('layout/base_head') ?>

    <!-- Tanto el estilo de leaflet como el script deben ir juntos, no se por que pero cuando
         se lo saca de ese lugar comienza a dar fallos inesperados    
    -->
    <link rel="stylesheet" href="<?= base_url('public/assets/leaflet/leaflet.css') ?>">
    <script src="<?= base_url('public/assets/leaflet/leaflet.js') ?>"></script>

    <!-- Cargo estilos propios de contacto -->
    <link rel="stylesheet" href="<?= base_url('public/assets/css/contacto.css') ?>">

    <?= view('adicional/modal_development_estilos.html') ?>
</head>

<body>

    <!-- Navbar -->
    <?= view('layout/navbar') ?>
    <?= view('adicional/modal_development.html') ?>

    <!-- Contenido específico de la página -->
    <?= view('contenido/contacto') ?>

    <!-- Footer -->
    <?= view('layout/footer') ?>

    <script>
    (() => {
        "use strict";
        const forms = document.querySelectorAll(".needs-validation");
        Array.from(forms).forEach((form) => {
            form.addEventListener(
                "submit",
                (event) => {
                    if (!form.checkValidity()) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add("was-validated");
                },
                false
            );
        });
    })();
    </script>

    <script>
    document
        .getElementById("btnEnviar")
        .addEventListener("click", function(event) {
            event.preventDefault(); // Prevenir el comportamiento por defecto de submit

            const form = document.querySelector(".needs-validation");

            // Verificar si el formulario es válido
            if (!form.checkValidity()) {
                form.classList.add("was-validated");
                return; // No continuar si el formulario no es válido
            }

            // Si el formulario es válido, continuar con la lógica de modales

            const modal1El = document.getElementById("modal1");
            const modal1 = bootstrap.Modal.getInstance(modal1El);

            const modal2El = document.getElementById("staticBackdrop");
            const modal2 = new bootstrap.Modal(modal2El);

            function cleanupModals() {
                const backdrop = document.querySelector(".modal-backdrop");
                if (backdrop) {
                    backdrop.parentNode.removeChild(backdrop);
                }
                document.body.classList.remove("modal-open");
                document.body.style.overflow = "auto";
            }

            if (modal1) {
                modal1.hide();

                modal1El.addEventListener(
                    "hidden.bs.modal",
                    function() {
                        cleanupModals();
                        modal2.show();
                    }, {
                        once: true
                    }
                );
            } else {
                cleanupModals();
                modal2.show();
            }
        });
    </script>

    <!-- Scripts generales que se usan en todas las paginas -->
    <?= view('layout/base_scripts') ?>
</body>

</html>