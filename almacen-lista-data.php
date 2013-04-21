<?php

require_once('home.php');
require_once('redirect.php');


$neas = array();

$neas = get_neas();

if($neas){
	$neas = fill_neas($neas);
}

$smarty->assign ('neas', $neas);
$smarty->assign ('section_title', TITLE . ' - Productos del almacén');
$smarty->assign ('file', 'almacen-lista-data.html');
$smarty->display ('index.html');

?>
