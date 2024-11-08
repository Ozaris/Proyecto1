<?php

require "conexion.php";
require "empresas.php";
$con = conectar_bd();

if (isset($_POST["envio-pub"])) {
    $titulo = $_POST["titulo"];
    $categoria = $_POST["categoria"];
    $descripcion = $_POST["descripcion"];
    $email_emp = $_COOKIE['email_emp'] ?? null;

    
    if (isset($_FILES['imagen_prod']) && $_FILES['imagen_prod']['error'] == 0) {
    //se guarda la imagen en la ruta deseada
        $imagen = $_FILES['imagen_prod'];
        $rutaDestino = 'uploads/' . basename($imagen['name']);

        // Mueve el archivo a la carpeta deseada
        if (move_uploaded_file($imagen['tmp_name'], $rutaDestino)) {
          
            crear_pub($con, $titulo, $categoria, $descripcion, $email_emp, $rutaDestino);
        } else {
            echo "Error al subir la imagen.";
        }
    } else {
        echo "No se ha seleccionado ninguna imagen o ha ocurrido un error.";
    }
}

function crear_pub($con, $titulo, $categoria, $descripcion, $email_emp, $img) {
    $consulta_login = "SELECT * FROM persona WHERE email = '$email_emp'";
    $resultado_login = mysqli_query($con, $consulta_login);

    if ($resultado_login && mysqli_num_rows($resultado_login) > 0) {
        $fila = mysqli_fetch_assoc($resultado_login);
        $id_per = $fila['Id_per'];

        // Inserta en la base de datos
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