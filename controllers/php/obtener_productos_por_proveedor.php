<?php
require_once '../../models/php/modelo_usuario.php';

if (!isset($_GET['id'])) {
    echo json_encode(['error' => 'ID de proveedor no proporcionado']);
    exit;
}

$model = new ProveedorModel();
$productos = $model->obtenerProductosProveedor($_GET['id']);

echo json_encode($productos);
