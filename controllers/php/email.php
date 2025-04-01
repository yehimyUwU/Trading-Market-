<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/phpmailer/phpmailer/src/Exception.php';
require '../vendor/phpmailer/phpmailer/src/PHPMailer.php';
require '../vendor/phpmailer/phpmailer/src/SMTP.php';

if (isset($_GET['nombre'], $_GET['email'], $_GET['mensaje'])){
    $nombre = htmlspecialchars($_GET['nombre']);
    $email = filter_var($_GET['email']);
    $mensaje = htmlspecialchars($_GET['mensaje']);

if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
    echo "Correo electronico no valido";
    exit;
}


$destinatario = "cristianmolano415@gmail.com";

$asunto = "Hola mundo $nombre";

$cuerpo = "
<html>
<head>
    <title>Nuevo Mensaje</title>
</head>
<body>
    <p><strong>Nombre: </strong> $nombre</p>
    <p><strong>Correo Electronico: </strong> $email</p>
    <p><strong>Mensaje: </strong></p>
    <p>$mensaje</p>
</body>
</html>
";

$mail = new PHPMailer (true);
try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'cristianmolano415@gmail.com';
    $mail->Password = 'popf hxdh kjmu ouao';
    $mail->SMTPSecure = PHPMailer :: ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    $mail->setFrom($email, $nombre);
    $mail->addAddress($destinatario);
    $mail->addReplyTo($email,$nombre);


    $mail->isHTML(true);
    $mail->Subject = $asunto;
    $mail->Body = $cuerpo;

    $mail ->send();
    echo "El mensaje se ha enviado correctamente.";
    } catch (Exception $e)   {
        echo "Hubo un error al enviar el mensaje. Mailer Error: {$mail ->Error}";
    }
} else{
    echo "Faltan datos en solicitud";
}
?>