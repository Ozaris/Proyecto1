<?php
require "conexion.php";
session_start();
$con = conectar_bd();

$prod = $_COOKIE['pub'] ?? null;
if (isset($_POST["envio-com"])) {
    
    $comentario = $_POST["comentario"];
    $email = $_COOKIE['email_emp'] ?? null;
   
    if (!empty($comentario) && $email) {
        crear_com($con, $comentario, $email,$prod);
    } else {
        echo json_encode(["error" => "Por favor, completa todos los campos."]);
    }
}



function crear_com($con, $comentario, $email, $prod) {
    $consulta_login = "SELECT * FROM persona WHERE email = '$email'";
    $resultado_login = mysqli_query($con, $consulta_login);

    if ($resultado_login && mysqli_num_rows($resultado_login) > 0) {
        $fila = mysqli_fetch_assoc($resultado_login);
        $id_per = $fila['Id_per'];

        // Inserta en la base de datos
        $consulta_insertar_persona = "INSERT INTO comentario (comentario, id_prod, id_per2) VALUES ('$comentario', '$prod', '$id_per')";

        if (mysqli_query($con, $consulta_insertar_persona)) {
            // Obtener el comentario insertado para retornarlo
            echo json_encode([
                "success" => true,
                "comentario" => htmlspecialchars($comentario),
                "nombre" => htmlspecialchars($fila['nombre_p']) // Asegúrate de tener el campo 'nombre_p' en tu tabla
            ]);
        } else {
            echo json_encode(["error" => "Error al insertar en la base de datos: " . mysqli_error($con)]);
        }
    } else {
        echo json_encode(["error" => "No se encontró ningún usuario con ese email."]);
    }
}
?>
