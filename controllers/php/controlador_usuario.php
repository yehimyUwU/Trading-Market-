<?php
header('Content-Type: application/json');
require '../../config/php/conexion.php';
require '../../models/php/modelo_usuario.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? null; // Acción recibida

    try {
        $pdo = Conexion::conectar();
        $usuarioModel = new Usuario($pdo);

        if ($action === 'obtenerPerfil') {
            $id_usuario = $_SESSION['usuario']['id'] ?? null;

            if (!$id_usuario) {
                echo json_encode(['success' => false, 'message' => 'No hay usuario logueado']);
                exit;
            }

            $datosUsuario = $usuarioModel->obtenerPerfil($id_usuario);

            if ($datosUsuario) {
                echo json_encode(['success' => true, 'data' => $datosUsuario]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Error al obtener los datos del usuario']);
            }
        } elseif ($action === 'actualizarPerfil') {
            // Datos a actualizar
            $id_usuario = $_SESSION['usuario']['id'] ?? null;
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

            if ($usuarioModel->actualizarPerfil($id_usuario, $nombre, $apellido, $documento, $email, $fecha_nacimiento, $genero)) {
                // Actualiza los datos en la sesión
                $_SESSION['usuario'] = [
                    'id' => $id_usuario,
                    'nombre' => $nombre,
                    'apellido' => $apellido,
                    'documento' => $documento,
                    'email' => $email,
                    'fecha_nacimiento' => $fecha_nacimiento,
                    'genero' => $genero
                ];
                echo json_encode(['success' => true, 'message' => 'Datos actualizados correctamente']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Error al actualizar los datos']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Acción no reconocida']);
        }
    } catch (PDOException $e) {
        error_log("Error en UsuarioController: " . $e->getMessage());
        echo json_encode(['success' => false, 'message' => 'Error en el servidor']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
}
?>
