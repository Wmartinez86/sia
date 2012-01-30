<?php

require_once('home.php');
require_once('redirect.php');
$pager = true;

$requerimientos = array();
$iduser = $_SESSION['loginuser']['iduser'];

if(is_admin($iduser))
    $requerimientos = get_requerimientos();
else
    $requerimientos = get_requerimientos_by_user($iduser);


if($requerimientos){
	$requerimientos = fill_reqs($requerimientos);
}

$smarty->assign ('RESULTS', $bcrs->get_navigation());
$smarty->assign ('requerimientos', $requerimientos);
$smarty->assign ('section_title', TITLE . ' - Requerimientos');
$smarty->assign ('file', 'requerimiento-lista.html');
$smarty->display ('index.html');

?>