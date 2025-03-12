<?php
header('Content-Type: application/json');
require 'conexion.php'; // Asegúrate de que este archivo se incluya correctamente

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verifica si las claves existen en el array $_POST
    $tipo_documento = $_POST['tipo_documento'] ?? null;
    $documento = $_POST['documento'] ?? null;
    $nombre = $_POST['nombre'] ?? null;
    $apellido = $_POST['apellido'] ?? null;
    $fecha_nacimiento = $_POST['fecha_nacimiento'] ?? null;
    $genero = $_POST['genero'] ?? null;
    $email = $_POST['email'] ?? null;
    $password = $_POST['password'] ?? null;

    // Verificar si todos los campos requeridos están presentes
    if (!$tipo_documento || !$documento || !$nombre || !$apellido || !$fecha_nacimiento || !$genero || !$email || !$password) {
        echo json_encode(['success' => false, 'message' => 'Todos los campos son obligatorios.']);
        exit;
    }

    // Hash de la contraseña
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    try {
        $pdo = Conexion::conectar(); // Conectar a la base de datos

        // Los signos de interrogación son marcadores de posición para los valores que se enlazarán a la declaración preparada
        $stmt = $pdo->prepare("INSERT INTO usuario (tipo_documento, documento, nombre, apellido, fecha_nacimiento, genero, email, password) 
                              VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        
        // Ejecuta la declaración preparada con los valores proporcionados
        if ($stmt->execute([$tipo_documento, $documento, $nombre, $apellido, $fecha_nacimiento, $genero, $email, $hashed_password])) {
            echo json_encode(['success' => true, 'message' => 'Registro exitoso.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al registrar el usuario.']);
        }
    } catch (PDOException $e) {
        // Manejo de errores más específico
        $errorMessage = 'Error al registrar: ';
        
        // Verificar si es un error de duplicado
        if ($e->getCode() == 23000) {
            if (strpos($e->getMessage(), 'documento')) {
                $errorMessage .= 'El número de documento ya está registrado.';
            } else if (strpos($e->getMessage(), 'email')) {
                $errorMessage .= 'El correo electrónico ya está registrado.';
            } else {
                $errorMessage .= 'Datos duplicados.';
            }
        } else {
            $errorMessage .= 'Por favor, intente más tarde.';
        }
        
        echo json_encode(['success' => false, 'message' => $errorMessage]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
}
?>