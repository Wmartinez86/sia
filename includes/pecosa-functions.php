<?php

/* PECOSAS */
function get_pecosa ($idpecosa) {
	global $bcdb;
	return $bcdb->get_row("SELECT * FROM $bcdb->pecosa WHERE idpecosa = '$idpecosa'");
}

function get_pecosas () {
	global $bcdb, $bcrs, $pager;
	$sql = "SELECT * 
			FROM $bcdb->pecosa 
			ORDER BY idpecosa DESC";
	$neas = ($pager) ? $bcrs->get_results($sql) : $bcdb->get_results($sql);
	return $neas;
}

function save_pecosa ($idpecosa, $pecosa_values) {
	global $bcdb, $msg;

	if ($bcdb->get_var("SELECT count(codigo) FROM $bcdb->pecosa WHERE idpecosa != '$idpecosa' AND codigo = '$pecosa_values[codigo]'") ) {
		$msg .= " Ya existe otra PECOSA con el mismo c&oacute;digo.";
		return false;
	}
	
	$pecosa_values['idpecosa'] = $idpecosa;
	
	if ( ($query = insert_update_query($bcdb->pecosa, $pecosa_values)) &&
		$bcdb->query($query) ) {
		
		if (empty($idpecosa))	
			$idpecosa = $bcdb->insert_id;
		
		$msg = "Los datos han sido guardados satisfactoriamente.";
		
		return $idpecosa;
	}
        
	$msg .= " No se hicieron cambios en los datos de la PECOSA.";
	return false;
}

function save_detalle_pecosa($idpecosa, $detalle_values) {
	global $bcdb, $msg;
	foreach($detalle_values as $k => $v) {
		$detalle_values[$k]['idpecosa'] = $idpecosa;
		$query = insert_update_query($bcdb->detallepecosa, $detalle_values[$k]);
		$bcdb->query($query);
		$msg = "Se guardaron los detalles de la PECOSA.";
	}
}

function remove_pecosa ($idpecosa) {
	global $bcdb;
	$bcdb->query("DELETE FROM $bcdb->pecosa WHERE idpecosa = $idpecosa");
}

function fill_pecosas($pecosas) {
	foreach($pecosas as $k => $v) {
		$pecosas[$k] = fill_pecosa($pecosas[$k]);
	}
	return $pecosas;
}

function fill_pecosa($pecosa) {
	$pecosa['fecha'] = fechita2($pecosa['fecha']);
	$pecosa['usuario'] = get_user($pecosa['createdby']);
        $pecosa['detalle'] = get_detalle_pecosa($pecosa['idpecosa']);
	return $pecosa;
}

/**
 * ACA MEQUEDEEE
 * @global type $bcdb
 * @param type $idpecosa
 * @return boolean 
 */
function get_detalle_pecosa($idpecosa) {
	global $bcdb;
	$detalle = $bcdb->get_results("SELECT * FROM $bcdb->detallepecosa WHERE idpecosa = '$idpecosa'");
	if($detalle) {
                foreach($detalle as $k => $v) {
                    $detalle[$k]['producto'] = get_producto($v['idenalmacen']);
                }
		return $detalle;
	} else
		return false;
}

/**
 * Productos de ALMACÉN 
 */

/**
 * Trae productos por proyecto
 * 
 * @param int $idproyecto el id del proyecto
 */
function get_productos_by_proyecto($idproyecto) {
    $results = array();
    $productos = array();
    $ordenes = get_ordenes_by_project($idproyecto, "compra");
    
    if($ordenes) {
        foreach($ordenes as $k => $orden) {
            $detalles = get_productos_by_orden($orden['idorden']);
            if($detalles) $results[] = $detalles;
        }

        foreach($results as $k => $grupo) {
            foreach($grupo as $j => $producto) {
                $productos[] = $producto;
            }
        } 
    }

    return $productos;  
    
}

/**
 * Retorna productos del almacen que han entrado de acuerdo al id de una orden de compra
 * 
 * @param int $idorden el id de la orden
 * @return mixed los productos o false si no existen
 */
