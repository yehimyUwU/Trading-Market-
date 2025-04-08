<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../../public/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../public/estilos/estilosAdmin.css" />
    <title>Panel de Administrador</title>

</head>
<body>
<?php
session_start();
$admin = $_SESSION['usuario'] ?? null;
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
        <!--Tarjetas bien bellacas-->
        <div class="cardBox">
            <div class="card">
                <div>
                    <div class="numbers">1.000</div>
                    <div class="cardName">Vistas diarias</div>
                </div>

                <div class="iconBx">
                    <ion-icon name="eye-outline"></ion-icon>
                </div>
            </div>

            <div class="card">
                <div>
                    <div class="numbers">12</div>
                    <div class="cardName">Ventas</div>
                </div>

                <div class="iconBx">
                    <ion-icon name="cart-outline"></ion-icon>
                </div>
            </div>

            <div class="card">
                <div>
                    <div class="numbers">235</div>
                    <div class="cardName">Comentarios</div>
                </div>

                <div class="iconBx">
                    <ion-icon name="chatbubble-outline"></ion-icon>
                </div>
            </div>

            <div class="card">
                <div>
                    <div class="numbers">&4.23</div>
                    <div class="cardName">Ganancias</div>
                </div>

                <div class="iconBx">
                    <ion-icon name="cash-outline"></ion-icon>
                </div>
            </div>
        </div>

        <!--Pedidos recientes-->
        <div class="details">
            <div class="recentOrders">
                <div class="cardHeader">
                    <h2>Pedidos recientes</h2>
                    <a href="#" class="btn">Todas las vistas</a>
                </div>

                <table>
                    <thead>
                        <tr>
                            <td>Nombre</td>
                            <td>Precio</td>
                            <td>Pago</td>
                            <td>Estado</td>

                        </tr>
                    </thead>

                    <tbody>
                        <tr>
                            <td>Computadora Nvidia</td>
                            <td>$19292</td>
                            <td>Pagado</td>
                            <td><span class="status delivered">Entregado</span></td>
                        </tr>

                        <tr>
                            <td>Refrigerador</td>
                            <td>$292</td>
                            <td>Vencido</td>
                            <td><span class="status pending">Pendiente</span></td>
                        </tr>

                        <tr>
                            <td>Chaqueta Adidas</td>
                            <td>$12</td>
                            <td>Pagado</td>
                            <td><span class="status return">Devolucion</span></td>
                        </tr>

                        <tr>
                            <td>Televisor D1</td>
                            <td>$1682</td>
                            <td>Vencido</td>
                            <td><span class="status inProgress">En proceso</span></td>
                        </tr>

                        <tr>
                            <td>Computadora Nvidia</td>
                            <td>$19292</td>
                            <td>Pagado</td>
                            <td><span class="status delivered">Entregado</span></td>
                        </tr>

                        <tr>
                            <td>Refrigerador</td>
                            <td>$292</td>
                            <td>Vencido</td>
                            <td><span class="status pending">Pendiente</span></td>
                        </tr>

                        <tr>
                            <td>Chaqueta Adidas</td>
                            <td>$12</td>
                            <td>Pagado</td>
                            <td><span class="status return">Devolucion</span></td>
                        </tr>

                        <tr>
                            <td>Televisor D1</td>
                            <td>$1682</td>
                            <td>Vencido</td>
                            <td><span class="status inProgress">En proceso</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!--Nuevos usuarios-->
            <div class="recentCustomers">
                <div class="cardHeader">
                    <h2>Usuarios recientcces</h2>
                </div>

                <table>
                    <tr>
                        <td width="60px">
                            <div class="imgBx"><img src="../../public/imagenes/ejem.jpg" alt=""></div>
                        </td>
                        <td>
                            <h4>David <br> <span>Colombia</span></h4>
                        </td>
                    </tr>

                    <tr>
                        <td width ="60px">
                            <div class="imgBx"><img src="../../public/imagenes/ejem.jpg" alt=""></div>
                        </td>
                        <td>
                            <h4>David <br> <span>Colombia</span></h4>
                        </td>
                    </tr>

                    <tr>
                        <td width ="60px">
                            <div class="imgBx"><img src="../../public/imagenes/ejem.jpg" alt=""></div>
                        </td>
                        <td>
                            <h4>David <br> <span>Colombia</span></h4>
                        </td>
                    </tr>

                    <tr>
                        <td width ="60px">
                            <div class="imgBx"><img src="../../public/imagenes/ejem.jpg" alt=""></div>
                        </td>
                        <td>
                            <h4>David <br> <span>Colombia</span></h4>
                        </td>
                    </tr>

                    <tr>
                        <td width ="60px">
                            <div class="imgBx"><img src="../../public/imagenes/ejem.jpg" alt=""></div>
                        </td>
                        <td>
                            <h4>David <br> <span>Colombia</span></h4>
                        </td>
                    </tr>
                    
                </table>
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
              <input type="text" id="name" name="name" value="<?php echo $admin['nombre'] ?? ''; ?>" disabled>

              <label for="lastname">Apellido:</label>
              <input type="text" id="lastname" name="lastname" value="<?php echo $admin['apellido'] ?? ''; ?>" disabled>

              <label for="document">Documento:</label>
              <input type="text" id="document" name="document" value="<?php echo $admin['documento'] ?? ''; ?>" disabled>
            
              <label for="email">Email:</label>
              <input type="email" id="email" name="email" value="<?php echo $admin['email'] ?? ''; ?>" disabled>
            
              <label for="birthdate">Fecha de nacimiento:</label>
              <input type="text" id="birthdate" name="birthdate" value="<?php echo $admin['fecha_nacimiento'] ?? ''; ?>" disabled>
            
              <label for="gender">Género:</label>
              <input type="text" id="gender" name="gender" value="<?php echo $admin['genero'] ?? ''; ?>" disabled>
              <br>
              <br>
            
              <button type="button" id="editButton">Editar</button> <!-- Botón Editar -->
              <button type="submit" id="saveButton">Guardar</button> <!-- Botón Guardar -->
            </form>
          </form>
        </div>
      </div>

      


    <!--Scripts bien gotys-->
    <script src="../../public/js/config.js"></script>
    <script src="../../public/js/admin.js"></script>
    <!--script para poder usar iconos bien bellaquitos // ionicons-->
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>
</html>