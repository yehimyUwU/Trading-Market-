<?php
$current = basename($_SERVER['SCRIPT_NAME']);
?>
<!--Barra de navegacion restaurada y mejorada-->
<div class="container-fluid p-0">
  <div class="navigation" style="width: 240px; min-height: 100vh; background: linear-gradient(180deg, #FF6B00 0%, #FFAE00 100%); border-top-right-radius: 32px; border-bottom-right-radius: 32px; box-shadow: 0 4px 24px rgba(255, 174, 0, 0.15); position: fixed; left: 0; top: 0; z-index: 100; transition: width 0.3s;">
    <ul>
      <li class="brand-row">
        <span class="title">Trading Market</span>
        <span class="toggle">
          <ion-icon name="menu-outline" style="color: #FF6B00; font-size: 1.7rem;"></ion-icon>
        </span>
      </li>
      <li<?php if($current=='BienvProv.php') echo ' class="hovered"'; ?>>
        <a href="../../views/html/BienvProv.php">
          <span class="icon"><ion-icon name="home-outline"></ion-icon></span>
          <span class="title">Principal</span>
        </a>
      </li>
      <li<?php if($current=='Misproductos.php') echo ' class="hovered"'; ?>>
        <a href="../../views/html/Misproductos.php">
          <span class="icon"><ion-icon name="people-outline"></ion-icon></span>
          <span class="title">Mis productos</span>
        </a>
      </li>
      <li<?php if($current=='Mispedidos.php') echo ' class="hovered"'; ?>>
        <a href="../../views/html/Mispedidos.php">
          <span class="icon"><ion-icon name="chatbubbles-outline"></ion-icon></span>
          <span class="title">Mis pedidos</span>
        </a>
      </li>
      <li<?php if($current=='Chat.php') echo ' class="hovered"'; ?>>
        <a href="../../views/html/Chat.php">
          <span class="icon"><ion-icon name="help-outline"></ion-icon></span>
          <span class="title">Chat</span>
        </a>
      </li>
      <li<?php if($current=='PerfilProv.php') echo ' class="hovered"'; ?>>
        <a href="../../views/html/PerfilProv.php">
          <span class="icon"><ion-icon name="settings-outline"></ion-icon></span>
          <span class="title">Perfil</span>
        </a>
      </li>
      <li>
        <a href="../../views/html/index.html" style="background: #fff3e0; color: #FF6B00; font-weight: bold; border: 2px solid #FFAE00;">
          <span class="icon"><ion-icon name="log-out-outline"></ion-icon></span>
          <span class="title">Salir</span>
        </a>
      </li>
    </ul>
  </div>
</div>

<style>
.navigation {
  transition: width 0.3s;
}
.navigation.active {
  width: 80px !important;
}
.navigation ul {
  margin-top: 60px;
  padding-left: 0;
}
.navigation ul li {
  list-style: none;
  margin-bottom: 8px;
  border-radius: 16px;
  transition: background 0.3s, color 0.3s;
  cursor: pointer;
  padding: 0;
}
.navigation ul li a {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 12px 18px;
  color: #fff;
  font-size: 1.08rem;
  font-weight: 500;
  border-radius: 12px;
  text-decoration: none;
  transition: background 0.3s, color 0.3s;
}
.navigation ul li a .icon {
  font-size: 1.5rem;
  display: flex;
  align-items: center;
  justify-content: center;
}
.navigation ul li.hovered, .navigation ul li:hover {
  background: rgba(255,255,255,0.18);
  color: #fff;
}
.navigation ul li a[style*="background"] {
  color: #FF6B00 !important;
}
.navigation.active ul li .title {
  display: none;
}
.navigation.active {
  width: 80px !important;
}
.brand-row {
  display: flex;
  align-items: center;
  gap: 12px;
  justify-content: flex-start;
  padding: 12px 18px;
}
.brand-row .toggle {
  position: static !important;
  margin-left: 12px;
  width: 40px;
  height: 40px;
  font-size: 1.7rem;
  background: #fff3e0;
  border-radius: 50%;
  box-shadow: 0 2px 8px rgba(255, 174, 0, 0.10);
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  border: 2px solid #FFAE00;
  z-index: 200;
}
.brand-row .toggle ion-icon,
.brand-row .toggle svg {
  font-size: 2.2rem !important;
  width: 2.2em;
  height: 2.2em;
  color: #FF6B00;
}
</style>
<!-- Fin barra de navegación -->
  
  
  