<?php
require_once '../../models/php/modelo_email_carrito.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);

    if (isset($input['correo'], $input['direccion'], $input['metodoPago'])) {
        $correo = $input['correo'];
        $direccion = $input['direccion'];
        $metodoPago = $input['metodoPago'];

        // Genera un mensaje de confirmación
        $mensaje = "
            <h1>¡Gracias por tu compra!</h1>
            <p>Tu pedido está en proceso.</p>
            <p><strong>Dirección:</strong> $direccion</p>
            <p><strong>Método de pago:</strong> $metodoPago</p>
        ";

        // Llama al modelo para enviar el correo
        $emailEnviado = EmailSender::sendOrderConfirmation($correo, "Confirmación de Pedido", $mensaje);

        if ($emailEnviado) {
            echo json_encode(['success' => 'Correo enviado con éxito.']);
        } else {
            echo json_encode(['error' => 'Error al enviar el correo.']);
        }
    } else {
        echo json_encode(['error' => 'Datos incompletos para procesar el pago.']);
    }
}
?>
