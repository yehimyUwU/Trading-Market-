<?php
/**
 * Archivo: verificar_acceso.php
 * Descripción: Controlador para verificar el acceso y roles de los usuarios
 * Conexiones:
 * - Se conecta con: config/php/conexion.php (para la conexión a la base de datos)
 * - Se utiliza en: Todas las vistas que requieren autenticación
 * - Interactúa con la tabla: usuario_rol, rol_usuario
 * Flujo general:
 * 1. Inicia la sesión
 * 2. Verifica si hay un usuario logueado
 * 3. Redirige al login si no hay sesión activa
 * 4. Proporciona función para verificar roles específicos
 */

session_start();

if (!isset($_SESSION['usuario'])) {
    header('Location: ../../views/html/longin.html');
    exit;
}

function verificarRol($rol) {
    return isset($_SESSION['roles']) && in_array($rol, $_SESSION['roles']);
}
?>
