<?php
session_start(); // Inicia la sesión

require '../../config/php/conexion.php'; // Asegúrate de que este archivo se incluya correctamente

if (isset($_SESSION['documento']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $documento = $_SESSION['documento']; // Obtiene el documento del usuario de la sesión
    $nombre = $_POST['nombre'] ?? null;
    $apellido = $_POST['apellido'] ?? null;
    $email = $_POST['email'] ?? null;
    $fecha_nacimiento = $_POST['fecha_nacimiento'] ?? null;
    $genero = $_POST['genero'] ?? null;

    // Consulta para actualizar los datos del usuario
    $stmt = $pdo->prepare("UPDATE usuarios SET nombre = ?, apellido = ?, email = ?, fecha_nacimiento = ?, genero = ? WHERE documento = ?");
    if ($stmt->execute([$nombre, $apellido, $email, $fecha_nacimiento, $genero, $documento])) {
        echo json_encode(['success' => true, 'message' => 'Datos actualizados correctamente.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al actualizar los datos.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'No hay sesión activa.']);
}
?>

