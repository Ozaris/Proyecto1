<?php

require_once "conexion.php";
include_once "mispublicaciones.php";
$con = conectar_bd();
$id_prod=$_POST['id_prod'];
    
    // Realiza una consula para eliminar una publicación en específico
    $consulta_actualizar_descripcion = "DELETE FROM publicacion_prod WHERE id_prod='$id_prod'";
    
    if (mysqli_query($con, $consulta_actualizar_descripcion)) {
       echo "Se elimino correctamente la publicación";
        exit();
    } else {
        echo "Error al actualizar la descripción: " . mysqli_error($con) . "<br>";
    }



?>