<?php

require_once('home.php');
require_once('redirect.php');
$pager = true;

if (isset($_REQUEST['idfuente'])) { 
    $idfuente = $_REQUEST['idfuente'];
} else {
    $idfuente = '0';
}

$idfuente = ! empty($_REQUEST['idfuente']) ? (int)$idfuente : 0;

	if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
		if ( validate_required(array(
			'Fuente' => $_POST['nombre'],
			'Abbre.' => $_POST['codigo'],))) {
			
			$fuente_values = array(
				'nombre' => $_POST['nombre'],
				'codigo' => $_POST['codigo'],
			);
			
			$fuente_values = array_map('strip_tags', $fuente_values);
			
			$id = save_fuente($idfuente, $fuente_values);
			if($id) $idfuente = 0;
		} else 
			$msg = "Ya existe la fuente '$fuente_values[nombre]'.";
	}
	
	$fuentes = get_fuentes();
	
	if($idfuente) {
		$smarty->assign ('fuente', get_fuente($idfuente));
	}
	$smarty->assign ('RESULTS', $bcrs->get_navigation());
	$smarty->assign ('fuentes', $fuentes);
	$smarty->assign ('msg', $msg);
	$smarty->assign ('section_title', TITLE . ' - Fuentes de financiamiento');
	$smarty->assign ('file', 'fuentes.html');
	$smarty->display ('index.html');

?>