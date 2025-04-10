<?php
require '../../config/php/conexion.php';

if (isset($_GET['id'])) {
    $idProveedor = $_GET['id'];

    $conexion = new Conexion();
    $db = $conexion->conectar();

    $stmt = $db->prepare("SELECT * FROM producto WHERE id_empresa = ?");
    $stmt->execute([$idProveedor]);
    $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($productos);
} else {
    echo json_encode(["error" => "ID de proveedor no proporcionado."]);
}
