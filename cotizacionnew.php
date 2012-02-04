<?php

require_once('home.php');
require_once('redirect.php');

if (isset($_REQUEST['idcot'])) { 
    $idcot = $_REQUEST['idcot'];
} else {
    $idcot = '0';
}
if (isset($_REQUEST['idreq'])) { 
    $idreq = $_REQUEST['idreq'];
} 

//$idcot = ! empty($_GET['idcot']) ? (int)$idcot : 0;

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

$codgen = generate_code($bcdb->cotizacion);

if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
	if ( validate_required(array(
		'Referencia ' => $_POST['referencia'], 
		'Nro. de Cotizacion' => $_POST['codigo']))) {

		$cot_values = array(
			'referencia' => $_POST['referencia'],
			'codigo' => $_POST['codigo'],
			'fecha' => fechita($_POST['fecha']),
			'createdby' => $_SESSION['loginuser']['iduser'],
			'tipocontrata' => $_POST['tipocontrata'],
		);		
		$detalle_values = array();
		
		if($_POST['cantidad']) {
			foreach($_POST['cantidad'] as $k=>$v) {
				$detalle_values[$k]['cantidad'] = $_POST['cantidad'][$k];
				$detalle_values[$k]['umedida'] = $_POST['umedida'][$k];
				$detalle_values[$k]['descripcion'] = $_POST['descripcion'][$k];
			}
		}
		
		$cot_values = array_map('strip_tags', $cot_values);

		if(isset($_POST['iddetalle'])) {
			foreach($_POST['iddetalle'] as $k=>$v) {
				$detalle_values[$k]['iddetalle'] = $_POST['iddetalle'][$k];
			}
			save_cotizacion($idcot, $cot_values);
			save_detalle_cot($idcot, $detalle_values);
//			save_pecosa($idorden, $pecosa_values);
		}else{
			$id = save_cotizacion($idcot, $cot_values);
			if($id) {
//				save_pecosa($id, $pecosa_values);
				save_detalle_cot($id, $detalle_values);
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

if(isset($idreq)){ 
	$req = fill_req(get_requerimiento($idreq));
	$smarty->assign ('req', $req);
	if(count($req['detalle'])==1) {
		$smarty->assign ('nodel', true);
	}
        
        $nreq2 = "Req. 000".$req['codigo']."-2012-".$req['usuario']['area']['abreviatura'];
        $proj = get_proj($req['usuario']['proyecto']['idproyecto']);
        $nreq2 .= "/".$proj['descripcion'];
        $smarty->assign('nreq2', $nreq2);
}
	
$smarty->assign ('codgen', $codgen);

$smarty->assign ('section_title', TITLE . ' - Cotizaciones');
$smarty->assign ('file', 'cotizacionnew.html');
$smarty->display ('index.html');

?>