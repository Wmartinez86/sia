<?php

require_once('home.php');
require_once('redirect.php');

if (isset($_REQUEST['idreq'])) { 
    $idreq = $_REQUEST['idreq'];
} else {
    $idreq = '0';
}

if (isset($_REQUEST['iddetalle'])) { 
    $iddetalle = $_REQUEST['iddetalle'];
} else {
    $iddetalle = '0';
}

//$idreq = ! empty($_REQUEST['idreq']) ? (int)$idreq : 0;
//$iddetalle = ! empty($_REQUEST['iddetalle']) ? (int)$iddetalle : 0;

if(borrar_detalle_requerimiento($iddetalle, $idreq)){
	header("Location: requerimientonew.php?idreq=$idreq&do=ok");
}else{
	header("Location: requerimientonew.php?idreq=$idreq&do=err");
}

?>