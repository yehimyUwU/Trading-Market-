<?php
require_once __DIR__ . '/conexion.php';

session_start(); // Siempre al inicio

if (!isset($_SESSION['usuario']['id'])) {
    echo json_encode(["error" => "Usuario no identificado."]);
    exit;
}

header("Content-Type: application/json");

$conn = Conexion::conectar();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents("php://input"), true);

    if (!isset($input['id_producto']) || !isset($input['cantidad'])) {
        echo json_encode(["error" => "Datos incompletos."]);
        exit;
    }

    $idUsuario = $_SESSION['usuario']['id']; // Usar el ID almacenado al iniciar sesión
    $idProducto = $input['id_producto'];
    $cantidad = $input['cantidad'];

    $sql = "INSERT INTO carrito (id_usuario, id_producto, cantidad) 
            VALUES (:id_usuario, :id_producto, :cantidad)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id_usuario', $idUsuario);
    $stmt->bindParam(':id_producto', $idProducto);
    $stmt->bindParam(':cantidad', $cantidad);

    if ($stmt->execute()) {
        echo json_encode(["success" => "Producto agregado al carrito."]);
    } else {
        echo json_encode(["error" => "Error al agregar producto al carrito."]);
    }
}
?>
