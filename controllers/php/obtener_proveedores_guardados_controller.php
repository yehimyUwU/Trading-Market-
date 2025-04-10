<?php
require_once '../../config/php/Conexion.php';

session_start();
$idUsuario = $_SESSION['usuario']['id'] ?? null;

if (!$idUsuario) {
    echo json_encode(['error' => 'Usuario no autenticado']);
    exit;
}

try {
    $db = new Conexion();
    $conn = $db->conectar();


    $sql = "
        SELECT u.id_usuario, u.nombre, u.apellido, u.email, u.genero, u.fecha_nacimiento, u.documento
        FROM proveedor_guardado pg
        JOIN usuario u ON pg.id_proveedor = u.id_usuario
        WHERE pg.id_usuario = :id_usuario
    ";

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id_usuario', $idUsuario);
    $stmt->execute();

    $proveedores = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($proveedores);

} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
