<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
</head>
<body>
    <div class="divfondoiniciodesesion1">
        <div class="divfondoiniciodesesion2">
            <img class="logosiniciodesesion" src="Imagenes/Logosblanco.png" alt="img">
            <p class="bienvenidodenuevoregistro">¡Bienvenido de nuevo!</p>
            <a class="botoniniciodesesion3" href="#">Empresa</a>
        </div>
        <div class="divfondoiniciodesesion3">
            <p class="crearcuentaregistro">Crear cuenta</p>
            <div class="divgridpadreregistro">
                <button class="botoniniciodesesion1"><img class="googlelogo" src="Imagenes/Google.png" alt=""></button>
                <button class="botoniniciodesesion2"><i class="fa-brands facebooklogo fa-facebook"></i></button>
            </div>
            <form action="RF_registro_usr.php" method="POST">
            <div class="input-container">
                            <input class="input1iniciodesesion" type="text" placeholder="Nombre" name="nombre_p" id="nombre_p"/>
                <span id="icon">
                    <i class="fa-regular fa-user"></i>
                </span>
            </div>
            <div class="input-container">
                <input class="input2iniciodesesion" type="text" placeholder="Email" name="email" id="email"/>
                <span id="icon">
                    <i class="fa-regular fa-envelope"></i>
                </span>
            </div>
            <div class="input-container">
                <input class="input3iniciodesesion" type="password" placeholder="Contraseña" name="pass" id="pass"/>
                <span id="icon">
                    <i class="fa-solid fa-lock"></i>
                </span>
            </div>
            <input class="botoniniciodesesion4"  type="submit" value="envio" name="Inicio de sesión">
        </form>
        </div>
    </div>
</body>
</html>