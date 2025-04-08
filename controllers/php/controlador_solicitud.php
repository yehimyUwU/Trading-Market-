<?php
require_once '../../models/php/modelo_solicitud.php';

// Configuración de conexión
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "inventarios";

// Crear instancia del modelo
$solicitudesModel = new SolicitudesModel($servername, $username, $password, $dbname);

try {
    // Obtener todas las solicitudes desde el modelo
    $solicitudes = $solicitudesModel->obtenerTodasLasSolicitudes();

    // Devolver las solicitudes en formato JSON
    header('Content-Type: application/json');
    echo json_encode($solicitudes);
} catch (Exception $e) {
    error_log("Error en el controlador de solicitudes: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Error en el servidor']);
} finally {
    $solicitudesModel->closeConnection();
}
?>
