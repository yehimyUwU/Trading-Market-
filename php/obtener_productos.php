<?php
require_once __DIR__ . '/conexion.php'; // Incluir la conexión

$conn = Conexion::conectar(); // Llamar al método de conexión

if (isset($_GET['categoria'])) {
    $categoria = $_GET['categoria'];

    $sql = "SELECT p.id_producto, p.nombre, p.descripcion, p.precio, p.stock, p.imagen 
            FROM producto p
            JOIN categoria c ON p.id_categoria = c.id_categoria
            WHERE c.nombre = :categoria";

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':categoria', $categoria, PDO::PARAM_STR);
    $stmt->execute();

    $productos = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        // Convertir imagen BLOB a base64
        $row['imagen'] = $row['imagen'] 
            ? "data:image/jpeg;base64," . base64_encode($row['imagen']) 
            : "ruta/a/imagen/default.jpg";
        
        $productos[] = $row;
    }

    echo json_encode($productos);
} else {
    echo json_encode(["error" => "No se especificó una categoría"]);
}
?>
