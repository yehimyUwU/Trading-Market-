<?php
session_start();
require '../../config/php/conexion.php';
header('Content-Type: application/json');

if (!isset($_SESSION['usuario']['id'])) {
    echo json_encode(['success' => false, 'message' => 'Usuario no identificado.']);
    exit;
}

$idUsuario = $_SESSION['usuario']['id'];

try {
    $conn = Conexion::conectar();
    $stmt = $conn->prepare("SELECT nombre, apellido, email, documento, imagen FROM usuario WHERE id_usuario = :id_usuario");
    $stmt->bindParam(':id_usuario', $idUsuario, PDO::PARAM_INT);
    $stmt->execute();
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario) {
        // Verificar si la imagen existe, si no asignar una imagen por defecto
        $usuario['imagen'] = $usuario['imagen'] ? "../imag/" . $usuario['imagen'] : "http://ssl.gstatic.com/accounts/ui/avatar_2x.png";
        echo json_encode(['success' => true, 'usuario' => $usuario]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Usuario no encontrado.']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error al obtener el perfil: ' . $e->getMessage()]);
}
?>