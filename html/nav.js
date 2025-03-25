fetch('barra.html')
  .then(response => response.text())
  .then(html => {
    document.getElementById('nav-container').innerHTML = html;
  });

  document.addEventListener("DOMContentLoaded", function () {
    setTimeout(() => {
        const searchInput = document.getElementById("search-input");
        if (!searchInput) return;

        const products = document.querySelectorAll(".card"); 

        searchInput.addEventListener("input", function () {
            const searchText = searchInput.value.toLowerCase();

            products.forEach(product => {
                const titleElement = product.querySelector(".card-title");
                if (!titleElement) return; // Evita errores si no encuentra título

                const title = titleElement.textContent.toLowerCase();
                
                if (title.includes(searchText)) {
                    product.style.display = "block";
                } else {
                    product.style.display = "none";
                }
            });
        });
    }, 500);
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

document.addEventListener("DOMContentLoaded", function () {
    let productosOriginales = [];

    // Cargar productos desde la base de datos
    function cargarProductos() {
        const contenedorProductos = document.getElementById("productos-container");
        if (!contenedorProductos) return;

        const categoria = contenedorProductos.getAttribute("data-categoria");

        fetch(`../php/obtener_productos.php?categoria=${encodeURIComponent(categoria)}`)
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    contenedorProductos.innerHTML = `<p>${data.error}</p>`;
                    return;
                }

                productosOriginales = data; // Guardar la copia original
                mostrarProductos(productosOriginales);
            })
            .catch(error => console.error("Error al cargar productos:", error));
    }

    // Mostrar productos en la página
    function mostrarProductos(productos) {
        const contenedor = document.getElementById("productos-container");
        contenedor.innerHTML = "";

        productos.forEach(producto => {
            const productoHTML = `
                <div class="col-md-4">
                    <div class="card">
                        <img src="${producto.imagen}" class="card-img-top" alt="${producto.nombre}">
                        <div class="card-body">
                            <h5 class="card-title">${producto.nombre}</h5>
                            <p class="card-text">${producto.descripcion}</p>
                            <p class="card-price">$${producto.precio}</p>
                            <button class="btn btn-primary agregar-carrito" data-id="${producto.id_producto}">Agregar al carrito</button>
                        </div>
                    </div>
                </div>
            `;
            contenedor.innerHTML += productoHTML;
        });

        // Agregar funcionalidad al botón "Agregar al carrito"
        document.querySelectorAll(".agregar-carrito").forEach(btn => {
            btn.addEventListener("click", function () {
                let idProducto = this.getAttribute("data-id");
                agregarAlCarrito(idProducto);
            });
        });
    }

    // Filtrar y ordenar productos
    function filtrarProductos() {
        let productosFiltrados = [...productosOriginales];

        const orden = document.getElementById("ordenar").value;
        const minPrecio = parseFloat(document.getElementById("precio-min").value) || 0;
        const maxPrecio = parseFloat(document.getElementById("precio-max").value) || Infinity;

        // Filtrar por precio
        productosFiltrados = productosFiltrados.filter(p => p.precio >= minPrecio && p.precio <= maxPrecio);

        // Ordenar alfabéticamente
        productosFiltrados.sort((a, b) => {
            if (orden === "az") return a.nombre.localeCompare(b.nombre);
            if (orden === "za") return b.nombre.localeCompare(a.nombre);
        });

        mostrarProductos(productosFiltrados);
    }

    // Inicializar la carga de productos
    cargarProductos();

    // Evento para aplicar filtros al hacer clic
    document.querySelector("button[onclick='filtrarProductos()']").addEventListener("click", filtrarProductos);
});

