<?php

// Evita que errores/warnings se impriman en la salida
ini_set('display_errors', 0);
error_reporting(0);

$usuario = json_decode(file_get_contents("php://input"));
if (!$usuario) exit("No se encontraron datos");

include_once "encabezado.php";
include_once "funciones.php";

// Ya realiza la verificación y devuelve el usuario si es válido
$respuesta = iniciarSesion($usuario->correo, $usuario->password);

// Guardar el resultado en un archivo de texto para depuración
file_put_contents("debug_respuesta.txt", print_r($respuesta, true));

if ($respuesta) {
    $usuarioData = [
        "nombreUsuario" => $respuesta->NOMBRE,
        "idUsuario" => $respuesta->ID_USUARIO,
        "idRol" => $respuesta->ID_ROL
    ];

    // Guardamos en un archivo lo que hay en $usuarioData
    file_put_contents("debug_usuarioData.txt", print_r($usuarioData, true));

    $_SESSION["id_rol"] = $respuesta->ID_ROL;

    file_put_contents("debug_respuesta_final.txt", json_encode([
        "resultado" => true,
        "datos" => $usuarioData
    ], JSON_PRETTY_PRINT));

    echo json_encode([
        "resultado" => true,
        "datos" => $usuarioData
    ]);

} else {
    echo json_encode(["resultado" => false]);
}



/*
session_start();
if ($_SESSION["id_rol"] != 1) {
    http_response_code(403); // Prohibido
    echo json_encode(["error" => "No tiene permisos para realizar esta acción."]);
    exit;
}PROBAR LUEGO PARA OTORGAR PERMISOS DEPENDIENDO DEL ROL*/
