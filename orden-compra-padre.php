<?php

require_once('home.php');
require_once('redirect.php');

if (isset($_REQUEST['idorden'])) { 
    $idorden = $_REQUEST['idorden'];
} else {
    $idorden = '0';
}

if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
    if ( validate_required(array(
            'C&oacute;digo O/C' => $_POST['codigo'],
            'RUC Proveedor' => $_POST['oruc'],
            'Facturar a' => $_POST['facturarto'],
            'RUC del proveedor' => ($_POST['fruc']),
            'Destino' => $_POST['destino']))) {

                $orden_values = array(
                        'codigo' => $_POST['codigo'],
                        'idproveedor' => get_idprov_by_ruc($_POST['oruc']),
                        'facturarto' => $_POST['facturarto'],
                        'fruc' => $_POST['fruc'],
                        'fecha' => fechita($_POST['fecha']),
                        'destino' => ($_POST['destino']),
                        'createdby' => $_SESSION['loginuser']['iduser']
                );

                $orden_values = array_map('strip_tags', $orden_values);

                $id = save_orden_comprapadre($idorden, $orden_values);
				
				safe_redirect('orden-compra-padre-hijos.php?idorden='. $id);
            }
    } 


// Toma el código de las ordenes de compra.
$codgen = generate_code($bcdb->ordencompra);

if($idorden){ 
	$orden = get_orden_compra($idorden);
	
	if(!$orden)  error();
	
	$orden = fill_compra($orden);
	if($orden['status'] == ORDEN_CONGELADA) {
		error();
	}
	
	$smarty->assign ('orden', $orden);
	if(count($orden['detalle'])==1) {
		$smarty->assign ('nodel', true);
	}
}

$smarty->assign ('codgen', $codgen);
$smarty->assign ('section_title', TITLE . ' - &Oacute;rdenes de Compra Padre');

$smarty->assign ('file', 'orden-compra-padre.html');
$smarty->display ('index.html');
?>
