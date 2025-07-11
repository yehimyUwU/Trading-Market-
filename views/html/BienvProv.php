<!DOCTYPE html>
<?php
/**
 * Archivo: BienvProv.php
 * Descripción: Página de bienvenida para proveedores
 * Conexiones:
 * - Se conecta con: controllers/php/verificar_acceso.php (para verificación de rol)
 * - Se conecta con: controllers/php/barra_prove.php (para la barra de navegación)
 * Funcionalidades:
 * - Dashboard de proveedor
 * - Estadísticas de ventas
 * - Acceso rápido a funciones principales
 * - Personalización con nombre del proveedor
 */
include_once '../../controllers/php/verificar_acceso.php';
if (!verificarRol('Proveedor')) {
    header('Location: ../../views/html/longin.html');
    exit;
}
?>
<!-- Coding By CodingNepal - youtube.com/@codingnepal -->
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Bienvenid-Trading Market </title>
  <link rel="stylesheet" href="../../public/Estilos/barraprove.css.css">
  <!-- Linking Google fonts for icons -->
  <link rel="stylesheet" href="../../public/Estilos/Admins-provedor.css">
  <link rel="stylesheet" href="../../public/Estilos/BienvenidoProv.css">
  <link rel="stylesheet" href="../../public/Estilos/prove_estilos.css" />
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0">
  <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
</head>




<body>
  <!-- SIDEBAR -->
  <?php
    require '../../controllers/php/barra_prove.php'; 
?>
  
  <!-- CONTENT -->
  <section id="content">
    <!-- NAVBAR -->

    
 
    <!-- NAVBAR -->

    <!-- MAIN -->
    <main class="content" style="margin-left: 240px; min-height: 100vh; ">
      <section class="welcome-section" style="padding-top: 32px;">
          <div class="welcome-message">
              <?php
                  $nombreVendedor = isset($_SESSION['usuario']['nombre']) ? $_SESSION['usuario']['nombre'] : 'Vendedor';
                  echo "<h1>¡Bienvenido, " . htmlspecialchars($nombreVendedor) . "!</h1>";
              ?>
              <p>Listo para impulsar tus ventas en Trading Market?</p>
          </div>

            <div class="cardBox">
                <div class="card">
                    <div>
                        <div class="numbers">$1,200</div>
                        <div class="cardName">Ventas del mes</div>
                    </div>
                    <div class="iconBx">
                        <ion-icon name="cash-outline"></ion-icon>
                    </div>
                </div>
                <div class="card">
                    <div>
                        <div class="numbers">Producto mas vendido</div>
                        <div class="cardName">Más vendido</div>
                    </div>
                    <div class="iconBx">
                        <ion-icon name="trending-up-outline"></ion-icon>
                    </div>
                </div>
                <div class="card">
                    <div>
                        <div class="numbers">500</div>
                        <div class="cardName">Visitas a la tienda</div>
                    </div>
                    <div class="iconBx">
                        <ion-icon name="storefront-outline"></ion-icon>
                    </div>
                </div>
            </div>

        <!-- Agrega Ionicons si aún no los tienes -->
        <script type="module" src="https://cdn.jsdelivr.net/npm/@ionic/core/dist/ionic/ionic.esm.js"></script>
        <script nomodule src="https://cdn.jsdelivr.net/npm/@ionic/core/dist/ionic/ionic.js"></script>

          <div class="welcome-tutorials">
              <h2>Recursos útiles</h2>
              <ul>
                  <li><a href="#">Cómo subir productos</a></li>
                  <li><a href="#">Gestionar pedidos</a></li>
                  <li><a href="#">Consejos para vender más</a></li>
              </ul>
          </div>
      </section>
    </main>
    <!-- MAIN -->
  </section>
  <!-- CONTENT -->
  
  <script src="../../public/js/barraprove.js.js"></script>
</body>
</html>

