<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/conexion.php'; // Incluir la conexión

$conn = Conexion::conectar(); // Llamar al método de conexión

if (!$conn) {
    die(json_encode(["error" => "No se pudo conectar a la base de datos."]));
}

if (!isset($_GET['categoria']) || empty($_GET['categoria'])) {
    die(json_encode(["error" => "No se especificó una categoría válida."]));
}

$categoria = trim($_GET['categoria']);
error_log("Categoría recibida: " . $categoria); // Log para depuración

$sql = "SELECT p.id_producto, p.nombre, p.descripcion, p.precio, p.stock, p.imagen 
        FROM producto p
        JOIN categoria c ON p.id_categoria = c.id_categoria
        WHERE c.nombre = :categoria";

$stmt = $conn->prepare($sql);
$stmt->bindParam(':categoria', $categoria, PDO::PARAM_STR);

if (!$stmt->execute()) {
    error_log("Error en consulta SQL: " . implode(" - ", $stmt->errorInfo()));
    die(json_encode(["error" => "Error en la consulta SQL."]));
}

$productos = [];
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    // Convertir imagen BLOB a base64 o asignar imagen por defecto
    if ($row['imagen']) {
        $row['imagen'] = "data:image/jpeg;base64," . base64_encode($row['imagen']);
    } else {
        $row['imagen'] = "ruta/a/imagen/default.jpg";
    }

    $productos[] = $row;
}

if (empty($productos)) {
    die(json_encode(["error" => "No se encontraron productos para la categoría: " . $categoria]));
}

echo json_encode($productos);
?>
