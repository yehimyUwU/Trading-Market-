<?php
include_once "conexion.php";

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $documento = $_POST['documento'];
    $password = $_POST['password'];

    if (!$documento || !$password) {
        echo json_encode(['success' => false, 'message' => 'Por favor, complete todos los campos']);
        exit;
    }

    try {
        $conexion = Conexion::conectar();
        $stmt = $conexion->prepare("SELECT * FROM usuario WHERE documento = :documento");
        $stmt->bindParam(':documento', $documento);
        $stmt->execute();
        $usuario = $stmt->fetch();

        if ($usuario && password_verify($password, $usuario['password'])) {
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
            echo json_encode(['success' => true, 'message' => 'Inicio de sesión exitoso']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Documento o contraseña incorrectos']);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Error al iniciar sesión. Por favor, intente más tarde.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
}
?>

