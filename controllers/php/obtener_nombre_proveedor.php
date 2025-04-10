<?php
/**
 * Archivo: obtener_nombre_proveedor.php
 * Descripción: Controlador para obtener el nombre y apellido de un proveedor
 * Conexiones:
 * - Se conecta con: config/php/conexion.php (para la conexión a la base de datos)
 * - Se utiliza en: views/html/Misproductos.php (para mostrar información del proveedor)
 * - Interactúa con la tabla: usuario
 * Flujo general:
 * 1. Recibe el ID del proveedor vía GET
 * 2. Consulta la información del proveedor en la base de datos
 * 3. Retorna nombre y apellido en formato JSON
 * Consulta SQL:
 * - SELECT nombre, apellido FROM usuario WHERE id_usuario = ?
 */

require '../../config/php/conexion.php'; 

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $conexion = new Conexion();
    $db = $conexion->conectar();

    $stmt = $db->prepare("SELECT nombre, apellido FROM usuario WHERE id_usuario = ?");
    $stmt->execute([$id]);
    $proveedor = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($proveedor) {
        echo json_encode($proveedor);
    } else {
        echo json_encode(["error" => "Proveedor no encontrado."]);
    }
} else {
    echo json_encode(["error" => "ID no proporcionado."]);
}
