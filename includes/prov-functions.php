<?php

function get_prov ($idproveedor) {
	global $bcdb;
	return $bcdb->get_row("SELECT * FROM $bcdb->proveedores WHERE idproveedor = '$idproveedor'");
}

function get_name_prov ($idproveedor) {
	global $bcdb;
	return $bcdb->get_var("SELECT nombres FROM $bcdb->proveedores WHERE idproveedor = '$idproveedor'");
}

function get_prov_by_ruc ($ruc) {
	global $bcdb;
	return $bcdb->get_row("SELECT * FROM $bcdb->proveedores WHERE ruc = '$ruc'");
}

function get_idprov_by_ruc($ruc) {
	global $bcdb;
	return $bcdb->get_var("SELECT idproveedor FROM $bcdb->proveedores WHERE ruc = '$ruc'");
}

function get_provs () {
	global $bcdb, $bcrs, $pager;
	
	$sql = "SELECT * 
			FROM $bcdb->proveedores 
			ORDER BY idproveedor";
	$proveedores = ($pager) ? $bcrs->get_results($sql) : $bcdb->get_results($sql);
	return $proveedores;
}

function save_prov($idproveedor, $prov_values) {
	global $bcdb, $msg;

	if ($bcdb->get_var("SELECT count(ruc) FROM $bcdb->proveedores WHERE idproveedor != '$idproveedor' AND ruc = '$prov_values[ruc]'") ) {
		$msg = "Ya existe otro proveedor con el mismo RUC.";
		return false;
	}
	
		$prov_values['idproveedor'] = $idproveedor;
	
	if ( ($query = insert_update_query($bcdb->proveedores, $prov_values)) &&
		$bcdb->query($query) ) {
		if (empty($idproveedor))	
			$idproveedor = $bcdb->insert_id;
		
		$msg = "Los datos han sido guardados satisfactoriamente.";
		
		return $idproveedor;
	}
	$msg = "Hubo un problema al intentar guardar el proveedor.";
	return false;
}

function remove_prov ($idproveedor) {
	global $bcdb;
	$bcdb->query("DELETE FROM $bcdb->proveedores WHERE idproveedor = $idproveedor");
}

?>