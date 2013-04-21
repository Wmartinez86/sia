<?php

require_once('home.php');
require_once('redirect.php');


$ordenes = array();

if (isset($_REQUEST['idorden'])) { 
    $idorden = $_REQUEST['idorden'];
} 

$ordenes = get_ordenes_compra("activas");

if($ordenes){
	$ordenes = fill_compras($ordenes);
}

$smarty->assign ('ordenes', $ordenes);
$smarty->assign ('section_title', TITLE . ' - &Oacute;rdenes de Compra');
$smarty->assign ('file', 'nea-orden-data.html');
$smarty->display ('index.html');

?>
