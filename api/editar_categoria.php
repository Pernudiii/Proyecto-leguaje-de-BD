<?php
// Asume que funciones.php y encabezado.php están en el mismo directorio 'api'
include_once "funciones.php";
include_once "encabezado.php";

// --- CORS ---
header("Access-Control-Allow-Origin: http://localhost:8080");
header("Access-Control-Allow-Methods: POST, PUT, OPTIONS"); // POST o PUT
header("Access-control-allow-headers: Content-Type, Authorization, X-Requested-With");
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') { exit(0); }
// --- Fin CORS ---

header('Content-Type: application/json');

// Lee el objeto JSON enviado desde Vue ({id_categoria: ..., tipo: ..., nombre: ..., descripcion: ...})
$categoria = json_decode(file_get_contents("php://input"));

// Validar entrada (basado en el SP ACTUALIZAR corregido)
if (!$categoria || !isset($categoria->id_categoria) || !is_numeric($categoria->id_categoria) || !isset($categoria->tipo) || trim($categoria->tipo) === '' || !isset($categoria->nombre) || trim($categoria->nombre) === '') {
    http_response_code(400);
    echo json_encode(["success" => false, "error" => "Se requiere 'id_categoria' numérico, 'tipo' y 'nombre' para editar."]);
    exit;
}

try {
    // Llama a la función OCI8 de funciones.php
    $resultado_operacion = editarCategoria($categoria);

    if ($resultado_operacion) {
        echo json_encode(["success" => true, "message" => "Categoría actualizada."]);
    } else {
        http_response_code(500);
        echo json_encode(["success" => false, "error" => "No se pudo actualizar la categoría (ejecución falló)."]);
    }
} catch (InvalidArgumentException $e) {
     http_response_code(400);
     error_log("Error de validación en editar_categoria.php: " . $e->getMessage());
     echo json_encode(["success" => false, "error" => $e->getMessage()]);
} catch (Exception $e) {
    http_response_code(500);
    error_log("Error catch general en editar_categoria.php: " . $e->getMessage());
     $errorMsg = (strpos($e->getMessage(), 'ORA-00001') !== false) ? "Ya existe otra categoría con ese nombre y tipo." : "Error inesperado al actualizar.";
    echo json_encode(["success" => false, "error" => $errorMsg]);
}
exit;
?>
