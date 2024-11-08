
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
       
        $rol = $data['rol'] ?? null;

       
     
    } else {
       
        $rol= 'inv';
    }
} else {

    $rol= 'inv';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link class="aslinkcarta" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="style/style.css">
    <link rel="icon" href="Imagenes/logoproyecto.png">
    <title>Información - Ozaris</title>
</head>
<body>

    <div class="parte1infromacion">
    
    <header class="headerinformacion">
        <a href="index.php"> <img class="logoinformacion" src="style/Imagenes/logoproyecto.png" alt="Logo"></a>
        <button id="abrir" class="abrirmenuinformacion"><i class="fa-solid fa-bars"></i></button>
        <nav class="navheaderinformacion" id="nav">
            <button class="cerrarmenuinformacion" id="cerrar"><i class="fa-solid fa-x"></i></button>
            <ul class="navlistainformacion">
                <li class="lismenuinformacion"><a class="asmenuinformacion" href="index.php">Inicio</a></li>
                <li class="lismenuinformacion"><a class="psmenuinformacion">|</a></li>
                <li class="lismenuinformacion"><a class="asmenuinformacion" href="empresas.php">Empresas</a></li>
                <li class="lismenuinformacion"><a class="psmenuinformacion">|</a></li>
                <li class="lismenuinformacion"><a class="asmenuinformacion" href="index.php#map">Ubicacion</a></li>
                <li class="lismenuinformacion"><a class="psmenuinformacion">|</a></li>
                <li class="lismenuinformacion"><a class="asmenuinformacion" href="#">Contacto</a></li>
            </ul>
        </nav>
    </header>
    <div class="divinformacionprincipal">
    <div class="logos2informaciondiv">
    <img class="logos2informacion" src="style/Imagenes/Logosblanco.png" alt="img">
    </div>
    
    <h1 class="h1parte1informacion">Conoce mas sobre KORF y OZARIS</h1>
    <p class="psparte1informacion">En esta sección, encontrará información detallada sobre nuestra empresa, el equipo detrás del proyecto OZARIS y los objetivos que motivaron su creación. Nuestro propósito es acercarnos a nuestros clientes y visitantes, ofreciendo transparencia y generando una comunidad sólida entre empresas y clientes. Nos comprometemos a ser el puente eficaz en esta interacción.</p>
    </div>
  </div>

    <div id="ozariskorf" class="TituloParte1"><img class="divinfologoproyecto" src="style/Imagenes/logoproyecto.png" alt="img"><h2 class="h2TituloParte1">OZARIS</h2></div>

    <div class="divinformacion">
      <p class="divtextoinfo">Nuestro proyecto creó una aplicación web centrada en ayudar a las PYMES (empresas chicas y medianas) a promocionar sus servicios de manera efectiva y accesible donde una empresa se puede registrar y publicar de qué trata su negocio, en donde esta ubicado su local, contactos para poder responder dudas de clientes sobre sus publicaciones, todo esto con el fin de lograr que estas empresas puedan subsistir a lo largo del tiempo. </p>
      <p class="divtextoinfonegrita">Ozaris es un proyecto que creamos con el fin de que tu empresa siga adelante, que tenga mas visibilidad a la hora de publicitar sus productos o el servicio que brinde.</p>
    </div>

    <div class="TituloParte1"><img class="divinfologoempresa" src="style/Imagenes/Logoempresa2.png" alt="img"><h2 class="h2TituloParte1">KORF</h2></div>
    <div class="divinformacion">
      <p class="divtextoinfo">KORF es una empresa emergente en el ámbito de la publicidad digital, dedicada a proporcionar soluciones de marketing efectivas y accesibles para pequeñas y medianas empresas (PYMES). Fundada con la misión de democratizar el acceso a herramientas publicitarias avanzadas, Korf se especializa en crear campañas publicitarias personalizadas que ayudan a las PYMES a destacar en un mercado competitivo.</p>
      <p class="divtextoinfonegrita">KORF está comprometida a transformar la manera en que las pequeñas y medianas empresas abordan la publicidad, brindándoles las herramientas y estrategias necesarias para competir y prosperar en el entorno digital.</p>
    </div>

    <div class="divdeFAQs" id="FAQs">

        <h2 class="h21FAQs">FAQs (Preguntas frecuentes):</h2>
        
        <div class="accordion accordion-flush divpreguntas" id="accordionFlushExample">
            <div class="accordion-item">
              <h2 class="accordion-header">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
                  ¿Puedo vender productos a traves de esta pagina?
                </button>
              </h2>
              <div id="flush-collapseOne" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
                <div class="accordion-body">No, nuestra pagina se creo con el proposito de publicitar no de vender a traves de esta.</div>
              </div>
            </div>
            <div class="accordion-item">
              <h2 class="accordion-header">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseTwo" aria-expanded="false" aria-controls="flush-collapseTwo">
                  ¿Que tipo de empresas se pueden publicitar?
                </button>
              </h2>
              <div id="flush-collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
                <div class="accordion-body">Contamos con un filtrado de empresas PYMES (chicas y medianas empresas) por lo tanto solo ese tipo de empresas podran publicitarse.</div>
              </div>
            </div>
            <div class="accordion-item">
              <h2 class="accordion-header">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseThree" aria-expanded="false" aria-controls="flush-collapseThree">
                  ¿Se podra publicar el catalogo de la empresa?
                </button>
              </h2>
              <div id="flush-collapseThree" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
                <div class="accordion-body">Si, podras publicarlo en la seccion de servicios.</div>
              </div>
            </div>
            <div class="accordion-item">
              <h2 class="accordion-header">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseFour" aria-expanded="false" aria-controls="flush-collapseFour">
                  ¿A donde me dirijo si tengo alguna consulta o queja?
                </button>
              </h2>
              <div id="flush-collapseFour" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
                  <div class="accordion-body">En la seccion de contacto podras contactarte con nosotros.</div>
              </div>
            </div>
           
            <div class="accordion-item">
              <h2 class="accordion-header">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseFive" aria-expanded="false" aria-controls="flush-collapseFive">
                  ¿Ofrecen descuentos o promociones especiales?
                </button>
              </h2>
              <div id="flush-collapseFive" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
                <div class="accordion-body">En la seccion de planes en la pagina principal podras comprar el pack premium que facilita la publicidad de tu empresa.</div>
              </div>
            </div>

            <div class="accordion-item">
              <h2 class="accordion-header">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseSix" aria-expanded="false" aria-controls="flush-collapseSix">
                  ¿Puedo cambiar la direccion de mi empresa en caso de que tengamos varias sucursales o haya algun cambio/equivocacion?
                </button>
              </h2>
              <div id="flush-collapseSix" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
                <div class="accordion-body"> Si, podras cambiar la informacion de tu empresa en la seccion de tu perfil.</div>
              </div>
            </div>

            <div class="accordion-item">
              <h2 class="accordion-header">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseSeven" aria-expanded="false" aria-controls="flush-collapseSeven">
                  ¿Cómo puedo obtener más información sobre un producto que aparece en una empresa?
                </button>
              </h2>
              <div id="flush-collapseSeven" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
                <div class="accordion-body">Si, podras preguntar tus dudas a las empresas ya que la pagina cuenta con un chat exclusivo para usuarios-empresas.</div>
              </div>
            </div>

            <div class="accordion-item">
              <h2 class="accordion-header">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseEight" aria-expanded="false" aria-controls="flush-collapseEight">
                  ¿Cómo actualizan la información sobre los productos publicitados?
                </button>
              </h2>
              <div id="flush-collapseEight" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
                <div class="accordion-body">Podras actualizar tu producto editando la misma o subiendo una nueva publicacion.</div>
              </div>
            </div>

            <div class="accordion-item">
              <h2 class="accordion-header">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseNine" aria-expanded="false" aria-controls="flush-collapseNine">
                  ¿Dónde encuentro opiniones o reseñas sobre los productos publicitados?
                </button>
              </h2>
              <div id="flush-collapseNine" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
                <div class="accordion-body">Cada publicacion cuenta con una seccion de comentarios de los usuarios.</div>
              </div>
            </div>

            <div class="accordion-item">
              <h2 class="accordion-header">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseTen" aria-expanded="false" aria-controls="flush-collapseTen">
                  ¿Donde puedo encontrar informacion sobre la pagina o terminos de uso?
                </button>
              </h2>
              <div id="flush-collapseTen" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
                <div class="accordion-body">En la seccion de “Saber mas” encontraras informacion sobre nosotros y nuestros terminos de uso.</div>
              </div>
            </div>

          </div>
          </div>
    </div>

    <script src="app.js"></script>

    <footer>
        <div class="divfooter">
            <nav class="navsfooter">
                <p class="pfooter">General</p>
                <a class="afooter aslinkcarta" href="index.php">Inicio</a>
                <a class="afooter aslinkcarta" href="empresas.php">Empresas</a>
                <a class="afooter aslinkcarta" href="index.php#Serviciosindex">Servicios</a>
                <a class="afooter aslinkcarta" href="index.php#topempresas">Top empresas</a>
            </nav>
            <nav class="navsfooter">
                <p class="pfooter">Ayuda</p>
                <a class="afooter aslinkcarta" href="contacto.html">Contacto</a>
                <a class="afooter aslinkcarta" href="Informacion.html#FAQs">FAQs</a>
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
                <a class="aredesfooter aslinkcarta" href=""><i class="fa-brands fa-2x fa-instagram"></i></a>
                <a class="aredesfooter aslinkcarta" href=""><i class="fa-brands fa-2x fa-facebook"></i></a>
                <a class="aredesfooter aslinkcarta" href=""><i class="fa-brands fa-2x fa-twitter"></i></a>
            </nav>
            </nav>
        </div>
        <div class="footerbottom">
            <p class="pbottomfooter">| © 2024 Instituto Tecnologico Superior de Paysandu · Uruguay · +598 99 *** *** · ozaris08@gmail.com · Por KORF |</p>
        </div>
    </footer>

</body>
</html>