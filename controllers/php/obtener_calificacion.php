<?php
session_start();
require '../../config/php/conexion.php';

if (!isset($_SESSION['usuario']['id'])) {
    echo json_encode(['success' => false, 'message' => 'No autenticado']);
    exit;
}

$idUsuario = $_SESSION['usuario']['id'];
$idProducto = isset($_GET['id_producto']) ? (int)$_GET['id_producto'] : 0;

try {
    $conn = Conexion::conectar();
    $stmt = $conn->prepare("SELECT calificacion FROM calificaciones WHERE id_usuario = :id_usuario AND id_producto = :id_producto");
    $stmt->execute([':id_usuario' => $idUsuario, ':id_producto' => $idProducto]);
    $calificacion = $stmt->fetchColumn();

    echo json_encode(['success' => true, 'calificacion' => $calificacion ?: 0]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}
