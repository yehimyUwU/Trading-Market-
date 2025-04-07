fetch('../../views/html/barra.html') // Cambiar ruta según estructura
  .then(response => response.text())
  .then(html => {
    document.getElementById('nav-container').innerHTML = html;
  });

document.addEventListener("DOMContentLoaded", function () {
    setTimeout(() => {
        const searchInput = document.getElementById("search-input");
        if (!searchInput) return;

        searchInput.addEventListener("input", function () {
            const searchText = searchInput.value.toLowerCase().trim();
            const products = document.querySelectorAll(".card"); // Obtener productos actualizados

            products.forEach(product => {
                const titleElement = product.querySelector(".card-title");
                if (!titleElement) return;

                const title = titleElement.textContent.toLowerCase();
                
                if (title.includes(searchText) || searchText === "") {
                    product.style.display = "flex";  // Asegurar que la tarjeta mantiene el formato
                } else {
                    product.style.display = "none";
                }
            });
        });
    }, 500);
});

function cerrarSesion() {
    fetch('../../controllers/php/logout.php', { // Cambiar ruta según estructura
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            localStorage.removeItem('usuario'); // Limpiar datos del usuario
            window.location.href = '../../views/html/longin.html'; // Cambiar ruta según estructura
        } else {
            console.error('Error en el logout:', data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        localStorage.removeItem('usuario'); // Limpiar datos del usuario
        window.location.href = '../../views/html/longin.html'; // Cambiar ruta según estructura
    });
}

function mostrarPerfil() {
    // Muestra el modal
    $('#userProfileModal').modal('show');

    // Llama al archivo PHP para obtener los datos del usuario
    fetch('../../controllers/php/obtener_perfil.php') // Cambiar ruta según estructura
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

function agregarAlCarrito(id, nombre, precio) {
    const datos = {
        id_producto: id,
        cantidad: 1,
        accion: 'agregar' // Esto asegura que el controlador sepa qué acción manejar
    };

    fetch('../../controllers/php/controlador_carrito.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(datos) // Envía los datos al servidor
    })
    .then(response => {
        if (!response.ok) { // Manejo de errores HTTP
            throw new Error(`Error HTTP: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.error) {
            alert(data.error);
        } else if (data.success) {
            alert(data.success);
        }
    })
    .catch(error => {
        console.error("Error al agregar al carrito:", error);
    });
}

function actualizarCarritoUI() {
    fetch('../../controllers/php/controlador_carrito.php', { method: 'GET' })
        .then(response => response.json())
        .then(data => {
            const contenidoCarrito = document.getElementById("contenidoCarrito");
            const totalCarrito = document.getElementById("totalCarrito");
            contenidoCarrito.innerHTML = ""; // Limpiar el contenido actual

            let total = 0;

            data.forEach(producto => {
                const precio = parseFloat(producto.precio);
                const cantidad = parseInt(producto.cantidad);

                if (isNaN(precio) || isNaN(cantidad)) {
                    console.error("Producto inválido:", producto);
                    return;
                }

                total += precio * cantidad;

                contenidoCarrito.innerHTML += `
                    <tr>
                        <td>${producto.id_producto}</td>
                        <td>${producto.nombre}</td>
                        <td>$${precio.toFixed(2)}</td>
                        <td>
                            <button class="btn btn-sm btn-outline-secondary disminuir-cantidad" data-id="${producto.id_producto}">-</button>
                            ${cantidad}
                            <button class="btn btn-sm btn-outline-secondary aumentar-cantidad" data-id="${producto.id_producto}">+</button>
                        </td>
                        <td>$${(precio * cantidad).toFixed(2)}</td>
                        <td><button class="btn btn-danger btn-sm eliminar-carrito" data-id="${producto.id_producto}">Eliminar</button></td>
                    </tr>
                `;
            });

            totalCarrito.textContent = `$${total.toFixed(2)}`;

            asignarEventosCarrito();
        })
        .catch(error => {
            console.error("Error al cargar el carrito:", error);
        });
}

// Llamada para agregar un producto (ejemplo)
function agregarProducto(idProducto, cantidad) {
    fetch('../../controllers/php/controlador_carrito.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            accion: 'agregar',
            id_producto: idProducto,
            cantidad: cantidad
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.error) {
            alert(data.error);
        } else {
            alert(data.success); // Mostrar el mensaje de éxito
            actualizarCarritoUI(); // Refrescar el carrito
        }
    })
    .catch(error => {
        console.error("Error al agregar producto:", error);
        alert("Ocurrió un error al agregar el producto.");
    });
}




function asignarEventosCarrito() {
    document.querySelectorAll(".aumentar-cantidad").forEach(boton => 
        boton.addEventListener("click", () => 
            actualizarCantidadProducto(boton.getAttribute("data-id"), 1)
        )
    );

    document.querySelectorAll(".disminuir-cantidad").forEach(boton => 
        boton.addEventListener("click", () => 
            actualizarCantidadProducto(boton.getAttribute("data-id"), -1)
        )
    );



    // Capturar clic en el botón "Eliminar"
    document.querySelectorAll(".eliminar-carrito").forEach(boton => {
        boton.addEventListener("click", function () {
            const idProducto = this.getAttribute("data-id");
            eliminarProductoCarrito(idProducto);
        });
    });
}

function eliminarProductoCarrito(idProducto) {
    const confirmarEliminacion = confirm("¿Estás seguro de eliminar este producto?");
    if (!confirmarEliminacion) {
        return; // Si el usuario cancela, no hace nada
    }

    fetch('../../controllers/php/controlador_carrito.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            accion: 'eliminar', // Acción específica
            id_producto: idProducto
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.error) {
            alert(data.error);
            return;
        }
        alert("Producto eliminado del carrito correctamente."); // Mensaje de éxito
        actualizarCarritoUI(); // Refrescar la interfaz del carrito
    })
    .catch(error => {
        console.error("Error al eliminar el producto:", error);
        alert("Ocurrió un error al eliminar el producto.");
    });
}


function actualizarCantidadProducto(idProducto, cambio) {
    idProducto = parseInt(idProducto, 10); // Asegurar que idProducto sea un número entero

    if (!idProducto || cambio === 0) {
        alert("Datos inválidos para actualizar la cantidad.");
        console.error("Datos inválidos:", { idProducto, cambio });
        return;
    }

    console.log("Enviando datos al servidor:", { id_producto: idProducto, cambio });

    fetch('../../controllers/php/actualizar_cantidad_carrito.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            accion: 'modificar',
            id_producto: idProducto,
            cambio: cambio
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.error) {
            alert(data.error); // Mostrar mensaje de error del servidor
            console.error("Error del servidor:", data.error);
        } else {
            alert(data.success); // Mensaje de éxito
            actualizarCarritoUI(); // Refrescar carrito
        }
    })
    .catch(error => {
        console.error("Error al actualizar la cantidad:", error);
        alert("Ocurrió un error al actualizar la cantidad.");
    });
}


//                              FUNCIONES DE CARRITO FINAL                                         //

document.addEventListener("DOMContentLoaded", function () {
    let productosOriginales = [];

    function cargarProductos() {
        const contenedorProductos = document.getElementById("productos-container");
        if (!contenedorProductos) {
            console.error("No se encontró el contenedor de productos.");
            return;
        }

        const categoria = contenedorProductos.getAttribute("data-categoria");

        if (!categoria) {
            contenedorProductos.innerHTML = `<p>No se especificó una categoría válida.</p>`;
            return;
        }

        fetch(`../../controllers/php/obtener_productos_controller.php?categoria=${encodeURIComponent(categoria)}`)
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    contenedorProductos.innerHTML = `<p>${data.error}</p>`;
                    return;
                }

                productosOriginales = data;
                mostrarProductos(productosOriginales);
            })
            .catch(error => {
                console.error("Error al cargar productos:", error);
                contenedorProductos.innerHTML = `<p>Error al cargar productos.</p>`;
            });
    }

    function mostrarProductos(productos) {
        const contenedor = document.getElementById("productos-container");
        contenedor.innerHTML = "";

        if (productos.length === 0) {
            contenedor.innerHTML = `<p>No hay productos disponibles en esta categoría.</p>`;
            return;
        }

        productos.forEach(producto => {
            const productoHTML = `
                <div class="col-md-4">
                    <div class="card">
                        <img src="../imagenes/${producto.imagen}" class="card-img-top" alt="${producto.nombre}">
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
        if (!producto) {
            console.error("No se encontró el producto con ID:", id);
            return;
        }

        document.getElementById("modalNombre").textContent = producto.nombre;
        document.getElementById("modalDescripcion").textContent = producto.descripcion;
        document.getElementById("modalPrecio").textContent = `$${producto.precio}`;
        document.getElementById("modalImagen").src = producto.imagen;

        $("#productoModal").modal("show");
    }

    function filtrarProductos() {
        let orden = document.getElementById("ordenar").value;
        let precioMin = parseFloat(document.getElementById("precio-min").value) || 0;
        let precioMax = parseFloat(document.getElementById("precio-max").value) || Infinity;

        let productosFiltrados = productosOriginales.filter(producto => 
            producto.precio >= precioMin && producto.precio <= precioMax
        );

        if (orden === "az") {
            productosFiltrados.sort((a, b) => a.nombre.localeCompare(b.nombre));
        } else if (orden === "za") {
            productosFiltrados.sort((a, b) => b.nombre.localeCompare(a.nombre));
        }

        mostrarProductos(productosFiltrados);
    }

    document.querySelector(".btn-primary").addEventListener("click", filtrarProductos);

    cargarProductos();
});

document.addEventListener('DOMContentLoaded', function() {
    cargarProductos();
});

function cargarProductos() {
    fetch('../../controllers/php/listar_productos.php')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const productosGrid = document.getElementById('productosGrid');
                productosGrid.innerHTML = '';
                data.listaProductos.forEach(producto => {
                    const productoCard = document.createElement('div');
                    productoCard.classList.add('col-md-4', 'mb-4');
                    productoCard.innerHTML = `
                        <div class="card">
                            <img src="../imagenes_P/${producto.imagen || 'default.jpeg'}" class="card-img-top" alt="${producto.nombre}" onerror="this.onerror=null;this.src='../imagenes_P/default.jpeg';">
                            <div class="card-body">
                                <h5 class="card-title">${producto.nombre}</h5>
                                <p class="card-text">${producto.descripcion}</p>
                                <p class="card-text font-weight-bold">$${producto.precio}</p>
                                <button class="btn btn-primary" onclick="verDetalles('${producto.nombre}', '${producto.descripcion}', '${producto.precio}', '../imagenes_P/${producto.imagen}')">Ver Detalles</button>
                                <button class="btn btn-success" onclick="agregarAlCarrito(${producto.id_producto}, '${producto.nombre}', ${producto.precio})">Agregar al Carrito</button>
                            </div>
                        </div>
                    `;
                    productosGrid.appendChild(productoCard);
                });
            } else {
                alert('No se pudieron cargar los productos');
            }
        })
}

