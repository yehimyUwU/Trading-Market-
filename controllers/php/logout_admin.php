<?php
header('Content-Type: application/json');
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    session_unset(); // Limpia todas las variables de sesión
    session_destroy(); // Destruye la sesión

    // Eliminar la cookie de sesión si existe
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }

    echo json_encode(['success' => true, 'message' => 'Sesión cerrada exitosamente']);
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
}
?>
