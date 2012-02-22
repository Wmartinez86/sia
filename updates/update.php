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

/**
 * Crea la tabla de Almacen con los productos que se tienen, 17 Feb 2012
 */
function update_002() {
    global $bcdb;
    $sql = "
        CREATE TABLE IF NOT EXISTS `o_detallealmacen` (
            `iddetalle` int(4) NOT NULL auto_increment COMMENT 'El id del detalle',
            `idennea` int(4) NOT NULL COMMENT 'El id del detalle relacionado en la tabla detallenea',
            `idorden` int(4) NOT NULL COMMENT 'El id de la Orden al que pertenece el producto',
            `cantidad` decimal(10,2) NOT NULL default '0.00' COMMENT 'La cantidad original',
            `cuantosalio` decimal(10,2) NOT NULL default '0.00' COMMENT 'Cuanto sale del almacen',
            PRIMARY KEY  (`iddetalle`)
        ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Guarda los productos que estan en el almacen';
        ";
    $bcdb->show_errors = true;
    $bcdb->query($sql);
    $bcdb->show_errors = false;
}

/**
 * Cambia la estructura de las tablas PECOSA
 */
function update_003() {
    global $bcdb;
    $bcdb->show_errors = true;
    
    $sql = "ALTER TABLE  `o_pecosa` DROP  `idnea";
    $bcdb->query($sql);
    
    $sql = "ALTER TABLE  `o_pecosa` ADD  `codigo` VARCHAR( 10 ) NOT NULL AFTER  `idpecosa` ;";
    $bcdb->query($sql);
    
    $sql = "ALTER TABLE `o_detallenea`
            DROP `especifica`,
            DROP `umedida`,
            DROP `descripcion`,
            DROP `precio`;";
    $bcdb->query($sql);
    
    $sql = "ALTER TABLE  `o_detallepecosa` ADD  `idenalmacen` INT( 4 ) NOT NULL AFTER  `iddetalle` ;";
    $bcdb->query($sql);
    
    $bcdb->show_errors = false;
}

update_002();
update_003();

?>
