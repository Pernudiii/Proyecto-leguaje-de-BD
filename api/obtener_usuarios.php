<?php
include_once "encabezado.php";
include_once "funciones.php";

// Llamada correcta a la función
$usuarios = obtenerUsuarios();

echo json_encode($usuarios);
