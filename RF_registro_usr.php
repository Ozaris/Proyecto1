<?php
session_start();
require_once("conexion.php");
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$con = conectar_bd();

if (isset($_POST["envio"])) {
    $nombre_p = $_POST["nombre_p"];
    $email = $_POST["email"];
    $contrasenia = $_POST["pass"];
    $rol = $_POST["usuario"];
    $foto = isset($_POST["default.png"]) ? $_POST["default.png"] : 'default.png';

    if (!consultar_existe_usr($con, $email) && !consultar_existe_nom($con, $nombre_p)) {
        // Hash the password
        $contrasenia = password_hash($contrasenia, PASSWORD_DEFAULT);
        
        // Generar un código de verificación de 6 caracteres
        $token = strtoupper(substr(bin2hex(random_bytes(3)), 0, 6));

        // Insertar usuario en la base de datos
        $consulta_insertar_persona = "INSERT INTO persona (nombre_p, email, contrasenia, token, rol, foto) VALUES ('$nombre_p', '$email', '$contrasenia', '$token', '$rol', '$foto')";

        if (mysqli_query($con, $consulta_insertar_persona)) {
            // Obtén el ID de la persona recién insertada
            $id_per = mysqli_insert_id($con);

            // Inserta en la tabla usuario usando el ID de la persona
            $consulta_insertar_usuario = "INSERT INTO usuario (Id_per, nombre_p, email, contrasenia) VALUES ($id_per, '$nombre_p', '$email', '$contrasenia')";

            if (mysqli_query($con, $consulta_insertar_usuario)) {
                // Enviar correo de verificación
                sendVerificationEmail($email, $token);
                
                // Redirigir a la página de verificación
                header("Location: verify_code.php");
                exit();
            } else {
                echo "Error al insertar en usuario: " . mysqli_error($con);
            }
        } else {
            echo "Error al insertar en persona: " . mysqli_error($con);
        }
    } else {
        echo "Usuario ya existe: " . mysqli_error($con);
    }
}

// Funciones para verificar si el usuario o el nombre existen
function consultar_existe_usr($con, $email) {
    $email = mysqli_real_escape_string($con, $email);
    $consulta_existe_usr = "SELECT email FROM persona WHERE email = '$email'";
    $resultado_existe_usr = mysqli_query($con, $consulta_existe_usr);
    return mysqli_num_rows($resultado_existe_usr) > 0;
}

function consultar_existe_nom($con, $nombre_p) {
    $nombre_p = mysqli_real_escape_string($con, $nombre_p);
    $consulta_existe_nom = "SELECT nombre_p FROM persona WHERE nombre_p = '$nombre_p'";
    $resultado_existe_nom = mysqli_query($con, $consulta_existe_nom);
    return mysqli_num_rows($resultado_existe_nom) > 0;
}

function sendVerificationEmail($email, $token) {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; 
        $mail->SMTPAuth = true; 
        $mail->Username = 'ozaris08@gmail.com'; 
        $mail->Password = 'ofdt opqi qchi wyvq'; 
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port = 465;

        $mail->setFrom('ozaris08@gmail.com', 'Ozaris');
        $mail->addAddress($email);
        $mail->isHTML(true);
        $mail->Subject = 'Tu Código de Verificación';
        $mail->Body = "Tu código de verificación es: <b>$token</b>";
        $mail->AltBody = "Tu código de verificación es: $token";

        $mail->send();
        echo "Correo enviado con éxito.";
    } catch (Exception $e) {
        echo "El mensaje no pudo ser enviado. Error: {$mail->ErrorInfo}";
    }
}

mysqli_close($con);
?>
