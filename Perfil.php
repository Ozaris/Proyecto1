<?php

session_start();
if(!isset($_SESSION['email'])){
    header("Location: Perfil.php");
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
    <link rel="stylesheet" href="style.css">
    <title>Tu Perfil</title>
</head>
<body class="bodyperfil">
    <div class="divcolorperfil">

        <div class="divcolorperfil2">
            <a href="index.html"><i class="fa-solid fa-2x fa-arrow-left-long iconoatrasperfil"></i></a>
            <a class="cerrarsesionperfil" href="logout.php">Cerrar sesión</a>
            <button><i class="fa-solid fa-1x fa-pen-to-square iconoeditar1perfil"></i></button>
            <div class="divfotoperfil">
                <div class="imagenperfilcontainer">
                    <img class="imagenperfil" src="Imagenes/GatoFotoPruebaPerfil.png" alt="img">
                    <i class="fa-solid fa-2x fa-pen-to-square editaricono"></i>
                </div>
            </div>
        </div>

    </div>

    <div class="bodydelperfil">

        <div class="divinformacionperfil">
            <div class="divnombreperfil"><h1>Nombre</h1><button class="iconoeditar2perfil"><i class="fa-solid fa-pen-to-square"></i></button></div>
            <div class="divdescripperfil"><p class="p1perfil">Descripción </p><button class="iconoeditar3perfil"><i class="fa-solid fa-pen-to-square"></i></button></div>
        </div>

        <div class="divinformacion2perfil">
            <h2>Privado</h2>
            <div class="divmailperfil"><i class="fa-regular fa-2x fa-envelope"></i><h3 class="p2perfil">nombre@gmail.com </h3><button class="iconoeditar3perfil"><i class="fa-solid fa-pen-to-square"></i></button></div>
            <div class="divcontraperfil"><i class="fa-solid fa-2x fa-lock"></i><p class="p2perfil">************** </p><button class="iconoeditar3perfil"><i class="fa-solid fa-pen-to-square"></i></button></div>


        </div>
    </div>

</body>
</html>
