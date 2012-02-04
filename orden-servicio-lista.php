<?php

require_once('home.php');
require_once('redirect.php');

$ordenes = array();
$projs = get_projs();
$users = get_admins();

/* Búsqueda y Filtro */
if(isset($_GET['submit'])) {
    $op = htmlspecialchars($_GET['op']);
    switch($op) {
        case 'search':
            if(!is_admin()) {
                header("location: error.php");
                exit();
            }
            $idproyecto = htmlspecialchars($_GET['idproyecto']);
            $iduser = htmlspecialchars($_GET['iduser']);
            $ordenes = search_orden_servicio($idproyecto, $iduser);
            
            $smarty->assign('idproyecto', $idproyecto);
            $smarty->assign('iduser', $iduser);
            
        break;
        case 'number':
            $codigo = htmlspecialchars($_GET['codigo']);
            $ordenes = get_ordenes_by_codigo($codigo, 'servicio');
            $smarty->assign('codigo', $codigo);
        break;
    }
} else {
    $pager = true;
    $ordenes = get_ordenes_servicio();
}

if($ordenes){
	$ordenes = fill_servicios($ordenes);
}

if($pager) $smarty->assign ('RESULTS', $bcrs->get_navigation());
$smarty->assign ('ordenes', $ordenes);
$smarty->assign ('projs', $projs);
$smarty->assign ('users', $users);
$smarty->assign ('section_title', TITLE . ' - &Oacute;rdenes de Servicio');
$smarty->assign ('file', 'orden-servicio-lista.html');
$smarty->display ('index.html');

?>