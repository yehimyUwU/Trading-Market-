<?php
require '../../config/php/conexion.php';
require '../../models/php/modelo_carrito.php';

session_start();

header("Content-Type: application/json");



// Verificar si el usuario está autenticado
if (!isset($_SESSION['usuario']['id'])) {
    echo json_encode(["error" => "Usuario no identificado."]);
    exit;
}

$conn = Conexion::conectar();
$idUsuario = $_SESSION['usuario']['id'];
$carritoModel = new Carrito($conn);

try {
    // Comprobamos el método de la solicitud
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $input = json_decode(file_get_contents("php://input"), true);

        if (isset($input['accion']) && $input['accion'] === 'agregar') {
            $idProducto = intval($input['id_producto']);
            $cantidad = intval($input['cantidad']);
            
            if ($carritoModel->agregarProducto($idUsuario, $idProducto, $cantidad)) {
                echo json_encode(["success" => "Producto agregado correctamente."]);
            } else {
                echo json_encode(["error" => "Error al agregar producto."]);
            }
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            // Obtener carrito
            echo json_encode($carritoModel->obtenerCarrito($idUsuario));
        } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $input = json_decode(file_get_contents("php://input"), true);
        
            // Acción para agregar productos al carrito
            if (isset($input['accion']) && $input['accion'] === 'agregar') {
                $idProducto = intval($input['id_producto']);
                $cantidad = intval($input['cantidad']);
                if ($carritoModel->agregarProducto($idUsuario, $idProducto, $cantidad)) {
                    echo json_encode(["success" => "Producto agregado correctamente."]);
                } else {
                    echo json_encode(["error" => "Error al agregar producto."]);
                }
        
            // Acción para modificar la cantidad de un producto en el carrito
            } elseif (isset($input['accion']) && $input['accion'] === 'modificar') {
                $idProducto = intval($input['id_producto']);
                $cambio = intval($input['cambio']);
                if ($carritoModel->modificarCantidad($idUsuario, $idProducto, $cambio)) {
                    echo json_encode(["success" => "Cantidad actualizada correctamente."]);
                } else {
                    echo json_encode(["error" => "Error al actualizar cantidad."]);
                }
        
            // Acción para eliminar un producto del carrito
            } elseif (isset($input['accion']) && $input['accion'] === 'eliminar') {
                $idProducto = intval($input['id_producto']);
                if ($carritoModel->eliminarProducto($idUsuario, $idProducto)) {
                    echo json_encode(["success" => "Producto eliminado correctamente."]);
                } else {
                    echo json_encode(["error" => "Error al eliminar producto."]);
                }
            }
        }
    }
    echo json_encode(["error" => "Método no permitido."]);
    exit;
} catch (Exception $e) {
    echo json_encode(["error" => "Error del servidor: " . $e->getMessage()]);
    exit;
}
?>
