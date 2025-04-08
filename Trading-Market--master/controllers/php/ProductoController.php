<?php
require_once '../config/php/conexion.php';
require_once '../models/ProductoModel.php';

class ProductoController {
    private $productoModel;

    public function __construct() {
        $this->productoModel = new ProductoModel();
    }

    public function registrarProducto() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["nombre"], $_POST["categoria"], $_POST["precio"], $_POST["descripcion"], $_POST["subcategoria"], $_POST["stock"], $_FILES["imagen"])) {
            $response = $this->productoModel->registrar(
                $_POST["nombre"],
                $_POST["categoria"],
                $_POST["precio"],
                $_POST["descripcion"],
                $_POST["subcategoria"],
                $_POST["stock"],
                $_FILES["imagen"]
            );
            echo json_encode($response);
        }
    }

    public function listarProductos() {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $response = $this->productoModel->listar();
            echo json_encode($response);
        }
    }

    public function editarProducto() {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_producto = $_POST['id_producto'] ?? null;
            $nombre = $_POST['nombre'] ?? null;
            $descripcion = $_POST['descripcion'] ?? null;
            $precio = $_POST['precio'] ?? null;
            $stock = $_POST['stock'] ?? null;

            $response = $this->productoModel->editar(
                $id_producto, $nombre, $descripcion, $precio, $stock
            );
            echo json_encode($response);
        } else {
            echo json_encode(['success' => false, 'message' => 'Método no permitido.']);
        }
    }

    public function obtenerProductos() {
        header('Content-Type: application/json');

        try {
            $input = json_decode(file_get_contents('php://input'), true);
            $busqueda = $input['busqueda'] ?? '';
            $ordenarPor = $input['ordenarPor'] ?? 'reciente';

            $response = $this->productoModel->obtenerProductos($busqueda, $ordenarPor);
            echo json_encode($response);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Error al obtener los productos: ' . $e->getMessage()]);
        }
    }
}
?>