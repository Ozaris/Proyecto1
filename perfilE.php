<?php
session_start();
require("conexion.php");
$con = conectar_bd();

$nombreEmpresa = isset($_SESSION['nombre_empresa']) ? $_SESSION['nombre_empresa'] : '';

function truncateText($text, $wordLimit) {
    $words = explode(' ', $text);
    if (count($words) > $wordLimit) {
        return implode(' ', array_slice($words, 0, $wordLimit)) . '...';
    }
    return $text;
}
// SE TOMAN VALORES DE LA TABLA EMRPESA PARA USARLOS EN EL ARCHIVO
if ($nombreEmpresa) {
    $sql = "SELECT * FROM persona WHERE nombre_p = '$nombreEmpresa'";
    $result = $con->query($sql);

    if ($result && $result->num_rows > 0) {
        $empresa = $result->fetch_assoc();
        $nombre_p=$empresa['nombre_p'];
        $foto=$empresa['foto'];
        $desc=$empresa['descripcion'];
        // Agrega más campos según sea necesario
    } else {
        echo "No se encontró información para la empresa: " . htmlspecialchars($nombreEmpresa);
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
    <link rel="icon" href="Imagenes/logoproyecto.png">
    <title>Perfil Empresa</title>
</head>

<body class="bodyperfil">
    <div class="divcolorperfil">

        <div class="divcolorperfil2">
            <a href="index.php"><i class="fa-solid fa-2x fa-arrow-left-long iconoatrasperfil"></i></a>
            <div class="divfotoperfil">
                <div class="imagenperfilcontainer">
                    <img class="imagenperfil" src="<?php echo "img_usr/$foto"; ?>" alt="Foto de perfil">
                </div>
            </div>
        </div>
    </div>

    <div class="bodydelperfil">

        <div class="divinformacionperfil">
            <div class="divnombreperfil"><?php echo $nombre_p; ?>
                
            </div>
            </form>

            <div class="divdescripperfil">
                <p class="p1perfil"><?php echo $desc;?></p>
              
            </div>
        </div>
        
       

        </div>
    </div>
    <div class="divprincipalpublisem" id="publicacionesContainer">
                <?php
                // Obtener las publicaciones de la base de datos
                $consulta_publicaciones = "SELECT p.*, pe.nombre_p AS nombre_p FROM publicacion_prod p JOIN persona pe ON p.Id_per = pe.Id_per WHERE pe.nombre_p='$nombreEmpresa' ORDER BY p.created_at DESC";
                $resultado_publicaciones = mysqli_query($con, $consulta_publicaciones);

                if ($resultado_publicaciones && mysqli_num_rows($resultado_publicaciones) > 0) {
                    while ($publicacion = mysqli_fetch_assoc($resultado_publicaciones)) {
                        $id_prod = $publicacion['id_prod'];
                        $tituloTruncado = truncateText($publicacion['titulo'], 3);
                        $descripcionTruncada = truncateText($publicacion['descripcion_prod'], 3);
                        ?>
                        <form class="containerpublis" action="PublicacionD.php" method="POST">
                            <div class="cardempresas">
                                <img src="<?php echo htmlspecialchars($publicacion['imagen_prod']); ?>" class="imgcardpubliem" alt="Imagen de publicación">
                                <div class="cardempresasbody">
                                    <input type="hidden" name="id_prod" value="<?php echo htmlspecialchars($id_prod); ?>">
                                    <h5 class="card-title"><?php echo htmlspecialchars($tituloTruncado); ?></h5>
                                    <p class="card-text"><?php echo htmlspecialchars($descripcionTruncada); ?></p>
                                    <p class="card-text"><small class="text-muted">Categoría: <?php echo htmlspecialchars($publicacion['categoria']); ?></small></p>
                                    <p class="card-text"><small class="text-muted">Publicado por: <?php echo htmlspecialchars($publicacion['nombre_p']); ?></small></p>
                                </div>
                                <input class="botonverpubliem" type="submit" value="Ver más" name="pub">
                            </div>
                        </form>
                        <?php
                    }
                } else {
                    echo "<p>No hay publicaciones disponibles.</p>";
                }
                ?>
            </div>
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

</body>
</html>