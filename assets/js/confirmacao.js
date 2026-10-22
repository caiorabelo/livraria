document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('form[data-confirmacao]').forEach((formulario) => {
        formulario.addEventListener('submit', (evento) => {
            if (!window.confirm(formulario.dataset.confirmacao)) {
                evento.preventDefault();
            }
        });
    });
});
