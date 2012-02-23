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

//update_002();
//update_003();

function update_004() {
    global $bcdb;
    $bcdb->show_errors = true;
    
    $sql = "DROP TABLE IF EXISTS `o_detallealmacen`;
    CREATE TABLE `o_detallealmacen` (
    `iddetalle` int(4) NOT NULL auto_increment COMMENT 'El id del detalle',
    `idennea` int(4) NOT NULL COMMENT 'El id del detalle relacionado en la tabla detallenea',
    `idorden` int(4) NOT NULL COMMENT 'El id de la Orden al que pertenece el producto',
    `cantidad` decimal(10,2) NOT NULL default '0.00' COMMENT 'La cantidad original',
    `cuantosalio` decimal(10,2) NOT NULL default '0.00' COMMENT 'Cuanto sale del almacen',
    PRIMARY KEY  (`iddetalle`)
    ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Guarda los productos que estan en el almacen';";
    $bcdb->query($sql);
    
    $sql = "DROP TABLE IF EXISTS `o_detallenea`;
CREATE TABLE `o_detallenea` (
  `iddetalle` int(4) NOT NULL auto_increment COMMENT 'El id del detalle',
  `idnea` int(4) NOT NULL COMMENT 'El id de la NEA al que pertenece el detalle',
  `especifica` varchar(20) NOT NULL COMMENT 'Específica del Detalle',
  `cantidad` decimal(10,2) NOT NULL default '0.00',
  `umedida` varchar(10) NOT NULL COMMENT 'Unidad de medida',
  `descripcion` varchar(255) NOT NULL COMMENT 'La descripción del bien',
  `precio` decimal(10,2) NOT NULL default '0.00',
  PRIMARY KEY  (`iddetalle`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Guarda detalles de las NEAS';";
    $bcdb->query($sql);
    
    $sql = "DROP TABLE IF EXISTS `o_detallepecosa`;
CREATE TABLE `o_detallepecosa` (
  `iddetalle` int(4) NOT NULL auto_increment COMMENT 'El id del detalle',
  `idpecosa` int(4) NOT NULL,
  `idenalmacen` int(4) NOT NULL,
  `cantidad` decimal(10,2) NOT NULL default '0.00',
  PRIMARY KEY  (`iddetalle`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Guarda detalles de las PECOSAS';";
    $bcdb->query($sql);
    
    $sql = "DROP TABLE IF EXISTS `o_neas`;
CREATE TABLE `o_neas` (
  `idnea` int(4) NOT NULL auto_increment COMMENT 'El id de la NEA',
  `codigo` varchar(10) NOT NULL COMMENT 'Código autogenerado de la NEA',
  `procedencia` varchar(200) default NULL COMMENT 'De donde proceden los bienes',
  `idorden` int(4) NOT NULL default '0' COMMENT 'La orden de compra de donde provienen los bienes, si no existe es 0',
  `destino` int(4) NOT NULL default '1' COMMENT 'Puede ser una obra o el almacén central (Genérico)',
  `fecha` date NOT NULL COMMENT 'La fecha de ingreso de los bienes',
  `observaciones` text NOT NULL COMMENT 'Observaciones que tienen que ver con la NEA',
  `createdby` int(3) NOT NULL COMMENT 'Quién ha creado la NEA',
  PRIMARY KEY  (`idnea`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Guarda las NEAS sean con destino o al almacén central';";
    $bcdb->query($sql);
    
    $sql = "DROP TABLE IF EXISTS `o_pecosa`;
CREATE TABLE `o_pecosa` (
  `idpecosa` int(4) NOT NULL auto_increment COMMENT 'El id de la PECOSA',
  `codigo` varchar(10) NOT NULL,
  `dependencia` varchar(200) NOT NULL COMMENT 'La dependencia del solicitante',
  `entregar` varchar(200) NOT NULL COMMENT 'persona a la Que se va hacer la Entrega',
  `destino` varchar(200) NOT NULL COMMENT 'Destino de los Productos',
  `fecha` date NOT NULL COMMENT 'Fecha de la Creacion de Pecosa',
  `createdby` int(3) NOT NULL COMMENT 'Usuario Creador de Pecosa',
  PRIMARY KEY  (`idpecosa`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;";
    $bcdb->query($sql);
    
}

update_004();

?>
