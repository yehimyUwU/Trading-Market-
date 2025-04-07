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
        $stmt->bindParam(':id_usuario', $idUsuario, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function agregarProducto($idUsuario, $idProducto, $cantidad) {
        // Verificar si el producto ya está en el carrito
        $sqlVerificar = "SELECT cantidad FROM carrito WHERE id_usuario = :id_usuario AND id_producto = :id_producto";
        $stmtVerificar = $this->conn->prepare($sqlVerificar);
        $stmtVerificar->bindParam(':id_usuario', $idUsuario, PDO::PARAM_INT);
        $stmtVerificar->bindParam(':id_producto', $idProducto, PDO::PARAM_INT);
        $stmtVerificar->execute();
        $productoEnCarrito = $stmtVerificar->fetch(PDO::FETCH_ASSOC);

        if ($productoEnCarrito) {
            // Si el producto ya está en el carrito, aumentar la cantidad
            $nuevaCantidad = $productoEnCarrito['cantidad'] + $cantidad;
            $sqlActualizar = "UPDATE carrito SET cantidad = :cantidad WHERE id_usuario = :id_usuario AND id_producto = :id_producto";
            $stmtActualizar = $this->conn->prepare($sqlActualizar);
            $stmtActualizar->bindParam(':cantidad', $nuevaCantidad, PDO::PARAM_INT);
            $stmtActualizar->bindParam(':id_usuario', $idUsuario, PDO::PARAM_INT);
            $stmtActualizar->bindParam(':id_producto', $idProducto, PDO::PARAM_INT);
            return $stmtActualizar->execute();
        } else {
            // Si no está en el carrito, agregarlo
            $sqlInsertar = "INSERT INTO carrito (id_usuario, id_producto, cantidad) VALUES (:id_usuario, :id_producto, :cantidad)";
            $stmtInsertar = $this->conn->prepare($sqlInsertar);
            $stmtInsertar->bindParam(':id_usuario', $idUsuario, PDO::PARAM_INT);
            $stmtInsertar->bindParam(':id_producto', $idProducto, PDO::PARAM_INT);
            $stmtInsertar->bindParam(':cantidad', $cantidad, PDO::PARAM_INT);
            return $stmtInsertar->execute();
        }
    }

    public function modificarCantidad($idUsuario, $idProducto, $cambio) {
        // Validar parámetros
        if (!is_int($idUsuario) || !is_int($idProducto) || !is_int($cambio)) {
            throw new Exception("Parámetros inválidos en modificarCantidad.");
        }
    
        $sql = "UPDATE carrito 
                SET cantidad = cantidad + :cambio 
                WHERE id_usuario = :id_usuario AND id_producto = :id_producto AND (cantidad + :cambio) >= 0";
        $stmt = $this->conn->prepare($sql);
    
        $stmt->bindParam(':cambio', $cambio, PDO::PARAM_INT);
        $stmt->bindParam(':id_usuario', $idUsuario, PDO::PARAM_INT);
        $stmt->bindParam(':id_producto', $idProducto, PDO::PARAM_INT);
    
        // Verificar si la consulta se ejecuta correctamente
        if ($stmt->execute()) {
            $sqlEliminar = "DELETE FROM carrito 
                            WHERE id_usuario = :id_usuario AND id_producto = :id_producto AND cantidad <= 0";
            $stmtEliminar = $this->conn->prepare($sqlEliminar);
            $stmtEliminar->bindParam(':id_usuario', $idUsuario, PDO::PARAM_INT);
            $stmtEliminar->bindParam(':id_producto', $idProducto, PDO::PARAM_INT);
            $stmtEliminar->execute();
            return true;
        }
        return false;
    }

    public function eliminarProducto($idUsuario, $idProducto) {
        $sql = "DELETE FROM carrito WHERE id_usuario = :id_usuario AND id_producto = :id_producto";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id_usuario', $idUsuario, PDO::PARAM_INT);
        $stmt->bindParam(':id_producto', $idProducto, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
?>
