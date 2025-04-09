<?php
require_once '../../config/php/conexion.php';

class CalificacionModel {
    private $conn;

    public function __construct() {
        $this->conn = Conexion::conectar();
    }

    public function guardarCalificacion($idUsuario, $idProducto, $calificacion) {
        try {
            // Elimina la calificación previa si existe
            $deleteStmt = $this->conn->prepare("DELETE FROM calificaciones WHERE id_producto = :id_producto AND id_usuario = :id_usuario");
            $deleteStmt->execute([
                ':id_producto' => $idProducto,
                ':id_usuario' => $idUsuario
            ]);

            // Inserta nueva calificación
            $stmt = $this->conn->prepare("INSERT INTO calificaciones (id_producto, id_usuario, calificacion) VALUES (:id_producto, :id_usuario, :calificacion)");
            $stmt->execute([
                ':id_producto' => $idProducto,
                ':id_usuario' => $idUsuario,
                ':calificacion' => $calificacion
            ]);

            return ['success' => true, 'message' => 'Calificación guardada.'];
        } catch (PDOException $e) {
            return ['success' => false, 'message' => 'Error al guardar: ' . $e->getMessage()];
        }
    }

    public function eliminarCalificacion($idUsuario, $idProducto) {
        try {
            $stmt = $this->conn->prepare("DELETE FROM calificaciones WHERE id_producto = :id_producto AND id_usuario = :id_usuario");
            $stmt->execute([
                ':id_producto' => $idProducto,
                ':id_usuario' => $idUsuario
            ]);
    
            return ['success' => true, 'message' => 'Calificación eliminada'];
        } catch (PDOException $e) {
            return ['success' => false, 'message' => 'Error: ' . $e->getMessage()];
        }
    }
    
    
    public function obtenerCalificacion($idUsuario, $idProducto) {
        try {
            $stmt = $this->conn->prepare("SELECT calificacion FROM calificaciones WHERE id_usuario = :id_usuario AND id_producto = :id_producto");
            $stmt->execute([
                ':id_usuario' => $idUsuario,
                ':id_producto' => $idProducto
            ]);
            $calificacion = $stmt->fetchColumn();

            return ['success' => true, 'calificacion' => $calificacion ?: 0];
        } catch (PDOException $e) {
            return ['success' => false, 'message' => 'Error: ' . $e->getMessage()];
        }
    }
}

