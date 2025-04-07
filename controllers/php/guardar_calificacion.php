<?php
require '../../config/php/conexion.php';
session_start(); // Asegúrate de que la sesión esté iniciada

// Verifica si el usuario está autenticado correctamente
if (!isset($_SESSION['usuario']['id'])) {
    echo json_encode(['success' => false, 'message' => 'No autenticado']);
    exit;
}

$idUsuario = $_SESSION['usuario']['id'];

// Solo acepta método POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents("php://input"), true);

    $idProducto = isset($input['id_producto']) ? (int)$input['id_producto'] : null;
    $calificacion = isset($input['calificacion']) ? (int)$input['calificacion'] : null;

    if ($idProducto > 0 && $calificacion >= 1 && $calificacion <= 5) {
        try {
            $conn = Conexion::conectar();

            // Borrar calificación anterior si existe
            $deleteStmt = $conn->prepare("DELETE FROM calificaciones WHERE id_producto = :id_producto AND id_usuario = :id_usuario");
            $deleteStmt->bindParam(':id_producto', $idProducto, PDO::PARAM_INT);
            $deleteStmt->bindParam(':id_usuario', $idUsuario, PDO::PARAM_INT);
            $deleteStmt->execute();

            // Insertar nueva calificación
            $stmt = $conn->prepare("INSERT INTO calificaciones (id_producto, id_usuario, calificacion) VALUES (:id_producto, :id_usuario, :calificacion)");
            $stmt->bindParam(':id_producto', $idProducto, PDO::PARAM_INT);
            $stmt->bindParam(':id_usuario', $idUsuario, PDO::PARAM_INT);
            $stmt->bindParam(':calificacion', $calificacion, PDO::PARAM_INT);

            $stmt->execute();

            echo json_encode(['success' => true, 'message' => 'Calificación guardada.']);
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'message' => 'Error al guardar: ' . $e->getMessage()]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Datos inválidos.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido.']);
}
