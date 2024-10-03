<?php
ob_start();
require_once("conexion.php");
require_once("registerclientes.html");

$con = conectar_bd();

// Comprobar que se envió un formulario por POST desde carga_datos
if (isset($_POST["envio"])) {

    $nombre_p =  $_POST["nombre_p"];
    $email = $_POST["email"];
    $contrasenia = $_POST["pass"];
    $rol=$_POST["usuario"];
    $foto=$_POST["default.png"];
   
    // Consultar si el usuario ya existe
    $existe_usr = consultar_existe_usr($con, $email);
    $existe_nom = consultar_existe_nom($con, $nombre_p);

    // Insertar datos si el usuario no existe
    insertar_datos($con, $nombre_p,$email, $contrasenia, $rol,$existe_usr,$existe_nom,$foto);

}

function consultar_existe_usr($con, $email) {

    $email = mysqli_real_escape_string($con, $email); // Escapar los campos para evitar inyección SQL
    $consulta_existe_usr = "SELECT email FROM usuario WHERE email = '$email'";
    $resultado_existe_usr = mysqli_query($con, $consulta_existe_usr);

    if (mysqli_num_rows($resultado_existe_usr) > 0) {
        return true;
    } else {
        return false;
    }
}


function consultar_existe_nom($con, $nombre_p) {

    $nombre_p = mysqli_real_escape_string($con, $nombre_p); // Escapar los campos para evitar inyección SQL
    $consulta_existe_nom = "SELECT nombre_p FROM usuario WHERE nombre_p = '$nombre_p'";
    $resultado_existe_nom = mysqli_query($con, $consulta_existe_nom);

    if (mysqli_num_rows($resultado_existe_nom) > 0) {
        return true;
    } else {
        return false;
    }
}



function insertar_datos($con, $nombre_p, $email, $contrasenia,$rol,$existe_nom,$existe_usr,$foto) {
    // Encripto la contraseña usando la función password_hash

    if ($existe_usr == false && $existe_nom == false){
        $email=mysqli_real_escape_string($con,$email);
       
    $contrasenia = password_hash($contrasenia, PASSWORD_DEFAULT);

    // Inserta en la tabla persona
    $consulta_insertar_persona = "INSERT INTO persona (nombre_p, email, contrasenia,rol,foto) VALUES ('$nombre_p', '$email', '$contrasenia','$rol','$foto')";
    
    if (mysqli_query($con, $consulta_insertar_persona)) {
        // Obtén el ID de la persona recién insertada
        $id_per = mysqli_insert_id($con);

        // Inserta en la tabla usuario usando el ID de la persona
        $consulta_insertar_usuario = "INSERT INTO usuario (Id_per, nombre_p, email, contrasenia) VALUES ($id_per, '$nombre_p', '$email', '$contrasenia')";

        if (mysqli_query($con, $consulta_insertar_usuario)) {
            $salida = consultar_datos($con);
            header("Location: iniciodesesion.html");
            echo $salida;
        } else {
            echo "Error al insertar en usuario: " . mysqli_error($con);
        }
    } else {
        echo "Error al insertar en persona: " . mysqli_error($con);
    }
}else{
        echo "Usuario ya existe: " . mysqli_error($con);
    }
}


function consultar_datos($con) {
    $consulta = "SELECT * FROM usuario";
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
    header("Location: index.php");
}

mysqli_close($con);
?>