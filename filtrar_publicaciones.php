<?php

include_once "conexion.php";
$con = conectar_bd();

$categoria = $_POST['categoria'] ?? '';

function truncateText($text, $maxWords) {
    $words = explode(' ', $text);
    if (count($words) > $maxWords) {
        return implode(' ', array_slice($words, 0, $maxWords)) . '...';
    }
    return $text;
}

if ($categoria) {
    $consulta_publicaciones = "SELECT p.*, pe.nombre_p AS nombre_p 
                               FROM publicacion_prod p 
                               JOIN persona pe ON p.Id_per = pe.Id_per 
                               WHERE p.categoria = ? 
                               ORDER BY p.created_at DESC";

    $stmt = $con->prepare($consulta_publicaciones);
    $stmt->bind_param("s", $categoria);
    $stmt->execute();
    $resultado_publicaciones = $stmt->get_result();

    if ($resultado_publicaciones && $resultado_publicaciones->num_rows > 0) {
        while ($publicacion = $resultado_publicaciones->fetch_assoc()) {
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
        echo "<p>No hay publicaciones disponibles para esta categoría.</p>";
    }
} else {
    echo "<p>Error en la categoría seleccionada.</p>";
}
?>
