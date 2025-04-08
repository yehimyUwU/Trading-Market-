<?php
require_once '../../config/php/conexion.php';

class CategoriaModel {
    private $conn;

    public function __construct() {
        $this->conn = Conexion::conectar();
    }

    public function listarCategorias() {
        try {
            $stmt = $this->conn->prepare("SELECT id_categoria, nombre FROM categoria");
            $stmt->execute();
            $categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return ["success" => true, "listaCategorias" => $categorias];
        } catch (PDOException $e) {
            return ["success" => false, "message" => $e->getMessage()];
        }
    }

    public function listarSubcategorias($id_categoria) {
        try {
            $stmt = $this->conn->prepare("SELECT id_subcategoria, nombre FROM subcategoria WHERE id_categoria = ?");
            $stmt->execute([$id_categoria]);
            $subcategorias = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return ["success" => true, "listaSubcategorias" => $subcategorias];
        } catch (PDOException $e) {
            return ["success" => false, "message" => $e->getMessage()];
        }
    }

    public function obtenerCategorias() {
        try {
            $stmt = $this->conn->query("SELECT id_categoria, nombre FROM categorias WHERE estado = 'activo'");
            $categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $categorias;
        } catch (PDOException $e) {
            return ["error" => $e->getMessage()];
        }
    }

    public function obtenerSubcategorias($categoria_id) {
        try {
            $stmt = $this->conn->prepare("SELECT id_subcategoria, nombre FROM subcategorias WHERE id_categoria = ?");
            $stmt->execute([$categoria_id]);
            $subcategorias = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $subcategorias;
        } catch (PDOException $e) {
            return ["error" => $e->getMessage()];
        }
    }
}
?>