<?php
include_once "encabezado.php";
include_once "funciones.php";

// Datos de conexión para Oracle 21c
$host = "localhost";
$port = "1521";
$sid  = "xe";
$usuario = "ADMINISTRADOR"; // Usuario con el que te conectas
$password = "123";

// Construir el DSN para PDO con Oracle
// charset=AL32UTF8 es un ejemplo, ajústalo si usas otro charset
$dsn = "oci:dbname=//$host:$port/$sid;charset=AL32UTF8";

try {
    // Crear la conexión PDO con Oracle
    $conexion = new PDO($dsn, $usuario, $password);
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conexion->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $conexion->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);

    // En Oracle, para saber si existe un usuario (esquema) 'BOTANERO_VENTAS',
    // consultamos la vista ALL_USERS (o DBA_USERS si tienes permisos).
    // Ten en cuenta que Oracle maneja los nombres de usuario en mayúsculas por defecto.
    $sentencia = $conexion->query("
        SELECT COUNT(*) AS resultado
        FROM ALL_USERS
        WHERE USERNAME = 'BOTANERO_VENTAS'
    ");

    $resultado = $sentencia->fetchAll();

    $conexion = null;

    // Retorna el resultado en JSON, por ejemplo { "resultado": 1 } si existe
    echo json_encode($resultado[0]);

} catch (PDOException $e) {
    echo "Error de conexión: " . $e->getMessage();
    exit;
}