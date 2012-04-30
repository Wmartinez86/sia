<?php

require_once('home.php');
require_once('redirect.php');

$idpadre = (isset($_REQUEST['idpadre'])) ? $_REQUEST['idpadre'] : 0;

$projs = get_projs();
$provs = get_provs();
$fuentes = get_fuentes();
$docs = get_docs();

if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
	if ( validate_required(array(
		'Nro. Referencia' => $_POST['nrodoc'], 
		'C&oacute;digo O/C' => $_POST['codigo'],
		'RUC Proveedor' => $_POST['oruc'],
		'Facturar a' => $_POST['facturarto'],
		'RUC del proveedor' => ($_POST['fruc']),
		'Destino' => $_POST['destino']))) {
		
		$orden_values = array(
			'iddoc' => $_POST['iddoc'],
			'nrodoc' => $_POST['nrodoc'], 
			'codigo' => $_POST['codigo'],
			'idproyecto' => $_POST['idproyecto'],
			'idfuente' => $_POST['idfuente'],
			'idproveedor' => get_idprov_by_ruc($_POST['oruc']),
			'facturarto' => $_POST['facturarto'],
			'fruc' => $_POST['fruc'],
			'fecha' => fechita($_POST['fecha']),
			'destino' => ($_POST['destino']),
			'createdby' => $_SESSION['loginuser']['iduser']
		);
		
		$detalle_values = array();
		if($_POST['especifica']) {
			foreach($_POST['especifica'] as $k=>$v) {
				$detalle_values[$k]['especifica'] = $_POST['especifica'][$k];
				$detalle_values[$k]['cantidad'] = $_POST['cantidad'][$k];
				$detalle_values[$k]['umedida'] = $_POST['umedida'][$k];
				$detalle_values[$k]['descripcion'] = ucwords(mb_strtolower($_POST['descripcion'][$k], 'UTF-8'));
				$detalle_values[$k]['precio'] = $_POST['precio'][$k];
			}
		}
		
		$orden_values = array_map('strip_tags', $orden_values);
		
		if(isset($_POST['iddetalle'])) {
			foreach($_POST['iddetalle'] as $k=>$v) {
				$detalle_values[$k]['iddetalle'] = $_POST['iddetalle'][$k];
			}
			save_orden_compra($idorden, $orden_values);
			save_detalle_compra($idorden, $detalle_values);
		}else{
			$id = save_orden_compra($idorden, $orden_values);
			if($id) {
				save_detalle_compra($id, $detalle_values);
			}
		}
	} 
	$idorden = 0;
}

$codgen = generate_code($bcdb->ordencompra);

if($idpadre){ 
	$padre = get_orden_comprapadre($idpadre);
	
	if(!$padre)  error();

	$padre = fill_comprapadre($padre);
	if($padre['status'] == ORDEN_CONGELADA) {
		error();
	}
	
	$smarty->assign ('padre', $padre);

}

$smarty->assign ('atipos', $atipos);
$smarty->assign ('projs', $projs);
$smarty->assign ('provs', $provs);
$smarty->assign ('fuentes', $fuentes);
$smarty->assign ('docs', $docs);
$smarty->assign ('codgen', $codgen);
$smarty->assign ('section_title', TITLE . ' - &Oacute;rdenes de Compra');

$smarty->display ('orden-compra-hijo.html');
?>
