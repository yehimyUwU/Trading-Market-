<?php
class Usuario {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function obtenerPerfil($id_usuario) {
        $stmt = $this->pdo->prepare("SELECT * FROM usuario WHERE id_usuario = ?");
        $stmt->execute([$id_usuario]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function actualizarPerfil($id_usuario, $nombre, $apellido, $documento, $email, $fecha_nacimiento, $genero) {
        $stmt = $this->pdo->prepare("
            UPDATE usuario
            SET nombre = ?, apellido = ?, documento = ?, email = ?, fecha_nacimiento = ?, genero = ?
            WHERE id_usuario = ?
        ");

        return $stmt->execute([$nombre, $apellido, $documento, $email, $fecha_nacimiento, $genero, $id_usuario]);
    }
}

?>
