<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="../../public/css/bootstrap.min.css">
    <link rel="stylesheet" href="js/bootstrap.bundle.js">
    <title>Panel de Administrador</title>
    <!--Agregar unos estilos bien belicos-->
    <link rel="stylesheet" href="../../public/Estilos/config_admin.css" />

</head>



<body>
    
<?php
require '../../controllers/php/barra_admin.php'; 
?>


    <!--Contenido principal-->
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
        <br>
        <br>
    
        <!-- Contenido del apartado de configuraciones -->
        <div class="settings-container">
            <h2>Configuraciones Generales</h2>
            <br>
            <br>
    
            <div class="card-section">
                <div class="settings-card">
                    <ion-icon name="person-outline"></ion-icon>
                    <h3>Gestión de Cuentas</h3>
                    <p>Administra los permisos y roles de los usuarios de la plataforma.</p>
                    <button class="btn-action">Gestionar</button>
                </div>
    
                <div class="settings-card">
                    <ion-icon name="options-outline"></ion-icon>
                    <h3>Preferencias de Plataforma</h3>
                    <p>Personaliza las reglas, políticas y el estilo de tu plataforma.</p>
                    <button class="btn-action">Editar</button>
                </div>
    
                <div class="settings-card">
                    <ion-icon name="shield-checkmark-outline"></ion-icon>
                    <h3>Seguridad</h3>
                    <p>Configura medidas como la autenticación en dos pasos.</p>
                    <button class="btn-action">Configurar</button>
                </div>
    
                <div class="settings-card">
                    <ion-icon name="color-palette-outline"></ion-icon>
                    <h3>Modo Visual</h3>
                    <p>Elige entre modo claro u oscuro según tu estilo.</p>
                    <button id="switch-visual-btn" class="modo-visual-btn">Cambiar Modo</button>
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
    <!--script para poder usar iconos bien bellaquitos // ionicons-->
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>
</html>