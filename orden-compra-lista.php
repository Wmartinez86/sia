<?php

require_once('home.php');
require_once('redirect.php');
$pager = true;

$ordenes = get_ordenes_compra();

if($ordenes){
	$ordenes = fill_compras($ordenes);
}

$smarty->assign ('RESULTS', $bcrs->get_navigation());
$smarty->assign ('ordenes', $ordenes);
$smarty->assign ('section_title', TITLE . ' - &Oacute;rdenes de Compra');
$smarty->assign ('file', 'orden-compra-lista.html');
$smarty->display ('index.html');

?>