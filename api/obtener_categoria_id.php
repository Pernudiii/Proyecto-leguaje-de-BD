<?php
// Asume que funciones.php y encabezado.php están en el mismo directorio 'api'
include_once "funciones.php";
include_once "encabezado.php";

// --- CORS ---
header("Access-Control-Allow-Origin: http://localhost:8080");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS"); // Puede ser GET o POST
header("Access-control-allow-headers: Content-Type, Authorization, X-Requested-With");
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') { exit(0); }
// --- Fin CORS ---

header('Content-Type: application/json');

$idCategoriaAObtener = null;

// Verifica si el ID viene como parámetro GET (?id=...)
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['id']) && is_numeric($_GET['id'])) {
     $idCategoriaAObtener = intval($_GET['id']);
}
// Verifica si el ID viene como JSON POST {"id": ...}
elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
     $datosEntrada = json_decode(file_get_contents("php://input"));
     if ($datosEntrada && isset($datosEntrada->id) && is_numeric($datosEntrada->id)) {
          $idCategoriaAObtener = intval($datosEntrada->id);
     }
}

// Si no se encontró un ID válido
if ($idCategoriaAObtener === null) {
    http_response_code(400);
    echo json_encode(["error" => "Se requiere un 'id' numérico (GET o POST JSON)."]);
    exit;
}

try {
    // Llama a la función OCI8 de funciones.php
    $resultado = obtenerCategoriaPorId($idCategoriaAObtener);

    if ($resultado === null) {
         http_response_code(404); // Not Found
         echo json_encode(["error" => "Categoría no encontrada."]);
    } else {
         echo json_encode($resultado); // Devuelve el objeto categoría
    }

} catch (Exception $e) {
    http_response_code(500);
    error_log("Error en obtener_categoria_id.php: " . $e->getMessage());
    echo json_encode(["error" => "Error al obtener datos de la categoría."]);
}
exit;
?>
