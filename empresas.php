<?php
include_once "conexion.php";

$con = conectar_bd();

$nom = $_COOKIE['nombre'] ?? null;
$foto = $_COOKIE['user_picture'] ?? $_COOKIE['foto'] ?? null;
$rol = $_COOKIE['rol'] ?? null;
?>



<!DOCTYPE html>
<html lang="en" class="htmlempresas">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="style/style.css">
    <link rel="stylesheet" href="lib/bootstrap.min.css">
 
    <title>Empresas - Ozaris</title>
</head>
<body class="bodyempresas">

        <div class="divpadreempresas">
            <div class="divparabotondesubir" id="headerempresas"></div>

            <div class="headermenuempresas">
                <a href="index.php"> <img class="logo" src="style/Imagenes/logoproyecto.png" alt="Logo"></a>
                <button class="abrirmenuempresas" id="abrir"><i class="fa-solid fa-bars"></i></button>
                <nav class="navheader" id="nav">
                    <img class="logosheader" src="Imagenes/Logos.png" alt="img">
                    <button class="cerrarmenuempresas" id="cerrar"><i class="fa-solid fa-x"></i></button>
                    <ul class="navlista">
                        
                        <li class="lismenu"><a class="asmenu" href="index.php">Inicio</a></li>
                        <li class="lismenu"><a class="psmenu">|</a></li>
                        <li class="lismenu"><a class="asmenu" href="#">Servicios</a></li>
                        <li class="lismenu"><a class="psmenu">|</a></li>
                        <li class="lismenu"><a class="asmenu" href="index.php#map">Ubicación</a></li>
                        <li class="lismenu"><a class="psmenu">|</a></li>
                        <li class="lismenu"><a class="asmenu" href="#">Contacto</a></li>

                    </ul>
                </nav>
            </div>

            <div class="divprincipalbuscador">
                <input  type="text" name="buscador" id="buscador" placeholder="Buscar">
