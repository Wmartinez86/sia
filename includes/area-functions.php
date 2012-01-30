<?php

function get_area ($idarea) {
	global $bcdb;
	return $bcdb->get_row("SELECT * FROM $bcdb->areas WHERE idarea = '$idarea'");
}

function get_name_area ($idarea) {
	global $bcdb;
	return $bcdb->get_var("SELECT nombre FROM $bcdb->areas WHERE idarea = '$idarea'");
}

function get_areas () {
	global $bcdb, $bcrs, $pager;
	
	$sql = "SELECT * 
			FROM $bcdb->areas 
			ORDER BY idarea";
	$areas = ($pager) ? $bcrs->get_results($sql) : $bcdb->get_results($sql);
	return $areas;
}

function save_area($idarea, $area_values) {
	global $bcdb, $msg;

	if ($bcdb->get_var("SELECT count(abreviatura) FROM $bcdb->areas WHERE idarea != '$idarea' AND abreviatura = '$area_values[abreviatura]'") ) {
		$msg = "Ya existe otro proveedor con la misma abreviatura.";
		return false;
	}
	
		$area_values['idarea'] = $idarea;
	
	if ( ($query = insert_update_query($bcdb->areas, $area_values)) &&
		$bcdb->query($query) ) {
		if (empty($idarea))	
			$idarea = $bcdb->insert_id;
		
		$msg = "Los datos han sido guardados satisfactoriamente.";
		
		return $idarea;
	}
	$msg = "Hubo un problema al intentar guardar el área.";
	return false;
}

function remove_area ($idarea) {
	global $bcdb;
	$bcdb->query("DELETE FROM $bcdb->areas WHERE idarea = $idarea");
}

?>