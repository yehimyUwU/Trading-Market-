<?php
// Archivo de depuración para productos con presentaciones
header('Content-Type: application/json');

// Habilitar reporte de errores
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Verificar si se recibieron los datos
echo json_encode([
    "POST_data" => $_POST,
    "FILES_data" => $_FILES,
    "REQUEST_METHOD" => $_SERVER['REQUEST_METHOD']
]);
?> 