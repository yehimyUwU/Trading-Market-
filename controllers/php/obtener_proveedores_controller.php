<?php
require '../../config/php/conexion.php';

try {
    $pdo = Conexion::conectar();

    // Verificar si se está solicitando un proveedor en específico
    if (isset($_GET['id'])) {
        $idProveedor = $_GET['id'];

        $stmt = $pdo->prepare("SELECT id_usuario, nombre, apellido, email, genero, fecha_nacimiento, documento FROM usuario WHERE id_usuario = ?");
        $stmt->execute([$idProveedor]);

        $proveedor = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$proveedor) {
            echo json_encode(["error" => "Proveedor no encontrado"]);
            exit;
        }

        echo json_encode($proveedor);
        exit; // Detiene la ejecución aquí si se está pidiendo un solo proveedor
    }

    // Obtener todos los proveedores
    $stmt = $pdo->query("SELECT u.id_usuario, u.nombre, u.apellido, u.email 
                         FROM usuario u 
                         INNER JOIN usuario_rol ur ON u.id_usuario = ur.id_usuario 
                         INNER JOIN rol_usuario r ON ur.id_rol = r.id_rol 
                         WHERE r.nombre = 'Proveedor'");

    if (!$stmt) {
        echo json_encode(["error" => "Error en la consulta SQL"]);
        exit;
    }

    $proveedores = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($proveedores)) {
        echo json_encode(["error" => "No hay proveedores registrados"]);
        exit;
    }

    echo json_encode($proveedores);
} catch (PDOException $e) {
    echo json_encode(["error" => "Excepción SQL: " . $e->getMessage()]);
}
?>