<?php

require_once('home.php');
require_once('redirect.php');

$cotizaciones = array();

$cotizaciones = get_cotizaciones();

if($cotizaciones){
     $cotizaciones = fill_cots($cotizaciones);
}

$smarty->assign ('cotizaciones', $cotizaciones);
$smarty->assign ('section_title', TITLE . ' - Cotizaciones');
$smarty->assign ('file', 'cotizacion-data.html');
$smarty->display ('index.html');

?>