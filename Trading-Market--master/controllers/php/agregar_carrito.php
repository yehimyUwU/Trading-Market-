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

    if (!isset($input['id_producto']) || !isset($input['cantidad'])) {
        echo json_encode(["error" => "Datos incompletos."]);
        exit;
    }

    $idUsuario = $_SESSION['usuario']['id'];
    $idProducto = intval($input['id_producto']);
    $cantidad = intval($input['cantidad']);

    // Verificar si el producto ya está en el carrito
    $sqlVerificar = "SELECT cantidad FROM carrito 
                     WHERE id_usuario = :id_usuario AND id_producto = :id_producto";
    $stmtVerificar = $conn->prepare($sqlVerificar);
    $stmtVerificar->bindParam(':id_usuario', $idUsuario, PDO::PARAM_INT);
    $stmtVerificar->bindParam(':id_producto', $idProducto, PDO::PARAM_INT);
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
        $stmtActualizar->bindParam(':id_usuario', $idUsuario, PDO::PARAM_INT);
        $stmtActualizar->bindParam(':id_producto', $idProducto, PDO::PARAM_INT);

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
        $stmtInsertar->bindParam(':id_usuario', $idUsuario, PDO::PARAM_INT);
        $stmtInsertar->bindParam(':id_producto', $idProducto, PDO::PARAM_INT);
        $stmtInsertar->bindParam(':cantidad', $cantidad, PDO::PARAM_INT);

        if ($stmtInsertar->execute()) {
            echo json_encode(["success" => "Producto agregado al carrito."]);
        } else {
            echo json_encode(["error" => "Error al agregar el producto al carrito."]);
        }
    }
}
?>
