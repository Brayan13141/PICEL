document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.custom-file-input').forEach((input) => {
        input.addEventListener('change', function () {
            let form = this.closest('form'); // Encuentra el formulario actual
            let submitButton = form.querySelector('.btn-success'); // Encuaentra el botón de enviar
            if (this.files.length > 0) {
                submitButton.removeAttribute('disabled');
            } else {
                submitButton.setAttribute('disabled', 'true');
            }
        });
    });
});
