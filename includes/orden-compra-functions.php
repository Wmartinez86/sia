<?php

/* Ordenes Compra */
function get_orden_compra ($idorden) {
	global $bcdb;
	return $bcdb->get_row("SELECT * FROM $bcdb->ordencompra WHERE idorden = '$idorden'");
}

function get_ordenes_compra () {
	global $bcdb, $bcrs, $pager;
	
	$sql = "SELECT * 
			FROM $bcdb->ordencompra 
			ORDER BY idorden DESC";
	$ordencompras = ($pager) ? $bcrs->get_results($sql) : $bcdb->get_results($sql);
	return $ordencompras;
}

function save_orden_compra($idorden, $orden_values) {
	global $bcdb, $msg;

	if ($bcdb->get_var("SELECT count(codigo) FROM $bcdb->ordencompra WHERE idorden != '$idorden' AND codigo = '$orden_values[codigo]'") ) {
		$msg .= " Ya existe otra orden con el mismo c&oacute;digo.";
		return false;
	}
	
		$orden_values['idorden'] = $idorden;
	
	if ( ($query = insert_update_query($bcdb->ordencompra, $orden_values)) &&
		$bcdb->query($query) ) {
		
		if (empty($idorden))	
			$idorden = $bcdb->insert_id;
		
		$msg = "Los datos han sido guardados satisfactoriamente.";
		
		return $idorden;
	}else {
		
	}
	$msg .= " No se hicieron cambios en los datos de la orden.";
	return false;
}

function remove_orden_compra ($idorden) {
	global $bcdb;
	$bcdb->query("DELETE FROM $bcdb->ordencompra WHERE idorden = $idorden");
}

function fill_compras($ordenes) {
	foreach($ordenes as $k => $v) {
		$ordenes[$k] = fill_compra($ordenes[$k]);
	}
	return $ordenes;
}

function fill_compra($orden) {
	$orden['doc'] = get_doc($orden['iddoc']);
	$orden['proyecto'] = get_proj($orden['idproyecto']);
	$orden['proveedor'] = get_prov($orden['idproveedor']);
//	$orden['rubro'] = $atipos[$orden['proyecto']['idtipo']];
	$orden['fuente'] = get_fuente($orden['idfuente']);
//	$orden['pecosa'] = get_pecosa($orden['idorden']);
	$orden['detalle'] = get_detalle_compra($orden['idorden']);
	$orden['fecha'] = fechita2($orden['fecha']);
	$orden['usuario'] = get_user($orden['createdby']);
	return $orden;
}

/* PECOSA */
/* Se eliminaron funciones de PECOSA antiguo 27 Enero 2012*/

/* DETALLE ORDEN COMPRA */

function save_detalle_compra($idorden, $detalle_values) {
	global $bcdb, $msg;
	foreach($detalle_values as $k => $v) {
		$detalle_values[$k]['idorden'] = $idorden;
		$query = insert_update_query($bcdb->detalleordencompra, $detalle_values[$k]);
		$bcdb->query($query);
		$msg = "Se guardaron los detalles de la orden.";
	}
}

function get_detalle_compra($idorden) {
	global $bcdb;
	$detalle = $bcdb->get_results("SELECT * FROM $bcdb->detalleordencompra WHERE idorden = '$idorden'");
	if($detalle) {
		foreach($detalle as $k => $v) {
			$detalle[$k]['total'] = $detalle[$k]['cantidad']*$detalle[$k]['precio'];
		}
		return $detalle;
	}else
		return false;
}

function borrar_detalle_compra($iddetalle, $idorden) {
	global $bcdb;
	return $bcdb->query("DELETE FROM $bcdb->detalleordencompra WHERE idorden = '$idorden' AND iddetalle = '$iddetalle'");
}


?>