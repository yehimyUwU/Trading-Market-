// Configuración de las subcategorías
const subcategorias = {
    'cosmeticos': ['Maquillaje facial', 'Maquillaje de ojos', 'Maquillaje de labios', 'Esmaltes de uñas', 'Productos para cejas', 'Bases y correctores', 'Polvos y rubores'],
    'cuidadoPersonal': ['Cremas corporales', 'Productos capilares', 'Productos faciales', 'Desodorantes', 'Jabones', 'Protección solar', 'Higiene bucal'],
    'productosNaturales': ['Aceites esenciales', 'Cremas naturales', 'Productos orgánicos', 'Hierbas medicinales', 'Suplementos naturales', 'Productos veganos', 'Productos biodegradables'],
    'accesorios': ['Brochas de maquillaje', 'Esponjas y aplicadores', 'Neceseres y organizadores', 'Espejos', 'Limas y cortaúñas', 'Pinzas y tijeras', 'Accesorios para el cabello']
};

// Configuración de la imagen
const CONFIG_IMAGEN = {
    FORMATOS_PERMITIDOS: ['image/png'],
 //   TAMANO_MAXIMO: 2 * 1024 * 1024, // 2MB en bytes
 //   ANCHO_MAXIMO: 1200,
 //   ALTO_MAXIMO: 1200
};

// Inicializar los manejadores de eventos cuando el DOM esté cargado
document.addEventListener('DOMContentLoaded', function() {
    // Primero verificamos que existan los elementos necesarios
    const formulario = document.getElementById('formularioProducto');
    const categoriaSelect = document.getElementById('categoria');
    const subcategoriaSelect = document.getElementById('subcategoria');

    if (!formulario) {
        console.error('No se encontró el formulario');
        return;
    }

    if (!categoriaSelect) {
        console.error('No se encontró el select de categorías');
        return;
    }

    if (!subcategoriaSelect) {
        console.error('No se encontró el select de subcategorías');
        return;
    }

    // Cargar categorías al inicio
    cargarCategorias();

    // Event listener para el cambio de categoría
    categoriaSelect.addEventListener('change', function() {
        cargarSubcategorias(this.value);
    });

    // Event listener para el formulario
    formulario.addEventListener('submit', function(e) {
        e.preventDefault();
        subirProducto(this);
    });
});

function cargarCategorias() {
    fetch('../php/obtener_categorias.php')
        .then(response => response.json())
        .then(categorias => {
            const select = document.getElementById('categoria');
            if (select) {
                select.innerHTML = '<option value="">Seleccione una categoría</option>';
                categorias.forEach(categoria => {
                    select.innerHTML += `<option value="${categoria.id_categoria}">${categoria.nombre}</option>`;
                });
            }
        })
        .catch(error => {
            console.error('Error al cargar categorías:', error);
        });
}

function cargarSubcategorias(categoriaId) {
    if (!categoriaId) return;
    
    fetch(`../php/obtener_subcategorias.php?categoria_id=${categoriaId}`)
        .then(response => response.json())
        .then(subcategorias => {
            const select = document.getElementById('subcategoria');
            if (select) {
                select.innerHTML = '<option value="">Seleccione una subcategoría</option>';
                subcategorias.forEach(sub => {
                    select.innerHTML += `<option value="${sub.id_subcategoria}">${sub.nombre}</option>`;
                });
            }
        })
        .catch(error => {
            console.error('Error al cargar subcategorías:', error);
        });
}

function subirProducto(formulario) {
    const formData = new FormData(formulario);
    
    fetch('../php/subir-producto.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Producto guardado exitosamente');
            formulario.reset();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error al guardar el producto');
    });
}

// Función para actualizar subcategorías
function actualizarSubcategorias() {
    const categoriaGeneral = document.getElementById('categoriaGeneral').value;
    const subcategoriaSelect = document.getElementById('subcategoria');
    subcategoriaSelect.innerHTML = '<option value="">Seleccione una subcategoría</option>';

    if (categoriaGeneral && subcategorias[categoriaGeneral]) {
        subcategorias[categoriaGeneral].forEach(sub => {
            const option = document.createElement('option');
            option.value = sub.toLowerCase().replace(/ /g, '_');
            option.textContent = sub;
            subcategoriaSelect.appendChild(option);
        });
        subcategoriaSelect.disabled = false;
    } else {
        subcategoriaSelect.disabled = true;
    }
}

