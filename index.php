<?php
include "conexion.php";
$con = conectar_bd();
session_start();

// Verifica si el usuario está logueado y obtiene su información
if (isset($_SESSION['email'])) {
    $email = $_SESSION['email'];
    $sql = "SELECT * FROM persona WHERE email='$email'";
    $resultado = $con->query($sql);

    if ($data = $resultado->fetch_assoc()) {
        $nombre_p = $data['nombre_p'];
        $foto2 = "img_usr/default.png";
        $foto = $data['foto'] ?? $foto2;
        $rol = $data['rol'] ?? null;

        setcookie("nombre", $nombre_p, time() + 4200, "/");
        setcookie("foto", $foto, time() + (86400 * 30), "/");
        setcookie("email_emp", $email, time() + (86400 * 30), "/");
        setcookie("rol", $rol, time() + 4200, "/");
    } else {
        $nombre_p = 'Nombre no disponible';
        $foto = 'img_usr/default.png';
        $rol= 'inv';
    }
} else {
    $nombre_p = 'Nombre no disponible';
    $foto = 'default.png';
    $rol= 'inv';
}

// Función para truncar el texto
function truncateText($text, $maxWords) {
    $words = explode(' ', $text);
    if (count($words) > $maxWords) {
        return implode(' ', array_slice($words, 0, $maxWords)) . '...';
    }
    return $text;
}

// Consulta para obtener las publicaciones mejor valoradas, limitadas a las 10 mejores
$consulta_publicaciones = "
    SELECT p.id_prod, p.titulo, p.imagen_prod, p.descripcion_prod, AVG(c.valoracion) AS promedio
    FROM publicacion_prod p
    LEFT JOIN comentario c ON c.id_prod = p.id_prod
    WHERE p.tipo = 'publicacion'
    GROUP BY p.id_prod, p.titulo, p.imagen_prod, p.descripcion_prod
    HAVING AVG(c.valoracion) IS NOT NULL
    ORDER BY promedio DESC
    LIMIT 10
";

// Ejecuta la consulta
$result_publicaciones = $con->query($consulta_publicaciones);
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content= "width=device-width, user-scalable=yes">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" class="aslinkcarta" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <link class="aslinkcarta" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <link rel="stylesheet" class="aslinkcarta" href="style/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/driver.js/dist/driver.min.css">
    <script src="https://cdn.jsdelivr.net/npm/driver.js/dist/driver.min.js"></script>
    <link rel="icon" href="Imagenes/logoproyecto.png">
    <title>Inicio - Ozaris</title>
</head>
    <body>
<!-- +++++++++++++++++++++++++++HEADER+++++++++++++++++++++++++++ --> 
<div class="inicio1" >
    <header class="headerinicio" id="headerinicio">
        <a href="index.php"> <img class="logoinicio" src="style/Imagenes/logoproyecto.png" alt="Logo"></a>
        <button id="abrir" class="abrirmenuinicio"><i class="fa-solid fa-bars"></i></button>
        <nav class="navheaderinicio" id="nav">
            <img class="logosheaderinicio" src="style/Imagenes/Logos.png" alt="img">
            <button class="cerrarmenuinicio" id="cerrar"><i class="fa-solid fa-x"></i></button>
            <ul class="navlistainicio">
                <li class="lismenu"><a class="asmenuinicio" href="empresas.php">Empresas</a></li>
                <li class="lismenu"><a class="psmenuinicio">|</a></li>
                <li class="lismenu"><a class="asmenuinicio" href="#map">Ubicación</a></li>
                <li class="lismenu"><a class="psmenuinicio">|</a></li>
                <li class="lismenu"><a class="asmenuinicio" href="contacto.html">Contacto</a></li>
                <li class="lismenu"><a class="psmenuinicio">|</a></li>
                <li class="lismenu"><div class="dropdown">
  <button class="fotondeperfil" type="button" data-bs-toggle="dropdown" aria-expanded="false">
  <img src="<?php echo htmlspecialchars("img_usr/$foto") ?? htmlspecialchars("$foto2") ; ?>" alt="img" class="imgpequeñoperfil">
  </button>
  <ul class="dropdown-menu">
    <li><a class="dropdown-item" href="javascript:void(0);" onclick="redireccion()">Perfil</a></li>
    <?php if ($rol==='empresa'){
   echo " <li><a class='dropdown-item item2' href='mispublicaciones.php'>Mis publicaciones</a></li>";
}elseif($rol==='usuario' || $rol==='inv'){
   
}
?>
 <?php if ($rol==='admin'){
   echo " <li><a class='dropdown-item item2' href='admin.php'>Control</a></li>";
}elseif($rol==='usuario' || $rol==='inv'){
   
}
?>
    
  </ul>
