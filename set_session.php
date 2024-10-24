<?php
session_start();
require("conexion.php");
$con = conectar_bd();

if (isset($_POST['nombre_empresa'])) {
    $_SESSION['nombre_empresa'] = $con->real_escape_string($_POST['nombre_empresa']);
}
?>
