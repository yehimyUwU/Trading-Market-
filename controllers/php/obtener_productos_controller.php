<?php
require_once '../../models/productoModelo.php';

class ProductoController {
    private $modelo;

    public function __construct() {
        $this->modelo = new ProductoCliente();
    }

    public function obtenerProductosPorCategoria($categoria) {
        return $this->modelo->obtenerProductosPorCategoria($categoria);
    }
}

// Manejo de solicitud HTTP
if (isset($_GET['categoria'])) {
    $productoController = new ProductoController();
    echo $productoController->obtenerProductosPorCategoria(trim($_GET['categoria']));
}
?>
