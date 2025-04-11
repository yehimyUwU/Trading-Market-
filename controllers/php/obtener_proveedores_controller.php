<?php
require_once '../../models/php/modelo_usuario.php';

$model = new ProveedorModel();
$id = isset($_GET['id']) ? $_GET['id'] : null;

$response = $model->obtenerProveedores($id);

if (!$response) {
    echo json_encode(['error' => $id ? 'Proveedor no encontrado' : 'No hay proveedores registrados']);
    exit;
}

// Codificar la(s) imagen(es) en base64
if ($id) {
    // Solo un proveedor
    if (isset($response['imagen']) && !empty($response['imagen'])) {
        $response['imagen'] = base64_encode($response['imagen']);
    }
} else {
    // Varios proveedores
    foreach ($response as &$proveedor) {
        if (isset($proveedor['imagen']) && !empty($proveedor['imagen'])) {
            $proveedor['imagen'] = base64_encode($proveedor['imagen']);
        }
    }
}

echo json_encode($response);
