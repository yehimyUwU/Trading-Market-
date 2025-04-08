<?php
require_once '../config/php/conexion.php';
require_once '../models/UsuarioModel.php';

class UsuarioController {
    private $usuarioModel;

    public function __construct() {
        $this->usuarioModel = new UsuarioModel();
    }

    public function obtenerPerfil() {
        session_start();
        header('Content-Type: application/json');

        if (!isset($_SESSION['usuario']['id'])) {
            echo json_encode(['success' => false, 'message' => 'Usuario no identificado.']);
            return;
        }

        $idUsuario = $_SESSION['usuario']['id'];
        $response = $this->usuarioModel->obtenerPerfil($idUsuario);
        echo json_encode($response);
    }

    public function actualizarPerfil() {
        session_start();
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_usuario = $_SESSION['usuario']['id'] ?? null;
            $nombre = $_POST['name'] ?? null;
            $apellido = $_POST['lastname'] ?? null;
            $documento = $_POST['document'] ?? null;
            $email = $_POST['email'] ?? null;
            $fecha_nacimiento = $_POST['birthdate'] ?? null;
            $genero = $_POST['gender'] ?? null;

            $response = $this->usuarioModel->actualizarPerfil(
                $id_usuario, $nombre, $apellido, 
                $documento, $email, $fecha_nacimiento, $genero
            );
            
            // Actualizar datos en sesión si la actualización fue exitosa
            if ($response['success']) {
                $_SESSION['usuario'] = [
                    'id' => $id_usuario,
                    'nombre' => $nombre,
                    'apellido' => $apellido,
                    'documento' => $documento,
                    'email' => $email,
                    'fecha_nacimiento' => $fecha_nacimiento,
                    'genero' => $genero
                ];
            }
            
            echo json_encode($response);
        } else {
            echo json_encode(['success' => false, 'message' => 'Método no permitido']);
        }
    }

    public function actualizarContrasenas() {
        header('Content-Type: application/json');
        
        try {
            $response = $this->usuarioModel->actualizarContrasenas();
            echo json_encode($response);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    }

    public function guardarImagen() {
        session_start();
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_FILES['profileImage']) && $_FILES['profileImage']['error'] === UPLOAD_ERR_OK) {
                $response = $this->usuarioModel->guardarImagen($_FILES['profileImage'], $_SESSION['usuario']['id']);
                echo json_encode($response);
            } else {
                echo json_encode(['success' => false, 'message' => 'No se recibió ninguna imagen o hubo un error al subirla.']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Método no permitido.']);
        }
    }
}
?>