<div id="resultado_busqueda"></div>
            </div>

            <div class="containerempresas">

                <!-- +++++++++++++++++++++++++++RECOMENDACIONES+++++++++++++++++++++++++++ -->

                <div class="divrecomendacionesempresas">
                    <div class="div1recomendaciones"><h3>Filtros</h3></div>
                    <div class="div2recomendaciones">

                        <div class="cartaderecomendados">
                            <img class="logorecomendados" src="style/Imagenes/Electronica.png" alt="img">
                            <p>Electronica</p>
                        </div>
                        <div class="cartaderecomendados">
                            <img class="logorecomendados" src="style/Imagenes/Entretenimiento.png" alt="img">
                            <p>Gaming</p>
                        </div>
                        <div class="cartaderecomendados">
                            <img class="logorecomendados" src="style/Imagenes/Ropa.png" alt="img">
                            <p>Ropa</p>
                        </div>
                        <div class="cartaderecomendados">
                            <img class="logorecomendados" src="style/Imagenes/Deporte.png" alt="img">
                            <p>Deporte</p>
                        </div>
                        <div class="cartaderecomendados">
                            <img class="logorecomendados" src="style/Imagenes/familia.png" alt="img">
                            <p>Familia</p>
                        </div>
                        <div class="cartaderecomendados">
                            <img class="logorecomendados" src="style/Imagenes/mascotas.png" alt="img">
                            <p>Mascotas</p>
                        </div>
                        <div class="cartaderecomendados">
                            <img class="logorecomendados" src="style/Imagenes/musica.png" alt="img">
                            <p>Musica</p>
                        </div>
                        <div class="cartaderecomendados">
                            <img class="logorecomendados" src="style/Imagenes/propiedad.png" alt="img">
                            <p>Propiedades</p>
                        </div>
                        <div class="cartaderecomendados">
                            <img class="logorecomendados" src="style/Imagenes/Vehiculos.png" alt="img">
                            <p>Vehiculos</p>
                        </div>

                    </div>
                </div>

                <!-- +++++++++++++++++++++++++++RECOMENDACIONES+++++++++++++++++++++++++++ -->

                <!-- +++++++++++++++++++++++++++BOTON PUBLICAR+++++++++++++++++++++++++++ -->
                
                <!-- Button trigger modal -->
                 <?php
                if ($rol === 'empresa') {

    echo ' <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                    Subir publicacion
                </button>';
} else {
    // Si es 'usuario' o no está definido, no mostramos el botón
}
                ?>
               
                <!-- Modal -->
                 
                 <div class="modal modal-xl fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                    <form action="crear_pub.php" method="POST" enctype="multipart/form-data">
    <div class="modal-content">
        <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">Publicar</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="divprincipalpublicacion">
                <div class="divsubirimagen">
                    <label for="formFile" class="form-label">
                        <i class="fa-solid fa-2x fa-plus iconomaspublicacion"></i>
                    </label>
                    <input class="form-control form-control1" type="file" id="formFile" name="imagen_prod">
                </div>
                <div class="divsubirinformacion">
                    <div class="divdatosinformacion">
                        <input type="text" class="form-control inputpublicacion1" id="floatingInput" placeholder="Titulo" name="titulo">
                        <select id="categoriaSelect" name="categoria">
                         <option>Elije una opción</option>
                         <option value="Electrónica">Electrónica</option>
                         <option value="Gaming">Gaming</option>
                         <option value="Ropa">Ropa</option>
                         <option value="Deporte">Deporte</option>
                         <option value="Familia">Familia</option>
                         <option value="Mascotas">Mascotas</option>
                         <option value="Propiedades">Propiedades</option>
                         <option value="Vehiculos">Vehiculos</option>
                        </select>
                        <textarea class="form-control inputpublicacion3" placeholder="Descripcion" id="floatingTextarea2" name="descripcion" style="height: 100px"></textarea>
                    </div>
                    <div class="divinformacionempresa">
                        <h6>Informacion de la empresa</h6>
                        <div class="divnombrempresapublicacion">
                            <img class="divfotopublicacion" src="<?php echo htmlspecialchars($foto); ?>" alt="img">
                            <h5 class="nombrempresapublicacion"><?php echo htmlspecialchars($nom); ?></h5>
                        </div>
                    </div>
                    <div class="divbotonpublicar">
                        <button class="botonsubirpublicacion" value="envio-pub" name="envio-pub">Subir publicación</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>


                    </div>
                </div>
            
             
                <div class="publicaciones">
    <h3>Publicaciones</h3>
    <div class="row">
        <?php
        // Obtener las publicaciones de la base de datos
        $consulta_publicaciones = "SELECT p.*, pe.nombre_p AS nombre_p FROM publicacion_prod p JOIN persona pe ON p.Id_per = pe.Id_per ORDER BY p.created_at DESC";
        $resultado_publicaciones = mysqli_query($con, $consulta_publicaciones);

        if ($resultado_publicaciones && mysqli_num_rows($resultado_publicaciones) > 0) {
            while ($publicacion = mysqli_fetch_assoc($resultado_publicaciones)) {
                // Crear tarjeta para cada publicación
                ?>
                <div class="col-md-4">
                    <div class="card mb-4">
                        <img src="<?php echo htmlspecialchars($publicacion['imagen_prod']); ?>" class="card-img-top" alt="Imagen de publicación">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($publicacion['titulo']); ?></h5>
                            <p class="card-text"><?php echo htmlspecialchars($publicacion['descripcion']); ?></p>
                            <p class="card-text"><small class="text-muted">Categoría: <?php echo htmlspecialchars($publicacion['categoria']); ?></small></p>
                            <p class="card-text"><small class="text-muted">Publicado por: <?php echo htmlspecialchars($publicacion['nombre_p']); ?></small></p>
                        </div>
                    </div>
                </div>
                <?php
            }
        } else {
            echo "<p>No hay publicaciones disponibles.</p>";
        }
        ?>
    </div>
</div>
            <a href="#headerempresas" class="botondescroll"><i class="fa-solid fa-arrow-up"></i></a>
            <script src="app.js"></script>
        </div>
    </div>
   

       <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
     <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="lib/jquery.js"></script>
<script type="text/javascript">
$(document).ready(function() {
    let debounceTimer;

    $("#buscador").keyup(function() {
        clearTimeout(debounceTimer);
        var input = $(this).val();

        debounceTimer = setTimeout(function() {
            if (input != "") {
                $.ajax({
                    url: "RF_buscar_user.php",
                    method: "POST",
                    data: { input: input },
                    success: function(data) {
                        $("#resultado_busqueda").html(data).css("display", "block");
                    },
                    error: function() {
                        $("#resultado_busqueda").html("Error en la búsqueda").css("display", "block");
                    }
                });
            } else {
                $("#resultado_busqueda").css("display", "none");
            }
        }, 300); // Esperar 300ms
    });

    $("#categoriaSelect").change(function() {
        var categoriaSeleccionada = $(this).val();
        // Aquí puedes hacer lo que desees con la categoría seleccionada,
        // como agregarla a un card o actualizar el contenido en la página.

        // Ejemplo de cómo actualizar un card:
        $(".card-text .text-muted").each(function() {
            $(this).text("Categoría: " + categoriaSeleccionada);
        });
    });
});
</script>

</body>
</html>