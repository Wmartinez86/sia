<?php

require_once('home.php');
require_once('redirect.php');

if (isset($_REQUEST['idproyecto'])) { 
    $idproyecto = $_REQUEST['idproyecto'];
} else {
    $idproyecto = '0';
}

if($idproyecto) {
	$proj = get_proj($idproyecto);
	$smarty->assign ('proj', $proj);
}

if (!isset($_GET['print'])) :

	if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
		if ( validate_required(array(
			'sec_func' => $_POST['sec_func'], 
			'programa' => $_POST['programa'],
			'prod_pry' => $_POST['prod_pry'],
			'act_ai_obra' => $_POST['act_ai_obra'],
			'funcion' => $_POST['funcion'],
			'division_func' => $_POST['division_func'],
                        'grupo_func' => $_POST['grupo_func'],
                        'meta' => $_POST['meta'],
                        'finalidad' => $_POST['finalidad'],
                        'descripcion' => $_POST['descripcion'],
                        ))) {
			
			$proj_values = array(
				'sec_func' => (string)$_POST['sec_func'], 
                                'programa' => (string)$_POST['programa'],
                                'prod_pry' => (string)$_POST['prod_pry'],
                                'act_ai_obra' => (string)$_POST['act_ai_obra'],
                                'funcion' => (string)$_POST['funcion'],
                                'division_func' => (string)$_POST['division_func'],
                                'grupo_func' => (string)$_POST['grupo_func'],
                                'meta' => (string)$_POST['meta'],
                                'finalidad' => (string)$_POST['finalidad'],
                                'descripcion' => ucwords(mb_strtolower($_POST['descripcion'], 'UTF-8')),
			);
			
			$proj_values = array_map('strip_tags', $proj_values);
			
			$id = save_proj($idproyecto, $proj_values);
			if($id) {
				$idproyecto = 0;
				$smarty->assign ('proj', '');
			}
		} 
	}
	
	$projs = get_projs();
	
	if(isset($_GET['excel'])) {
		header ("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT-5");
		header ("Cache-Control: no-cache, must-revalidate");
		header ("Pragma: no-cache");
		header ("Content-type: application/x-msexcel");
		header ("Content-Disposition: attachment; filename=proyectos.xls" );
		$smarty->assign ('projs', $projs);
		$xls = $smarty->fetch ('proyectos-excel.html');
		die($xls);
	}
	
	$smarty->assign ('projs', $projs);
	$smarty->assign ('msg', $msg);
	$smarty->assign ('section_title', TITLE . ' - Proyectos');
	$smarty->assign ('file', 'proyectos.html');
	$smarty->display ('index.html');

else :
	$smarty->display ('proyecto-print.html');

endif;

?>
