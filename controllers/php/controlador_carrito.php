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
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        // Obtener carrito
        echo json_encode($carritoModel->obtenerCarrito($idUsuario));
        exit;
    } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $input = json_decode(file_get_contents("php://input"), true);

        if (!isset($input['accion'])) {
            echo json_encode(["error" => "Acción no especificada."]);
            exit;
        }

        $accion = $input['accion'];
        $idProducto = intval($input['id_producto'] ?? 0);
        $cambio = intval($input['cambio'] ?? 0);
        $cantidad = intval($input['cantidad'] ?? 1);

        if ($accion === 'agregar') {
            if ($carritoModel->agregarProducto($idUsuario, $idProducto, $cantidad)) {
                echo json_encode([
                    "success" => "Producto agregado correctamente.",
                    "carrito" => $carritoModel->obtenerCarrito($idUsuario)
                ]);
            } else {
                echo json_encode(["error" => "Error al agregar producto."]);
            }
        } elseif ($accion === 'modificar') {
            if ($cambio === 0) {
                echo json_encode(["error" => "El valor de cambio no puede ser 0."]);
                exit;
            }

            try {
                if ($carritoModel->modificarCantidad($idUsuario, $idProducto, $cambio)) {
                    echo json_encode([
                        "success" => "Cantidad actualizada correctamente.",
                        "carrito" => $carritoModel->obtenerCarrito($idUsuario)
                    ]);
                } else {
                    echo json_encode(["error" => "Error al modificar cantidad."]);
                }
            } catch (Exception $e) {
                echo json_encode(["error" => "Error del servidor: " . $e->getMessage()]);
            }
        } elseif ($accion === 'eliminar') {
            if ($carritoModel->eliminarProducto($idUsuario, $idProducto)) {
                echo json_encode([
                    "success" => "Producto eliminado correctamente.",
                    "carrito" => $carritoModel->obtenerCarrito($idUsuario)
                ]);
            } else {
                echo json_encode(["error" => "Error al eliminar producto."]);
            }
        } elseif ($accion === 'pagar') {
            // Procesar el pago
            $correo = $input['correo'];
            $direccion = $input['direccion'];
            $metodoPago = $input['metodoPago'];

            $carrito = $carritoModel->obtenerCarrito($idUsuario);

            if (empty($carrito)) {
                echo json_encode(["error" => "El carrito está vacío."]);
                exit;
            }

            // Crear el mensaje del correo
            $asunto = "Confirmación de Compra";
            $mensaje = "Gracias por su compra.\n\n";
            $mensaje .= "Dirección de Envío: $direccion\n";
            $mensaje .= "Método de Pago: $metodoPago\n";
            $mensaje .= "Detalle del Pedido:\n";

            foreach ($carrito as $producto) {
                $mensaje .= "- {$producto['nombre']} x {$producto['cantidad']} = $ {$producto['precio']} \n";
            }

            // Enviar correo (reemplaza 'mail()' con un sistema SMTP si es necesario)
            if (mail($correo, $asunto, $mensaje)) {
                // Limpia el carrito después de completar el pago
                $carritoModel->limpiarCarrito($idUsuario);
                echo json_encode(['success' => 'Pago procesado y correo enviado correctamente.']);
            } else {
                echo json_encode(['error' => 'Error al enviar el correo.']);
            }
        } else {
            echo json_encode(["error" => "Acción no válida."]);
        }
        exit;
    } else {
        echo json_encode(["error" => "Método no permitido."]);
        exit;
    }
} catch (Exception $e) {
    echo json_encode(["error" => "Error del servidor: " . $e->getMessage()]);
    exit;
}

?>
