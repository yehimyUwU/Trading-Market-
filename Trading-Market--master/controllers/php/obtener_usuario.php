<?php
session_start(); // Inicia la sesión

require '../../config/php/conexion.php'; // Asegúrate de que este archivo se incluya correctamente


if (isset($_SESSION['documento'])) { // Verifica si el usuario ha iniciado sesión
    $documento = $_SESSION['documento']; // Obtiene el documento del usuario de la sesión

    // Consulta para obtener los datos del usuario
    $stmt = $pdo->prepare("SELECT nombre, apellido, documento, email, fecha_nacimiento, genero FROM usuarios WHERE documento = ?");
    $stmt->execute([$documento]);
    $usuario = $stmt->fetch();

    if ($usuario) {
        echo json_encode(['success' => true, 'data' => $usuario]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Usuario no encontrado.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'No hay sesión activa.']);
}
?>
