<?php
include("../home.php");
include("../redirect.php");

/**
 * Actualizacion de campos del 16 Febrero 2012
  */
function update_001() {
    global $bcdb;
    
    $bcdb->show_errors = true;

    $sql = "ALTER TABLE  `o_detallecotizacion` CHANGE  `cantidad`  `cantidad` DECIMAL( 10, 2 ) NOT NULL DEFAULT  '0';";
    $bcdb->query($sql);
    
    $sql = "ALTER TABLE  `o_detallenea` CHANGE  `cantidad`  `cantidad` DECIMAL( 10, 2 ) NOT NULL DEFAULT  '0';";
    $bcdb->query($sql);
    $sql = "ALTER TABLE  `o_detallenea` CHANGE  `precio`  `precio` DECIMAL( 10, 2 ) NOT NULL DEFAULT  '0';";
    $bcdb->query($sql);
    
    $sql = "ALTER TABLE  `o_detalleordencompra` CHANGE  `cantidad`  `cantidad` DECIMAL( 10, 2 ) NOT NULL DEFAULT  '0';";
    $bcdb->query($sql);
    $sql = "ALTER TABLE  `o_detalleordencompra` CHANGE  `precio`  `precio` DECIMAL( 10, 2 ) NOT NULL DEFAULT  '0';";
    $bcdb->query($sql);
    
    $sql = "ALTER TABLE  `o_detalleordenservicio` CHANGE  `cantidad`  `cantidad` DECIMAL( 10, 2 ) NOT NULL DEFAULT  '0';";
    $bcdb->query($sql);
    $sql = "ALTER TABLE  `o_detalleordenservicio` CHANGE  `precio`  `precio` DECIMAL( 10, 2 ) NOT NULL DEFAULT  '0';";
    $bcdb->query($sql);
    
    $sql = "ALTER TABLE  `o_detallepecosa` CHANGE  `cantidad`  `cantidad` DECIMAL( 10, 2 ) NOT NULL DEFAULT  '0';";
    $bcdb->query($sql);
    $sql = "ALTER TABLE  `o_detallepecosa` CHANGE  `precio`  `precio` DECIMAL( 10, 2 ) NOT NULL DEFAULT  '0';";
    $bcdb->query($sql);
    
    $sql = "ALTER TABLE  `o_detallerequerimiento` CHANGE  `cantidad`  `cantidad` DECIMAL( 10, 2 ) NOT NULL DEFAULT  '0';";
    $bcdb->query($sql);
    
    $bcdb->show_errors = false;
}

update_001();

?>
