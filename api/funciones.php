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
	$numeroMesas = obtenerInformacionLocal()[0]->numeroMesas;
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

function cambiarPassword($idUsuario, $password) {
    $bd = conectarBaseDatos();
    // Generamos el hash de la contraseña
    $passwordCod = password_hash($password, PASSWORD_DEFAULT);
    
    // Preparamos un bloque PL/SQL que llama al procedimiento
    $sql = "BEGIN FIDE_USUARIO_CAMBIAR_PASSWORD_SP(:p_id_usuario, :p_password); END;";
    $stmt = $bd->prepare($sql);
    
    // Vinculamos los parámetros de entrada
    $stmt->bindParam(':p_id_usuario', $idUsuario, PDO::PARAM_INT);
    $stmt->bindParam(':p_password', $passwordCod, PDO::PARAM_STR);
    
    // Ejecutamos el bloque y devolvemos el resultado
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



function eliminarUsuario($idUsuario){
    $bd = conectarBaseDatos();
    $sql = "BEGIN FIDE_USUARIO_ELIMINAR_SP(:p_id); END;";
    $stmt = $bd->prepare($sql);
    $stmt->bindParam(":p_id", $idUsuario, PDO::PARAM_INT);
    return $stmt->execute();
}

function editarUsuario($usuario){
    $bd = conectarBaseDatos();
    $sql = "BEGIN FIDE_USUARIO_ACTUALIZAR_SP(:p_id_usuario, :p_nombre, :p_correo, :p_contrasena, :p_id_rol); END;";
    $stmt = $bd->prepare($sql);
    
    $stmt->bindParam(":p_id_usuario", $usuario->id, PDO::PARAM_INT);
    $stmt->bindParam(":p_nombre", $usuario->nombre, PDO::PARAM_STR);
    $stmt->bindParam(":p_correo", $usuario->correo, PDO::PARAM_STR);
    $stmt->bindParam(":p_contrasena", $usuario->contrasena, PDO::PARAM_STR);
    $stmt->bindParam(":p_id_rol", $usuario->id_rol, PDO::PARAM_INT);

    return $stmt->execute();
}

function obtenerUsuarioPorId($idUsuario) {
    $bd = conectarBaseDatos();
    $sql = "BEGIN FIDE_USUARIO_OBTENER_POR_ID_SP(:p_id, :p_resultado); END;";
    $stmt = $bd->prepare($sql);

    // Vincular el parámetro de entrada
    $stmt->bindParam(':p_id', $idUsuario, PDO::PARAM_INT);

    // Vincular el parámetro de salida como un cursor
    $stmt->bindParam(':p_resultado', $cursor, PDO::PARAM_STMT);

    // Ejecutar la sentencia
    $stmt->execute();

    // Obtener el conjunto de resultados del cursor
    $cursor->execute();
    $usuario = $cursor->fetch(PDO::FETCH_OBJ);

    return $usuario;
}

function obtenerUsuarios() {
    $bd = conectarBaseDatos();
    $sql = "BEGIN FIDE_USUARIO_OBTENER_TODOS_SP(:p_resultado); END;";
    $stmt = $bd->prepare($sql);

    $stmt->bindParam(':p_resultado', $cursor, PDO::PARAM_STMT);
    $stmt->execute();
    $cursor->execute();

    return $cursor->fetchAll(PDO::FETCH_OBJ);
}


function registrarUsuario($usuario) {
    $bd = conectarBaseDatos();
    $sql = "BEGIN FIDE_USUARIO_INSERTAR_SP(:p_id_usuario, :p_nombre, :p_correo, :p_contrasena, :p_id_rol); END;";
    $stmt = $bd->prepare($sql);

    $stmt->bindParam(':p_id_usuario', $usuario->id, PDO::PARAM_INT);
    $stmt->bindParam(':p_nombre', $usuario->nombre, PDO::PARAM_STR);
    $stmt->bindParam(':p_correo', $usuario->correo, PDO::PARAM_STR);
    $stmt->bindParam(':p_contrasena', $usuario->password, PDO::PARAM_STR);
    $stmt->bindParam(':p_id_rol', $usuario->id_rol, PDO::PARAM_INT);

    return $stmt->execute();
}

function obtenerInsumosPorNombre($insumo){
	$bd = conectarBaseDatos();
	$sentencia = $bd->prepare("SELECT insumos.*, IFNULL(categorias.nombre, 'NO DEFINIDA') AS categoria
	FROM insumos
	LEFT JOIN categorias ON categorias.id = insumos.categoria 
	WHERE insumos.nombre LIKE ? ");
	$sentencia->execute(['%'.$insumo.'%']);
	return $sentencia->fetchAll();
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

function eliminarInsumo($idInsumo){
	$bd = conectarBaseDatos();
    $sentencia = $bd->prepare("DELETE FROM insumos WHERE id = ?");
	return $sentencia->execute([$idInsumo]);
}

function editarInsumo($insumo){
	$bd = conectarBaseDatos();
	$sentencia = $bd->prepare("UPDATE insumos SET tipo = ?, codigo = ?, nombre = ?, descripcion = ?, categoria = ?, precio = ? WHERE id = ?");
	return $sentencia->execute([$insumo->tipo, $insumo->codigo, $insumo->nombre, $insumo->descripcion,$insumo->categoria, $insumo->precio, $insumo->id]);	
}

function obtenerInsumoPorId($idInsumo){
	$bd = conectarBaseDatos();
	$sentencia = $bd->prepare("SELECT * FROM insumos WHERE id = ?");
	$sentencia->execute([$idInsumo]);
	return $sentencia->fetchObject();
}

function obtenerInsumos($filtros){
	$bd = conectarBaseDatos();
	$valoresAEjecutar = [];
	$sql = "SELECT insumos.*, IFNULL(categorias.nombre, 'NO DEFINIDA') AS categoria
	FROM insumos
	LEFT JOIN categorias ON categorias.id = insumos.categoria WHERE 1 ";

	if($filtros->tipo != "") {
		$sql .= " AND  insumos.tipo = ?";
		array_push($valoresAEjecutar, $filtros->tipo);
	}

	if($filtros->categoria != "") {
		$sql .= " AND  insumos.categoria = ?";
		array_push($valoresAEjecutar, $filtros->categoria);
	}

	if($filtros->nombre != "") {
		$sql .= " AND  insumos.nombre LIKE ? OR insumos.codigo LIKE ?";
		array_push($valoresAEjecutar, '%'.$filtros->nombre.'%');
		array_push($valoresAEjecutar, '%'.$filtros->nombre.'%');
	}

	$sentencia = $bd->prepare($sql);
	$sentencia->execute($valoresAEjecutar);
	return $sentencia->fetchAll();
}

function registrarInsumo($insumo){
	$bd = conectarBaseDatos();
	$sentencia = $bd->prepare("INSERT INTO insumos (codigo, nombre, descripcion, precio, tipo,  categoria) VALUES (?,?,?,?,?,?)");
	return $sentencia->execute([$insumo->codigo, $insumo->nombre, $insumo->descripcion,$insumo->precio, $insumo->tipo, $insumo->categoria]);
}

function obtenerCategoriasPorTipo($tipo){
	$bd = conectarBaseDatos();
	$sentencia = $bd->prepare("SELECT * FROM categorias WHERE tipo = ?");
	$sentencia->execute([$tipo]);
	return $sentencia->fetchAll();
}


function eliminarCategoria($idCategoria) {
    $bd = conectarBaseDatos();
    $sentencia = $bd->prepare("DELETE FROM categorias WHERE id = ?");
	return $sentencia->execute([$idCategoria]);
}

function editarCategoria($categoria) {
    $bd = conectarBaseDatos();
    $sentencia = $bd->prepare("UPDATE categorias SET tipo = ?, nombre = ?, descripcion = ? WHERE id = ?");
	return $sentencia->execute([$categoria->tipo, $categoria->nombre, $categoria->descripcion, $categoria->id]);
}

function registrarCategoria($categoria){
	$bd = conectarBaseDatos();
	$sentencia = $bd->prepare("INSERT INTO categorias (tipo, nombre, descripcion) VALUES (?,?,?)");
	return $sentencia->execute([$categoria->tipo, $categoria->nombre, $categoria->descripcion]);
}

function obtenerCategorias(){
	$bd = conectarBaseDatos();
	$sentencia = $bd->query("SELECT * FROM categorias ORDER BY id DESC");
	return $sentencia->fetchAll();
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