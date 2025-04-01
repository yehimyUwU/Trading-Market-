<?php
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