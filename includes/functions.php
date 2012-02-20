<?php

function send_headers($mime = "text/html") {
	static $sent = false;
	if ( headers_sent() || $sent) return;
	
	$sent = true;
	session_start();

	header("Content-Type: $mime; charset=" . CHARSET);
	header("Vary: Accept");
}

function site_url() {
	$site_schema = ( isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) == 'on' ) ? 'https://' : 'http://';
	$site_server =  $_SERVER['HTTP_HOST'] . ( $_SERVER['SERVER_PORT'] == 80 ? '' : ':' . $_SERVER['SERVER_PORT'] );

	return $site_schema . $site_server . site_path();
}

function site_path() {
	static $site_path;
	
	if ( empty($site_path) ) {
		$site_path = str_replace(
			str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']), '', 
			str_replace('\\', '/', BASE_PATH)
		);
	}
	return $site_path;
}

function safe_redirect($url) {
	$url = strip_tags(preg_replace('#[\r\n]#', '', $url));
	header('Location: ' . $url);
	exit();
}

function insert_query ($table, $params) {
	if ( ($insert = process_params_for_insert($params)) !== false )	{
		$query = @sprintf('INSERT INTO %s(%s) VALUES (%s)', $table, $insert['fields'], $insert['values']);
		
		if (!empty($query))
			return $query;
	}
	return false;
}

function update_query($table, $params, $condition) {
	if ( ($update = process_params_for_update($params)) !== false )	{
		$query_fmt = 'UPDATE %s SET %s';
		if (!empty($condition))
			$query_fmt .= ' WHERE %s';
		elseif ($condition !== false)		
			die("No hay una condici�n para hacer el update");
			
		$query = @sprintf($query_fmt, $table, $update, $condition);
		
		if (!empty($query))
			return $query;
	}
	return false;
}

function insert_update_query ($table, $params) {
	$insert = process_params_for_insert($params);
	$update = process_params_for_update($params);
	
	if (!empty($insert) && !empty($update)) {
		$query_fmt = 'INSERT INTO %s (%s) VALUES (%s) ON DUPLICATE KEY UPDATE %s';
		
		$query = sprintf(
			$query_fmt, 
			$table, 
			$insert['fields'],
			$insert['values'],
			$update
			);
		
		if (!empty($query))
			return $query;
	}
	return false;
}

function process_params_for_insert($array) {
	if (!is_array($array)) return false;

	$returnData['fields'] = implode(',', array_keys($array));
	foreach ($array as $k => $v) {
		if (is_array($v)) return false;

		$array[$k] = ($v == 'now()') ? $v : "'" . trim($v) . "'";
	}
	$returnData['values'] = implode(',', array_values($array));

	return $returnData;
}

function process_params_for_update($array) {
	if (!is_array($array)) return false;

	foreach ($array as $k => $v) {
		if (is_array($v)) return false;
		$array[$k] = "$k = " . ( ($v == 'now()') ? $v : "'" . trim($v) . "'" );
	}

	return implode(',', array_values($array));
}

function process_params_for_search($array) {
	if (!is_array($array)) return false;

	foreach ($array as $k => $v) {
		if (is_array($v)) return false;
		$array[$k] = is_numeric($v) ? "$k = $v" : "$k like '%$v%'";
	}

	return implode(' AND ', array_values($array));
}

function validate_required($values) {
	global $msg;
	$errors = false;
	$msg = '<ul>';
	foreach ($values as $k => $val) {
		if (empty($val)) {
			$msg .= '<li>' . sprintf('%s es requerido', $k) . "</li>\n";
			$errors = true;
		}
	}
	$msg .= '</ul>';
	if (!$errors)
		$msg = null;
	return ! $errors;
}

function fechita($fecha) {
	$f = split("/", $fecha);
	return $f[2] . "-" . $f[1] . "-" . $f[0];
}


function fechita2($fecha) {
	$f = split("-", $fecha);
	return $f[2] . "/" . $f[1] . "/" . $f[0];
}

function fechita3($fecha) {
	$f = split("-", $fecha);
	$mes=' ';
	switch ($f[1]) {
		case 1:
			$mes = "Enero";
			break;
		case 2:
			$mes = "Febrero";
			break;
		case 3:
			$mes = "Marzo";
			break;
		case 4:
			$mes ="Abril";
			break;
		case 5:
			$mes ="Mayo";
			break;
		case 6:
			$mes ="Junio";
			break;
		case 7:
			$mes ="Julio";
			break;
		case 8:
			$mes ="Agosto";
		break;
		case 9:
			$mes ="Setiembre";
		break;
		case 10:
			$mes ="Octubre";
		break;
		case 11:
			$mes ="Noviembre";
		break;
		case 12:
			$mes ="Diciembre";
		break;
	}	
	return $f[2] . " de " . $mes . " del " . $f[0];
}

