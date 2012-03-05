<?php
/**
  * $allowed es la lista de páginas que son permitidas 
  * a los usuarios operadores
  */
$allowed = array(
    "/error.php",
    "/index.php",
    "/requerimiento.php",
    "/requerimiento-lista.php",
    "/requerimientonew.php",
    "/requerimiento-print.php",
    "/borrar-detalle-requerimiento.php",
    "/add-item.php",
    "/",
);

/**
  * $almacen es la lista de páginas que son permitidas 
  * a los usuarios de almacén
  */
$almacen = array(
    "/error.php",
    "/index.php",
    "/almacen.php",
    "/nea-print.php",
    "/nea-orden.php",
    "/add-item.php",
    "/almacen-lista.php",
    "/pecosa-print.php",
    "/pecosa-lista.php",
    "/almacen-quitar.php",
    "/almacen-confirmar.php",
    "/orden-compra-print.php",
);

if(!isset($session_active)) {
        header("Location: ". BASE_URL . "login.php?r=" . $_SERVER['PHP_SELF']);
        exit();
}

if(is_almacenero()) {
    if(!in_array($_SERVER['PHP_SELF'], $almacen)) {
            header("Location: ". BASE_URL . "error.php");
            exit();
    }
}elseif(!is_admin($_SESSION['loginuser']['iduser'])) {
        if(!in_array($_SERVER['PHP_SELF'], $allowed)) {
                header("Location: ". BASE_URL . "error.php");
                exit();
        }
}
    
?>
