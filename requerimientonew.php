<?php

require_once('home.php');
require_once('redirect.php');

if (isset($_REQUEST['idreq'])) { 
    $idreq = $_REQUEST['idreq'];
} else {
    $idreq = '0';
}


//$idcot = ! empty($_GET['idcot']) ? (int)$idcot : 0;

if(isset($_GET['cancel'])) {
	cancel_req($idreq, "requerimientos");
	header("Location: requerimiento-lista.php");
	exit();
}

if(isset($_GET['activate'])) {
	activate_req($idreq, "requerimientos");
	header("Location: requerimiento-lista.php");
	exit();
}


if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
	if ( validate_required(array(
		'Nro. de Requerimiento' => $_POST['codigo']))) {

		$req_values = array(
			'codigo' => $_POST['codigo'],
			'fecha' => fechita($_POST['fecha']),
			'createdby' => $_SESSION['loginuser']['iduser']
		);
		
		$detalle_values = array();
		
		if($_POST['cantidad']) {
			foreach($_POST['cantidad'] as $k=>$v) {
				$detalle_values[$k]['cantidad'] = $_POST['cantidad'][$k];
				$detalle_values[$k]['umedida'] = $_POST['umedida'][$k];
				$detalle_values[$k]['descripcion'] = ucwords(mb_strtolower($_POST['descripcion'][$k], 'UTF-8'));
			}
		}
		
		$req_values = array_map('strip_tags', $req_values);

		if(isset($_POST['iddetalle'])) {
			foreach($_POST['iddetalle'] as $k=>$v) {
				$detalle_values[$k]['iddetalle'] = $_POST['iddetalle'][$k];
			}
			save_requerimiento($idreq, $req_values);
			save_detalle_req($idreq, $detalle_values);
		}else{
			$id = save_requerimiento($idreq, $req_values);
			if($id) {
				save_detalle_req($id, $detalle_values);
			}
		}
		
	} 
	$idreq = 0;
}
$codgen = generate_code($bcdb->requerimientos, $_SESSION['loginuser']['iduser']);
if($idreq){ 
	$req = fill_req(get_requerimiento($idreq));
	$smarty->assign ('req', $req);
	if(count($req['detalle'])==1) {
		$smarty->assign ('nodel', true);
	}
}
	
//$smarty->assign ('atipos', $atipos);
//$smarty->assign ('projs', $projs);
//$smarty->assign ('provs', $provs);
//$smarty->assign ('fuentes', $fuentes);
//$smarty->assign ('docs', $docs);
//$smarty->assign ('especs', $especs);
$smarty->assign ('codgen', $codgen);

$smarty->assign ('section_title', TITLE . ' - Requerimientos');
$smarty->assign ('file', 'requerimientonew.html');
$smarty->display ('index.html');

?>