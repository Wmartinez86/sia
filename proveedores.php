<?php

require_once('home.php');
require_once('redirect.php');
$pager = true;

if (isset($_REQUEST['idproveedor'])) { 
    $idproveedor = $_REQUEST['idproveedor'];
} else {
    $idproveedor = '0';
}

//$idproveedor = ! empty($_REQUEST['idproveedor']) ? (int)$idproveedor : 0;
$popup = isset($_REQUEST['popup']);

if($idproveedor) {
	$smarty->assign ('prov', get_prov($idproveedor));
}

if (!isset($_GET['print'])) :

	if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
		$cond = isset($_POST['getbyruc']);
		if($cond) {
			$prov = get_prov_by_ruc($_POST['ruc']);
			if($prov)
				die("<strong>Proveedor</strong>: " . $prov['razonsocial']);
			else
				die('El proveedor no existe. <a href="proveedores.php?popup" onclick="pop(\'proveedores.php?popup\',600,430);return false;">A&ntilde;adir Proveedor</a>');
		}
		if ( validate_required(array(
			'Raz�n Social' => $_POST['razonsocial'],
			'Direcci�n' => $_POST['direccion'], 
			'RUC' => $_POST['ruc']))) {
			
			$prov_values = array(
				'razonsocial' => $_POST['razonsocial'],
				'direccion' => $_POST['direccion'],
				'telefono' => $_POST['telefono'],
				'email' => $_POST['email'],
				'ruc' => $_POST['ruc']
			);
			$prov_values['razonsocial']=ucwords(mb_strtolower($prov_values['razonsocial'], 'UTF-8'));
			$prov_values['direccion']=ucwords(mb_strtolower($prov_values['direccion'], 'UTF-8'));
			$prov_values = array_map('strip_tags', $prov_values);
			
			$id = save_prov($idproveedor, $prov_values);
			if($id) $idproveedor = 0;
		} else 
			$msg = "Ya existe un proveedor con el mismo RUC.";
	}
	
	$provs = get_provs();
	
	if(isset($_GET['excel'])) {
		header ("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT-5");
		header ("Cache-Control: no-cache, must-revalidate");
		header ("Pragma: no-cache");
		header ("Content-type: application/x-msexcel");
		header ("Content-Disposition: attachment; filename=proveedores.xls" );
		$smarty->assign ('provs', $provs);
		$xls = $smarty->fetch ('proveedores-excel.html');
		die($xls);
	}
	$disp = (!$popup) ? 'index.html' : 'proveedores-popup.html';
	$smarty->assign ('RESULTS', $bcrs->get_navigation());
	$smarty->assign ('provs', $provs);
	$smarty->assign ('msg', $msg);
	$smarty->assign ('section_title', TITLE . ' - Proveedores');
	$smarty->assign ('file', 'proveedores.html');
	$smarty->display ($disp);

else :
	$smarty->display ('proveedor-print.html');
endif;

?>
