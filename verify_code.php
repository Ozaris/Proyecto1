<?php
session_start();
require_once("conexion.php");

$con = conectar_bd();

// Verificar si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Asegúrate de que el email y el token estén presentes
    if (isset($_POST['email']) && isset($_POST['token'])) {
        $email = mysqli_real_escape_string($con, $_POST['email']);
        $token = mysqli_real_escape_string($con, $_POST['token']);

        // Consulta para verificar el token en la base de datos
        $consulta = "SELECT token FROM persona WHERE email = '$email'";
        $resultado = mysqli_query($con, $consulta);

        if ($resultado && mysqli_num_rows($resultado) > 0) {
            $fila = mysqli_fetch_assoc($resultado);
            if ($fila['token'] === $token) {
                // Token válido, procede a iniciar sesión o realizar otra acción
                echo "Código de verificación correcto. ¡Bienvenido!";
                
                // Puedes redirigir al usuario a la página de inicio de sesión
                header("Location: iniciodesesion.html");
                exit();
            } else {
                echo "El código de verificación es incorrecto. Intenta de nuevo.";
            }
        } else {
            echo "No se encontró el usuario con ese correo electrónico.";
        }
    } else {
        echo "Por favor, completa todos los campos.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/style.css">
    <title>Verificar Código</title>
</head>
<body>
    <div class="container">
        <h1>Verifica tu Código</h1>
        <form action="verify_code.php" method="POST">
            <input type="email" name="email" placeholder="Tu Email" required>
            <input type="text" name="token" placeholder="Código de Verificación" required>
            <button type="submit">Verificar</button>
        </form>
    </div>
</body>
</html>
