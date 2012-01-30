<?php

require_once('home.php');
require_once('redirect.php');
$pager = true;

$ordenes = get_ordenes_servicio();

if($ordenes){
	$ordenes = fill_servicios($ordenes);
}

$smarty->assign ('RESULTS', $bcrs->get_navigation());
$smarty->assign ('ordenes', $ordenes);
$smarty->assign ('section_title', TITLE . ' - &Oacute;rdenes de Servicio');
$smarty->assign ('file', 'orden-servicio-lista.html');
$smarty->display ('index.html');

?>