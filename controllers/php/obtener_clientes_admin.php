<?php
require '../../config/php/conexion.php'; // Ruta actualizada a tu configuración

header('Content-Type: application/json');

try {
    $pdo = Conexion::conectar(); // Conectar a la base de datos

    // Consultar los usuarios con rol de cliente
    $stmt = $pdo->prepare("
        SELECT u.id_usuario, u.tipo_documento, u.documento, u.nombre, u.apellido, u.fecha_nacimiento, u.genero, u.email
        FROM usuario u
        JOIN usuario_rol ur ON u.id_usuario = ur.id_usuario
        JOIN rol_usuario r ON ur.id_rol = r.id_rol
        WHERE r.nombre = 'Cliente'
    ");

    $stmt->execute();
    $clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(['success' => true, 'data' => $clientes]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Error al obtener clientes: ' . $e->getMessage()]);
}
?>
