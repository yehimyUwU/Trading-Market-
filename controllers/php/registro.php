<?php
session_start();
header('Content-Type: application/json');
require '../../config/php/conexion.php'; // Ruta actualizada

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Inicializar intentos fallidos en la sesión si no existen
    if (!isset($_SESSION['registro_intentos'])) {
        $_SESSION['registro_intentos'] = 0;
        $_SESSION['registro_bloqueado_hasta'] = null;
    }

    // Verificar si el usuario está bloqueado
    if ($_SESSION['registro_bloqueado_hasta'] && time() < $_SESSION['registro_bloqueado_hasta']) {
        echo json_encode(['success' => false, 'message' => 'Has sido bloqueado temporalmente. Intenta más tarde.']);
        exit;
    }

    // Capturar los datos enviados desde el formulario
    $tipo_documento = $_POST['tipo_documento'] ?? null;
    $documento = $_POST['documento'] ?? null;
    $nombre = $_POST['nombre'] ?? null;
    $apellido = $_POST['apellido'] ?? null;
    $fecha_nacimiento = $_POST['fecha_nacimiento'] ?? null;
    $genero = $_POST['genero'] ?? null;
    $email = $_POST['email'] ?? null;
    $password = $_POST['password'] ?? null;
    $rol = $_POST['rol'] ?? 'Cliente'; // Por defecto, asignar "Cliente" si no se selecciona un rol

    // Verificar si todos los campos requeridos están presentes
    if (!$tipo_documento || !$documento || !$nombre || !$apellido || !$fecha_nacimiento || !$genero || !$email || !$password || !$rol) {
        $_SESSION['registro_intentos']++;

        // Bloquear al usuario si supera los 3 intentos fallidos
        if ($_SESSION['registro_intentos'] >= 3) {
            $_SESSION['registro_bloqueado_hasta'] = time() + 900; // Bloquear por 15 minutos
            $_SESSION['registro_intentos'] = 0; // Reiniciar intentos después de bloquear
            echo json_encode(['success' => false, 'message' => 'Has sido bloqueado temporalmente. Intenta más tarde.']);
        } else {
            $intentos_restantes = 3 - $_SESSION['registro_intentos'];
            echo json_encode(['success' => false, 'message' => 'Todos los campos son obligatorios. Te quedan ' . $intentos_restantes . ' intentos.']);
        }
        exit;
    }

    // Reiniciar intentos fallidos si el registro es exitoso
    $_SESSION['registro_intentos'] = 0;
    $_SESSION['registro_bloqueado_hasta'] = null;

    // Hash de la contraseña
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    try {
        $pdo = Conexion::conectar(); // Conectar a la base de datos

        // Insertar el usuario en la tabla 'usuario'
        $stmt = $pdo->prepare("INSERT INTO usuario (tipo_documento, documento, nombre, apellido, fecha_nacimiento, genero, email, password) 
                              VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        
        if ($stmt->execute([$tipo_documento, $documento, $nombre, $apellido, $fecha_nacimiento, $genero, $email, $hashed_password])) {
            // Obtener el ID del usuario recién creado
            $id_usuario = $pdo->lastInsertId();

            // Asignar el rol seleccionado al usuario
            $stmt_rol = $pdo->prepare("
                INSERT INTO usuario_rol (id_usuario, id_rol) 
                VALUES (?, (SELECT id_rol FROM rol_usuario WHERE nombre = ?))
            ");
            if ($stmt_rol->execute([$id_usuario, $rol])) {
                echo json_encode(['success' => true, 'message' => 'Registro exitoso.']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Error al asignar el rol al usuario.']);
            }
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