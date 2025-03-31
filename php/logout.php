<?php

//php para cerrar sesion en admin_panel
header('Content-Type: application/json');
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Destruir la sesión
    session_destroy();
    echo json_encode(['success' => true, 'message' => 'Sesión cerrada exitosamente']);
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
}
?>
