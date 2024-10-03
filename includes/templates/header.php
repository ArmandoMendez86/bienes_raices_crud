<?php
if (!isset($_SESSION)) {
    session_start();
}

$auth = $_SESSION['login'] ?? false;

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienes Raices</title>
    <link rel="stylesheet" href="/bienes_raices/build/css/app.css">
</head>

<body>
    <header class="header <?php echo $inicio ? 'inicio' : ''   ?>">
        <div class="contenedor contenido-header">
            <div class="barra">
                <a href="/bienes_raices">
                    <img src="/bienes_raices/build/img/logo.svg" alt="Logotipo de Bienes Raices">
                </a>

                <div class="mobile-menu">
                    <img src="/bienes_raices/build/img/barras.svg" alt="icono menu responsive">
                </div>

                <div class="derecha">
                    <img class="dark-mode-boton" src="/bienes_raices/build/img/dark-mode.svg">
                    <nav class="navegacion">
                        <a href="/bienes_raices/nosotros.php">Nosotros</a>
                        <a href="/bienes_raices/anuncios.php">Anuncios</a>
                        <a href="/bienes_raices/blog.php">Blog</a>
                        <a href="/bienes_raices/contacto.php">Contacto</a>
                        <?php if ($auth) : ?>
                            <a href="/bienes_raices/cerrar-sesion.php">Cerrar sesión</a>
                        <?php endif ?>
                    </nav>
                </div>


            </div> <!--.barra-->

            <?php if ($inicio) : ?>
                <h1>Venta de Casas y Departamentos Exclusivos de Lujo</h1>
            <?php endif ?>
        </div>
    </header>