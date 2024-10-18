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
    $email_emp = $_COOKIE['email_emp'] ?? null;

    // Verifica si se ha subido un archivo
    if (isset($_FILES['imagen_prod']) && $_FILES['imagen_prod']['error'] == 0) {
        // Obtiene la información del archivo
        $imagen = $_FILES['imagen_prod'];
        $rutaDestino = 'uploads/' . basename($imagen['name']);

        // Mueve el archivo a la carpeta deseada
        if (move_uploaded_file($imagen['tmp_name'], $rutaDestino)) {
            // Llamada a la función para crear la publicación
            crear_pub($con, $titulo, $categoria, $descripcion, $email_emp, $rutaDestino);
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

function crear_pub($con, $titulo, $categoria, $descripcion, $email_emp, $img) {
    $consulta_login = "SELECT * FROM persona WHERE email = '$email_emp'";
    $resultado_login = mysqli_query($con, $consulta_login);

    if ($resultado_login && mysqli_num_rows($resultado_login) > 0) {
        $fila = mysqli_fetch_assoc($resultado_login);
        $id_per = $fila['Id_per'];

        // Inserta en la base de datos
        $consulta_insertar_persona = "INSERT INTO publicacion_prod (titulo, categoria, descripcion, imagen_prod, Id_per) VALUES ('$titulo', '$categoria', '$descripcion', '$img', '$id_per')";
        
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
        return implode(' ', array_slice($words, 0, $maxWords)) . '...';
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
    <title>Empresas - Ozaris</title>
</head>
<body class="bodyempresas">

    <div class="divpadreempresas">
        <div class="divparabotondesubir" id="headerempresas"></div>

        <div class="headermenuempresas">
            <a href="index.php"><img class="logo" src="style/Imagenes/logoproyecto.png" alt="Logo"></a>
            <button class="abrirmenuempresas" id="abrir"><i class="fa-solid fa-bars"></i></button>
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
  <button class="otondeperfil" type="button" data-bs-toggle="dropdown" aria-expanded="false">
  <img src="<?php echo htmlspecialchars("img_usr/$foto") ?? htmlspecialchars("$foto2") ; ?>" alt="img" class="imgpequeñoperfil">
  </button>
  <ul class="dropdown-menu">
    <li><a class="dropdown-item" href="javascript:void(0);" onclick="redireccion()">Perfil</a></li>
    <li><a class="dropdown-item" href="#">Mis publicaciones</a></li>
  </ul>
</div>
                </ul>
            </nav>
        </div>

        <div class="divprincipalbuscador" id="bus">
            <input type="text" name="buscador" id="buscador" placeholder="Buscar">
            <div id="resultado_busqueda"></div>
        </div>

        <div class="containerempresas">
            <div class="divrecomendacionesempresas" id="filtro">
                <div class="div1recomendaciones"><h3>Filtros</h3></div>
                <div class="div2recomendaciones">
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
        imagePreview.innerHTML = `<img src="${e.target.result}" alt="Imagen Previa" style="max-width: 100%; height: auto;">`;

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
</body>
</html>