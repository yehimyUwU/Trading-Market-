fetch('barra.html')
  .then(response => response.text())
  .then(html => {
    document.getElementById('nav-container').innerHTML = html;
  });

  function cerrarSesion() {
    fetch('../php/logout.php')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                localStorage.removeItem('usuario'); // Limpiar datos del usuario
                window.location.href = 'longin.html';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            localStorage.removeItem('usuario'); // Limpiar datos del usuario
            window.location.href = 'longin.html';
        });
}

function mostrarPerfil() {
    // Muestra el modal
    $('#userProfileModal').modal('show');

    // Llama al archivo PHP para obtener los datos del usuario
    fetch('../php/obtener_perfil.php') // Verifica que esta URL sea correcta
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Asigna los datos del usuario a los elementos correspondientes
                document.getElementById('nombreUsuario').textContent = data.usuario.nombre || 'No disponible';
                document.getElementById('apellidoUsuario').textContent = data.usuario.apellido || 'No disponible';
                document.getElementById('documentoUsuario').textContent = data.usuario.documento || 'No disponible';
                document.getElementById('emailUsuario').textContent = data.usuario.email || 'No disponible';
                document.getElementById('fechaNacimientoUsuario').textContent = data.usuario.fecha_nacimiento || 'No disponible';
                document.getElementById('generoUsuario').textContent = data.usuario.genero || 'No disponible';
            } else {
                alert('No se pudo cargar la información del perfil');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al cargar el perfil');
        });
}

function cerrarPerfil() {
    $('#userProfileModal').modal('hide'); // Cierra el modal
}

// Cerrar modal al hacer clic en el fondo
document.getElementById('perfilContenedor').addEventListener('click', cerrarPerfil);





