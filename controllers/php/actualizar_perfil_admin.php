<?php

//php para poder actualizar los datos del usuario en admin_panel

header('Content-Type: application/json');
require '../../config/php/conexion.php'; // Asegúrate de que este archivo exista y tenga la configuración correcta

session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_usuario = $_SESSION['usuario']['id'] ?? null; // ID del usuario en la sesión
    $nombre = $_POST['name'] ?? null;
    $apellido = $_POST['lastname'] ?? null;
    $documento = $_POST['document'] ?? null;
    $email = $_POST['email'] ?? null;
    $fecha_nacimiento = $_POST['birthdate'] ?? null;
    $genero = $_POST['gender'] ?? null;

    if (!$id_usuario || !$nombre || !$apellido || !$documento || !$email || !$fecha_nacimiento || !$genero) {
        echo json_encode(['success' => false, 'message' => 'Todos los campos son obligatorios']);
        exit;
    }

    try {
        $pdo = Conexion::conectar(); // Conectar a la base de datos
        $stmt = $pdo->prepare("
            UPDATE usuario
            SET nombre = ?, apellido = ?, documento = ?, email = ?, fecha_nacimiento = ?, genero = ?
            WHERE id_usuario = ?
        ");

        if ($stmt->execute([$nombre, $apellido, $documento, $email, $fecha_nacimiento, $genero, $id_usuario])) {
            // Actualiza los datos en la sesión
            $_SESSION['usuario']['nombre'] = $nombre;
            $_SESSION['usuario']['apellido'] = $apellido;
            $_SESSION['usuario']['documento'] = $documento;
            $_SESSION['usuario']['email'] = $email;
            $_SESSION['usuario']['fecha_nacimiento'] = $fecha_nacimiento;
            $_SESSION['usuario']['genero'] = $genero;
            echo json_encode(['success' => true, 'message' => 'Datos actualizados correctamente']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al actualizar los datos']);
        }
    } catch (PDOException $e) {
        error_log("Error en update_profile.php: " . $e->getMessage());
        echo json_encode(['success' => false, 'message' => 'Error en el servidor']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
}
?>
