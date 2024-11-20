<?php
include "conexion.php";
session_start();
$con = conectar_bd();

$email = $_COOKIE['email'] ?? null;
$nombre_p = $_COOKIE['nombre'] ?? null;
$foto = $_COOKIE['user_picture'] ?? null;
$rol = $_COOKIE['rol'] ?? null;
$id_prod = $_POST['id_prod'] ?? $_SESSION['pub'] ?? null;

if ($id_prod) {
    $_SESSION['pub'] = $id_prod; // Almacenar en sesión
    setcookie("pub", $id_prod, time() + (86400 * 30), "/");

    // Consulta para obtener información del producto
    $sql = "SELECT * FROM publicacion_prod WHERE id_prod='$id_prod'";
    $resultado = $con->query($sql);

    // Consulta para obtener el nombre de la empresa
    $sql2 = "SELECT e.nombre_p FROM empresa e JOIN publicacion_prod p ON e.Id_per=p.id_per WHERE id_prod='$id_prod'";
    $resultado2 = $con->query($sql2);

    // Obtener el promedio de la valoración
    $sql_avg = "SELECT AVG(valoracion) AS promedio FROM comentario WHERE id_prod='$id_prod'";
    $result_avg = $con->query($sql_avg);
    $promedio_valoracion = 0;

    if ($result_avg && $row = $result_avg->fetch_assoc()) {
        $promedio_valoracion = round($row['promedio'], 1);
    }

    // SE GUARDAN LOS DATOS EN VARIABLES PARA USARLOS DESPUÉS
    if ($data = $resultado->fetch_assoc()) {
        $titulo_emp = $data['titulo'];
        $cat_emp = $data['categoria'];
        $foto_pub = $data['imagen_prod'];
        $desc_emp = $data['descripcion_prod'];
        $latitud = $data['lat'] ?? null;  // Obtener latitud
        $longitud = $data['lon'] ?? null; // Obtener longitud
        $tipo_pub = $data['tipo']; // Agregar tipo de publicación
    }

    // SE GUARDAN LOS DATOS EN VARIABLES PARA USARLOS DESPUÉS
    if ($resultado2 && $data2 = $resultado2->fetch_assoc()) {
        $nom_pub = $data2['nombre_p'];
    } else {
        $nom_pub = 'Nombre no disponible';
    }

    $sql0 = "SELECT pe.* FROM publicacion_prod p JOIN persona pe ON p.Id_per = pe.Id_per WHERE p.id_prod='$id_prod'";
    $resultado0 = $con->query($sql0);

    if ($data_ft = $resultado0->fetch_assoc()) {
        $nom_empp = $data_ft['foto'];
    }

    // Verifica si la URL de origen está disponible
  
    
}
?>

<!DOCTYPE html>
<html class="htmlpubliD" lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="style/style.css">
    <link rel="icon" href="style/Imagenes/logoproyecto.png">
    <title>Publicacion</title>
</head>
<body class="bodypubliD">
<a class="botonatraspubliD" href="<?php echo getenv('HTTP_REFERER'); ?>"><i class="fa-solid iconoatraspubliD fa-arrow-left"></i></a>
<div class="divprincipalD">

    <div class="divsec1publiD">
        <img class="imagenprincipalcomentarios" src="<?php echo $foto_pub ?>" alt="img">
       </div>

    <div class="divsec2publiD">
        <div class="divtitulodescripcion"><h2 class="titulopubliD">Información</h2></div>
        <div class="divinfopubliD">
            <h3 class="letraspubliD2"><div class="divlogopublisD"><img class="imagenlogitopublicaciones" src="<?php echo 'img_usr/'.$nom_empp?>" alt="img"> <?php echo $nom_pub; ?></div></h3>
            <h3 class="letraspubliD2"><i class="fa-solid fa-layer-group"></i> <?php echo $cat_emp; ?></h3>
            <h3 class="letraspubliD2"> <i class="fa-solid fa-gem"></i> Valoración: <?php echo $promedio_valoracion; ?> <img class="estrella2" src="style/Imagenes/estrella.png" alt="img"></h3>
        </div>
        <div class="divtitulodescripcion2"><h2 class="titulopubliD2">Descripción</h2></div>
        <div class="divdescripcionpubliD">
            <p class="letraspubliDdesc"><?php echo $desc_emp; ?></p>
        </div>
        <div class="divmapapubliD" id="map"></div>
    </div>
