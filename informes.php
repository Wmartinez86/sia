<?php

require_once('home.php');
require_once('redirect.php');
//$pager = true;
$status = 1;
$postback = isset($_GET['orden']);
if($postback) {
	$orden = $_GET['orden'];
	$idproyecto = $_GET['idproyecto'];
	$iddoc = $_GET['iddoc'];
	$idfuente = $_GET['idfuente'];
	$status = $_GET['status'];
	$fecha1 = $_GET['fecha1'];
	$fecha2 = $_GET['fecha2'];
	$ruc = (strlen($_GET['ruc'])>0) ? $_GET['ruc'] : '';
	
	$search_values = array(
			'iddoc' => $_GET['iddoc'],
			'idproyecto' => $_GET['idproyecto'],
			'idfuente' => $_GET['idfuente'],
			'status' => $_GET['status']
	);
	
	$prov = get_prov_by_ruc($ruc);
	$search_values['idproveedor'] = $prov['idproveedor'];
	if(strlen($ruc)<11&&!empty($ruc))
		$search_values['idproveedor'] = 'otro';
	$ordenes = search_ordenes($search_values, $bcdb->$orden, fechita($fecha1), fechita($fecha2));
	if($ordenes) {
		if($orden == "ordencompra") {
			$ordenes = fill_compras($ordenes);
		}else{
			$ordenes = fill_servicios($ordenes);
		}
		$atotal = 0;
		$field = ($orden=="ordencompra") ? 'total' : 'precio';
		foreach($ordenes as $k=>$v) {
			$ordenes[$k]['stotal'] = supertotal($v['detalle'], $field);
			$atotal += supertotal($v['detalle'], $field);
		}
		$smarty->assign ('atotal', $atotal);
	}
	
	
	$smarty->assign ('ordenes', $ordenes);
	$smarty->assign ('postback', $postback);
	$smarty->assign ('idproyecto', $idproyecto);
	$smarty->assign ('iddoc', $iddoc);
	$smarty->assign ('idfuente', $idfuente);
	$smarty->assign ('ruc', $ruc);

	$smarty->assign ('fecha1', $fecha1);
	$smarty->assign ('fecha2', $fecha2);
	$smarty->assign ('orden', $orden);
	
	if(isset($_GET['excel'])) {
		header ("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT-5");
		header ("Cache-Control: no-cache, must-revalidate");
		header ("Pragma: no-cache");
		header ("Content-type: application/x-msexcel");
		header ("Content-Disposition: attachment; filename=informe.xls" );
		$xls = $smarty->fetch ('informe-excel.html');
		die($xls);
	}
}
$smarty->assign ('status', $status);

$projs = get_projs();
$provs = get_provs();
$fuentes = get_fuentes();
$docs = get_docs();
$especs = get_especificas();

$smarty->assign ('atipos', $atipos);
$smarty->assign ('projs', $projs);
$smarty->assign ('provs', $provs);
$smarty->assign ('fuentes', $fuentes);
$smarty->assign ('docs', $docs);
$smarty->assign ('especs', $especs);

$smarty->assign ('section_title', TITLE . ' - Informes');
$smarty->assign ('file', 'informes.html');
$smarty->display ('index.html');

?>