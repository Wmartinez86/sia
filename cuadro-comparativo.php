<?php

require_once('home.php');
require_once('redirect.php');

if (isset($_REQUEST['idcuadro'])) { 
    $idcuadro = $_REQUEST['idcuadro'];
} else {
    $idcuadro = '0';
}

if (isset($_REQUEST['idcot'])) { 
    $idcot = $_REQUEST['idcot'];
} else {
    $idcot = '0';
}
//$idcot = ! empty($_REQUEST['idcot']) ? (int)$idcot : 0;
//$idcuadro = ! empty($_REQUEST['idcuadro']) ? (int)$idcuadro : 0;
if($idcot){
$idcuadro= get_cuadro_cot($idcot);
}
$idprov4= ! empty($_REQUEST['idprov4']) ? (int)$idprov4 : 0;

if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
	if ( validate_required(array(
//		'Ruc del cuarto Proveedor' => $_POST['oruc4'], 
		'Fecha del Cuadro' => $_POST['fechaO'], 
		'Fecha del Cotizacion' => $_POST['fecha'], 
		'Quienes Firmaran el Acta' => $_POST['cep'], 
		'RUC del 1er Postor' => $_POST['oruc1'],
		'RUC del 2do Postor' => $_POST['oruc2'],
		'RUC del 3er Postor' => $_POST['oruc3'],		
		'Plazo de Entrega del 1er Postor' => $_POST['plazo1'],
		'Plazo de Entrega del 2do Postor' => $_POST['plazo2'],
		'Plazo de Entrega del 3er Postor' => $_POST['plazo3'])))
		{
			$prov1_values = array(
				'idprov' => isset($_POST['idprov1']) ? $_POST['idprov1'] : 0,
				'idproveedor' => get_idprov_by_ruc($_POST['oruc1']),
				'plazo' => $_POST['plazo1'],
				'fecha' => fechita($_POST['fecha1']),
				);
			$prov2_values = array(
				'idprov' => isset($_POST['idprov2']) ? $_POST['idprov2'] : 0,
				'idproveedor' => get_idprov_by_ruc($_POST['oruc2']),
				'plazo' => $_POST['plazo2'],
				'fecha' => fechita($_POST['fecha2'])
				);
			$prov3_values = array(
				'idprov' => isset($_POST['idprov3']) ? $_POST['idprov3'] : 0,
				'idproveedor' => get_idprov_by_ruc($_POST['oruc3']),
				'plazo' => $_POST['plazo3'],
				'fecha' => fechita($_POST['fecha3'])
				);

			$cuadro_values = array(
				'idcot' => $_POST['idcot'],
				'fecha' => fechita($_POST['fechaO']),
				'idproveedor' => isset($_POST['adjudicado'])?$_POST['adjudicado']:'1',
				'justificacion' => $_POST['justificacion'],
				'Observacion' => $_POST['observacion'],
				'cep' => $_POST['cep']
				);
			if($_POST['precio1']) {
				foreach($_POST['idprecio1'] as $k=>$v) {
					if(isset($_POST['idprecio1'])) {
						$precio1_values[$k]['idprecio'] = $_POST['idprecio1'][$k];	
					}				
					$precio1_values[$k]['iddetalle'] = $_POST['iddetalle'][$k];
					$precio1_values[$k]['precio'] = $_POST['precio1'][$k];
				}
			}
			
			if($_POST['precio2']) {
				foreach($_POST['precio2'] as $k=>$v) {
					if(isset($_POST['idprecio2'])) {
						$precio2_values[$k]['idprecio'] = $_POST['idprecio2'][$k];
					}				
					$precio2_values[$k]['iddetalle'] = $_POST['iddetalle'][$k];
					$precio2_values[$k]['precio'] = $_POST['precio2'][$k];
				}
			}
			if($_POST['precio3']) {
				foreach($_POST['precio3'] as $k=>$v) {
					if(isset($_POST['idprecio3'])) {
						$precio3_values[$k]['idprecio'] = $_POST['idprecio3'][$k];
					}				
					$precio3_values[$k]['iddetalle'] = $_POST['iddetalle'][$k];
					$precio3_values[$k]['precio'] = $_POST['precio3'][$k];
				}
			}
		if(isset($_POST['idcuadro'])) {
			$idcuadro=$_POST['idcuadro'];
		}
		save_cuadro($idcuadro, $cuadro_values);
		
			if(($_POST['oruc1'])!=0){
				save_cuadro_proveedor($idcot, $prov1_values, '1');				
				save_cuadro_precios('1', $precio1_values);
			}
			if(($_POST['oruc2'])!=0){
				save_cuadro_proveedor($idcot, $prov2_values, '2');
				save_cuadro_precios('2', $precio2_values);
			}
			if(($_POST['oruc3'])!=0){
				save_cuadro_proveedor($idcot, $prov3_values, '3');
				save_cuadro_precios('3', $precio3_values);
			}
	} 
	$idcuadro = 0;
}

if($idcot){ 
	$cot = fill_cot_cuadro(get_cotizacion($idcot));
	$smarty->assign ('cot', $cot);
	if(count($cot['detalle'])==1) {
		$smarty->assign ('nodel', true);
	}
}
if($idcuadro){
	$cuadro = get_cuadro($idcuadro);
	if($cuadro){
		$cuadro['fecha']=fechita2($cuadro['fecha']);
	}
	$smarty->assign ('cuadro', $cuadro);
}
$smarty->assign ('section_title', TITLE . ' - Cotizaciones');
$smarty->assign ('file', 'cuadro-comparativo.html');
$smarty->display ('index.html');
?>
