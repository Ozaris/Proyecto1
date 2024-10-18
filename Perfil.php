<?php
/* INICIA LA SESIÓN Y TOMA LOS DATOS*/
include "conexion.php";
$con = conectar_bd();
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: Perfil.php");
    exit(); 
}

//Convierte los datos en variables para posteriormente usarlos

$email = $_SESSION['email'];
$sql = "SELECT * FROM persona WHERE email='$email'";
$resultado = $con->query($sql);

if ($data = $resultado->fetch_assoc()) {
    $nombre_p = $data['nombre_p'];
    $email = $data['email'];
    $foto = $data['foto'];
    $rol = $data['rol'];

 
} else {
    $nombre_p = 'Nombre no disponible';
    $email = 'Email no disponible';
    $foto = 'default.png'; // Imagen predeterminada
}



// Actualizar foto de perfil

if (isset($_POST["envio-edit-ft-usr"])) {
    try {
        $archivo = $_FILES["foto_usr"]; // Toma el archivo subido
        
        if ($archivo["error"] === UPLOAD_ERR_OK) {
            $nombre_temporal = $archivo["tmp_name"];
            $nombre_archivo = basename($archivo["name"]); // Usa basename para evitar problemas con rutas

            // Define la ruta donde se moverá el archivo
            $ruta_destino = "img_usr/$nombre_archivo";
            
            // Mueve el archivo a la carpeta específica en el servidor
            if (move_uploaded_file($nombre_temporal, $ruta_destino)) {
                
                // Redimensionar la imagen a 800x800 píxeles
                list($width, $height, $type) = getimagesize($ruta_destino);

                // Definie un tamaño en específico
                $max_width = 1000;
                $max_height = 1000;

                // Calcular el nuevo tamaño manteniendo la relación de aspecto
                $ratio = $width / $height;
                if ($width > $height) {
                    $new_width = $max_width;
                    $new_height = $max_width / $ratio;
                } else {
                    $new_height = $max_height;
                    $new_width = $max_height * $ratio;
                }

                // Crear una imagen en blanco para el redimensionamiento
                $image_p = imagecreatetruecolor($new_width, $new_height);

                // Crear la imagen a partir del archivo movido
                switch ($type) {
                    case IMAGETYPE_JPEG:
                        $image = imagecreatefromjpeg($ruta_destino);
                        break;
                    case IMAGETYPE_PNG:
                        $image = imagecreatefrompng($ruta_destino);
                        break;
                    case IMAGETYPE_GIF:
                        $image = imagecreatefromgif($ruta_destino);
                        break;
                    default:
                        throw new Exception("Tipo de imagen no soportado.");
                }

                // Redimensionar la imagen
                imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

                // Guarda la imagen redimensionada en la misma ubicación
                switch ($type) {
                    case IMAGETYPE_JPEG:
                        imagejpeg($image_p, $ruta_destino);
                        break;
                    case IMAGETYPE_PNG:
                        imagepng($image_p, $ruta_destino);
                        break;
                    case IMAGETYPE_GIF:
                        imagegif($image_p, $ruta_destino);
                        break;
                }

                // Limpiar
                imagedestroy($image);
                imagedestroy($image_p);

                // Actualiza el nombre de la foto en la base de datos
                $sql = "UPDATE persona SET foto = ? WHERE email = ?";
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

// Muestra la foto en la página del perfil, o una imagen por defecto si no hay foto
$picture_to_show = "img_usr/" . ($foto ?: 'default.png');

// Establecer la cookie para el path de la imagen
setcookie("user_picture", $picture_to_show, time() + (86400 * 30), "/"); // Cookie válida por 30 días
setcookie("default", $foto, time() + (86400 * 30), "/"); 
// Puedes acceder a la cookie más tarde con $_COOKIE['user_picture']


/* ACTUALIZA EL NOMBRE DE USUARIO EN LA TABLA PERSONA Y USUARIO*/

// Actualiza el nombre de usuario en la tabla persona y usuario
if (isset($_POST["envio-edit-nom-usr"])) {
    $edit_nom_usr = $_POST["edit_nom_usr"];
    $nombre_p = $data['nombre_p'];
    $existe_nom = consultar_existe_nom($con, $edit_nom_usr);
    $rol = $data['rol'];
    if (!$existe_nom) {
       

        // Actualiza en persona
        $consulta_actualizar_persona = "UPDATE persona SET nombre_p = '$edit_nom_usr' WHERE nombre_p = '$nombre_p'";
        
        if (mysqli_query($con, $consulta_actualizar_persona)) {
            echo "Actualización exitosa en persona.<br>";

            // Actualiza en usuario
            if ($rol === 'usuario') {
                $sql = "UPDATE usuario SET nombre_p = '$edit_nom_usr' WHERE nombre_p = '$nombre_p'";
                echo "Consulta de actualización en usuario: " . $sql . "<br>"; // Para depuración
                
                if (mysqli_query($con, $sql)) {
                    echo "Actualización exitosa en usuario.<br>";
                } else {
                    echo "Error en la consulta de usuario: " . mysqli_error($con) . "<br>";
                }
            } elseif ($rol === 'empresa') {
                $consulta_actualizar_empresa = "UPDATE empresa SET nombre_p = '$edit_nom_usr' WHERE nombre_p = '$nombre_p'";
                
                if (mysqli_query($con, $consulta_actualizar_empresa)) {
                    echo "Actualización exitosa en empresa.<br>";
                } else {
                    echo "Error al actualizar en empresa: " . mysqli_error($con) . "<br>";
                }
            }
        } else {
            echo "Error al actualizar en persona: " . mysqli_error($con) . "<br>";
        }
    } else {
        echo "El usuario ya existe.<br>";
    }
}

function consultar_existe_nom($con, $edit_nom_usr) {
    $edit_nom_usr = mysqli_real_escape_string($con, $edit_nom_usr);
    $consulta_existe_nom = "SELECT nombre_p FROM persona WHERE nombre_p = '$edit_nom_usr'";
    $resultado_existe_nom = mysqli_query($con, $consulta_existe_nom);

    if ($resultado_existe_nom) {
        return mysqli_num_rows($resultado_existe_nom) > 0;
    } else {
        echo "Error en la consulta de existencia de nombre: " . mysqli_error($con) . "<br>";
        return false; // Para evitar problemas si hay un error
    }
}




//ELIMINAR CUENTA

if (isset($_POST["envio-elim-usr"])) {

  
    $envio_elim_usr = $_POST["envio-elim-usr"];
    $nombre_p = $data['nombre_p'];
    $rol=$data['rol'];
    if ($envio_elim_usr == 'envio-elim-usr') {
        elim($con, $nombre_p,$rol);
    } else {
        echo "No se puede eliminar el usuario";
    }
}

function elim($con, $nombre_p, $rol) {
    // Escapar los campos para evitar inyección SQL
    $nombre_p = mysqli_real_escape_string($con, $nombre_p);
    
    // Primero, eliminar de la tabla empresa si el rol es 'empresa'
    if ($rol === 'empresa') {
        $consulta_eliminar_empresa = "DELETE FROM empresa WHERE nombre_p='$nombre_p'";
        if (mysqli_query($con, $consulta_eliminar_empresa)) {
            echo "Eliminación exitosa en empresa.<br>";
            
        } else {
            echo "Error al eliminar en empresa: " . mysqli_error($con) . "<br>";
            return; // Salimos de la función si hay un error
        }
    }
    
      // Si el rol es 'usuario', también eliminar de la tabla usuario
      if ($rol === 'usuario') {
        $sql = "DELETE FROM usuario WHERE nombre_p='$nombre_p'";
        if (mysqli_query($con, $sql)) {
            echo "Eliminación exitosa en usuario.<br>";
            
        } else {
            echo "Error en la consulta de usuario: " . mysqli_error($con) . "<br>";
        }
    }
    // Luego, eliminar de la tabla persona
    $consulta_eliminar_persona = "DELETE FROM persona WHERE nombre_p='$nombre_p'";
    if (mysqli_query($con, $consulta_eliminar_persona)) {
        echo "Eliminación exitosa en persona.<br>";
        header("Location: logout.php");
    } else {
        echo "Error al eliminar en persona: " . mysqli_error($con) . "<br>";
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
    <link rel="stylesheet" href="style/style.css">
    <title>Tu Perfil</title>
</head>
<body class="bodyperfil">
    <div class="divcolorperfil">

        <div class="divcolorperfil2">
            <a href="index.php"><i class="fa-solid fa-2x fa-arrow-left-long iconoatrasperfil"></i></a>
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
                    
                    <ul class="dropdown-menu">
                        <div class="mb-3">
                            <div class="row g-3 align-items-center">
                                <div class="col-auto">
                                <form action="#" method="POST">
                                  <label for="inputPassword6" class="col-form-label">Editar Descripción</label>
                                </div>
                                <div class="col-auto">
                                  <input type="text" name="edit_desc" id="inputPassword6" class="form-control form-control2" aria-describedby="passwordHelpInline">
                                  <button class="BotonPublicarPerfil" type="submit" name="envio-edit-desc-usr">Publicar</button>
                                  </form>
                                </div>
                              </div>
                        </div>
                    </ul>
                </div>
            </div>
        </div>
        
        <div class="divinformacion2perfil">
            <h2>Privado <i class="fa-solid fa-1x fa-lock"></i></h2>
            <div class="divmailperfil"><?php echo $email; ?></div>
            <div class="divcontraperfil"><p class="p2perfil">************** </p><button class="iconoeditar3perfil"><i class="fa-solid fa-pen-to-square"></i></button></div>
           <form action="Perfil.php" method="post">
            <button class="Boton2PublicarPerfil" type="submit" name="envio-elim-usr" value="envio-elim-usr">Eliminar cuenta</button>
</form>
        </div>
    </div>

    <div class="divcolorperfil22">

        <div class="divcolorperfil23">
            <img class="logosperfil" src="style/Imagenes/Logosblanco.png" alt="img">
            <a class="h3perfil" href="contacto.html">Soporte <i class="fa-solid fa-headset"></i></a>
            <p class="pfooterperfil">Recuerda que todos tus datos están protegidos.</p>
        </div>

    </div>

</body>
</html>