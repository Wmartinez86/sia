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
            $ordenes = search_orden_compra($idproyecto, $iduser);

            $smarty->assign('idproyecto', $idproyecto);
            $smarty->assign('iduser', $iduser);
            
        break;
        case 'number':
            $codigo = htmlspecialchars($_GET['codigo']);
            $ordenes = get_ordenes_by_codigo($codigo, 'compra');
            $smarty->assign('codigo', $codigo);
        break;
        case 'proveedor':
            $nombre = htmlspecialchars($_GET['nombre']);
            $ordenes = get_ordenes_by_nombre_prov($nombre, 'compra');
            $smarty->assign('nombre', $nombre);
        break;
        case 'ruc':
            $ruc = htmlspecialchars($_GET['ruc']);
            $ordenes = get_ordenes_by_ruc_prov($ruc, 'compra');
            $smarty->assign('ruc', $ruc);
        break;
    }
    
    //d($ordenes);
} else {
    $pager = true;
    $ordenes = get_ordenes_compra();
}

if($ordenes){
	$ordenes = fill_compras($ordenes);
        $atotal = 0;
		$field = 'total';
		foreach($ordenes as $k=>$v) {
			$ordenes[$k]['stotal'] = supertotal($v['detalle'], $field);
		}
}

//d($ordenes[0]['detalle']);
if($pager) $smarty->assign ('RESULTS', $bcrs->get_navigation());
$smarty->assign ('ordenes', $ordenes);
$smarty->assign ('projs', $projs);
$smarty->assign ('users', $users);
$smarty->assign ('section_title', TITLE . ' - &Oacute;rdenes de Compra');
$smarty->assign ('file', 'orden-compra-lista.html');
$smarty->display ('index.html');

?>
