<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="../../public/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="../../public/estilos/estilos_ayuda.css">
        <title>Panel de Administrador</title>
    </head>
<body>

<?php
require '../../controllers/php/barra_admin.php'; 
?>
    <div class="main">
        <div class="topbar">
            <div class="toggle">
                <ion-icon name="menu-outline"></ion-icon>
            </div>
            <div class="search">
                <label>
                    <input type="text" placeholder="Busca aquí">
                    <ion-icon name="search-outline"></ion-icon>
                </label>
            </div>
            <div>
                <button id="modo-visual-btn" class="modo-visual-btn rounded-pill">
                    <ion-icon name="moon-outline"></ion-icon>
                </button>
            </div>
            <div class="user">
                <img src="../../public/imagenes/cucu.jpg" alt="" id="profileImage">
            </div>
        </div>
    
        <!-- Contenido del apartado de ayuda -->
        <div class="help-container">
            <h2>Centro de Ayuda</h2>
            <p>Selecciona un tema o explora los recursos disponibles para resolver tus dudas.</p>
            
            <div class="help-cards">
                <!-- Tarjeta de ayuda interactiva -->
                <div class="help-card interactive">
                    <div class="card-header">
                        <ion-icon name="help-circle-outline"></ion-icon>
                        <h3>Asistente Virtual</h3>
                    </div>
                    <p>Chatea con nuestro asistente para resolver tus preguntas de manera instantánea.</p>
                    <button class="btn-action">Abrir Chat</button>
                </div>
    
                <!-- Tarjeta de búsqueda visual -->
                <div class="help-card visual-search">
                    <div class="card-header">
                        <ion-icon name="search-outline"></ion-icon>
                        <h3>Búsqueda Inteligente</h3>
                    </div>
                    <p>Busca respuestas rápidas usando palabras clave o selecciona preguntas sugeridas.</p>
                    <div class="search-tags">
                        <button>#Transacciones</button>
                        <button>#Mensajes</button>
                        <button>#Problemas</button>
                    </div>
                </div>
    
                <!-- Tarjeta de video tutorial -->
                <div class="help-card video-tutorial">
                    <div class="card-header">
                        <ion-icon name="videocam-outline"></ion-icon>
                        <h3>Tutoriales en Video</h3>
                    </div>
                    <p>Accede a videos explicativos sobre las principales funciones de la plataforma.</p>
                    <button class="btn-action">Ver Videos</button>
                </div>
    
                <!-- Tarjeta de soporte personalizado -->
                <div class="help-card personalized">
                    <div class="card-header">
                        <ion-icon name="chatbubbles-outline"></ion-icon>
                        <h3>Soporte Personalizado</h3>
                    </div>
                    <p>Conecta con un agente en vivo para resolver tus problemas específicos.</p>
                    <button class="btn-action">Hablar con un Agente</button>
                </div>
            </div>
        </div>
    </div>

<!--Modal para ver perfil-->

    <div id="profileModal" class="modal">
        <div class="modal-content">
          <span class="close">&times;</span>
          <h2>Perfil de usuario</h2>
          <div class="user2">
              <img src="../../public/imagenes/cucu.jpg" alt="">
          </div>
          
          <!-- Formularios de entrada -->
          <form id="profileForm">
          <label for="name">Nombre:</label>
          <input type="text" id="name" name="name" value="" disabled>

           <label for="lastname">Apellido:</label>
           <input type="text" id="lastname" name="lastname" value="" disabled>

           <label for="document">Documento:</label>
           <input type="text" id="document" name="document" value="" disabled>

           <label for="email">Email:</label>
           <input type="email" id="email" name="email" value="" disabled>

           <label for="birthdate">Fecha de nacimiento:</label>
           <input type="text" id="birthdate" name="birthdate" value="" disabled>

           <label for="gender">Género:</label>
           <input type="text" id="gender" name="gender" value="" disabled>
              <br>
              <br>
            
              <button type="button" id="editButton">Editar</button> <!-- Botón Editar -->
              <button type="submit" id="saveButton">Guardar</button> <!-- Botón Guardar -->
            </form>
          </form>
        </div>
      </div>
    
    

        <!--Scripts bien gotys-->
    <script src="../../public/js/admin.js"></script>
    <script src="../../public/js/config.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>
</html>