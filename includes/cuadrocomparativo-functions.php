<?php

/* cuadrocomparativo-functions.php */
function get_cuadro($idcuadro) {
	global $bcdb;
	return $bcdb->get_row("SELECT * FROM $bcdb->cuadrocomparativo WHERE idcuadro = '$idcuadro'");
}
function get_cuadro_cot($idcot) {
	global $bcdb;
	return $bcdb->get_var("SELECT idcuadro FROM $bcdb->cuadrocomparativo WHERE idcot = '$idcot'");
}

function get_cuadros () {
	global $bcdb, $bcrs, $pager;	
	$sql = "SELECT * 
			FROM $bcdb->cuadrocomparativo
			ORDER BY idcuadro DESC";
	$cuadrocomparativo = ($pager) ? $bcrs->get_results($sql) : $bcdb->get_results($sql);
	return $cuadrocomparativo;
}

function save_cuadro($idcuadro, $cuadro_values) {
	global $bcdb, $msg;
	
		$cuadro_values['idcuadro'] = $idcuadro;
		
	if ( ($query = insert_update_query($bcdb->cuadrocomparativo, $cuadro_values)) &&
		$bcdb->query($query) ) {
		
		if (empty($idcuadro))	
			$idcuadro = $bcdb->insert_id;
		
		$msg = "Los datos han sido guardados satisfactoriamente (cuadro comparativo).";
		$bcdb->query("UPDATE ". $bcdb->cotizacion. " SET status = 3 WHERE idcot = ".$cuadro_values['idcot']);
		return $idcuadro;
	}else {
		
	}
	$msg .= " No se hicieron cambios en los datos del cuadro comparativo";
	return false;
}

function remove_cuadro ($idcuadro) {
	global $bcdb;
	$bcdb->query("DELETE FROM $bcdb->cuadrocomparativo WHERE idcuadro = $idcuadro");
}

function save_cuadro_proveedor($idcot, $prov_values, $nprov) {
	global $bcdb, $msg;
		$prov_values['idcot'] = $idcot;
		$prov_values['nprov'] = $nprov;
	if ( ($query = insert_update_query($bcdb->provcotizacion, $prov_values)) &&
		$bcdb->query($query) ) {
		if (empty($idprov))	
			$idprov = $bcdb->insert_id;
		$msg = $msg."Los datos han sido guardados satisfactoriamente.(Proveedor)<br>".$nprov;
		return $idprov;
	}else {		
	}
	$msg .= " No se hicieron cambios en los datos del Proveedor";	
	return false;
}

function save_cuadro_precios($idprov, $precio_values) {
	global $bcdb, $msg;

	foreach($precio_values as $k => $v) {
		$precio_values[$k]['idprov'] = $idprov;
		$query = insert_update_query($bcdb->preciocotizacion, $precio_values[$k]);
		$bcdb->query($query);
		$msg = "Se guardaron los detalles del Cuadro Comparativo.";
	}
}

//function fill_cot_cuadro($cot,$detalle) {
function fill_cot_cuadro($cot) {
	global $bcdb, $atipos;
	$cot['detalle'] = get_detalle_cot($cot['idcot']);
	$cot['fecha'] = fechita2($cot['fecha']);
	$cot['usuario'] = get_user($cot['createdby']);
	$cot['prov1']= get_prov_cuadro($cot['idcot'],'1');
	if($cot['prov1']['fecha']){	$cot['prov1']['fecha'] = fechita2($cot['prov1']['fecha']);}
	$cot['prov2']= get_prov_cuadro($cot['idcot'],'2');
	if($cot['prov2']['fecha']){	$cot['prov2']['fecha'] = fechita2($cot['prov2']['fecha']);}
	$cot['prov3']= get_prov_cuadro($cot['idcot'],'3');
	if($cot['prov3']['fecha']){	$cot['prov3']['fecha'] = fechita2($cot['prov3']['fecha']);}
	$cot['precio1']= get_precios($cot['detalle'],'1');	
	$cot['precio2']= get_precios($cot['detalle'],'2');	
	$cot['precio3']= get_precios($cot['detalle'],'3');	
//	$cot['precio4']= get_precios($cot['detalle'],'4');
	return $cot;
}

function get_prov_cuadro($idcot, $nprov){
	global $bcdb;
	return $bcdb->get_row("SELECT idprov,a.idproveedor,idcot,plazo,fecha,nprov,nombre,razonsocial,direccion,telefono,email,ruc FROM $bcdb->provcotizacion a,$bcdb->proveedores b WHERE a.idproveedor=b.idproveedor AND idcot = '$idcot' AND nprov = '$nprov' ");
}

function get_ganador_cuadro($idcot){
	global $bcdb;
        $sql = "SELECT b.nprov
                FROM $bcdb->cuadrocomparativo a, 
                $bcdb->provcotizacion b 
                WHERE a.idcot = '$idcot' 
                AND b.idcot = '$idcot' 
                AND a.idproveedor = b.nprov ";
        echo $sql;
	return $bcdb->get_var($sql);
}
function get_precios($detalle, $nprov){
	global $bcdb;
	foreach($detalle as $k => $v) {
		$id = $detalle[$k]['iddetalle'];
		$precios[$k] = $bcdb->get_row("SELECT * FROM $bcdb->preciocotizacion WHERE iddetalle = '$id' AND idprov= '$nprov'");
	}
	if ($precios){
		return $precios;
	}else{
		return false;
	}
}

