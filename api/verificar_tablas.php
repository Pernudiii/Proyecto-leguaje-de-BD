<?php
ini_set('display_errors', 1); // Para depuración
error_reporting(E_ALL);

// Incluye los archivos necesarios una sola vez
include_once "encabezado.php";
include_once "funciones.php"; // Asume que conectarBaseDatos() está aquí

header('Content-Type: application/json'); // Siempre enviar encabezado JSON

$resultado_final = ["existe" => false, "error" => null]; // Respuesta por defecto

try {
    // Usa la función de conexión centralizada
    $bd = conectarBaseDatos();

    // Verifica si el usuario/esquema 'ADMINISTRADOR' (o el correcto) existe
    // Usamos strtoupper para asegurar comparación en mayúsculas
    $nombreUsuarioBuscado = 'ADMINISTRADOR'; // Ajusta si tu usuario tiene otro nombre exacto
    $sql = "SELECT COUNT(*) AS total FROM ALL_USERS WHERE USERNAME = :username";
    $sentencia = $bd->prepare($sql);

    if (!$sentencia) {
        $errorInfo = $bd->errorInfo();
        throw new Exception("Error preparando la consulta de verificación: " . ($errorInfo[2] ?? 'Error desconocido'));
    }

    $sentencia->bindParam(':username', $nombreUsuarioBuscado, PDO::PARAM_STR);

    if (!$sentencia->execute()) {
        $errorInfo = $sentencia->errorInfo();
        throw new Exception("Error ejecutando la consulta de verificación: " . ($errorInfo[2] ?? 'Error desconocido'));
    }

    // Obtener el resultado (COUNT siempre devuelve una fila)
    $fila = $sentencia->fetch(PDO::FETCH_OBJ); // Fetch una sola fila como objeto

    // Si fetch fue exitoso y la propiedad existe (puede ser mayúscula o minúscula según config PDO)
    if ($fila && (isset($fila->TOTAL) || isset($fila->total)) ) {
        $conteo = isset($fila->TOTAL) ? $fila->TOTAL : $fila->total;
        $resultado_final["existe"] = ($conteo > 0); // True si el conteo es mayor a 0
    } else {
         // Esto no debería pasar con COUNT(*), pero por si acaso
         throw new Exception("No se pudo obtener el conteo de usuarios.");
    }

    $sentencia->closeCursor();

} catch (PDOException $e) {
    // Error específico de PDO (conexión, SQL)
    $resultado_final["error"] = "Error de Base de Datos: " . $e->getMessage();
    http_response_code(500); // Error interno del servidor
    // Loguear el error también es buena idea
    error_log("Error en verificar_tablas.php (PDO): " . $e->getMessage());

} catch (Exception $e) {
    // Otros errores generales
    $resultado_final["error"] = "Error General: " . $e->getMessage();
    http_response_code(500);
    error_log("Error en verificar_tablas.php (General): " . $e->getMessage());
} finally {
     // Considera cerrar la conexión si es apropiado
     // if (isset($bd)) { $bd = null; }
}

// Siempre devolver una respuesta JSON válida
echo json_encode($resultado_final);
exit; // Terminar script aquí
