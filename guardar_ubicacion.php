<?php
$servername = "localhost";
$username = "root"; // Por defecto en XAMPP
$password = ""; // Sin contraseña por defecto
$dbname = "ozaris";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener datos de la solicitud
$data = json_decode(file_get_contents("php://input"), true);
$titulo = $data['titulo'];
$categoria = $data['categoria'];
$descripcion = $data['descripcion'];
$imagen_prod = $data['imagen_prod'];
$id_per = $data['id_per'];
$lat = $data['lat'];
$lon = $data['lon'];

// Preparar la consulta
$sql = "INSERT INTO publicacion_prod (titulo, categoria, descripcion, imagen_prod, id_per, lat, lon) 
        VALUES ('$titulo', '$categoria', '$descripcion', '$imagen_prod', '$id_per', '$lat', '$lon')";

if ($conn->query($sql) === TRUE) {
    echo json_encode(["id" => $conn->insert_id, "lat" => $lat, "lon" => $lon]);
} else {
    echo json_encode(["error" => $conn->error]);
}

$conn->close();
?>