function generate_code($table, $userid = NULL) {
	global $bcdb;
        if(is_null($userid))
            $codes = $bcdb->get_col("SELECT codigo FROM $table ORDER BY codigo DESC");
        else
            $codes = $bcdb->get_col("SELECT codigo FROM $table WHERE createdby = '$userid' ORDER BY codigo DESC");
	if($codes) :
		foreach($codes as $k => $v) {
			$codes[$k] = (int)$v;
	 	}
		sort($codes);
		$codes = array_reverse($codes);
		return $codes[0]+1;
	else :
		return 1;
	endif;
}

function search_ordenes($params, $table, $f1, $f2) {
	global $bcdb;
	$query = "SELECT * FROM $table WHERE ";
	foreach($params as $k => $v) {
		if($v) {
			$query .= " $k = '$v' AND ";
		}
	}
	$query .= "fecha BETWEEN '$f1' AND '$f2'";
	return $bcdb->get_results($query);
}

/**
 * Trae ordenes por código
 * 
 * @param int $id el código 
 * @param string $tipo el tipo de orden
 * @return array los resultados
 */
function get_ordenes_by_codigo($codigo, $tipo) {
    global $bcdb;
    $tabla = ($tipo == 'compra') ? $bcdb->ordencompra : $bcdb->ordenservicio ;
    $sql = "SELECT * FROM $tabla
                        WHERE codigo LIKE '%$codigo%' WHERE status = 1";
    
    $results = $bcdb->get_results($sql);
    return $results;
}

/**
 * Trae ordenes por proyecto
 * 
 * @param int $idproyecto el id del proyecto
 * @param string $tipo el tipo de orden
 * @param string $activos, solo debería mostrar activos
 * @return array los resultados
 * 
 */
function get_ordenes_by_project ($idproyecto, $tipo, $activos = NULL) {
	global $bcdb;	
        $tabla = ($tipo == 'compra') ? $bcdb->ordencompra : $bcdb->ordenservicio ;
	$sql = "SELECT * 
			FROM $tabla WHERE idproyecto = $idproyecto";
        $sql .= (!is_null($activos)) ? " AND status = '1' " : "";
        $sql .= " ORDER BY idorden DESC";
	$ordenes = $bcdb->get_results($sql);
	return $ordenes;
}

function supertotal($detalle, $field) {
	$super = 0;
	if($detalle) {
		foreach($detalle as $k => $v) {
			$super += $v[$field];
		}
	}
	return $super;

	
}

function cancel_order($idorden, $table) {
	global $bcdb;
	return $bcdb->query("UPDATE ". $bcdb->$table . " SET status = 2 WHERE idorden = $idorden");
}

function activate_order($idorden, $table) {
	global $bcdb;
	return $bcdb->query("UPDATE ". $bcdb->$table . " SET status = 1 WHERE idorden = $idorden");
}

//Cotizaciones Jonas
function cancel_cot($idcot, $table) {
	global $bcdb;
	$status = $bcdb->get_row("SELECT status FROM $bcdb->cotizacion WHERE idcot = $idcot");
	if($status!=3){	
		return $bcdb->query("UPDATE ". $bcdb->$table . " SET status = 2 WHERE idcot = $idcot");
	}else{
	return false;
	}
}

function activate_cot($idcot, $table) {
	global $bcdb;
	$status = $bcdb->get_row("SELECT status FROM $bcdb->cotizacion WHERE idcot = $idcot");
	if($status!=3){	
		return $bcdb->query("UPDATE ". $bcdb->$table . " SET status = 1 WHERE idcot = $idcot");
	}else{
	return false;
	}
}
function cancel_req($idreq, $table) {
	global $bcdb;
	$status = $bcdb->get_row("SELECT status FROM $bcdb->requerimientos WHERE idreq = $idreq");
	if($status!=3){	
		return $bcdb->query("UPDATE ". $bcdb->$table . " SET status = 2 WHERE idreq = $idreq");
	}else{
	return false;
	}
}

function activate_req($idreq, $table) {
	global $bcdb;
	$status = $bcdb->get_row("SELECT status FROM $bcdb->requerimientos WHERE idreq = $idreq");
	if($status!=3){	
		return $bcdb->query("UPDATE ". $bcdb->$table . " SET status = 1 WHERE idreq = $idreq");
	}else{
	return false;
	}
}

/* Opciones */
function get_options() {
    global $bcdb;
    
    $options = array();
    $results = $bcdb->get_results("SELECT * FROM $bcdb->opciones");
    
    foreach($results as $k => $v) {
        $options[$v['nombre']] = $v['descripcion'];
    }
    
    return $options;
}

function get_option($option) {
    global $bcdb;
    return $bcdb->get_var("SELECT descripcion FROM $bcdb->opciones WHERE nombre = '$option'");
}

function save_option($option, $description) {
    global $bcdb;
    return $bcdb->query("UPDATE $bcdb->opciones SET descripcion = '$description' WHERE nombre = '$option'");
}

/**
 *  ¿Quiéres hacer Debug?
 */
function d() {
    include_once(INCLUDE_PATH . "krumo/class.krumo.php");
    
    $_ = func_get_args();
    call_user_func_array(
        array('krumo', 'dump'), $_
    );
    die();
}

/**
 * Manda a la página de error 
 */
function error() {
    header("Location: error.php");
    exit();
}

?>