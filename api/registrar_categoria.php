<?php
// Asume que funciones.php y encabezado.php están en el mismo directorio 'api'
include_once "funciones.php";
include_once "encabezado.php";

// --- CORS ---
header("Access-Control-Allow-Origin: http://localhost:8080");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-control-allow-headers: Content-Type, Authorization, X-Requested-With");
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') { exit(0); }
// --- Fin CORS ---

header('Content-Type: application/json');

// Lee el objeto JSON enviado desde Vue ({tipo: ..., nombre: ..., descripcion: ...})
$categoria = json_decode(file_get_contents("php://input"));

// Validar entrada (basado en el SP INSERTAR corregido)
if (!$categoria || !isset($categoria->tipo) || trim($categoria->tipo) === '' || !isset($categoria->nombre) || trim($categoria->nombre) === '') {
    http_response_code(400);
    echo json_encode(["success" => false, "error" => "Se requiere 'tipo' y 'nombre' para registrar categoría."]);
    exit;
}

try {
    // Llama a la función OCI8 de funciones.php
    $resultado_operacion = registrarCategoria($categoria);

    if ($resultado_operacion) {
        echo json_encode(["success" => true, "message" => "Categoría registrada."]);
    } else {
        // La función OCI8 devolvió false (error durante execute)
        http_response_code(500);
        echo json_encode(["success" => false, "error" => "No se pudo registrar la categoría (falló la ejecución)."]);
    }
} catch (InvalidArgumentException $e) { // Captura validaciones de la función
     http_response_code(400);
     error_log("Error de validación en registrar_categoria.php: " . $e->getMessage());
     echo json_encode(["success" => false, "error" => $e->getMessage()]);
} catch (Exception $e) { // Captura errores OCI8 (conexión, constraint, etc.)
    http_response_code(500);
    error_log("Error catch general en registrar_categoria.php: " . $e->getMessage());
    $errorMsg = (strpos($e->getMessage(), 'ORA-00001') !== false) ? "Ya existe una categoría con ese nombre y tipo." : "Error inesperado al registrar.";
    echo json_encode(["success" => false, "error" => $errorMsg]);
}
exit;
?>
