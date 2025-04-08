<?php
require_once '../../config/php/conexion.php';

class UsuarioModel {
    private $conn;

    public function __construct() {
        $this->conn = Conexion::conectar();
    }

    public function verificarCredenciales($documento, $password, $role) {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM usuario WHERE documento = ?");
            $stmt->execute([$documento]);
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($usuario && password_verify($password, $usuario['password'])) {
                $stmt_rol = $this->conn->prepare("
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
                    $_SESSION['roles'] = [$rol_valido];

                    $redirect = match ($rol_valido) {
                        'Administrador' => '../../views/html/admin_panel.php',
                        'Cliente' => '../../views/html/inico.html',
                        'Proveedor' => '../../views/html/BienvProv.php',
                        default => null
                    };

                    return [
                        'success' => true,
                        'message' => 'Inicio de sesión exitoso',
                        'redirect' => $redirect
                    ];
                } else {
                    return [
                        'success' => false,
                        'message' => 'El rol seleccionado no coincide con el usuario.'
                    ];
                }
            } else {
                return [
                    'success' => false,
                    'message' => 'Documento o contraseña incorrectos'
                ];
            }
        } catch (PDOException $e) {
            error_log("Error en login: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Error al iniciar sesión. Por favor, intente más tarde.'
            ];
        }
    }

    public function registrarUsuario($tipo_documento, $documento, $nombre, $apellido, $fecha_nacimiento, $genero, $email, $password, $rol) {
        try {
            // Hash de la contraseña
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insertar el usuario en la tabla 'usuario'
            $stmt = $this->conn->prepare("INSERT INTO usuario (tipo_documento, documento, nombre, apellido, fecha_nacimiento, genero, email, password) 
                                        VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            
            if ($stmt->execute([$tipo_documento, $documento, $nombre, $apellido, $fecha_nacimiento, $genero, $email, $hashed_password])) {
                // Obtener el ID del usuario recién creado
                $id_usuario = $this->conn->lastInsertId();

                // Asignar el rol seleccionado al usuario
                $stmt_rol = $this->conn->prepare("
                    INSERT INTO usuario_rol (id_usuario, id_rol) 
                    VALUES (?, (SELECT id_rol FROM rol_usuario WHERE nombre = ?))
                ");
                
                if ($stmt_rol->execute([$id_usuario, $rol])) {
                    return ['success' => true, 'message' => 'Registro exitoso.'];
                } else {
                    return ['success' => false, 'message' => 'Error al asignar el rol al usuario.'];
                }
            } else {
                return ['success' => false, 'message' => 'Error al registrar el usuario.'];
            }
        } catch (PDOException $e) {
            $errorMessage = 'Error al registrar: ';
            
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
            
            return ['success' => false, 'message' => $errorMessage];
        }
    }

    public function obtenerPerfil($idUsuario) {
        try {
            $stmt = $this->conn->prepare("SELECT nombre, apellido, email, documento, imagen FROM usuario WHERE id_usuario = ?");
            $stmt->execute([$idUsuario]);
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($usuario) {
                $usuario['imagen'] = $usuario['imagen'] ? "../imag/" . $usuario['imagen'] : "http://ssl.gstatic.com/accounts/ui/avatar_2x.png";
                return ['success' => true, 'usuario' => $usuario];
            } else {
                return ['success' => false, 'message' => 'Usuario no encontrado.'];
            }
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Error al obtener el perfil: ' . $e->getMessage()];
        }
    }

    public function actualizarPerfil($id_usuario, $nombre, $apellido, $documento, $email, $fecha_nacimiento, $genero) {
        try {
            $stmt = $this->conn->prepare("
                UPDATE usuario
                SET nombre = ?, apellido = ?, documento = ?, email = ?, fecha_nacimiento = ?, genero = ?
                WHERE id_usuario = ?
            ");

            $success = $stmt->execute([$nombre, $apellido, $documento, $email, $fecha_nacimiento, $genero, $id_usuario]);
            
            return [
                'success' => $success,
                'message' => $success ? 'Datos actualizados correctamente' : 'Error al actualizar los datos'
            ];
        } catch (PDOException $e) {
            error_log("Error en actualizarPerfil: " . $e->getMessage());
            return ['success' => false, 'message' => 'Error en el servidor'];
        }
    }

    public function actualizarContrasenas() {
        try {
            $stmt = $this->conn->query("SELECT id_usuario, password FROM usuario");
            $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($usuarios as $usuario) {
                $id_usuario = $usuario['id_usuario'];
                $password = $usuario['password'];
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                $update_stmt = $this->conn->prepare("UPDATE usuario SET password = ? WHERE id_usuario = ?");
                $update_stmt->execute([$hashed_password, $id_usuario]);
            }

            return ['success' => true, 'message' => 'Contraseñas actualizadas correctamente.'];
        } catch (PDOException $e) {
            return ['success' => false, 'message' => 'Error al actualizar las contraseñas: ' . $e->getMessage()];
        }
    }

    public function guardarImagen($imagen, $idUsuario) {
        $uploadDir = '../../public/imag/';
        $fileName = uniqid() . '_' . basename($imagen['name']);
        $uploadFile = $uploadDir . $fileName;

        // Validar el tipo de archivo
        $fileType = mime_content_type($imagen['tmp_name']);
        if (!in_array($fileType, ['image/jpeg', 'image/png', 'image/gif'])) {
            return ['success' => false, 'message' => 'Formato de imagen no válido.'];
        }

        // Mover el archivo a la carpeta de destino
        if (move_uploaded_file($imagen['tmp_name'], $uploadFile)) {
            try {
                // Actualizar la ruta de la imagen en la base de datos
                $stmt = $this->conn->prepare("UPDATE usuario SET imagen = ? WHERE id_usuario = ?");
                $stmt->execute([$fileName, $idUsuario]);

                return ['success' => true, 'fileName' => $fileName];
            } catch (Exception $e) {
                return ['success' => false, 'message' => 'Error al guardar la imagen en la base de datos: ' . $e->getMessage()];
            }
        } else {
            return ['success' => false, 'message' => 'Error al mover el archivo.'];
        }
    }
}
?>