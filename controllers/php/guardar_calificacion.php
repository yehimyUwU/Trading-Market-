<?php
session_start();
require_once '../../models/CalificacionModelo.php';

if (!isset($_SESSION['usuario']['id'])) {
    echo json_encode(['success' => false, 'message' => 'No autenticado']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit;
}

$input = json_decode(file_get_contents("php://input"), true);

$idProducto = isset($input['id_producto']) ? (int)$input['id_producto'] : null;
$calificacion = isset($input['calificacion']) ? (int)$input['calificacion'] : null;
$idUsuario = $_SESSION['usuario']['id'];

if ($idProducto > 0 && $calificacion >= 1 && $calificacion <= 5) {
    $model = new CalificacionModel();
    $response = $model->guardarCalificacion($idUsuario, $idProducto, $calificacion);
    echo json_encode($response);
} else {
    echo json_encode(['success' => false, 'message' => 'Datos inválidos']);
}
