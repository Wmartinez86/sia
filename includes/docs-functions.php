<?php

function get_doc ($iddoc) {
	global $bcdb;
	return $bcdb->get_row("SELECT * FROM $bcdb->documentos WHERE iddoc = '$iddoc'");
}

function get_name_doc ($iddoc) {
	global $bcdb;
	return $bcdb->get_var("SELECT nombre FROM $bcdb->documentos WHERE iddoc = '$iddoc'");
}

function get_docs () {
	global $bcdb, $bcrs, $pager;
	
	$sql = "SELECT * 
			FROM $bcdb->documentos 
			ORDER BY iddoc";
	$docs = ($pager) ? $bcrs->get_results($sql) : $bcdb->get_results($sql);
	return $docs;
}

function save_doc($iddoc, $doc_values) {
	global $bcdb, $msg;

	if ($bcdb->get_var("SELECT count(nombre) FROM $bcdb->documentos WHERE iddoc != '$iddoc' AND nombre = '$doc_values[nombre]'") ) {
		$msg = "Ya existe el tipo '$doc_values[nombre]'.";
		return false;
	}
	
	$doc_values['iddoc'] = $iddoc;
	
	if ( ($query = insert_update_query($bcdb->documentos, $doc_values)) &&
		$bcdb->query($query) ) {
		if (empty($iddoc))	
			$iddoc = $bcdb->insert_id;
		
		$msg = "Los datos han sido guardados satisfactoriamente.";
		
		return $iddoc;
	}
	$msg = "Hubo un problema al intentar guardar el tipo.";
	return false;
}

function remove_doc ($iddoc) {
	global $bcdb;
	$bcdb->query("DELETE FROM $bcdb->documentos WHERE iddoc = $iddoc");
}

?>