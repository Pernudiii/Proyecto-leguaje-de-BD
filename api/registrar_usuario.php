<?php
include_once "encabezado.php";

$usuario = json_decode(file_get_contents("php://input"));
if (!$usuario) {
    http_response_code(500);
    exit;
}

include_once "funciones.php";

// Establece la contraseña por defecto
$usuario->password = "123";

// Puedes establecer manualmente un ID (si no es autoincremental)
$usuario->id = rand(1000, 9999); // Usa una lógica segura en producción

// Si no tiene rol, asigna uno por defecto
if (!isset($usuario->id_rol)) {
    $usuario->id_rol = 2; // Ejemplo: Asistente
}

$resultado = registrarUsuario($usuario);

echo json_encode($resultado);



