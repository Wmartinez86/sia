<?php

/* Requerimientos */
function get_requerimiento ($idreq) {
	global $bcdb;
	return $bcdb->get_row("SELECT * FROM $bcdb->requerimientos WHERE idreq = '$idreq'");
}

function get_requerimiento_by_user ($idreq, $iduser) {
	global $bcdb;
	return $bcdb->get_row("SELECT * FROM $bcdb->requerimientos WHERE idreq = '$idreq' AND createdby = '$iduser'");
}

function get_requerimientos () {
	global $bcdb, $bcrs, $pager;	
	$sql = "SELECT * 
			FROM $bcdb->requerimientos
			ORDER BY idreq DESC";
	$requerimientos = ($pager) ? $bcrs->get_results($sql) : $bcdb->get_results($sql);
	return $requerimientos;
}

function get_requerimientos_by_user ($userid) {
	global $bcdb, $bcrs, $pager;	
	$sql = "SELECT * 
			FROM $bcdb->requerimientos
                        WHERE createdby = '$userid'
			ORDER BY idreq DESC";
	$requerimientos = ($pager) ? $bcrs->get_results($sql) : $bcdb->get_results($sql);
	return $requerimientos;
}

function save_requerimiento($idreq, $req_values) {
	global $bcdb, $msg;

	if ($bcdb->get_var("SELECT count(codigo) 
                            FROM $bcdb->requerimientos 
                            WHERE idreq != '$idreq' 
                            AND codigo = '$req_values[codigo]' 
                            AND createdby = '$req_values[createdby]]'") ) {
            
		$msg .= " Ya existe otro requerimiento con el mismo c&oacute;digo.";
		return false;
	}
	
	$req_values['idreq'] = $idreq;

	if ( ($query = insert_update_query($bcdb->requerimientos, $req_values)) &&
		$bcdb->query($query) ) {
		
		if (empty($idreq))	
			$idreq = $bcdb->insert_id;
		
		$msg = "Los datos han sido guardados satisfactoriamente.";
		
		return $idreq;
	}else {
		
	}
	$msg .= " No se hicieron cambios en los datos de la Requerimiento.";
	return false;
}

function remove_requerimiento ($idreq) {
	global $bcdb;
	$bcdb->query("DELETE FROM $bcdb->requerimientos WHERE idreq = $idreq");
}

function fill_reqs($reqs) {
	global $bcdb;
	foreach($reqs as $k => $v) {
		$reqs[$k] = fill_req($reqs[$k]);
	}
	return $reqs;
}

function fill_req($req) {
	global $bcdb;
	$req['detalle'] = get_detalle_req($req['idreq']);
	$req['fecha'] = fechita2($req['fecha']);
	$req['usuario'] = get_user($req['createdby']);
	return $req;
}

/* DETALLE REQUERIMIENTO */
function save_detalle_req($idreq, $detalle_values) {
	global $bcdb, $msg;
	foreach($detalle_values as $k => $v) {
		$detalle_values[$k]['idreq'] = $idreq;
		$query = insert_update_query($bcdb->detallerequerimiento, $detalle_values[$k]);
		$bcdb->query($query);
		$msg = "Se guardaron los detalles del requerimiento.";
	}
}

function get_detalle_req($idreq) {
	global $bcdb;
	$detalle = $bcdb->get_results("SELECT * FROM $bcdb->detallerequerimiento WHERE idreq = '$idreq' ORDER BY iddetalle ASC");
	if($detalle) {
		return $detalle;
	}else
		return false;
}

function borrar_detalle_requerimiento($iddetalle, $idreq) {
	global $bcdb;
	return $bcdb->query("DELETE FROM $bcdb->detallerequerimiento WHERE idreq = '$idreq' AND iddetalle = '$iddetalle'");
}

?>