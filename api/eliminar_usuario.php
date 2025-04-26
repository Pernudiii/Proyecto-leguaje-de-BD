<?php
include_once "encabezado.php"; // Asegúrate que no imprime nada

// --- Manejo de CORS Preflight (OPTIONS) ---
header("Access-Control-Allow-Origin: http://localhost:8080"); // O '*'
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-control-allow-headers: Content-Type, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit(0);
}
// --- Fin Manejo de CORS Preflight ---

// Establecer header JSON para la respuesta real
header('Content-Type: application/json');

// El frontend (HttpService.eliminar) envía {"id": valor}
$datosEntrada = json_decode(file_get_contents("php://input"));

// Validar entrada: debe ser objeto con propiedad 'id' numérica
if (!$datosEntrada || !isset($datosEntrada->id) || !is_numeric($datosEntrada->id)) {
    http_response_code(400); // Bad Request
    echo json_encode(["success" => false, "error" => "Solicitud inválida. Se requiere 'id' numérico en el cuerpo JSON."]);
    exit;
}

include_once "funciones.php"; // Donde está eliminarUsuario(int $id)

// Extraer el ID numérico
$idUsuarioAEliminar = intval($datosEntrada->id);

try {
    // Llamar a la función OCI8 para eliminar
    $resultado_operacion = eliminarUsuario($idUsuarioAEliminar);

    if ($resultado_operacion) {
        // Éxito
        echo json_encode(["success" => true, "message" => "Usuario eliminado correctamente."]);
    } else {
        // Error durante la ejecución OCI (ya logueado dentro de eliminarUsuario)
        http_response_code(500);
        echo json_encode(["success" => false, "error" => "No se pudo eliminar el usuario en la base de datos."]);
    }

} catch (Exception $e) {
    // Capturar cualquier otra excepción
    http_response_code(500);
    error_log("Error catch general en eliminar_usuario.php: " . $e->getMessage());
    echo json_encode(["success" => false, "error" => "Ocurrió un error inesperado en el servidor al eliminar."]);
}

exit; // Terminar script
?>