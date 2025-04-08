<?php
session_start();

require '../../config/php/conexion.php';
$conn = Conexion::conectar();

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['id_carrito']) || !isset($data['cambio'])) {
    die(json_encode(["error" => "Datos incompletos."]));
}


$id_carrito = intval($data['id_carrito']);
$cambio = intval($data['cambio']);

$sql = "UPDATE carrito SET cantidad = cantidad + :cambio WHERE id_carrito = :id_carrito AND cantidad + :cambio >= 0";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':id_carrito', $id_carrito, PDO::PARAM_INT);
$stmt->bindParam(':cambio', $cambio, PDO::PARAM_INT);

if ($stmt->execute()) {
    echo json_encode(["success" => "Cantidad actualizada."]);
} else {
    echo json_encode(["error" => "Error al actualizar cantidad."]);
}



?>
