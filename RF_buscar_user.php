<?php
require("conexion.php");
$con= conectar_bd();

$input = isset($_POST['input']) ? $con->real_escape_string($_POST['input']) : ''; //La variable input toma el valor enviado por el POST y lo pasa a string


$sql = "SELECT * FROM usuario WHERE nombre_p LIKE '$input%' "; // Consulta a la base de datos
$result = $con->query($sql);


if ($result->num_rows > 0) {  // Comprobar si hay resultados dentro de la base de datos y lo muestra
    while($row = $result->fetch_assoc()) {
        echo "<div>" . $row['nombre_p'] . "</div>";  //Recorre las columnas y muestra el valor deseado
    }
} else {
    echo "No se encontraron resultados.";
}
