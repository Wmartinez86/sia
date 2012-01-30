<?php

require_once('home.php');
require_once('redirect.php');
$pager = true;

if (isset($_REQUEST['idespec'])) { 
    $idespec = $_REQUEST['idespec'];
} else {
    $idespec = '0';
}

//$idespec = ! empty($_REQUEST['idespec']) ? (int)$idespec : 0;

	if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
		if ( validate_required(array(
			'C&oacute;digo' => $_POST['codigo'],
			'Nombre' => $_POST['nombre']))) {
			
			$especifica_values = array(
			    'codigo' => $_POST['codigo'],
				'nombre' => $_POST['nombre']
			);
			
			$especifica_values = array_map('strip_tags', $especifica_values);
			
			$id = save_especifica($idespec, $especifica_values);
			if($id) $idespec = 0;
		} else 
			$msg = "Ya existe la Especifica '$especifica_values[nombre]'.";
	}
	
	$especificas = get_especificas();
	
	if($idespec) {
		$smarty->assign ('especifica', get_especifica($idespec));
	}
	$smarty->assign ('RESULTS', $bcrs->get_navigation());
	$smarty->assign ('especificas', $especificas);
	$smarty->assign ('msg', $msg);
	$smarty->assign ('section_title', TITLE . ' - Espec&iacute;ficas');
	$smarty->assign ('file', 'especificas.html');
	$smarty->display ('index.html');

?>