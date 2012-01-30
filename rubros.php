<?php
	require_once("home.php");
	if ($_POST["type"]=="xml")	header ("content-type: text/xml");
	$idtipo = isset($_POST['idtipo']) ? $_POST['idtipo'] : 0;
	
	if($idtipo) {
		$projs = get_projs_by_tipo ($idtipo);
	}
	
	$smarty->assign ('projs', $projs);
	$smarty->display ('rubros.html');
?>