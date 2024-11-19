<?php
ob_start();

require_once("conexion.php");
require_once("registerempresas.html");
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
$con = conectar_bd();

// Comprobar que se envió un formulario por POST desde carga_datos
if (isset($_POST["envio-emp"])) {
    $nombre_p = $_POST["nombre_p"];
    $email = $_POST["email"];
    $desc = $_POST["Descripcion"];
    $contrasenia = $_POST["pass"];
    $rol = $_POST["empresa"];
    $foto = isset($_POST["default.png"]) ? $_POST["default.png"] : 'default.png'; // Asegúrate de que el índice está definido

    $existe_usr = consultar_existe_usr($con, $email);
    $existe_nom = consultar_existe_nom($con, $nombre_p);

    // Insertar datos si el usuario no existe
    insertar_datos($con, $nombre_p, $email, $contrasenia, $rol, $existe_usr, $existe_nom, $foto,$desc);
}


function consultar_existe_usr($con, $email) {

    $email = mysqli_real_escape_string($con, $email); // Escapar los campos para evitar inyección SQL
    $consulta_existe_usr = "SELECT email FROM persona WHERE email = '$email'";
    $resultado_existe_usr = mysqli_query($con, $consulta_existe_usr);

    if (mysqli_num_rows($resultado_existe_usr) > 0) {
        return true;
    } else {
        return false;
    }
}


function consultar_existe_nom($con, $nombre_p) {

    $nombre_p = mysqli_real_escape_string($con, $nombre_p); // Escapar los campos para evitar inyección SQL
    $consulta_existe_nom = "SELECT nombre_p FROM persona WHERE nombre_p = '$nombre_p'";
    $resultado_existe_nom = mysqli_query($con, $consulta_existe_nom);

    if (mysqli_num_rows($resultado_existe_nom) > 0) {
        return true;
    } else {
        return false;
    }
}



function insertar_datos($con, $nombre_p, $email, $contrasenia,$rol,$existe_nom,$existe_usr,$foto,$desc) {
    // Encripto la contraseña usando la función password_hash

    if ($existe_usr == false && $existe_nom == false){
        $email=mysqli_real_escape_string($con,$email);
       
    $contrasenia = password_hash($contrasenia, PASSWORD_DEFAULT);

    // Generar un código de verificación de 6 caracteres
    $token = strtoupper(substr(bin2hex(random_bytes(3)), 0, 6));

    // Inserta en la tabla persona
    $consulta_insertar_persona = "INSERT INTO persona (nombre_p, email, contrasenia, token, rol, foto,descripcion) VALUES ('$nombre_p', '$email', '$contrasenia', '$token', '$rol', '$foto','$desc')";
    
    if (mysqli_query($con, $consulta_insertar_persona)) {
        // Obtén el ID de la persona recién insertada
        $id_per = mysqli_insert_id($con);

        // Inserta en la tabla usuario usando el ID de la persona
        $consulta_insertar_usuario = "INSERT INTO empresa (Id_per, nombre_p, email, contrasenia) VALUES ($id_per, '$nombre_p', '$email', '$contrasenia')";
      
        if (mysqli_query($con, $consulta_insertar_usuario)) {
                 // Enviar correo de verificación
                 sendVerificationEmail($email, $token);
                
                 // Redirigir a la página de verificación
                 header("Location: codigo_verificacion.php");
                 exit();
             } else {
                 echo "<div style='display: flex; border: 1px solid red; color: red; padding: 5px 8px; border-radius: 5px; background-color: rgb(255, 167, 167);'> <i class='fas fa-exclamation-triangle' style='margin-right: 8px;'></i> Error al insertar en usuario </div> " . mysqli_error($con);
             }
         } else {
             echo "<div style='display: flex; border: 1px solid red; color: red; padding: 5px 8px; border-radius: 5px; background-color: rgb(255, 167, 167);'> <i class='fas fa-exclamation-triangle' style='margin-right: 8px;'></i> Error al insertar en persona </div> " . mysqli_error($con);
         }
     } else {
         echo "<div style='display: flex; border: 1px solid red; color: red; padding: 5px 8px; border-radius: 5px; background-color: rgb(255, 167, 167);'> <i class='fas fa-exclamation-triangle' style='margin-right: 8px;'></i> Esta empresa ya existe </div>" . mysqli_error($con);
     }
 }


function consultar_datos($con) {
    $consulta = "SELECT * FROM persona";
    $resultado = mysqli_query($con, $consulta);

    // Inicializo una variable para guardar los resultados
    $salida = "";

    // Si se encuentra algún registro de la consulta
    if (mysqli_num_rows($resultado) > 0) {
        // Mientras haya registros
        header("Location: iniciodesesion.html");
        while ($fila = mysqli_fetch_assoc($resultado)) {
            $salida .= "id: " . $fila["Id_per"] . " - Nombre: " . $fila["nombre_p"] . " - Email: " . $fila["email"] . "<br> <hr>";
        }
    } else {
        $salida = "Sin datos";
    }

    return $salida;
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