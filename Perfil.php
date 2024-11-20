<?php
/* INICIA LA SESIÓN Y TOMA LOS DATOS */
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
    $descripcion = $data['descripcion'];
} else {
    $nombre_p = 'Nombre no disponible';
    $email = 'Email no disponible';
    $foto = 'default.png'; // Imagen predeterminada
}

// Actualizar foto de perfil
if (isset($_POST["envio-edit-ft-usr"])) {
    try {
        $archivo = $_FILES["foto_usr"];

        if ($archivo["error"] === UPLOAD_ERR_OK) {
            $nombre_temporal = $archivo["tmp_name"];
            $nombre_archivo = basename($archivo["name"]); // Usa basename para evitar problemas con rutas

            // Define la ruta
            $ruta_destino = "img_usr/$nombre_archivo";

            // Mueve el archivo a la carpeta específica
            if (move_uploaded_file($nombre_temporal, $ruta_destino)) {

                // Redimensiona la imagen a 800x800 píxeles
                list($width, $height, $type) = getimagesize($ruta_destino);

                // Define un tamaño específico
                $max_width = 1000;
                $max_height = 1000;

                // Calcula el nuevo tamaño
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

                // Crea la imagen a partir del archivo movido
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

                // Redimensiona la imagen
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
                echo "<script>alert('Foto de perfil actualizada exitosamente.');</script>";
                header("Location: Perfil.php");
                exit();
            } else {
                echo "<script>alert('Error al mover el archivo al servidor.');</script>";
            }
        } else {
            echo "<script>alert('Error al subir el archivo.');</script>";
        }
    } catch (Exception $e) {
        echo "<script>alert('Error: " . $e->getMessage() . "');</script>";
    }
}

// Muestra la foto en la página del perfil, o una imagen por defecto si no hay foto
$picture_to_show = "img_usr/" . ($foto ?: 'default.png');
setcookie("user_picture", $picture_to_show, time() + (86400 * 30), "/");
setcookie("default", $foto, time() + (86400 * 30), "/");

/* ACTUALIZA EL NOMBRE DE USUARIO EN LA TABLA PERSONA Y USUARIO */

if (isset($_POST["envio-edit-nom-usr"])) {
    $edit_nom_usr = $_POST["edit_nom_usr"];
    $nombre_p = $data['nombre_p'];
    $existe_nom = consultar_existe_nom($con, $edit_nom_usr);
    $rol = $data['rol'];

    if (!$existe_nom) {
        // Actualiza en persona
        $consulta_actualizar_persona = "UPDATE persona SET nombre_p = '$edit_nom_usr' WHERE nombre_p = '$nombre_p'";

        if (mysqli_query($con, $consulta_actualizar_persona)) {
            // Actualiza en usuario
            if ($rol === 'usuario') {
                $sql = "UPDATE usuario SET nombre_p = '$edit_nom_usr' WHERE nombre_p = '$nombre_p'";

                if (mysqli_query($con, $sql)) {
                    echo "<script>alert('Nombre de usuario actualizado exitosamente en usuario.');</script>";
                    header("Location: Perfil.php");
                } else {
                    echo "<script>alert('Error al actualizar nombre en la tabla usuario: " . mysqli_error($con) . "');</script>";
                }
            } elseif ($rol === 'empresa') {
                $consulta_actualizar_empresa = "UPDATE empresa SET nombre_p = '$edit_nom_usr' WHERE nombre_p = '$nombre_p'";

                if (mysqli_query($con, $consulta_actualizar_empresa)) {
                    echo "<script>alert('Nombre de usuario actualizado exitosamente en empresa.');</script>";
                                        header("Location: Perfil.php");
                } else {
                    echo "<script>alert('Error al actualizar nombre en la tabla empresa.');</script>";
                }
            }
        } else {
            echo "<script>alert('Error al actualizar nombre de usuario en persona: " . mysqli_error($con) . "');</script>";
        }
    } else {
        echo "<script>alert('El nombre de usuario ya existe.');</script>";
    }
}

if (isset($_POST["envio-edit-desc-usr"]) && isset($_POST["edit_desc_usr"])) {
    $edit_desc_usr = $_POST["edit_desc_usr"];
    $nombre_p = $data['nombre_p'];

    $edit_desc_usr = mysqli_real_escape_string($con, $edit_desc_usr);

    // Actualiza en la tabla persona
    $consulta_actualizar_descripcion = "UPDATE persona SET descripcion = '$edit_desc_usr' WHERE nombre_p = '$nombre_p'";

    if (mysqli_query($con, $consulta_actualizar_descripcion)) {
        echo "<script>alert('Descripción actualizada exitosamente.');</script>";
        header("Location: Perfil.php");
        exit();
    } else {
        echo "<script>alert('Error al actualizar la descripción: " . mysqli_error($con) . "');</script>";
    }
}

