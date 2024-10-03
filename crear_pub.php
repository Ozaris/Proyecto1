<?php

require "conexion.php";

$con= conectar_bd();

if (isset($_POST["envio-pub"])){
    $titulo = $_POST["titulo"];
    $categoria = $_POST["categoria"];
    $descripcion = $_POST["descripcion"];

    // Llamada funcion login
    crear_pub($con, $titulo,$categoria,$descripcion);
}

function crear_pub($con, $titulo,$categoria,$descripcion) {
    $consulta_login = "SELECT * FROM persona WHERE email= '$email'";
    $resultado_login = mysqli_query($con, $consulta_login);
        // Inserta en la tabla persona
        $consulta_insertar_persona = "INSERT INTO publicacion_prod(Id_per,titulo,categoria,descripcion) VALUES ('$id_per','$titulo','$categoria','$cdescripcion')";
        
        if (mysqli_query($con, $consulta_insertar_persona)) {
            // Obtén el ID de la persona recién insertada
            $id_per = mysqli_insert_id($con);
    
            // Inserta en la tabla usuario usando el ID de la persona
            $consulta_insertar_usuario = "INSERT INTO usuario (Id_per, nombre_p, email, contrasenia) VALUES ($id_per, '$nombre_p', '$email', '$contrasenia')";
    
            if (mysqli_query($con, $consulta_insertar_usuario)) {
                $salida = consultar_datos($con);
                echo $salida;
            } else {
                echo "Error al insertar en usuario: " . mysqli_error($con);
            }
        } else {
            echo "Error al insertar en persona: " . mysqli_error($con);
        }
   
    
    
}

    ?>
