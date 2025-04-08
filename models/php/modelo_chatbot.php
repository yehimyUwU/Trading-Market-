<?php
class Chatbot {
    private $conn;



    public function __construct($servername, $username, $password, $dbname) {
        $this->conn = new mysqli($servername, $username, $password, $dbname);

        if ($this->conn->connect_error) {
            die("Conexión fallida: " . $this->conn->connect_error);
        }
    }

    public function getResponse($userQuestion) {
        $stmt = $this->conn->prepare("SELECT respuesta FROM chatBot WHERE pregunta = ?");
        $stmt->bind_param("s", $userQuestion);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['respuesta'];
        } else {
            return "Lo siento, no entiendo esa pregunta. Intenta con otra de las preguntas disponibles.";
        }
    }

    public function notifyAdmin($message) {
        // Inserta la solicitud en una tabla específica o genera una acción
        $stmt = $this->conn->prepare("INSERT INTO solicitudes (mensaje) VALUES (?)");
        $stmt->bind_param("s", $message);
    
        if ($stmt->execute()) {
            return "Solicitud enviada al administrador.";
        } else {
            return "Error al enviar la solicitud.";
        }
    }
    

    public function closeConnection() {
        $this->conn->close();
    }
}
?>
