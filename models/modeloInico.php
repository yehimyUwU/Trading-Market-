<?php
require_once '../../config/php/conexion.php';

class Modelo {
    protected $conn;

    public function __construct() {
        $this->conn = Conexion::conectar();
        if (!$this->conn) {
            die(json_encode(["error" => "No se pudo conectar a la base de datos."]));
        }
    }

    public function ejecutarConsulta($sql, $params = []) {
        $stmt = $this->conn->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key + 1, $value);
        }
        if (!$stmt->execute()) {
            error_log("Error en consulta SQL: " . implode(" - ", $stmt->errorInfo()));
            return false;
        }
        return $stmt;
    }

    // 📌 Obtener productos por categoría
    public function obtenerProductosPorCategoria($categoria) {
        $sql = "SELECT p.id_producto, p.nombre, p.descripcion, p.precio, p.stock, p.imagen 
                FROM producto p
                JOIN categoria c ON p.id_categoria = c.id_categoria
                WHERE c.nombre = ?";
        
        $stmt = $this->ejecutarConsulta($sql, [$categoria]);
        if (!$stmt) {
            return json_encode(["error" => "Error en la consulta SQL."]);
        }

        $productos = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $row['imagen'] = $row['imagen'] ? "../../public/imag/" . $row['imagen'] : "../../public/imagenes_P/default.jpeg";
            $productos[] = $row;
        }

        return empty($productos) ? json_encode(["error" => "No se encontraron productos para la categoría: " . $categoria]) : json_encode($productos);
    }

    // 📌 Obtener proveedor por ID
    public function obtenerProveedorPorId($idProveedor) {
        $sql = "SELECT id_usuario, nombre, apellido, email, genero, fecha_nacimiento, documento 
                FROM usuario WHERE id_usuario = ?";
        
        $stmt = $this->ejecutarConsulta($sql, [$idProveedor]);
        if (!$stmt || !$proveedor = $stmt->fetch(PDO::FETCH_ASSOC)) {
            return json_encode(["error" => "Proveedor no encontrado"]);
        }
        return json_encode($proveedor);
    }

    // 📌 Obtener todos los proveedores
    public function obtenerProveedores() {
        $sql = "SELECT u.id_usuario, u.nombre, u.apellido, u.email 
                FROM usuario u 
                INNER JOIN usuario_rol ur ON u.id_usuario = ur.id_usuario 
                INNER JOIN rol_usuario r ON ur.id_rol = r.id_rol 
                WHERE r.nombre = 'Proveedor'";

        $stmt = $this->ejecutarConsulta($sql);
        if (!$stmt) {
            return json_encode(["error" => "Error en la consulta SQL"]);
        }

        $proveedores = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return empty($proveedores) ? json_encode(["error" => "No hay proveedores registrados"]) : json_encode($proveedores);
    }
}
?>
