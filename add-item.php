<?php

require_once('home.php');
require_once('redirect.php');

$id = isset($_REQUEST['id']) ? (int)$_REQUEST['id'] : 0;
$entity = isset($_REQUEST['entity']) ? $_REQUEST['entity'] : '';


if($id == 0 || $entity == '') {
	header("Location: index.php");
	exit();
}

if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {

        $detalle_values = array();

        if($_POST['cantidad']) {
                foreach($_POST['cantidad'] as $k=>$v) {
                        $detalle_values[$k]['cantidad'] = $_POST['cantidad'][$k];
                        $detalle_values[$k]['umedida'] = $_POST['umedida'][$k];
                        $detalle_values[$k]['descripcion'] = $_POST['descripcion'][$k];
                }
        }
        
        $object = array();

        switch($entity):
            case 'requerimiento':
                save_detalle_req($id, $detalle_values);
                $object['id'] = 'idreq';
                $object['page'] = 'requerimientonew';
                break;
            case 'cotizacion':
                save_detalle_cot($id, $detalle_values);
                $object['id'] = 'idcot';
                $object['page'] = 'cotizacionnew';
                break;
            case 'compra':
                save_detalle_compra($id, $detalle_values);
                $object['id'] = 'idorden';
                $object['page'] = 'orden-compra';
                break;
            case 'servicio':
                save_detalle_servicio($id, $detalle_values);
                $object['id'] = 'idorden';
                $object['page'] = 'orden-servicio';
                break;
            case 'almacen':
                save_detalle_nea($id, $detalle_values);
                $object['id'] = 'idnea';
                $object['page'] = 'almacen';
                break;
        endswitch;
        header("Location: $object[page].php?$object[id]=$id");
}


$smarty->assign ('section_title', TITLE . ' - A&ntilde;adir Item');
$smarty->assign ('id', $id);
$smarty->assign ('entity', $entity);
$smarty->assign ('file', 'add-item.html');
$smarty->display ('index.html');

?>