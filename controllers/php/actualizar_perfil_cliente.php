<?php
session_start();
require '../../config/php/conexion.php';
header('Content-Type: application/json');

if (!isset($_SESSION['usuario']['id'])) {
    echo json_encode(['success' => false, 'message' => 'Usuario no identificado.']);
    exit;
}

$idUsuario = $_SESSION['usuario']['id'];
$data = $_POST;

try {
    $conn = Conexion::conectar();
    $stmt = $conn->prepare("UPDATE usuario SET nombre = :nombre, apellido = :apellido, email = :email WHERE id_usuario = :id_usuario");
    $stmt->bindParam(':nombre', $data['nombre'], PDO::PARAM_STR);
    $stmt->bindParam(':apellido', $data['apellido'], PDO::PARAM_STR);
    $stmt->bindParam(':email', $data['email'], PDO::PARAM_STR);
    $stmt->bindParam(':id_usuario', $idUsuario, PDO::PARAM_INT);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al actualizar los datos.']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error en el servidor: ' . $e->getMessage()]);
}
?>
