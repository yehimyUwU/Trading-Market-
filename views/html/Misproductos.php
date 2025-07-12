<!DOCTYPE html>
<?php
/**
 * Archivo: Misproductos.php
 * Descripción: Gestión de productos para proveedores
 * Conexiones:
 * - Se conecta con: controllers/php/barra_prove.php (para la barra de navegación)
 * - Se conecta con: controllers/php/listar_categorias.php (para categorías)
 * - Se conecta con: controllers/php/productoControl.php (para gestión de productos)
 * Funcionalidades:
 * - Lista de productos
 * - Formulario de nuevo producto
 * - Edición de productos existentes
 * - Eliminación de productos
 * - Filtros y búsqueda
 * - Vista previa de productos
 */
session_start();

if (!isset($_SESSION['usuario']['id'])) {
    header("Location: ../../views/html/longin.html");
    exit;
}

$id_proveedor = $_SESSION['usuario']['id'];
?>
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
    <script src="https://cdn.jsdelivr.net/npm/fuse.js@7.0.0/dist/fuse.min.js"></script>
    <style>
.busqueda-controles.mejorada {
    display: flex;
    flex-wrap: wrap;
    gap: 1.2rem;
    align-items: center;
    background: #fff;
    border-radius: 18px;
    box-shadow: 0 2px 16px rgba(0,0,0,0.07);
    padding: 1.2rem 2rem 1.2rem 2rem;
    margin-bottom: 1.5rem;
}
.smart-search {
    position: relative;
    display: flex;
    align-items: center;
    flex: 1 1 320px;
    max-width: 420px;
    min-width: 220px;
}
.smart-search input[type="text"] {
    width: 100%;
    padding: 0.7em 2.5em 0.7em 1.1em;
    border: 1.5px solid #e0e0e0;
    border-radius: 12px;
    font-size: 1.08em;
    transition: border 0.2s;
    outline: none;
    background: #fafbfc;
}
.smart-search input[type="text"]:focus {
    border: 2px solid #28a745;
    background: #fff;
}
#btn-search-icon {
    position: absolute;
    right: 0.5em;
    background: none;
    border: none;
    color: #28a745;
    font-size: 1.5em;
    cursor: pointer;
    padding: 0.2em 0.5em;
    border-radius: 8px;
    transition: background 0.2s;
    z-index: 2;
}
#btn-search-icon:focus, #btn-search-icon:hover {
    background: #eafbe7;
}
/* Animación para el indicador de búsqueda */
@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}
.search-suggestions {
    position: absolute;
    top: 110%;
    left: 0;
    width: 100%;
    background: #fff;
    border: 1.5px solid #e0e0e0;
    border-radius: 0 0 12px 12px;
    box-shadow: 0 4px 16px rgba(40,167,69,0.08);
    z-index: 10;
    max-height: 220px;
    overflow-y: auto;
    font-size: 1em;
    display: none;
}
.search-suggestions.active {
    display: block;
    animation: fadeIn 0.18s;
}
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-8px); }
    to { opacity: 1; transform: translateY(0); }
}
#ordenarPor {
    padding: 0.6em 1.2em;
    border-radius: 10px;
    border: 1.5px solid #e0e0e0;
    font-size: 1.05em;
    background: #fafbfc;
    transition: border 0.2s;
}
#ordenarPor:focus {
    border: 2px solid #28a745;
    background: #fff;
}
#btn-buscar {
    padding: 0.7em 1.7em;
    border-radius: 10px;
    font-size: 1.08em;
    font-weight: 600;
    background: linear-gradient(90deg, #28a745 60%, #51e67a 100%);
    color: #fff;
    border: none;
    box-shadow: 0 2px 8px rgba(40,167,69,0.10);
    cursor: pointer;
    transition: background 0.2s, box-shadow 0.2s;
}
#btn-buscar:hover, #btn-buscar:focus {
    background: linear-gradient(90deg, #218838 60%, #51e67a 100%);
    box-shadow: 0 4px 16px rgba(40,167,69,0.18);
}
@media (max-width: 700px) {
    .busqueda-controles.mejorada {
        flex-direction: column;
        gap: 0.7rem;
        padding: 1.1rem 0.7rem;
    }
    .smart-search {
        max-width: 100%;
    }
}
</style>
</head>
<body>
<?php
    require '../../controllers/php/barra_prove.php'; 
