<?php

require_once('home.php');
require_once('redirect.php');

$requerimientos = array();
$projs = get_projs();
$users = get_users();

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
            $requerimientos = search_requerimiento($idproyecto, $iduser);
            
            $smarty->assign('idproyecto', $idproyecto);
            $smarty->assign('iduser', $iduser);
            
        break;
        case 'number':
            $codigo = htmlspecialchars($_GET['codigo']);
            $requerimientos = get_requerimientos_by_codigo($codigo);
            $smarty->assign('codigo', $codigo);
        break;
    }
} else {

    $pager = true;
    $iduser = $_SESSION['loginuser']['iduser'];
    if(is_admin($iduser))
        $requerimientos = get_requerimientos();
    else
        $requerimientos = get_requerimientos_by_user($iduser);

}

if($requerimientos){
	$requerimientos = fill_reqs($requerimientos);
}

if($pager) $smarty->assign ('RESULTS', $bcrs->get_navigation());

$smarty->assign ('requerimientos', $requerimientos);
$smarty->assign ('projs', $projs);
$smarty->assign ('users', $users);
$smarty->assign ('section_title', TITLE . ' - Requerimientos');
$smarty->assign ('file', 'requerimiento-lista.html');
$smarty->display ('index.html');

?>