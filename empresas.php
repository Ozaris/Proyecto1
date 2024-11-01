<?php
include_once "conexion.php";
session_start();
$con = conectar_bd();

$nom = $_COOKIE['nombre'] ?? null;
$foto = $_COOKIE['user_picture'] ?? $_COOKIE['foto'] ?? null;
$rol = $_COOKIE['rol'] ?? null;

if (isset($_POST["envio-pub"])) {
    $titulo = $_POST["titulo"];
    $categoria = $_POST["categoria"];
    $descripcion = $_POST["descripcion"];
    $lat = $_POST["lat"]; // Obtener latitud
    $lon = $_POST["lon"]; // Obtener longitud
    $email_emp = $_COOKIE['email_emp'] ?? null;

    // Verifica si se ha subido un archivo
    if (isset($_FILES['imagen_prod']) && $_FILES['imagen_prod']['error'] == 0) {
        // Obtiene la información del archivo
        $imagen = $_FILES['imagen_prod'];
        $rutaDestino = 'uploads/' . basename($imagen['name']);

        // Mueve el archivo a la carpeta deseada
        if (move_uploaded_file($imagen['tmp_name'], $rutaDestino)) {
            // Llamada a la función para crear la publicación
            crear_pub($con, $titulo, $categoria, $descripcion, $email_emp, $rutaDestino, $lat, $lon);
        } else {
            echo "Error al subir la imagen.";
        }
    } else {
        echo "No se ha seleccionado ninguna imagen o ha ocurrido un error.";
    }
}

if (isset($_SESSION['email'])) {
    $email = $_SESSION['email'];
    $sql = "SELECT * FROM persona WHERE email='$email'";
    $resultado = $con->query($sql);

    if ($data = $resultado->fetch_assoc()) {
        $nombre_p = $data['nombre_p'];
        $foto2 = "img_usr/default.png";
        $email = $data['email'];
        $foto = $data['foto'] ?? $foto2;
        $rol = $data['rol'];

        setcookie("nombre", $nombre_p, time() + 4200, "/");
        setcookie("foto", $foto, time() + (86400 * 30), "/");
        setcookie("email_emp", $email, time() + (86400 * 30), "/");
        setcookie("rol", $rol, time() + 4200, "/");
    } else {
        $nombre_p = 'Nombre no disponible';
        $email = 'Email no disponible';
        $foto = 'img_usr/default.png';
    }
} else {
    $nombre_p = 'Nombre no disponible';
    $email = 'Email no disponible';
    $foto = 'default.png';
}

function crear_pub($con, $titulo, $categoria, $descripcion, $email_emp, $img, $lat, $lon) {
    $consulta_login = "SELECT * FROM persona WHERE email = '$email_emp'";
    $resultado_login = mysqli_query($con, $consulta_login);

    if ($resultado_login && mysqli_num_rows($resultado_login) > 0) {
        $fila = mysqli_fetch_assoc($resultado_login);
        $id_per = $fila['Id_per'];

        // Inserta en la base de datos, incluyendo latitud y longitud
        $consulta_insertar_persona = "INSERT INTO publicacion_prod (titulo, categoria, descripcion, imagen_prod, Id_per, lat, lon) VALUES ('$titulo', '$categoria', '$descripcion', '$img', '$id_per', '$lat', '$lon')";
        
        if (mysqli_query($con, $consulta_insertar_persona)) {
            echo "Publicación creada exitosamente.";
        } else {
            echo "Error al insertar en publicacion_prod: " . mysqli_error($con);
        }
    } else {
        echo "No se encontró ningún usuario con ese email.";
    }
}

function truncateText($text, $maxWords) {
    $words = explode(' ', $text);
    if (count($words) > $maxWords) {
        return implode(' ', array_slice($words, 3, $maxWords)) . '...';
    }
    return $text;
}
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
    <link rel="icon" href="Imagenes/logoproyecto.png">
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css"/>
    <title>Empresas - Ozaris</title>
