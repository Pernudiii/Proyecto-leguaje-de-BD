<?php
// api/obtener_categorias_tipo.php
include_once "funciones.php";
include_once "encabezado.php";

// --- CORS ---
header("Access-Control-Allow-Origin: http://localhost:8080");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-control-allow-headers: Content-Type, Authorization, X-Requested-With");
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') { exit(0); }
// --- Fin CORS ---

header('Content-Type: application/json');

$response = [];
$http_status_code = 200;
$tipo_filtro_final = null; // Variable para el valor limpio

// --- Debugging Input ---
$raw_input = file_get_contents("php://input");
error_log(">>> [obtener_categorias_tipo] Raw input: [$raw_input]"); // Entre corchetes para ver espacios

$decoded_input = json_decode($raw_input);
error_log(">>> [obtener_categorias_tipo] Decoded type: [" . gettype($decoded_input) . "]");
error_log(">>> [obtener_categorias_tipo] Decoded value: [" . print_r($decoded_input, true) . "]");

// --- Extracción y Limpieza del Tipo ---
if ($decoded_input !== null && is_string($decoded_input)) {
    // Si se decodificó como string (ej. de "\"PLATILLO\"")
    $tipo_filtro_final = trim($decoded_input); // Trim quita espacios comunes
    error_log(">>> [obtener_categorias_tipo] Tipo extraído del JSON string: [{$tipo_filtro_final}]");
} else {
     error_log(">>> [obtener_categorias_tipo] Input no fue decodificado como string non-null.");
     // $tipo_filtro_final permanece null
}

// --- Validación (Usando la variable final limpia) ---
$tipo_upper = $tipo_filtro_final ? strtoupper($tipo_filtro_final) : null; // Convertir a mayúsculas ANTES de in_array
$es_valido = $tipo_upper && in_array($tipo_upper, ['PLATILLO', 'BEBIDA']);

error_log(">>> [obtener_categorias_tipo] Antes de validar: tipo_filtro_final='{$tipo_filtro_final}', tipo_upper='{$tipo_upper}', es_valido=" . ($es_valido ? 'true' : 'false'));

if (!$es_valido) {
   http_response_code(400);
   error_log(">>> [obtener_categorias_tipo] Validation Failed! Valor final validado: '" . ($tipo_filtro_final ?? 'NULL') . "'");
   echo json_encode(["error" => "Tipo inválido o no proporcionado. Debe ser 'PLATILLO' o 'BEBIDA'."]);
   exit;
}

// --- Si la validación pasa ---
error_log(">>> [obtener_categorias_tipo] Validation OK. Llamando obtenerCategoriasPorTipo con: " . $tipo_filtro_final);
try {
    $response = obtenerCategoriasPorTipo($tipo_filtro_final); // Llama a la función OCI8
    http_response_code(200);
    echo json_encode($response);

} catch (Exception $e) {
    http_response_code(500);
    error_log(">>> ERROR catch en obtener_categorias_tipo.php: " . $e->getMessage());
    echo json_encode(["error" => "Error al obtener categorías por tipo."]);
}
exit;
?>