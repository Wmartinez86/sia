<?php

/* PECOSAS */
function get_pecosa ($idpecosa) {
	global $bcdb;
	return $bcdb->get_row("SELECT * FROM $bcdb->pecosa WHERE idpecosa = '$idpecosa'");
}

function get_pecosas () {
	global $bcdb, $bcrs, $pager;
	$sql = "SELECT * 
			FROM $bcdb->pecosa 
			ORDER BY idpecosa DESC";
	$neas = ($pager) ? $bcrs->get_results($sql) : $bcdb->get_results($sql);
	return $neas;
}

function save_pecosa ($idpecosa, $pecosa_values) {
	global $bcdb, $msg;

	if ($bcdb->get_var("SELECT count(codigo) FROM $bcdb->pecosa WHERE idpecosa != '$idpecosa' AND codigo = '$pecosa_values[codigo]'") ) {
		$msg .= " Ya existe otra PECOSA con el mismo c&oacute;digo.";
		return false;
	}
	
	$pecosa_values['idpecosa'] = $idpecosa;
	
	if ( ($query = insert_update_query($bcdb->pecosa, $pecosa_values)) &&
		$bcdb->query($query) ) {
		
		if (empty($idpecosa))	
			$idpecosa = $bcdb->insert_id;
		
		$msg = "Los datos han sido guardados satisfactoriamente.";
		
		return $idpecosa;
	}else {
		
	}
	$msg .= " No se hicieron cambios en los datos de la PECOSA.";
	return false;
}

function save_detalle_pecosa($idpecosa, $detalle_values) {
	global $bcdb, $msg;
	foreach($detalle_values as $k => $v) {
		$detalle_values[$k]['idpecosa'] = $idpecosa;
		$query = insert_update_query($bcdb->detallepecosa, $detalle_values[$k]);
		$bcdb->query($query);
		$msg = "Se guardaron los detalles de la PECOSA.";
	}
}

function remove_pecosa ($idpecosa) {
	global $bcdb;
	$bcdb->query("DELETE FROM $bcdb->pecosa WHERE idpecosa = $idpecosa");
}

function fill_pecosas($neas) {
	foreach($neas as $k => $v) {
		$neas[$k] = fill_pecosa($neas[$k]);
	}
	return $neas;
}

function fill_pecosa($nea) {
	$nea['detalle'] = get_detalle_pecosa($nea['idpecosa']);
	$nea['fecha'] = fechita2($nea['fecha']);
	$nea['usuario'] = get_user($nea['createdby']);
	return $nea;
}

function fill_pecosa_by_orden($orden) {
	$orden['detalle'] = get_detalle_compra($orden['idorden']);
	//$orden['detalle'] = get_precios2($orden['detalle'], get_ganador_cuadro($orden['idcot']));
 	unset($orden['fecha']);
        unset($orden['codigo']);
	return $orden;
}

function get_detalle_pecosa($idpecosa) {
	global $bcdb;
	$detalle = $bcdb->get_results("SELECT * FROM $bcdb->detallepecosa WHERE idpecosa = '$idpecosa'");
	if($detalle) {
		foreach($detalle as $k => $v) {
			$detalle[$k]['total'] = $detalle[$k]['cantidad']*$detalle[$k]['precio'];
		}
		return $detalle;
	}else
		return false;
}

function borrar_detalle_pecosa($iddetalle, $idpecosa) {
	global $bcdb;
	return $bcdb->query("DELETE FROM $bcdb->detallepecosa WHERE idpecosa = '$idpecosa' AND iddetalle = '$iddetalle'");
}


?>