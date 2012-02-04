<?php

require_once('home.php');
require_once('redirect.php');

if (isset($_REQUEST['idnea'])) { 
    $idnea = $_REQUEST['idnea'];
} else {
    $idnea = '0';
}

if ( $_SERVER['REQUEST_METHOD'] == 'POST') {
	if ( validate_required(array(
		'C&oacute;digo O/C' => $_POST['codigo'],
		'Procedencia' => $_POST['procedencia'],
		'Fecha' => $_POST['fecha'],
		))) {
		
		$nea_values = array(
			'codigo' => $_POST['codigo'],
                        'procedencia' => $_POST['procedencia'],
                        'destino' => ($_POST['idproyecto']),
                        'observaciones' => ($_POST['observaciones']),
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
		
		$nea_values = array_map('strip_tags', $nea_values);
		
		if(isset($_POST['iddetalle'])) {
			foreach($_POST['iddetalle'] as $k=>$v) {
				$nea_values[$k]['iddetalle'] = $_POST['iddetalle'][$k];
			}
			save_nea($idnea, $nea_values);
			save_detalle_nea($idnea, $detalle_values);
		}else{
			$id = save_nea($idnea, $nea_values);
			if($id) {
				save_detalle_nea($id, $detalle_values);
			}
		}
	} 
	$idnea = 0;
}

$projs = get_projs();
$codgen = generate_code($bcdb->neas);

if($idnea){ 
	$nea = fill_nea(get_nea($idnea));
	$smarty->assign ('nea', $nea);
	if(count($nea['detalle'])==1) {
		$smarty->assign ('nodel', true);
	}
}
	
$smarty->assign ('projs', $projs);
$smarty->assign ('codgen', $codgen);

$smarty->assign ('section_title', TITLE . ' - Almacén');
$smarty->assign ('file', 'almacen.html');
$smarty->display ('index.html');

?>
