<?php

require_once('home.php');
require_once('redirect.php');

$ordenes = array();
$projs = get_projs();
$users = get_admins();

/* Búsqueda y Filtro */
if(isset($_GET['submit'])) {
    $smarty->assign('buscado', "buscado");    
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
        case 'proveedor':
            $nombre = htmlspecialchars($_GET['nombre']);
            $ordenes = get_ordenes_by_nombre_prov($nombre, 'servicio');
            $smarty->assign('nombre', $nombre);
        break;
        case 'ruc':
            $ruc = htmlspecialchars($_GET['ruc']);
            $ordenes = get_ordenes_by_ruc_prov($ruc, 'servicio');
            $smarty->assign('ruc', $ruc);
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