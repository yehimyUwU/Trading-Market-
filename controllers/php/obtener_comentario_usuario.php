<?php
session_start();
require '../../models/ComentarioModel.php';

if (!empty($_GET['id_producto']) && isset($_SESSION['usuario']['id'])) {
    $comentarioModel = new ComentarioModel();
    $comentario = $comentarioModel->obtenerComentarioUsuario($_GET['id_producto'], $_SESSION['usuario']['id']);

    if ($comentario) {
        echo json_encode(['success' => true, 'comentario' => $comentario['comentario']]);
    } else {
        echo json_encode(['success' => false, 'comentario' => '']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
}
