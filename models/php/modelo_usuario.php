<?php

require_once '../../config/php/Conexion.php';

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


class ProveedorModel {
    private $conn;

    public function __construct() {
        $db = new Conexion();
        $this->conn = $db->conectar();
    }

    public function guardarProveedor($idUsuario, $idProveedor) {
        $query = $this->conn->prepare("SELECT * FROM proveedor_guardado WHERE id_usuario = ? AND id_proveedor = ?");
        $query->execute([$idUsuario, $idProveedor]);

        if ($query->rowCount() > 0) {
            return ['mensaje' => 'Proveedor ya guardado'];
        }

        $insert = $this->conn->prepare("INSERT INTO proveedor_guardado (id_usuario, id_proveedor) VALUES (?, ?)");
        $insert->execute([$idUsuario, $idProveedor]);

        return ['mensaje' => 'Proveedor guardado correctamente'];
    }

    public function eliminarProveedorGuardado($idUsuario, $idProveedor) {
        $stmt = $this->conn->prepare("DELETE FROM proveedor_guardado WHERE id_usuario = ? AND id_proveedor = ?");
        $stmt->execute([$idUsuario, $idProveedor]);

        return ['success' => true, 'mensaje' => 'Proveedor eliminado de la lista'];
    }

    public function obtenerProductosProveedor($idProveedor) {
        $stmt = $this->conn->prepare("SELECT * FROM producto WHERE id_empresa = ?");
        $stmt->execute([$idProveedor]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerProveedores($id = null) {
        if ($id) {
            $stmt = $this->conn->prepare("SELECT id_usuario, nombre, apellido, email, genero, fecha_nacimiento, documento FROM usuario WHERE id_usuario = ?");
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }

        $stmt = $this->conn->query("
            SELECT u.id_usuario, u.nombre, u.apellido, u.email 
            FROM usuario u
            INNER JOIN usuario_rol ur ON u.id_usuario = ur.id_usuario
            INNER JOIN rol_usuario r ON ur.id_rol = r.id_rol
            WHERE r.nombre = 'Proveedor'
        ");

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

?>
