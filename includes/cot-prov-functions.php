<?php

/* Cotizacion */
function get_cotizacion ($idcot) {
	global $bcdb;
	return $bcdb->get_row("SELECT * FROM $bcdb->cotizacion WHERE idcot = '$idcot'");
}

function get_cotizaciones () {
	global $bcdb, $bcrs, $pager;	
	$sql = "SELECT * 
			FROM $bcdb->cotizacion
			ORDER BY idcot DESC";
	$cotizaciones = ($pager) ? $bcrs->get_results($sql) : $bcdb->get_results($sql);
	return $cotizaciones;
}

function save_cotizacion($idcot, $cot_values) {
	global $bcdb, $msg;

	if ($bcdb->get_var("SELECT count(codigo) FROM $bcdb->cotizacion WHERE idcot != '$idcot' AND codigo = '$cot_values[codigo]'") ) {
		$msg .= " Ya existe otra Cotizacion con el mismo c&oacute;digo.";
		return false;
	}
	
		$cot_values['idcot'] = $idcot;
	
	if ( ($query = insert_update_query($bcdb->cotizacion, $cot_values)) &&
		$bcdb->query($query) ) {
		
		if (empty($idcot))	
			$idcot = $bcdb->insert_id;
		
		$msg = "Los datos han sido guardados satisfactoriamente.";
		
		return $idcot;
	}else {
		
	}
	$msg .= " No se hicieron cambios en los datos de la Cotizacion.";
	return false;
}

function remove_cotizacion ($idcot) {
	global $bcdb;
	$bcdb->query("DELETE FROM $bcdb->cotizacion WHERE idcot = $idcot");
}

function fill_cots($cots) {
	global $bcdb;
	foreach($cots as $k => $v) {
		$cots[$k] = fill_cot($cots[$k]);
	}
	return $cots;
}

function fill_cot($cot) {
	global $bcdb, $atipos;
	$cot['detalle'] = get_detalle_cot($cot['idcot']);
	$cot['fecha'] = fechita2($cot['fecha']);
	$cot['usuario'] = get_user($cot['createdby']);
	return $cot;
}

/* DETALLE COTIZACION */

function save_detalle_cot($idcot, $detalle_values) {
	global $bcdb, $msg;
	foreach($detalle_values as $k => $v) {
		$detalle_values[$k]['idcot'] = $idcot;
		$query = insert_update_query($bcdb->detallecotizacion, $detalle_values[$k]);
		$bcdb->query($query);
		$msg = "Se guardaron los detalles de la Cotizacion.";
	}
}

function get_detalle_cot($idcot) {
	global $bcdb;
	$detalle = $bcdb->get_results("SELECT * FROM $bcdb->detallecotizacion WHERE idcot = '$idcot' ORDER BY `o_detallecotizacion`.`iddetalle` ASC");
	if($detalle) {
//		foreach($detalle as $k => $v) {
			//$detalle[$k]['especifica'] = get_especifica($detalle[$k]['idespec']);
//			$detalle[$k]['total'] = $detalle[$k]['cantidad']*$detalle[$k]['precio'];
//		}
		return $detalle;
	}else
		return false;
}

function borrar_detalle_cot($iddetalle, $idcot) {
	global $bcdb;
	return $bcdb->query("DELETE FROM $bcdb->detallecotizacion WHERE idcot = '$idcot' AND iddetalle = '$iddetalle'");
}
//prove cotizacion
function save_prov_cot($idcot, $prov_values) {
	global $bcdb, $msg;

	$prov_values['idcot'] = $idcot;
	
	if ( ($query = insert_update_query($bcdb->o_provcotizacion, $prov_values)) &&
		$bcdb->query($query) ) {
		$msg .= " Se guardaron los cambios de el proveedor.";
		return true;
	}	
	$msg .= " No hubo cambios en el proveedor.";
	return false;
}
//precio Cotizacion

function save_prov_precio($idprov, $detalle_values) {
	global $bcdb, $msg;
	foreach($detalle_values as $k => $v) {
		$detalle_values[$k]['idprov'] = $idprov;
		$query = insert_update_query($bcdb->preciocotizacion, $detalle_values[$k]);
		$bcdb->query($query);
		$msg = "Se guardaron los precios del proveedor.";
	}
}


?>