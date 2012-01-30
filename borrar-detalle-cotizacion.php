<?php

require_once('home.php');
require_once('redirect.php');

if (isset($_REQUEST['idcot'])) { 
    $idcot = $_REQUEST['idcot'];
} else {
    $idcot = '0';
}

if (isset($_REQUEST['iddetalle'])) { 
    $iddetalle = $_REQUEST['iddetalle'];
} else {
    $iddetalle = '0';
}

//$idcot = ! empty($_REQUEST['idcot']) ? (int)$idcot : 0;
//$iddetalle = ! empty($_REQUEST['iddetalle']) ? (int)$iddetalle : 0;

if(borrar_detalle_cotizacion($iddetalle, $idcot)){
	header("Location: cotizacionnew.php?idcot=$idcot&do=ok");
}else{
	header("Location: cotizacionnew.php?idcot=$idcot&do=err");
}

?>