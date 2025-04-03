<?php
require '../../config/php/conexion.php';

session_start();

header("Content-Type: application/json");

if (!isset($_SESSION['usuario']['id'])) {
    echo json_encode(["error" => "Usuario no identificado."]);
    exit;
}


$conn = Conexion::conectar();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents("php://input"), true);

    if (!isset($input['id_producto'])) {
        echo json_encode(["error" => "Datos incompletos."]);
        exit;
    }

    $idUsuario = $_SESSION['usuario']['id'];
    $idProducto = $input['id_producto'];

    $sql = "DELETE FROM carrito 
            WHERE id_usuario = :id_usuario AND id_producto = :id_producto";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id_usuario', $idUsuario);
    $stmt->bindParam(':id_producto', $idProducto);

    if ($stmt->execute()) {
        echo json_encode(["success" => "Producto eliminado correctamente."]);
    } else {
        echo json_encode(["error" => "Error al eliminar el producto."]);
    }
}
?>