</div>
</li>
        
        </header>

<!-- +++++++++++++++++++++++++++FINAL DEL HEADER+++++++++++++++++++++++++++ --> 

<!-- +++++++++++++++++++++++++++BODY+++++++++++++++++++++++++++ -->

<div class="parte1body" id="#welcomeSection" data-aos="fade-up" data-aos-anchor-placement="top-center" data-aos-duration="2000">
    <h1 class="h1parte1">Bienvenido/a, es un gusto tenerte aquí</h1>
    <h3 class="h3parte1">Te damos la bienvenida a una página con un sinfín de empresas esperándote. De la mano de KORF Company, esperamos ayudarte.</h3>

  <div class="divbotondeparte1body">
    <a class="botondeparte1body" class="aslinkcarta" href="Informacion.php">Saber más</a>
  </div>
</div>

</div>

<div class="parte3body">
    <div class="psparte3body">
     <h1 class="h1parte3" data-aos="fade-down" data-aos-easing="linear" data-aos-duration="1500">OZARIS</h1>
     <p class="p1parte3" data-aos="fade-down" data-aos-easing="linear" data-aos-duration="1500">La plataforma OZARIS es referencia en soluciones publicitarias innovadoras y eficaces. Nuestra misión es transformar la forma en que las empresas se conectan con sus clientes, ofreciendo estrategias de publicidad que combinan creatividad y tecnología.</p>
     <p class="p2parte3" data-aos="fade-down" data-aos-easing="linear" data-aos-duration="1500">Para nosotros es igualmente importante ofrecer asistencia a los visitantes nuevos que desean encontrar nuevas empresas o mejores precios mediante la comparación de un extenso catálogo.</p>
    </div>
</div>

<div class="parte2body">
    <div class="parte2body2">
    <div class="parte2bodydiv2" data-aos="fade-down" data-aos-duration="2000">
        <img class="imgparte2body" src="style/Imagenes/personas.png" alt="img">
        <p class="tituloparte2body">Servicio de marketing</p>
        <p class="parrafoparte2body">Nuestra empresa KORF se encargará de distribuir publicidad empresarial eficazmente para mejorar la visibilidad de tu empresa.</p>
    </div>
    <div class="parte2bodydiv1" data-aos="fade-down" data-aos-duration="2000">
        <img class="imgparte2body" src="style/Imagenes/flecha.png" alt="img">
        <p class="tituloparte2body">Estrategia de expansión</p>
        <p class="parrafoparte2body">Nuestro equipo especializado te proporcionará la orientación necesaria para desarrollar una estrategia de alcance publicitaria efectiva para tu empresa.</p>
    </div>
    <div class="parte2bodydiv3" data-aos="fade-down" data-aos-duration="2000">
        <img class="imgparte2body" src="style/Imagenes/manos.png" alt="img">
        <p class="tituloparte2body">Con el usuario</p>
        <p class="parrafoparte2body">Ayudamos a los visitantes a encontrar empresas o precios de manera más sencilla y rápida usando etiquetas y catálogos.</p>
    </div>
  </div>
</div>

<div id="topempresas"></div>
<div class="parte4body swiper" id="parte4" data-aos="zoom-in-up" data-aos-duration="1500">
    <h1 class="h1parte4body">10 empresas destacadas de la semana:</h1>

    <!-- Si hay más de 3 empresas, mostrar botones para deslizar -->
    <?php if ($result_publicaciones && $result_publicaciones->num_rows > 3) : ?>
        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>
    <?php endif; ?>

    <div class="swiper-container mySwiper">
        <div class="swiper-wrapper">
            <?php
            if ($result_publicaciones && $result_publicaciones->num_rows > 0) {
                while ($company = $result_publicaciones->fetch_assoc()) {
                    $id_prod = $company['id_prod'];
                    $descripcionTruncada = truncateText($company['descripcion_prod'], 3);
                    ?>
                    <div class="swiper-slide" data-aos="flip-left" data-aos-easing="ease-out-cubic" data-aos-duration="1500">
                        <form class="containerpublis" action="PublicacionD.php" method="POST">
                            <input type="hidden" name="id_prod" value="<?php echo htmlspecialchars($id_prod); ?>">
                            <img src="<?php echo htmlspecialchars($company['imagen_prod']); ?>" alt="Imagen de la empresa">
                            <div class="descripcioncarta">
                                <div class="titulocarta">
                                    <h4><?php echo htmlspecialchars($company['titulo']); ?></h4>
                                </div>
                                <div class="textocarta">
                                    <p><?php echo htmlspecialchars($descripcionTruncada); ?></p>
                                </div>
                                <div class="linkcarta">
                                    <input class="botonverpubliem" type="submit" value="Ver más" name="pub">
                                </div>
                            </div>
                        </form>
                    </div>
                    <?php
                }
            } else {
                echo "<div class='swiper-slide'>No hay publicaciones disponibles.</div>";
            }
            ?>
        </div>
    </div>
