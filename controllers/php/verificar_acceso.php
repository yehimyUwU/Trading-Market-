<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header('Location: ../../views/html/longin.html');
    exit;
}

function verificarRol($rol) {
    return isset($_SESSION['roles']) && in_array($rol, $_SESSION['roles']);
}
?>
