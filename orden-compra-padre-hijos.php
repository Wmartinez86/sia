<?php

require_once('home.php');
require_once('redirect.php');

if (isset($_REQUEST['idorden'])) { 
    $idorden = $_REQUEST['idorden'];
} else {
    $idorden = '0';
}

if($idorden){ 
	$orden = get_orden_comprapadre($idorden);
	
	// Control
	if(!$orden)  error();
	if($orden['status'] == ORDEN_CONGELADA) {
		error();
	}
        
        $orden = fill_comprapadre($orden);
	
	$smarty->assign ('orden', $orden);
}

$smarty->assign ('section_title', TITLE . ' - &Oacute;rdenes de Compra Padre');

$smarty->assign ('file', 'orden-compra-padre-hijos.html');
$smarty->display ('index.html');
?>