// Función para manejar la selección de imagen
async function manejarSeleccionImagen(event) {
    const file = event.target.files[0];
    const errorImagen = document.getElementById('errorImagen');
    const previewImagen = document.getElementById('previewImagen');
    
    errorImagen.style.display = 'none';
    previewImagen.innerHTML = '';
    
    if (!file) return;
    
    // Validar formato
    if (!CONFIG_IMAGEN.FORMATOS_PERMITIDOS.includes(file.type)) {
        mostrarError(errorImagen, 'El formato de imagen debe ser JPG o PNG');
        event.target.value = '';
        return;
    }
    
    // Validar tamaño
    if (file.size > CONFIG_IMAGEN.TAMANO_MAXIMO) {
        mostrarError(errorImagen, `El archivo es demasiado grande. El tamaño máximo es ${CONFIG_IMAGEN.TAMANO_MAXIMO / (1024 * 1024)}MB`);
        event.target.value = '';
        return;
    }
    
    // Mostrar vista previa
    const reader = new FileReader();
    reader.onload = function(e) {
        const img = document.createElement('img');
        img.src = e.target.result;
        img.onload = function() {
            if (this.width > CONFIG_IMAGEN.ANCHO_MAXIMO || this.height > CONFIG_IMAGEN.ALTO_MAXIMO) {
                mostrarError(errorImagen, `La imagen no debe exceder ${CONFIG_IMAGEN.ANCHO_MAXIMO}x${CONFIG_IMAGEN.ALTO_MAXIMO} píxeles`);
                event.target.value = '';
                return;
            }
            previewImagen.appendChild(img);
            actualizarVistaPrevia();
        };
    };
    reader.readAsDataURL(file);
}

// Función para manejar el envío del formulario
async function manejarEnvioFormulario(event) {
    event.preventDefault();
    
    // Limpiar mensajes de error previos
    document.querySelectorAll('.error-mensaje').forEach(el => el.style.display = 'none');
    
    // Validar campos
    const validaciones = validarCampos();
    if (!validaciones.esValido) {
        validaciones.errores.forEach(({campo, mensaje}) => {
            mostrarError(document.getElementById(`error${campo}`), mensaje);
        });
            return;
        }

    // Preparar datos para envío
    const formData = new FormData(event.target);
    
    try {
        // Mostrar indicador de carga
        const btnSubmit = event.target.querySelector('button[type="submit"]');
        const btnTextoOriginal = btnSubmit.innerHTML;
        btnSubmit.disabled = true;
        btnSubmit.innerHTML = '<span class="material-symbols-outlined">hourglass_empty</span> Publicando...';

        const response = await fetch('../php/subir-producto.php', {
            method: 'POST',
            body: formData
        });

        const data = await response.json();
        
        if (data.success) {
            mostrarNotificacion('¡Producto publicado exitosamente!', 'success');
            cerrarModal();
            // Opcional: recargar lista de productos
            cargarProductos();
        } else {
            throw new Error(data.message || 'Error al publicar el producto');
        }
    } catch (error) {
        mostrarNotificacion(error.message, 'error');
    } finally {
        btnSubmit.disabled = false;
        btnSubmit.innerHTML = btnTextoOriginal;
    }
}

// Función para validar campos
function validarCampos() {
    const errores = [];
    const campos = {
        'nombreProducto': {
            valor: document.getElementById('nombreProducto').value.trim(),
            reglas: {
                requerido: true,
                minLength: 3,
                maxLength: 100
            }
        },
        'descripcion': {
            valor: document.getElementById('descripcion').value.trim(),
            reglas: {
                requerido: true,
                minLength: 10,
                maxLength: 500
            }
        },
        'precio': {
            valor: document.getElementById('precio').value,
            reglas: {
                requerido: true,
                min: 0.01
            }
        },
        'stock': {
            valor: document.getElementById('stock').value,
            reglas: {
                requerido: true,
                min: 1
            }
        }
    };
    
    // Validar cada campo
    Object.entries(campos).forEach(([campo, config]) => {
        if (config.reglas.requerido && !config.valor) {
            errores.push({campo, mensaje: 'Este campo es requerido'});
        } else if (config.valor) {
            if (config.reglas.minLength && config.valor.length < config.reglas.minLength) {
                errores.push({campo, mensaje: `Debe tener al menos ${config.reglas.minLength} caracteres`});
            }
            if (config.reglas.maxLength && config.valor.length > config.reglas.maxLength) {
                errores.push({campo, mensaje: `No debe exceder ${config.reglas.maxLength} caracteres`});
            }
            if (config.reglas.min && parseFloat(config.valor) < config.reglas.min) {
                errores.push({campo, mensaje: `Debe ser mayor a ${config.reglas.min}`});
            }
        }
    });
    
    return {
        esValido: errores.length === 0,
        errores
    };
}

// Función para mostrar errores
function mostrarError(elemento, mensaje) {
    elemento.textContent = mensaje;
    elemento.style.display = 'block';
}

