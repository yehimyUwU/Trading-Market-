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
                // Asigna los datos del usuario a los inputs correspondientes
                document.getElementById('nombreUsuario').value = data.usuario.nombre || 'No disponible';
                document.getElementById('apellidoUsuario').value = data.usuario.apellido || 'No disponible';
                document.getElementById('documentoUsuario').value = data.usuario.documento || 'No disponible';
                document.getElementById('emailUsuario').value = data.usuario.email || 'No disponible';
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


//edicion y guardado de datos en ver perfil

document.addEventListener('DOMContentLoaded', function () {
    // Función para habilitar edición
    function habilitarEdicion() {
        document.querySelectorAll('#nombreUsuario, #apellidoUsuario, #documentoUsuario, #emailUsuario').forEach(input => {
            input.disabled = false;
        });
    }

    // Función para guardar datos
    function guardarCambios() {
        const datosActualizados = {
            nombre: document.getElementById('nombreUsuario').value,
            apellido: document.getElementById('apellidoUsuario').value,
            documento: document.getElementById('documentoUsuario').value,
            email: document.getElementById('emailUsuario').value,
        };

        fetch('../../controllers/php/actualizar_perfil_cliente.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(datosActualizados)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Datos actualizados correctamente.');
                document.querySelectorAll('#nombreUsuario, #apellidoUsuario, #documentoUsuario, #emailUsuario').forEach(input => {
                    input.disabled = true;
                });
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al guardar los cambios.');
        });
    }

    // Asignar eventos
    document.getElementById('editarDatos').addEventListener('click', habilitarEdicion);
    document.getElementById('guardarDatos').addEventListener('click', guardarCambios);
});





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

            // Verificar si ya existe el botón "Pagar" y el modal
            if (!document.getElementById("modalPago")) {
                // Generar el botón y el modal solo si no existen
                insertarBotonYModal();
            }

            asignarEventosCarrito();
        })
        .catch(error => {
            console.error("Error al cargar el carrito:", error);
        });
}

function insertarBotonYModal() {
    // Crear el botón "Pagar"
    const container = document.createElement("div");
    container.classList.add("text-end", "mt-5");
    const botonPagar = document.createElement("button");
    botonPagar.classList.add("btn", "btn-primary");
    botonPagar.textContent = "Pagar";
    container.appendChild(botonPagar);

    // Insertar el botón en el contenedor de la tabla del carrito
    const productosContainer = document.getElementById("productos-container");
    productosContainer.appendChild(container);

    // Crear el modal
    const modalHTML = `
    <div class="modal fade" id="modalPago" tabindex="-1" role="dialog" aria-labelledby="modalPagoLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalPagoLabel">Detalles de Pago</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formPago">
                        <div class="form-group">
                            <label for="correo">Correo Electrónico:</label>
                            <input type="email" class="form-control" id="correo" placeholder="nombre@ejemplo.com" required>
                        </div>
                        <div class="form-group">
                            <label for="direccion">Dirección de Envío:</label>
                            <input type="text" class="form-control" id="direccion" placeholder="Dirección completa" required>
                        </div>
                        <div class="form-group">
                            <label for="metodoPago">Método de Pago:</label>
                            <select class="form-control" id="metodoPago" required>
                                <option value="tarjeta">Tarjeta de Crédito/Débito</option>
                                <option value="paypal">PayPal</option>
                                <option value="efectivo">Efectivo</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-success" id="btnConfirmarPago">Pagar</button>
                </div>
            </div>
        </div>
    </div>`;
    const modalContainer = document.createElement("div");
    modalContainer.innerHTML = modalHTML;
    document.body.appendChild(modalContainer);

    // Configurar el evento del botón "Confirmar Pago"
    document.getElementById("btnConfirmarPago").addEventListener("click", procesarPago);

    // Configurar el botón "Pagar" para abrir el modal
    botonPagar.setAttribute("data-toggle", "modal");
    botonPagar.setAttribute("data-target", "#modalPago");
}