</div>
        </div>
    </div>
</div>
<div class="parte5body">

    <div id="planes" class="parte5body2">

    <div class="preguntaparte5body" data-aos="fade-down" data-aos-easing="linear" data-aos-duration="1500">
        <h1 class="h1parte5body">¿Tienes una empresa?</h1>
        <p class="pparte5body">Mira los planes que tenemos para ti</p>
    </div>

        <div class="cartasplanes" data-aos="flip-left" data-aos-easing="ease-out-cubic" data-aos-duration="1500">
            <img src="style/Imagenes/logousuario2.png" alt="img">
            <div class="descripcioncartaplanes">
                <div class="titulocartaplanes">
                    <h4>BASICO</h4>
                </div>
                <div class="textocartaplanes">
                    <p class="pcartasplanes"><i class="icono-tick fa-solid fa-check"></i>  Maximo de caracteres 300 </p>
                    <p class="pcartasplanes"><i class="icono-tick fa-solid fa-check"></i>  Cantidad maxima de publicaciones 3 </p>
                    <p class="pcartasplanes"><i class="icono-tick fa-solid fa-check"></i>  Sin acceso a la opcion de fijado de publicacion </p>
                    <p class="pcartasplanes"><i class="icono-tick fa-solid fa-check"></i>  Hora de atencion limitada </p>
                </div>
                <div class="linkcartaplanes">
                    <a class="botondeparte5bodyplanes aslinkcarta" href="empresas.php">Empezar</a>
                </div>
            </div>
        </div>

            <div class="cartasplanes" data-aos="flip-left" data-aos-easing="ease-out-cubic" data-aos-duration="1500">
                <img src="style/Imagenes/logopremium.png" alt="img">
                <div class="descripcioncartaplanes">
                    <div class="titulocartaplanes">
                        <h4>PREMIUM</h4>
                    </div>
                    <div class="textocartaplanes">
                        <p class="pcartasplanes"><i class="icono-tick fa-solid fa-check"></i>  Maximo de caracteres 600 </p>
                        <p class="pcartasplanes"><i class="icono-tick fa-solid fa-check"></i>  Cantidad maxima de publicaciones 6 </p>
                        <p class="pcartasplanes"><i class="icono-tick fa-solid fa-check"></i>  Mejor difamacion mediante un fijado </p>
                        <p class="pcartasplanes"><i class="icono-tick fa-solid fa-check"></i>  Mayor atencion de personal especializado </p>
                    </div>
                    <div class="linkcartaplanes">
                    <a class="boton2departe5bodyplanes aslinkcarta" href="pago.html">Comprar</a>
                    </div>
                </div>
        </div>
    </div>   
</div>

<div class="parte6body" id="map" data-aos="fade-down" data-aos-duration="3000">
    <div class="parte6body2">
    <div class="parte6bodytexto">
    <h1 class="h1parte6body">Nuestro local:</h1>
    <p class="pparte6body">Nos encontramos en 19 de Abril, entre Sarandí y Leandro Gomez. Nuestro personal especializado estará para atenderte en caso de que se te presente alguna duda o queja. </p>
    <p class="pparte6body">El horario de atención al cliente es de 13:15 a 19:05 de la tarde. </p>
    <img class="logosparte6body" src="style/Imagenes/Logos.png" alt="img">
