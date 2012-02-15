<?php

require_once('home.php');
require_once('redirect.php');


$ordenes = array();
$projs = get_projs();
$users = get_admins();

if (isset($_REQUEST['idorden'])) { 
    $idorden = $_REQUEST['idorden'];
} 

/* Búsqueda y Filtro */
if(isset($_GET['submit'])) {
    $op = htmlspecialchars($_GET['op']);
    switch($op) {
        case 'search':
            $idproyecto = htmlspecialchars($_GET['idproyecto']);
            $iduser = "";
            $ordenes = search_orden_compra($idproyecto, $iduser);
            
            $smarty->assign('idproyecto', $idproyecto);
            $smarty->assign('iduser', $iduser);
            
        break;
        case 'number':
            $codigo = htmlspecialchars($_GET['codigo']);
            $ordenes = get_ordenes_by_codigo($codigo, 'compra');
            $smarty->assign('codigo', $codigo);
        break;
    }
} else {
    $pager = true;
    $ordenes = get_ordenes_compra("activas");
}

if($ordenes){
	$ordenes = fill_compras($ordenes);
}

if($pager) $smarty->assign ('RESULTS', $bcrs->get_navigation());
$smarty->assign ('ordenes', $ordenes);
$smarty->assign ('projs', $projs);
$smarty->assign ('section_title', TITLE . ' - &Oacute;rdenes de Compra');
$smarty->assign ('file', 'nea-orden.html');
$smarty->display ('index.html');

?>