// Función para mostrar notificaciones
function mostrarNotificacion(mensaje, tipo) {
    const notificacion = document.createElement('div');
    notificacion.className = `notificacion ${tipo}`;
    notificacion.textContent = mensaje;
    
    document.body.appendChild(notificacion);
    
    setTimeout(() => {
        notificacion.classList.add('mostrar');
        setTimeout(() => {
            notificacion.classList.remove('mostrar');
            setTimeout(() => {
                document.body.removeChild(notificacion);
            }, 300);
        }, 3000);
    }, 100);
}

// Función para actualizar la vista previa
function actualizarVistaPrevia() {
    const previewProducto = document.getElementById('previewProducto');
    const previewContenido = document.getElementById('previewContenido');
    
    if (!previewProducto || !previewContenido) return;
    
    const nombre = document.getElementById('nombreProducto').value || 'Nombre del Producto';
    const precio = document.getElementById('precio').value || '0.00';
    const imagenSrc = document.querySelector('#previewImagen img')?.src || '';
    
    previewProducto.style.display = 'block';
    previewContenido.innerHTML = `
        <div class="producto-card">
            <div class="producto-imagen">
                ${imagenSrc ? `<img src="${imagenSrc}" alt="${nombre}">` : '<div class="sin-imagen">Sin imagen</div>'}
            </div>
            <div class="producto-info">
                <h4>${nombre}</h4>
                <div class="producto-detalles">
                    <span class="precio">$${precio}</span>
                </div>
            </div>
        </div>
    `;
}

// Función para cerrar el modal
function cerrarModal() {
    const modal = document.getElementById('modalProducto');
    if (modal) {
        modal.style.display = 'none';
        document.getElementById('formularioProducto').reset();
        document.getElementById('previewImagen').innerHTML = '';
        document.getElementById('previewProducto').style.display = 'none';
        document.querySelectorAll('.error-mensaje').forEach(el => el.style.display = 'none');
    }
}

// Función para cargar productos
async function cargarProductos(busqueda = '', categoria = '', orden = 'reciente') {
    try {
        const params = new URLSearchParams({
            busqueda: busqueda,
            categoria: categoria,
            orden: orden
        });

        const response = await fetch(`../php/obtener-productos.php?${params}`);
        const data = await response.json();

        if (data.success) {
            mostrarProductos(data.productos);
        } else {
            throw new Error(data.message || 'Error al cargar los productos');
        }
    } catch (error) {
        mostrarNotificacion(error.message, 'error');
    }
}

// Función para mostrar productos en la interfaz
function mostrarProductos(productos) {
    const productosGrid = document.getElementById('productosGrid');
    productosGrid.innerHTML = '';

    if (productos.length === 0) {
        productosGrid.innerHTML = `
            <div class="no-productos">
                <span class="material-symbols-outlined">inventory_2</span>
                <p>No se encontraron productos</p>
            </div>
        `;
        return;
    }

    productos.forEach(producto => {
        const productoCard = document.createElement('div');
        productoCard.className = 'producto-card';
        productoCard.innerHTML = `
            <div class="producto-imagen">
                <img src="${producto.imagen_url}" alt="${producto.nombre}">
                <div class="producto-acciones">
                    <button class="btn-editar" onclick="editarProducto(${producto.id_producto})">
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
                    <span class="precio">$${producto.precio_formato}</span>
                    <span class="stock">Stock: ${producto.stock}</span>
                </div>
                <div class="producto-estado">
                    <span class="badge ${producto.estado}">${producto.estado}</span>
                </div>
            </div>
        `;
        productosGrid.appendChild(productoCard);
    });
}

// Función de debounce para evitar muchas llamadas seguidas
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
} 
}

function cargarCategorias() {
    fetch('php/obtener_categorias.php')
        .then(response => response.json())
        .then(categorias => {
            const select = document.getElementById('categoria');
            select.innerHTML = '<option value="">Seleccione una categoría</option>';
            categorias.forEach(categoria => {
                select.innerHTML += `<option value="${categoria.id_categoria}">${categoria.nombre}</option>`;
            });
        });
}

function cargarSubcategorias(categoriaId) {
    if (!categoriaId) return;
    
    fetch(`php/obtener_subcategorias.php?categoria_id=${categoriaId}`)
        .then(response => response.json())
        .then(subcategorias => {
            const select = document.getElementById('subcategoria');
            select.innerHTML = '<option value="">Seleccione una subcategoría</option>';
            subcategorias.forEach(sub => {
                select.innerHTML += `<option value="${sub.id_subcategoria}">${sub.nombre}</option>`;
            });
        });
}

function subirProducto(formulario) {
    const formData = new FormData(formulario);
    
    fetch('php/subir-producto.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Producto guardado exitosamente');
            formulario.reset();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error al guardar el producto');
    });
} 