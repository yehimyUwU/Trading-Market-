<?php
session_start();
require_once '../../models/CalificacionModelo.php';

if (!isset($_SESSION['usuario']['id'])) {
    echo json_encode(['success' => false, 'message' => 'No autenticado']);
    exit;
}

$idProducto = isset($_GET['id_producto']) ? (int)$_GET['id_producto'] : 0;
$idUsuario = $_SESSION['usuario']['id'];

$model = new CalificacionModel();
$response = $model->obtenerCalificacion($idUsuario, $idProducto);
echo json_encode($response);
