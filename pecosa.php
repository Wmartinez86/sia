<?php

require_once('home.php');
require_once('redirect.php');

if (isset($_REQUEST['idnea'])) { 
    $idnea = $_REQUEST['idnea'];
} else {
    $idnea = '0';
}

if (isset($_REQUEST['idpecosa'])) { 
    $idpecosa = $_REQUEST['idpecosa'];
} else {
    $idpecosa = '0';
}

if ( $_SERVER['REQUEST_METHOD'] == 'POST') {
	if ( validate_required(array(
		'C&oacute;digo' => $_POST['codigo'],
		'Dependencia' => $_POST['dependencia'],
		'Entregar a' => $_POST['entregar'],
		'Destino' => $_POST['destino'],
		'Fecha' => $_POST['fecha'],
		))) {
		
		$pecosa_values = array(
			'codigo' => $_POST['codigo'],
                        'dependencia' => $_POST['dependencia'],
                        'destino' => ($_POST['destino']),
                        'entregar' => ($_POST['entregar']),
			'fecha' => fechita($_POST['fecha']),
			'createdby' => $_SESSION['loginuser']['iduser']
		);
                

		$detalle_values = array();
		if($_POST['especifica']) {
			foreach($_POST['especifica'] as $k=>$v) {
				$detalle_values[$k]['especifica'] = $_POST['especifica'][$k];
				$detalle_values[$k]['cantidad'] = $_POST['cantidad'][$k];
				$detalle_values[$k]['umedida'] = $_POST['umedida'][$k];
				$detalle_values[$k]['descripcion'] = $_POST['descripcion'][$k];
				$detalle_values[$k]['precio'] = $_POST['precio'][$k];
			}
		}
		
		$pecosa_values = array_map('strip_tags', $pecosa_values);
		
		if(isset($_POST['iddetalle']) && $idpecosa != 0) {
			foreach($_POST['iddetalle'] as $k=>$v) {
				$pecosa_values[$k]['iddetalle'] = $_POST['iddetalle'][$k];
			}
			save_pecosa($idpecosa, $pecosa_values);
			save_detalle_pecosa($idpecosa, $detalle_values);
		}else{
			$id = save_pecosa($idpecosa, $pecosa_values);
			if($id) {
				save_detalle_pecosa($id, $detalle_values);
			}
		}
	} 
	$idpecosa = 0;
}

$codgen = generate_code($bcdb->pecosa);

if($idnea){ 
	$nea = fill_nea(get_nea($idnea));
	$smarty->assign ('nea', $nea);
	if(count($nea['detalle'])==1) {
		$smarty->assign ('nodel', true);
	}
}

if($idpecosa){ 
	$pecosa = fill_pecosa(get_pecosa($idpecosa));
	$smarty->assign ('pecosa', $pecosa);
	if(count($pecosa['detalle'])==1) {
		$smarty->assign ('nodel', true);
	}
}

/**
 * Si se va a crear desde una orden de compra
 */
if(isset($idorden)){ 
        $orden = fill_compra(get_orden_compra($idorden));
	$nea = fill_nea_by_orden($orden);
	$smarty->assign ('nea', $nea);
        $smarty->assign ('orden', $orden);
}
	
$smarty->assign ('codgen', $codgen);
$smarty->assign ('section_title', TITLE . ' - PECOSA');
$smarty->assign ('file', 'pecosa.html');
$smarty->display ('index.html');

?>
