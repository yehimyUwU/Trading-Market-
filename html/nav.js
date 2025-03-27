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


//                         FUNCIONES DE CARRITO                      //

let carrito = JSON.parse(localStorage.getItem("carrito")) || [];

// Función para actualizar y mostrar el carrito en la tabla
function actualizarCarrito() {
    const contenidoCarrito = document.getElementById("contenidoCarrito");
    contenidoCarrito.innerHTML = ""; // Limpiar contenido previo

    if (carrito.length === 0) {
        contenidoCarrito.innerHTML = `<tr><td colspan="6" class="text-center">El carrito está vacío.</td></tr>`;
        document.getElementById("totalCarrito").innerText = "$0.00";
        return;
    }

    carrito.forEach(producto => {
        const fila = `
            <tr>
                <td>${producto.id}</td>
                <td>${producto.nombre}</td>
                <td>$${parseFloat(producto.precio).toFixed(2)}</td>
                <td>
                    <input type="number" value="${producto.cantidad}" min="1" class="form-control form-control-sm"
                        onchange="cambiarCantidad('${producto.id}', this.value)">
                </td>
                <td>$${(parseFloat(producto.precio) * producto.cantidad).toFixed(2)}</td>
                <td>
                    <button class="btn btn-danger btn-sm" onclick="eliminarProducto('${producto.id}')">Eliminar</button>
                </td>
            </tr>
        `;
        contenidoCarrito.innerHTML += fila;
    });

    calcularTotalCarrito(); // Actualizar el total generalSDFG
}


function limpiarCarrito() {
    const carritoCompleto = [];

    carrito.forEach(producto => {
        const existe = carritoCompleto.find(item => item.id === producto.id);
        if (existe) {
            // Actualizar cantidad si el producto ya existeASDFG
            existe.cantidad += producto.cantidad;
        } else if (producto.nombre && producto.precio) {
            // Solo agregar productos con información completaASDFG
            carritoCompleto.push(producto);
        }
    });

    // Actualiza el carrito y el localStorage con datos limpios DSDSSDFG
    carrito = carritoCompleto;
    localStorage.setItem("carrito", JSON.stringify(carrito));
}

function limpiarCarrito() {
    carrito = carrito.filter(producto => producto.nombre && producto.precio && producto.cantidad > 0);
    localStorage.setItem("carrito", JSON.stringify(carrito));
    console.log("Carrito limpiado:", carrito);
}
document.addEventListener("DOMContentLoaded", function () {
    carrito = JSON.parse(localStorage.getItem("carrito")) || [];
    limpiarCarrito(); // Limpia datos duplicados/incompletos solo al cargar
    actualizarCarrito(); // Muestra los datos del carrito
});

// Función para calcular el total general del carrito
function calcularTotalCarrito() {
    const total = carrito.reduce((sum, producto) => sum + producto.precio * producto.cantidad, 0);
    document.getElementById("totalCarrito").innerText = `$${total.toFixed(2)}`;
}




// Función para agregar productos al carritoSDFGSDFG
function agregarAlCarrito(id, nombre, precio) {
    const productoExistente = carrito.find(producto => producto.id === id);

    if (productoExistente) {
        productoExistente.cantidad++; // Incrementar solo si ya existe el productoASSD
    } else {
        carrito.push({
            id: id,
            nombre: nombre,
            precio: precio,
            cantidad: 1
        });
    }

    localStorage.setItem("carrito", JSON.stringify(carrito)); // Guardar el carrito actualizado
    console.log(`Producto agregado: ${nombre}. Carrito:`, carrito);
    alert(`El producto ha sido agregado al carrito.`);
    actualizarCarrito(); // Refrescar la tabla del carrito
}




// Función para cambiar la cantidad de un producto
function cambiarCantidad(id, nuevaCantidad) {
    const producto = carrito.find(producto => producto.id === id);
    if (producto) {
        producto.cantidad = parseInt(nuevaCantidad);

        if (isNaN(producto.cantidad) || producto.cantidad <= 0) {
            alert("La cantidad debe ser un número mayor a 0.");
            producto.cantidad = 1; // Volvemos a 1 si el valor es inválido
        }
    }

    // Actualizar localStorage
    localStorage.setItem("carrito", JSON.stringify(carrito));

    // Actualizar la tabla
    actualizarCarrito();
}