</head>
<body class="bodyempresas">
<!-- +++++++++++++++++++++++++++HEADER+++++++++++++++++++++++++++ --> 
    <div class="divpadreempresas">
        <div class="headermenuempresas">
            <a href="index.php"><img class="logo" src="style/Imagenes/logoproyecto.png" alt="Logo"></a>
            <button id="abrir" class="abrirmenuinicio"><i class="fa-solid fa-bars"></i></button>
            <nav class="navheaderinicio" id="nav">
                <img class="logosheaderinicio" src="style/Imagenes/Logos.png" alt="img">
                <button class="cerrarmenuinicio" id="cerrar"><i class="fa-solid fa-x"></i></button>
                <ul class="navlistainicio">
                    <li class="lismenu"><a class="asmenuinicio" href="index.php">Inicio</a></li>
                    <li class="lismenu"><a class="psmenuinicio">|</a></li>
                    <li class="lismenu"><a class="asmenuinicio" href="servicios.php">Servicios</a></li>
                    <li class="lismenu"><a class="psmenuinicio">|</a></li>
                    <li class="lismenu"><a class="asmenuinicio" href="index.php#map">Ubicación</a></li>
                    <li class="lismenu"><a class="psmenuinicio">|</a></li>
                    <li class="lismenu"><a class="asmenuinicio" href="contacto.html">Contacto</a></li>
                    <li class="lismenu"><a class="psmenuinicio">|</a></li>
                    <li class="lismenu">
                    <li class="lismenu"><div class="dropdown">
  <button class="fotondeperfil" type="button" data-bs-toggle="dropdown" aria-expanded="false">
  <img src="<?php echo htmlspecialchars("img_usr/$foto") ?? htmlspecialchars("$foto2") ; ?>" alt="img" class="imgpequeñoperfil">
  </button>
  <ul class="dropdown-menu">
    <li><a class="dropdown-item" href="javascript:void(0);" onclick="redireccion()">Perfil</a></li>
    <li><a class="dropdown-item" href="mispublicaciones.php">Mis publicaciones</a></li>
  </ul>
</div>
                </ul>
            </nav>
        </div>
<!-- +++++++++++++++++++++++++++FIN DE HEADER+++++++++++++++++++++++++++ --> 

<!-- +++++++++++++++++++++++++++BUSCADOR+++++++++++++++++++++++++++ --> 

        <div class="divprincipalbuscador" id="bus">
            <input type="text" name="buscador" id="buscador" placeholder="Buscar">
            <div id="resultado_busqueda"></div>
        </div>

<!-- +++++++++++++++++++++++++++FIN DE BUSCADOR+++++++++++++++++++++++++++ --> 

<!-- +++++++++++++++++++++++++++FILTROS+++++++++++++++++++++++++++ --> 

        <div class="containerempresas">
            <div class="divrecomendacionesempresas" id="filtro">
                <div class="div1recomendaciones"><h3>Filtros</h3></div>
                <div class="div2recomendaciones">
                <a href="empresas.php">
                <div class="cartaderecomendados">
                        <img class="logorecomendados" src="Imagenes/todos.png" alt="img">
                        <p>Todos</p>
                    </div>
                    </a>
                <div class="cartaderecomendados" onclick="filtrarPublicaciones('Electronica')">
                        <img class="logorecomendados" src="style/Imagenes/Electronica.png" alt="img">
                        <p>Electronica</p>
                    </div>
                    <div class="cartaderecomendados" onclick="filtrarPublicaciones('Gaming')">
                        <img class="logorecomendados" src="style/Imagenes/Entretenimiento.png" alt="img">
                        <p>Gaming</p>
                    </div>
                    <div class="cartaderecomendados" onclick="filtrarPublicaciones('Ropa')">
                        <img class="logorecomendados" src="style/Imagenes/Ropa.png" alt="img">
                        <p>Ropa</p>
                    </div>
                        <div class="cartaderecomendados" onclick="filtrarPublicaciones('Deporte')">
                            <img class="logorecomendados" src="style/Imagenes/Deporte.png" alt="img">
                            <p>Deporte</p>
                        </div>
                        <div class="cartaderecomendados" onclick="filtrarPublicaciones('Familia')">
                            <img class="logorecomendados" src="style/Imagenes/familia.png" alt="img">
                            <p>Familia</p>
                        </div>
                        <div class="cartaderecomendados" onclick="filtrarPublicaciones('Mascotas')">
                            <img class="logorecomendados" src="style/Imagenes/mascotas.png" alt="img">
                            <p>Mascotas</p>
                        </div>
                        <div class="cartaderecomendados" onclick="filtrarPublicaciones('Musica')">
                            <img class="logorecomendados" src="style/Imagenes/musica.png" alt="img">
                            <p>Musica</p>
                        </div>
                        <div class="cartaderecomendados" onclick="filtrarPublicaciones('Propiedades')">
                            <img class="logorecomendados" src="style/Imagenes/propiedad.png" alt="img">
                            <p>Propiedades</p>
                        </div>
                        <div class="cartaderecomendados" onclick="filtrarPublicaciones('Vehiculos')">
                            <img class="logorecomendados" src="style/Imagenes/Vehiculos.png" alt="img">
                            <p>Vehiculos</p>
                        </div>
                </div>
            </div>
<!-- +++++++++++++++++++++++++++FIN DE FILTROS+++++++++++++++++++++++++++ -->

