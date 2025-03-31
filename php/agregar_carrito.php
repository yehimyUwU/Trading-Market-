<?php
require_once __DIR__ . '/conexion.php';

session_start();

header("Content-Type: application/json");

if (!isset($_SESSION['usuario']['id'])) {
    echo json_encode(["error" => "Usuario no identificado."]);
    exit;
}

$conn = Conexion::conectar();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents("php://input"), true);

<<<<<<< HEAD
// Verificar que haya un usuario logueado en la sesión
if (!isset($_SESSION['usuario']['id'])) {
    die(json_encode(["error" => "No hay usuario logueado."]));
}

$id_usuario = $_SESSION['usuario']['id']; // Obtener el ID del usuario logueado

// Decodificar el JSON recibido
// Decodificar el JSON recibido
// Decodificar el JSON recibido
$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['id_producto']) || !isset($data['cantidad'])) {
    die(json_encode(["error" => "Datos incompletos. Falta id_producto o cantidad."]));
}

$id_producto = intval($data['id_producto']);
$cantidad = intval($data['cantidad']); // Verificar si este valor llega correctamente
=======
    if (!isset($input['id_producto']) || !isset($input['cantidad'])) {
        echo json_encode(["error" => "Datos incompletos."]);
        exit;
    }

    $idUsuario = $_SESSION['usuario']['id'];
    $idProducto = $input['id_producto'];
    $cantidad = $input['cantidad'];

    // Verificar si el producto ya está en el carrito
    $sqlVerificar = "SELECT cantidad FROM carrito 
                    WHERE id_usuario = :id_usuario AND id_producto = :id_producto";
    $stmtVerificar = $conn->prepare($sqlVerificar);
    $stmtVerificar->bindParam(':id_usuario', $idUsuario);
    $stmtVerificar->bindParam(':id_producto', $idProducto);
    $stmtVerificar->execute();

    $productoEnCarrito = $stmtVerificar->fetch(PDO::FETCH_ASSOC);

    if ($productoEnCarrito) {
        // Si el producto ya está en el carrito, aumentar la cantidad
        $nuevaCantidad = $productoEnCarrito['cantidad'] + $cantidad;
        $sqlActualizar = "UPDATE carrito 
                          SET cantidad = :cantidad 
                          WHERE id_usuario = :id_usuario AND id_producto = :id_producto";
        $stmtActualizar = $conn->prepare($sqlActualizar);
        $stmtActualizar->bindParam(':cantidad', $nuevaCantidad, PDO::PARAM_INT);
        $stmtActualizar->bindParam(':id_usuario', $idUsuario);
        $stmtActualizar->bindParam(':id_producto', $idProducto);
>>>>>>> fc8b6405ad01c9099e1fc39de8b1d0e6704a71b9

        if ($stmtActualizar->execute()) {
            echo json_encode(["success" => "Cantidad actualizada correctamente."]);
        } else {
            echo json_encode(["error" => "Error al actualizar la cantidad del producto."]);
        }
    } else {
        // Si no está en el carrito, agregarlo
        $sqlInsertar = "INSERT INTO carrito (id_usuario, id_producto, cantidad) 
                        VALUES (:id_usuario, :id_producto, :cantidad)";
        $stmtInsertar = $conn->prepare($sqlInsertar);
        $stmtInsertar->bindParam(':id_usuario', $idUsuario);
        $stmtInsertar->bindParam(':id_producto', $idProducto);
        $stmtInsertar->bindParam(':cantidad', $cantidad);

<<<<<<< HEAD
$sql = "INSERT INTO carrito (id_usuario, id_producto, cantidad) VALUES (:id_usuario, :id_producto, :cantidad)";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
$stmt->bindParam(':id_producto', $id_producto, PDO::PARAM_INT);
$stmt->bindParam(':cantidad', $cantidad, PDO::PARAM_INT);


if ($stmt->execute()) {
    echo json_encode(["success" => "Producto añadido al carrito."]);
} else {
    echo json_encode(["error" => "Error al añadir producto al carrito."]);
=======
        if ($stmtInsertar->execute()) {
            echo json_encode(["success" => "Producto agregado al carrito."]);
        } else {
            echo json_encode(["error" => "Error al agregar el producto al carrito."]);
        }
    }
>>>>>>> fc8b6405ad01c9099e1fc39de8b1d0e6704a71b9
}



?>
