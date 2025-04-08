<?php
require_once '../config/php/conexion.php';
require_once '../models/UsuarioModel.php';

class AuthController {
    private $usuarioModel;

    public function __construct() {
        $this->usuarioModel = new UsuarioModel();
    }

    public function login() {
        session_start();
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $documento = $_POST['documento'] ?? null;
            $password = $_POST['password'] ?? null;
            $role = $_POST['role'] ?? null;

            $response = $this->usuarioModel->verificarCredenciales($documento, $password, $role);
            echo json_encode($response);
        } else {
            echo json_encode(['success' => false, 'message' => 'Método no permitido']);
        }
    }

    public function registro() {
        session_start();
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $tipo_documento = $_POST['tipo_documento'] ?? null;
            $documento = $_POST['documento'] ?? null;
            $nombre = $_POST['nombre'] ?? null;
            $apellido = $_POST['apellido'] ?? null;
            $fecha_nacimiento = $_POST['fecha_nacimiento'] ?? null;
            $genero = $_POST['genero'] ?? null;
            $email = $_POST['email'] ?? null;
            $password = $_POST['password'] ?? null;
            $rol = $_POST['rol'] ?? 'Cliente';

            $response = $this->usuarioModel->registrarUsuario(
                $tipo_documento, $documento, $nombre, $apellido, 
                $fecha_nacimiento, $genero, $email, $password, $rol
            );
            echo json_encode($response);
        } else {
            echo json_encode(['success' => false, 'message' => 'Método no permitido']);
        }
    }

    public function verificarAcceso($rolRequerido) {
        session_start();
        
        if (!isset($_SESSION['usuario'])) {
            header('Location: ../../views/html/longin.html');
            exit;
        }
        
        if (!in_array($rolRequerido, $_SESSION['roles'] ?? [])) {
            header('Location: ../../views/html/acceso_denegado.html');
            exit;
        }
    }

    public function logout() {
        session_start();
        session_unset();
        session_destroy();
        
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }

        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'message' => 'Sesión cerrada exitosamente']);
    }
}
?>