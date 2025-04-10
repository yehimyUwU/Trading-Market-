<?php
require '../../config/php/conexion.php'; // Ruta actualizada

header('Content-Type: application/json');

// Verifica que se haya enviado un ID
$id = $_GET['id'] ?? null;

if ($id) {
    try {
        $pdo = Conexion::conectar();

        // Eliminar el cliente de la tabla usuario
        $stmt = $pdo->prepare("DELETE FROM usuario WHERE id_usuario = ?");
        if ($stmt->execute([$id])) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'No se pudo eliminar el cliente']);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Error de servidor: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'ID no proporcionado']);
}
?>
