<?php
require_once '../../models/php/modelo_chatbot.php';



// Configuración de conexión
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "inventarios";

// Crear instancia del modelo
$chatbotModel = new Chatbot($servername, $username, $password, $dbname);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pregunta'])) {
    $userQuestion = trim($_POST['pregunta']);
    echo $chatbotModel->getResponse($userQuestion);
} else {
    echo "No se recibió ninguna pregunta válida.";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pregunta'])) {
    $userQuestion = trim($_POST['pregunta']);

    // Verificar si la pregunta es "solicitud"
    if (strtolower($userQuestion) === 'solicitud') {
        echo $chatbotModel->notifyAdmin("Un cliente solicita convertirse en proveedor.");
    } else {
        echo $chatbotModel->getResponse($userQuestion);
    }
} else {
    echo "No se recibió ninguna pregunta válida.";
}


$chatbotModel->closeConnection();
?>
