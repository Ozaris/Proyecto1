<?php
session_start();
require_once("conexion.php");

$con = conectar_bd();

if (isset($_POST["token"]) && isset($_POST["password"])) {
    $token = $_POST["token"];
    $new_password = password_hash($_POST["password"], PASSWORD_DEFAULT);

    // Check if token is valid
    $consulta = "SELECT email FROM persona WHERE token='$token'";
    $resultado = mysqli_query($con, $consulta);

    if (mysqli_num_rows($resultado) > 0) {
        // Update the password and clear the token
        $email = mysqli_fetch_assoc($resultado)['email'];
        $update_password = "UPDATE persona SET contrasenia='$new_password', token=NULL WHERE email='$email'";
        
        if (mysqli_query($con, $update_password)) {
            header("Location: iniciodesesion.html");
            exit();
        } else {
            echo "Error updating password.";
        }
    } else {
        echo "Invalid token.";
    }
}

mysqli_close($con);
?>
