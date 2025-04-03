<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="../../public/Estilos/bootstrap.min.css">
    <link rel="stylesheet" href="js/bootstrap.bundle.js">
    <title>Panel de Administrador</title>
    <!--Agregar unos estilos bien belicos-->
    <link rel="stylesheet" href="../../public/Estilos/estilosc.css" />

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
                    <input type="text" placeholder="Busca aqui">
                    <ion-icon name="search-outline"></ion-icon>
                </label>
            </div>

            <div>
                <button id="modo-visual-btn" class="modo-visual-btn rounded-pill"><ion-icon name="moon-outline"></ion-icon></button>
            </div>

            <div class="user">

                <img src="../../public/imagenes/cucu.jpg" alt="" id="profileImage">

            </div>
        </div>

          <!--Pedidos recientes-->
          <div class="details">
            <div class="recentOrders">
                <div class="cardHeader">
                    <h2>Usuarios recientes</h2>
                    <a href="#" class="btn">Todas las vistas</a>
                </div>
                <br>
                <br>

                <table>
                    <thead>
                        <tr>
                            <td>Perfil</td>
                            <td>Nombre</td>
                            <td>Correo</td>
                            <td>Telefono</td>
                            <td>Estado</td>

                        </tr>
                    </thead>

                    <tbody>
                        <tr>
                            <td width="100px">
                                <div class="user3">
                                    <img src="../../public/imagenes/siuu.jpg" alt="">
                                </div>
                            </td>
                            <td>Juan Esteban Castañeda Ortiz</td>
                            <td>juanestebanstt@gmail.com</td>
                            <td>3146889521</td>
                            <td><span class="status delivered">Activo</span></td>
                        </tr>

                        <tr>
                            <td width="100px">
                                <div class="user3">
                                    <img src="../../public/imagenes/cucu.jpg" alt="">
                                </div>
                            </td>
                            <td>Yehimy Daniela Velandia Fagua</td>
                            <td>danyyemi@gmail.com</td>
                            <td>3456551209</td>
                            <td><span class="status pending">Registrado</span></td>
                        </tr>

                        <tr>
                            <td width="100px">
                                <div class="user3">
                                    <img src="../../public/imagenes/perfil.jpg" alt="">
                                </div>
                            </td>
                            <td>Cristian Daniel Aguilar Molano</td>
                            <td>crisda00@gmai.com</td>
                            <td>3124281257</td>
                            <td><span class="status pending">Registrado</span></td>
                        </tr>

                        <tr>
                            <td width="100px">
                                <div class="user3">
                                    <img src="../../public/imagenes/perfil.png" alt="">
                                </div>
                            </td>
                            <td>Silvia Daniela Gonzales Perez</td>
                            <td>silvydany@gmail.com</td>
                            <td>3105882367</td>
                            <td><span class="status inProgress">En proceso</span></td>
                        </tr>

                        <tr>
                            <td width="60px">
                                <div class="user3">
                                    <img src="../../public/imagenes/mm.jpg" alt="">
                                </div>
                            </td>
                            <td>Maria Guadalupe Patiño Alcacerzer</td>
                            <td>guadalupe2006@gmail.com</td>
                            <td>3102288812</td>
                            <td><span class="status delivered">Activo</span></td>
                        </tr>

                        <tr>
                            <td width="60px">
                                <div class="user3">
                                    <img src="../../public/imagenes/nn.jpg" alt="">
                                </div>
                            </td>
                            <td>Rafael Leopoldo Perez Garcia</td>
                            <td>skibidirafa@gmail.com</td>
                            <td>3147345910</td>
                            <td><span class="status pending">Registrado</span></td>
                        </tr>

                        <tr>
                            <td width="60px">
                                <div class="user3">
                                    <img src="../../public/imagenes/ll.jpg" alt="">
                                </div>
                            </td>
                            <td>Samuel Sebastian Villamil Velandia</td>
                            <td>papasotesamuel@gmail.com</td>
                            <td>3506140678</td>
                            <td><span class="status return">Inactivo</span></td>
                        </tr>

                        <tr>
                            <td width="60px">
                                <div class="user3">
                                    <img src="../../public/imagenes/shopping (7).webp" alt="">
                                </div>
                            </td>
                            <td>David Leonardo Pedraza Bello</td>
                            <td>davidleo@gmail.com</td>
                            <td>33419906523</td>
                            <td><span class="status delivered">Activo</span></td>
                        </tr>
                    </tbody>
                </table>
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