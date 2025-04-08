<?php
require '../../models/ComentarioModel.php';

if (!empty($_GET['id_producto'])) {
    $comentarioModel = new ComentarioModel();
    $comentarios = $comentarioModel->obtenerComentarios($_GET['id_producto']);
    echo json_encode(['success' => true, 'comentarios' => $comentarios]);
} else {
    echo json_encode(['success' => false, 'message' => 'ID de producto no especificado']);
}