?>
  
    <div id="content" style="
    top: 300px;
        

        <main class="contenido">
            <div class="header-container" style=" padding-top: 120px;">
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
                <div class="busqueda-controles mejorada">
                    <div class="search-box smart-search">
                        <input type="text" id="search-input" placeholder="Buscar productos, categorías o presentaciones..." aria-label="Buscar productos" autocomplete="off" />
                        <button id="btn-search-icon" tabindex="0" aria-label="Buscar">
                            <span class="material-symbols-outlined">search</span>
                        </button>
                        <div id="search-suggestions" class="search-suggestions" style="display:none;"></div>
                    </div>
                    <select id="ordenarPor" aria-label="Ordenar productos">
                        <option value="reciente">Más recientes</option>
                        <option value="antiguo">Más antiguos</option>
                        <option value="precio-asc">Precio: Menor a Mayor</option>
                        <option value="precio-desc">Precio: Mayor a Menor</option>
                    </select>
                    <button class="btn-neo" id="btn-buscar">Buscar</button>
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
    <div id="modalProducto" class="modal" style="display: none;">
        <div class="modal-contenido animated-modal">
            <div class="modal-header custom-modal-header">
                <h2 id="modalTitulo">Subir Nuevo Producto</h2>
                <button class="btn-cerrar" onclick="cerrarModal()">&times;</button>
            </div>
            
            <div class="modal-body">
              <form id="formularioProducto">
                  <input type="hidden" id="productoId" name="productoId" value="">
                  <input type="hidden" id="id_proveedor_hidden" name="id_proveedor" value="<?php echo isset($_SESSION['usuario']['id']) ? $_SESSION['usuario']['id'] : ''; ?>">
                  
                  <!-- Datos generales del producto -->
                  <div class="form-group">
                      <label for="nombreProducto">Nombre del Producto*</label>
                      <input type="text" id="nombreProducto" name="nombreProducto" required minlength="3" maxlength="100" placeholder="Ingrese el nombre del producto">
                      <span id="errorNombre" class="error-mensaje"></span>
                  </div>
          
                  <div class="form-group">
                      <label for="descripcion">Descripción*</label>
                      <textarea id="descripcion" name="descripcion" rows="4" required minlength="10" maxlength="500" placeholder="Describa las características del producto"></textarea>
                      <span id="errorDescripcion" class="error-mensaje"></span>
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
          
                  <!-- Sección de presentaciones/tamaños -->
                  <div class="form-group">
                      <div class="presentaciones-header">
                          <label>Presentaciones/Tamaños del Producto*</label>
                          <button type="button" class="btn-agregar-presentacion" onclick="agregarPresentacion()">
                              <span class="material-symbols-outlined">add</span>
                              Agregar Presentación
                          </button>
                      </div>
                      <div id="presentaciones-container">
                          <!-- Las presentaciones se agregarán aquí dinámicamente -->
                      </div>
                      <span id="errorPresentaciones" class="error-mensaje"></span>
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

    <div id="toast-notification" style="display:none;position:fixed;bottom:30px;left:50%;transform:translateX(-50%);z-index:9999;min-width:220px;max-width:90vw;padding:16px 28px;border-radius:8px;font-size:1.1em;font-weight:500;box-shadow:0 4px 16px rgba(0,0,0,0.12);background:#fff;color:#222;transition:all 0.3s;align-items:center;gap:10px;"></div>

    <script>
    // Variables globales
    let modoEdicion = false;
    let productoActual = null;
    let contadorPresentaciones = 0; // Este contador ya no se usará como índice, solo para IDs únicos
    let productosOriginales = [];
    let fuse = null;

    // Función para inicializar Fuse.js con los productos
    function inicializarBuscadorFuse(productos) {
        console.log('Inicializando Fuse.js con', productos.length, 'productos');
        
        const options = {
            keys: [
                'nombre',
                'descripcion',
                'nombre_categoria',
                'presentaciones.tamano',
                'presentaciones.unidad'
            ],
            threshold: 0.35, // tolerancia a errores
            includeMatches: true,
            minMatchCharLength: 2,
            ignoreLocation: true,
            useExtendedSearch: true
        };
        
        try {
            fuse = new Fuse(productos, options);
            console.log('Fuse.js inicializado correctamente');
        } catch (error) {
            console.error('Error al inicializar Fuse.js:', error);
            fuse = null;
        }
    }

    // Función para renderizar productos (usada por búsqueda y carga inicial)
    function renderizarProductos(productos, matches = null) {
        const productosGrid = document.getElementById('productosGrid');
        productosGrid.innerHTML = '';
        if (!productos.length) {
            productosGrid.innerHTML = '<div style="padding:2em;text-align:center;color:#888;font-size:1.2em;">No se encontraron productos. <br>¿Buscabas otra cosa?</div>';
            return;
        }
        productos.forEach((producto, idx) => {
            let imagenPrincipal = producto.imagen && producto.imagen !== '../../public/imagenes_P/default.jpeg' ? '../../public/imag/' + producto.imagen : null;
            if (!imagenPrincipal && producto.presentaciones && producto.presentaciones.length > 0) {
                imagenPrincipal = producto.presentaciones[0].imagen;
            }
            if (!imagenPrincipal) {
                imagenPrincipal = '../imagenes_P/default.jpeg';
            }
            const presentacionPrincipal = producto.presentaciones && producto.presentaciones.length > 0 
                ? producto.presentaciones[0] 
                : null;
            const numPresentaciones = producto.presentaciones ? producto.presentaciones.length : 0;
            const textoPresentaciones = numPresentaciones === 0 ? 'Sin presentaciones' : (numPresentaciones === 1 ? '1 presentación' : `${numPresentaciones} presentaciones`);
            const estado = producto.estado && producto.estado.toLowerCase() === 'inactivo' ? 'Inactivo' : 'Activo';
            const badgeEstado = estado === 'Activo' ? '<span class="badge badge-estado activo">Activo</span>' : '<span class="badge badge-estado inactivo">Inactivo</span>';
            const precio = presentacionPrincipal ? `$${presentacionPrincipal.precio}` : '';
            const peso = presentacionPrincipal ? `${presentacionPrincipal.tamano || ''}${presentacionPrincipal.unidad || ''}` : '';

            // Resaltado de coincidencias si hay matches
            let nombreResaltado = producto.nombre;
            let descResaltado = producto.descripcion;
            if (matches && matches[idx] && matches[idx].matches) {
                matches[idx].matches.forEach(m => {
                    if (m.key === 'nombre') {
                        nombreResaltado = resaltarCoincidencia(producto.nombre, m.indices);
                    }
                    if (m.key === 'descripcion') {
                        descResaltado = resaltarCoincidencia(producto.descripcion, m.indices);
                    }
                });
            }

            const productoCard = document.createElement('div');
            productoCard.classList.add('producto-card');
            productoCard.innerHTML = `
                <div class="producto-imagen">
                <img src="${imagenPrincipal}" 
                     alt="${producto.nombre}" 
                     onerror="this.onerror=null;this.src='../imagenes_P/default.jpeg';">
                    <div class="producto-acciones">
                        <button class="btn-editar" onclick="editarProductoPorId(${producto.id_producto})">
                            <span class="material-symbols-outlined">edit</span>
                        </button>
                        <button class="btn-eliminar" onclick="eliminarProducto(${producto.id_producto})">
                            <span class="material-symbols-outlined">delete</span>
                        </button>
                    </div>
                </div>
                <div class="producto-info" style="display: flex; align-items: center; justify-content: space-between; gap: 8px;">
                    <h4 style="margin: 0;">${nombreResaltado}</h4>
                    ${badgeEstado}
                </div>
                <div class="producto-footer">
                    ${precio ? `<div class="footer-item precio">${precio}</div>` : ''}
                    ${peso ? `<div class="footer-item peso">${peso}</div>` : ''}
                    <div class="footer-item presentaciones">${textoPresentaciones}</div>
                </div>
            `;
            productosGrid.appendChild(productoCard);
        });
        document.getElementById('totalProductos').textContent = productos.length;
    }

    // Función para resaltar coincidencias
    function resaltarCoincidencia(texto, indices) {
        if (!indices || !indices.length) return texto;
        let resultado = '';
        let ultimo = 0;
        indices.forEach(([ini, fin]) => {
            resultado += texto.substring(ultimo, ini);
            resultado += `<mark style='background:#ffe066;color:#d35400;border-radius:3px;'>${texto.substring(ini, fin + 1)}</mark>`;
            ultimo = fin + 1;
        });
        resultado += texto.substring(ultimo);
        return resultado;
    }

    // Búsqueda avanzada mejorada
    function buscarProductosAvanzado() {
        const searchInput = document.getElementById('search-input');
        const searchTerm = searchInput.value.trim();
        
        console.log('Búsqueda avanzada iniciada con término:', searchTerm);
        
        // Mostrar indicador de búsqueda
        mostrarIndicadorBusqueda(true);
        
        if (!searchTerm) {
            console.log('Término vacío, mostrando todos los productos');
            renderizarProductos(productosOriginales);
            mostrarIndicadorBusqueda(false);
            return;
        }
        
        if (!fuse) {
            console.log('Fuse.js no inicializado, usando búsqueda básica');
            // Búsqueda básica como fallback
            const resultados = productosOriginales.filter(producto => 
                producto.nombre.toLowerCase().includes(searchTerm.toLowerCase()) ||
                producto.descripcion.toLowerCase().includes(searchTerm.toLowerCase()) ||
                producto.nombre_categoria.toLowerCase().includes(searchTerm.toLowerCase())
            );
            renderizarProductos(resultados);
            mostrarIndicadorBusqueda(false);
            return;
        }
        
        console.log('Ejecutando búsqueda con Fuse.js');
        const resultados = fuse.search(searchTerm);
        console.log('Resultados de Fuse.js:', resultados);
        
        if (resultados.length > 0) {
            const productosEncontrados = resultados.map(r => r.item);
            renderizarProductos(productosEncontrados, resultados);
            mostrarResultadosBusqueda(resultados.length, searchTerm);
        } else {
            // Sugerencias inteligentes
            console.log('No se encontraron resultados exactos, buscando sugerencias...');
            const sugerencias = productosOriginales.filter(producto => 
                producto.nombre_categoria.toLowerCase().includes(searchTerm.toLowerCase()) ||
                (producto.presentaciones && producto.presentaciones.some(p => 
                    p.tamano && p.tamano.toString().includes(searchTerm)
                ))
            );
            
            if (sugerencias.length > 0) {
                console.log('Mostrando sugerencias:', sugerencias.length);
                renderizarProductos(sugerencias);
                mostrarResultadosBusqueda(sugerencias.length, searchTerm, true);
            } else {
                console.log('No se encontraron sugerencias');
                renderizarProductos([]);
                mostrarResultadosBusqueda(0, searchTerm);
            }
        }
        
        mostrarIndicadorBusqueda(false);
    }

    // Función para mostrar/ocultar indicador de búsqueda
    function mostrarIndicadorBusqueda(mostrar) {
        const searchButton = document.getElementById('btn-search-icon');
        if (searchButton) {
            if (mostrar) {
                searchButton.innerHTML = '<span class="material-symbols-outlined" style="animation: spin 1s linear infinite;">sync</span>';
            } else {
                searchButton.innerHTML = '<span class="material-symbols-outlined">search</span>';
            }
        }
    }

    // Función para mostrar resultados de búsqueda
    function mostrarResultadosBusqueda(cantidad, termino, esSugerencia = false) {
        const productosGrid = document.getElementById('productosGrid');
        const mensaje = document.createElement('div');
        mensaje.style.cssText = 'grid-column: 1 / -1; padding: 1rem; text-align: center; color: #666; font-size: 0.9em;';
        
        if (cantidad === 0) {
            mensaje.innerHTML = `No se encontraron productos para "<strong>${termino}</strong>"`;
        } else if (esSugerencia) {
            mensaje.innerHTML = `Mostrando ${cantidad} sugerencias relacionadas con "<strong>${termino}</strong>"`;
        } else {
            mensaje.innerHTML = `Se encontraron ${cantidad} productos para "<strong>${termino}</strong>"`;
        }
        
        // Insertar mensaje al inicio del grid
        productosGrid.insertBefore(mensaje, productosGrid.firstChild);
        
        // Remover mensaje después de 3 segundos
        setTimeout(() => {
            if (mensaje.parentNode) {
                mensaje.remove();
            }
        }, 3000);
    }

    // Función para abrir el modal en modo edición
    function abrirModalEdicion(producto) {
        modoEdicion = true;
        productoActual = producto;

        // Configurar el modal para edición
        document.getElementById('modalTitulo').textContent = 'Editar Producto';
        document.getElementById('btnAccionTexto').textContent = 'Actualizar Producto';
        if (document.getElementById('productoId')) document.getElementById('productoId').value = producto.id_producto;
        if (document.getElementById('nombreProducto')) document.getElementById('nombreProducto').value = producto.nombre || '';
        if (document.getElementById('descripcion')) document.getElementById('descripcion').value = producto.descripcion || '';

        // Cargar categoría y subcategoría
        cargarCategoriasParaEdicion(producto.id_categoria, producto.id_subcategoria);

        // Limpiar presentaciones anteriores
        limpiarPresentaciones();

        // Reconstruir presentaciones si existen
        if (producto.presentaciones && producto.presentaciones.length > 0) {
            producto.presentaciones.forEach((presentacion, idx) => {
                agregarPresentacion(); // Agrega un bloque vacío
                // Selecciona el último bloque agregado
                const container = document.getElementById('presentaciones-container');
                const presentaciones = container.querySelectorAll('.presentacion-item');
                const presentacionDiv = presentaciones[presentaciones.length - 1];
                // Llena los campos
                presentacionDiv.querySelector('input[name*="[tamano]"]').value = presentacion.tamano || '';
                presentacionDiv.querySelector('select[name*="[unidad]"]').value = presentacion.unidad || '';
                presentacionDiv.querySelector('input[name*="[precio]"]').value = presentacion.precio || '';
                presentacionDiv.querySelector('input[name*="[stock]"]').value = presentacion.stock || '';
                presentacionDiv.querySelector('input[name*="[largo]"]').value = presentacion.largo || '';
                presentacionDiv.querySelector('input[name*="[ancho]"]').value = presentacion.ancho || '';
                presentacionDiv.querySelector('input[name*="[alto]"]').value = presentacion.alto || '';
                presentacionDiv.querySelector('select[name*="[unidad_dimension]"]').value = presentacion.unidad_dimension || '';
                // Asegura que presentacion.imagen tenga la ruta correcta
                if (!presentacion.imagen && presentacion.nombre_imagen) {
                    presentacion.imagen = '../../public/imag/' + presentacion.nombre_imagen;
                }
                // Previsualizar la imagen si existe
                const preview = presentacionDiv.querySelector('.presentacion-preview');
                console.log('Imagen previa para presentación', idx, ':', presentacion.imagen);
                if (preview && presentacion.imagen) {
                    preview.innerHTML = `<img src="${presentacion.imagen}" alt="Imagen presentación" style="max-width:80px;max-height:80px;">`;
                }
                // Al reconstruir presentaciones en abrirModalEdicion, agrega el input hidden para id_presentacion si existe
                if (presentacion.id_presentacion) {
                    let inputId = document.createElement('input');
                    inputId.type = 'hidden';
                    inputId.name = `presentaciones[${idx}][id_presentacion]`;
                    inputId.value = presentacion.id_presentacion;
                    presentacionDiv.appendChild(inputId);
                }
                // Agrega input hidden para nombre_imagen si existe
                if (presentacion.nombre_imagen) {
                    let inputNombreImagen = document.createElement('input');
                    inputNombreImagen.type = 'hidden';
                    inputNombreImagen.name = `presentaciones[${idx}][nombre_imagen]`;
                    inputNombreImagen.value = presentacion.nombre_imagen;
                    presentacionDiv.appendChild(inputNombreImagen);
                }
            });
        }

        // Mostrar el modal
        document.getElementById('modalProducto').style.display = 'block';
        // Asignar el evento al botón publicarBtn cada vez que se abre el modal
        setTimeout(() => {
            const btn = document.getElementById('publicarBtn');
            if (btn) {
                btn.onclick = validarFormulario;
            }
        }, 100);
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

      // Esto debe estar ANTES de tus otros scripts JS
      const idProveedor = <?php echo json_encode($_SESSION['usuario']['id'] ?? null); ?>;
    
    if (!idProveedor) {
        alert('No se pudo obtener el ID del proveedor. Redirigiendo...');
        window.location.href = '../../views/html/longin.html';
    }

    // Función para manejar el envío del formulario (crear o actualizar)
    function validarFormulario() { 
        console.log('validarFormulario ejecutándose');
        const mensajesError = document.querySelectorAll('.error-mensaje');
        mensajesError.forEach(mensaje => mensaje.textContent = '');
        let hayErrores = false;

        const nombre = document.getElementById('nombreProducto').value.trim();
        const descripcion = document.getElementById('descripcion').value.trim();
        const categoriaGeneral = document.getElementById('categoriaGeneral').value;
        const subcategoria = document.getElementById('subcategoria').value;
        const productoId = document.getElementById('productoId').value;

        // Validaciones básicas del producto
        if (!nombre) {
            document.getElementById('errorNombre').textContent = 'El nombre del producto es obligatorio.';
            hayErrores = true;
        }
        if (!descripcion) {
            document.getElementById('errorDescripcion').textContent = 'La descripción es obligatoria.';
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

        // Validar presentaciones
        if (!validarPresentaciones()) {
            hayErrores = true;
        }

        console.log('¿Hay errores?', hayErrores);
        if (!hayErrores) {
            const formData = new FormData();
            formData.append('id_proveedor', idProveedor);
            formData.append('nombre', nombre);
            formData.append('descripcion', descripcion);
            formData.append('categoria', categoriaGeneral);
            formData.append('subcategoria', subcategoria);
            
            // Agregar presentaciones al FormData
            const presentaciones = document.querySelectorAll('.presentacion-item');
            presentaciones.forEach((presentacion, index) => {
                // Si existe un id_presentacion (en modo edición), inclúyelo
                const idPresentacionInput = presentacion.querySelector('input[name*="[id_presentacion]"]');
                if (idPresentacionInput) {
                    formData.append(`presentaciones[${index}][id_presentacion]`, idPresentacionInput.value);
                }
                const tamaño = presentacion.querySelector('input[name*="[tamano]"]').value;
                const unidad = presentacion.querySelector('select[name*="[unidad]"]').value;
                const precio = presentacion.querySelector('input[name*="[precio]"]').value;
                const stock = presentacion.querySelector('input[name*="[stock]"]').value;
                const imagen = presentacion.querySelector('input[type="file"]').files[0];
                const largo = presentacion.querySelector('input[name*="[largo]"]').value;
                const ancho = presentacion.querySelector('input[name*="[ancho]"]').value;
                const alto = presentacion.querySelector('input[name*="[alto]"]').value;
                const unidad_dimension = presentacion.querySelector('select[name*="[unidad_dimension]"]').value;

                formData.append(`presentaciones[${index}][tamano]`, tamaño);
                formData.append(`presentaciones[${index}][unidad]`, unidad);
                formData.append(`presentaciones[${index}][precio]`, precio);
                formData.append(`presentaciones[${index}][stock]`, stock);
                // Cambia aquí: usa el nombre plano para la imagen
                formData.append(`presentaciones_imagen_${index}`, imagen);
                formData.append(`presentaciones[${index}][largo]`, largo);
                formData.append(`presentaciones[${index}][ancho]`, ancho);
                formData.append(`presentaciones[${index}][alto]`, alto);
                formData.append(`presentaciones[${index}][unidad_dimension]`, unidad_dimension);
            });
            
            // Agregar el ID del producto si estamos en modo edición
            if (modoEdicion) {
                formData.append('id_producto', productoId);
                formData.append('accion', 'editar');
            } else {
                formData.append('accion', 'crear');
            }

            // Siempre enviar a productoControl.php para crear y editar (ajustar si tienes un endpoint separado para editar)
            const url = '../../controllers/php/productoControl.php';

            // Log para depuración
            console.log('Enviando datos a productoControl.php:', Array.from(formData.entries()));

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
                console.log('Respuesta del backend:', data);
                if (data.success) {
                    showToast('¡Producto registrado exitosamente!', 'success');
                    document.getElementById('formularioProducto').reset();
                    cerrarModal();
                    cargarProductos(); // Actualizar la lista de productos sin recargar
                } else {
                    showToast('Error: ' + (data.message || 'No se pudo registrar el producto.'), 'error');
                    console.error('Error del servidor:', data);
                }
            })
            .catch(error => {
                showToast('Error al registrar/actualizar el producto. Intenta de nuevo o contacta soporte.', 'error');
                console.error('Error en fetch:', error);
            });
        }
    }

  

    // Función para cargar los productos
    function cargarProductos() {
        const formData = new FormData();
        formData.append('id_proveedor', idProveedor);
        formData.append('listarConPresentaciones', 'ok');
        console.log('Enviando a productoControl.php:', Array.from(formData.entries()));
        fetch('../../controllers/php/productoControl.php', {
            method: 'POST',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                console.log('Productos recibidos:', data.listaProductos);
                if (data.success) {
                    productosOriginales = data.listaProductos;
                    console.log('Inicializando Fuse.js con', productosOriginales.length, 'productos');
                    inicializarBuscadorFuse(productosOriginales);
                    renderizarProductos(productosOriginales);
                    // Inicializar eventos de búsqueda después de cargar productos
                    inicializarEventosBusqueda();
                } else {
                    renderizarProductos([]);
                }
            })
            .catch(error => {
                console.error('Error al cargar los productos:', error);
                renderizarProductos([]);
            });
    }

    // Función para inicializar eventos de búsqueda
    function inicializarEventosBusqueda() {
        const searchInput = document.getElementById('search-input');
        const searchButton = document.getElementById('btn-buscar');
        const ordenarSelect = document.getElementById('ordenarPor');
        
        console.log('Inicializando eventos de búsqueda...');
        
        // Evento de búsqueda en tiempo real
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                console.log('Buscando:', this.value);
                buscarProductosAvanzado();
            });
        }
        
        // Evento del botón buscar
        if (searchButton) {
            searchButton.addEventListener('click', function(e) {
                e.preventDefault();
                console.log('Buscando (botón):', searchInput.value);
                buscarProductosAvanzado();
            });
        }
        
        // Evento de ordenación
        if (ordenarSelect) {
            ordenarSelect.addEventListener('change', function() {
                console.log('Ordenando por:', this.value);
                ordenarProductos(this.value);
            });
        }
    }

    // Función para ordenar productos
    function ordenarProductos(criterio) {
        let productosOrdenados = [...productosOriginales];
        
        switch(criterio) {
            case 'reciente':
                productosOrdenados.sort((a, b) => new Date(b.fecha_creacion) - new Date(a.fecha_creacion));
                break;
            case 'antiguo':
                productosOrdenados.sort((a, b) => new Date(a.fecha_creacion) - new Date(b.fecha_creacion));
                break;
            case 'precio-asc':
                productosOrdenados.sort((a, b) => {
                    const precioA = a.presentaciones && a.presentaciones.length > 0 ? parseFloat(a.presentaciones[0].precio) : 0;
                    const precioB = b.presentaciones && b.presentaciones.length > 0 ? parseFloat(b.presentaciones[0].precio) : 0;
                    return precioA - precioB;
                });
                break;
            case 'precio-desc':
                productosOrdenados.sort((a, b) => {
                    const precioA = a.presentaciones && a.presentaciones.length > 0 ? parseFloat(a.presentaciones[0].precio) : 0;
                    const precioB = b.presentaciones && b.presentaciones.length > 0 ? parseFloat(b.presentaciones[0].precio) : 0;
                    return precioB - precioA;
                });
                break;
        }
        
        renderizarProductos(productosOrdenados);
    }

    // Función para eliminar un producto
    function eliminarProducto(idProducto) {
        if (confirm('¿Estás seguro de que deseas eliminar este producto?')) {
            const formData = new FormData();
            formData.append('eliminarProducto', idProducto);
            
            fetch('../../controllers/php/productoControl.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.codigo === "200") {
                    alert('Producto eliminado exitosamente');
                    cargarProductos();
                } else {
                    alert('Error al eliminar el producto: ' + data.mensaje);
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
        document.getElementById('productoId').value = '';
        limpiarPresentaciones(); // Limpiar presentaciones anteriores
        
        // Mostrar el modal
        document.getElementById('modalProducto').style.display = 'block';
        // Asignar el evento al botón publicarBtn cada vez que se abre el modal
        setTimeout(() => {
            const btn = document.getElementById('publicarBtn');
            if (btn) {
                btn.onclick = validarFormulario;
            }
        }, 100);
    }

    // Función para cerrar el modal
    function cerrarModal() {
        document.getElementById('modalProducto').style.display = 'none';
        document.getElementById('formularioProducto').reset();
        document.getElementById('previewProducto').style.display = 'none';
        limpiarPresentaciones(); // Limpiar presentaciones al cerrar
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
    // Elimina cualquier addEventListener global para publicarBtn si existe

    window.onclick = function(event) {
        const modal = document.getElementById('modalProducto');
        if (event.target === modal) {
            cerrarModal();
        }
    }

    // Delegación de eventos para asegurar que el botón funcione aunque se regenere el modal
    document.addEventListener('click', function(e) {
        if (e.target && e.target.id === 'publicarBtn') {
            validarFormulario();
        }
    });

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
    }

    // Función para agregar una nueva presentación
    function agregarPresentacion() {
        const container = document.getElementById('presentaciones-container');
        const presentaciones = container.querySelectorAll('.presentacion-item');
        const index = presentaciones.length; // SIEMPRE consecutivo
        contadorPresentaciones++; // Solo para IDs únicos en el DOM
        
        const presentacionHTML = `
            <div class="presentacion-item" id="presentacion-${contadorPresentaciones}">
                <div class="presentacion-header">
                    <h4 class="presentacion-titulo">Presentación ${index + 1}</h4>
                    <button type="button" class="btn-eliminar-presentacion" onclick="eliminarPresentacion(${contadorPresentaciones})">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>
                
                <div class="presentacion-grid">
                    <div class="presentacion-input-group">
                        <label for="tamanio-${contadorPresentaciones}">Tamaño/Volumen*</label>
                        <input type="number" id="tamanio-${contadorPresentaciones}" name="presentaciones[${index}][tamano]" step="0.01" min="0.01" required placeholder="500">
                    </div>
                    
                    <div class="presentacion-input-group">
                        <label for="unidad-${contadorPresentaciones}">Unidad*</label>
                        <select id="unidad-${contadorPresentaciones}" name="presentaciones[${index}][unidad]" required>
                            <option value="">Seleccionar</option>
                            <option value="g">Gramos (g)</option>
                            <option value="kg">Kilogramos (kg)</option>
                            <option value="ml">Mililitros (ml)</option>
                            <option value="L">Litros (L)</option>
                            <option value="cm">Centímetros (cm)</option>
                            <option value="in">Pulgadas (in)</option>
                            <option value="unidad">Unidad</option>
                            <option value="par">Par</option>
                            <option value="docena">Docena</option>
                            <option value="otro">Otro</option>
                        </select>
                    </div>
                    
                    <div class="presentacion-input-group">
                        <label for="precio-${contadorPresentaciones}">Precio*</label>
                        <div class="input-group">
                            <span class="currency-symbol">$</span>
                            <input type="number" id="precio-${contadorPresentaciones}" name="presentaciones[${index}][precio]" step="0.01" min="0.01" required placeholder="0.00">
                        </div>
                    </div>
                    
                    <div class="presentacion-input-group">
                        <label for="stock-${contadorPresentaciones}">Stock*</label>
                        <input type="number" id="stock-${contadorPresentaciones}" name="presentaciones[${index}][stock]" min="1" required placeholder="Cantidad">
                    </div>
                    
                    <div class="presentacion-input-group presentacion-imagen">
                        <label for="imagen-${contadorPresentaciones}">Imagen de esta presentación*</label>
                        <input type="file" id="imagen-${contadorPresentaciones}" name="presentaciones[${index}][imagen]" accept="image/*" onchange="previsualizarPresentacion(${contadorPresentaciones}, event)">
                        <div id="preview-presentacion-${contadorPresentaciones}" class="presentacion-preview"></div>
                    </div>
                    
                    <div class="dimensiones-opcionales">
                        <div class="presentacion-input-group">
                            <label for="largo-${contadorPresentaciones}">Largo</label>
                            <input type="number" id="largo-${contadorPresentaciones}" name="presentaciones[${index}][largo]" step="0.01" min="0" placeholder="0">
                        </div>
                        <div class="presentacion-input-group">
                            <label for="ancho-${contadorPresentaciones}">Ancho</label>
                            <input type="number" id="ancho-${contadorPresentaciones}" name="presentaciones[${index}][ancho]" step="0.01" min="0" placeholder="0">
                        </div>
                        <div class="presentacion-input-group">
                            <label for="alto-${contadorPresentaciones}">Alto</label>
                            <input type="number" id="alto-${contadorPresentaciones}" name="presentaciones[${index}][alto]" step="0.01" min="0" placeholder="0">
                        </div>
                        <div class="presentacion-input-group">
                            <label for="unidad-dim-${contadorPresentaciones}">Unidad dim.</label>
                            <select id="unidad-dim-${contadorPresentaciones}" name="presentaciones[${index}][unidad_dimension]">
                                <option value="cm">cm</option>
                                <option value="in">in</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        container.insertAdjacentHTML('beforeend', presentacionHTML);
        renombrarIndicesPresentaciones();
    }

    // Función para eliminar una presentación
    function eliminarPresentacion(id) {
        const presentacion = document.getElementById(`presentacion-${id}`);
        if (presentacion) {
            presentacion.remove();
            renombrarIndicesPresentaciones();
        }
    }

    // Función para renombrar los índices de los name de las presentaciones para que sean consecutivos
    function renombrarIndicesPresentaciones() {
        const presentaciones = document.querySelectorAll('.presentacion-item');
        presentaciones.forEach((presentacion, index) => {
            // Cambiar el título
            const titulo = presentacion.querySelector('.presentacion-titulo');
            if (titulo) {
                titulo.textContent = `Presentación ${index + 1}`;
            }
            // Cambiar los name de todos los inputs/selects
            const campos = presentacion.querySelectorAll('input, select');
            campos.forEach(campo => {
                if (campo.name) {
                    campo.name = campo.name.replace(/presentaciones\[\d+\]/, `presentaciones[${index}]`);
                }
            });
        });
    }

    // Función para previsualizar imagen de presentación
    function previsualizarPresentacion(id, event) {
        const input = event.target;
        const previewContainer = document.getElementById(`preview-presentacion-${id}`);
        previewContainer.innerHTML = '';

        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function (e) {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.alt = 'Vista previa de la presentación';
                previewContainer.appendChild(img);
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    // Función para validar presentaciones
    function validarPresentaciones() {
        const presentaciones = document.querySelectorAll('.presentacion-item');
        const errorElement = document.getElementById('errorPresentaciones');
        
        if (presentaciones.length === 0) {
            errorElement.textContent = 'Debe agregar al menos una presentación del producto.';
            return false;
        }
        
        let hayErrores = false;
        errorElement.textContent = '';
        
        presentaciones.forEach((presentacion, index) => {
            const tamaño = presentacion.querySelector('input[name*="[tamano]"]').value;
            const unidad = presentacion.querySelector('select[name*="[unidad]"]').value;
            const precio = presentacion.querySelector('input[name*="[precio]"]').value;
            const stock = presentacion.querySelector('input[name*="[stock]"]').value;
            const imagenInput = presentacion.querySelector('input[type="file"]');
            const imagen = imagenInput.files[0];
            const preview = presentacion.querySelector('.presentacion-preview');
            const tieneImagenPrevia = preview && preview.querySelector('img');

            // Log de depuración
            console.log(`Presentación ${index + 1}: tamaño=${tamaño}, unidad=${unidad}, precio=${precio}, stock=${stock}, imagen=${!!imagen}, tieneImagenPrevia=${!!tieneImagenPrevia}`);

            // Validación: en edición, la imagen solo es obligatoria si no hay previa ni nueva
            if (!tamaño || !unidad || !precio || !stock || (!imagen && !tieneImagenPrevia)) {
                errorElement.textContent = `Complete todos los campos obligatorios de la presentación ${index + 1}.`;
                hayErrores = true;
            }
        });
        
        return !hayErrores;
    }

    // Función para limpiar presentaciones al cerrar modal
    function limpiarPresentaciones() {
        const container = document.getElementById('presentaciones-container');
        container.innerHTML = '';
        contadorPresentaciones = 0;
        document.getElementById('errorPresentaciones').textContent = '';
    }

    function showToast(message, type = 'success') {
        const toast = document.getElementById('toast-notification');
        toast.textContent = '';
        toast.style.display = 'flex';
        toast.style.justifyContent = 'center';
        toast.style.alignItems = 'center';
        toast.style.opacity = '1';
        toast.style.pointerEvents = 'auto';
        if (type === 'success') {
            toast.style.background = 'linear-gradient(90deg,#FFAE00 0%,#FF6B00 100%)';
            toast.style.color = '#fff';
            toast.innerHTML = '<span style="font-size:1.4em;">✅</span> ' + message;
        } else {
            toast.style.background = '#DB504A';
            toast.style.color = '#fff';
            toast.innerHTML = '<span style="font-size:1.4em;">❌</span> ' + message;
        }
        setTimeout(() => {
            toast.style.opacity = '0';
            toast.style.pointerEvents = 'none';
        }, 3000);
        setTimeout(() => {
            toast.style.display = 'none';
        }, 3400);
    }

    // Nueva función para editar producto por id (carga datos completos del backend)
    function editarProductoPorId(id_producto) {
        const formData = new FormData();
        formData.append('getProductoConPresentaciones', 'ok');
        formData.append('id_producto', id_producto);

        fetch('../../controllers/php/productoControl.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.producto) {
                abrirModalEdicion(data.producto);
            } else {
                alert('No se pudo cargar el producto para editar');
            }
        })
        .catch(error => {
            alert('Error al cargar el producto para editar');
            console.error(error);
        });
    }
    </script>

    <script src="../../public/js/barraprove.js.js"></script>
    <script type="module" src="https://cdn.jsdelivr.net/npm/@ionic/core/dist/ionic/ionic.esm.js"></script>
    <script nomodule src="https://cdn.jsdelivr.net/npm/@ionic/core/dist/ionic/ionic.js"></script>
</body>
</html>