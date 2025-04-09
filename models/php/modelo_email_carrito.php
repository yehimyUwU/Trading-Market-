<?php
// models/EmailSender.php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../vendor/autoload.php';

class EmailSender {
    public static function sendOrderConfirmation($toEmail, $subject, $message) {
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; // Cambia esto
            $mail->SMTPAuth = true;
            $mail->Username = 'juanestebanstt@gmail.com'; // Cambia esto
            $mail->Password = 'ajzw qgqm tviq revx'; // Cambia esto
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('juanestebanstt@gmail.com', 'Trading market');
            $mail->addAddress($toEmail);

            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = $message;

            $mail->send();
            return true;
        } catch (Exception $e) {
            error_log("Error al enviar el correo: " . $mail->ErrorInfo);
            return false;
        }
    }
}
?>
