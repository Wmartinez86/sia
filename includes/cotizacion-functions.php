<?php

/* Cotizacion */
function get_cotizacion ($idcot) {
	global $bcdb;
	return $bcdb->get_row("SELECT * FROM $bcdb->cotizacion WHERE idcot = '$idcot'");
}

function get_cotizaciones () {
	global $bcdb, $bcrs, $pager;	
	$sql = "SELECT * 
			FROM $bcdb->cotizacion
			ORDER BY idcot DESC";
	$cotizaciones = ($pager) ? $bcrs->get_results($sql) : $bcdb->get_results($sql);
	return $cotizaciones;
}

/**
 * Trae cotizaciones por proyecto
 * 
 * @param $idproyecto el id del proyecto
 * @return array los resultados
 * 
 */
function get_cotizaciones_by_project ($idproyecto) {
	global $bcdb;	
	$sql = "SELECT * 
			FROM $bcdb->cotizacion c
                        INNER JOIN $bcdb->usuarios u
                        ON c.createdby = u.iduser
                        WHERE u.idproyecto = $idproyecto
			ORDER BY c.idcot DESC";
	$cotizaciones = $bcdb->get_results($sql);
	return $cotizaciones;
}

function save_cotizacion($idcot, $cot_values) {
	global $bcdb, $msg;

	if ($bcdb->get_var("SELECT count(codigo) FROM $bcdb->cotizacion WHERE idcot != '$idcot' AND codigo = '$cot_values[codigo]'") ) {
		$msg .= " Ya existe otra Cotizacion con el mismo c&oacute;digo.";
		return false;
	}
	
		$cot_values['idcot'] = $idcot;
	
	if ( ($query = insert_update_query($bcdb->cotizacion, $cot_values)) &&
		$bcdb->query($query) ) {
		
		if (empty($idcot))	
			$idcot = $bcdb->insert_id;
		
		$msg = "Los datos han sido guardados satisfactoriamente.";
		
		return $idcot;
	}else {
		
	}
	$msg .= " No se hicieron cambios en los datos de la Cotizacion.";
	return false;
}

function remove_cotizacion ($idcot) {
	global $bcdb;
	$bcdb->query("DELETE FROM $bcdb->cotizacion WHERE idcot = $idcot");
}

function fill_cots($cots) {
	global $bcdb;
	foreach($cots as $k => $v) {
		$cots[$k] = fill_cot($cots[$k]);
	}
	return $cots;
}

function fill_cot($cot) {
	global $bcdb, $atipos;
	$cot['detalle'] = get_detalle_cot($cot['idcot']);
	$cot['fecha'] = fechita2($cot['fecha']);
	$cot['usuario'] = get_user($cot['createdby']);
	return $cot;
}

/**
 * Busca cotizaciones
 * 
 * @param int $idproyecto el id del proyecto
 * @param int $iduser el id del usuario 
 * @return array los resultados
 */
function search_cotizacion($idproyecto, $iduser) {
    $results = array();
    
    if(!empty($idproyecto)) {
        $results = get_cotizaciones_by_project($idproyecto);
    } else {
        $results = get_cotizaciones();
    }
    
    if(!empty($iduser)) {
        $final = array();
        if($results) {
            foreach($results as $k => $r) {
                if($r['createdby'] == $iduser)
                    $final[] = $r;
            }
        }
        $results = $final;
    }
    
    return $results;
}

/**
 * Trae requerimientos por código
 * 
 * @param $id el código 
 * @return array los resultados
 */
function get_cotizaciones_by_codigo($codigo) {
    global $bcdb;
    $sql = "SELECT * FROM $bcdb->cotizacion
                        WHERE codigo LIKE '%$codigo%'";
    
    $results = $bcdb->get_results($sql);
    return $results;
}

/* DETALLE COTIZACION */

function save_detalle_cot($idcot, $detalle_values) {
	global $bcdb, $msg;
	foreach($detalle_values as $k => $v) {
		$detalle_values[$k]['idcot'] = $idcot;
		$query = insert_update_query($bcdb->detallecotizacion, $detalle_values[$k]);
		$bcdb->query($query);
		$msg = "Se guardaron los detalles de la Cotizacion.";
	}
}

function get_detalle_cot($idcot) {
	global $bcdb;
	$detalle = $bcdb->get_results("SELECT * FROM $bcdb->detallecotizacion WHERE idcot = '$idcot' ORDER BY `o_detallecotizacion`.`iddetalle` ASC");
	return ($detalle) ? $detalle : false;
}

/**
 * Borra detalle de una cotización
 * @param int $iddetalle El id del detalle que se va a borrar
 * @param int $idcot El id de la cotización a la que pertenece el detalle
 * @return boolean El resultado de la consulta
 */
function borrar_detalle_cotizacion($iddetalle, $idcot) {
	global $bcdb;
	return $bcdb->query("DELETE FROM $bcdb->detallecotizacion WHERE idcot = '$idcot' AND iddetalle = '$iddetalle'");
}
//prove cotizacion
function save_prov_cot($idcot, $prov_values) {
	global $bcdb, $msg;

	$prov_values['idcot'] = $idcot;
	
	if ( ($query = insert_update_query($bcdb->provcotizacion, $prov_values)) &&
		$bcdb->query($query) ) {
		$msg .= " Se guardaron los cambios de el proveedor.";
		return true;
	}	
	$msg .= " No hubo cambios en el proveedor.";
	return false;
}
//precio Cotizacion

function save_prov_precio($idprov, $detalle_values) {
	global $bcdb, $msg;
	foreach($detalle_values as $k => $v) {
		$detalle_values[$k]['idprov'] = $idprov;
		$query = insert_update_query($bcdb->preciocotizacion, $detalle_values[$k]);
		$bcdb->query($query);
		$msg = "Se guardaron los precios del proveedor.";
	}
}

?>