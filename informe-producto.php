<?php

require_once('home.php');
require_once('redirect.php');
//$pager = true;
$status = 0;
$producto = isset($_GET['producto']) ? $_GET['producto'] : "";

$sql = "SELECT o.status, d.* 
        FROM $bcdb->detalleordencompra d
        INNER JOIN $bcdb->ordencompra o
        ON d.idorden = o.idorden
        WHERE d.descripcion LIKE '%$producto%' 
        AND o.status = '1'
        ORDER BY precio DESC";

$productos = $bcdb->get_results($sql);

foreach($productos as $k => $producto) {
  $productos[$k]['orden'] = get_orden_compra($producto['idorden']);
  $productos[$k]['orden']['fecha'] = fechita2($productos[$k]['orden']['fecha']);
}

$smarty->assign('productos', $productos);

$results = $smarty->fetch('informe-producto.html');
print $results;
?>
