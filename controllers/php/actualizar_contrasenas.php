<?php
require '../../config/php/conexion.php';

try {
    $pdo = Conexion::conectar();

    // Seleccionar todos los usuarios
    $stmt = $pdo->query("SELECT id_usuario, password FROM usuario");
    $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($usuarios as $usuario) {
        $id_usuario = $usuario['id_usuario'];
        $password = $usuario['password'];

        // Generar un nuevo hash para la contraseña
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);   
        

        // Actualizar la contraseña en la base de datos
        $update_stmt = $pdo->prepare("UPDATE usuario SET password = ? WHERE id_usuario = ?");
        $update_stmt->execute([$hashed_password, $id_usuario]);
    }

    echo "Contraseñas actualizadas correctamente.";
} catch (PDOException $e) {
    echo "Error al actualizar las contraseñas: " . $e->getMessage();
}
?>
