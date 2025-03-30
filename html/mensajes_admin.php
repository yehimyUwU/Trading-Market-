<!DOCTYPE html>
<html lang="es">
    <head>

        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <link rel="stylesheet" href="js/bootstrap.bundle.js">
        <title>Panel de Administrador</title>
        <!--Agregar unos estilos bien belicos-->
        <link rel="stylesheet" href="../Estilos/estilos_mensajes.css" />
    
    </head>
<body>

<?php
require '../php/barra_admin.php'; 
?>



    <div class="main">
        <div class="topbar">
            <div class="toggle">
                <ion-icon name="menu-outline"></ion-icon>
            </div>

            <div class="search">
                <label>
                    <input type="text" placeholder="Busca aqui">
                    <ion-icon name="search-outline"></ion-icon>
                </label>
            </div>

            <div>
                <button id="modo-visual-btn" class="modo-visual-btn rounded-pill"><ion-icon name="moon-outline"></ion-icon></button>
            </div>

            <div class="user">

                <img src="../imagenes/cucu.jpg" alt="" id="profileImage">

            </div>
        </div>
        <br>
        

        <!-- Tabla de Mensajes -->
        <div class="tabla-mensajes">
            <table>
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Correo</th>
                        <th>Mensaje</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Juan Esteban</td>
                        <td>juan.esteban@gmail.com</td>
                        <td>Usted es un sapo hermano</td>
                        <td>
                            <button class="btn-responder" onclick="responderMensaje('Juan Esteban', 'juan.esteban@gmail.com')">Responder</button>
                        </td>
                    </tr>
                    <tr>
                        <td>Daniela Fagua</td>
                        <td>daniela.fagua@yahoo.com</td>
                        <td>Necesito ayuda con el pago.</td>
                        <td>
                            <button class="btn-responder" onclick="responderMensaje('Daniela Fagua', 'daniela.fagua@yahoo.com')">Responder</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Formulario para Responder -->
        <div class="formulario-respuesta">
            <h2>Responder Mensaje</h2>
            <form id="respuesta-form">
                <label for="nombre">Para:</label>
                <input type="text" id="nombre" name="nombre" readonly>
                
                <label for="correo">Correo:</label>
                <input type="email" id="correo" name="correo" readonly>
                
                <label for="respuesta">Mensaje:</label>
                <textarea id="respuesta" name="respuesta" rows="5" placeholder="Escribe tu respuesta aquí"></textarea>
                
                <div class="botones">
                    <button type="submit" class="btn-enviar">Enviar</button>
                    <button type="button" id="btn-limpiar" class="btn-limpiar">Limpiar</button>
                </div>
            </form>
        </div>
        
    </div>

<!--Modal para ver perfil-->  


    <div id="profileModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Perfil de usuario</h2>
            <div class="user2">
                <img src="../imagenes/cucu.jpg" alt="">
            </div>

        <!-- Formularios de entrada -->
            <form id="profileForm">
                <label for="name">Nombre:</label>
                <input type="text" id="name" name="name" value="Cucurrella" disabled>

                <label for="name">Apellido:</label>
                <input type="text" id="name" name="name" value="Skibidi" disabled>

                <label for="name">Documento:</label>
                <input type="text" id="name" name="name" value="105678382" disabled>
            
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="Paella@gmail.com" disabled>
            
                <label for="phone">Fecha de nacimiento:</label>
                <input type="tel" id="phone" name="phone" value="21/09/1900" disabled>
            
                <label for="role">Genero:</label>
                <input type="text" id="role" name="role" value="Masculino" disabled>
                <br>
                <br>
            
                <button type="button" id="editButton">Editar</button> <!-- Botón Editar -->
                <button type="submit" id="saveButton">Guardar</button> <!-- Botón Guardar -->
            </form>
            </form>
        </div>
        </div>
  

    

    <!--Scripts bien gotys-->
    <script src="js/admin.js"></script>
    <script src="js/mensajes_admin.js"></script>
    <script src="js/config.js"></script>
    <!--script para poder usar iconos bien bellaquitos // ionicons-->
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>
</html>