<!-- +++++++++++++++++++++++++++BOTON PUBLICAR+++++++++++++++++++++++++++ -->
                <h3 class="h3publiem">Publicaciones</h3>
                <?php
                if ($rol === 'empresa') {

    echo '<div class="divbtn-primary"> <button type="button" class="btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                    Subir publicacion
                </button></div>';
} else {
    // Si es 'usuario' o no está definido, no mostramos el botón
}
                ?>
  <!-- Modal -->
   
  <div class="modal modal-xl fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="empresas.php" method="POST" enctype="multipart/form-data">
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
                            <button class="botoneliminarimagen"><i class="fa-solid fa-trash"></i></button>
                            <input class="form-control form-control1" type="file" id="formFile" name="imagen_prod" accept="image/jpeg,jpg,png" required>
                            <div id="imagePreview" class="image-preview"></div> <!-- Vista previa -->
                        </div>

                        <div class="divubicacionempresa">
                        <div class="mapaempresas" id="map"></div> <!-- Mapa debajo -->
                        </div>

                        <div class="divsubirinformacion">
                            <div class="divdatosinformacion">
                                <input type="text" class="form-control inputpublicacion1" id="floatingInput" placeholder="Título" name="titulo" required>
                                <select class="selectpublicar" id="categoriaSelect" name="categoria" required>
                                    <option value="Electrónica">Electrónica</option>
                                    <option value="Gaming">Gaming</option>
                                    <option value="Ropa">Ropa</option>
                                    <option value="Deporte">Deporte</option>
                                    <option value="Familia">Familia</option>
                                    <option value="Mascotas">Mascotas</option>
                                    <option value="Propiedades">Propiedades</option>
                                    <option value="Vehículos">Vehículos</option>
                                </select>
                                <textarea class="form-control inputpublicacion3" placeholder="Descripción" id="descripcion" name="descripcion" maxlength="300" style="height: 100px" oninput="validateInput()" required></textarea>
                                <div class="caracteresletrasalerta" id="charCount">300 caracteres restantes</div> <!-- Contador de caracteres -->
                            </div>
                            <p id="coordenadas"></p>
                            <input type="hidden" id="lat" name="lat" value="">
                            <input type="hidden" id="lon" name="lon" value="">

                            <!-- CDN de Leaflet -->
                            <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
                            <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>

                            <div class="divinformacionempresa">
                                <h6>Información de la empresa</h6>
                                <div class="divnombrempresapublicacion">
                                    <img class="divfotopublicacion" src="<?php echo htmlspecialchars("img_usr/$foto"); ?>" alt="img">
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


</div>
</div>

<!-- +++++++++++++++++++++++++++FIN DE BOTON PUBLICAR+++++++++++++++++++++++++++ --> 

<!-- +++++++++++++++++++++++++++PUBLICACIONES+++++++++++++++++++++++++++ --> 
            <div>
            <div class="divprincipalpublisem" id="publicacionesContainer">
                <?php
                // Obtener las publicaciones de la base de datos
                $consulta_publicaciones = "SELECT p.*, pe.nombre_p AS nombre_p FROM publicacion_prod p JOIN persona pe ON p.Id_per = pe.Id_per ORDER BY p.created_at DESC";
                $resultado_publicaciones = mysqli_query($con, $consulta_publicaciones);

                if ($resultado_publicaciones && mysqli_num_rows($resultado_publicaciones) > 0) {
                    while ($publicacion = mysqli_fetch_assoc($resultado_publicaciones)) {
                        $id_prod = $publicacion['id_prod'];
                        $tituloTruncado = truncateText($publicacion['titulo'], 3);
                        $descripcionTruncada = truncateText($publicacion['descripcion'], 3);
                        ?>
                        <form class="containerpublis" action="PublicacionD.php" method="POST">
                            <div class="cardempresas">
                                <img src="<?php echo htmlspecialchars($publicacion['imagen_prod']); ?>" class="imgcardpubliem" alt="Imagen de publicación">
                                <div class="cardempresasbody">
                                    <input type="hidden" name="id_prod" value="<?php echo htmlspecialchars($id_prod); ?>">
                                    <h5 class="card-title"><?php echo htmlspecialchars($tituloTruncado); ?></h5>
                                    <p class="card-text"><?php echo htmlspecialchars($descripcionTruncada); ?></p>
                                    <p class="card-text"><small class="text-muted">Categoría: <?php echo htmlspecialchars($publicacion['categoria']); ?></small></p>
                                    <p class="card-text"><small class="text-muted">Publicado por: <?php echo htmlspecialchars($publicacion['nombre_p']); ?></small></p>
                                </div>
                                <input class="botonverpubliem" type="submit" value="Ver más" name="pub">
                            </div>
                        </form>
                        <?php
                    }
                } else {
                    echo "<p>No hay publicaciones disponibles.</p>";
                }
                ?>
            </div>
        </div>
    </div>
            </div>
