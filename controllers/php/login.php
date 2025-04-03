<?php
header('Content-Type: application/json');
require '../../config/php/conexion.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $documento = $_POST['documento'] ?? null;
    $password = $_POST['password'] ?? null;
    $role = $_POST['role'] ?? null; // Captura el rol enviado desde el frontend

    // Depuración: Registrar los datos recibidos
    error_log("Documento recibido: " . $documento);
    error_log("Contraseña recibida: " . $password);
    error_log("Rol recibido: " . $role);

    if (!$documento || !$password || !$role) {
        echo json_encode(['success' => false, 'message' => 'Por favor, complete todos los campos']);
        exit;
    }

    try {
        $pdo = Conexion::conectar(); // Conectar a la base de datos

        // Buscar el usuario en la tabla 'usuario'
        $stmt = $pdo->prepare("SELECT * FROM usuario WHERE documento = ?");
        $stmt->execute([$documento]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario && password_verify($password, $usuario['password'])) {
            // Verificar si el usuario tiene el rol seleccionado
            $stmt_rol = $pdo->prepare("
                SELECT r.nombre 
                FROM usuario_rol ur 
                INNER JOIN rol_usuario r ON ur.id_rol = r.id_rol 
                WHERE ur.id_usuario = ? AND r.nombre = ?
            ");
            $stmt_rol->execute([$usuario['id_usuario'], $role]);
            $rol_valido = $stmt_rol->fetch(PDO::FETCH_COLUMN);

            if ($rol_valido) {
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
                $_SESSION['roles'] = [$rol_valido]; // Almacenar el rol en la sesión

                // Redirigir según el rol
                $redirect = match ($rol_valido) {
                    'Administrador' => '../../views/html/admin_panel.php',
                    'Cliente' => '../../views/html/inico.html',
                    'Proveedor' => '../../views/html/BienvProv.php', // Asegúrate de usar "Proveedor"
                    default => null
                };

                echo json_encode([
                    'success' => true,
                    'message' => 'Inicio de sesión exitoso',
                    'redirect' => $redirect
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'El rol seleccionado no coincide con el usuario.'
                ]);
            }
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Documento o contraseña incorrectos'
            ]);
        }
    } catch (PDOException $e) {
        error_log("Error en login.php: " . $e->getMessage()); // Registrar el error en el log
        echo json_encode([
            'success' => false,
            'message' => 'Error al iniciar sesión. Por favor, intente más tarde.'
        ]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
}