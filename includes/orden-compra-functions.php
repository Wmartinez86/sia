<?php

/* Ordenes Compra */
/**
 * Trae una orden de compra
 * 
 * @param type $idorden el id de la orden
 * @return mixed si existe devuelve la orden, sino false
 */
function get_orden_compra ($idorden) {
	global $bcdb;
        $orden = $bcdb->get_row("SELECT * FROM $bcdb->ordencompra WHERE idorden = '$idorden'");
	return ($orden) ? $orden : false;
}

/**
 * Devuelve las órdenes de compra
 * 
 * @global Object $bcdb El objeto Base de Datos
 * @global Object $bcrs El objeto Paginador
 * @global boolean $pager Identifica si el resultado volverá paginado o no
 * @param mixed $active Si no es nula, devuelve sólo las órdenes activas
 * @return array $ordencompras Los resultados 
 */
function get_ordenes_compra ($active = null) {
	global $bcdb, $bcrs, $pager;
	
	$sql = "SELECT * 
			FROM $bcdb->ordencompra ";
        $sql .= (!is_null($active)) ? "WHERE status = 1" : "";
        $sql .= " ORDER BY idorden DESC";
        
	$ordencompras = ($pager) ? $bcrs->get_results($sql) : $bcdb->get_results($sql);
	return $ordencompras;
}

function save_orden_compra($idorden, $orden_values) {
	global $bcdb, $msg;

	if ($bcdb->get_var("SELECT count(codigo) FROM $bcdb->ordencompra WHERE idorden != '$idorden' AND codigo = '$orden_values[codigo]'") ) {
		$msg .= " Ya existe otra orden con el mismo c&oacute;digo.";
		return false;
	}
	
		$orden_values['idorden'] = $idorden;
	
	if ( ($query = insert_update_query($bcdb->ordencompra, $orden_values)) &&
		$bcdb->query($query) ) {
		
		if (empty($idorden))	
			$idorden = $bcdb->insert_id;
		
		$msg = "Los datos han sido guardados satisfactoriamente.";
		
		return $idorden;
	}else {
		
	}
	$msg .= " No se hicieron cambios en los datos de la orden.";
	return false;
}

function remove_orden_compra ($idorden) {
	global $bcdb;
	$bcdb->query("DELETE FROM $bcdb->ordencompra WHERE idorden = $idorden");
}

function fill_compras($ordenes) {
	foreach($ordenes as $k => $v) {
		$ordenes[$k] = fill_compra($ordenes[$k]);
	}
	return $ordenes;
}

function fill_compra($orden) {
	$orden['doc'] = get_doc($orden['iddoc']);
	$orden['proyecto'] = get_proj($orden['idproyecto']);
	$orden['proveedor'] = get_prov($orden['idproveedor']);
	$orden['fuente'] = get_fuente($orden['idfuente']);
	$orden['detalle'] = get_detalle_compra($orden['idorden']);
	$orden['fecha'] = fechita2($orden['fecha']);
	$orden['usuario'] = get_user($orden['createdby']);
	return $orden;
}

/**
 * Busca órdenes de compra
 * 
 * @param int $idproyecto el id del proyecto
 * @param int $iduser el id del usuario 
 * @return array los resultados
 */
function search_orden_compra($idproyecto, $iduser, $activos = null) {
    $results = array();
    
    if(!empty($idproyecto)) {
        $results = get_ordenes_by_project($idproyecto, "compra", $activos);
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

/* PECOSA */
/* Se eliminaron funciones de PECOSA antiguo 27 Enero 2012*/

/* DETALLE ORDEN COMPRA */

function save_detalle_compra($idorden, $detalle_values) {
	global $bcdb, $msg;
	foreach($detalle_values as $k => $v) {
		$detalle_values[$k]['idorden'] = $idorden;
		$query = insert_update_query($bcdb->detalleordencompra, $detalle_values[$k]);
		$bcdb->query($query);
		$msg = "Se guardaron los detalles de la orden.";
	}
}

function get_detalle_compra($idorden) {
	global $bcdb;
	$detalle = $bcdb->get_results("SELECT * FROM $bcdb->detalleordencompra WHERE idorden = '$idorden'");
	if($detalle) {
		foreach($detalle as $k => $v) {
			$detalle[$k]['total'] = $detalle[$k]['cantidad']*$detalle[$k]['precio'];
		}
		return $detalle;
	}else
		return false;
}

function borrar_detalle_compra($iddetalle, $idorden) {
	global $bcdb;
	return $bcdb->query("DELETE FROM $bcdb->detalleordencompra WHERE idorden = '$idorden' AND iddetalle = '$iddetalle'");
}

/**
 * Congela una orden para que ya no pueda ser editada
 * además indica que la orden ya ha sido ingresada al almacén
 * 
 * @param type $idorden la Orden
 * @return mixed éxito
 */
function freeze_orden($idorden) {
	global $bcdb;
	return $bcdb->query("UPDATE $bcdb->ordencompra SET status = 3 WHERE idorden = $idorden");
}


/**
 * Funciones para órdenes padre
 *
 */
 
function save_orden_comprapadre($idorden, $orden_values) {
	global $bcdb, $msg;

	if ($bcdb->get_var("SELECT count(codigo) FROM $bcdb->ordencompra WHERE idorden != '$idorden' AND codigo = '$orden_values[codigo]'") ) {
		$msg .= " Ya existe otra orden con el mismo c&oacute;digo.";
		return false;
	}
	
		$orden_values['idorden'] = $idorden;
	
	if ( ($query = insert_update_query($bcdb->ordencomprapadre, $orden_values)) &&
		$bcdb->query($query) ) {
		
		if (empty($idorden))	
			$idorden = $bcdb->insert_id;
		
		$msg = "Los datos han sido guardados satisfactoriamente.";
		
		return $idorden;
	}else {
		
	}
	$msg .= " No se hicieron cambios en los datos de la orden.";
	return false;
}

function get_orden_comprapadre ($idorden) {
	global $bcdb;
        $orden = $bcdb->get_row("SELECT * FROM $bcdb->ordencomprapadre WHERE idorden = '$idorden'");
	return ($orden) ? $orden : false;
}

function get_ordenes_compra_hijos ($idpadre, $active = null) {
	global $bcdb;
	
	$sql = "SELECT * FROM $bcdb->ordencompra WHERE idpadre = '$idpadre' ";
        $sql .= (!is_null($active)) ? "AND status = 1" : "";
        $sql .= " ORDER BY idorden DESC";
        
	$ordencompras = $bcdb->get_results($sql);
        
        if($ordencompras) {
            foreach($ordencompras as $k => $orden) {
                $ordencompras[$k] = fill_compra($orden);
            }
            
            return $ordencompras;
        }
	return false;
}

function fill_comprapadre($padre) {
	$padre['proveedor'] = get_prov($padre['idproveedor']);
	$padre['detalle'] = get_detalle_compra($padre['idorden']);
	$padre['fecha'] = fechita2($padre['fecha']);
	$padre['usuario'] = get_user($padre['createdby']);
        $padre['hijos'] = get_ordenes_compra_hijos($padre['idorden']);
	return $padre;
}

?>