<?php

require_once('home.php');
require_once('redirect.php');

$pager = true;
$provs=array();

if(isset($_GET['submit'])) {
    $smarty->assign('buscado', "buscado");
    $pager = false;
    $op = htmlspecialchars($_GET['op']);
    switch($op) {
        case 'search':
            if(!is_admin()) {
                header("location: error.php");
                exit();
            }
            $idproyecto = htmlspecialchars($_GET['idproyecto']);
            $provs = search_proveedor($idproyecto, $iduser);
        break;
        case 'proveedor':
            $nombre = htmlspecialchars($_GET['nombre']);
            $provs = get_proveedor_by_nombre_prov($nombre, 'compra');
            //d($provs);
            $smarty->assign('nombre', $nombre);
        break;
        case 'number':
            //d($ruc);
            $ruc = htmlspecialchars($_GET['ruc']);
            $provs = get_proveedor_by_ruc_prov($ruc);
//            d($provs);
            $smarty->assign('ruc', $ruc);
        break;
    }
} else {
    $provs = get_provs();
}

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
			'Razónn Social' => $_POST['razonsocial'],
			'Dirección' => $_POST['direccion'], 
			'RUC' => $_POST['ruc']))) {
			
			$prov_values = array(
				'razonsocial' => $_POST['razonsocial'],
				'direccion' => $_POST['direccion'],
				'telefono' => $_POST['telefono'],
				'email' => $_POST['email'],
				'ruc' => $_POST['ruc']
			);
			$prov_values['razonsocial']=$prov_values['razonsocial'];
			$prov_values['direccion']=$prov_values['direccion'];
			$prov_values = array_map('strip_tags', $prov_values);
			
			$id = save_prov($idproveedor, $prov_values);
			if($id) $idproveedor = 0;
		} else 
			$msg = "Ya existe un proveedor con el mismo RUC.";
	}
	
	$disp = (!$popup) ? 'index.html' : 'proveedores-popup.html';
	if($pager)$smarty->assign ('RESULTS', $bcrs->get_navigation());
	$smarty->assign ('provs', $provs);
	$smarty->assign ('msg', $msg);
	$smarty->assign ('section_title', TITLE . ' - Proveedores');
	$smarty->assign ('file', 'proveedores.html');
	$smarty->display ($disp);

else :
	$smarty->display ('proveedor-print.html');
endif;

?>
