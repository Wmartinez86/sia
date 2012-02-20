<?php

require_once('home.php');
require_once('redirect.php');

$productos = array();
$projs = get_projs();

if(isset($_GET['submit'])) {
    $op = htmlspecialchars($_GET['op']);
    switch($op) {
        case 'proyecto':
            $idproyecto = htmlspecialchars($_GET['idproyecto']);
            if($idproyecto == 1) {
                $productos = get_productos_almacen();
            }else {
                $productos = get_productos_by_proyecto($idproyecto);
            }
            
            $smarty->assign('idproyecto', $idproyecto);
        break;
        case 'number':
            $codigo = htmlspecialchars($_GET['codigo']);
            $productos = get_productos_by_codigo($codigo);
            $smarty->assign('codigo', $codigo);
        break;
    }
}

$smarty->assign ('projs', $projs);
$smarty->assign ('productos', $productos);
$smarty->assign ('section_title', TITLE . ' - Productos');
$smarty->assign ('file', 'almacen-quitar.html');
$smarty->display ('index.html');

?>