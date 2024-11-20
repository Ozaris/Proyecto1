<?php
session_start();
require("conexion.php");
$con = conectar_bd();

$nombre_p = $_COOKIE['nombre'] ?? null;

$nombreEmpresa = isset($_SESSION['nombre_empresa']) ? $_SESSION['nombre_empresa'] : '';
//MUESTRA LOS 3 ... EN LA DESCRIPCIÓN Y EL NOMBRE
function truncateText($text, $wordLimit) {
    $words = explode(' ', $text);
    if (count($words) > $wordLimit) {
        return implode(' ', array_slice($words, 0, $wordLimit)) . '...';
    }
    return $text;
}
if ($nombreEmpresa) {
    $sql = "SELECT * FROM persona WHERE nombre_p = '$nombreEmpresa'";
    $result = $con->query($sql);

    if ($result && $result->num_rows > 0) {
        $empresa = $result->fetch_assoc();
        $nombre_p=$empresa['nombre_p'];
        $foto=$empresa['foto'];
        // Agrega más campos según sea necesario
    } else {
        echo "No se encontró información para la empresa: " . htmlspecialchars($nombreEmpresa);
    }

}
if (isset($_POST["elim-pub"])) {
    $id_prod = $_POST['id_prod'];
    elim_pub($con,$id_prod);
}

function elim_pub($con,$id_prod) {
    $sql = "DELETE FROM comentario WHERE id_prod = '$id_prod'";
    $result = $con->query($sql);

    if ($result) {
        $sql2 = "DELETE FROM publicacion_prod WHERE id_prod = '$id_prod'";
        $result2 = $con->query($sql2);

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
    <link rel="icon" href="style/Imagenes/logoproyecto.png">
    <title>Mis Publicaciones</title>
</head>

<body class="bodymispublis">
    <a href="index.php" class="botonatrasmp"> <i class="fa-solid fa-2x fa-arrow-left"></i> </a>
    <h2 class="titulomispublicaciones">Centro de control de publicaciones</h2>
    <div class="divprincipalpublisem" id="publicacionesContainer">
                <?php
                // Obtiene todas las publicaciones qu se crearon
                $consulta_publicaciones = "SELECT p.* FROM publicacion_prod p";
                $resultado_publicaciones = mysqli_query($con, $consulta_publicaciones);
                    //CREA UN WHILE PARA MOSTRAR TODAS LAS PUBLICACIÓN SEGÚN LA CANTIDAD DE LAS MISMAS
                if ($resultado_publicaciones && mysqli_num_rows($resultado_publicaciones) > 0) {
                    while ($publicacion = mysqli_fetch_assoc($resultado_publicaciones)) {
                        $id_prod = $publicacion['id_prod'];
                        $tituloTruncado = truncateText($publicacion['titulo'], 3);
                        $descripcionTruncada = truncateText($publicacion['descripcion_prod'], 3);
                        ?>
                        <form class="containerpublis" action="admin.php" method="POST">
                            <div class="cardempresas">
                                <img src="<?php echo htmlspecialchars($publicacion['imagen_prod']); ?>" class="imgcardpubliem" alt="Imagen de publicación">
                                <div class="cardempresasbody">
                                    <input type="hidden" name="id_prod" value="<?php echo htmlspecialchars($id_prod); ?>">
                                    <h5 class="card-title"><?php echo htmlspecialchars($tituloTruncado); ?></h5>
                                    <p class="card-text"><?php echo htmlspecialchars($descripcionTruncada); ?></p>
                                    <p class="card-text"><small class="text-muted">Categoría: <?php echo htmlspecialchars($publicacion['categoria']); ?></small></p>
                                    
                                </div>
                                <input class="botonverpubliem" type="submit" onclick = "this.form.action = 'PublicacionD.php'" value='Ver más' name='pub' >
                                
                                <input class='botoneliminarpublimispublis' type='submit' value='Eliminar' name='elim-pub'>

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
    <div class="divcolorfooterP">
        <div class="divcolorfooterPdentro">
            <img class="logosperfilP" src="style/Imagenes/Logosblanco.png" alt="img">
        </div>
    </div>
</body>
</html>