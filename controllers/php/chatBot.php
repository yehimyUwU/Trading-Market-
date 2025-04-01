<?php
// Conexión a la base de datos
$servername = "localhost";
$username = "root"; // Cambia según tus credenciales
$password = ""; // Cambia según tus credenciales
$dbname = "inventarios";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener la pregunta del usuario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pregunta'])) {
    $userQuestion = trim($_POST['pregunta']);

    // Buscar en la base de datos
    $stmt = $conn->prepare("SELECT respuesta FROM chatBot WHERE pregunta = ?");
    $stmt->bind_param("s", $userQuestion);
    $stmt->execute();
    $result = $stmt->get_result();

    // Responder al usuario
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo $row['respuesta'];
    } else {
        echo "Lo siento, no entiendo esa pregunta. Intenta con otra de las preguntas disponibles.";
    }

    $stmt->close();
} else {
    echo "No se recibió ninguna pregunta válida.";
}

$conn->close();
?>

