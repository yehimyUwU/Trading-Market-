<?php
class SolicitudesModel {
    private $conn;

    public function __construct($servername, $username, $password, $dbname) {
        $this->conn = new mysqli($servername, $username, $password, $dbname);

        if ($this->conn->connect_error) {
            die("Conexión fallida: " . $this->conn->connect_error);
        }
    }

    public function obtenerTodasLasSolicitudes() {
        $query = "
            SELECT 
                u.nombre, 
                u.email, 
                s.mensaje, 
                s.fecha 
            FROM 
                solicitudes s
            JOIN 
                usuario u
            ON 
                s.id_usuario = u.id_usuario
            ORDER BY 
                s.fecha DESC
        ";
        $result = $this->conn->query($query);

        if ($result->num_rows > 0) {
            $solicitudes = [];
            while ($row = $result->fetch_assoc()) {
                $solicitudes[] = $row;
            }
            return $solicitudes;
        } else {
            return [];
        }
    }

    public function closeConnection() {
        $this->conn->close();
    }
}
?>
