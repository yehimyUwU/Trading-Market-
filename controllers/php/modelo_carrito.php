<?php
class Carrito {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function obtenerCarrito($idUsuario) {
        $sql = "SELECT c.id_producto, p.nombre, ROUND(p.precio, 2) AS precio, c.cantidad 
                FROM carrito c
                JOIN producto p ON c.id_producto = p.id_producto
                WHERE c.id_usuario = :id_usuario";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id_usuario', $idUsuario);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function modificarCarrito($idCarrito, $cambio) {
        $sql = "UPDATE carrito SET cantidad = cantidad + :cambio WHERE id_carrito = :id_carrito AND cantidad + :cambio >= 0";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id_carrito', $idCarrito, PDO::PARAM_INT);
        $stmt->bindParam(':cambio', $cambio, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function eliminarProducto($idUsuario, $idProducto) {
        $sql = "DELETE FROM carrito WHERE id_usuario = :id_usuario AND id_producto = :id_producto";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id_usuario', $idUsuario);
        $stmt->bindParam(':id_producto', $idProducto);
        return $stmt->execute();
    }

    public function agregarProducto($idUsuario, $idProducto, $cantidad) {
        $sql = "INSERT INTO carrito (id_usuario, id_producto, cantidad) VALUES (:id_usuario, :id_producto, :cantidad)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id_usuario', $idUsuario, PDO::PARAM_INT);
        $stmt->bindParam(':id_producto', $idProducto, PDO::PARAM_INT);
        $stmt->bindParam(':cantidad', $cantidad, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
?>
