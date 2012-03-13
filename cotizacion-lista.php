<?php

require_once('home.php');
require_once('redirect.php');

$cotizaciones = array();
$projs = get_projs();
$users = get_users();

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
            $cotizaciones = search_cotizacion($idproyecto, $iduser);
            
            $smarty->assign('idproyecto', $idproyecto);
            $smarty->assign('iduser', $iduser);
            
        break;
        case 'number':
            $codigo = htmlspecialchars($_GET['codigo']);
            $cotizaciones = get_cotizaciones_by_codigo($codigo);
            $smarty->assign('codigo', $codigo);
        break;
        case 'referencia':
            $referencia = htmlspecialchars($_GET['referencia']);
            $cotizaciones = get_cotizaciones_by_referencia($referencia);
            $smarty->assign('referencia', $referencia);
        break;
    }
} else {

    $pager = true;
    $cotizaciones = get_cotizaciones();
}

if($cotizaciones){
     $cotizaciones = fill_cots($cotizaciones);
}
//d($referencia);
if($pager) $smarty->assign ('RESULTS', $bcrs->get_navigation());
$smarty->assign ('cotizaciones', $cotizaciones);
$smarty->assign ('projs', $projs);
$smarty->assign ('users', $users);
$smarty->assign ('section_title', TITLE . ' - Cotizaciones');
$smarty->assign ('file', 'cotizacion-lista.html');
$smarty->display ('index.html');

?>