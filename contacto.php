<?php
// Asegúrate de incluir el autoload de PHPMailer
require 'vendor/autoload.php';  // Si usas Composer, de lo contrario usa la ruta correcta de la librería

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $nombre = htmlspecialchars($_POST['nombre']);
    $email = htmlspecialchars($_POST['email']);
    $mensaje = htmlspecialchars($_POST['mensaje']);

    // Crear una instancia de PHPMailer
    $mail = new PHPMailer(true);

    try {
        // Configuración del servidor SMTP
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';  // Usamos el servidor SMTP de Gmail
        $mail->SMTPAuth = true;
        $mail->Username = 'ozaris08@gmail.com';  // Tu correo de Gmail
        $mail->Password = 'ofdt opqi qchi wyvq';  // Tu contraseña o contraseña de aplicación de Gmail
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Destinatarios
        $mail->setFrom($email, $nombre);  // El correo desde el cual se envía (el que el usuario ingresó)
        $mail->addAddress('ozaris08@gmail.com', 'Ozaris');  // Correo de destino (al que se enviará el mensaje)

        // Contenido del mensaje
        $mail->isHTML(true);
        $mail->Subject = 'Nuevo mensaje de contacto desde el sitio web';
        $mail->Body    = "
            <html>
                <head>
                    <title>Nuevo mensaje de contacto</title>
                </head>
                <body>
                    <p><strong>Nombre:</strong> $nombre</p>
                    <p><strong>Correo Electrónico:</strong> $email</p>
                    <p><strong>Mensaje:</strong></p>
                    <p>$mensaje</p>
                </body>
            </html>
        ";

        // Enviar el correo
        if ($mail->send()) {
            echo 'Mensaje enviado correctamente.';
        } else {
            echo 'Hubo un problema al enviar el mensaje.';
        }
    } catch (Exception $e) {
        echo "Error al enviar el mensaje: {$mail->ErrorInfo}";
    }
}
?>
