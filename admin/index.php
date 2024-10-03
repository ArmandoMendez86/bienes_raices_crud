<?php
require '../includes/funciones.php';

$auth = estaAutenticado();
if (!$auth) {
    header('Location: /bienes_raices');
}

require '../includes/config/database.php';
$db = conectarDB();

$query = "SELECT * FROM propiedades";
$propiedades = mysqli_query($db, $query);


$resultado = $_GET['resultado'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $id = filter_var($id, FILTER_VALIDATE_INT);

    if ($id) {

        //Eliminar imagen
        $imagen = "SELECT imagen FROM propiedades WHERE id = '$id'";
        $resultado = mysqli_query($db, $imagen);
        $resultadoImagen = mysqli_fetch_assoc($resultado);
        unlink('../imagenes/' . $resultadoImagen['imagen']);

        //Eliminar propiedad
        $propiedad = "DELETE FROM propiedades WHERE id = '$id'";
        $elminarPropiedad = mysqli_query($db, $propiedad);
        if ($elminarPropiedad) {
            header('Location: ./?resultado=3');
        }
    }
}



incluirTemplate('header');
?>

<main class="contenedor seccion">
    <h1>Administrador de Bienes Raices</h1>

    <?php if (intval($resultado) === 1) : ?>
        <p class="alerta exito">Anuncio Creado Correctamente</p>
    <?php elseif (intval($resultado) === 2) : ?>
        <p class="alerta exito">Anuncio Actualizado Correctamente</p>
    <?php elseif (intval($resultado) === 3) : ?>
        <p class="alerta exito">Anuncio Eliminado Correctamente</p>
    <?php endif ?>
    <a href="./propiedades/crear.php" class="boton boton-verde">Crear propiedad</a>

    <table class="propiedades">
        <thead>
            <tr>
                <th>ID</th>
                <th>Titulo</th>
                <th>Imagen</th>
                <th>Precio</th>
                <th>Acciones</th>
            </tr>
        </thead>

        <tbody>
            <?php while ($row = mysqli_fetch_assoc($propiedades)):  ?>
                <tr>
                    <td><?php echo $row['id'] ?></td>
                    <td><?php echo $row['titulo'] ?></td>
                    <td><img src="/bienes_raices/imagenes/<?php echo $row['imagen'] ?>" class="imagen-tabla"></td>
                    <td>$ <?php echo $row['precio'] ?></td>
                    <td>
                        <form method="POST">
                            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                            <input type="submit" value="Eliminar" class="boton-rojo-block">
                        </form>
                        <a href="./propiedades/actualizar.php?id=<?php echo $row['id'] ?>" class="boton-amarillo-block">Actualizar</a>
                    </td>
                </tr>
            <?php endwhile ?>
        </tbody>

    </table>
</main>

<?php
mysqli_close($db);
incluirTemplate('footer');
?>