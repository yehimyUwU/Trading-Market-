<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis productos - Trading Market</title>
    <!-- Fuentes -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Iconos -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <!-- Estilos -->
    <link rel="stylesheet" href="../../public/Estilos/barraprove.css.css">
    <link rel="stylesheet" href="../../public/Estilos/estilos-productos.css">
    <link rel="stylesheet" href="../../public/Estilos/prove_estilos.css" />
</head>
<body>
<?php
    require '../../controllers/php/barra_prove.php'; 
?>
  
    <div id="content">
        <nav>
            <a href="#" class="nav-link">Mis productos</a>
            <form action="#">
                <div class="form-input">
                    <input type="search" placeholder="Buscar contacto...">
                    <button type="submit" class="search-btn"><i class='bx bx-search'></i></button>
                </div>
            </form>
            <a href="#" class="notification">
                <i class='bx bxs-bell'></i>
                <span class="num">3</span>
            </a>
        </nav>

        <main class="contenido">
            <div class="header-container">
                <div class="header-text">
                    <h1>Productos</h1>
                    <p>producto, <span id="nombreVendedor">Vendedor</span></p>
                </div>

                

                <div class="header-stats">
                    <div class="stat-card">
                        <span class="stat-number" id="totalProductos">0</span>
                        <span class="stat-label">Productos Activos</span>
                    </div>
                    <div class="stat-card">
                        <span class="stat-number" id="pedidosPendientes">0</span>
                        <span class="stat-label">Pedidos Pendientes</span>
                    </div>
                    <div class="stat-card">
                        <span class="stat-number" id="ventasMes">$0</span>
                        <span class="stat-label">Ventas del Mes</span>
                    </div>
                </div>
            </div>

            <section class="busqueda-productos">
                <h2 class="section-header">Gestión de Productos</h2>
                <div class="busqueda-controles">
                    <div class="search-box">
                        <input type="text" id="search-input" placeholder="Buscar productos...">
                        <button>🔍</button>
                    </div>
                    <select id="ordenarPor">
                        <option value="reciente">Más recientes</option>
                        <option value="antiguo">Más antiguos</option>
                        <option value="precio-asc">Precio: Menor a Mayor</option>
                        <option value="precio-desc">Precio: Mayor a Menor</option>
                    </select>
                    <button class="btn-neo">Buscar</button>
                </div>
                <div class="publicar-producto">
                    <button id="btnAbrirModal" class="btn-neo">
                        <span class="material-symbols-outlined">add</span>
                        Nuevo Producto
                    </button>
                </div>
            </section>

            <section class="productos-grid-container">
                <div class="productos-grid" id="productosGrid">
                    <!-- Los productos se cargarán aquí dinámicamente -->
                </div>
            </section>
        </main>
    </div>

    <!-- Modal para nuevo/editar producto -->
    <div id="modalProducto" class="modal">
        <div class="modal-contenido">
            <div class="modal-header">
                <h2 id="modalTitulo">Subir Nuevo Producto</h2>
                <button class="btn-cerrar" onclick="cerrarModal()">&times;</button>
            </div>
            
            <div class="modal-body">
              <form id="formularioProducto">
                  <input type="hidden" id="productoId" name="productoId" value="">
                  <div class="form-group">
                      <label for="nombreProducto">Nombre del Producto*</label>
                      <input type="text" id="nombreProducto" name="nombreProducto" required minlength="3" maxlength="100"
                          placeholder="Ingrese el nombre del producto">
                      <span id="errorNombre" class="error-mensaje"></span>
                  </div>
          
                  <div class="form-group">
                      <label for="descripcion">Descripción*</label>
                      <textarea id="descripcion" name="descripcion" rows="4" required minlength="10" maxlength="500"
                          placeholder="Describa las características del producto"></textarea>
                      <span id="errorDescripcion" class="error-mensaje"></span>
                  </div>
          
                  <div class="form-row">
                      <div class="form-group half">
                          <label for="precio">Precio*</label>
                          <div class="input-group">
                              <span class="currency-symbol">$</span>
                              <input type="number" id="precio" name="precio" step="0.01" min="0.01" required placeholder="0.00">
                          </div>
                          <span id="errorPrecio" class="error-mensaje"></span>
                      </div>
          
                      <div class="form-group half">
                          <label for="stock">Stock Inicial*</label>
                          <input type="number" id="stock" name="stock" min="1" required placeholder="Cantidad disponible">
                          <span id="errorStock" class="error-mensaje"></span>
                      </div>
                  </div>
          
                  <div class="form-row">
                      <div class="form-group half">
                          <label for="categoriaGeneral">Categoría*</label>
                          <select id="categoriaGeneral" name="categoriaGeneral" required onchange="actualizarSubcategorias()">
                              <option value="">Seleccione una categoría</option>
                          </select>
                      </div>
          
                      <div class="form-group half">
                          <label for="subcategoria">Subcategoría*</label>
                          <select id="subcategoria" name="subcategoria">
                              <option value="">Primero seleccione una categoría</option>
                          </select>
                          <span id="errorCategoria" class="error-mensaje"></span>
                      </div>
                  </div>
          
                  <div class="form-group">
                      <label for="imagenProducto">Imagen del Producto*</label>
                      <div class="upload-container">
                          <input type="file" id="imagenProducto" name="imagenProducto" accept="image/*"
                              onchange="previsualizarImagen(event)">
                          <div class="upload-button">
                              <span class="material-symbols-outlined">upload</span>
                              Seleccionar Imagen
                          </div>
                          <span class="file-info">Formatos aceptados: JPG, PNG. Máximo 2MB</span>
                      </div>
                      <div id="previewImagen" class="image-preview"></div>
                      <span id="errorImagen" class="error-mensaje"></span>
                  </div>
          
                  <div class="form-actions">
                      <button type="button" class="btn-secundario" onclick="cerrarModal()">Cancelar</button>
                      <button type="button" id="publicarBtn" class="btn-primario">
                          <span class="material-symbols-outlined">publish</span>
                          <span id="btnAccionTexto">Publicar Producto</span>
                      </button>
                  </div>
              </form>
          
              <div class="preview-producto" id="previewProducto" style="display: none;">
                  <h3>Vista Previa del Producto</h3>
                  <div id="previewContenido"></div>
              </div>
            </div>
        </div>
    </div>

    <script>
    // Variables globales
    let modoEdicion = false;
    let productoActual = null;

    // Función para abrir el modal en modo edición
    function abrirModalEdicion(producto) {
        modoEdicion = true;
        productoActual = producto;

        // Configurar el modal para edición
        document.getElementById('modalTitulo').textContent = 'Editar Producto';
        document.getElementById('btnAccionTexto').textContent = 'Actualizar Producto';
        document.getElementById('productoId').value = producto.id_producto;
        document.getElementById('nombreProducto').value = producto.nombre || '';
        document.getElementById('descripcion').value = producto.descripcion || '';
        document.getElementById('precio').value = producto.precio || '';
        document.getElementById('stock').value = producto.stock || '';

        // Cargar categoría y subcategoría
        cargarCategoriasParaEdicion(producto.id_categoria, producto.id_subcategoria);

        // Mostrar imagen actual si existe
        const previewContainer = document.getElementById('previewImagen');
        if (producto.imagen) {
            previewContainer.innerHTML = `<img src="../imag/${producto.imagen}" alt="Imagen actual del producto" style="max-width: 100%; border-radius: 10px;">`;
        } else {
            previewContainer.innerHTML = ''; // Limpiar vista previa si no hay imagen
        }

        // Mostrar el modal
        document.getElementById('modalProducto').style.display = 'block';
    }

    // Función para cargar categorías y seleccionar la correcta en modo edición
    function cargarCategoriasParaEdicion(idCategoria, idSubcategoria) {
        fetch('../../controllers/php/listar_categorias.php')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const categoriaSelect = document.getElementById('categoriaGeneral');
                    categoriaSelect.innerHTML = '<option value="">Seleccione una categoría</option>';
                    
                    data.listaCategorias.forEach(categoria => {
                        const option = document.createElement('option');
                        option.value = categoria.id_categoria;
                        option.textContent = categoria.nombre;
                        if (categoria.id_categoria == idCategoria) {
                            option.selected = true;
                        }
                        categoriaSelect.appendChild(option);
                    });
                    
                    // Una vez cargada la categoría, cargar las subcategorías
                    actualizarSubcategoriasParaEdicion(idCategoria, idSubcategoria);
                }
            });
    }

    // Función para cargar subcategorías y seleccionar la correcta en modo edición
    function actualizarSubcategoriasParaEdicion(idCategoria, idSubcategoria) {
        const subcategoriaSelect = document.getElementById('subcategoria');
        subcategoriaSelect.innerHTML = '<option value="">Cargando subcategorías...</option>';

        fetch('../../controllers/php/listar_subcategorias.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `id_categoria=${idCategoria}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                subcategoriaSelect.innerHTML = '<option value="">Seleccione una subcategoría</option>';
                data.listaSubcategorias.forEach(subcategoria => {
                    const option = document.createElement('option');
                    option.value = subcategoria.id_subcategoria;
                    option.textContent = subcategoria.nombre;
                    if (subcategoria.id_subcategoria == idSubcategoria) {
                        option.selected = true;
                    }
                    subcategoriaSelect.appendChild(option);
                });
            }
        });
    }

    // Función para manejar el envío del formulario (crear o actualizar)
    function validarFormulario() { 
        const mensajesError = document.querySelectorAll('.error-mensaje');
        mensajesError.forEach(mensaje => mensaje.textContent = '');
        let hayErrores = false;

        const nombre = document.getElementById('nombreProducto').value.trim();
        const descripcion = document.getElementById('descripcion').value.trim();
        const precio = parseFloat(document.getElementById('precio').value);
        const categoriaGeneral = document.getElementById('categoriaGeneral').value;
        const subcategoria = document.getElementById('subcategoria').value;
        const stock = parseInt(document.getElementById('stock').value);
        const imagen = document.getElementById('imagenProducto').files[0];
        const productoId = document.getElementById('productoId').value;

        // Validaciones
        if (!nombre) {
            document.getElementById('errorNombre').textContent = 'El nombre del producto es obligatorio.';
            hayErrores = true;
        }
        if (!descripcion) {
            document.getElementById('errorDescripcion').textContent = 'La descripción es obligatoria.';
            hayErrores = true;
        }
        if (isNaN(precio) || precio <= 0) {
            document.getElementById('errorPrecio').textContent = 'Ingrese un precio válido.';
            hayErrores = true;
        }
        if (!categoriaGeneral) {
            document.getElementById('errorCategoria').textContent = 'Seleccione una categoría.';
            hayErrores = true;
        }
        if (!subcategoria) {
            document.getElementById('errorCategoria').textContent = 'Seleccione una subcategoría.';
            hayErrores = true;
        }
        if (isNaN(stock) || stock <= 0) {
            document.getElementById('errorStock').textContent = 'Ingrese un stock válido.';
            hayErrores = true;
        }
        if (!modoEdicion && !imagen) {
            document.getElementById('errorImagen').textContent = 'Seleccione una imagen.';
            hayErrores = true;
        }

        if (!hayErrores) {
            const formData = new FormData();
            formData.append('nombre', nombre);
            formData.append('descripcion', descripcion);
            formData.append('precio', precio);
            formData.append('categoria', categoriaGeneral);
            formData.append('subcategoria', subcategoria);
            formData.append('stock', stock);
            if (imagen) formData.append('imagen', imagen);
            
            // Agregar el ID del producto si estamos en modo edición
            if (modoEdicion) {
                formData.append('id_producto', productoId);
                formData.append('accion', 'editar');
            } else {
                formData.append('accion', 'crear');
            }

            const url = modoEdicion ? '../../controllers/php/editar_producto.php' : '../../controllers/php/productoControl.php';

            fetch(url, {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    return response.text().then(text => {
                        throw new Error(`HTTP error! status: ${response.status}, response: ${text}`);
                    });
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    alert(modoEdicion ? 'Producto actualizado exitosamente.' : 'Producto registrado exitosamente.');
                    document.getElementById('formularioProducto').reset();
                    cerrarModal();
                    actualizarListaProductos(); // Actualizar la lista de productos sin recargar
                } else {
                    alert('Error: ' + data.message);
                    console.error('Error del servidor:', data);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Ocurrió un error al procesar la solicitud. Verifique la consola para más detalles.');
            });
        }
    }

    function actualizarListaProductos() {
        fetch('../../controllers/php/listar_productos.php')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const productosGrid = document.getElementById('productosGrid');
                    productosGrid.innerHTML = ''; // Limpiar la lista actual

                    data.listaProductos.forEach(producto => {
                        const productoCard = document.createElement('div');
                        productoCard.classList.add('producto-card');
                        productoCard.innerHTML = `
                            <div class="producto-imagen">
                                <img src="../imag/${producto.imagen}" alt="${producto.nombre}" onerror="this.onerror=null;this.src='../imagenes_P/default.jpeg';">
                                <div class="producto-acciones">
                                    <button class="btn-editar" onclick="abrirModalEdicion(${JSON.stringify(producto).replace(/"/g, '&quot;')})">
                                        <span class="material-symbols-outlined">edit</span>
                                    </button>
                                    <button class="btn-eliminar" onclick="eliminarProducto(${producto.id_producto})">
                                        <span class="material-symbols-outlined">delete</span>
                                    </button>
                                </div>
                            </div>
                            <div class="producto-info">
                                <h4>${producto.nombre}</h4>
                                <div class="producto-detalles">
                                    <span class="precio">$${producto.precio}</span>
                                    <span class="stock">Stock: ${producto.stock}</span>
                                </div>
                                <div class="producto-estado">
                                    <span class="badge activo">Activo</span>
                                </div>
                            </div>
                        `;
                        productosGrid.prepend(productoCard); // Agregar al principio de la lista
                    });
                } else {
                    alert('No se pudieron cargar los productos');
                }
            })
            .catch(error => {
                console.error('Error al cargar los productos:', error);
                alert('Error al cargar los productos');
            });
    }

    // Función para cargar los productos
    function cargarProductos() {
        fetch('../../controllers/php/listar_productos.php')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const productosGrid = document.getElementById('productosGrid');
                    productosGrid.innerHTML = '';
                    
                    data.listaProductos.forEach(producto => {
                        const productoCard = document.createElement('div');
                        productoCard.classList.add('producto-card');
                        productoCard.innerHTML = `
                            <div class="producto-imagen">
                                <img src="../imag/${producto.imagen}" alt="${producto.nombre}" onerror="this.onerror=null;this.src='../imagenes_P/default.jpeg';">
                                <div class="producto-acciones">
                                    <button class="btn-editar" onclick="abrirModalEdicion(${JSON.stringify(producto).replace(/"/g, '&quot;')})">
                                        <span class="material-symbols-outlined">edit</span>
                                    </button>
                                    <button class="btn-eliminar" onclick="eliminarProducto(${producto.id_producto})">
                                        <span class="material-symbols-outlined">delete</span>
                                    </button>
                                </div>
                            </div>
                            <div class="producto-info">
                                <h4>${producto.nombre}</h4>
                                <div class="producto-detalles">
                                    <span class="precio">$${producto.precio}</span>
                                    <span class="stock">Stock: ${producto.stock}</span>
                                </div>
                                <div class="producto-estado">
                                    <span class="badge activo">Activo</span>
                                </div>
                            </div>
                        `;
                        productosGrid.appendChild(productoCard);
                    });
                } else {
                    alert('No se pudieron cargar los productos');
                }
            })
            .catch(error => {
                console.error('Error al cargar los productos:', error);
                alert('Error al cargar los productos');
            });
    }

    // Función para eliminar un producto
    function eliminarProducto(idProducto) {
        if (confirm('¿Estás seguro de que deseas eliminar este producto?')) {
            fetch('../../controllers/php/eliminar_producto.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `id_producto=${idProducto}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Producto eliminado exitosamente');
                    cargarProductos();
                } else {
                    alert('Error al eliminar el producto: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Ocurrió un error al eliminar el producto');
            });
        }
    }

    // Función para abrir el modal en modo creación
    function abrirModalCreacion() {
        modoEdicion = false;
        productoActual = null;
        
        // Configurar el modal para creación
        document.getElementById('modalTitulo').textContent = 'Subir Nuevo Producto';
        document.getElementById('btnAccionTexto').textContent = 'Publicar Producto';
        document.getElementById('formularioProducto').reset();
        document.getElementById('previewImagen').innerHTML = '';
        document.getElementById('productoId').value = '';
        
        // Mostrar el modal
        document.getElementById('modalProducto').style.display = 'block';
    }

    // Función para cerrar el modal
    function cerrarModal() {
        document.getElementById('modalProducto').style.display = 'none';
        document.getElementById('formularioProducto').reset();
        document.getElementById('previewProducto').style.display = 'none';
    }

    // Función para previsualizar la imagen
    function previsualizarImagen(event) {
        const input = event.target;
        const previewContainer = document.getElementById('previewImagen');
        previewContainer.innerHTML = ''; // Limpiar vista previa anterior

        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function (e) {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.alt = 'Vista previa de la imagen';
                img.style.maxWidth = '100%';
                img.style.borderRadius = '10px';
                previewContainer.appendChild(img);
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    // Event listeners
    document.getElementById('btnAbrirModal').addEventListener('click', abrirModalCreacion);
    document.getElementById('publicarBtn').addEventListener('click', validarFormulario);

    window.onclick = function(event) {
        const modal = document.getElementById('modalProducto');
        if (event.target === modal) {
            cerrarModal();
        }
    }

    // Cargar categorías al inicio
    document.addEventListener('DOMContentLoaded', function() {
        // Cargar categorías para el select
        fetch('../../controllers/php/listar_categorias.php')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const categoriaSelect = document.getElementById('categoriaGeneral');
                    categoriaSelect.innerHTML = '<option value="">Seleccione una categoría</option>';
                    data.listaCategorias.forEach(categoria => {
                        const option = document.createElement('option');
                        option.value = categoria.id_categoria;
                        option.textContent = categoria.nombre;
                        categoriaSelect.appendChild(option);
                    });
                }
            });
        
        // Cargar productos al inicio
        cargarProductos();
    });

    // Función para actualizar subcategorías (genérica)
    function actualizarSubcategorias() {
        const categoriaGeneral = document.getElementById('categoriaGeneral').value;
        const subcategoriaSelect = document.getElementById('subcategoria');
        subcategoriaSelect.innerHTML = '<option value="">Cargando subcategorías...</option>';

        if (!categoriaGeneral) {
            subcategoriaSelect.innerHTML = '<option value="">Primero seleccione una categoría válida</option>';
            return;
        }

        fetch('../../controllers/php/listar_subcategorias.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `id_categoria=${categoriaGeneral}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                subcategoriaSelect.innerHTML = '<option value="">Seleccione una subcategoría</option>';
                data.listaSubcategorias.forEach(subcategoria => {
                    const option = document.createElement('option');
                    option.value = subcategoria.id_subcategoria;
                    option.textContent = subcategoria.nombre;
                    subcategoriaSelect.appendChild(option);
                });
            } else {
                subcategoriaSelect.innerHTML = '<option value="">No hay subcategorías disponibles</option>';
            }
        })
        .catch(error => {
            console.error('Error al cargar las subcategorías:', error);
            subcategoriaSelect.innerHTML = '<option value="">Error al cargar las subcategorías</option>';
        });

        setTimeout(() => {
            const searchInput = document.getElementById("search-input");
            const searchButton = document.querySelector(".btn-neo"); // Botón de búsqueda

            if (!searchInput || !searchButton) return;

            // Función para filtrar productos
            const filtrarProductos = () => {
                const searchText = searchInput.value.toLowerCase().trim();
                const products = document.querySelectorAll(".producto-card"); // Obtener productos actualizados

                products.forEach(product => {
                    const titleElement = product.querySelector("h4"); // Título del producto
                    if (!titleElement) return;

                    const title = titleElement.textContent.toLowerCase();
                    
                    if (title.includes(searchText) || searchText === "") {
                        product.style.display = "flex";  // Mostrar la tarjeta
                    } else {
                        product.style.display = "none"; // Ocultar la tarjeta
                    }
                });
            };

            // Evento para buscar al escribir en el campo de búsqueda
            searchInput.addEventListener("input", filtrarProductos);

            // Evento para buscar al hacer clic en el botón
            searchButton.addEventListener("click", (e) => {
                e.preventDefault(); // Evitar el comportamiento predeterminado del botón
                filtrarProductos();
            });
        }, 500);
    }
    </script>

    <script src="../../public/js/barraprove.js.js"></script>
    <script type="module" src="https://cdn.jsdelivr.net/npm/@ionic/core/dist/ionic/ionic.esm.js"></script>
    <script nomodule src="https://cdn.jsdelivr.net/npm/@ionic/core/dist/ionic/ionic.js"></script>
</body>
</html>