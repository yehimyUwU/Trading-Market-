<?php
session_start();
require '../../config/php/conexion.php';
header('Content-Type: application/json');


try {
    // Por ahora usaremos un ID de vendedor fijo (después se puede implementar un sistema de login)
    $id_vendedor = 1;
    
    $sql = "SELECT id_vendedor, nombre, apellido, email, imagen_perfil FROM vendedores WHERE id_vendedor = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id' => $id_vendedor]);
    
    $vendedor = $stmt->fetch();
    
    if ($vendedor) {
        echo json_encode([
            'success' => true,
            'vendedor' => $vendedor
        ]);
    } else {
        throw new Exception('Vendedor no encontrado');
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
} 