function procesarPago() {
    const correo = document.getElementById("correo").value; // Obtener el correo
    const direccion = document.getElementById("direccion").value; // Obtener la dirección
    const metodoPago = document.getElementById("metodoPago").value; // Obtener el método de pago

    // Validación de campos
    if (!correo || !direccion || !metodoPago) {
        alert("Por favor, complete todos los campos del formulario.");
        return;
    }
    if (!correo.match(/^[^\s@]+@[^\s@]+\.[^\s@]+$/)) { // Validar formato de correo electrónico
        alert("Por favor, ingrese un correo electrónico válido.");
        return;
    }

    // Preparar datos para enviar al controlador
    const datosPedido = {
        correo,
        direccion,
        metodoPago
    };

    // Enviar datos al controlador PHP
    fetch('../../controllers/php/controlador_email_carrito.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(datosPedido),
    })
    .then(response => {
        if (!response.ok) {
            console.error(`Error HTTP: ${response.status} ${response.statusText}`);
            throw new Error("Error al procesar la solicitud al servidor.");
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            alert("¡Pago realizado con éxito! El correo de confirmación ha sido enviado.");
            $('#modalPago').modal('hide'); // Cerrar el modal de pago
        } else {
            alert(data.error || "Ocurrió un error al procesar el pago.");
        }
    })
    .catch(error => {
        console.error("Error al procesar el pago:", error);
        alert("Ocurrió un error inesperado.");
    });
}


// Llamada para agregar un producto (ejemplo)
function agregarProducto(idProducto, cantidad) {
    fetch('../../controllers/php/controlador_email_carrito.php', {
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









//metodos de pago




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
    if (!producto) return;

    document.getElementById("modalNombre").textContent = producto.nombre;
    document.getElementById("modalDescripcion").textContent = producto.descripcion;
    document.getElementById("modalPrecio").textContent = `$${producto.precio}`;
    document.getElementById("modalImagen").src = producto.imagen;

    const estrellasContainer = document.getElementById("estrellas");
    estrellasContainer.setAttribute("data-id-producto", producto.id_producto);
    document.getElementById("mensaje-calificacion").style.display = "none";
    document.querySelectorAll(".estrella").forEach(e => e.classList.remove("seleccionada"));

    // ✨ Mostrar modal
    $("#productoModal").modal("show");

    // ⭐ Lógica para estrellas
    asignarEventosEstrellas();
    cargarCalificacionGuardada(producto.id_producto);

    // 💬 Cargar comentarios automáticamente al abrir el modal
    cargarComentarios(producto.id_producto);

    // 💬 Cargar comentario del usuario en el textarea
    cargarComentarioUsuario(producto.id_producto);

}

    
function asignarEventosEstrellas() {
    document.querySelectorAll(".estrella").forEach(estrella => {
        estrella.onclick = function () {
            // Si ya están seleccionadas (ya calificó), no hacer nada
            if (document.getElementById("btnEditarCalificacion").style.display === "inline-block") return;

            const valor = this.getAttribute("data-valor");
            const idProducto = document.getElementById("estrellas").getAttribute("data-id-producto");

            fetch('../../controllers/php/guardar_calificacion.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ id_producto: idProducto, calificacion: valor })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    marcarEstrellas(valor);
                    document.getElementById("mensaje-calificacion").style.display = "block";
                    document.getElementById("btnEditarCalificacion").style.display = "inline-block";

                    // ✅ Desactivar evento de click después de calificar
                    document.querySelectorAll(".estrella").forEach(estrella => estrella.onclick = null);
                }
            });
        };
    });
}
    
    function marcarEstrellas(valor) {
        document.querySelectorAll(".estrella").forEach(estrella => {
            estrella.classList.remove("seleccionada");
            if (estrella.getAttribute("data-valor") <= valor) {
                estrella.classList.add("seleccionada");
            }
        });
    }
    
    function cargarCalificacionGuardada(idProducto) {
        fetch(`../../controllers/php/obtener_calificacion.php?id_producto=${idProducto}`)
            .then(res => res.json())
            .then(data => {
                if (data.success && data.calificacion > 0) {
                    marcarEstrellas(data.calificacion);
                    document.getElementById("btnEditarCalificacion").style.display = "inline-block";
                } else {
                    document.getElementById("btnEditarCalificacion").style.display = "none";
                }
            });
    }
    
    function editarCalificacion() {
        const idProducto = document.getElementById("estrellas").getAttribute("data-id-producto");
    
        fetch('../../controllers/php/guardar_calificacion.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id_producto: idProducto, calificacion: 0 }) // Eliminar para volver a calificar
        }).then(() => {
            document.querySelectorAll(".estrella").forEach(e => e.classList.remove("seleccionada"));
            document.getElementById("btnEditarCalificacion").style.display = "none";
            document.getElementById("mensaje-calificacion").style.display = "none";
    
            // ✅ Reasignar eventos después de habilitar edición
            asignarEventosEstrellas();
        });
    }

    // Example usage: Add a button to trigger the function
    document.getElementById("btnEditarCalificacion").addEventListener("click", editarCalificacion);
    
    

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