<!-- +++++++++++++++++++++++++++FIN DE PUBLICACIONES+++++++++++++++++++++++++++ --> 

<!-- +++++++++++++++++++++++++++SCRIPTS+++++++++++++++++++++++++++ --> 


<script>
function validateInput() {
    const textarea = document.getElementById('descripcion');
    const words = textarea.value.split(/\s+/); // separa el texto en palabras
    const filteredWords = words.filter(word => word.length <= 20); // crea un filtro de 16 letras
    if (filteredWords.length !== words.length) {
        textarea.value = filteredWords.join(' '); // Une las palabras que puedes colocar
        alert('Las palabras no pueden tener más de 20 letras.');
    }
}
</script>


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
        
        $(".card-text .text-muted").each(function() {
            $(this).text("Categoría: " + categoriaSeleccionada);
        });
    });
});
document.getElementById('formFile').addEventListener('change', function(event) {
    const file = event.target.files[0];
    const reader = new FileReader();

    reader.onload = function(e) {
        const imagePreview = document.getElementById('imagePreview');
        imagePreview.innerHTML = `<img src="${e.target.result}" alt="Imagen Previa" style="max-width: 100%; height: auto;">`;
    };

    if (file) {
        reader.readAsDataURL(file);
    } else {
        // Limpiar la vista previa si no hay archivo
        document.getElementById('imagePreview').innerHTML = '';
    }
});

function filtrarPublicaciones(categoria) {
            $.ajax({
                url: 'filtrar_publicaciones.php',
                method: 'POST',
                data: { categoria: categoria },
                success: function(data) {
                    $('#publicacionesContainer').html(data);
                },
                error: function() {
                    alert("Error al filtrar las publicaciones.");
                }
            });
        }

function redireccion() {
        <?php if (isset($_SESSION['email'])): ?>
            window.location.href = 'Perfil.php';
        <?php else: ?>
            window.location.href = 'iniciodesesion.html';
        <?php endif; ?>
    }
</script>
<script type="text/javascript">
        function filtrarPublicaciones(categoria) {
            $.ajax({
                url: 'filtrar_publicaciones.php',
                method: 'POST',
                data: { categoria: categoria },
                success: function(data) {
                    $('#publicacionesContainer').html(data);
                },
                error: function() {
                    alert("Error al filtrar las publicaciones.");
                }
            });
        }
    </script>

<script>
    const textarea = document.getElementById('descripcion'); // Cambiar al ID del textarea
    const charCount = document.getElementById('charCount');
    const maxLength = textarea.getAttribute('maxlength');

    textarea.addEventListener('input', () => {
        const remaining = maxLength - textarea.value.length;
        charCount.textContent = `${remaining} caracteres restantes`;
    });
</script>
<script>
        document.getElementById('formFile').addEventListener('change', function(event) {
    const file = event.target.files[0];
    const reader = new FileReader();

    reader.onload = function(e) {
        const imagePreview = document.getElementById('imagePreview');
        imagePreview.innerHTML = `<img src="${e.target.result}" alt="Imagen Previa" style="max-width: 100%; height: 480px;">`;

        // Hide the label when an image is previewed
        document.querySelector('label[for="formFile"]').style.display = 'none';
    };

    if (file) {
        reader.readAsDataURL(file);
    } else {
        // Clear the preview and show the label if no file is selected
        document.getElementById('imagePreview').innerHTML = '';
        document.querySelector('label[for="formFile"]').style.display = 'block'; // Show the label again
    }
});


    </script>

<script>
    // Inicializa el mapa centrado en Paysandú
    const map = L.map('map').setView([-32.3219, -58.0792], 13);

    // Capa de CartoDB
    L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
        maxZoom: 19,
        attribution: '© OpenStreetMap, © CartoDB',
    }).addTo(map);

    let marcador;

    // Evento de clic en el mapa
    map.on('click', function(e) {
        const lat = e.latlng.lat;
        const lon = e.latlng.lng;

        document.getElementById('lat').value = lat; // Establece latitud
        document.getElementById('lon').value = lon; // Establece longitud

        if (marcador) {
            marcador.setLatLng(e.latlng);
        } else {
            marcador = L.marker(e.latlng).addTo(map);
        }
    });
</script>



<script src="app.js"></script>

<!-- +++++++++++++++++++++++++++FIN DE SCRIPTS+++++++++++++++++++++++++++ --> 

</body>
</html>