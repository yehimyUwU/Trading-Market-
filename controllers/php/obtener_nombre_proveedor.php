<?php
require_once '../../config/conexion.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $conexion = new Conexion();
    $db = $conexion->conectar();

    $stmt = $db->prepare("SELECT nombre, apellido FROM usuario WHERE id = ?");
    $stmt->execute([$id]);
    $proveedor = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($proveedor) {
        echo json_encode($proveedor);
    } else {
        echo json_encode(["error" => "Proveedor no encontrado."]);
    }
} else {
    echo json_encode(["error" => "ID no proporcionado."]);
}
