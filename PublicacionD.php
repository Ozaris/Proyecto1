<?php
include "conexion.php";
session_start();
$con= conectar_bd();


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id_prod'])) {
    $id_prod = $_POST['id_prod'];

    $sql = "SELECT * FROM publicacion_prod WHERE id_prod='$id_prod'";
    $resultado = $con->query($sql);

    $sql2 = "SELECT e.nombre_p FROM empresa e JOIN publicacion_prod p ON e.Id_per=p.id_per WHERE id_prod='$id_prod'";
    $resultado2 = $con->query($sql2);

    if ($data = $resultado->fetch_assoc()) {
        $titulo_emp = $data['titulo'];
        $cat_emp = $data['categoria'];
        $foto_pub = $data['imagen_prod'];
        $desc_emp = $data['descripcion'];
    }

    if ($resultado2 && $data2 = $resultado2->fetch_assoc()) {
        $nom_pub = $data2['nombre_p'];
    } else {
        $nombre_p = 'Nombre no disponible';
        $email = 'Email no disponible';
        $foto = 'default.png'; // Imagen predeterminada
    }
}
?>


<!DOCTYPE html>
<html class="htmlpubliD" lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="style/style.css">
    <title>Publicacion</title>
</head>
<body class="bodypubliD">

    <div class="divprincipalD">

        <div class="divsec1publiD">
            <img class="imagenprincipalcomentarios" src="<?php echo $foto_pub?>" alt="img">
        </div>

        <div class="divsec2publiD">
            <h2 class="titulopubliD">Informacion</h2>
            <div class="divinfopubliD">
                <h3 class="letraspubliD"><?php echo $nom_pub;?></h3>
                <h3 class="letraspubliD"><?php echo $desc_emp;?></h3>
                <h3 class="letraspubliD">Telefono</h3>
            </div>

            <div class="divinfopubliD">
                <h3 class="letraspubliD">Ubicacion</h3>
                <h3 class="letraspubliD"><?php echo $cat_emp;?></h3>
                <h3 class="letraspubliD">Valoracion</h3>
            </div>
        </div>
    </div>
    
    <form action="">
        <div class="divprincipalcomentarios">
            <img class="imagenperfilcomentarios" src="style/Imagenes/GatoFotoPruebaPerfil.png" alt="img">
            <input type="text" class="inputcomentarios" placeholder="Comentario" aria-label="Comentario">
            <button class="botoncomentar"><i class="fa-solid fa-arrow-right"></i></button>
        </div>
        <div class="divinfocomentarios">
            <h2>Comentarios</h2><i class="fa-solid fa-arrow-right"></i>
        </div>
        <div class="divprincipalcomentarios2">
            <div class="divimagenperfilcomentarios2">
                <img class="imagenperfilcomentarios2" src="style/Imagenes/GatoFotoPruebaPerfil.png" alt="img">
            </div>
            <div class="divinformacioncomentarios">
                <h2>Nombre</h2>
                <h4>Comentario</h4>
                <h2>Valoracion</h2>
            </div>
        </div>
    </form>

</body>
</html>