function fill_cuadros($cuadros) {
	global $bcdb;
	foreach($cuadros as $k => $v) {
		$cuadros[$k] = fill_cuadro($cuadros[$k]);
	}
	return $cuadros;
}


function fill_cuadro($cuadro) {
	global $bcdb, $atipos;
	$cuadro['proveedor'] = get_cuadro_rucs($cuadro['idcot']);
	$cuadro['fecha'] = fechita2($cuadro['fecha']);
	$cuadro['detalle'] = get_detalle_cuadro($cuadro['idcot']);
//	$cuadro['proveedor'] = get_prov($cuadro['idproveedor']);
	$cuadro['detalle'] = get_detalle_cuadro($cuadro['idcot']);
	$cuadro['usuario'] = get_user($cuadro['createdby']);
	return $cuadro;
//idcuadro 	idcot 	fecha 	idproveedor 	monto 	justificacion 	Observacion 	cep 
}
function fill_compra_by_cot($orden) { //referencia
	global $bcdb, $atipos;
	$orden['nrodoc']=$orden['referencia'];
	$orden['codigo']=null;	
	$orden['proveedor'] = get_prov(get_ganador_cuadro($orden['idcot']));	
	$orden['detalle'] = get_detalle_cot($orden['idcot']);//cantidad 	umedida 	descripcion 	precio
	$orden['detalle'] = get_precios2($orden['detalle'], get_ganador_cuadro($orden['idcot']));
 	$orden['fecha'] = fechita2($orden['fecha']);
	return $orden;
}

function fill_servicio_by_cot($orden) { //referencia
	global $bcdb, $atipos;
	$orden['nrodoc']=$orden['referencia'];
	$orden['codigo']=null;	
	$orden['proveedor']=get_prov(get_ganador_cuadro($orden['idcot']));	
	$orden['detalle'] = get_detalle_cot($orden['idcot']);//cantidad 	umedida 	descripcion 	precio
	$orden['detalle'] = get_precios2($orden['detalle'], get_ganador_cuadro($orden['idcot']));
 	$orden['fecha'] = fechita2($orden['fecha']);
	return $orden;
}

function get_precios2($detalle, $nprov){
	$precio = get_precios($detalle, $nprov);
	foreach($detalle as $k => $v) {
		$detalle[$k]['precio'] = $precio[$k]['precio'];
	}	
	return $detalle;
}
function get_precios_ganador($detalle){
	global $bcdb;
	$idcot=$detalle['idcot'];
	$wind=$bcdb->get_row("SELECT idproveedor FROM $bcdb->cuadrocomparativo WHERE idcot = '$idcot' ");
	foreach($detalle as $k => $v) {
		$id=$detalle[$k]['iddetalle'];
		$detalle[$k]['precio'] = $bcdb->get_row("SELECT precio FROM $bcdb->preciocotizacion WHERE iddetalle = '$id' AND idprov= '$wind'");
		$detalle[$k]['total'] = $detalle[$k]['precio']*$detalle[$k]['cantidad'];
	}
	return $detalle;
}

/* DETALLE COTIZACION */

function save_detalle_cuadro($idcot, $detalle_values) {
	global $bcdb, $msg;
	foreach($detalle_values as $k => $v) {
		$detalle_values[$k]['idcot'] = $idcot;
		$query = insert_update_query($bcdb->detallecotizacion, $detalle_values[$k]);
		$bcdb->query($query);
		$msg = "Se guardaron los detalles de la Cotizacion.";
	}
}

function get_detalle_cuadro($idcot) {
	global $bcdb;
	$detalle = $bcdb->get_results("SELECT * FROM $bcdb->detallecotizacion WHERE idcot = '$idcot' ORDER BY `o_detallecotizacion`.`iddetalle` ASC");
	if($detalle) {
		return $detalle;
	}else
		return false;
}
function get_cuadro_rucs($idcot){
	global $bcdb;
	$rucs = $bcdb->get_results("SELECT idprov FROM $bcdb->preciocotizacion a,$bcdb->detallecotizacion b WHERE a.iddetalle = b.iddetalle AND idcot='$idcot' GROUP BY idprov");
	if($rucs){
		return $rucs;
	}else
	 return false;
}
function borrar_detalle_cuadro($iddetalle, $idcot) {
	global $bcdb;
	return $bcdb->query("DELETE FROM $bcdb->detallecotizacion WHERE idcot = '$idcot' AND iddetalle = '$iddetalle'");
}
//prove cotizacion
function save_prov_cuadro($idcot, $prov_values) {
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

function save_prov_precios($idprov, $detalle_values) {
	global $bcdb, $msg;
	foreach($detalle_values as $k => $v) {
		$detalle_values[$k]['idprov'] = $idprov;
		$query = insert_update_query($bcdb->preciocotizacion, $detalle_values[$k]);
		$bcdb->query($query);
		$msg = "Se guardaron los precios del proveedor.";
	}
}
function nombre_spa($nombre) {
	$name = split(" ", $nombre);
	if(! isset($name[1])){$name[1]="";}
	if(! isset($name[2])){$name[2]="";}
	return $name;
}
?>