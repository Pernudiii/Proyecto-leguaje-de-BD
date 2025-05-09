<?php

define('DIRECTORIO', './fotos/');

function verificarTablas() {
    $bd = conectarBaseDatos();
    // Verifica si existe el esquema (usuario) "ADMINSTRADOR" en Oracle.
    $sentencia = $bd->query("SELECT COUNT(*) AS resultado FROM ALL_USERS WHERE USERNAME = 'ADMINISTRADOR'");
    return $sentencia->fetchAll();
}

function obtenerVentasPorMesesDeUsuario($anio, $idUsuario) {
    $bd = conectarBaseDatos();
    $sentencia = $bd->prepare("SELECT MONTH(fecha) AS mes, SUM(total) AS totalVentas FROM ventas 
        WHERE YEAR(fecha) = ? AND idUsuario = ?
        GROUP BY MONTH(fecha) ORDER BY mes ASC");
    $sentencia->execute([$anio, $idUsuario]);
    return $sentencia->fetchAll();
}

function obtenerVentasPorDiaMes($mes, $anio, $idUsuario){
	$bd = conectarBaseDatos();
	$sentencia = $bd->prepare("SELECT DAY(fecha) AS dia, SUM(total) AS totalVentas
	FROM ventas
	WHERE MONTH(fecha) = ? AND YEAR(fecha) = ? AND idUsuario = ?
	GROUP BY dia
	ORDER BY dia ASC");
	$sentencia->execute([$mes, $anio, $idUsuario]);
	return $sentencia->fetchAll();
}

function obtenerVentasSemanaDeUsuario($idUsuario) {
    $bd = conectarBaseDatos();
    $sentencia = $bd->prepare("SELECT DAYNAME(fecha) AS dia, DAYOFWEEK(fecha) AS numeroDia, 
	 SUM(total) AS totalVentas FROM ventas
     WHERE YEARWEEK(fecha)=YEARWEEK(CURDATE())
	 AND idUsuario = ?
     GROUP BY dia 
     ORDER BY fecha ASC");
	 $sentencia->execute([$idUsuario]);
    return $sentencia->fetchAll();

}

function obtenerInsumosMasVendidos($limite){
	$bd = conectarBaseDatos();
	$sentencia = $bd->prepare("SELECT SUM(insumos_venta.precio * insumos_venta.cantidad ) 
	AS total, insumos.nombre, insumos.tipo, IFNULL(categorias.nombre, 'NO DEFINIDA') AS categoria 
	FROM insumos_venta 
	INNER JOIN insumos ON insumos.id = insumos_venta.idInsumo 
	LEFT JOIN categorias ON categorias.id = insumos.categoria
	GROUP BY insumos_venta.idInsumo 
	ORDER BY total DESC 
	LIMIT ?");
	$sentencia->execute([$limite]);
	return $sentencia->fetchAll();
}

function obtenerTotalesPorMesa(){
	$bd = conectarBaseDatos();
	$sentencia = $bd->query("SELECT SUM(total) AS total, idMesa
	FROM ventas 
	GROUP BY idMesa
	ORDER BY total DESC");
	return $sentencia->fetchAll();
}

function obtenerVentasDelDia(){
	$bd = conectarBaseDatos();
	$sentencia = $bd->query("SELECT IFNULL(SUM(total),0) AS totalVentasHoy
	FROM ventas
	WHERE DATE(fecha) = CURDATE()");
	return $sentencia->fetchObject()->totalVentasHoy;
}

function obtenerNumeroUsuarios(){
	$bd = conectarBaseDatos();
	$sentencia = $bd->query("SELECT COUNT(*) AS numeroUsuarios
	FROM usuarios");
	return $sentencia->fetchObject()->numeroUsuarios;
}

function obtenerNumeroInsumos(){
	$bd = conectarBaseDatos();
	$sentencia = $bd->query("SELECT COUNT(*) AS numeroInsumos
	FROM insumos");
	return $sentencia->fetchObject()->numeroInsumos;
}

function obtenerTotalVentas(){
	$bd = conectarBaseDatos();
	$sentencia = $bd->query("SELECT IFNULL(SUM(total),0) AS totalVentas
	FROM ventas");
	return $sentencia->fetchObject()->totalVentas;
}

function obtenerNumeroMesasOcupadas(){
	$archivos = new FilesystemIterator("./mesas_ocupadas", FilesystemIterator::SKIP_DOTS);
	return iterator_count($archivos);
}

function obtenerVentasUsuario($fechaInicio, $fechaFin){
	$bd = conectarBaseDatos();
	$sentencia = $bd->prepare("SELECT usuarios.nombre, SUM(ventas.total) AS totalVentas
	FROM ventas
	INNER JOIN usuarios ON usuarios.id = ventas.idUsuario
	WHERE (DATE(fecha) >= ? AND DATE(fecha) <= ?)
	GROUP BY ventas.idUsuario");
	$sentencia->execute([$fechaInicio, $fechaFin]);
	return $sentencia->fetchAll();
}

function obtenerVentasPorHora($fechaInicio, $fechaFin) {
    $bd = conectarBaseDatos();
    $sentencia = $bd->prepare("SELECT DATE_FORMAT(fecha,'%H') AS hora, 
   	SUM(total) as totalVentas FROM ventas 
    WHERE (DATE(fecha) >= ? AND DATE(fecha) <= ?)
    GROUP BY DATE_FORMAT(fecha,'%H') 
    ORDER BY hora ASC
    ");
	$sentencia->execute([$fechaInicio, $fechaFin]);
    return $sentencia->fetchAll();
}

function obtenerVentasPorMeses($anio) {
    $bd = conectarBaseDatos();
    $sentencia = $bd->prepare("SELECT MONTH(fecha) AS mes, SUM(total) AS totalVentas FROM ventas 
        WHERE YEAR(fecha) = ?
        GROUP BY MONTH(fecha) ORDER BY mes ASC");
    $sentencia->execute([$anio]);
    return $sentencia->fetchAll();
}

function obtenerVentasDiasSemana() {
    $bd = conectarBaseDatos();
	$sql = "BEGIN FIDE_OBTENER_VENTAS_DIAS_SEMANA_SP(:p_resultado); END;";
    $stmt = $bd->prepare($sql);

    // Declaramos un cursor
    $stmt->bindParam(':p_resultado', $cursor, PDO::PARAM_STMT);
    $stmt->execute();

    // Ejecutamos el cursor
    oci_execute($cursor);

    // Recogemos los datos del cursor
    $datos = [];
    while ($fila = oci_fetch_assoc($cursor)) {
        $datos[] = $fila;
    }

    // Cerramos cursor
    oci_free_statement($cursor);

    return $datos;
}

function obtenerVentasPorUsuario($fechaInicio, $fechaFin){
	$bd = conectarBaseDatos();
	$sentencia = $bd->prepare("SELECT IFNULL(SUM(ventas.total), 0) AS total,
	usuarios.nombre 
	FROM ventas
	INNER JOIN usuarios ON usuarios.id = ventas.idUsuario
	WHERE (DATE(ventas.fecha) >= ? AND DATE(ventas.fecha) <= ?)
	GROUP BY ventas.idUsuario");
	$sentencia->execute([$fechaInicio, $fechaFin]);
	return $sentencia->fetchAll();
}

function obtenerVentas($fechaInicio, $fechaFin, $idUsuario){
	$bd = conectarBaseDatos();
	$valoresAEjecutar = [$fechaInicio, $fechaFin];
	
	$sql = "SELECT ventas.*, IFNULL(usuarios.nombre, 'NO ENCONTRADO') AS atendio 
	FROM ventas
	LEFT JOIN usuarios ON ventas.idUsuario = usuarios.id
	WHERE (DATE(ventas.fecha) >= ? AND DATE(ventas.fecha) <= ?)";

	if($idUsuario !== "") {
		$sql .= " AND ventas.idUsuario = ?";
		array_push($valoresAEjecutar, $idUsuario);
	}

	$sql .= " ORDER BY ventas.id DESC";

	$sentencia = $bd->prepare($sql);
	$sentencia->execute($valoresAEjecutar);
	return $sentencia->fetchAll();
}

function obtenerInsumosVenta($idVenta){
	$bd = conectarBaseDatos();
	$sentencia = $bd->prepare("SELECT insumos_venta.*, insumos.nombre, insumos.codigo
	 FROM insumos_venta 
	 LEFT JOIN insumos ON insumos.id = insumos_venta.idInsumo
	 WHERE idVenta = ?");
	$sentencia->execute([$idVenta]);
	return $sentencia->fetchAll(); 
}

function registrarVenta($venta){
	$bd = conectarBaseDatos();
	$sentencia = $bd->prepare("INSERT INTO ventas (idMesa, cliente, fecha, total, pagado, idUsuario) VALUES (?,?,?,?,?,?)");
	$sentencia->execute([$venta->idMesa, $venta->cliente, date("Y-m-d H:i:s"), $venta->total, $venta->pagado,  $venta->idUsario]);
	$idVenta = $bd->lastInsertId();

	$insumosRegistrados = registrarInsumosVenta($venta->insumos, $idVenta);
	$archivoEliminado = unlink("./mesas_ocupadas/". $venta->idMesa .".csv");
	if($sentencia && count($insumosRegistrados) > 0 && $archivoEliminado) return true;
}

function registrarInsumosVenta($insumos, $idVenta){
	$resultados = [];
	$bd = conectarBaseDatos();
	foreach($insumos as $insumo){
		$sentencia = $bd->prepare("INSERT INTO insumos_venta(idInsumo, precio, cantidad, idVenta) VALUES(?,?,?,?)");
		$sentencia->execute([$insumo->id, $insumo->precio, $insumo->cantidad, $idVenta]);
		if($sentencia) array_push($resultados, $sentencia);
	}
	return $resultados;
}

function obtenerMesas(){
	$mesas = [];
	$numeroMesas =[0]->numeroMesas;
	for($i = 1; $i <= $numeroMesas; $i++){
		array_push($mesas, leerArchivo($i)); 
	}
	return $mesas;
}

function leerArchivo($numeroMesa){
	if(file_exists("./mesas_ocupadas/". $numeroMesa .".csv")){
		$archivo = fopen("./mesas_ocupadas/". $numeroMesa .".csv", "r");
		if($archivo ){
			while (!feof($archivo) ) {
				$datos[] = fgetcsv($archivo, 1000, ',');
			}

			$mesa = [
				"idMesa" => $datos[0][0],
				"atiende" => $datos[0][1],
				"idUsuario" => $datos[0][2],
				"total" => $datos[0][3],
				"estado" => $datos[0][4],
				"cliente" => $datos[0][5],
			];

			$insumos = crearInsumosMesa($datos);
		
			fclose($archivo);
			return ["mesa" => $mesa, "insumos" => $insumos];
		}
	} else {
		$mesa = [
			"idMesa" => $numeroMesa,
			"atiende" => "",
			"idUsuario" => "",
			"total" => "",
			"estado" => "libre",
		];

		$insumos = [];
		return ["mesa" => $mesa, "insumos" => $insumos];
	}
}

function crearInsumosMesa($datos){
	$insumos = [];
	for($j = 1; $j < count($datos); $j++){
		$insumoTemp = [];
		$temp = $datos[$j];
		
		if (is_array($temp) || is_object($temp)){
			for($i = 0; $i < count($temp); $i++){
				$insumoTemp = [
					"id" => $temp[0],
					"codigo" => $temp[1],
					"nombre" => $temp[2],
					"precio" => $temp[3],
					"caracteristicas" => $temp[4],
					"cantidad" => $temp[5],
					"estado" => $temp[6]
				];
			}
			array_push($insumos, $insumoTemp);
		}
	}
	return $insumos;
}

function cancelarMesa($id){
	$archivoEliminado = unlink("./mesas_ocupadas/". $id .".csv");
	if($archivoEliminado){
		return true;
	}
}

function editarMesa($mesa){
	$archivoEliminado = unlink("./mesas_ocupadas/". $mesa->id .".csv");
	if($archivoEliminado){
		ocuparMesa($mesa);
		return true;
	}
}

function ocuparMesa($mesa){
	$archivo = fopen("./mesas_ocupadas/".$mesa->id.".csv", "w");
	$cliente = ($mesa->cliente === "") ? "MOSTRADOR": $mesa->cliente;
	fputcsv($archivo, array($mesa->id, $mesa->atiende, $mesa->idUsuario, $mesa->total, "ocupada", $cliente));
	foreach ($mesa->insumos as $insumo)
	{
		fputcsv($archivo,get_object_vars($insumo));
	}
	
	fclose($archivo);
	return true;
}

function cambiarPassword($idUsuario, $nuevaContrasena) {
    $bd = conectarBaseDatos();
    $sql = "BEGIN FIDE_USUARIO_CAMBIAR_PASSWORD_SP(:p_id_usuario, :p_nueva_contrasena); END;";
    $stmt = $bd->prepare($sql);
    $stmt->bindParam(':p_id_usuario', $idUsuario, PDO::PARAM_INT);
    $stmt->bindParam(':p_nueva_contrasena', $nuevaContrasena, PDO::PARAM_STR);
    return $stmt->execute();
}

function verificarPassword($correo, $password) {
    $bd = conectarBaseDatos();
    $sql = "BEGIN FIDE_USUARIO_VERIFICAR_PASSWORD_SP(:p_correo, :p_contrasena, :p_resultado); END;";
    $stmt = $bd->prepare($sql);

    $stmt->bindParam(":p_correo", $correo, PDO::PARAM_STR);
    $stmt->bindParam(":p_contrasena", $password, PDO::PARAM_STR);

    $resultado = "";
    $stmt->bindParam(":p_resultado", $resultado, PDO::PARAM_STR | PDO::PARAM_INPUT_OUTPUT, 20);

    $stmt->execute();

    // TEMPORAL: ver qué devuelve
    file_put_contents("debug_resultado.txt", "Resultado SP: '$resultado'");

    return (trim($resultado) === "VALIDO");
}



function iniciarSesion($correo, $password) {
    $bd = conectarBaseDatos();
    $sql = "SELECT id_usuario, nombre, correo, contrasena, id_rol 
            FROM FIDE_USUARIO_TB 
            WHERE correo = :correo";
    $stmt = $bd->prepare($sql);
    $stmt->bindParam(":correo", $correo, PDO::PARAM_STR);
    $stmt->execute();
    $usuario = $stmt->fetch(PDO::FETCH_OBJ);

    if ($usuario === false) {
        return false;
    }

	// 🛠️ DEBUG: Guardar lo que devuelve la consulta SQL antes de validar password
    file_put_contents("debug_usuario.txt", print_r($usuario, true));
    
    // Usamos la función verificarPassword para validar la contraseña
    if (verificarPassword($correo, $password)) {
        return $usuario;
    } else {
        return false;
    }
}



function eliminarUsuario(int $idUsuario) { // Asegura que recibe un entero
    $host = "localhost"; $port = "1521"; $sid = "xe";
    $user = "ADMINISTRADOR"; $pass = "123"; $charset = 'AL32UTF8';
    $tns = "//$host:$port/$sid";

    $conn = null; $stid = null;
    $exito = false;

    // Validar ID positivo (opcional pero bueno)
    if ($idUsuario <= 0) {
        error_log("Intento de eliminar usuario con ID inválido: " . $idUsuario);
        return false;
    }

    try {
        $conn = oci_connect($user, $pass, $tns, $charset);
        if (!$conn) throw new Exception("Error OCI8 al conectar (eliminar): " . oci_error()['message']);
        error_log("OCI8 [eliminarUsuario ID:{$idUsuario}]: Conexión OK.");

        // Llamada al SP con 1 parámetro :p_id (asegúrate que tu SP lo use)
        $sql = "BEGIN FIDE_USUARIO_ELIMINAR_SP(:p_id); END;";
        $stid = oci_parse($conn, $sql);
        if (!$stid) throw new Exception("Error OCI8 al parsear SP (eliminar): " . oci_error($conn)['message']);

        // Bind del parámetro de ENTRADA :p_id
        oci_bind_by_name($stid, ":p_id", $idUsuario, -1, SQLT_INT); // Bindea el entero

        // Ejecuta y confirma (el SP ya hace COMMIT)
        $exito = oci_execute($stid, OCI_COMMIT_ON_SUCCESS);

        if (!$exito) {
             $e = oci_error($stid);
             error_log("Error OCI8 al ejecutar SP (eliminar): " . ($e ? htmlentities($e['message']) : 'Error desconocido'));
        } else {
             error_log("OCI8 [eliminarUsuario ID:{$idUsuario}]: Ejecución exitosa.");
        }

        return $exito;

    } catch (Exception $e) {
        error_log(">>> ERROR en eliminarUsuario(ID: {$idUsuario}) [OCI8]: " . $e->getMessage());
        return false; // Indica fallo si hubo excepción
    } finally {
        if ($stid) @oci_free_statement($stid);
        if ($conn) @oci_close($conn);
        error_log("OCI8 [eliminarUsuario ID:{$idUsuario}]: Recursos liberados.");
    }
}

function editarUsuario($usuario) {
    $host = "localhost"; $port = "1521"; $sid = "xe";
    $user = "ADMINISTRADOR"; $pass = "123"; $charset = 'AL32UTF8';
    $tns = "//$host:$port/$sid";

    $conn = null; $stid = null;
    $exito = false;

    if (!isset($usuario->id_usuario) || !isset($usuario->nombre) || !isset($usuario->correo) || !isset($usuario->id_rol)) {
         throw new InvalidArgumentException("Datos incompletos para actualizar usuario.");
    }

    try {
        $conn = oci_connect($user, $pass, $tns, $charset);
        if (!$conn) throw new Exception("Error OCI8 al conectar (editar): " . oci_error()['message']);
        error_log("OCI8 [editarUsuario ID:{$usuario->id}]: Conexión OK.");

        // Llamada al SP con los 4 parámetros CORRECTOS
        $sql = "BEGIN FIDE_USUARIO_ACTUALIZAR_SP(:p_id_usuario, :p_nombre, :p_correo, :p_id_rol); END;";
        $stid = oci_parse($conn, $sql);
        if (!$stid) throw new Exception("Error OCI8 al parsear SP (editar): " . oci_error($conn)['message']);

        // Bind de los 4 parámetros de ENTRADA
        $idUsuarioInt = intval($usuario->id_usuario);
        $idRolInt = intval($usuario->id_rol);
        $nombreStr = strval($usuario->nombre);
        $correoStr = strval($usuario->correo);

        oci_bind_by_name($stid, ":p_id_usuario", $idUsuarioInt, -1, SQLT_INT);
        oci_bind_by_name($stid, ":p_nombre", $nombreStr);
        oci_bind_by_name($stid, ":p_correo", $correoStr);
        oci_bind_by_name($stid, ":p_id_rol", $idRolInt, -1, SQLT_INT);

        // Ejecuta y confirma (el SP ya hace COMMIT)
        $exito = oci_execute($stid, OCI_COMMIT_ON_SUCCESS);

        if (!$exito) {
             $e = oci_error($stid);
             error_log("Error OCI8 al ejecutar SP (editar): " . ($e ? htmlentities($e['message']) : 'Error desconocido'));
             // $exito ya es false
        } else {
             error_log("OCI8 [editarUsuario ID:{$usuario->id}]: Ejecución exitosa.");
        }

        return $exito;

    } catch (Exception $e) {
        error_log(">>> ERROR en editarUsuario(ID: {$usuario->id}) [OCI8]: " . $e->getMessage());
        // Podrías relanzar o simplemente retornar false
        // throw $e;
        return false; // Indica fallo si hubo excepción
    } finally {
        if ($stid) @oci_free_statement($stid);
        if ($conn) @oci_close($conn);
        error_log("OCI8 [editarUsuario ID:{$usuario->id}]: Recursos liberados.");
    }
}

function obtenerUsuarioPorId($idUsuario) {
    $conn = null;
    $stid = null;
    $curs = null;
    $usuario_final = null; // Resultado por defecto

    // --- Detalles de Conexión OCI8 ---
    $host = "localhost"; $port = "1521"; $sid  = "xe";
    $user = "ADMINISTRADOR"; $pass = "123"; $charset = 'AL32UTF8';
    $connection_string = "//" . $host . ":" . $port . "/" . $sid;
    // --- Fin Detalles de Conexión ---

    try {
        $conn = oci_connect($user, $pass, $connection_string, $charset);
        if (!$conn) {
            $e = oci_error();
            throw new Exception("Error de conexión OCI8: " . ($e['message'] ?? 'Error desconocido'));
        }

        // Asegúrate que los nombres coincidan con Oracle (P_ID_USUARIO, P_RESULTADO)
        $sql = "BEGIN FIDE_USUARIO_OBTENER_POR_ID_SP(:P_ID_USUARIO, :P_RESULTADO); END;";
        $stid = oci_parse($conn, $sql);
        if (!$stid) {
            $e = oci_error($conn);
            throw new Exception("Error al parsear SQL OCI8 (por ID): " . ($e['message'] ?? 'Error desconocido'));
        }

        // --- Bind Parámetro de ENTRADA ---
        $idUsuarioInt = intval($idUsuario); // Asegura que sea entero
        if (!oci_bind_by_name($stid, ":P_ID_USUARIO", $idUsuarioInt, -1, SQLT_INT)) { // Tipo SQLT_INT para NUMBER
             $e = oci_error($stid);
             throw new Exception("Error al bindear :P_ID_USUARIO OCI8: " . ($e['message'] ?? 'Error desconocido'));
        }

        // --- Bind Parámetro de SALIDA (Cursor) ---
        $curs = oci_new_cursor($conn);
        if (!$curs) {
            $e = oci_error($conn);
            throw new Exception("Error al crear cursor OCI8 (por ID): " . ($e['message'] ?? 'Error desconocido'));
        }
        if (!oci_bind_by_name($stid, ":P_RESULTADO", $curs, -1, OCI_B_CURSOR)) {
             $e = oci_error($stid);
             throw new Exception("Error al bindear :P_RESULTADO OCI8: " . ($e['message'] ?? 'Error desconocido'));
        }

        // Ejecuta el bloque PL/SQL
        if (!oci_execute($stid, OCI_NO_AUTO_COMMIT)) {
            $e = oci_error($stid);
            throw new Exception("Error al ejecutar procedimiento OCI8 (por ID): " . ($e['message'] ?? 'Error desconocido'));
        }

        // Ejecuta el CURSOR
        if (!oci_execute($curs, OCI_NO_AUTO_COMMIT)) {
             $e = oci_error($curs);
             throw new Exception("Error al ejecutar cursor OCI8 (por ID): " . ($e['message'] ?? 'Error desconocido'));
        }

        // Obtiene la ÚNICA fila esperada del cursor
        // Usamos oci_fetch_assoc para obtener claves asociativas (MAYÚSCULAS por defecto)
        $usuario_oci = oci_fetch_assoc($curs);

        if ($usuario_oci === false) {
             // No encontró fila o hubo error en fetch
             error_log(">>> [obtenerUsuarioPorId OCI] oci_fetch_assoc devolvió false para ID: " . $idUsuarioInt);
             // $usuario_final sigue siendo null
        } else {
             error_log(">>> [obtenerUsuarioPorId OCI] Fila leída: " . print_r($usuario_oci, true));
             // Convertir claves a minúsculas
             $usuario_formateado = array_change_key_case($usuario_oci, CASE_LOWER);
             // Convertir a objeto
             $usuario_final = (object)$usuario_formateado;
             error_log(">>> [obtenerUsuarioPorId OCI] Usuario formateado: " . print_r($usuario_final, true));
        }

    } catch (Exception $e) {
        $logMessage = date('Y-m-d H:i:s') . " - Error en obtenerUsuarioPorId(ID: $idUsuario) [OCI8]: " . $e->getMessage() . "\n";
        file_put_contents("log_error_usuarios.txt", $logMessage, FILE_APPEND);
        error_log(">>> ERROR en obtenerUsuarioPorId(ID: $idUsuario) [OCI8]: " . $e->getMessage());
        // $usuario_final sigue siendo null

    } finally {
        // Libera recursos OCI8
        if ($curs) { oci_free_statement($curs); }
        if ($stid) { oci_free_statement($stid); }
        if ($conn) { oci_close($conn); }
         error_log(">>> [obtenerUsuarioPorId OCI] Recursos OCI liberados.");
    }

    // Devuelve el objeto de usuario encontrado o null si no se encontró/hubo error
    return $usuario_final;
}

function obtenerUsuarios() {
    // --- Detalles de Conexión ---
    // Ajusta estos valores según tu configuración
    $host = "localhost";
    $port = "1521";
    $sid  = "xe"; // O SERVICE_NAME si usas eso
    $user = "ADMINISTRADOR"; // Usuario de la BD Oracle
    $pass = "123";       // Contraseña del usuario de la BD
    $charset = 'AL32UTF8'; // O el charset correcto para tu BD y datos

    // Construir la cadena de conexión TNS para oci_connect
    // Easy Connect es generalmente la más simple si está habilitado en Oracle
    $tns = "//$host:$port/$sid";

    // Inicializar variables
    $conn = null; // Conexión OCI8
    $stid = null; // Statement principal (llamada al SP)
    $curs = null; // Cursor OUT
    $usuarios_oracle = []; // Array para resultados crudos de Oracle (claves mayúsculas)
    $usuarios_final = [];  // Array final con claves en minúsculas

    try {
        // 1. Conectar usando OCI8
        error_log("OCI8: Intentando conectar a '$tns' con usuario '$user'...");
        $conn = oci_connect($user, $pass, $tns, $charset);
        if (!$conn) {
            $e = oci_error();
            error_log("Error OCI8 al conectar: " . ($e ? htmlentities($e['message']) : 'Error desconocido'));
            throw new Exception("Error OCI8 al conectar: " . ($e ? htmlentities($e['message']) : 'Error desconocido'));
        }
        error_log("OCI8: Conexión exitosa.");

        // 2. Preparar la llamada al procedimiento almacenado
        $sql = 'BEGIN FIDE_USUARIO_OBTENER_TODOS_SP(:cursor); END;';
        error_log("OCI8: Preparando SQL: " . $sql);
        $stid = oci_parse($conn, $sql);
        if (!$stid) {
            $e = oci_error($conn);
            error_log("Error OCI8 al parsear SP: " . ($e ? htmlentities($e['message']) : 'Error desconocido'));
            throw new Exception("Error OCI8 al parsear SP: " . ($e ? htmlentities($e['message']) : 'Error desconocido'));
        }
        error_log("OCI8: Parseo del SP exitoso.");

        // 3. Crear un nuevo descriptor de cursor
        $curs = oci_new_cursor($conn);
        if (!$curs) {
            $e = oci_error($conn);
            error_log("Error OCI8 al crear cursor: " . ($e ? htmlentities($e['message']) : 'Error desconocido'));
            throw new Exception("Error OCI8 al crear cursor: " . ($e ? htmlentities($e['message']) : 'Error desconocido'));
        }
        error_log("OCI8: Cursor creado.");

        // 4. Bindea el cursor PHP al placeholder :cursor del SP
        //    Se usa OCI_B_CURSOR para indicar que es un cursor.
        //    El tamaño -1 es estándar para cursores OUT.
        error_log("OCI8: Bindeando cursor al placeholder :cursor...");
        if (!oci_bind_by_name($stid, ":cursor", $curs, -1, OCI_B_CURSOR)) {
            $e = oci_error($stid);
            error_log("Error OCI8 al bindear cursor: " . ($e ? htmlentities($e['message']) : 'Error desconocido'));
            throw new Exception("Error OCI8 al bindear cursor: " . ($e ? htmlentities($e['message']) : 'Error desconocido'));
        }
        error_log("OCI8: Bindeo del cursor exitoso.");

        // 5. Ejecuta la llamada al procedimiento
        //    OCI_DEFAULT asegura que la ejecución no haga COMMIT automáticamente.
        error_log("OCI8: Ejecutando statement principal (llamada al SP)...");
        if (!oci_execute($stid, OCI_DEFAULT)) {
            $e = oci_error($stid);
            error_log("Error OCI8 al ejecutar SP: " . ($e ? htmlentities($e['message']) : 'Error desconocido'));
            throw new Exception("Error OCI8 al ejecutar SP: " . ($e ? htmlentities($e['message']) : 'Error desconocido'));
        }
        error_log("OCI8: Ejecución del SP exitosa.");

        // 6. Ejecuta el CURSOR que fue devuelto por el procedimiento
        error_log("OCI8: Ejecutando el cursor devuelto...");
        if (!oci_execute($curs, OCI_DEFAULT)) {
             $e = oci_error($curs);
             error_log("Error OCI8 al ejecutar el CURSOR devuelto: " . ($e ? htmlentities($e['message']) : 'Error desconocido'));
             throw new Exception("Error OCI8 al ejecutar el CURSOR devuelto: " . ($e ? htmlentities($e['message']) : 'Error desconocido'));
        }
        error_log("OCI8: Ejecución del CURSOR devuelto exitosa.");

        // 7. Obtiene todos los resultados del cursor en un array
        //    OCI_FETCHSTATEMENT_BY_ROW: Organiza el array por filas.
        //    OCI_ASSOC: Usa nombres de columna (asociativos) como claves.
        error_log("OCI8: Realizando fetch all del cursor...");
        $num_filas = oci_fetch_all($curs, $usuarios_oracle, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC);
        if ($num_filas === false) {
             $e = oci_error($curs);
             error_log("Error OCI8 durante fetch all: " . ($e ? htmlentities($e['message']) : 'Error desconocido'));
             // Podrías decidir continuar con un array vacío o lanzar excepción
             // throw new Exception("Error OCI8 durante fetch all: " . ($e ? htmlentities($e['message']) : 'Error desconocido'));
             $usuarios_oracle = []; // Asume array vacío si fetch falla pero no es excepción fatal
        }
        error_log("OCI8: Fetch all completado. Filas obtenidas: " . ($num_filas !== false ? $num_filas : 'Error/0'));
        // Descomenta para ver la estructura devuelta por Oracle:
        // error_log("OCI8: Datos crudos de Oracle: " . print_r($usuarios_oracle, true));

        // 8. *** Convertir claves a minúsculas ***
        error_log("OCI8: Convirtiendo claves a minúsculas...");
        foreach ($usuarios_oracle as $fila_oracle) {
            // array_change_key_case convierte todas las claves de un array
            $usuarios_final[] = array_change_key_case($fila_oracle, CASE_LOWER);
        }
        error_log("OCI8: Conversión de claves finalizada. Filas procesadas: " . count($usuarios_final));
        // Descomenta para ver la estructura final:
        // error_log("OCI8: Datos finales (claves minúsculas): " . print_r($usuarios_final, true));

        // 9. Liberar recursos (¡importante hacerlo siempre!)
        error_log("OCI8: Liberando recursos...");
        if ($curs) {
             @oci_free_statement($curs); // Usar @ para suprimir errores si ya falló antes
        }
        if ($stid) {
             @oci_free_statement($stid);
        }
        if ($conn) {
             @oci_close($conn);
        }
        error_log("OCI8: Recursos liberados.");

        // 10. Devolver el array con claves en minúsculas
        return $usuarios_final;

    } catch (Exception $e) {
        // Loguear cualquier excepción capturada durante el proceso
        error_log("--- ERROR CATCH GENERAL en obtenerUsuarios() [OCI8] ---");
        error_log("Mensaje: " . $e->getMessage());
        error_log("Archivo: " . $e->getFile() . " - Línea: " . $e->getLine());
        error_log("Stack Trace: " . $e->getTraceAsString());
        error_log("-------------------------------------------------------");


        // Asegurarse de intentar liberar recursos incluso en caso de error grave
        if ($curs) @oci_free_statement($curs);
        if ($stid) @oci_free_statement($stid);
        if ($conn) @oci_close($conn);
        error_log("OCI8: Recursos liberados (desde catch).");


        // Relanzar la excepción para que el script que llama (obtener_usuarios.php)
        // pueda manejarla y devolver una respuesta de error JSON apropiada.
        throw $e;
    }
}
function registrarUsuario($usuario) {
    $bd = conectarBaseDatos();

    $queryId = $bd->query("SELECT NVL(MAX(id_usuario), 0) + 1 AS nuevo_id FROM FIDE_USUARIO_TB");
    $nuevoId = $queryId->fetch(PDO::FETCH_OBJ)->NUEVO_ID;

    $sql = "BEGIN FIDE_USUARIO_INSERTAR_SP(:p_id_usuario, :p_nombre, :p_correo, :p_contrasena, :p_id_rol); END;";
    $stmt = $bd->prepare($sql);

    $stmt->bindParam(':p_id_usuario', $nuevoId, PDO::PARAM_INT);
    $stmt->bindParam(':p_nombre', $usuario->nombre, PDO::PARAM_STR);
    $stmt->bindParam(':p_correo', $usuario->correo, PDO::PARAM_STR);
    $stmt->bindParam(':p_contrasena', $usuario->password, PDO::PARAM_STR);
    $stmt->bindParam(':p_id_rol', $usuario->id_rol, PDO::PARAM_INT);

    return $stmt->execute();
}
if (!defined('DB_USER')) define('DB_USER', 'ADMINISTRADOR');
if (!defined('DB_PASS')) define('DB_PASS', '123');
if (!defined('DB_TNS')) define('DB_TNS', '//localhost:1521/xe');
if (!defined('DB_CHARSET')) define('DB_CHARSET', 'AL32UTF8');

function obtenerInsumos($filtros = null) {
    $conn = null; $stid = null; $curs = null;
    $insumos_oracle = []; $insumos_final = [];
    $procedure_name = 'FIDE_INSUMO_OBTENER_TODOS_SP'; // Llama al SP correcto

    try {
        $conn = oci_connect(DB_USER, DB_PASS, DB_TNS, DB_CHARSET);
        if (!$conn) throw new Exception("OCI8 Conectar (Insumos Todos): " . oci_error()['message']);

        $sql = "BEGIN {$procedure_name}(:cursor); END;";
        $stid = oci_parse($conn, $sql);
        if (!$stid) throw new Exception("OCI8 Parsear (Insumos Todos): " . oci_error($conn)['message']);

        $curs = oci_new_cursor($conn);
        if (!$curs) throw new Exception("OCI8 Crear Cursor (Insumos Todos): " . oci_error($conn)['message']);

        if (!oci_bind_by_name($stid, ":cursor", $curs, -1, OCI_B_CURSOR)) {
            throw new Exception("OCI8 Bind Cursor (Insumos Todos): " . oci_error($stid)['message']);
        }
        if (!oci_execute($stid, OCI_DEFAULT)) {
            throw new Exception("OCI8 Ejecutar SP (Insumos Todos): " . oci_error($stid)['message']);
        }
        if (!oci_execute($curs, OCI_DEFAULT)) {
             throw new Exception("OCI8 Ejecutar Cursor (Insumos Todos): " . oci_error($curs)['message']);
        }

        $num_filas = oci_fetch_all($curs, $insumos_oracle, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC);
        if ($num_filas === false) $insumos_oracle = [];

        foreach ($insumos_oracle as $fila_oracle) {
            $insumos_final[] = array_change_key_case($fila_oracle, CASE_LOWER);
        }

        // Filtrado en PHP (ajusta si tienes un SP para filtrar)
        if ($filtros && !empty(array_filter((array)$filtros))) {
             $insumos_filtrados = array_filter($insumos_final, function($insumo) use ($filtros) {
                 $pasa = true;
                 if (!empty($filtros->tipo) && isset($insumo['tipo']) && strtolower($insumo['tipo']) != strtolower($filtros->tipo)) $pasa = false;
                 if (!empty($filtros->categoria) && isset($insumo['id_categoria']) && $insumo['id_categoria'] != $filtros->categoria) $pasa = false;
                  if (!empty($filtros->nombre)) {
                      $nombre_match = isset($insumo['nombre']) && stripos($insumo['nombre'], $filtros->nombre) !== false;
                      $codigo_match = isset($insumo['codigo']) && stripos($insumo['codigo'], $filtros->nombre) !== false;
                      if (!$nombre_match && !$codigo_match) $pasa = false;
                 }
                 return $pasa;
             });
             return array_values($insumos_filtrados);
        } else {
            return $insumos_final;
        }
    } catch (Exception $e) {
        error_log(">>> ERROR obtenerInsumos [OCI8]: " . $e->getMessage()); throw $e;
    } finally {
        if ($curs) @oci_free_statement($curs); if ($stid) @oci_free_statement($stid); if ($conn) @oci_close($conn);
    }
}

function obtenerInsumoPorId(int $idInsumo) {
    $conn = null; $stid = null; $curs = null; $insumo_final = null;
    try {
        $conn = oci_connect(DB_USER, DB_PASS, DB_TNS, DB_CHARSET);
        if (!$conn) throw new Exception("OCI8 Conectar (Insumo ID): " . oci_error()['message']);
        $sql = "BEGIN FIDE_INSUMO_OBTENER_POR_ID_SP(:P_ID_INSUMO, :P_RESULTADO); END;";
        $stid = oci_parse($conn, $sql);
        if (!$stid) throw new Exception("OCI8 Parsear (Insumo ID): " . oci_error($conn)['message']);
        if (!oci_bind_by_name($stid, ":P_ID_INSUMO", $idInsumo, -1, SQLT_INT)) throw new Exception("OCI8 Bind ID (Insumo ID): " . oci_error($stid)['message']);
        $curs = oci_new_cursor($conn);
        if (!$curs) throw new Exception("OCI8 Crear Cursor (Insumo ID): " . oci_error($conn)['message']);
        if (!oci_bind_by_name($stid, ":P_RESULTADO", $curs, -1, OCI_B_CURSOR)) throw new Exception("OCI8 Bind Cursor (Insumo ID): " . oci_error($stid)['message']);
        if (!oci_execute($stid, OCI_DEFAULT)) throw new Exception("OCI8 Ejecutar SP (Insumo ID): " . oci_error($stid)['message']);
        if (!oci_execute($curs, OCI_DEFAULT)) throw new Exception("OCI8 Ejecutar Cursor (Insumo ID): " . oci_error($curs)['message']);
        $insumo_oci = oci_fetch_assoc($curs);
        if ($insumo_oci !== false) $insumo_final = (object)array_change_key_case($insumo_oci, CASE_LOWER);
        return $insumo_final;
    } catch (Exception $e) {
        error_log(">>> ERROR obtenerInsumoPorId(ID:{$idInsumo}) [OCI8]: " . $e->getMessage()); throw $e;
    } finally {
        if ($curs) @oci_free_statement($curs); if ($stid) @oci_free_statement($stid); if ($conn) @oci_close($conn);
    }
}

function registrarInsumo($insumo) {
    $conn = null; $stid = null; $exito = false;
    if (!isset($insumo->codigo) || !isset($insumo->nombre) || !isset($insumo->precio) || !isset($insumo->tipo) || !isset($insumo->categoria)) throw new InvalidArgumentException("Datos incompletos para registrar insumo.");
    try {
        $conn = oci_connect(DB_USER, DB_PASS, DB_TNS, DB_CHARSET);
        if (!$conn) throw new Exception("OCI8 Conectar (Registrar Insumo): " . oci_error()['message']);
        $sql = "BEGIN FIDE_INSUMO_INSERTAR_SP(:p_codigo, :p_nombre, :p_descripcion, :p_precio, :p_tipo, :p_id_categoria); END;";
        $stid = oci_parse($conn, $sql);
        if (!$stid) throw new Exception("OCI8 Parsear (Registrar Insumo): " . oci_error($conn)['message']);
        $precioFloat = floatval($insumo->precio);
        $idCategoriaInt = intval($insumo->categoria);
        $descripcionStr = isset($insumo->descripcion) ? strval($insumo->descripcion) : null;
        oci_bind_by_name($stid, ":p_codigo", $insumo->codigo);
        oci_bind_by_name($stid, ":p_nombre", $insumo->nombre);
        oci_bind_by_name($stid, ":p_descripcion", $descripcionStr);
        oci_bind_by_name($stid, ":p_precio", $precioFloat);
        oci_bind_by_name($stid, ":p_tipo", $insumo->tipo);
        oci_bind_by_name($stid, ":p_id_categoria", $idCategoriaInt, -1, SQLT_INT);
        $exito = oci_execute($stid, OCI_COMMIT_ON_SUCCESS);
        if (!$exito) error_log("Error OCI8 Ejecutar (Registrar Insumo): " . ($e = oci_error($stid)) ? htmlentities($e['message']) : 'Error desconocido');
        return $exito;
    } catch (Exception $e) {
        error_log(">>> ERROR registrarInsumo [OCI8]: " . $e->getMessage()); throw $e;
    } finally {
        if ($stid) @oci_free_statement($stid); if ($conn) @oci_close($conn);
    }
}

function editarInsumo($insumo) {
    $conn = null; $stid = null; $exito = false;
    if (!isset($insumo->id) || !isset($insumo->codigo) || !isset($insumo->nombre) || !isset($insumo->precio) || !isset($insumo->tipo) || !isset($insumo->categoria)) throw new InvalidArgumentException("Datos incompletos para editar insumo (id requerido).");
    try {
        $conn = oci_connect(DB_USER, DB_PASS, DB_TNS, DB_CHARSET);
        if (!$conn) throw new Exception("OCI8 Conectar (Editar Insumo): " . oci_error()['message']);
        $sql = "BEGIN FIDE_INSUMO_ACTUALIZAR_SP(:p_id_insumo, :p_codigo, :p_nombre, :p_descripcion, :p_precio, :p_tipo, :p_id_categoria); END;";
        $stid = oci_parse($conn, $sql);
        if (!$stid) throw new Exception("OCI8 Parsear (Editar Insumo): " . oci_error($conn)['message']);
        $idInsumoInt = intval($insumo->id);
        $precioFloat = floatval($insumo->precio);
        $idCategoriaInt = intval($insumo->categoria);
        $descripcionStr = isset($insumo->descripcion) ? strval($insumo->descripcion) : null;
        oci_bind_by_name($stid, ":p_id_insumo", $idInsumoInt, -1, SQLT_INT);
        oci_bind_by_name($stid, ":p_codigo", $insumo->codigo);
        oci_bind_by_name($stid, ":p_nombre", $insumo->nombre);
        oci_bind_by_name($stid, ":p_descripcion", $descripcionStr);
        oci_bind_by_name($stid, ":p_precio", $precioFloat);
        oci_bind_by_name($stid, ":p_tipo", $insumo->tipo);
        oci_bind_by_name($stid, ":p_id_categoria", $idCategoriaInt, -1, SQLT_INT);
        $exito = oci_execute($stid, OCI_COMMIT_ON_SUCCESS);
        if (!$exito) error_log("Error OCI8 Ejecutar (Editar Insumo): " . ($e = oci_error($stid)) ? htmlentities($e['message']) : 'Error desconocido');
        return $exito;
    } catch (Exception $e) {
        error_log(">>> ERROR editarInsumo(ID: {$insumo->id}) [OCI8]: " . $e->getMessage()); throw $e;
    } finally {
        if ($stid) @oci_free_statement($stid); if ($conn) @oci_close($conn);
    }
}

function eliminarInsumo(int $idInsumo) {
    $conn = null; $stid = null; $exito = false;
    if ($idInsumo <= 0) throw new InvalidArgumentException("ID de insumo inválido para eliminar.");
    try {
        $conn = oci_connect(DB_USER, DB_PASS, DB_TNS, DB_CHARSET);
        if (!$conn) throw new Exception("OCI8 Conectar (Eliminar Insumo): " . oci_error()['message']);
        // Asegúrate que el parámetro en el SP se llame :P_ID
        $sql = "BEGIN FIDE_INSUMO_ELIMINAR_SP(:P_ID); END;";
        $stid = oci_parse($conn, $sql);
        if (!$stid) throw new Exception("OCI8 Parsear (Eliminar Insumo): " . oci_error($conn)['message']);
        oci_bind_by_name($stid, ":P_ID", $idInsumo, -1, SQLT_INT);
        $exito = oci_execute($stid, OCI_COMMIT_ON_SUCCESS);
        if (!$exito) error_log("Error OCI8 Ejecutar (Eliminar Insumo): " . ($e = oci_error($stid)) ? htmlentities($e['message']) : 'Error desconocido');
        return $exito;
    } catch (Exception $e) {
        error_log(">>> ERROR eliminarInsumo(ID: {$idInsumo}) [OCI8]: " . $e->getMessage()); throw $e;
    } finally {
        if ($stid) @oci_free_statement($stid); if ($conn) @oci_close($conn);
    }
}

function obtenerImagen($imagen){
    $imagen = str_replace('data:image/png;base64,', '', $imagen);
    $imagen = str_replace('data:image/jpeg;base64,', '', $imagen);
    $imagen = str_replace(' ', '+', $imagen);
    $data = base64_decode($imagen);
    $file = DIRECTORIO. uniqid() . '.png';
            
            
    $insertar = file_put_contents($file, $data);
    return $file;
}

function obtenerCategoriasPorTipo(string $tipo) {
    $conn = null; $stid = null; $curs = null;
    $categorias_oracle = []; $categorias_final = [];

    // Validar tipo (básico)
    if (empty($tipo) || !in_array(strtoupper($tipo), ['PLATILLO', 'BEBIDA'])) {
        // Si el tipo está vacío o no es válido, devuelve un array vacío
        // o podrías lanzar InvalidArgumentException si prefieres ser más estricto
        return [];
        // throw new InvalidArgumentException("Tipo inválido para obtener categorías.");
    }

    try {
        $conn = oci_connect(DB_USER, DB_PASS, DB_TNS, DB_CHARSET);
        if (!$conn) throw new Exception("OCI8 Conectar (Categorias Por Tipo): " . oci_error()['message']);

        $sql = "BEGIN FIDE_CATEGORIA_OBTENER_POR_TIPO_SP(:P_TIPO, :P_RESULTADO); END;";
        $stid = oci_parse($conn, $sql);
        if (!$stid) throw new Exception("OCI8 Parsear (Categorias Por Tipo): " . oci_error($conn)['message']);

        // Bind Parámetro ENTRADA (Tipo)
        if (!oci_bind_by_name($stid, ":P_TIPO", $tipo)) { // No necesita tipo específico SQLT_*
             throw new Exception("OCI8 Bind Tipo (Categorias Por Tipo): " . oci_error($stid)['message']);
        }
        // Bind Parámetro SALIDA (Cursor)
        $curs = oci_new_cursor($conn);
        if (!$curs) throw new Exception("OCI8 Crear Cursor (Categorias Por Tipo): " . oci_error($conn)['message']);
        if (!oci_bind_by_name($stid, ":P_RESULTADO", $curs, -1, OCI_B_CURSOR)) {
             throw new Exception("OCI8 Bind Cursor (Categorias Por Tipo): " . oci_error($stid)['message']);
        }

        if (!oci_execute($stid, OCI_DEFAULT)) {
            throw new Exception("OCI8 Ejecutar SP (Categorias Por Tipo): " . oci_error($stid)['message']);
        }
        if (!oci_execute($curs, OCI_DEFAULT)) {
            throw new Exception("OCI8 Ejecutar Cursor (Categorias Por Tipo): " . oci_error($curs)['message']);
        }

        $num_filas = oci_fetch_all($curs, $categorias_oracle, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC);
        if ($num_filas === false) $categorias_oracle = [];

        foreach ($categorias_oracle as $fila_oracle) {
            $categorias_final[] = array_change_key_case($fila_oracle, CASE_LOWER);
        }
        return $categorias_final;

    } catch (Exception $e) {
        error_log(">>> ERROR obtenerCategoriasPorTipo(Tipo:{$tipo}) [OCI8]: " . $e->getMessage()); throw $e;
    } finally {
        if ($curs) @oci_free_statement($curs); if ($stid) @oci_free_statement($stid); if ($conn) @oci_close($conn);
    }
}
function obtenerCategorias() {
    $conn = null; $stid = null; $curs = null;
    $categorias_oracle = []; $categorias_final = [];
    try {
        $conn = oci_connect(DB_USER, DB_PASS, DB_TNS, DB_CHARSET);
        if (!$conn) throw new Exception("OCI8 Conectar (Categorias Todas): " . oci_error()['message']);
        $sql = 'BEGIN FIDE_CATEGORIA_OBTENER_TODAS_SP(:cursor); END;';
        $stid = oci_parse($conn, $sql);
        if (!$stid) throw new Exception("OCI8 Parsear (Categorias Todas): " . oci_error($conn)['message']);
        $curs = oci_new_cursor($conn);
        if (!$curs) throw new Exception("OCI8 Crear Cursor (Categorias Todas): " . oci_error($conn)['message']);
        if (!oci_bind_by_name($stid, ":cursor", $curs, -1, OCI_B_CURSOR)) throw new Exception("OCI8 Bind Cursor (Categorias Todas): " . oci_error($stid)['message']);
        if (!oci_execute($stid, OCI_DEFAULT)) throw new Exception("OCI8 Ejecutar SP (Categorias Todas): " . oci_error($stid)['message']);
        if (!oci_execute($curs, OCI_DEFAULT)) throw new Exception("OCI8 Ejecutar Cursor (Categorias Todas): " . oci_error($curs)['message']);
        $num_filas = oci_fetch_all($curs, $categorias_oracle, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC);
        if ($num_filas === false) $categorias_oracle = [];
        foreach ($categorias_oracle as $fila_oracle) $categorias_final[] = array_change_key_case($fila_oracle, CASE_LOWER);
        return $categorias_final;
    } catch (Exception $e) {
        error_log(">>> ERROR obtenerCategorias [OCI8]: " . $e->getMessage()); throw $e;
    } finally {
        if ($curs) @oci_free_statement($curs); if ($stid) @oci_free_statement($stid); if ($conn) @oci_close($conn);
    }
}

function obtenerCategoriaPorId(int $idCategoria) {
    $conn = null; $stid = null; $curs = null; $categoria_final = null;
    try {
        $conn = oci_connect(DB_USER, DB_PASS, DB_TNS, DB_CHARSET);
        if (!$conn) throw new Exception("OCI8 Conectar (Categoria ID): " . oci_error()['message']);
        $sql = "BEGIN FIDE_CATEGORIA_OBTENER_POR_ID_SP(:P_ID_CATEGORIA, :P_RESULTADO); END;";
        $stid = oci_parse($conn, $sql);
        if (!$stid) throw new Exception("OCI8 Parsear (Categoria ID): " . oci_error($conn)['message']);
        if (!oci_bind_by_name($stid, ":P_ID_CATEGORIA", $idCategoria, -1, SQLT_INT)) throw new Exception("OCI8 Bind ID (Categoria ID): " . oci_error($stid)['message']);
        $curs = oci_new_cursor($conn);
        if (!$curs) throw new Exception("OCI8 Crear Cursor (Categoria ID): " . oci_error($conn)['message']);
        if (!oci_bind_by_name($stid, ":P_RESULTADO", $curs, -1, OCI_B_CURSOR)) throw new Exception("OCI8 Bind Cursor (Categoria ID): " . oci_error($stid)['message']);
        if (!oci_execute($stid, OCI_DEFAULT)) throw new Exception("OCI8 Ejecutar SP (Categoria ID): " . oci_error($stid)['message']);
        if (!oci_execute($curs, OCI_DEFAULT)) throw new Exception("OCI8 Ejecutar Cursor (Categoria ID): " . oci_error($curs)['message']);
        $categoria_oci = oci_fetch_assoc($curs);
        if ($categoria_oci !== false) $categoria_final = (object)array_change_key_case($categoria_oci, CASE_LOWER);
        return $categoria_final;
    } catch (Exception $e) {
        error_log(">>> ERROR obtenerCategoriaPorId(ID:{$idCategoria}) [OCI8]: " . $e->getMessage()); throw $e;
    } finally {
        if ($curs) @oci_free_statement($curs); if ($stid) @oci_free_statement($stid); if ($conn) @oci_close($conn);
    }
}

function registrarCategoria($categoria) { // $categoria debe tener ->tipo, ->nombre, ->descripcion (opcional)
    $conn = null; $stid = null; $exito = false;
    if (!isset($categoria->tipo) || trim($categoria->tipo) === '' || !isset($categoria->nombre) || trim($categoria->nombre) === '') {
         throw new InvalidArgumentException("Se requiere tipo y nombre para registrar categoría.");
    }
    try {
        $conn = oci_connect(DB_USER, DB_PASS, DB_TNS, DB_CHARSET);
        if (!$conn) throw new Exception("OCI8 Conectar (Registrar Categoria): " . oci_error()['message']);
        $sql = "BEGIN FIDE_CATEGORIA_INSERTAR_SP(:p_tipo, :p_nombre, :p_descripcion); END;";
        $stid = oci_parse($conn, $sql);
        if (!$stid) throw new Exception("OCI8 Parsear (Registrar Categoria): " . oci_error($conn)['message']);
        $descripcionStr = isset($categoria->descripcion) ? strval($categoria->descripcion) : null;
        oci_bind_by_name($stid, ":p_tipo", $categoria->tipo);
        oci_bind_by_name($stid, ":p_nombre", $categoria->nombre);
        oci_bind_by_name($stid, ":p_descripcion", $descripcionStr);
        $exito = oci_execute($stid, OCI_COMMIT_ON_SUCCESS);
        if (!$exito) error_log("Error OCI8 Ejecutar (Registrar Categoria): " . ($e = oci_error($stid)) ? htmlentities($e['message']) : 'Error desconocido');
        return $exito;
    } catch (Exception $e) {
        error_log(">>> ERROR registrarCategoria [OCI8]: " . $e->getMessage()); throw $e;
    } finally {
        if ($stid) @oci_free_statement($stid); if ($conn) @oci_close($conn);
    }
}

function editarCategoria($categoria) { // $categoria debe tener ->id_categoria, ->tipo, ->nombre, ->descripcion (opcional)
    $conn = null; $stid = null; $exito = false;
    if (!isset($categoria->id_categoria) || !is_numeric($categoria->id_categoria) || !isset($categoria->tipo) || trim($categoria->tipo) === '' || !isset($categoria->nombre) || trim($categoria->nombre) === '') {
         throw new InvalidArgumentException("Se requiere ID numérico, tipo y nombre para editar categoría.");
    }
    try {
        $conn = oci_connect(DB_USER, DB_PASS, DB_TNS, DB_CHARSET);
        if (!$conn) throw new Exception("OCI8 Conectar (Editar Categoria): " . oci_error()['message']);
        $sql = "BEGIN FIDE_CATEGORIA_ACTUALIZAR_SP(:p_id_categoria, :p_tipo, :p_nombre, :p_descripcion); END;";
        $stid = oci_parse($conn, $sql);
        if (!$stid) throw new Exception("OCI8 Parsear (Editar Categoria): " . oci_error($conn)['message']);
        $idCategoriaInt = intval($categoria->id_categoria);
        $descripcionStr = isset($categoria->descripcion) ? strval($categoria->descripcion) : null;
        oci_bind_by_name($stid, ":p_id_categoria", $idCategoriaInt, -1, SQLT_INT);
        oci_bind_by_name($stid, ":p_tipo", $categoria->tipo);
        oci_bind_by_name($stid, ":p_nombre", $categoria->nombre);
        oci_bind_by_name($stid, ":p_descripcion", $descripcionStr);
        $exito = oci_execute($stid, OCI_COMMIT_ON_SUCCESS);
        if (!$exito) error_log("Error OCI8 Ejecutar (Editar Categoria): " . ($e = oci_error($stid)) ? htmlentities($e['message']) : 'Error desconocido');
        return $exito;
    } catch (Exception $e) {
        error_log(">>> ERROR editarCategoria(ID: {$categoria->id_categoria}) [OCI8]: " . $e->getMessage()); throw $e;
    } finally {
        if ($stid) @oci_free_statement($stid); if ($conn) @oci_close($conn);
    }
}

function eliminarCategoria(int $idCategoria) {
    $conn = null; $stid = null; $exito = false;
    if ($idCategoria <= 0) throw new InvalidArgumentException("ID de categoría inválido para eliminar.");
    try {
        $conn = oci_connect(DB_USER, DB_PASS, DB_TNS, DB_CHARSET);
        if (!$conn) throw new Exception("OCI8 Conectar (Eliminar Categoria): " . oci_error()['message']);
        $sql = "BEGIN FIDE_CATEGORIA_ELIMINAR_SP(:P_ID_CATEGORIA); END;";
        $stid = oci_parse($conn, $sql);
        if (!$stid) throw new Exception("OCI8 Parsear (Eliminar Categoria): " . oci_error($conn)['message']);
        oci_bind_by_name($stid, ":P_ID_CATEGORIA", $idCategoria, -1, SQLT_INT);
        $exito = oci_execute($stid, OCI_COMMIT_ON_SUCCESS);
        if (!$exito) error_log("Error OCI8 Ejecutar (Eliminar Categoria): " . ($e = oci_error($stid)) ? htmlentities($e['message']) : 'Error desconocido');
        return $exito;
    } catch (Exception $e) {
        error_log(">>> ERROR eliminarCategoria(ID: {$idCategoria}) [OCI8]: " . $e->getMessage()); throw $e;
    } finally {
        if ($stid) @oci_free_statement($stid); if ($conn) @oci_close($conn);
    }
}


function conectarBaseDatos() {
    $host = "localhost";
    $port = "1521";      // Puerto por defecto de Oracle
    $sid  = "xe";        // O SERVICE_NAME; ajusta según tu entorno
    $user = "ADMINISTRADOR";  // Usuario de conexión
    $pass = "123";       // Contraseña

    // DSN para PDO con Oracle (OCI)
    $dsn = "oci:dbname=//$host:$port/$sid;charset=AL32UTF8";

    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];
    try {
         $pdo = new PDO($dsn, $user, $pass, $options);
         return $pdo;
    } catch (PDOException $e) {
         throw new PDOException($e->getMessage(), (int)$e->getCode());
    }
}