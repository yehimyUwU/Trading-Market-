<!DOCTYPE html>
<?php
/**
 * Archivo: PerfilProv.php
 * Descripción: Perfil y configuración del proveedor
 * Conexiones:
 * - Se conecta con: controllers/php/barra_prove.php (para la barra de navegación)
 * - Se conecta con: controllers/php/obtener_perfil.php (para datos del perfil)
 * - Se conecta con: controllers/php/guardar_imagen.php (para subir imágenes)
 * Funcionalidades:
 * - Visualización de datos del perfil
 * - Edición de información personal
 * - Subida de imagen de perfil
 * - Estadísticas de ventas
 * - Historial de pedidos
 */
require '../../controllers/php/barra_prove.php'; 
?>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil - Trading Market</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="../../public/Estilos/Admins-provedor.css">
    <link rel="stylesheet" href="../../public/Estilos/prove_estilos.css" />
    <link rel="stylesheet" href="../../public/Estilos/BienvenidoProv.css">
    <link rel="stylesheet" href="../../public/Estilos/barraprove.css.css">
    <link rel="stylesheet" href="../../public/Estilos/PerfilProv.css">
</head>
<body>

    <section id="content">
        
    <main class="container-fluid min-vh-100 d-flex align-items-center justify-content-center" style="background: linear-gradient(120deg, #e0eafc 0%, #cfdef3 100%);padding-top: 0px;">
            <div class="card-profile">
                <div class="profile-header">
                    <img id="profile-avatar" src="../../public/imag/default.jpeg" class="profile-avatar" alt="avatar" onmouseover="this.style.transform='scale(1.07)'" onmouseout="this.style.transform='scale(1)'">
                    <form id="uploadForm" enctype="multipart/form-data" class="d-flex flex-column align-items-center gap-2 mb-2">
                        <input type="file" id="profileImageInput" name="profileImage" accept="image/*" style="display:none" onchange="previewImage(event)">
                        <label for="profileImageInput" class="btn btn-outline-primary btn-sm d-flex align-items-center gap-1">
                            <i class="bx bx-upload"></i> Cambiar imagen
                        </label>
                        <button type="button" class="btn btn-success btn-sm mt-1 px-3" onclick="uploadImage()">
                            <i class="bx bx-save"></i> Guardar Imagen
                        </button>
                        <div id="spinner" class="spinner-border text-primary" style="display:none;" role="status">
                            <span class="visually-hidden">Cargando...</span>
                        </div>
                    </form>
                    <h3 class="fw-bold mb-1" style="color: #306a91;">Proveedor <i class="bx bxs-badge-check text-info" data-bs-toggle="tooltip" title="Verificado"></i></h3>
                </div>
                <p id="imagenMensaje" class="text-danger small text-center" style="display: none;">Agregue una imagen a su perfil.</p>
                <div class="profile-stats mb-3 mt-3">
                    <div class="stat-card">
                        <i class="bx bx-package text-primary" data-bs-toggle="tooltip" title="Pedidos realizados"></i>
                        <div class="fw-bold stat-value" id="pedidos">0</div>
                        <span class="badge bg-primary text-orange">Pedidos</span>
                        <div class="stat-label text-orange">Pedidos</div>
                    </div>
                    <div class="stat-card">
                        <i class="bx bx-check-circle text-success" data-bs-toggle="tooltip" title="Entregas completadas"></i>
                        <div class="fw-bold stat-value" id="entregas">0</div>
                        <span class="badge bg-success text-orange">Entregas</span>
                        <div class="stat-label text-orange">Entregas</div>
                    </div>
                </div>
                <div class="profile-badges mb-3">
                    <span class="badge bg-gradient" style="background: linear-gradient(90deg,#306a91,#4b6584); color: #fff;">Activo</span>
                    <span class="badge bg-warning text-dark">Miembro desde 01/01/2024</span>
                </div>
                <form class="profile-form row g-3">
                    <div class="profile-field col-12">
                        <label class="form-label"><i class="bx bx-user"></i> Nombre</label>
                        <input type="text" id="nombre" class="form-control" placeholder="Nombre" disabled>
                    </div>
                    <div class="profile-field col-12">
                        <label class="form-label"><i class="bx bx-user"></i> Apellidos</label>
                        <input type="text" id="apellidos" class="form-control" placeholder="Apellidos" disabled>
                    </div>
                    <div class="profile-field col-12">
                        <label class="form-label"><i class="bx bx-envelope"></i> Email</label>
                        <input type="email" id="email" class="form-control" placeholder="Email" disabled>
                    </div>
                    <div class="profile-field col-12">
                        <label class="form-label"><i class="bx bx-id-card"></i> Número de documento</label>
                        <input type="text" id="documento" class="form-control" placeholder="Número de documento" disabled>
                    </div>
                </form>
                <div class="profile-actions mt-4">
                    <button class="btn btn-outline-primary btn-sm px-3" data-bs-toggle="modal" data-bs-target="#editarPerfilModal">
                        <i class="bx bx-edit"></i> Editar Perfil
                    </button>
                </div>
            </div>
        </main>
    </section>

    <!-- Modal de edición de perfil (fuera de cualquier contenedor) -->
    <div class="modal fade" id="editarPerfilModal" tabindex="-1" aria-labelledby="editarPerfilLabel" aria-hidden="true">
      <div class="modal-dialog">
        <form id="formEditarPerfil" class="modal-content" style="border-radius: 1.2rem;">
          <div class="modal-header" style="background: linear-gradient(90deg, #ff6b00 0%, #fd7238 100%); color: #fff;">
            <h5 class="modal-title" id="editarPerfilLabel"><i class="bx bx-edit"></i> Editar Perfil</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
          </div>
          <div class="modal-body" style="background: #fff7f0;">
            <div class="mb-3">
              <label for="editNombre" class="form-label" style="color:#ff6b00;">Nombre</label>
              <input type="text" class="form-control" id="editNombre" name="nombre" required style="border: 1.5px solid #ff6b00;">
            </div>
            <div class="mb-3">
              <label for="editApellido" class="form-label" style="color:#ff6b00;">Apellidos</label>
              <input type="text" class="form-control" id="editApellido" name="apellido" required style="border: 1.5px solid #ff6b00;">
            </div>
            <div class="mb-3">
              <label for="editEmail" class="form-label" style="color:#ff6b00;">Email</label>
              <input type="email" class="form-control" id="editEmail" name="email" required style="border: 1.5px solid #ff6b00;">
            </div>
          </div>
          <div class="modal-footer" style="background: #fff7f0;">
            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn" style="background: linear-gradient(90deg, #ff6b00 0%, #fd7238 100%); color: #fff;">Guardar Cambios</button>
          </div>
        </form>
      </div>
    </div>

    <script src="../../public/js/barraprove.js.js"></script>
    <script type="module" src="https://cdn.jsdelivr.net/npm/@ionic/core/dist/ionic/ionic.esm.js"></script>
        <script nomodule src="https://cdn.jsdelivr.net/npm/@ionic/core/dist/ionic/ionic.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            console.log('Cargando perfil...');
            fetch('../../controllers/php/obtener_perfil.php')
                .then(response => response.json())
                .then(data => {
                    console.log('Respuesta de obtener_perfil.php:', data);
                    if (data.success) {
                        document.getElementById('nombre').value = data.usuario.nombre || 'No disponible';
                        document.getElementById('apellidos').value = data.usuario.apellido || 'No disponible';
                        document.getElementById('email').value = data.usuario.email || 'No disponible';
                        document.getElementById('documento').value = data.usuario.documento || 'No disponible';

                        const avatar = document.getElementById('profile-avatar');
                        const mensaje = document.getElementById('imagenMensaje');

                        // Actualizar la imagen del perfil
                        if (data.usuario.imagen) {
                            avatar.src = '../../public/imag/' + data.usuario.imagen.replace('../imag/', '');
                            mensaje.style.display = 'none';
                        } else {
                            avatar.src = '../../public/imag/default.jpeg';
                            mensaje.style.display = 'block';
                        }
                    } else {
                        alert('No se pudo cargar la información del perfil.');
                    }
                })
                .catch(error => {
                    console.error('Error al cargar el perfil:', error);
                    alert('Error al cargar el perfil.');
                });
        });

        // Función para previsualizar la imagen seleccionada
        function previewImage(event) {
            const input = event.target;
            const preview = document.getElementById('profile-avatar');

            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    preview.src = e.target.result;
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        // Función para subir la imagen al servidor
        function uploadImage() {
            const form = document.getElementById('uploadForm');
            const formData = new FormData(form);
            const input = document.getElementById('profileImageInput');
            const spinner = document.getElementById('spinner');
            
            if (!input.files || !input.files[0]) {
                alert('Por favor, seleccione una imagen primero.');
                return;
            }

            spinner.style.display = 'inline-block';
            fetch('../../controllers/php/guardar_imagen.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                spinner.style.display = 'none';
                if (data.success) {
                    const avatar = document.getElementById('profile-avatar');
                    // Mantener la imagen actual en el preview
                    const currentPreview = avatar.src;
                    
                    // Crear una nueva imagen para verificar la carga
                    const newImage = new Image();
                    newImage.onload = function() {
                        // Una vez que la nueva imagen se carga correctamente, actualizamos el avatar
                        avatar.src = '../../public/imag/' + data.imagen;
                        document.getElementById('imagenMensaje').style.display = 'none';
                        alert('Imagen guardada exitosamente.');
                    };
                    newImage.onerror = function() {
                        // Si hay error al cargar la nueva imagen, mantenemos la previsualización
                        avatar.src = currentPreview;
                        alert('La imagen se guardó.');
                    };
                    newImage.src = '../../public/imag/' + data.imagen;
                } else {
                    alert('Error al guardar la imagen: ' + data.message);
                }
            })
            .catch(error => {
                spinner.style.display = 'none';
                console.error('Error:', error);
                alert('Ocurrió un error al guardar la imagen.');
            });
        }

        // Activar tooltips de Bootstrap
        document.addEventListener('DOMContentLoaded', function () {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
            // Hacer que el label de cambiar imagen dispare el input
            document.querySelector('label[for="profileImageInput"]').onclick = function() {
                document.getElementById('profileImageInput').click();
            };
        });

        // Llenar automáticamente los campos del modal con los datos actuales SOLO cuando se hace clic en el botón
        const btnEditar = document.querySelector('[data-bs-target="#editarPerfilModal"]');
        btnEditar.addEventListener('click', function() {
            document.getElementById('editNombre').value = document.getElementById('nombre').value;
            document.getElementById('editApellido').value = document.getElementById('apellidos').value;
            document.getElementById('editEmail').value = document.getElementById('email').value;
        });

        // Enviar el formulario por AJAX
        const formEditar = document.getElementById('formEditarPerfil');
        formEditar.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            fetch('../../controllers/php/actualizar_perfil_cliente.php', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if(data.success){
                    alert('Perfil actualizado correctamente');
                    location.reload();
                } else {
                    alert('Error al actualizar: ' + data.message);
                }
            })
            .catch(() => alert('Error de conexión'));
        });
    </script>
</body>
</html>