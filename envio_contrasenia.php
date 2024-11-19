<?php
session_start();
require_once("conexion.php");
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$con = conectar_bd();

if (isset($_POST["email"])) {
    $email = $_POST["email"];

    // Check if email exists
    if (consultar_existe_usr($con, $email)) {
        // Generate a reset token
        $token = strtoupper(substr(bin2hex(random_bytes(3)), 0, 6));

        // Store token in the database (you may create a new table for reset tokens)
        $consulta_insertar_token = "UPDATE persona SET token='$token' WHERE email='$email'";
        mysqli_query($con, $consulta_insertar_token);

        // Send email with reset token
        sendResetEmail($email, $token);
        header("Location: cambiar_contrasenia.html");
        exit();
    } else {
        echo "Email no encontrado.";
    }
}

// Function to check if user exists
function consultar_existe_usr($con, $email) {
    $email = mysqli_real_escape_string($con, $email);
    $consulta_existe_usr = "SELECT email FROM persona WHERE email = '$email'";
    $resultado_existe_usr = mysqli_query($con, $consulta_existe_usr);
    return mysqli_num_rows($resultado_existe_usr) > 0;
}

// Function to send reset email
function sendResetEmail($email, $token) {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; 
        $mail->SMTPAuth = true; 
        $mail->Username = 'ozaris08@gmail.com'; 
        $mail->Password = 'ofdt opqi qchi wyvq'; // Use environment variables or a secure method for storing passwords
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port = 465;

        $mail->setFrom('ozaris08@gmail.com', 'Ozaris');
        $mail->addAddress($email);
        $mail->isHTML(true);
        $mail->Subject = 'Cambio de contraseña';
        $mail->Body = "Uiliza este token para cambiar tu contraseña:$token";
        $mail->AltBody = "Uiliza este token para cambiar tu contraseña:$token";

        $mail->send();
    } catch (Exception $e) {
        echo "Error al enviar: {$mail->ErrorInfo}";
    }
}

mysqli_close($con);
?>
