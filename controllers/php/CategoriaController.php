<?php
require_once '../config/php/conexion.php';
require_once '../models/CategoriaModel.php';

class CategoriaController {
    private $categoriaModel;

    public function __construct() {
        $this->categoriaModel = new CategoriaModel();
    }

    public function listarCategorias() {
        header('Content-Type: application/json');
        $response = $this->categoriaModel->listarCategorias();
        echo json_encode($response);
    }

    public function listarSubcategorias() {
        header('Content-Type: application/json');
        
        if (isset($_POST['id_categoria'])) {
            $response = $this->categoriaModel->listarSubcategorias($_POST['id_categoria']);
            echo json_encode($response);
        } else {
            echo json_encode(['success' => false, 'message' => 'No se recibió el ID de la categoría.']);
        }
    }

    public function obtenerCategorias() {
        header('Content-Type: application/json');
        $response = $this->categoriaModel->obtenerCategorias();
        echo json_encode($response);
    }

    public function obtenerSubcategorias() {
        header('Content-Type: application/json');
        
        if (isset($_GET['categoria_id'])) {
            $response = $this->categoriaModel->obtenerSubcategorias($_GET['categoria_id']);
            echo json_encode($response);
        } else {
            echo json_encode(['success' => false, 'message' => 'No se recibió el ID de la categoría.']);
        }
    }
}
?>