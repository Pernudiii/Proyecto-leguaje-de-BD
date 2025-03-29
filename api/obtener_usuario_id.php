<?php
include_once "encabezado.php";

// Decodificamos el JSON
$idUsuario = json_decode(file_get_contents("php://input"));
if (!$idUsuario) {
    http_response_code(400); // Error de solicitud
    exit;
}

include_once "funciones.php";

// Usamos el idUsuario directamente si es un número simple
$resultado = obtenerUsuarioPorId($idUsuario);

// Devolvemos la respuesta en formato JSON
echo json_encode($resultado);



