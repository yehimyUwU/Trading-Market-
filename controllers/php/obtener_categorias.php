<?php
/**
 * Archivo: obtener_categorias.php
 * Descripción: Controlador para obtener las categorías activas del sistema
 * Conexiones:
 * - Se conecta con: config/php/conexion.php (para la conexión a la base de datos)
 * - Se utiliza en: views/html/Misproductos.php (para mostrar categorías en formularios)
 * - Interactúa con la tabla: categorias
 * Flujo general:
 * 1. Establece conexión con la base de datos
 * 2. Ejecuta consulta para obtener categorías activas
 * 3. Retorna los resultados en formato JSON
 * Consulta SQL:
 * - SELECT id_categoria, nombre FROM categorias WHERE estado = 'activo'
 */

require '../../config/php/conexion.php';

$stmt = $pdo->query("SELECT id_categoria, nombre FROM categorias WHERE estado = 'activo'");
$categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);


header('Content-Type: application/json');
echo json_encode($categorias);
?> 