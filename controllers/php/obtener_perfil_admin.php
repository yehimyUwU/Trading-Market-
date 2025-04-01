<?php
//php para obtener los datos del perfil y poder mostrarlos en otros archivos
header('Content-Type: application/json');
session_start();

if (!empty($_SESSION['usuario'])) {
    echo json_encode(['success' => true, 'data' => $_SESSION['usuario']]);
} else {
    echo json_encode(['success' => false, 'message' => 'No hay usuario logueado']);
}
?>
