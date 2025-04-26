<?php
ini_set('display_errors', 1); // Para depuración
error_reporting(E_ALL);

// ¡IMPORTANTE! Establecer ANTES de cualquier posible salida.
header('Content-Type: application/json');

include_once "encabezado.php"; // Asegúrate que no imprime NADA
include_once "funciones.php";  // Contiene conectarBaseDatos y obtenerUsuarios

$response = null; // Variable para la respuesta final
$http_status_code = 200; // Código OK por defecto

try {
    $usuarios = obtenerUsuarios(); // Llama a la función

    // Asume éxito si no hubo excepciones
    $response = $usuarios; // La respuesta son los datos obtenidos

} catch (Exception $e) {
    // Captura CUALQUIER excepción que haya sido relanzada
    $http_status_code = 500; // Error del servidor
    // Loguear el error detallado en el servidor
    error_log("Error final capturado en obtener_usuarios.php: " . $e->getMessage() . " | File: " . $e->getFile() . " | Line: " . $e->getLine());

    // Preparar respuesta de error para el cliente
    $response = [
        "error" => "Ocurrió un error en el servidor al obtener los usuarios.",
        // Opcional: incluir detalles SOLO en entorno de desarrollo
        // "details" => $e->getMessage()
    ];
}

// Establecer código de estado HTTP
http_response_code($http_status_code);

// ¡ÚNICA SALIDA JSON AL FINAL!
echo json_encode($response);
exit; // Terminar ejecución
?>

