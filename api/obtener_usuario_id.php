<?php
include_once "encabezado.php"; // Asegúrate que no imprime nada

// Establecer header JSON temprano, pero después de includes/lógica inicial si es necesario
header('Content-Type: application/json');

// --- Añadir Debugging ---
$rawInput = file_get_contents("php://input");
error_log(">>> [obtener_usuario_id] Raw input recibido: " . $rawInput); // Log 1: Ver el input crudo

$datosEntrada = json_decode($rawInput);
error_log(">>> [obtener_usuario_id] Tipo de datos decodificados: " . gettype($datosEntrada)); // Log 2: Ver el tipo (object, null, etc)

if ($datosEntrada) {
    // Log 3: Ver el contenido del objeto si no es null
    error_log(">>> [obtener_usuario_id] Contenido decodificado: " . print_r($datosEntrada, true));
    // Log 4: Verificar específicamente si existe la propiedad 'id'
    error_log(">>> [obtener_usuario_id] ¿Existe propiedad 'id'?: " . (isset($datosEntrada->id) ? 'Sí' : 'No'));
    if(isset($datosEntrada->id)){
         // Log 5: Verificar si el valor de 'id' es numérico
         error_log(">>> [obtener_usuario_id] ¿Valor de 'id' es numérico?: " . (is_numeric($datosEntrada->id) ? 'Sí' : 'No') . " | Valor: " . $datosEntrada->id);
    }
} else {
     error_log(">>> [obtener_usuario_id] json_decode devolvió null o false.");
}
// --- Fin Debugging ---


// Validación (la misma que antes, pero ahora tendremos logs si falla)
if (!$datosEntrada || !isset($datosEntrada->id) || !is_numeric($datosEntrada->id)) {
    http_response_code(400); // Bad Request
    error_log(">>> [obtener_usuario_id] FALLO LA VALIDACIÓN."); // Log 6: Confirmar fallo
    echo json_encode(["error" => "Cuerpo de solicitud JSON inválido, vacío, o falta la propiedad 'id' numérica."]);
    exit;
}

// Si la validación pasa...
error_log(">>> [obtener_usuario_id] Validación SUPERADA."); // Log 7: Confirmar éxito validación
include_once "funciones.php";
$idUsuarioAObtener = intval($datosEntrada->id);
error_log(">>> [obtener_usuario_id] ID a obtener: " . $idUsuarioAObtener); // Log 8: Ver el ID final

try {
    $resultado = obtenerUsuarioPorId($idUsuarioAObtener);

    if ($resultado === null) {
         http_response_code(404);
         error_log(">>> [obtener_usuario_id] Usuario no encontrado (404).");
         echo json_encode(["error" => "Usuario no encontrado con el ID proporcionado."]);
    } else {
         error_log(">>> [obtener_usuario_id] Usuario encontrado, devolviendo datos.");
         echo json_encode($resultado);
    }

} catch (Exception $e) {
    http_response_code(500);
    error_log(">>> ERROR catch en obtener_usuario_id.php: " . $e->getMessage());
    echo json_encode(["error" => "Ocurrió un error al obtener los datos del usuario."]);
}

exit;
?>