<?php

$pass = json_decode(file_get_contents("php://input"));
if (!$pass) exit("No se encontraron datos");

include_once "encabezado.php";
include_once "funciones.php";


$resultado = verificarPassword($pass->correo, $pass->password);

echo json_encode($resultado);

