<?php
include_once "conexion.php";

if (isset($_POST['id_categoria'])) {
    $id_categoria = $_POST['id_categoria'];

    try {
        $stmt = Conexion::conectar()->prepare("SELECT id_subcategoria, nombre FROM subcategoria WHERE id_categoria = :id_categoria");
        $stmt->bindParam(":id_categoria", $id_categoria, PDO::PARAM_INT);
        $stmt->execute();
        $subcategorias = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode(["success" => true, "listaSubcategorias" => $subcategorias]);
    } catch (PDOException $e) {
        echo json_encode(["success" => false, "message" => $e->getMessage()]);
    }
} else {
    echo json_encode(["success" => false, "message" => "No se recibió el ID de la categoría."]);
}
?>

