// Script para mensajes_admin y poder responder mensajes
function responderMensaje(nombre, correo) {
    document.getElementById('nombre').value = nombre;
    document.getElementById('correo').value = correo;
    document.getElementById('respuesta').focus();
}

// Enviar el mensaje
document.getElementById('respuesta-form').addEventListener('submit', function(e) {
    e.preventDefault();
    alert('Mensaje enviado correctamente.');

});

// Limpiar campos manualmente con el botón
document.getElementById('btn-limpiar').addEventListener('click', function() {
    document.getElementById('nombre').value = '';
    document.getElementById('correo').value = '';
    document.getElementById('respuesta').value = '';
});
