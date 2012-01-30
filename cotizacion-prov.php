<?php

require_once('home.php');
require_once('redirect.php');

if (isset($_REQUEST['idcot'])) { 
    $idcot = $_REQUEST['idcot'];
} else {
    $idcot = '0';
}

//$idcot = ! empty($_REQUEST['idcot']) ? (int)$idcot : 0;

if(isset($_GET['cancel'])) {
	cancel_cot($idcot, "cotizacion");
	header("Location: cotizacion-lista.php");
	exit();
}

if(isset($_GET['activate'])) {
	activate_cot($idcot, "cotizacion");
	header("Location: cotizacion-lista.php");
	exit();
}

if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
	if ( validate_required(array(
		'Ruc del Proveedor' => $_POST['oruc'], 
		'Fecha del Cotizacion' => $_POST['fecha'], 
		'Plazo de Entrega' => $_POST['plazo']))) 
		{
			$prov_values = array(
				'idproveedor' => get_idprov_by_ruc($_POST['oruc']),
				'idcot' => $_POST['idcot'],
				'plazo' => $_POST['plazo'],
				'fecha' => fechita($_POST['fecha'])
				);
//			$precio_values = array_map('strip_tags', $precio_values);
//			$precio_values = array();
			if($_POST['precio']) {
				foreach($_POST['precio'] as $k=>$v) {
					$precio_values[$k]['precio'] = $_POST['precio'][$k];
					$precio_values[$k]['iddetalle'] = $_POST['iddetalle'][$k];
				}
		}

//		$cot_values = array_map('strip_tags', $cot_values);
		$idprov=get_idprov_by_ruc($_POST['oruc']);
		if(isset($_POST['iddetalle'])) {
			foreach($_POST['iddetalle'] as $k=>$v) {
				$detalle_values[$k]['iddetalle'] = $_POST['iddetalle'][$k];
			}
//			save_cotizacion_prov($idcot, $cot_values);
//			save_detalle_cot($idcot, $detalle_values);
			save_prov_cot($idcot, $prov_values);
			save_prov_precio($idprov, $precio_values);
		}else{
			$id = save_cotizacion($idcot, $cot_values);
			if($id) {
				save_prov_cot($idcot, $prov_values);
				save_prov_precio($idprov, $precio_values);
				//save_pecosa($id, $pecosa_values);
				//save_detalle_cot($id, $detalle_values);
			}
		}
		
	} 
	$idcot = 0;
}

if($idcot){ 
	$cot = fill_cot(get_cotizacion($idcot));
	$smarty->assign ('cot', $cot);
	if(count($cot['detalle'])==1) {
		$smarty->assign ('nodel', true);
	}
}
	
//$smarty->assign ('atipos', $atipos);
//$smarty->assign ('projs', $projs);
//$smarty->assign ('provs', $provs);
//$smarty->assign ('fuentes', $fuentes);
//$smarty->assign ('docs', $docs);
//$smarty->assign ('especs', $especs);
//$smarty->assign ('codgen', $codgen);

$smarty->assign ('section_title', TITLE . ' - Cotizaciones');
$smarty->assign ('file', 'cotizacion-prov.html');
$smarty->display ('index.html');
?>

