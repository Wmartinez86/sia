<?php

/* Ordenes Servicio */
function get_orden_servicio ($idorden) {
	global $bcdb;
	return $bcdb->get_row("SELECT * FROM $bcdb->ordenservicio WHERE idorden = '$idorden'");
}

function get_ordenes_servicio () {
	global $bcdb, $bcrs, $pager;
	
	$sql = "SELECT * 
			FROM $bcdb->ordenservicio 
			ORDER BY idorden DESC";
	$ordenservicios = ($pager) ? $bcrs->get_results($sql) : $bcdb->get_results($sql);
	return $ordenservicios;
}

function save_orden_servicio($idorden, $orden_values) {
	global $bcdb, $msg;
	if ($bcdb->get_var("SELECT count(codigo) FROM $bcdb->ordenservicio WHERE idorden != '$idorden' AND codigo = '$orden_values[codigo]'") ) {
		$msg = "Ya existe otra orden con el mismo c&oacute;digo.";
		return false;
	}
	
		$orden_values['idorden'] = $idorden;
	
	if ( ($query = insert_update_query($bcdb->ordenservicio, $orden_values)) &&
		$bcdb->query($query) ) {
		if (empty($idorden))	
			$idorden = $bcdb->insert_id;
		
		$msg = "Los datos han sido guardados satisfactoriamente.";
		
		return $idorden;
	}
	$msg = "No hubo cambios en los datos de la orden.";
	return false;
}

function remove_orden_servicio ($idorden) {
	global $bcdb;
	$bcdb->query("DELETE FROM $bcdb->ordenservicio WHERE idorden = $idorden");
}

function fill_servicios($ordenes) {
	global $bcdb;
	foreach($ordenes as $k => $v) {
		$ordenes[$k] = fill_servicio($ordenes[$k]);
	}
	return $ordenes;
}

function fill_servicio($orden) {
	global $bcdb, $atipos;
	$orden['doc'] = get_doc($orden['iddoc']);
	$orden['proyecto'] = get_proj($orden['idproyecto']);
	$orden['proveedor'] = get_prov($orden['idproveedor']);
//	$orden['rubro'] = $atipos[$orden['proyecto']['idtipo']];
	$orden['fuente'] = get_fuente($orden['idfuente']);
	$orden['detalle'] = get_detalle_servicio($orden['idorden']);
	$orden['fecha'] = fechita2($orden['fecha']);
	$orden['usuario'] = get_user($orden['createdby']);
	return $orden;
}

/**
 * Busca órdenes de servicio
 * 
 * @param int $idproyecto el id del proyecto
 * @param int $iduser el id del usuario 
 * @return array los resultados
 */
function search_orden_servicio($idproyecto, $iduser) {
    $results = array();
    
    if(!empty($idproyecto)) {
        $results = get_ordenes_by_project($idproyecto, "servicio");
    } else {
        $results = get_ordenes_compra();
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


/* DETALLE ORDEN COMPRA */

function save_detalle_servicio($idorden, $detalle_values) {
	global $bcdb, $msg;
	foreach($detalle_values as $k => $v) {
		$detalle_values[$k]['idorden'] = $idorden;
		$query = insert_update_query($bcdb->detalleordenservicio, $detalle_values[$k]);
		if($bcdb->query($query))
			$msg .= " Se guardaron los detalles de la orden";
		else
			$msg .= " No hubieron cambios en los detalles de la orden";
	}
}

function get_detalle_servicio($idorden) {
	global $bcdb;
	$detalle = $bcdb->get_results("SELECT * FROM $bcdb->detalleordenservicio WHERE idorden = '$idorden'");
	if($detalle) {
		/*foreach($detalle as $k => $v) {
			$detalle[$k]['especifica'] = get_especifica($detalle[$k]['idespec']);
		}*/
		return $detalle;
	}else
		return false;
}

?>