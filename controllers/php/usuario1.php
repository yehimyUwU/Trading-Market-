<?php
/**
 * Archivo: usuario1.php
 * Descripción: Controlador para obtener información básica del usuario actual
 * Conexiones:
 * - Se conecta con: config/php/conexion.php (para la conexión a la base de datos)
 * - Se utiliza en: views/html/PerfilProv.php (para mostrar información del usuario)
 * - Interactúa con la tabla: usuario
 * Flujo general:
 * 1. Inicia la sesión
 * 2. Verifica si hay un usuario logueado
 * 3. Obtiene los datos básicos del usuario de la sesión
 * 4. Retorna los datos en formato JSON
 */

session_start(); // Asegúrate de que la sesión esté iniciada
header('Content-Type: application/json');

// Verifica si el usuario está autenticado
if (isset($_SESSION['usuario'])) {
    // Obtiene los datos del usuario de la sesión
    $usuario = $_SESSION['usuario'];
    
    
    // Devuelve los datos en formato JSON
    echo json_encode([
        'success' => true,
        'username' => $usuario['nombre'], // Cambia esto si el campo es diferente
        'email' => $usuario['email'] ?? 'No disponible' // Asegúrate de que el email esté en la sesión
    ]);
} else {
    // Si no hay sesión, devuelve un error
    echo json_encode(['success' => false, 'message' => 'Usuario no autenticado']);
}
?> 