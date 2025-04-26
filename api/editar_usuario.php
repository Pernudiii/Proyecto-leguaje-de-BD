<?php
include_once "encabezado.php"; // Asegúrate que no imprime nada

// --- Manejo de CORS Preflight (OPTIONS) ---
// Permite solicitudes desde el origen de tu Vue Dev Server (ajusta si es necesario)
// ¡IMPORTANTE! En producción, sé más específico que '*' si es posible.
header("Access-Control-Allow-Origin: http://localhost:8080"); // O '*' para cualquiera
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With"); // Añade otras cabeceras que uses

// Si es una solicitud OPTIONS, simplemente termina con éxito (200 OK o 204 No Content)
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    // Opcional: header("HTTP/1.1 204 No Content");
    exit(0); // Termina el script sin procesar más
}
// --- Fin Manejo de CORS Preflight ---


// --- Lógica para POST/PUT (la que ya tenías) ---

// Establecer header JSON para la respuesta real
header('Content-Type: application/json');

$usuario = json_decode(file_get_contents("php://input"));

// Validación de entrada más específica
if (!$usuario || !isset($usuario->id_usuario) || !isset($usuario->nombre) /* añade otras validaciones */) {
    http_response_code(400); // Bad Request por datos inválidos
    echo json_encode(["success" => false, "error" => "Datos de entrada inválidos o incompletos para la edición."]);
    exit;
}

include_once "funciones.php"; // Donde está editarUsuario (versión OCI8)

try {
    $resultado_operacion = editarUsuario($usuario);

    if ($resultado_operacion) {
        echo json_encode(["success" => true, "message" => "Usuario actualizado correctamente."]);
    } else {
        // Error durante la ejecución OCI (ya logueado dentro de editarUsuario)
        http_response_code(500);
        echo json_encode(["success" => false, "error" => "No se pudo actualizar el usuario en la base de datos."]);
    }

} catch (InvalidArgumentException $e) {
     http_response_code(400);
     error_log("Error de validación en editar_usuario.php: " . $e->getMessage());
     echo json_encode(["success" => false, "error" => $e->getMessage()]);

} catch (Exception $e) {
    // Otros errores (conexión, etc.)
    http_response_code(500);
    error_log("Error catch general en editar_usuario.php: " . $e->getMessage());
    echo json_encode(["success" => false, "error" => "Ocurrió un error inesperado en el servidor durante la edición."]);
}

exit; // Terminar script
