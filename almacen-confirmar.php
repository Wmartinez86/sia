<?php

require_once('home.php');
require_once('redirect.php');

$productos = array();

if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
        $op = $_POST['op'];
        switch($op) {
            case "confirm":
                
                $productos = array();
                $detalles = $_POST['detalles'];
                $salidas = $_POST['salidas'];
                
                foreach($detalles as $k => $iddetalle) {
                    $productos[$k]['producto'] = get_producto($iddetalle);
                    $productos[$k]['producto']['salida'] = $salidas[$k];
                    $productos[$k]['producto']['nuevosaldo'] = $productos[$k]['producto']['saldo']-$productos[$k]['producto']['salida'];
                }
                
                //d($productos);                
                break;
            case "save":
                break;
            default:
                error();
        }
}

$smarty->assign ('productos', $productos);
$smarty->assign ('section_title', TITLE . ' - Productos');
$smarty->assign ('file', 'almacen-confirmar.html');
$smarty->display ('index.html');

?>