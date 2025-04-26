<?php
// Busca los archivos en el MISMO directorio (api/)
include_once "funciones.php";
include_once "encabezado.php";

// --- El resto del script (headers, try/catch, etc.) ---
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: http://localhost:8080"); // O '*'
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') { exit(0); }

$response = [];
$http_status_code = 200;

try {
    $response = obtenerCategorias(); // Ahora debería encontrar esta función
} catch (Exception $e) {
    $http_status_code = 500;
    error_log("Error en obtener_categorias.php: " . $e->getMessage());
    $response = ["error" => "Error al obtener categorías."];
}

http_response_code($http_status_code);
echo json_encode($response);
exit;
?>