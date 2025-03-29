<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

include_once "encabezado.php";
include_once "funciones.php";

try {
    $usuarios = obtenerUsuarios();

    if (!$usuarios) {
        throw new Exception("No se obtuvieron usuarios desde Oracle.");
    }

    // Para depuración:
    file_put_contents("log_usuarios.txt", print_r($usuarios, true));

    header('Content-Type: application/json');
    echo json_encode($usuarios);

} catch (Exception $e) {
    // Más detalles de error
    file_put_contents("log_error_usuarios.txt", $e->getMessage());
    http_response_code(500);
    echo json_encode(["error" => $e->getMessage()]);
}
