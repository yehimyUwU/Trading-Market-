<?php
/**
 * Archivo: obtener_subcategorias.php
 * Descripción: Controlador para obtener las subcategorías de una categoría específica
 * Conexiones:
 * - Se conecta con: config/php/conexion.php (para la conexión a la base de datos)
 * - Se utiliza en: views/html/Misproductos.php (para mostrar subcategorías en formularios)
 * - Interactúa con la tabla: subcategorias
 * Flujo general:
 * 1. Recibe el ID de la categoría vía GET
 * 2. Consulta las subcategorías asociadas
 * 3. Retorna los resultados en formato JSON
 * Consulta SQL:
 * - SELECT id_subcategoria, nombre FROM subcategorias WHERE id_categoria = ?
 */

require '../../config/php/conexion.php';

if(isset($_GET['categoria_id'])) {
    $stmt = $pdo->prepare("SELECT id_subcategoria, nombre FROM subcategorias WHERE id_categoria = ?");
    $stmt->execute([$_GET['categoria_id']]);
    $subcategorias = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    
    header('Content-Type: application/json');
    echo json_encode($subcategorias);
}
?> 