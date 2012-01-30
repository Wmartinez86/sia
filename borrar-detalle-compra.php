<?php

require_once('home.php');
require_once('redirect.php');

if (isset($_REQUEST['idorden'])) { 
    $idorden = $_REQUEST['idorden'];
} else {
    $idorden = '0';
}

if (isset($_REQUEST['iddetalle'])) { 
    $iddetalle = $_REQUEST['iddetalle'];
} else {
    $iddetalle = '0';
}


//$idorden = ! empty($_REQUEST['idorden']) ? (int)$idorden : 0;
//$iddetalle = ! empty($_REQUEST['iddetalle']) ? (int)$iddetalle : 0;

if(borrar_detalle_compra($iddetalle, $idorden)){
	header("Location: orden-compra.php?idorden=$idorden&do=ok");
}else{
	header("Location: orden-compra.php?idorden=$idorden&do=err");
}

?>