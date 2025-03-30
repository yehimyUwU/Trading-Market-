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




function agregarAlCarrito(id, nombre, precio) {
    const datos = {
        id_producto: id,
        cantidad: 1 // Por defecto, se agrega 1 unidad
    };

    fetch('../php/agregar_carrito.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(datos)
    })
    .then(response => response.json())
    .then(data => {
        if (data.error) {
            alert(data.error);
        } else if (data.success.includes("Cantidad actualizada")) {
            alert("Este producto ya estaba en el carrito. Se ha actualizado su cantidad.");
        } else {
            alert("Producto agregado al carrito.");
        }
        actualizarCarritoUI(); // Refrescar el carrito en la interfaz
    })
    .catch(error => {
        console.error("Error al agregar al carrito:", error);
    });
}


function actualizarCarritoUI() {
    fetch('../php/obtener_carrito.php')
        .then(response => response.json())
        .then(data => {
            const contenidoCarrito = document.getElementById("contenidoCarrito");
            const totalCarrito = document.getElementById("totalCarrito");
            contenidoCarrito.innerHTML = ""; // Limpiar contenido actual
            let total = 0;

            data.forEach(producto => {
                // Asegúrate de que los valores sean válidos y redondeados
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


function asignarEventosCarrito() {
    document.querySelectorAll(".aumentar-cantidad").forEach(boton => {
        boton.addEventListener("click", function () {
            const idProducto = this.getAttribute("data-id");
            actualizarCantidadProducto(idProducto, 1); // Incrementar cantidad
        });
    });

    document.querySelectorAll(".disminuir-cantidad").forEach(boton => {
        boton.addEventListener("click", function () {
            const idProducto = this.getAttribute("data-id");
            actualizarCantidadProducto(idProducto, -1); // Decrementar cantidad
        });
    });

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

    fetch('../php/eliminar_carrito.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ id_producto: idProducto })
    })
    .then(response => response.json())
    .then(data => {
        if (data.error) {
            alert(data.error);
            return;
        }
        alert("Producto eliminado del carrito.");
        actualizarCarritoUI(); // Refrescar la interfaz del carrito
    })
    .catch(error => {
        console.error("Error al eliminar el producto:", error);
    });
}



function actualizarCantidadProducto(idProducto, cambio) {
    fetch('../php/actualizar_cantidad_carrito.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ id_producto: idProducto, cambio: cambio })
    })
    .then(response => response.json())
    .then(data => {
        if (data.error) {
            alert(data.error);
            return;
        }
        actualizarCarritoUI(); // Refrescar el carrito en la interfaz
    })
    .catch(error => {
        console.error("Error al actualizar la cantidad:", error);
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
    fetch('../php/listar_productos.php')
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
                                <button class="btn btn-success" onclick="agregarAlCarrito(${producto.id}, '${producto.nombre}', ${producto.precio})">Agregar al Carrito</button>
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
