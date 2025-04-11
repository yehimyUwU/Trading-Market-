<?php
require_once '../../models/php/modelo_usuario.php';

$model = new ProveedorModel();
$id = isset($_GET['id']) ? $_GET['id'] : null;

$response = $model->obtenerProveedores($id);

if (!$response) {
    echo json_encode(['error' => $id ? 'Proveedor no encontrado' : 'No hay proveedores registrados']);
    exit;
}

echo json_encode($response);
