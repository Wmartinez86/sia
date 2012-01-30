<?php

require_once('home.php');
require_once('redirect.php');
$pager = true;

if (isset($_REQUEST['idarea'])) { 
    $idarea = $_REQUEST['idarea'];
} else {
    $idarea = '0';
}

if($idarea) {
	$smarty->assign ('area', get_area($idarea));
}

if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
	if ( validate_required(array(
		'Nombre' => $_POST['nombre'], 
		'Abreviatura' => $_POST['abreviatura']))) {
		
		$area_values = array(
			'nombre' => $_POST['nombre'],
			'abreviatura' => strtoupper($_POST['abreviatura']),
		);
		
		$area_values = array_map('strip_tags', $area_values);
		
		$id = save_area($idarea, $area_values);
		if($id) $idarea = 0;
	} else 
		$msg = "Ya existe un &Aacute;rea con el mismo nombre.";
}

$areas = get_areas();

$smarty->assign ('RESULTS', $bcrs->get_navigation());
$smarty->assign ('areas', $areas);
$smarty->assign ('msg', $msg);
$smarty->assign ('section_title', TITLE . ' - &Aacute;reas');
$smarty->assign ('file', 'areas.html');
$smarty->display ('index.html');

?>
