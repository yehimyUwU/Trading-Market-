<?php
require_once '../../models/modeloInico.php';

class ProveedorController {
    private $modelo;

    public function __construct() {
        $this->modelo = new Modelo();
    }

    public function obtenerProveedorPorId($idProveedor) {
        return $this->modelo->obtenerProveedorPorId($idProveedor);
    }

    public function obtenerProveedores() {
        return $this->modelo->obtenerProveedores();
    }
}

// Manejo de solicitud HTTP
$proveedorController = new ProveedorController();
if (isset($_GET['id'])) {
    echo $proveedorController->obtenerProveedorPorId($_GET['id']);
} else {
    echo $proveedorController->obtenerProveedores();
}
?>