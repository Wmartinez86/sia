<?php



require_once('home.php');

require_once('redirect.php');



if (isset($_REQUEST['idorden'])) { 

    $idorden = $_REQUEST['idorden'];

} else {

    $idorden = '0';

}



if (isset($_REQUEST['idcot'])) { 

    $idcot = $_REQUEST['idcot'];

} 

//$idorden = ! empty($_REQUEST['idorden']) ? (int)$idorden : 0;



if(isset($_GET['cancel'])) {

	cancel_order($idorden, "ordencompra");

	header("Location: orden-compra-lista.php");

	exit();

}



if(isset($_GET['activate'])) {

	activate_order($idorden, "ordencompra");

	header("Location: orden-compra-lista.php");

	exit();

}



$projs = get_projs();

$provs = get_provs();

$fuentes = get_fuentes();

$docs = get_docs();

$especs = get_especificas();



if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {

	if ( validate_required(array(

		'Nro. Referencia' => $_POST['nrodoc'], 

		'C&oacute;digo O/C' => $_POST['codigo'],

		'RUC Proveedor' => $_POST['oruc'],

		'Facturar a' => $_POST['facturarto'],

		'RUC del proveedor' => ($_POST['fruc']),

		'Destino' => $_POST['destino']))) {

		

		$orden_values = array(

			'iddoc' => $_POST['iddoc'],

			'nrodoc' => $_POST['nrodoc'], 

			'codigo' => $_POST['codigo'],

			'idproyecto' => $_POST['idproyecto'],

			'idfuente' => $_POST['idfuente'],

			'idproveedor' => get_idprov_by_ruc($_POST['oruc']),

			'facturarto' => $_POST['facturarto'],

			'fruc' => $_POST['fruc'],

			'fecha' => fechita($_POST['fecha']),

			'destino' => ($_POST['destino']),

			'createdby' => $_SESSION['loginuser']['iduser']

		);

		

		$detalle_values = array();

		if($_POST['especifica']) {

			foreach($_POST['especifica'] as $k=>$v) {

				$detalle_values[$k]['especifica'] = $_POST['especifica'][$k];

				$detalle_values[$k]['cantidad'] = $_POST['cantidad'][$k];

				$detalle_values[$k]['umedida'] = $_POST['umedida'][$k];

				$detalle_values[$k]['descripcion'] = ucwords(mb_strtolower($_POST['descripcion'][$k], 'UTF-8'));

				$detalle_values[$k]['precio'] = $_POST['precio'][$k];

			}

		}

		

		$orden_values = array_map('strip_tags', $orden_values);

		

		if(isset($_POST['iddetalle'])) {

			foreach($_POST['iddetalle'] as $k=>$v) {

				$detalle_values[$k]['iddetalle'] = $_POST['iddetalle'][$k];

			}

			save_orden_compra($idorden, $orden_values);

			save_detalle_compra($idorden, $detalle_values);

		}else{

			$id = save_orden_compra($idorden, $orden_values);

			if($id) {

				save_detalle_compra($id, $detalle_values);

			}

		}

	} 

	$idorden = 0;

}

$codgen = generate_code($bcdb->ordencompra);

//	$orden = fill_compra_by_cot(get_cotizacion($idcot));



if(isset($idcot)){

	$orden = fill_compra_by_cot(get_cotizacion($idcot));

        $orden['fecha']=NULL;

	$smarty->assign ('orden', $orden);

	$smarty->assign ('fcot', true);

	}



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



$especial = (isset($_GET['especial'])) ? $_GET['especial'] : false;



$smarty->assign ('atipos', $atipos);

$smarty->assign ('projs', $projs);

$smarty->assign ('provs', $provs);

$smarty->assign ('fuentes', $fuentes);

$smarty->assign ('docs', $docs);

$smarty->assign ('especs', $especs);

$smarty->assign ('codgen', $codgen);

$smarty->assign ('especial', $especial);



$smarty->assign ('section_title', TITLE . ' - &Oacute;rdenes de Compra');

if($especial == 1)

    $smarty->assign ('file', 'orden-compra-especial.html');

else

    $smarty->assign ('file', 'orden-compra.html');

$smarty->display ('index.html');



?>

