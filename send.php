<?php
namespace PHPMailer\PHPMailer;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'assets/php/src/Exception.php';
require 'assets/php/src/PHPMailer.php';
require 'assets/php/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true);

$nombre = $_POST['name'];
$apellido = $_POST['apellido'];
$email = $_POST['correo'];
$mensaje = $_POST['mensaje'];

if ($nombre == "" || $apellido == "" || $email == "" || $mensaje == "") {
    //echo '<div class="alert alert-danger">Todos los campos son requeridos para el envio</div>';    
    echo '<script>alert("Todos los campos son requeridos para el envio");</script>';
    header("Location: contacto.html");
    exit;
} else {
    $maxAttempts = 3;  
    $currentAttempt = 0;

    try {
        // Configuración, cambiar correo y credenciales.
        $mail->isSMTP();
        $mail->Host = 'mail.allgarage.cl';
        $mail->SMTPAuth = true;
        $mail->Username = 'demo@allgarage.cl';
        $mail->Password = 'Qwerty.11';
        $mail->SMTPSecure = 'ssl';
        $mail->Port = 465;


        //$to = "efigueroa@allgarage.cl"; // el correo donde llegará todo
        $mail->setFrom($email);
        $mail->addAddress($to);
        $mail->Subject = 'Nuevo mensaje desde tu web';

        $mail->isHTML(true);
        $mail->Body = '<strong>' . $nombre . ' ' . $apellido . '</strong> te ha contactado desde tu web y ha enviado el siguiente mensaje: <br><p>' . $mensaje . '</p>';

        // Envío del correo electrónico
        $mail->send();


        header("Location: index.html");
        exit;
    } catch (Exception $e) {
        $currentAttempt++;

        if ($currentAttempt <= $maxAttempts) {
            // Registrar error y reintentar envío con retraso
            echo "Error al enviar correo (Intento: $currentAttempt): {$e->getMessage()}";
            sleep(5); 
        } else {
            // Limite de intentos excedido
            echo "Error al enviar correo: Limite de intentos excedido ($maxAttempts).";
        }
    }
}
?>

