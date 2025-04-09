<?php
require_once '../../config/php/conexion.php';

class ComentarioModel {
    private $conn;

    public function __construct() {
        $this->conn = Conexion::conectar();
    }

    public function guardarComentario($id_producto, $id_usuario, $comentario) {
        $stmt = $this->conn->prepare("
            INSERT INTO comentarios (id_producto, id_usuario, comentario) 
            VALUES (:id_producto, :id_usuario, :comentario)
        ");
        return $stmt->execute([
            ':id_producto' => $id_producto,
            ':id_usuario' => $id_usuario,
            ':comentario' => $comentario
        ]);
    }

    public function obtenerComentarioUsuario($id_producto, $id_usuario) {
        $stmt = $this->conn->prepare("
            SELECT comentario 
            FROM comentarios 
            WHERE id_producto = :id_producto AND id_usuario = :id_usuario
        ");
        $stmt->execute([
            ':id_producto' => $id_producto,
            ':id_usuario' => $id_usuario
        ]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function guardarOActualizarComentario($id_producto, $id_usuario, $comentario) {
        $comentarioExistente = $this->obtenerComentarioUsuario($id_producto, $id_usuario);

        if ($comentarioExistente) {
            $stmt = $this->conn->prepare("
                UPDATE comentarios 
                SET comentario = :comentario, fecha = CURRENT_TIMESTAMP 
                WHERE id_producto = :id_producto AND id_usuario = :id_usuario
            ");
        } else {
            $stmt = $this->conn->prepare("
                INSERT INTO comentarios (id_producto, id_usuario, comentario) 
                VALUES (:id_producto, :id_usuario, :comentario)
            ");
        }

        return $stmt->execute([
            ':id_producto' => $id_producto,
            ':id_usuario' => $id_usuario,
            ':comentario' => $comentario
        ]);
    }

    public function obtenerComentarios($id_producto) {
        $stmt = $this->conn->prepare("
            SELECT 
                u.nombre AS nombre_usuario, 
                c.comentario, 
                c.fecha,
                IFNULL(cal.calificacion, 0) AS calificacion
            FROM comentarios c
            JOIN usuario u ON c.id_usuario = u.id_usuario
            LEFT JOIN calificaciones cal 
                ON cal.id_usuario = c.id_usuario AND cal.id_producto = c.id_producto
            WHERE c.id_producto = :id_producto
            ORDER BY c.fecha DESC
        ");
        $stmt->execute([':id_producto' => $id_producto]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>