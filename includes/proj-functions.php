<?php

function get_proj ($idproyecto) {
	global $bcdb;
	return $bcdb->get_row("SELECT * FROM $bcdb->proyectos WHERE idproyecto = '$idproyecto'");
}

function get_name_proy ($idproyecto) {
	global $bcdb;
	return $bcdb->get_var("SELECT descripcion FROM $bcdb->proyectos WHERE idproyecto = '$idproyecto'");
}

function get_projs () {
	global $bcdb, $bcrs, $pager;
	$sql = "SELECT * 
			FROM $bcdb->proyectos 
			ORDER BY sec_func ASC";
	$proyectos = ($pager) ? $bcrs->get_results($sql) : $bcdb->get_results($sql);
	foreach($proyectos as $k => $v) {
            $proyectos[$k]['descripcion'] = "sec_func=" . $v['sec_func'] . " " . substr($v['descripcion'], 0, 90);
            $proyectos[$k]['cadena'] = cadena_funcional($v);
        }
	return $proyectos;
}

function cadena_funcional($proyecto) {
    return "$proyecto[sec_func].$proyecto[programa].$proyecto[prod_pry].$proyecto[act_ai_obra].$proyecto[funcion].$proyecto[division_func].$proyecto[grupo_func].$proyecto[meta].$proyecto[finalidad]";
}

function save_proj($idproyecto, $proj_values) {
	global $bcdb, $msg;

	if ($bcdb->get_var("SELECT count(descripcion) FROM $bcdb->proyectos WHERE idproyecto != '$idproyecto' AND descripcion = '$proj_values[descripcion]'") ) {
		$msg = "Ya existe otro proyecto con el mismo nombre.";
		return false;
	}
	
		$proj_values['idproyecto'] = $idproyecto;
	
	if ( ($query = insert_update_query($bcdb->proyectos, $proj_values)) &&
		$bcdb->query($query) ) {
		if (empty($idproyecto))	
			$idproyecto = $bcdb->insert_id;
		
		$msg = "Los datos han sido guardados satisfactoriamente.";
		
		return $idproyecto;
	}
	$msg = "Hubo un problema al intentar guardar el proyecto.";
	return false;
}

function remove_proj ($idproyecto) {
	global $bcdb;
	$bcdb->query("DELETE FROM $bcdb->proyectos WHERE idproyecto = $idproyecto");
}

?>
