fetch('barra.html')
  .then(response => response.text())
  .then(html => {
    document.getElementById('nav-container').innerHTML = html;
  });

  document.addEventListener("DOMContentLoaded", function () {
    // Esperar a que la barra de navegación se cargue antes de buscar el input
    setTimeout(() => {
        const searchInput = document.getElementById("search-input");
        if (!searchInput) return; // Evitar errores si no se encuentra el input
        
        const products = document.querySelectorAll(".card"); // Todos los productos

        searchInput.addEventListener("input", function () {
            const searchText = searchInput.value.toLowerCase(); // Convierte a minúsculas

            products.forEach(product => {
                const title = product.querySelector(".card-title").textContent.toLowerCase();
                
                if (title.includes(searchText)) {
                    product.parentElement.style.display = "block"; // Muestra el producto
                } else {
                    product.parentElement.style.display = "none"; // Oculta el producto
                }
            });
        });
    }, 500); // Retraso para asegurarse de que la barra de navegación se cargue
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

// Asegúrate de que el elemento con id 'perfilContenedor' exista antes de agregar el event listener
document.addEventListener('DOMContentLoaded', function() {
    const perfilContenedor = document.getElementById('perfilContenedor');
    if (perfilContenedor) {
        perfilContenedor.addEventListener('click', cerrarPerfil);
    }
});