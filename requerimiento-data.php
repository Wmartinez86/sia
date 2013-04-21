<?php

require_once('home.php');
require_once('redirect.php');

$requerimientos = array();

$pager = false;
$iduser = $_SESSION['loginuser']['iduser'];
if(is_admin($iduser))
    $requerimientos = get_requerimientos();
else
    $requerimientos = get_requerimientos_by_user($iduser);


if($requerimientos){
	$requerimientos = fill_reqs($requerimientos);
}

$smarty->assign ('requerimientos', $requerimientos);
$smarty->assign ('section_title', TITLE . ' - Requerimientos');
$smarty->assign ('file', 'requerimiento-data.html');
$smarty->display ('index.html');

?>