<?php
// Asume que funciones.php y encabezado.php están en el mismo directorio 'api'
include_once "funciones.php";
include_once "encabezado.php";

// --- CORS ---
header("Access-Control-Allow-Origin: http://localhost:8080");
header("Access-Control-Allow-Methods: POST, DELETE, OPTIONS"); // POST o DELETE
header("Access-control-allow-headers: Content-Type, Authorization, X-Requested-With");
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') { exit(0); }
// --- Fin CORS ---

header('Content-Type: application/json');

// Asumiendo que Vue envía {"id": valor} en el body (como hacía HttpService.eliminar)
$datosEntrada = json_decode(file_get_contents("php://input"));

if (!$datosEntrada || !isset($datosEntrada->id) || !is_numeric($datosEntrada->id)) {
    http_response_code(400);
    echo json_encode(["success" => false, "error" => "Solicitud inválida. Se requiere 'id' numérico en el cuerpo JSON."]);
    exit;
}

// El ID de la categoría a eliminar
$idCategoriaAEliminar = intval($datosEntrada->id);

try {
    // Llama a la función OCI8 de funciones.php
    $resultado_operacion = eliminarCategoria($idCategoriaAEliminar);

    if ($resultado_operacion) {
        echo json_encode(["success" => true, "message" => "Categoría eliminada."]);
    } else {
        http_response_code(500);
        echo json_encode(["success" => false, "error" => "No se pudo eliminar la categoría (ejecución falló)."]);
    }
} catch (InvalidArgumentException $e) {
     http_response_code(400);
     echo json_encode(["success" => false, "error" => $e->getMessage()]);
} catch (Exception $e) {
    http_response_code(500);
    error_log("Error catch general en eliminar_categoria.php: " . $e->getMessage());
    $errorMsg = (strpos($e->getMessage(), 'ORA-02292') !== false) ? "No se puede eliminar, la categoría está en uso." : "Error inesperado al eliminar.";
    echo json_encode(["success" => false, "error" => $errorMsg]);
}
exit;
?>
