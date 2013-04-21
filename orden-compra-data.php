<?php

require_once('home.php');
require_once('redirect.php');


$ordenes = array();

$ordenes = get_ordenes_compra();

if($ordenes){
	$ordenes = fill_compras($ordenes);
        $atotal = 0;
		$field = 'total';
		foreach($ordenes as $k=>$v) {
			$ordenes[$k]['stotal'] = supertotal($v['detalle'], $field);
		}
}

$smarty->assign ('ordenes', $ordenes);

$smarty->assign ('section_title', TITLE . ' - &Oacute;rdenes de Compra');
$smarty->assign ('file', 'orden-compra-data.html');
if((stristr(MY_SITE, 'carhuayo'))) $smarty->assign('memo', TRUE); 
$smarty->display ('index.html');

?>