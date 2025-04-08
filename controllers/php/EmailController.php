<?php
require_once '../vendor/phpmailer/phpmailer/src/Exception.php';
require_once '../vendor/phpmailer/phpmailer/src/PHPMailer.php';
require_once '../vendor/phpmailer/phpmailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class EmailController {
    public function enviarEmail() {
        if (isset($_GET['nombre'], $_GET['email'], $_GET['mensaje'])) {
            $nombre = htmlspecialchars($_GET['nombre']);
            $email = filter_var($_GET['email'], FILTER_VALIDATE_EMAIL);
            $mensaje = htmlspecialchars($_GET['mensaje']);

            if (!$email) {
                echo "Correo electrónico no válido";
                return;
            }

            $destinatario = "cristianmolano415@gmail.com";
            $asunto = "Hola mundo $nombre";
            $cuerpo = $this->crearCuerpoEmail($nombre, $email, $mensaje);

            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'cristianmolano415@gmail.com';
                $mail->Password = 'popf hxdh kjmu ouao';
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                $mail->setFrom($email, $nombre);
                $mail->addAddress($destinatario);
                $mail->addReplyTo($email, $nombre);

                $mail->isHTML(true);
                $mail->Subject = $asunto;
                $mail->Body = $cuerpo;

                $mail->send();
                echo "El mensaje se ha enviado correctamente.";
            } catch (Exception $e) {
                echo "Hubo un error al enviar el mensaje. Mailer Error: {$mail->ErrorInfo}";
            }
        } else {
            echo "Faltan datos en solicitud";
        }
    }

    private function crearCuerpoEmail($nombre, $email, $mensaje) {
        return "
        <html>
        <head>
            <title>Nuevo Mensaje</title>
        </head>
        <body>
            <p><strong>Nombre: </strong> $nombre</p>
            <p><strong>Correo Electrónico: </strong> $email</p>
            <p><strong>Mensaje: </strong></p>
            <p>$mensaje</p>
        </body>
        </html>
        ";
    }
}
?>