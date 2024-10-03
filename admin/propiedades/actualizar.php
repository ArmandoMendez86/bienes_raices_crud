<?php
require '../../includes/funciones.php';

$auth = estaAutenticado();
if(!$auth){
    header('Location: /bienes_raices');
}



$id = $_GET['id'];
$id = filter_var($id, FILTER_VALIDATE_INT);

if (!$id) {
    header('Location: /bienes_raices/admin');
}

require '../../includes/config/database.php';
$db = conectarDB();

//Obtener datos de la propiedad

$informacioPropiedad = "SELECT * FROM propiedades WHERE id = {$id}";
$resultadoInformacion = mysqli_query($db, $informacioPropiedad);
$propiedad = mysqli_fetch_assoc($resultadoInformacion);

//Consultar los vendedores
$consulta = "SELECT * FROM vendedores";
$resultado = mysqli_query($db, $consulta);

$titulo = $propiedad['titulo'];
$precio = $propiedad['precio'];
$descripcion = $propiedad['descripcion'];
$habitaciones = $propiedad['habitaciones'];
$wc = $propiedad['wc'];
$estacionamiento = $propiedad['estacionamiento'];
$vendedorId = $propiedad['vendedores_id'];

$errores = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    //Aplicar validacion por FILTER_VAR y FILTER_SANITIZE PENDIENTE

    $titulo = mysqli_escape_string($db, $_POST['titulo']);
    $precio = mysqli_escape_string($db, $_POST['precio']);
    $descripcion = mysqli_escape_string($db, $_POST['descripcion']);
    $habitaciones = mysqli_escape_string($db, $_POST['habitaciones']);
    $wc = mysqli_escape_string($db, $_POST['wc']);
    $estacionamiento = mysqli_escape_string($db, $_POST['estacionamiento']);
    $vendedorId = mysqli_escape_string($db, $_POST['vendedor']);
    $creado = date('Y/m/d');

    $imagen = $_FILES['imagen'];

    if (!$titulo) {
        $errores[] = 'Debes añadir un titulo';
    }
    if (!$precio) {
        $errores[] = 'Debes añadir un precio';
    }
    if (!$descripcion || strlen($descripcion) < 50) {
        $errores[] = 'Debes añadir una descripción y debe ser menor a 50 caracteres';
    }
    if (!$habitaciones) {
        $errores[] = 'Indica el número de habitaciones';
    }
    if (!$wc) {
        $errores[] = 'Indica el número de baños';
    }
    if (!$estacionamiento) {
        $errores[] = 'Indica el número de estacionamientos';
    }
    if (!$vendedorId) {
        $errores[] = 'Elige el vendedor';
    }
    /* if (!$imagen['name']) {
        $errores[] = 'La imagen es obligatoria';
    } */
    /* if ($imagen['size'] > 100000) {
        $errores[] = 'La imgen es demasiado grande';
    } */

    if (empty($errores)) {
        /* SUBIDA DE ARCHIVOS */

        //Crear carpeta
        $carpetaImagenes = '../../imagenes';

        if (!is_dir($carpetaImagenes)) {
            mkdir($carpetaImagenes);
        }

        //Nombre de la imagen
        $nombreImagen = md5(uniqid(rand(), true));

        if ($imagen['name']) {
            unlink($carpetaImagenes . '/' . $propiedad['imagen']);

            //Comprobar la extensión MIME
            if ($imagen['type'] === 'image/jpeg') {
                $nombreImagen .= '.jpg';
            }
            if ($imagen['type'] === 'image/png') {
                $nombreImagen .= '.png';
            }
            move_uploaded_file($imagen['tmp_name'], $carpetaImagenes . '/' . $nombreImagen);
        } else {
            $nombreImagen = $propiedad['imagen'];
        }

        //Insertar en la base de datos
        $query = "UPDATE propiedades SET titulo ='$titulo', precio='$precio', imagen= '$nombreImagen', descripcion='$descripcion', habitaciones='$habitaciones', wc='$wc', estacionamiento='$estacionamiento', creado='$creado', vendedores_id='$vendedorId' WHERE id='$id'";
        $resultado = mysqli_query($db, $query);

        if ($resultado) {
            header('Location: ../?resultado=2');
            exit;
        }
    }
}


incluirTemplate('header');
?>

<main class="contenedor seccion">
    <h1>Actualizar Propiedad</h1>

    <a href="../../admin" class="boton boton-verde">Volver</a>

    <?php foreach ($errores as $error) : ?>
        <div class="alerta error">
            <?php echo $error;  ?>
        </div>
    <?php endforeach ?>

    <form action="" class="formulario" method="POST" enctype="multipart/form-data">
        <fieldset>
            <legend>Información General</legend>

            <!-- Titulo -->
            <label for="titulo">Titulo:</label>
            <input type="text" id="titulo" name="titulo" placeholder="Titulo de propiedad" value="<?php echo $titulo ?>">

            <!-- Precio -->
            <label for="precio">Precio:</label>
            <input type="number" id="precio" name="precio" placeholder="Precio de propiedad" value="<?php echo $precio ?>">

            <!-- Imagen -->
            <label for="imagen">Imagen:</label>
            <input type="file" id="imagen" name="imagen" placeholder="Precio de propiedad" accept="image/jpeg, image/png">

            <!-- Descripción -->
            <label for="descripcion">Descripcion:</label>
            <textarea id="descripcion" name="descripcion"><?php echo $descripcion ?></textarea>
        </fieldset>

        <fieldset>
            <legend>Información Propiedad</legend>

            <!-- Habitaciones -->
            <label for="habitaciones">Habitaciones</label>
            <input type="number" id="habitaciones" name="habitaciones" placeholder="Ej: 2" min="1" max="9" value="<?php echo $habitaciones ?>">

            <!-- Baños -->
            <label for="wc">Baños</label>
            <input type="number" id="wc" name="wc" placeholder="Ej: 2" min="1" max="9" value="<?php echo $wc ?>">

            <!-- Estacionamiento -->
            <label for="estacionamiento">Estacionamiento</label>
            <input type="number" id="estacionamiento" name="estacionamiento" placeholder="Ej: 2" min="1" max="9" value="<?php echo $estacionamiento ?>">
        </fieldset>

        <fieldset>
            <legend>Vendedor</legend>
            <select name="vendedor">
                <option value="">Selecciona el vendedor</option>
                <?php while ($row = mysqli_fetch_assoc($resultado)) : ?>
                    <option <?php echo $vendedorId === $row['id'] ? 'selected' : '' ?> value="<?php echo $row['id'] ?>"><?php echo $row['nombre'] . ' ' .  $row['apellido'] ?></option>
                <?php endwhile ?>
            </select>
        </fieldset>

        <input type="submit" value="Actualizar propiedad" class="boton boton-verde">
    </form>



</main>

<?php incluirTemplate('footer'); ?>