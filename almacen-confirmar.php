<?php

require_once('home.php');
require_once('redirect.php');

$productos = array();

if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
        $op = $_POST['op'];
        switch($op) {
            case "confirm":
                
                $detalles = $_POST['detalles'];
                $salidas = $_POST['salidas'];
                
                foreach($detalles as $k => $iddetalle) {
                    $productos[$k]['producto'] = get_producto($iddetalle);
                    $productos[$k]['producto']['salida'] = $salidas[$k];
                    $productos[$k]['producto']['nuevosaldo'] = $productos[$k]['producto']['saldo']-$productos[$k]['producto']['salida'];
                }
                
                // Orden Random
                $ranorden = $productos[0]['producto']['idorden'];
                
                if($ranorden) {
                    $orden = get_orden_compra($ranorden);
                    $proj = get_proj($orden['idproyecto']);
                    $smarty->assign('proj', $proj);
                }
                break;
            case "save":
                
                $detalles = $_POST['detalles'];
                $salidas = $_POST['salidas'];
                
                $pecosa_values = array(
			'codigo' => $_POST['codigo'],
			'dependencia' => $_POST['dependencia'],
			'entregar' => $_POST['entregar'],
			'destino' => $_POST['destino'],
			'fecha' => fechita($_POST['fecha']),
			'createdby' => $_SESSION['loginuser']['iduser']
		);
                
                $pecosa_values = array_map('strip_tags', $pecosa_values);
                
                $id = save_pecosa(0, $pecosa_values);
                
                $total = count($detalles);
                
                for($i = 0; $i < $total; $i++) {
                    save_detalle_salida($id, $detalles[$i], $salidas[$i]);
                }
                
                $msg = "La salida fue registrada";
                safe_redirect("pecosa-lista.php");

                break;
            default:
                error();
        }
}

$codgen = generate_code($bcdb->pecosa);
$smarty->assign ('codgen', $codgen);

$smarty->assign ('productos', $productos);
$smarty->assign ('section_title', TITLE . ' - Productos');
$smarty->assign ('file', 'almacen-confirmar.html');
$smarty->display ('index.html');

?>