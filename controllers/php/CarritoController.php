<?php
require_once '../config/php/conexion.php';
require_once '../models/CarritoModel.php';

class CarritoController {
    private $carritoModel;

    public function __construct() {
        $this->carritoModel = new CarritoModel();
    }

    public function manejarCarrito() {
        session_start();
        header("Content-Type: application/json");

        if (!isset($_SESSION['usuario']['id'])) {
            echo json_encode(["error" => "Usuario no identificado."]);
            return;
        }

        $idUsuario = $_SESSION['usuario']['id'];
        $conn = Conexion::conectar();

        try {
            if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                // Obtener carrito
                echo json_encode($this->carritoModel->obtenerCarrito($idUsuario));
            } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $input = json_decode(file_get_contents("php://input"), true);

                if (!isset($input['accion'])) {
                    echo json_encode(["error" => "Acción no especificada."]);
                    return;
                }

                $accion = $input['accion'];
                $idProducto = intval($input['id_producto'] ?? 0);
                $cambio = intval($input['cambio'] ?? 0);
                $cantidad = intval($input['cantidad'] ?? 1);

                if ($accion === 'agregar') {
                    $response = $this->carritoModel->agregarProducto($idUsuario, $idProducto, $cantidad);
                    echo json_encode($response);
                } elseif ($accion === 'modificar') {
                    $response = $this->carritoModel->modificarCantidad($idUsuario, $idProducto, $cambio);
                    echo json_encode($response);
                } elseif ($accion === 'eliminar') {
                    $response = $this->carritoModel->eliminarProducto($idUsuario, $idProducto);
                    echo json_encode($response);
                } else {
                    echo json_encode(["error" => "Acción no válida."]);
                }
            } else {
                echo json_encode(["error" => "Método no permitido."]);
            }
        } catch (Exception $e) {
            echo json_encode(["error" => "Error del servidor: " . $e->getMessage()]);
        }
    }
}
?>