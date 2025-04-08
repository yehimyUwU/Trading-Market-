<?php
require_once '../config/php/conexion.php';
require_once '../models/VendedorModel.php';

class VendedorController {
    private $vendedorModel;

    public function __construct() {
        $this->vendedorModel = new VendedorModel();
    }

    public function obtenerVendedor() {
        session_start();
        header('Content-Type: application/json');

        try {
            $response = $this->vendedorModel->obtenerVendedor();
            echo json_encode($response);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
}
?>