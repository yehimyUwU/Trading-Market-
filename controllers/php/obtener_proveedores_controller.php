<?php
require_once '../../models/php/modelo_usuario.php';

$model = new ProveedorModel();
$proveedores = $model->obtenerProveedores();

// Si estás guardando la imagen en formato LONGBLOB, conviértela a base64
foreach ($proveedores as &$proveedor) {
    if (!empty($proveedor['imagen'])) {
        $proveedor['imagen'] = 'data:image/jpeg;base64,' . base64_encode($proveedor['imagen']);
    } else {
        $proveedor['imagen'] = '../../public/imag/default.jpeg';
    }
}

header('Content-Type: application/json');
echo json_encode($proveedores);
