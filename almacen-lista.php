<?php

require_once('home.php');
require_once('redirect.php');


$neas = array();
$projs = get_projs();

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
            $neas = get_neas_by_destino($idproyecto);
            $smarty->assign('iduser', $iduser);
            
        break;
    }
} else {
    $pager = true;
    $neas = get_neas();
}

if($neas){
	$neas = fill_neas($neas);
}

if($pager) $smarty->assign ('RESULTS', $bcrs->get_navigation());
$smarty->assign ('neas', $neas);
$smarty->assign ('projs', $projs);
$smarty->assign ('section_title', TITLE . ' - Productos del almacén');
$smarty->assign ('file', 'almacen-lista.html');
$smarty->display ('index.html');

?>