// Función para eliminar un producto del carrito
function eliminarProducto(id) {
    // Filtrar el producto a eliminar
    carrito = carrito.filter(producto => producto.id !== id);

    // Actualizar localStorage
    localStorage.setItem("carrito", JSON.stringify(carrito));

    // Actualizar la tabla
    actualizarCarrito();
}


// Función para inicializar los botones de "Agregar al carrito"
function manejarBotones() {
    document.querySelectorAll(".agregar-carrito").forEach(boton => {
        boton.addEventListener("click", function () {
            const id = parseInt(this.getAttribute("data-id"));
            const nombre = this.parentElement.querySelector(".card-title").innerText;
            const precio = parseFloat(this.parentElement.querySelector(".card-price").innerText.replace("$", ""));

            // Llama a la función para agregar al carrito
            agregarAlCarrito(id, nombre, precio);
        });
    });
}

// Mostrar el carrito al cargar la página
document.addEventListener("DOMContentLoaded", function () {
    actualizarCarrito();
    manejarBotones();
});

//                              FUNCIONES DE CARRITO FINAL                                         //






document.addEventListener("DOMContentLoaded", function () {
    let productosOriginales = [];

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
                productosOriginales = data;
                mostrarProductos(productosOriginales);
            })
            .catch(error => console.error("Error al cargar productos:", error));
    }

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
                            <button class="btn btn-primary ver-detalles mb-2" data-id="${producto.id_producto}">Ver Detalles</button>
                            <button class="btn btn-success agregar-carrito mb-3" data-id="${producto.id_producto}" data-nombre="${producto.nombre}" data-precio="${producto.precio}">Agregar al carrito</button>
                        </div>
                    </div>
                </div>
            `;
            contenedor.innerHTML += productoHTML;
        });

        asignarEventos();
    }

    function asignarEventos() {
        document.querySelectorAll(".ver-detalles").forEach(btn => {
            btn.addEventListener("click", function () {
                let idProducto = this.getAttribute("data-id");
                mostrarDetallesProducto(idProducto);
            });
        });

        document.querySelectorAll(".agregar-carrito").forEach(boton => {
            boton.addEventListener("click", function () {
                const id = this.getAttribute("data-id");
                const nombre = this.getAttribute("data-nombre");
                const precio = this.getAttribute("data-precio");

                agregarAlCarrito(id, nombre, precio);
            });
        });
    }

    function mostrarDetallesProducto(id) {
        const producto = productosOriginales.find(p => p.id_producto == id);
        if (!producto) return;

        document.getElementById("modalNombre").textContent = producto.nombre;
        document.getElementById("modalDescripcion").textContent = producto.descripcion;
        document.getElementById("modalPrecio").textContent = `$${producto.precio}`;
        document.getElementById("modalImagen").src = producto.imagen;

        $("#productoModal").modal("show");
    }

    function filtrarProductos() {
        let productosFiltrados = [...productosOriginales];
        const orden = document.getElementById("ordenar").value;
        const minPrecio = parseFloat(document.getElementById("precio-min").value) || 0;
        const maxPrecio = parseFloat(document.getElementById("precio-max").value) || Infinity;

        productosFiltrados = productosFiltrados.filter(p => p.precio >= minPrecio && p.precio <= maxPrecio);

        productosFiltrados.sort((a, b) => {
            if (orden === "az") return a.nombre.localeCompare(b.nombre);
            if (orden === "za") return b.nombre.localeCompare(a.nombre);
        });

        mostrarProductos(productosFiltrados);
    }

    document.getElementById("ordenar").addEventListener("change", filtrarProductos);
    document.getElementById("precio-min").addEventListener("input", filtrarProductos);
    document.getElementById("precio-max").addEventListener("input", filtrarProductos);

    cargarProductos();
}); 

$('#productoModal').on('shown.bs.modal', function () {
    $('.modal-dialog').css({
        'max-width': '90vw',
        'height': '90vh'
    });
});