</div>

<!-- Mapa -->

<!-- CDN de Leaflet -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>

<script>
    // Inicializa el mapa centrado en las coordenadas de la publicación
    const map = L.map('map').setView([<?php echo $latitud; ?>, <?php echo $longitud; ?>], 13);

    // Capa de OpenStreetMap
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '© OpenStreetMap'
    }).addTo(map);

    // Agrega un marcador en la ubicación
    L.marker([<?php echo $latitud; ?>, <?php echo $longitud; ?>]).addTo(map)
        .bindPopup('<b><?php echo htmlspecialchars($titulo_emp); ?></b>').openPopup();
</script>

<?php if ($rol == "usuario"): ?>
    <form class="fromestrellas" id="commentForm">
    <div class="estrellas">
                <input type="radio" name="puntuacion" id="estrella5" value="5"><label for="estrella5">★</label>
                <input type="radio" name="puntuacion" id="estrella4" value="4"><label for="estrella4">★</label>
                <input type="radio" name="puntuacion" id="estrella3" value="3"><label for="estrella3">★</label>
                <input type="radio" name="puntuacion" id="estrella2" value="2"><label for="estrella2">★</label>
                <input type="radio" name="puntuacion" id="estrella1" value="1" required><label for="estrella1">★</label>
            </div>
        <div class="divprincipalcomentarios">
            <img class="imagenperfilcomentarios" src="<?php echo $foto ?>" alt="img">
            <input type="text" class="inputcomentarios" placeholder="Comentario" maxlength="150" aria-label="Comentario" name="comentario" required>
            <button type="submit" class="botoncomentar">Publicar</button>
        </div>
    </form>
<?php else: ?>
    <p style="margin-top: 2rem; text-align:center;"></p>
<?php endif; ?>

<div class="divinfocomentarios">
    <h2>Comentarios</h2><i class="fa-solid fa-comment"></i>
</div>
<div id="commentsContainer">
    <?php
    $consulta_publicaciones = "SELECT p.*, pe.nombre_p, pe.foto FROM comentario p JOIN persona pe ON p.id_per2 = pe.Id_per WHERE p.id_prod='$id_prod' ORDER BY p.created_at DESC";
    $resultado_publicaciones = mysqli_query($con, $consulta_publicaciones);

    if ($resultado_publicaciones && mysqli_num_rows($resultado_publicaciones) > 0) {
        while ($publicacion = mysqli_fetch_assoc($resultado_publicaciones)) {
            ?>
            <div class="divprincipalcomentarios2">
                <div class="divimagenperfilcomentarios2">
                    <img class="imagenperfilcomentarios2" src="<?php echo "img_usr/" . $publicacion['foto']; ?>" alt="img">
                </div>
                <div class="divinformacioncomentarios">
                    <h2><?php echo htmlspecialchars($publicacion['nombre_p']); ?></h2>
                    <h4><?php echo htmlspecialchars($publicacion['comentario']); ?></h4>
                    <h3>Valoración: <?php echo htmlspecialchars($publicacion['valoracion']); ?><img class="estrella" src="style/Imagenes/estrella.png" alt="img"></h3>
                </div>
            </div>
            <?php
        }
    } else {
        echo "<p>No hay publicaciones disponibles.</p>";
    }
    ?>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $('#commentForm').on('submit', function(e) {
        e.preventDefault();

        $.ajax({
            url: 'crear_com.php',
            type: 'POST',
            data: $(this).serialize() + '&id_prod=<?php echo $id_prod; ?>',
            success: function(response) {
                const res = JSON.parse(response);
                if (res.success) {
                    const newComment = `
    <div class="divprincipalcomentarios2">
        <div class="divimagenperfilcomentarios2">
            <img class="imagenperfilcomentarios2" src="img_usr/${res.foto}" alt="img">
        </div>
        <div class="divinformacioncomentarios">
            <h2>${res.nombre}</h2>
            <h4>${res.comentario}</h4>
            <h3>Valoración: ${res.valoracion}</h3>
        </div>
    </div>`;
                    $('#commentsContainer').prepend(newComment);
                    $('#commentForm')[0].reset();
                } else {
                    alert(res.error);
                }
            },
            error: function() {
                alert("Error al enviar el comentario. Inténtalo de nuevo.");
            }
        });
    });
});
</script>

</body>
</html>