document.addEventListener('DOMContentLoaded', function () {
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
                                <p class="card-subtitle text-muted">ID: ${producto.id_producto}</p>
                                <p class="card-text">${producto.descripcion}</p>
                                <p class="card-text font-weight-bold">$${producto.precio}</p>
                                <button class="btn btn-primary mb-3" onclick="verDetalles('${producto.nombre}', '${producto.descripcion}', '${producto.precio}', '../imagenes_P/${producto.imagen}', ${producto.id_producto})">Ver Detalles</button>
                                <button class="btn btn-success mb-3" onclick="agregarAlCarrito(${producto.id_producto}, '${producto.nombre}', ${producto.precio})">Agregar al Carrito</button>
                            </div>
                        </div>
                    `;
                    productosGrid.appendChild(productoCard);
                });
            } else {
                alert('No se pudieron cargar los productos');
            }
        });
}

function verDetalles(nombre, descripcion, precio, imagen, id_producto) {
    document.getElementById("modalNombre").textContent = nombre;
    document.getElementById("modalDescripcion").textContent = descripcion;
    document.getElementById("modalPrecio").textContent = `$${precio}`;
    document.getElementById("modalImagen").src = imagen;

    // Asigna el ID al contenedor de estrellas
    const estrellasContainer = document.getElementById("estrellas");
    estrellasContainer.setAttribute("data-id-producto", id_producto);

    // Limpia mensaje y estrellas
    document.getElementById("mensaje-calificacion").style.display = "none";
    document.querySelectorAll(".estrella").forEach(e => e.classList.remove("seleccionada"));

    // Mostrar modal
    $('#productoModal').modal('show');

    // Calificación
    asignarEventosEstrellas();
    cargarCalificacionGuardada(id_producto);

    // Comentarios
    cargarComentarios(id_producto);
    cargarComentarioUsuario(id_producto);
}



document.addEventListener("DOMContentLoaded", function () {
    fetch("../../controllers/php/obtener_proveedores_controller.php")
        .then(response => response.json())
        .then(proveedores => {
            const container = document.getElementById("proveedores-container");
            container.classList.add("row", "justify-content-center");

            if (proveedores.error) {
                container.innerHTML = "<p>Error al cargar los proveedores.</p>";
                return;
            }

            proveedores.forEach(proveedor => {
                const card = document.createElement("div");
                card.classList.add("col-lg-4", "col-md-6", "col-sm-12", "mb-3");

                card.innerHTML = `
                    <div class="card position-relative h-100">
                        <button class="btn btn-light position-absolute top-0 end-0 m-2 rounded-circle shadow-sm"
                            title="Guardar proveedor"
                            onclick="guardarProveedor(${proveedor.id_usuario})">
                            <i class="fas fa-plus"></i>
                        </button>
                        <div class="card-body text-center">
                            <img src="data:image/jpeg;base64,${proveedor.imagen}" class="img-fluid rounded-circle mb-3" style="width: 120px; height: 120px; object-fit: cover;" alt="Imagen del proveedor" />
                            <h3 class="card-title">${proveedor.nombre} ${proveedor.apellido}</h3>
                            <button class="btn btn-primary btn-sm mb-3" onclick="mostrarInfo(${proveedor.id_usuario})">Información personal</button>
                            <button class="btn btn-success btn-sm mb-3" onclick="verProductos(${proveedor.id_usuario})">Productos Relacionado</button>
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

function guardarComentario() {
    const idProducto = document.getElementById("estrellas").getAttribute("data-id-producto");
    const comentario = document.getElementById("comentarioTexto").value;

    if (!comentario.trim()) return;

    fetch('../../controllers/php/guardar_comentario.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id_producto: idProducto, comentario: comentario })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            document.getElementById("comentarioTexto").value = "";
            cargarComentarios(idProducto);
        }
    });
}

function cargarComentarios(idProducto) {
    fetch(`../../controllers/php/obtener_comentarios.php?id_producto=${idProducto}`)
        .then(res => res.json())
        .then(data => {
            const contenedor = document.getElementById("listaComentarios");
            contenedor.innerHTML = "";

            if (data.success && data.comentarios.length > 0) {
                data.comentarios.forEach(coment => {
                    const div = document.createElement("div");
                    div.classList.add("border", "p-2", "mb-2", "position-relative");

                    // Generar estrellas según calificación
                    let estrellasHTML = '';
                    for (let i = 1; i <= 5; i++) {
                        estrellasHTML += `<i class="fa-star ${i <= coment.calificacion ? 'fas text-warning' : 'far text-muted'} small"></i>`;
                    }

                    div.innerHTML = `
                        <div class="position-absolute" style="top: 8px; right: 10px;">
                            ${estrellasHTML}
                        </div>
                        <strong>${coment.nombre_usuario || "Anónimo"}:</strong><br>
                        ${coment.comentario}<br>
                        <small class="text-muted">${coment.fecha}</small>
                    `;
                    contenedor.appendChild(div);
                });
            } else {
                contenedor.innerHTML = "<p>No hay comentarios aún.</p>";
            }
        });
}



