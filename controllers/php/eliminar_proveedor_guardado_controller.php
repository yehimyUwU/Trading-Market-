<?php
session_start();
require_once '../../config/php/Conexion.php';

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['id_proveedor']) || !isset($_SESSION['usuario']['id'])) {
    echo json_encode(['error' => 'Datos incompletos o sesión no iniciada']);
    exit;
}

$idUsuario = $_SESSION['usuario']['id'];
$idProveedor = $data['id_proveedor'];

try {
    $db = new Conexion();
    $conn = $db->conectar();

    $stmt = $conn->prepare("DELETE FROM proveedor_guardado WHERE id_usuario = ? AND id_proveedor = ?");
    $stmt->execute([$idUsuario, $idProveedor]);

    echo json_encode(['success' => true, 'mensaje' => 'Proveedor eliminado de la lista']);
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
