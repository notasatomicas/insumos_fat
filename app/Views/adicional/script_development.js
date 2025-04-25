<script>
  // Script para disparar el modal cuando se hace clic en un botón con la clase "comprar"
  document.querySelectorAll('.comprar').forEach(button => {
    button.addEventListener('click', function () {
      var myModal = new bootstrap.Modal(document.getElementById('staticBackdrop'), {
        keyboard: false
      });
      myModal.show();
    });
  });
</script>