</div>
    <iframe class="mapaubi" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d421.46950100782357!2d-58.08446172949984!3d-32.3184081663352!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x95afc95860544a23%3A0x2def9fadc88a46fb!2s19%20De%20Abril%2C%2060000%20Paysand%C3%BA%2C%20Departamento%20de%20Paysand%C3%BA!5e0!3m2!1ses!2suy!4v1722467891182!5m2!1ses!2suy" ></iframe>
</div>
</div>
    
<script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>

</div>

<!-- +++++++++++++++++++++++++++FINAL DEL BODY+++++++++++++++++++++++++++ --> 

<!-- +++++++++++++++++++++++++++FOOTER+++++++++++++++++++++++++++ --> 
    <footer>
        <div class="divfooter" id="footer_">
        <nav class="navsfooter">
                <p class="pfooter">General</p>
                <a class="afooter aslinkcarta" href="index.php">Inicio</a>
                <a class="afooter aslinkcarta" href="empresas.php">Empresas</a>
                <a class="afooter aslinkcarta" href="#">Servicios</a>
                <a class="afooter aslinkcarta" href="index.php#topempresas">Top empresas</a>
            </nav>
            <nav class="navsfooter">
                <p class="pfooter">Ayuda</p>
                <a class="afooter aslinkcarta" href="contacto.html">Contacto</a>
                <a class="afooter aslinkcarta" href="Informacion.php#FAQs">FAQs</a>
                <a class="afooter aslinkcarta" href="index.php#planes">Planes</a>
                <?php if ($rol==='empresa'){
   echo "<a class='afooter aslinkcarta' href='mispublicaciones.php'>Mis posts</a>";
}elseif($rol==='usuario' || $rol==='inv'){
   
}




?>
                
            </nav>
            <nav class="nav2footer">
                <p class="pfooter">Nosotros</p>
                <a class="afooter aslinkcarta" href="Informacion.php">Informacion</a>
                <a class="afooter aslinkcarta" href="index.php#map">Ubicacion</a>
                <a class="afooter aslinkcarta" href="Informacion.php#ozariskorf">Korf/Ozaris</a>
            <nav class="navredesfooter">
                <a class="aredesfooter aslinkcarta" href=""><i class="fa-brands fa-3x fa-instagram"></i></a>
                <a class="aredesfooter aslinkcarta" href=""><i class="fa-brands fa-3x fa-facebook"></i></a>
                <a class="aredesfooter aslinkcarta" href=""><i class="fa-brands fa-3x fa-twitter"></i></a>
            </nav>
            </nav>
        </div>
        <div class="footerbottom">
            <p class="pbottomfooter">| © 2024 Instituto Tecnologico Superior de Paysandu · Uruguay · +598 ** *** *** · ozaris08@gmail.com · Por KORF |</p>
        </div>
    </footer>
<!-- +++++++++++++++++++++++++++FINAL DEL FOOTER+++++++++++++++++++++++++++ --> 
<script>

    function redireccion() {
        <?php if (isset($_SESSION['email'])): ?>
            window.location.href = 'Perfil.php';
        <?php else: ?>
            window.location.href = 'iniciodesesion.html';
        <?php endif; ?>
    }
</script>

</script>

    <script src="https://cdn.jsdelivr.net/npm/driver.js@1.0.1/dist/driver.js.iife.js"></script>
    <script>
document.addEventListener("DOMContentLoaded", function() {
    const tourCompleted = localStorage.getItem('tourCompleted');

    // Solo iniciar el tour si no ha sido completado
    if (!tourCompleted) {
        const driver = window.driver.js.driver;

        const tour = driver({
            showProgress: true,
            steps: [
                { element: '#headerinicio', popover: { title: 'Header', description: 'Aqui puedes navegar a través de la aplicación web' } },
                { element: '#welcomeSection', popover: { title: 'Welcome Section', description: 'Puedes visualizar distintos apartados importantes' } },
                { element: '#parte4', popover: { title: 'Location', description: 'Próximamente en esta sección podrás visualizar las 10 mejores pubicaciones de la semana' } }
            ],
            onDestroyStarted: () => {
                // Marcar el tour como cancelado si el usuario decide salir
                localStorage.setItem('tourCompleted', 'true');
                if (!tour.hasNextStep() || confirm("Are you sure?")) {
                    tour.destroy();
                }
            },
        });

        // Iniciar el tour
        tour.drive().then(() => {
            // Marcar el tour como completado
            localStorage.setItem('tourCompleted', 'true');
        });
    }
});

</script>

<script src="app.js"></script>

<script>
  AOS.init();
</script>

</body>