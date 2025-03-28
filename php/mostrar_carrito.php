<?php
session_start();

require_once __DIR__ . '/conexion.php';
$conn = Conexion::conectar();

if (!$conn) {
    die(json_encode(["error" => "No se pudo conectar a la base de datos."]));
}

// Verificar que haya un usuario logueado
if (!isset($_SESSION['usuario']['id'])) {
    die(json_encode(["error" => "No hay usuario logueado."]));
}

$id_usuario = $_SESSION['usuario']['id'];

$sql = "SELECT c.id_carrito, p.nombre, p.precio, c.cantidad, (p.precio * c.cantidad) AS total
        FROM carrito c
        JOIN producto p ON c.id_producto = p.id_producto
        WHERE c.id_usuario = :id_usuario";

$stmt = $conn->prepare($sql);
$stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);

if ($stmt->execute()) {
    $carrito = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($carrito);
} else {
    echo json_encode(["error" => "Error al obtener el carrito."]);
}
?>
