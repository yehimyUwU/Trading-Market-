<!DOCTYPE html>
<!-- Coding By CodingNepal - youtube.com/@codingnepal -->
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Ajustes </title>
  <link rel="stylesheet" href="../../public/Estilos//barraprove.css.css">
  <link rel="stylesheet" href="../../public/Estilos//Admins-provedor.css">
  <link rel="stylesheet" href="../../public/Estilos//prove_estilos.css" />
  <link rel="stylesheet" href="../../public/Estilos//estilos_pedidos.css" />

  <!-- Linking Google fonts for icons -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0">
</head>
<body>
  


<?php
    
    require '../../controllers/php/barra_prove.php'; 
?>
  

<!-- CONTENT -->
<section id="content">
  <!-- NAVBAR -->
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

  <!-- MAIN -->
  <main>
   

    <ul class="box-info">
      <li>
        <i class='bx bxs-calendar-check' ></i>
        <span class="text">
          <h3>1020</h3>
          <p>Nuevas ordenes </p>
        </span>
      </li>
      <li>
        <i class='bx bxs-group' ></i>
        <span class="text">
          <h3>2834</h3>
          <p>Cuantas personas han visto tus productos </p>
        </span>
      </li>
      <li>
        <i class='bx bxs-dollar-circle' ></i>
        <span class="text">
          <h3>$2543</h3>
          <p>Total de vendido</p>
        </span>
      </li>
    </ul>


    <div class="table-data">
      <div class="order">
        <div class="head">
          <h3>Pedidos pendientes </h3>
          <i class='bx bx-search' ></i>
          <i class='bx bx-filter' ></i>
        </div>
        <table>
          <thead>
            <tr>
              <th>Cliente </th>
              <th>Numero de orden</th>
              <th>Estado</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>
                <img src="img/people.png">
                <p>John Doe</p>
              </td>
              <td>01-10-2021</td>
              <td><span class="status completed">Completo</span></td>
            </tr>
            <tr>
              <td>
                <img src="img/people.png">
                <p>John Doe</p>
              </td>
              <td>01-10-2021</td>
              <td><span class="status pending">Pendiente</span></td>
            </tr>
            <tr>
              <td>
                <img src="img/people.png">
                <p>John Doe</p>
              </td>
              <td>01-10-2021</td>
              <td><span class="status process">En carrito </span></td>
            </tr>
            <tr>
              <td>
                <img src="img/people.png">
                <p>John Doe</p>
              </td>
              <td>01-10-2021</td>
              <td><span class="status pending">Pendinente</span></td>
            </tr>
            <tr>
              <td>
                <img src="img/people.png">
                <p>John Doe</p>
              </td>
              <td>01-10-2021</td>
              <td><span class="status completed">Completo</span></td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="todo">
        <div class="head">
          <h3>Seguimiento a ventas </h3>
          <i class='bx bx-plus' ></i>
          <i class='bx bx-filter' ></i>
        </div>
        <ul class="todo-list">
          <li class="completed">
            <p>Completado</p>
            <i class='bx bx-dots-vertical-rounded' ></i>
          </li>
          <li class="not-completed">
            <p>Pendiente </p>
            <i class='bx bx-dots-vertical-rounded' ></i>
          </li>
          <li class="process">
            <p>En carrito</p>
            <i class='bx bx-dots-vertical-rounded' ></i>
          </li>
        </ul>
      </div>
    </div>
  </main>
  <!-- MAIN -->
</section>
<script type="module" src="https://cdn.jsdelivr.net/npm/@ionic/core/dist/ionic/ionic.esm.js"></script>
        <script nomodule src="https://cdn.jsdelivr.net/npm/@ionic/core/dist/ionic/ionic.js"></script>
        

<script src="barraprove.js.js"></script>
</body>
</html>