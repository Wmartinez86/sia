<?php

function get_fuente ($idfuente) {
	global $bcdb;
	return $bcdb->get_row("SELECT * FROM $bcdb->fuentes WHERE idfuente = '$idfuente'");
}

function get_fuentes () {
	global $bcdb, $bcrs, $pager;
	
	$sql = "SELECT * 
			FROM $bcdb->fuentes 
			ORDER BY idfuente";
	$fuentes = ($pager) ? $bcrs->get_results($sql) : $bcdb->get_results($sql);
	return $fuentes;
}

function save_fuente($idfuente, $fuente_values) {
	global $bcdb, $msg;

	if ($bcdb->get_var("SELECT count(nombre) FROM $bcdb->fuentes WHERE idfuente != '$idfuente' AND nombre = '$fuente_values[nombre]'") ) {
		$msg = "Ya existe la fuente '$fuente_values[nombre]'.";
		return false;
	}
	
	$fuente_values['idfuente'] = $idfuente;
	
	if ( ($query = insert_update_query($bcdb->fuentes, $fuente_values)) &&
		$bcdb->query($query) ) {
		if (empty($idfuente))	
			$idfuente = $bcdb->insert_id;
		
		$msg = "Los datos han sido guardados satisfactoriamente.";
		
		return $idfuente;
	}
	$msg = "Hubo un problema al intentar guardar la fuente.";
	return false;
}

function remove_fuente ($idfuente) {
	global $bcdb;
	$bcdb->query("DELETE FROM $bcdb->fuentes WHERE idfuente = $idfuente");
}

?>