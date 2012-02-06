CREATE TABLE `o_pecosa` (
  `idpecosa` int(4) NOT NULL auto_increment,
  `dependencia` varchar(200) NOT NULL,
  `entregar` varchar(200) NOT NULL,
  `destino` varchar(200) NOT NULL,
  `fecha` date NOT NULL,
  `idnea` int(3) NOT NULL default '0',
  `createdby` int(3) NOT NULL,
  PRIMARY KEY  (`idpecosa`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE  `o_detallepecosa` (
 `iddetalle` INT( 4 ) NOT NULL AUTO_INCREMENT COMMENT  'El id del detalle',
 `idpecosa` INT( 4 ) NOT NULL COMMENT  'El id de la PECOSA al que pertenece el detalle',
 `especifica` VARCHAR( 20 ) NOT NULL COMMENT  'Específica del Detalle',
 `cantidad` FLOAT NOT NULL COMMENT  'Cantidad',
 `umedida` VARCHAR( 10 ) NOT NULL COMMENT  'Unidad de medida',
 `descripcion` VARCHAR( 255 ) NOT NULL COMMENT  'La descripción del bien',
 `precio` FLOAT NOT NULL COMMENT  'El precio del bien',
PRIMARY KEY (  `iddetalle` )
) ENGINE = MYISAM DEFAULT CHARSET = utf8 AUTO_INCREMENT =1 COMMENT  'Guarda detalles de las PECOSAS';