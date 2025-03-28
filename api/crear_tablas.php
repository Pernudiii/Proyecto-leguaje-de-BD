<?php
include_once "encabezado.php";
include_once "funciones.php";

// Datos de conexión a Oracle
$dsn      = "oci:dbname=//localhost:1521/xe"; 
$usuario  = "ADMINISTRADOR";
$password = "123";

try {
    // Conexión a Oracle mediante PDO
    $conexion = new PDO($dsn, $usuario, $password);
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Ejemplo de respuesta final
    echo json_encode(["mensaje" => "Conexión exitosa a Oracle"]);
} catch (PDOException $e) {
    echo "Error de conexión: " . $e->getMessage();
    exit;
}

// Cierra la conexión
$conexion = null;

