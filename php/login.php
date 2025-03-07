<?php
header('Content-Type: application/json');
require 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $documento = $_POST['documento'] ?? null;
    $password = $_POST['password'] ?? null;

    if (!$documento || !$password) {
        echo json_encode(['success' => false, 'message' => 'Por favor, complete todos los campos']);
        exit;
    }

    try {
        // Buscar el usuario en la tabla 'usuario'
        $stmt = $pdo->prepare("SELECT * FROM usuario WHERE documento = ?");
        $stmt->execute([$documento]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario && password_verify($password, $usuario['password'])) {
            // Inicio de sesión exitoso
            session_start();
            $_SESSION['usuario'] = [
                'id' => $usuario['id_usuario'],
                'nombre' => $usuario['nombre'],
                'apellido' => $usuario['apellido'],
                'documento' => $usuario['documento'],
                'email' => $usuario['email'],
                'fecha_nacimiento' => $usuario['fecha_nacimiento'],
                'genero' => $usuario['genero']
            ];
            
            // Agregar log para depuración
            error_log('Usuario logueado: ' . print_r($_SESSION['usuario'], true));
            
            echo json_encode([
                'success' => true,
                'message' => 'Inicio de sesión exitoso'
            ]);
        } else {
            // Credenciales incorrectas
            echo json_encode([
                'success' => false,
                'message' => 'Documento o contraseña incorrectos'
            ]);
        }
    } catch (PDOException $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Error al iniciar sesión. Por favor, intente más tarde.'
        ]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
}
