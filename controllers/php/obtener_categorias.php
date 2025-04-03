<?php
require '../../config/php/conexion.php';

$stmt = $pdo->query("SELECT id_categoria, nombre FROM categorias WHERE estado = 'activo'");
$categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);


header('Content-Type: application/json');
echo json_encode($categorias);
?> 