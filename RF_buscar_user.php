<?php
require("conexion.php");
$con = conectar_bd();

$input = isset($_POST['input']) ? $con->real_escape_string($_POST['input']) : '';

$sql = "SELECT * FROM empresa WHERE nombre_p LIKE '$input%'";
$result = $con->query($sql);
//MUESTRA LOS RESULTADOS DE LOS NOMBRES QUE SE BUSCAN
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "<div><a href='javascript:void(0);' onclick='loadPerfil(\"" . htmlspecialchars($row['nombre_p']) . "\")'>" . htmlspecialchars($row['nombre_p']) . "</a></div>";
    }
} else {
    echo "No se encontraron resultados.";
}
?>

<script type="text/javascript">
function loadPerfil(nombre) {
    // Almacenar el nombre en una variable de sesión mediante AJAX
    $.post('set_session.php', { nombre_empresa: nombre }, function() {
        window.location.href = 'perfilE.php';
    });
}
</script>

