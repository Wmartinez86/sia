<?php

require_once('home.php');
require_once('redirect.php');
$pager = true;

if (isset($_REQUEST['iddoc'])) { 
    $idcot = $_REQUEST['iddoc'];
} else {
    $iddoc = '0';
}

//$iddoc = ! empty($_REQUEST['iddoc']) ? (int)$iddoc : 0;

	if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
		if ( validate_required(array(
			'Nombre' => $_POST['nombre']))) {
			
			$doc_values = array(
				'nombre' => $_POST['nombre']
			);
			
			$doc_values = array_map('strip_tags', $doc_values);
			
			$id = save_doc($iddoc, $doc_values);
			if($id) $iddoc = 0;
		} else 
			$msg = "Ya existe el tipo '$doc_values[nombre]'.";
	}
	
	$docs = get_docs();
	
	if($iddoc) {
		$smarty->assign ('doc', get_doc($iddoc));
	}
	$smarty->assign ('RESULTS', $bcrs->get_navigation());
	$smarty->assign ('docs', $docs);
	$smarty->assign ('msg', $msg);
	$smarty->assign ('section_title', TITLE . ' - Tipos de documento');
	$smarty->assign ('file', 'docs.html');
	$smarty->display ('index.html');

?>
