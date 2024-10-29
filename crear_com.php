<?php
require "conexion.php";
session_start();
$con = conectar_bd();

if (isset($_POST["comentario"])) {
    
    $comentario = $_POST["comentario"];
    $puntuacion = $_POST["puntuacion"];
    $prod = $_POST['id_prod'];
    $email = $_COOKIE['email_emp'] ?? null;

    if (!empty($comentario) && $email) {
        crear_com($con, $comentario, $email, $prod, $puntuacion);
    } else {
        echo json_encode(["error" => "Por favor, completa todos los campos."]);
    }
}

function crear_com($con, $comentario, $email, $prod, $puntuacion) {
    $consulta_login = "SELECT * FROM persona WHERE email = '$email'";
    $resultado_login = mysqli_query($con, $consulta_login);

    if ($resultado_login && mysqli_num_rows($resultado_login) > 0) {
        $fila = mysqli_fetch_assoc($resultado_login);
        $id_per = $fila['Id_per'];
        $foto_perfil = $fila['foto'];

        // Inserta en la base de datos
        $consulta_insertar_persona = "INSERT INTO comentario (comentario, id_prod, id_per2, valoracion) VALUES ('$comentario', '$prod', '$id_per', '$puntuacion')";

        if (mysqli_query($con, $consulta_insertar_persona)) {
            // Actualizar el promedio de la valoración
            actualizar_promedio_valoracion($con, $prod);

            // Obtener el comentario insertado para retornarlo
            echo json_encode([
                "success" => true,
                "comentario" => htmlspecialchars($comentario),
                "valoracion" => htmlspecialchars($puntuacion),
                "nombre" => htmlspecialchars($fila['nombre_p']),
                "foto" => $foto_perfil // Pasar la foto en la respuesta
            ]);
        } else {
            echo json_encode(["error" => "Error al insertar en la base de datos: " . mysqli_error($con)]);
        }
    } else {
        echo json_encode(["error" => "No se encontró ningún usuario con ese email."]);
    }
}


function actualizar_promedio_valoracion($con, $prod) {
    $sql_avg = "SELECT AVG(valoracion) AS promedio FROM comentario WHERE id_prod='$prod'";
    $result_avg = mysqli_query($con, $sql_avg);
    
    if ($result_avg && $row = mysqli_fetch_assoc($result_avg)) {
        $promedio_valoracion = round($row['promedio'], 1);
    }
}
?>
