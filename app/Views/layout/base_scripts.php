<script>
document.addEventListener('DOMContentLoaded', () => {
    const activeElement = document.querySelector('.nav-link.active');
    if (activeElement) {
        const parentElement = activeElement.closest('li.hvr-underline-from-center');
        if (parentElement) {
            parentElement.classList.remove('hvr-underline-from-center');
        }

        const icon = activeElement.querySelector('i');
        if (icon) {
            icon.classList.remove('animate__animated', 'animate__heartBeat', 'animate__infinite');
        }
    }

    const elementoActivo = document.getElementById('btn_nav_<?= esc($activo ?? '') ?>');
    if (elementoActivo) {
        elementoActivo.classList.add('active');
        const parentElement = elementoActivo.closest('li.hvr-underline-from-center');
        if (parentElement) {
            parentElement.classList.remove('hvr-underline-from-center');
        }

        const icon = elementoActivo.querySelector('i');
        if (icon) {
            icon.classList.add('animate__animated', 'animate__heartBeat', 'animate__infinite');
        }
    }
});
</script>

<script src="<?= base_url('public/assets/js/bootstrap.bundle.js') ?>"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>