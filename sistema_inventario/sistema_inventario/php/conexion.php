<?php
$host = 'localhost';
$dbname = 'subirProductos_db';
$username = 'root';  // Ajusta según tu configuración
$password = '';      // Ajusta según tu configuración

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Error de conexión: " . $e->getMessage();
}
?>