function verDetalles(nombre, descripcion, precio, imagen) {
    document.getElementById('modalNombre').textContent = nombre;
    document.getElementById('modalDescripcion').textContent = descripcion;
    document.getElementById('modalPrecio').textContent = `$${precio}`;
    document.getElementById('modalImagen').src = imagen;
    $('#productoModal').modal('show');
}

document.addEventListener("DOMContentLoaded", function () {
    fetch("../../controllers/php/obtener_proveedores_controller.php")
        .then(response => response.json())
        .then(proveedores => {
            const container = document.getElementById("proveedores-container");
            container.classList.add("row", "justify-content-center"); // Centrar y organizar

            if (proveedores.error) {
                container.innerHTML = "<p>Error al cargar los proveedores.</p>";
                return;
            }

            proveedores.forEach(proveedor => {
                const card = document.createElement("div");
                card.classList.add("col-lg-4", "col-md-6", "col-sm-12", "mb-3"); // 3 por fila en grande, 2 en medianas, 1 en móviles

                card.innerHTML = `
                    <div class="card">
                        <div class="card-body text-center">
                            <h3 class="card-title">${proveedor.nombre} ${proveedor.apellido}</h3>
                            <button class="btn btn-primary mb-3" onclick="mostrarInfo(${proveedor.id_usuario})">Mostrar Información</button>
                            <button class="btn btn-success mb-3">Productos Relacionados</button>
                        </div>
                    </div>
                `;

                container.appendChild(card);
            });
        })
        .catch(error => console.error("Error al obtener proveedores:", error));
});

function mostrarInfo(idProveedor) {
    fetch(`../../controllers/php/obtener_proveedores_controller.php?id=${idProveedor}`)
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                alert("Error: " + data.error);
                return;
            }
            // Llenar los datos en el modal
            document.getElementById("modalNombre").textContent = data.nombre;
            document.getElementById("modalApellido").textContent = data.apellido;
            document.getElementById("modalEmail").textContent = data.email;
            document.getElementById("modalGenero").textContent = data.genero;
            document.getElementById("modalFechaNacimiento").textContent = data.fecha_nacimiento;
            document.getElementById("modalDocumento").textContent = data.documento;

            // Mostrar el modal
            $("#infoProveedorModal").modal("show");
        })
        .catch(error => console.error("Error al obtener los datos del proveedor:", error));
}

