<?php

function conectarDB()
{
    $db = mysqli_connect('localhost', 'root', '', 'bienes_raices_crud');

    if (!$db) {

        echo 'No se pudo conectar';
        exit();
    }
    return $db;
}
