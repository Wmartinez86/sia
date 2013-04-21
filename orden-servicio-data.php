<?php

require_once('home.php');
require_once('redirect.php');

$ordenes = array();

$ordenes = get_ordenes_servicio();

if($ordenes){
	$ordenes = fill_servicios($ordenes);
}

if($pager) $smarty->assign ('RESULTS', $bcrs->get_navigation());
$smarty->assign ('ordenes', $ordenes);

$smarty->assign ('section_title', TITLE . ' - &Oacute;rdenes de Servicio');
$smarty->assign ('file', 'orden-servicio-data.html');
if((stristr(MY_SITE, 'carhuayo'))) $smarty->assign('memo', TRUE);
$smarty->display ('index.html');

?>