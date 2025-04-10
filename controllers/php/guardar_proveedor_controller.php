<?php
session_start();
require_once '../../config/php/conexion.php';

$data = json_decode(file_get_contents("php://input"), true);

// Verificamos si están los datos necesarios
if (!isset($data['id_proveedor']) || !isset($_SESSION['usuario']['id'])) {
    echo json_encode(['error' => 'Datos incompletos o sesión no iniciada']);
    exit;
}

$idUsuario = $_SESSION['usuario']['id'];
$idProveedor = $data['id_proveedor'];

try {
    $db = new Conexion();
    $conn = $db->conectar();

    // Verificamos que no esté ya guardado
    $query = $conn->prepare("SELECT * FROM proveedor_guardado WHERE id_usuario = ? AND id_proveedor = ?");
    $query->execute([$idUsuario, $idProveedor]);

    if ($query->rowCount() > 0) {
        echo json_encode(['mensaje' => 'Proveedor ya guardado']);
    } else {
        $insert = $conn->prepare("INSERT INTO proveedor_guardado (id_usuario, id_proveedor) VALUES (?, ?)");
        $insert->execute([$idUsuario, $idProveedor]);
        echo json_encode(['mensaje' => 'Proveedor guardado correctamente']);
    }
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
