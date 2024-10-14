<?php

require "conexion.php";

$con = conectar_bd();

if (isset($_POST["envio-com"])) {
    $comentario = $_POST["comentario"];
    $email = $_COOKIE['email_emp'] ?? null;

    if (!empty($comentario) && $email) {
        crear_com($con, $comentario, $email);
    } else {
        echo json_encode(["error" => "Por favor, completa todos los campos."]);
    }
}

function crear_com($con, $comentario, $email) {
    $consulta_login = "SELECT * FROM persona WHERE email = '$email'";
    $resultado_login = mysqli_query($con, $consulta_login);

    if ($resultado_login && mysqli_num_rows($resultado_login) > 0) {
        $fila = mysqli_fetch_assoc($resultado_login);
        $id_per = $fila['Id_per'];

        // Inserta en la base de datos
        $consulta_insertar_persona = "INSERT INTO comentario (comentario, id_per2) VALUES ('$comentario', '$id_per')";

        if (mysqli_query($con, $consulta_insertar_persona)) {
            // Retornar el comentario insertado
            echo json_encode(["success" => "Publicación creada exitosamente.", "comentario" => htmlspecialchars($comentario)]);
        } else {
            echo json_encode(["error" => "Error al insertar en publicacion_prod: " . mysqli_error($con)]);
        }
    } else {
        echo json_encode(["error" => "No se encontró ningún usuario con ese email."]);
    }
}
?>
