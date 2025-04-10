<?php
session_start();
require_once '../../models/php/modelo_usuario.php';

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['id_proveedor']) || !isset($_SESSION['usuario']['id'])) {
    echo json_encode(['error' => 'Datos incompletos o sesión no iniciada']);
    exit;
}

$model = new ProveedorModel();
$response = $model->guardarProveedor($_SESSION['usuario']['id'], $data['id_proveedor']);

echo json_encode($response);
