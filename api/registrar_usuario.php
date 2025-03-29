<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

include_once "encabezado.php";
include_once "funciones.php";

// Leer los datos JSON del cuerpo de la solicitud
$usuario = json_decode(file_get_contents("php://input"));
if (!$usuario) {
    echo json_encode(false);
    exit;
}

// Establece la contraseña por defecto si no viene definida
if (!isset($usuario->password) || empty($usuario->password)) {
    $usuario->password = "Temporal123";
}

// Si no viene ID, genera uno (o puedes manejarlo en la BD)
if (!isset($usuario->id)) {
    $usuario->id = rand(1000, 9999); // Para demo; en producción usa secuencia o trigger
}
// Registrar
$resultado = registrarUsuario($usuario);

// Devolver respuesta JSON limpia
header('Content-Type: application/json');
echo json_encode($resultado);
