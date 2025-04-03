<?php
require '../../config/php/conexion.php';

try {
    $stmt = Conexion::conectar()->prepare("SELECT id_categoria, nombre FROM categoria");
    $stmt->execute();
    $categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode(["success" => true, "listaCategorias" => $categorias]);
} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}
?>
