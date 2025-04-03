<?php
require '../../config/php/conexion.php';

// Mostrar errores para depuración
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json'); // Asegurar que la respuesta sea JSON

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Depuración: Verificar datos recibidos
    $id_producto = $_POST['id_producto'] ?? null;
    $nombre = $_POST['nombre'] ?? null;
    $descripcion = $_POST['descripcion'] ?? null;
    $precio = $_POST['precio'] ?? null;
    $stock = $_POST['stock'] ?? null;

    if (!$id_producto || !$nombre || !$descripcion || !$precio || !$stock) {
        echo json_encode(['success' => false, 'message' => 'Todos los campos son obligatorios.', 'data' => $_POST]);
        exit;
    }

    try {
        // Depuración: Verificar conexión a la base de datos
        if (!$conn) {
            echo json_encode(['success' => false, 'message' => 'Error de conexión a la base de datos.']);
            exit;
        }

        // Depuración: Verificar consulta SQL
        $query = "UPDATE productos SET nombre = ?, descripcion = ?, precio = ?, stock = ? WHERE id_producto = ?";
        $stmt = $conn->prepare($query);
        if (!$stmt) {
            echo json_encode(['success' => false, 'message' => 'Error al preparar la consulta.', 'error' => $conn->error]);
            exit;
        }

        $stmt->bind_param('ssdii', $nombre, $descripcion, $precio, $stock, $id_producto);

        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Producto actualizado correctamente.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al ejecutar la consulta.', 'error' => $stmt->error]);
        }

        $stmt->close();
        $conn->close();
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error del servidor: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido.']);
}
?>