function get_productos_by_orden($idorden) {
    global $bcdb;
    
    $sql = sprintf("SELECT
            a.*,
            n.descripcion,
            n.umedida
            FROM %s a
            INNER JOIN %s n
            ON a.idennea = n.iddetalle
            WHERE a.idorden = %s;", $bcdb->detallealmacen, $bcdb->detallenea, $idorden);

    $results = $bcdb->get_results($sql);
    
    $productos = array();
    if($results) {
        foreach($results as $k => $v) {
            if(get_saldo($v) == 0) unset($results[$k]);
        }
        
        foreach($results as $k => $v) {
            $productos[] = $v;
        }
        return $productos;
        
    } else {
        return false;
    }
}

/**
 * Retorna productos del almacen que han entrado de acuerdo al codigo de una orden de compra
 * 
 * @param string $codigo el codigo de la orden
 * @return mixed los productos o false si no existen
 */
function get_productos_by_codigo($codigo) {
    global $bcdb;
    
    $sql = sprintf("SELECT 
                    a.*, 
                    n.descripcion, 
                    n.umedida,
                    c.status 
                    FROM %s a
                    INNER JOIN %s n
                    ON a.idennea = n.iddetalle
                    INNER JOIN %s nea
                    ON n.idnea = nea.idnea
                    INNER JOIN %s c
                    ON nea.idorden = c.idorden
                    WHERE c.codigo = '%s'", $bcdb->detallealmacen, 
                                                $bcdb->detallenea, 
                                                $bcdb->neas, 
                                                $bcdb->ordencompra,
                                                $codigo);
    
    $results = $bcdb->get_results($sql);
    
    $productos = array();
    if($results) {
        foreach($results as $k => $v) {
            if(get_saldo($v) == 0) unset($results[$k]);
        }
        
        foreach($results as $k => $v) {
            $productos[] = $v;
        }
        return $productos;
        
    } else {
        return false;
    }
}

/**
 * Trae productos del almacen
 * @return 
 *  Los productos, caso contrario es FALSE
 */
function get_productos_almacen() {
   global $bcdb;
   
   $sql = sprintf("SELECT 
            a.*,            
            n.descripcion,
            n.umedida
            FROM %s a
            INNER JOIN %s n
            ON a.idennea = n.iddetalle
            WHERE a.idorden = 0;", $bcdb->detallealmacen, $bcdb->detallenea);
   
    $results = $bcdb->get_results($sql);
    
    $productos = array();
    if($results) {
        foreach($results as $k => $v) {
            
            if(get_saldo($v) == 0) unset($results[$k]);
        }
        
        foreach($results as $k => $v) {
            $productos[] = $v;
        }
        return $productos;
        
    } else {
        return false;
    }
}

/**
 * Retorna el saldo de un determinado producto
 * @param array $producto el producto
 * @return 
 *  float El Saldo
 */
function get_saldo($producto) {
    return $producto['cantidad']-$producto['cuantosalio'];
}

/**
 * Retorna un producto que está en el almacen
 *
 * @param int $iddetalle el id del producto
 * @return 
 *  mixed El producto, sino FALSE
 */
function get_producto($iddetalle) {
    global $bcdb;
    
    $sql = sprintf("SELECT 
            a.*,            
            n.descripcion,
            n.umedida
            FROM %s a
            INNER JOIN %s n
            ON a.idennea = n.iddetalle
            WHERE a.iddetalle = %s;", $bcdb->detallealmacen, $bcdb->detallenea, $iddetalle);
   
    $producto = $bcdb->get_row($sql);
    if($producto) {
        $producto['saldo'] = get_saldo($producto);
        return $producto;
    } else {
        return false;
    }
}

/**
 * Quitar producto
 * @param array $producto el producto
 * @return mixed la consulta 
 */
function quitar_producto($producto) {
    global $bcdb;
    $sql = sprintf("UPDATE $bcdb->detallealmacen
            SET cuantosalio = %s
            WHERE iddetalle = %s", $producto['cuantosalio'], $producto['iddetalle']);
    return $bcdb->query($sql);
}

/**
 * Guarda detalles que han sido quitados del almacen
 * 
 * @param int $idpecosa La PECOSA a la que pertenecen
 * @param int $idenalmacen El id en almacen
 * @param numeric $cantidad Cuanto sale
 */
function save_detalle_salida($idpecosa, $idenalmacen, $cantidad) {
    global $bcdb, $msg;
    
    $prod = get_producto($idenalmacen);
    $prod['cuantosalio'] = $prod['cuantosalio']+$cantidad;
    
    quitar_producto($prod);
    
    $sql = "INSERT INTO 
        $bcdb->detallepecosa(idpecosa, idenalmacen, cantidad)
            VALUES(
                '$idpecosa',
                '$idenalmacen',
                '$cantidad'
            );";
    
    $bcdb->query($sql);

}

?>