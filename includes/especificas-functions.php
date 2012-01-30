<?php

function get_especifica ($idespec) {
	global $bcdb;
	return $bcdb->get_row("SELECT * FROM $bcdb->especificas WHERE idespec = '$idespec'");
}

function get_especifica_by_code ($codigo) {
	global $bcdb;
	return $bcdb->get_row("SELECT * FROM $bcdb->especificas WHERE codigo = '$codigo'");
}

function get_especificas () {
	global $bcdb, $bcrs, $pager;
	
	$sql = "SELECT * 
			FROM $bcdb->especificas 
			ORDER BY idespec";
	$especificas = ($pager) ? $bcrs->get_results($sql) : $bcdb->get_results($sql);
	return $especificas;
}

function save_especifica($idespec, $especifica_values) {
	global $bcdb, $msg;

	if ($bcdb->get_var("SELECT count(codigo) FROM $bcdb->especificas WHERE idespec != '$idespec' AND codigo = '$especifica_values[codigo]'") ) {
		$msg = "Ya existe la especifica con el mismo c&oacute;digo.";
		return false;
	}
	
	$especifica_values['idespec'] = $idespec;
	if ( ($query = insert_update_query($bcdb->especificas, $especifica_values)) &&
		$bcdb->query($query) ) {
		if (empty($idespec))	
			$idespec = $bcdb->insert_id;
		
		$msg = "Los datos han sido guardados satisfactoriamente.";
		
		return $idespec;
	}
	$msg = "Hubo un problema al intentar guardar la especifica.";
	return false;
}

function remove_especifica ($idespec) {
	global $bcdb;
	$bcdb->query("DELETE FROM $bcdb->especificas WHERE idespec = $idespec");
}

?>