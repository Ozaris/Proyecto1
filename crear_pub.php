<?php

require "conexion.php";

$con = conectar_bd();

if (isset($_POST["envio-pub"])) {
    $titulo = $_POST["titulo"];
    $categoria = $_POST["categoria"];
    $descripcion = $_POST["descripcion"];
    $img = $_POST['imagen_prod'];

    $email_emp = $_COOKIE['email_emp'] ?? null; // Obtener el email de la sesión

    // Llamada a la función para crear la publicación
    crear_pub($con, $titulo, $categoria, $descripcion, $email_emp, $img);
}

function crear_pub($con, $titulo, $categoria, $descripcion, $email_emp, $img) {
    // Preparar la consulta para obtener el ID de la persona
    $consulta_login = "SELECT * FROM persona WHERE email = '$email_emp'";
    $resultado_login = mysqli_query($con, $consulta_login);

    // Verificar si se encontró un registro
    if ($resultado_login && mysqli_num_rows($resultado_login) > 0) {
        // Obtener la fila como un array asociativo
        $fila = mysqli_fetch_assoc($resultado_login);
        $id_per = $fila['Id_per']; // Almacena el ID

        // Consulta para insertar en la tabla publicacion_prod
        $consulta_insertar_persona = "INSERT INTO publicacion_prod (titulo, categoria, descripcion, imagen_prod, Id_per) VALUES ('$titulo', '$categoria', '$descripcion', '$img', '$id_per')";
        
        if (mysqli_query($con, $consulta_insertar_persona)) {
            echo "Publicación creada exitosamente.";
        } else {
            echo "Error al insertar en publicacion_prod: " . mysqli_error($con);
        }
    } else {
        echo "No se encontró ningún usuario con ese email.";
    }
}

?>