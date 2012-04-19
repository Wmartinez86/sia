<?php

require_once('home.php');
require_once('redirect.php');

global $msg;

$projs = get_projs();

/* Búsqueda y Filtro */
if(isset($_GET['submit'])) {
    $smarty->assign('buscado', "buscado");
    $op = htmlspecialchars($_GET['op']);
    
    switch($op) {
        case 'search':
            $idproyecto = htmlspecialchars($_GET['idproyecto']);
            $pecosas = get_pecosas_by_proj($idproyecto);
            $smarty->assign('idproyecto', $idproyecto);
        break;
        case 'number':
            $codigo = htmlspecialchars($_GET['codigo']);
            $pecosas = get_pecosas_by_codigo($codigo);
            $smarty->assign('codigo', $codigo);
        break;
        case 'proveedor':
            $nombre = htmlspecialchars($_GET['nombre']);
            $pecosas = get_pecosas_by_prov($nombre, 'razonsocial');
            $smarty->assign('nombre', $nombre);
        break;
        case 'ruc':
            $ruc = htmlspecialchars($_GET['ruc']);
            $pecosas = get_pecosas_by_prov($ruc, 'ruc');
            $smarty->assign('ruc', $ruc);
        break;
        case 'producto':
            $producto = htmlspecialchars($_GET['producto']);
            $pecosas = get_pecosas_by_producto($producto);
            $smarty->assign('producto', $producto);
        break;
    }
    
    //d($ordenes);
} else {
    $pager = true;
    $pecosas = get_pecosas();
}

if($pecosas){
    $pecosas = fill_pecosas($pecosas);
}

//d($pecosas);
if($pager) $smarty->assign ('RESULTS', $bcrs->get_navigation());
$smarty->assign ('pecosas', $pecosas);
$smarty->assign ('projs', $projs);
$smarty->assign ('msg', $msg);
$smarty->assign ('section_title', TITLE . ' - Productos del almacén');
$smarty->assign ('file', 'pecosa-lista.html');
$smarty->display ('index.html');

?>
