<?php
session_start();
require '../../models/ComentarioModel.php';

$data = json_decode(file_get_contents("php://input"), true);

if (!empty($data['id_producto']) && isset($_SESSION['usuario']['id'])) {
    $comentarioModel = new ComentarioModel();
    $resultado = $comentarioModel->guardarOActualizarComentario(
        $data['id_producto'],
        $_SESSION['usuario']['id'],
        $data['comentario']
    );
    echo json_encode(['success' => $resultado]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Datos incompletos o sesión no iniciada',
    ]);
}
?>