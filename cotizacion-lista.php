<?php

require_once('home.php');
require_once('redirect.php');
$pager = true;

$cotizaciones = get_cotizaciones();

if($cotizaciones){
	$cotizaciones = fill_cots($cotizaciones);
}

$smarty->assign ('RESULTS', $bcrs->get_navigation());
$smarty->assign ('cotizaciones', $cotizaciones);
$smarty->assign ('section_title', TITLE . ' - Cotizaciones');
$smarty->assign ('file', 'cotizacion-lista.html');
$smarty->display ('index.html');

?>