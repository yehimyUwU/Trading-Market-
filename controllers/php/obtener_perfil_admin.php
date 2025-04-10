<?php
/**
 * Archivo: obtener_perfil_admin.php
 * Descripción: Controlador para obtener los datos del perfil del administrador
 * Conexiones:
 * - Se conecta con: config/php/conexion.php (para la conexión a la base de datos)
 * - Se utiliza en: views/html/admin_panel.php (para mostrar información del admin)
 * - Interactúa con la tabla: usuario
 * Flujo general:
 * 1. Inicia la sesión
 * 2. Verifica si hay un usuario logueado
 * 3. Retorna los datos del usuario en formato JSON
 */

header('Content-Type: application/json');
session_start();

if (!empty($_SESSION['usuario'])) {
    echo json_encode(['success' => true, 'data' => $_SESSION['usuario']]);
} else {
    echo json_encode(['success' => false, 'message' => 'No hay usuario logueado']);
}
?>
