<?php

/* Neas */
function get_nea ($idnea) {
	global $bcdb;
	return $bcdb->get_row("SELECT * FROM $bcdb->neas WHERE idnea = '$idnea'");
}

function get_neas () {
	global $bcdb, $bcrs, $pager;
	$sql = "SELECT * 
			FROM $bcdb->neas 
			ORDER BY idnea DESC";
	$neas = ($pager) ? $bcrs->get_results($sql) : $bcdb->get_results($sql);
	return $neas;
}

function save_nea ($idnea, $nea_values) {
	global $bcdb, $msg;

	if ($bcdb->get_var("SELECT count(codigo) FROM $bcdb->neas WHERE idnea != '$idnea' AND codigo = '$nea_values[codigo]'") ) {
		$msg .= " Ya existe otra NEA con el mismo c&oacute;digo.";
		return false;
	}
	
	$nea_values['idnea'] = $idnea;
	
	if ( ($query = insert_update_query($bcdb->neas, $nea_values)) &&
		$bcdb->query($query) ) {
		
		if (empty($idnea))	
			$idnea = $bcdb->insert_id;
		
		$msg = "Los datos han sido guardados satisfactoriamente.";
		
		return $idnea;
	}else {
		
	}
	$msg .= " No se hicieron cambios en los datos de la NEA.";
	return false;
}

/**
 * Guarda detalles de la entrada y el almacen
 * 
 * @param type $idnea La entrada a la que pertenecen los detalles
 * @param type $detalle_values Los detalles
 */
function save_detalle_nea($idnea, $detalle_values) {
	global $bcdb, $msg;
	foreach($detalle_values as $k => $v) {
            
                // Ingresamos al detalle de la Entrada
		$detalle_values[$k]['idnea'] = $idnea;
		$query = insert_update_query($bcdb->detallenea, $detalle_values[$k]);
		$bcdb->query($query);
                
                // Ingresamos al Almacen
                $nea = get_nea($idnea);
                $idennea = $bcdb->insert_id;
                
                $almacen_values[$k]['idennea'] = $idennea;
                $almacen_values[$k]['idorden'] = $nea['idorden'];
                $almacen_values[$k]['cantidad'] = $detalle_values[$k]['cantidad'];
                
                $query = insert_update_query($bcdb->detallealmacen, $almacen_values[$k]);
                $bcdb->query($query);
                
	}
        $msg = "Se guardaron los detalles de la NEA.";
}

function cancelar_nea ($idnea) {
	global $bcdb;

	$nea = get_nea($idnea);

	$nsql = sprintf("SELECT * FROM %s WHERE idnea = '%s';",
	$bcdb->detallenea, $idnea);

	$results = $bcdb->get_results($nsql);
	
	foreach($results as $k => $v) {
		$dsql = sprintf("DELETE FROM %s WHERE idennea = '%s';",
			$bcdb->detallealmacen, $v['iddetalle']);
		$bcdb->query($dsql);
	}

	$bcdb->query(sprintf("DELETE FROM %s WHERE idnea = '%s';",
			$bcdb->detallenea, $idnea));

	$bcdb->query(sprintf("DELETE FROM %s WHERE idnea = '%s';",
			$bcdb->neas, $idnea));

	unfreeze_orden($nea['idorden']);
}

function fill_neas($neas) {
	foreach($neas as $k => $v) {
		$neas[$k] = fill_nea($neas[$k]);
	}
	return $neas;
}

function fill_nea($nea) {
	$nea['proyecto'] = get_proj($nea['destino']);
	$nea['detalle'] = get_detalle_nea($nea['idnea']);
	$nea['fecha'] = fechita2($nea['fecha']);
	$nea['usuario'] = get_user($nea['createdby']);
	$nea['hasalido'] = en_pecosa($nea['idnea']);
	return $nea;
}

function en_pecosa($idnea) {
	global $bcdb;
	$sql = sprintf("SELECT iddetalle  FROM %s WHERE idnea = '%s';",
	$bcdb->detallenea, $idnea);
	$suma = 0;
	$results = $bcdb->get_results($sql);
	foreach ($results as $k => $v) {
		$vsql = sprintf("SELECT SUM(cuantosalio) as suma FROM %s WHERE idennea = '%s';",
			$bcdb->detallealmacen, $v['iddetalle']);
		$suma += $bcdb->get_var($vsql);
	}
	return ($suma>0);
} 

function fill_nea_by_orden($orden) {
	$orden['detalle'] = get_detalle_compra($orden['idorden']);
 	unset($orden['fecha']);
        unset($orden['codigo']);
	return $orden;
}

function get_detalle_nea($idnea) {
	global $bcdb;
	$detalle = $bcdb->get_results("SELECT * FROM $bcdb->detallenea WHERE idnea = '$idnea'");
	if($detalle) {
		foreach($detalle as $k => $v) {
			$detalle[$k]['total'] = $detalle[$k]['cantidad']*$detalle[$k]['precio'];
		}
		return $detalle;
	}else
		return false;
}

function borrar_detalle_nea($iddetalle, $idnea) {
	global $bcdb;
	return $bcdb->query("DELETE FROM $bcdb->detallenea WHERE idnea = '$idnea' AND iddetalle = '$iddetalle'");
}


?>
