<?php
require '../../config/php/conexion.php';

if(isset($_GET['categoria_id'])) {
    $stmt = $pdo->prepare("SELECT id_subcategoria, nombre FROM subcategorias WHERE id_categoria = ?");
    $stmt->execute([$_GET['categoria_id']]);
    $subcategorias = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    
    header('Content-Type: application/json');
    echo json_encode($subcategorias);
}
?> 