//VERIFICA SI YA EXISTE EL NOMBRE DE USUARIO
function consultar_existe_nom($con, $edit_nom_usr) {
    $edit_nom_usr = mysqli_real_escape_string($con, $edit_nom_usr);
    $consulta_existe_nom = "SELECT nombre_p FROM persona WHERE nombre_p = '$edit_nom_usr'";
    $resultado_existe_nom = mysqli_query($con, $consulta_existe_nom);

    if ($resultado_existe_nom) {
        return mysqli_num_rows($resultado_existe_nom) > 0;
    } else {
        echo "<script>alert('Error al buscar nombres que coincidan: " . mysqli_error($con) . "');</script>";
        return false; // Para evitar problemas si hay un error
    }
}

//ELIMINAR CUENTA
if (isset($_POST["envio-elim-usr"])) {
    $envio_elim_usr = $_POST["envio-elim-usr"];
    $nombre_p = $data['nombre_p'];
    $rol = $data['rol'];
    if ($envio_elim_usr == 'envio-elim-usr') {
        elim($con, $nombre_p, $rol);
    } else {
        echo "<script>alert('No se puede eliminar usuario.');</script>";
    }
}

function elim($con, $nombre_p, $rol) {
    $nombre_p = mysqli_real_escape_string($con, $nombre_p);

    // Si el rol es empresa, se toma la id_per y con esta se elimina los comentarios
    if ($rol === 'empresa') {
        $id_per_result = mysqli_query($con, "SELECT Id_per FROM empresa WHERE nombre_p='$nombre_p'");
        $id_per_row = mysqli_fetch_assoc($id_per_result);
        $id_per = $id_per_row['Id_per'];

        // Primero elimina los comentarios
        $consulta_eliminar_com = "DELETE FROM comentario WHERE id_prod IN (SELECT Id_prod FROM publicacion_prod WHERE id_per='$id_per')";
        mysqli_query($con, $consulta_eliminar_com);

        // Luego elimina de la tabla publicacion_prod
        $consulta_eliminar_publ = "DELETE FROM publicacion_prod WHERE id_per = '$id_per'";
        mysqli_query($con, $consulta_eliminar_publ);

        // Luego elimina de la tabla empresa
        $consulta_eliminar_empresa = "DELETE FROM empresa WHERE nombre_p='$nombre_p'";
        mysqli_query($con, $consulta_eliminar_empresa);
    }

    // Si el rol es 'usuario', es el mismo procedimiento pero sin borrar la tabla publicación
    if ($rol === 'usuario') {
        $consulta_eliminar_com = "DELETE FROM comentario WHERE id_per2 = (SELECT Id_per FROM usuario WHERE nombre_p='$nombre_p')";
        mysqli_query($con, $consulta_eliminar_com);

        // Luego eliminar de la tabla usuario
        $consulta_eliminar_usuario = "DELETE FROM usuario WHERE nombre_p='$nombre_p'";
        mysqli_query($con, $consulta_eliminar_usuario);
    }

    // Finalmente, eliminar de la tabla persona
    $consulta_eliminar_persona = "DELETE FROM persona WHERE nombre_p='$nombre_p'";
    if (mysqli_query($con, $consulta_eliminar_persona)) {
        header("Location: logout.php");
    } else {
        echo "<script>alert('No se pudo eliminar usuario.');</script>";
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
    <link rel="icon" href="style/Imagenes/logoproyecto.png">
    <link rel="stylesheet" href="style/style.css">
    <title>Tu Perfil</title>
</head>
<body class="bodyperfil">
    <div class="divcolorperfil">
        <div class="divcolorperfil2">
            <a href="index.php"><i class="fa-solid fa-2x fa-arrow-left-long iconoatrasperfil"></i></a>
            <a class="cerrarsesionperfil" href="logout.php">Cerrar sesión</a>
            <div class="dropdown divdropdowncoloresperfil">
                <button class="btn dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fa-solid fa-1x fa-pen-to-square iconoeditar1perfil"></i>
                </button>
                <ul class="dropdown-colores dropdown-menu">
                    <button class="botoncoloropcion1" onclick="cambiarColor('#ae0808')"><i class="fa-solid fa-droplet"></i></button>
                    <button class="botoncoloropcion2" onclick="cambiarColor('#66a6e6')"><i class="fa-solid fa-droplet"></i></button>
                    <button class="botoncoloropcion3" onclick="cambiarColor('#333')"><i class="fa-solid fa-droplet"></i></button>
                    <button class="botoncoloropcion4" onclick="cambiarColor('#ffb654')"><i class="fa-solid fa-droplet"></i></button>
                    <button class="botoncoloropcion5" onclick="cambiarColor('#8a8a8a')"><i class="fa-solid fa-droplet"></i></button>
                    <button class="botoncoloropcion6" onclick="cambiarColor('#6d1d5f')"><i class="fa-solid fa-droplet"></i></button>
                </ul>
            </div>
            <div class="btn-group">
                <button type="button" class="iconoeditarfotoperfil" data-bs-toggle="dropdown" data-bs-display="static" aria-expanded="false">
                    <i class="fa-solid fa-1x fa-pen-to-square"></i>
                </button>
                <form action="Perfil.php" method="POST" enctype="multipart/form-data">
                    <ul class="dropdown-menu">
                        <div class="mb-3 dropdowneditarfotoperfil">
                            <label for="formFile" class="form-label"><i class="fa-solid fa-2x fa-plus iconomas"></i></label>
                            <input class="form-control form-control1" type="file" id="formFile" name="foto_usr">
                            <button class="BotonPublicarPerfil" type="submit" name="envio-edit-ft-usr">Publicar</button>
                        </div>
                    </ul>
                </form>
            </div>
            <div class="divfotoperfil">
                <div class="imagenperfilcontainer">
                    <img class="imagenperfil" src="<?php echo $picture_to_show; ?>" alt="Foto de perfil">
                </div>
            </div>
        </div>
    </div>

    <div class="bodydelperfil">
        <div class="divinformacionperfil">
            <div class="divnombreperfil">
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
                                    <input type="text" name="edit_nom_usr" id="inputNombre" maxlength="30" class="form-control form-control2" aria-describedby="passwordHelpInline" oninput="validateName()">
                                        <button class="BotonPublicarPerfil" type="submit" name="envio-edit-nom-usr">Publicar</button>
                                    </div>
                                </div>
                            </div>
                        </ul>
                    </form>
                </div>
                <?php echo $nombre_p; ?>
            </div>
            <div class="divdescripperfil">
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
                                    <textarea class="form-control" maxlength="300" id="inputDescripcion" name="edit_desc_usr" rows="3" oninput="validateInput()"></textarea>
                                        <button class="BotonPublicarPerfil" type="submit" name="envio-edit-desc-usr">Publicar</button>
                                    </form>
                                    </div>
                                </div>
                            </div>
                    </ul>
                </div>
                <p class="p1perfil"><?php echo $descripcion;?></p>
            </div>
        </div>
        
        <div class="divinformacion2perfil">
            <h2>Privado <i class="fa-solid fa-1x fa-lock"></i></h2>
            <div class="divmailperfil"><?php echo $email; ?></div>
            <div class="divcontraperfil"><a href="contrasenia.html"><button class="iconoeditar3perfil"><i class="fa-solid fa-pen-to-square"></i></button></a><p class="p2perfil">************** </p></div>
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

<!-- +++++++++++++++++++++++++++Localstorage+++++++++++++++++++++++++++ --> 

    <script>
    function cambiarColor(color) {
        document.querySelector('.divcolorperfil2').style.backgroundColor = color;
        document.querySelector('.divcolorperfil23').style.backgroundColor = color;
        localStorage.setItem('colorPerfil', color);
    }
    

    window.onload = function() {
        const colorGuardado = localStorage.getItem('colorPerfil');
        if (colorGuardado) {
            cambiarColor(colorGuardado);
        }
    };
    </script>

    <!-- +++++++++++++++++++++++++++Localstorage+++++++++++++++++++++++++++ --> 

    <!-- +++++++++++++++++++++++++++Limite de letras en palabras+++++++++++++++++++++++++++ --> 
    <script>
function validateInput() {
    const textarea = document.getElementById('inputDescripcion');
    const words = textarea.value.split(/\s+/); // separa el texto en palabras
    const filteredWords = words.filter(word => word.length <= 20); // crea un filtro de 16 letras
    if (filteredWords.length !== words.length) {
        textarea.value = filteredWords.join(' '); // Une las palabras que puedes colocar
        alert('Las palabras no pueden tener más de 16 letras.');
    }
}
</script>

<script>
function validateName() {
    const input = document.getElementById('inputNombre');
    const words = input.value.split(/\s+/);
    const filteredWords = words.filter(word => word.length <= 16);
    if (filteredWords.length !== words.length) {
        input.value = filteredWords.join(' '); 
        alert('Las palabras no pueden tener más de 16 letras.');
    }
}
</script>

 <!-- +++++++++++++++++++++++++++Limite de letras en palabras+++++++++++++++++++++++++++ --> 

</body>
</html>