function mostrarModalProducto(producto) {
    document.getElementById("modalNombre").textContent = producto.nombre;
    document.getElementById("modalImagen").src = producto.imagen;
    document.getElementById("modalDescripcion").textContent = producto.descripcion;
    document.getElementById("modalPrecio").textContent = `$${producto.precio}`;
    document.getElementById("estrellas").setAttribute("data-id-producto", producto.id_producto);

    // ✅ Cargar comentarios automáticamente
    cargarComentarios(producto.id_producto);

    // Mostrar el modal
    $('#productoModal').modal('show');
}

function cargarComentarioUsuario(idProducto) {
    fetch(`../../controllers/php/obtener_comentario_usuario.php?id_producto=${idProducto}`)
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                document.getElementById("comentarioTexto").value = data.comentario;
            } else {
                document.getElementById("comentarioTexto").value = "";
            }
        });
}

document.addEventListener("DOMContentLoaded", function () {
    const params = new URLSearchParams(window.location.search);
    const idProveedor = params.get("id");

    if (idProveedor) {
        fetch(`../../controllers/php/obtener_nombre_proveedor.php?id=${idProveedor}`)
            .then(response => response.json())
            .then(data => {
                if (data && data.nombre && data.apellido) {
                    const titulo = document.getElementById("tituloProveedor");
                    titulo.textContent = `Productos de ${data.nombre} ${data.apellido}`;
                } else {
                    console.error("Proveedor no encontrado.");
                }
            })
            .catch(error => {
                console.error("Error al obtener el nombre del proveedor:", error);
            });
    }
});

function verProductos(idProveedor) {
    console.log("Redireccionando a productosProveedores.html con id:", idProveedor);
    window.location.href = `productosProveedores.html?id=${idProveedor}`;
}

function guardarProveedor(idProveedor) {
    fetch('../../controllers/php/guardar_proveedor_controller.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ id_proveedor: idProveedor }) // asegúrate que `idProveedor` tenga valor
    })
    .then(res => res.json())
    .then(data => {
        console.log(data);
        if (data.error) {
            alert("Error: " + data.error);
        } else {
            alert(data.mensaje);
        }
    })
    .catch(error => {
        console.error("Error al guardar proveedor:", error);
    });
}

document.addEventListener("DOMContentLoaded", function () {
    fetch("../../controllers/php/obtener_proveedores_guardados_controller.php")
        .then(response => response.json())
        .then(proveedores => {
            const tbody = document.getElementById("bodyProveedores");

            if (proveedores.error) {
                tbody.innerHTML = `<tr><td colspan="6">${proveedores.error}</td></tr>`;
                return;
            }

            if (proveedores.length === 0) {
                tbody.innerHTML = `<tr><td colspan="6">No tienes proveedores guardados.</td></tr>`;
                return;
            }

            proveedores.forEach(p => {
                const fila = `
    <tr>
        <td>${p.nombre}</td>
        <td>${p.apellido}</td>
        <td>${p.email}</td>
        <td>${p.genero}</td>
        <td>${p.fecha_nacimiento}</td>
        <td>${p.documento}</td>
        <td>
            <button class="btn btn-danger btn-sm" onclick="eliminarProveedor(${p.id_usuario}, this)">
                <i class="fas fa-trash-alt"></i> Quitar
            </button>
        </td>
    </tr>
`;

                tbody.insertAdjacentHTML('beforeend', fila);
            });
        })
        .catch(error => {
            console.error("Error al cargar proveedores guardados:", error);
        });
});

function eliminarProveedor(idProveedor, boton) {
    if (!confirm("¿Estás seguro de quitar este proveedor de tu lista?")) return;

    fetch('../../controllers/php/eliminar_proveedor_guardado_controller.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ id_proveedor: idProveedor })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            // Borramos la fila del DOM
            const fila = boton.closest("tr");
            fila.remove();
        } else {
            alert("Error: " + (data.error || "No se pudo eliminar"));
        }
    })
    .catch(error => {
        console.error("Error al eliminar proveedor:", error);
    });
}
