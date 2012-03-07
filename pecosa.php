<?php

require_once('home.php');
require_once('redirect.php');

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
                

		$pecosa_values = array_map('strip_tags', $pecosa_values);
		
		if($idpecosa != 0) {
			save_pecosa($idpecosa, $pecosa_values);
		}
	}
        $msg = "Se guardaron los cambios";
        safe_redirect("pecosa-lista.php");
	$idpecosa = 0;
}

if($idpecosa){ 
	$pecosa = fill_pecosa(get_pecosa($idpecosa));
	$smarty->assign ('pecosa', $pecosa);
} else {
    error();
}

/**
 * Si se va a crear desde una orden de compra
 */
$smarty->assign ('section_title', TITLE . ' - PECOSA');
$smarty->assign ('file', 'pecosa.html');
$smarty->display ('index.html');

?>
