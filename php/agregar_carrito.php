<?php
session_start(); // Asegúrate de iniciar la sesión

require_once __DIR__ . '/conexion.php';

$conn = Conexion::conectar();

if (!$conn) {
    die(json_encode(["error" => "No se pudo conectar a la base de datos."]));
}

// Verificar que haya un usuario logueado en la sesión
if (!isset($_SESSION['usuario']['id'])) {
    die(json_encode(["error" => "No hay usuario logueado."]));
    
}


// Obtener y decodificar el cuerpo de la solicitud
$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['id_producto']) || !isset($data['cantidad'])) {
    die(json_encode(["error" => "Datos incompletos (id_producto o cantidad no presentes)."]));
}

// Asignar los valores decodificados
$id_producto = $data['id_producto'];
$cantidad = $data['cantidad'];



$id_usuario = $_SESSION['usuario']['id']; // Obtener el ID del usuario logueado
$id_producto = $_POST['id_producto']; // ID del producto que se agrega al carrito
$cantidad = $_POST['cantidad']; // Cantidad seleccionada

$sql = "INSERT INTO carrito (id_usuario, id_producto, cantidad) VALUES (:id_usuario, :id_producto, :cantidad)";
$stmt = $conn->prepare($sql);

$stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
$stmt->bindParam(':id_producto', $id_producto, PDO::PARAM_INT);
$stmt->bindParam(':cantidad', $cantidad, PDO::PARAM_INT);

if ($stmt->execute()) {
    echo json_encode(["success" => "Producto añadido al carrito."]);
} else {
    echo json_encode(["error" => "Error al añadir producto al carrito."]);
}
?>
