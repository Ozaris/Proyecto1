<?php
include_once "conexion.php";

$con = conectar_bd();

$nom = $_COOKIE['nombre'] ?? null;
$foto = $_COOKIE['user_picture'] ?? $_COOKIE['foto'];
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
    
    <div id="content-wrapper">
        <div id="sidebar-container" class="bg-primary">
            <div class="logoempresas">
                
     
            <input  type="text" name="buscador" id="buscador" placeholder="Buscar">
<div id="resultado_busqueda"></div>
                   
            </div>
            <div class="menuempresas">
 
                <a href="#" class="d-block text-light p-3 border-0 asempresas"><i class="icon ion-md-apps lead mr-2"></i> <img class="logodeopciones" src="Imagenes/casa.png" alt="img"> Hogar</a>
                <a href="#" class="d-block text-light p-3 border-0 asempresas"><i class="icon ion-md-people lead mr-2"></i> <img class="logodeopciones" src="Imagenes/Deporte.png" alt="img"> Deporte</a>
                <a href="#" class="d-block text-light p-3 border-0 asempresas"><i class="icon ion-md-stats lead mr-2"></i>  <img class="logodeopciones" src="Imagenes/Electronica.png" alt="img"> Electronica</a>
                <a href="#" class="d-block text-light p-3 border-0 asempresas"><i class="icon ion-md-person lead mr-2"></i> <img class="logodeopciones" src="Imagenes/Entretenimiento.png" alt="img"> Gaming</a>
                <a href="#" class="d-block text-light p-3 border-0 asempresas"><i class="icon ion-md-settings lead mr-2"></i> <img class="logodeopciones" src="Imagenes/familia.png" alt="img"> Familia</a>
                
                <a href="#" class="d-block text-light p-3 border-0 asempresas"><i class="icon ion-md-apps lead mr-2"></i> <img class="logodeopciones" src="Imagenes/mascotas.png" alt="img"> Mascotas</a>
                <a href="#" class="d-block text-light p-3 border-0 asempresas"><i class="icon ion-md-people lead mr-2"></i> <img class="logodeopciones" src="Imagenes/musica.png" alt="img"> Musica</a>
                <a href="#" class="d-block text-light p-3 border-0 asempresas"><i class="icon ion-md-stats lead mr-2"></i> <img class="logodeopciones" src="Imagenes/propiedad.png" alt="img"> Propiedades</a>
                <a href="#" class="d-block text-light p-3 border-0 asempresas"><i class="icon ion-md-person lead mr-2"></i> <img class="logodeopciones" src="Imagenes/Ropa.png" alt="img"> Ropa</a>
                <a href="#" class="d-block text-light p-3 border-0 asempresas"><i class="icon ion-md-person lead mr-2"></i> <img class="logodeopciones" src="Imagenes/Vehiculos.png" alt="img"> Vehiculos</a>
                
            </div>
        </div>

        <div class="divpadreempresas">
            <div class="divparabotondesubir" id="headerempresas"></div>

            <div class="headermenuempresas">
                <a href="index.php"> <img class="logo" src="Imagenes/logoproyecto.png" alt="Logo"></a>
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

            <div class="containerempresas">

                <!-- +++++++++++++++++++++++++++RECOMENDACIONES+++++++++++++++++++++++++++ -->

                <div class="divrecomendacionesempresas">
                    <div class="div1recomendaciones"><h5>Recomendaciones</h5></div>
                    <div class="div2recomendaciones">

                        <div class="cartaderecomendados">
                            <img class="logorecomendados" src="Imagenes/Electronica.png" alt="img">
                            <p>Electronica</p>
                        </div>
                        <div class="cartaderecomendados">
                            <img class="logorecomendados" src="Imagenes/Entretenimiento.png" alt="img">
                            <p>Gaming</p>
                        </div>
                        <div class="cartaderecomendados">
                            <img class="logorecomendados" src="Imagenes/Ropa.png" alt="img">
                            <p>Ropa</p>
                        </div>
                        <div class="cartaderecomendados">
                            <img class="logorecomendados" src="Imagenes/Deporte.png" alt="img">
                            <p>Deporte</p>
                        </div>

                    </div>
                </div>

                <!-- +++++++++++++++++++++++++++RECOMENDACIONES+++++++++++++++++++++++++++ -->

                <!-- +++++++++++++++++++++++++++BOTON PUBLICAR+++++++++++++++++++++++++++ -->
                
                <!-- Button trigger modal -->
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                    Launch demo modal
                </button>
                <!-- Modal -->
                 
                 <div class="modal modal-xl fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <form action="crear_pub.php" method="POST">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="exampleModalLabel">Publicar</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                
                                <div class="divprincipalpublicacion">
                                    <div class="divsubirimagen">
                                        <label for="formFile" class="form-label"><i class="fa-solid fa-2x fa-plus iconomaspublicacion"></i></label>
                        <input class="form-control form-control1" type="file" id="formFile" name="imagen_prod">
                                    </div>
                                    <div class="divsubirinformacion">
                                        <div class="divdatosinformacion">
                                        <input type="text" class="form-control inputpublicacion1" id="floatingInput" placeholder="Titulo" name="titulo">
                                        <input type="text" class="form-control inputpublicacion2" id="floatingInput" placeholder="Categoria" name="categoria">
                                        <textarea class="form-control inputpublicacion3" placeholder="Descripcion" id="floatingTextarea2" name="descripcion" style="height: 100px"></textarea>
                                        </div>
                                        
                                        <div class="divinformacionempresa">
                                            <h6>Informacion de la empresa</h6>
                                            <div class="divnombrempresapublicacion">
                                                <img class="divfotopublicacion" src="<?php echo htmlspecialchars($foto); ?>" alt="img">
                                                <h5 class="nombrempresapublicacion"><?php echo htmlspecialchars($nom); ?></h5>
                                            </div>
                                        </div>

                                    <div class="divbotonpublicar"><button class="botonsubirpublicacion" value="envio-pub" name="envio-pub">Subir publicación</button></div>
                                </div>
                            </div>
                            </div>
                        </div>
                    </form>
                    </div>
                </div>
            
                <!-- +++++++++++++++++++++++++++BOTON PUBLICAR+++++++++++++++++++++++++++ -->

                <div class="row">
                    <div class="col-md-3 mb-4">
                        <div class="card" style="width: 100%;" id="cardempresas">
                            <img src="..." class="card-img-top" alt="...">
                            <div class="card-body">
                                <h5 class="card-title">Card title</h5>
                                <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-4">
                        <div class="card" style="width: 100%;">
                            <img src="..." class="card-img-top" alt="...">
                            <div class="card-body">
                                <h5 class="card-title">Card title</h5>
                                <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                                <a href="#" class="btn btn-primary">Go somewhere</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-4">
                        <div class="card" style="width: 100%;">
                            <img src="..." class="card-img-top" alt="...">
                            <div class="card-body">
                                <h5 class="card-title">Card title</h5>
                                <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                                <a href="#" class="btn btn-primary">Go somewhere</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-4">
                        <div class="card" style="width: 100%;">
                            <img src="..." class="card-img-top" alt="...">
                            <div class="card-body">
                                <h5 class="card-title">Card title</h5>
                                <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                                <a href="#" class="btn btn-primary">Go somewhere</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="containerempresas">
                <div class="row">
                    <div class="col-md-3 mb-4">
                        <div class="card" style="width: 100%;">
                            <img src="..." class="card-img-top" alt="...">
                            <div class="card-body">
                                <h5 class="card-title">Card title</h5>
                                <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                                <a href="#" class="btn btn-primary">Go somewhere</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-4">
                        <div class="card" style="width: 100%;">
                            <img src="..." class="card-img-top" alt="...">
                            <div class="card-body">
                                <h5 class="card-title">Card title</h5>
                                <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                                <a href="#" class="btn btn-primary">Go somewhere</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-4">
                        <div class="card" style="width: 100%;">
                            <img src="..." class="card-img-top" alt="...">
                            <div class="card-body">
                                <h5 class="card-title">Card title</h5>
                                <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                                <a href="#" class="btn btn-primary">Go somewhere</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-4">
                        <div class="card" style="width: 100%;">
                            <img src="..." class="card-img-top" alt="...">
                            <div class="card-body">
                                <h5 class="card-title">Card title</h5>
                                <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                                <a href="#" class="btn btn-primary">Go somewhere</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="containerempresas">
                <div class="row">
                    <div class="col-md-3 mb-4">
                        <div class="card" style="width: 100%;">
                            <img src="..." class="card-img-top" alt="...">
                            <div class="card-body">
                                <h5 class="card-title">Card title</h5>
                                <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                                <a href="#" class="btn btn-primary">Go somewhere</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-4">
                        <div class="card" style="width: 100%;">
                            <img src="..." class="card-img-top" alt="...">
                            <div class="card-body">
                                <h5 class="card-title">Card title</h5>
                                <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                                <a href="#" class="btn btn-primary">Go somewhere</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-4">
                        <div class="card" style="width: 100%;">
                            <img src="..." class="card-img-top" alt="...">
                            <div class="card-body">
                                <h5 class="card-title">Card title</h5>
                                <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                                <a href="#" class="btn btn-primary">Go somewhere</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-4">
                        <div class="card" style="width: 100%;">
                            <img src="..." class="card-img-top" alt="...">
                            <div class="card-body">
                                <h5 class="card-title">Card title</h5>
                                <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                                <a href="#" class="btn btn-primary">Go somewhere</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="containerempresas">
                <div class="row">
                    <div class="col-md-3 mb-4">
                        <div class="card" style="width: 100%;">
                            <img src="..." class="card-img-top" alt="...">
                            <div class="card-body">
                                <h5 class="card-title">Card title</h5>
                                <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                                <a href="#" class="btn btn-primary">Go somewhere</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-4">
                        <div class="card" style="width: 100%;">
                            <img src="..." class="card-img-top" alt="...">
                            <div class="card-body">
                                <h5 class="card-title">Card title</h5>
                                <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                                <a href="#" class="btn btn-primary">Go somewhere</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-4">
                        <div class="card" style="width: 100%;">
                            <img src="..." class="card-img-top" alt="...">
                            <div class="card-body">
                                <h5 class="card-title">Card title</h5>
                                <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                                <a href="#" class="btn btn-primary">Go somewhere</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-4">
                        <div class="card" style="width: 100%;">
                            <img src="..." class="card-img-top" alt="...">
                            <div class="card-body">
                                <h5 class="card-title">Card title</h5>
                                <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                                <a href="#" class="btn btn-primary">Go somewhere</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="containerempresas">
                <div class="row">
                    <div class="col-md-3 mb-4">
                        <div class="card" style="width: 100%;">
                            <img src="..." class="card-img-top" alt="...">
                            <div class="card-body">
                                <h5 class="card-title">Card title</h5>
                                <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                                <a href="#" class="btn btn-primary">Go somewhere</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-4">
                        <div class="card" style="width: 100%;">
                            <img src="..." class="card-img-top" alt="...">
                            <div class="card-body">
                                <h5 class="card-title">Card title</h5>
                                <p class="card-text">Some quick.</p>
                                <a href="#" class="btn btn-primary">Go somewhere</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-4">
                        <div class="card" style="width: 100%;">
                            <img src="..." class="card-img-top" alt="...">
                            <div class="card-body">
                                <h5 class="card-title">Card title</h5>
                                <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                                <a href="#" class="btn btn-primary">Go somewhere</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-4">
                        <div class="card" style="width: 100%;">
                            <img src="..." class="card-img-top" alt="...">
                            <div class="card-body">
                                <h5 class="card-title">Card title</h5>
                                <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content adas dadadadadadasdasd as.</p>
                                <a href="#" class="btn btn-primary">Go somewhere</a>
                            </div>
                        </div>
                    </div>
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
});
</script>

</body>
</html>