<?php
/* INICIA LA SESIÓN Y TOMA LOS DATOS*/
include "conexion.php";
$con = conectar_bd();
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: Perfil.php");
    exit(); 
}

$email = $_SESSION['email'];
$sql = "SELECT * FROM usuario WHERE email='$email'";
$resultado = $con->query($sql);

if ($data = $resultado->fetch_assoc()) {
    $nombre_p = $data['nombre_p'];
    $email = $data['email'];
    $foto = $data['foto'];
} else {
    $nombre_p = 'Nombre no disponible';
    $email = 'Email no disponible';
    $foto = 'default.png'; // Imagen predeterminada
}



// Actualizar foto de perfil

if (isset($_POST["envio-edit-ft-usr"])) {
    try {
        // Archivo subido
        $archivo = $_FILES["foto_usr"];
        
        if ($archivo["error"] === UPLOAD_ERR_OK) {
            $nombre_temporal = $archivo["tmp_name"];
            $nombre_archivo = basename($archivo["name"]); // Usa basename para evitar problemas con rutas

            // Mueve el archivo a una carpeta específica en el servidor
            $ruta_destino = "img_usr/$nombre_archivo";
            if (move_uploaded_file($nombre_temporal, $ruta_destino)) {
                // Actualiza el nombre de la foto en la base de datos
                $sql = "UPDATE usuario SET foto = ? WHERE email = ?";
                $stmt = $con->prepare($sql);
                $stmt->bind_param('ss', $nombre_archivo, $email);
                $stmt->execute();
                
                // Actualiza la variable de foto en el script
                $foto = $nombre_archivo;
                
                // Redirige a la misma página para reflejar el cambio
                header("Location: Perfil.php");
                exit();
            } else {
                echo "Error al mover el archivo al servidor.";
            }
        } else {
            echo "Error al subir el archivo.";
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}

$picture_to_show = "img_usr/" . ($foto ?: 'default.png');

/* ACTUALIZA EL NOMBRE DE USUARIO EN LA TABLA PERSONA Y USUARIO*/

if (isset($_POST["envio-edit-nom-usr"])) {
    // Obtener valores del formulario
    $edit_nom_usr = $_POST["edit_nom_usr"];
    // Asegúrate de obtener el valor de $nombre_p
    $nombre_p = $data['nombre_p'];
    // Consultar si el nombre ya existe
    $existe_nom = consultar_existe_nom($con, $edit_nom_usr);

    // Actualizar la base de datos
    actualizar($con, $edit_nom_usr, $nombre_p, $existe_nom);
}

function consultar_existe_nom($con, $edit_nom_usr) {
    $edit_nom_usr = mysqli_real_escape_string($con, $edit_nom_usr);
    $consulta_existe_nom = "SELECT nombre_p FROM persona WHERE nombre_p = '$edit_nom_usr'";
    $resultado_existe_nom = mysqli_query($con, $consulta_existe_nom);

    return mysqli_num_rows($resultado_existe_nom) > 0;
}

function actualizar($con, $edit_nom_usr, $nombre_p, $existe_nom) {
    // Escapar los campos para evitar inyección SQL
    $edit_nom_usr = mysqli_real_escape_string($con, $edit_nom_usr);
    $nombre_p = mysqli_real_escape_string($con, $nombre_p);

    if (!$existe_nom) {
        // Actualizar en la tabla persona
        $consulta_actualizar_persona = "UPDATE persona SET nombre_p = '$edit_nom_usr' WHERE nombre_p = '$nombre_p'";
        if (mysqli_query($con, $consulta_actualizar_persona)) {
            // Actualizar en la tabla usuario
            $consulta_actualizar_usuario = "UPDATE usuario SET nombre_p = '$edit_nom_usr' WHERE nombre_p = '$nombre_p'";
            if (mysqli_query($con, $consulta_actualizar_usuario)) {
               
            } else {
                echo "Error al actualizar en usuario: " . mysqli_error($con);
            }
        } else {
            echo "Error al actualizar en persona: " . mysqli_error($con);
        }
    } else {
        echo "El usuario ya existe.";
    }
}




?>








<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link class="aslinkcarta" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <title>Tu Perfil</title>
</head>
<body class="bodyperfil">
    <div class="divcolorperfil">

        <div class="divcolorperfil2">
            <a href="index.html"><i class="fa-solid fa-2x fa-arrow-left-long iconoatrasperfil"></i></a>
            <a class="cerrarsesionperfil" href="logout.php">Cerrar sesión</a>
            <button><i class="fa-solid fa-1x fa-pen-to-square iconoeditar1perfil"></i></button>

            <div class="btn-group">
                <button type="button" class="iconoeditarfotoperfil" data-bs-toggle="dropdown" data-bs-display="static" aria-expanded="false">
                    <i class="fa-solid fa-1x fa-pen-to-square"></i>
                </button>
                <form action="Perfil.php" method="POST" enctype="multipart/form-data">
                <ul class="dropdown-menu">
                    <div class="mb-3">
                        <label for="formFile" class="form-label"><i class="fa-solid fa-2x fa-plus iconomas"></i></label>
                        <input class="form-control form-control1" type="file" id="formFile" name="foto_usr">
                        <button class="BotonPublicarPerfil" type="submit" name="envio-edit-ft-usr">Publicar</button>
                    </div>
                </ul>
            </div>
            </form>
            <div class="divfotoperfil">
                <div class="imagenperfilcontainer">
                    <img class="imagenperfil" src="<?php echo $picture_to_show; ?>" alt="Foto de perfil">
                </div>
            </div>
        </div>
    </div>

    <div class="bodydelperfil">

        <div class="divinformacionperfil">
            <div class="divnombreperfil"><?php echo $nombre_p; ?>
                <div class="btn-group">
                    <button type="button" class="iconoeditar2perfil" data-bs-toggle="dropdown" data-bs-display="static" aria-expanded="false">
                        <i class="fa-solid fa-1x fa-pen-to-square"></i>
                    </button>
                    <form action="Perfil.php" method="POST">
                    <ul class="dropdown-menu">
                        <div class="mb-3">
                            <div class="row g-3 align-items-center">
                                <div class="col-auto">
                                  <label for="inputPassword6" class="col-form-label">Editar Nombre</label>
                                </div>
                                <div class="col-auto">
                                  <input type="text" name="edit_nom_usr" id="inputPassword6" class="form-control form-control2" aria-describedby="passwordHelpInline">
                                  <button class="BotonPublicarPerfil" type="submit" name="envio-edit-nom-usr">Publicar</button>
                                </div>
                              </div>
                        </div>
                    </ul>
                </div>
            </div>
            </form>

            <div class="divdescripperfil">
                <p class="p1perfil">Descripción </p>
                <div class="btn-group">
                    <button type="button" class="iconoeditar3perfil" data-bs-toggle="dropdown" data-bs-display="static" aria-expanded="false">
                        <i class="fa-solid fa-1x fa-pen-to-square"></i>
                    </button>
                    <form action="funciones_edit_usr.php" method="POST">
                    <ul class="dropdown-menu">
                        <div class="mb-3">
                            <div class="row g-3 align-items-center">
                                <div class="col-auto">
                                  <label for="inputPassword6" class="col-form-label">Editar Descripción</label>
                                </div>
                                <div class="col-auto">
                                  <input type="text" name="edit_desc" id="inputPassword6" class="form-control form-control2" aria-describedby="passwordHelpInline">
                                  <button class="BotonPublicarPerfil" type="submit" name="envio-edit-desc-usr">Publicar</button>
                                </div>
                              </div>
                        </div>
                    </ul>
                </div>
            </div>
        </div>
        
        <div class="divinformacion2perfil">
            <h2>Privado</h2>
            <div class="divmailperfil"><i class="fa-regular fa-2x fa-envelope"></i><?php echo $email; ?><button class="iconoeditar3perfil"><i class="fa-solid fa-pen-to-square"></i></button></div>
            <div class="divcontraperfil"><i class="fa-solid fa-2x fa-lock"></i><p class="p2perfil">************** </p><button class="iconoeditar3perfil"><i class="fa-solid fa-pen-to-square"></i></button></div>
        </div>
    </div>

</body>
</html>