<?php
require '../../config/php/conexion.php';

session_start();

if (!isset($_SESSION['usuario']['id'])) {
    echo json_encode(["error" => "Usuario no identificado."]);
    exit;
}



header("Content-Type: application/json");

$conn = Conexion::conectar();
$idUsuario = $_SESSION['usuario']['id'];

$sql = "SELECT c.id_producto, p.nombre, ROUND(p.precio, 2) AS precio, c.cantidad 
        FROM carrito c
        JOIN producto p ON c.id_producto = p.id_producto
        WHERE c.id_usuario = :id_usuario";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':id_usuario', $idUsuario);

if ($stmt->execute()) {
    $carrito = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($carrito);
} else {
    echo json_encode(["error" => "Error al obtener el carrito."]);
}
?>
