<?php
session_start();
header('Content-Type: application/json');

// Agregar headers para debug
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');

// Log del estado de la sesión
error_log('Estado de sesión: ' . print_r($_SESSION, true));

if (isset($_SESSION['usuario'])) {
    error_log('Usuario encontrado en sesión: ' . print_r($_SESSION['usuario'], true));
    echo json_encode([
        'success' => true,
        'usuario' => $_SESSION['usuario']
    ]);
} else {
    error_log('No se encontró usuario en la sesión');
    echo json_encode([
        'success' => false,
        'message' => 'No hay sesión activa'
    ]);
}
?> 