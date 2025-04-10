<?php
/**
 * Archivo: listar_categorias.php
 * Descripción: Controlador para obtener la lista de categorías del sistema
 * Conexiones:
 * - Se conecta con: config/php/conexion.php (para la conexión a la base de datos)
 * - Se utiliza en: views/html/Misproductos.php (para mostrar categorías en formularios)
 * - Interactúa con la tabla: categoria
 * Flujo general:
 * 1. Establece conexión con la base de datos
 * 2. Ejecuta consulta para obtener todas las categorías
 * 3. Retorna los resultados en formato JSON
 * Consulta SQL:
 * - SELECT id_categoria, nombre FROM categoria
 */

require '../../config/php/conexion.php';

try {
    // Preparar y ejecutar consulta para obtener todas las categorías
    $stmt = Conexion::conectar()->prepare("SELECT id_categoria, nombre FROM categoria");
    $stmt->execute();
    
    // Obtener resultados y formatear respuesta
    $categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode(["success" => true, "listaCategorias" => $categorias]);
} catch (PDOException $e) {
    // Manejar errores y retornar mensaje de error
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}
?>
