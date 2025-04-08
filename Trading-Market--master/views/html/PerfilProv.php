<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil - Trading Market</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="../../public/Estilos/Admins-provedor.css">
    <link rel="stylesheet" href="../../public/Estilos/PerfilProv.css">
    <link rel="stylesheet" href="../../public/Estilos/prove_estilos.css" />
</head>
<body>

<?php
    require '../../controllers/php/barra_prove.php'; 
?>
    
    <section id="content">
        <nav>
            <a href="#" class="nav-link">Mi Perfil</a>
            <form action="#">
                <div class="form-input">
                    <input type="search" placeholder="Buscar...">
                    <button type="submit" class="search-btn"><i class='bx bx-search'></i></button>
                </div>
            </form>
            <a href="#" class="notification">
                <i class='bx bxs-bell'></i>
                <span class="num">3</span>
            </a>
        </nav>

        <main class="profile-main">
            <div class="profile-card">
                <div class="profile-header">
                    <img id="profile-avatar" src="http://ssl.gstatic.com/accounts/ui/avatar_2x.png" class="profile-avatar" alt="avatar">
                    <form id="uploadForm" enctype="multipart/form-data">
                        <input type="file" class="profile-upload" id="profileImageInput" name="profileImage" accept="image/*" onchange="previewImage(event)">
                        <button type="button" onclick="uploadImage()">Guardar Imagen</button>
                    </form>
                    <h3>Datos del Proveedor</h3>
                    <p id="imagenMensaje" style="color: red; font-size: 14px; display: none;">Agregue una imagen a su perfil.</p>
                </div>

                <div class="profile-stats">
                    <div class="stat-item">
                        <i class='bx bx-package'></i>
                        <span id="pedidos">0 Pedidos</span>
                    </div>
                    <div class="stat-item">
                        <i class='bx bx-check-circle'></i>
                        <span id="entregas">0 Entregas</span>
                    </div>
                </div>

                <form class="profile-form">
                    <div class="form-group">
                        <label>Nombre</label>
                        <input type="text" id="nombre" placeholder="Nombre" disabled>
                    </div>
                    <div class="form-group">
                        <label>Apellidos</label>
                        <input type="text" id="apellidos" placeholder="Apellidos" disabled>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" id="email" placeholder="Email" disabled>
                    </div>
                    <div class="form-group">
                        <label>Número de documento</label>
                        <input type="text" id="documento" placeholder="Número de documento" disabled>
                    </div>
                </form>
            </div>
        </main>
    </section>

    <script src="../../public/js/barraprove.js.js"></script>
    <script type="module" src="https://cdn.jsdelivr.net/npm/@ionic/core/dist/ionic/ionic.esm.js"></script>
        <script nomodule src="https://cdn.jsdelivr.net/npm/@ionic/core/dist/ionic/ionic.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            fetch('../../controllers/php/obtener_perfil.php')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('nombre').value = data.usuario.nombre || 'No disponible';
                        document.getElementById('apellidos').value = data.usuario.apellido || 'No disponible';
                        document.getElementById('email').value = data.usuario.email || 'No disponible';
                        document.getElementById('documento').value = data.usuario.documento || 'No disponible';

                        const avatar = document.getElementById('profile-avatar');
                        const mensaje = document.getElementById('imagenMensaje');

                        avatar.src = data.usuario.imagen; // Asignar la imagen desde la respuesta
                        mensaje.style.display = data.usuario.imagen.includes('avatar_2x.png') ? 'block' : 'none';
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

            fetch('../../controllers/php/guardar_imagen.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Imagen guardada exitosamente.');
                    location.reload(); // Recargar la página para reflejar los cambios
                } else {
                    alert('Error al guardar la imagen: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Ocurrió un error al guardar la imagen.');
            });
        }
    </script>
</body>
</html>