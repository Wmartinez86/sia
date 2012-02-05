use sonccoc_cachi;
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Guarda las NEAS sean con destino o al almacén central' AUTO_INCREMENT=1 ;
CREATE TABLE `o_detallenea` (
  `iddetalle` int(4) NOT NULL auto_increment COMMENT 'El id del detalle',
  `idnea` int(4) NOT NULL COMMENT 'El id de la NEA al que pertenece el detalle',
  `especifica` varchar(20) NOT NULL COMMENT 'Específica del Detalle',
  `cantidad` float NOT NULL COMMENT 'Cantidad',
  `umedida` varchar(10) NOT NULL COMMENT 'Unidad de medida',
  `descripcion` varchar(255) NOT NULL COMMENT 'La descripción del bien',
  `precio` float NOT NULL COMMENT 'El precio del bien',
  PRIMARY KEY  (`iddetalle`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 COMMENT 'Guarda detalles de las NEAS';
use sonccoc_ccarhua;
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Guarda las NEAS sean con destino o al almacén central' AUTO_INCREMENT=1 ;
CREATE TABLE `o_detallenea` (
  `iddetalle` int(4) NOT NULL auto_increment COMMENT 'El id del detalle',
  `idnea` int(4) NOT NULL COMMENT 'El id de la NEA al que pertenece el detalle',
  `especifica` varchar(20) NOT NULL COMMENT 'Específica del Detalle',
  `cantidad` float NOT NULL COMMENT 'Cantidad',
  `umedida` varchar(10) NOT NULL COMMENT 'Unidad de medida',
  `descripcion` varchar(255) NOT NULL COMMENT 'La descripción del bien',
  `precio` float NOT NULL COMMENT 'El precio del bien',
  PRIMARY KEY  (`iddetalle`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 COMMENT 'Guarda detalles de las NEAS';

use sonccoc_lares;
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Guarda las NEAS sean con destino o al almacén central' AUTO_INCREMENT=1 ;
CREATE TABLE `o_detallenea` (
  `iddetalle` int(4) NOT NULL auto_increment COMMENT 'El id del detalle',
  `idnea` int(4) NOT NULL COMMENT 'El id de la NEA al que pertenece el detalle',
  `especifica` varchar(20) NOT NULL COMMENT 'Específica del Detalle',
  `cantidad` float NOT NULL COMMENT 'Cantidad',
  `umedida` varchar(10) NOT NULL COMMENT 'Unidad de medida',
  `descripcion` varchar(255) NOT NULL COMMENT 'La descripción del bien',
  `precio` float NOT NULL COMMENT 'El precio del bien',
  PRIMARY KEY  (`iddetalle`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 COMMENT 'Guarda detalles de las NEAS';


use sonccoc_ollanta;
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Guarda las NEAS sean con destino o al almacén central' AUTO_INCREMENT=1 ;
CREATE TABLE `o_detallenea` (
  `iddetalle` int(4) NOT NULL auto_increment COMMENT 'El id del detalle',
  `idnea` int(4) NOT NULL COMMENT 'El id de la NEA al que pertenece el detalle',
  `especifica` varchar(20) NOT NULL COMMENT 'Específica del Detalle',
  `cantidad` float NOT NULL COMMENT 'Cantidad',
  `umedida` varchar(10) NOT NULL COMMENT 'Unidad de medida',
  `descripcion` varchar(255) NOT NULL COMMENT 'La descripción del bien',
  `precio` float NOT NULL COMMENT 'El precio del bien',
  PRIMARY KEY  (`iddetalle`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 COMMENT 'Guarda detalles de las NEAS';


use sonccoc_ordenes;
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Guarda las NEAS sean con destino o al almacén central' AUTO_INCREMENT=1 ;
CREATE TABLE `o_detallenea` (
  `iddetalle` int(4) NOT NULL auto_increment COMMENT 'El id del detalle',
  `idnea` int(4) NOT NULL COMMENT 'El id de la NEA al que pertenece el detalle',
  `especifica` varchar(20) NOT NULL COMMENT 'Específica del Detalle',
  `cantidad` float NOT NULL COMMENT 'Cantidad',
  `umedida` varchar(10) NOT NULL COMMENT 'Unidad de medida',
  `descripcion` varchar(255) NOT NULL COMMENT 'La descripción del bien',
  `precio` float NOT NULL COMMENT 'El precio del bien',
  PRIMARY KEY  (`iddetalle`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 COMMENT 'Guarda detalles de las NEAS';


use sonccoc_pucyura;
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Guarda las NEAS sean con destino o al almacén central' AUTO_INCREMENT=1 ;
CREATE TABLE `o_detallenea` (
  `iddetalle` int(4) NOT NULL auto_increment COMMENT 'El id del detalle',
  `idnea` int(4) NOT NULL COMMENT 'El id de la NEA al que pertenece el detalle',
  `especifica` varchar(20) NOT NULL COMMENT 'Específica del Detalle',
  `cantidad` float NOT NULL COMMENT 'Cantidad',
  `umedida` varchar(10) NOT NULL COMMENT 'Unidad de medida',
  `descripcion` varchar(255) NOT NULL COMMENT 'La descripción del bien',
  `precio` float NOT NULL COMMENT 'El precio del bien',
  PRIMARY KEY  (`iddetalle`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 COMMENT 'Guarda detalles de las NEAS';


use sonccoc_quinota;
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Guarda las NEAS sean con destino o al almacén central' AUTO_INCREMENT=1 ;
CREATE TABLE `o_detallenea` (
  `iddetalle` int(4) NOT NULL auto_increment COMMENT 'El id del detalle',
  `idnea` int(4) NOT NULL COMMENT 'El id de la NEA al que pertenece el detalle',
  `especifica` varchar(20) NOT NULL COMMENT 'Específica del Detalle',
  `cantidad` float NOT NULL COMMENT 'Cantidad',
  `umedida` varchar(10) NOT NULL COMMENT 'Unidad de medida',
  `descripcion` varchar(255) NOT NULL COMMENT 'La descripción del bien',
  `precio` float NOT NULL COMMENT 'El precio del bien',
  PRIMARY KEY  (`iddetalle`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 COMMENT 'Guarda detalles de las NEAS';


use sonccoc_zurite;
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Guarda las NEAS sean con destino o al almacén central' AUTO_INCREMENT=1 ;
CREATE TABLE `o_detallenea` (
  `iddetalle` int(4) NOT NULL auto_increment COMMENT 'El id del detalle',
  `idnea` int(4) NOT NULL COMMENT 'El id de la NEA al que pertenece el detalle',
  `especifica` varchar(20) NOT NULL COMMENT 'Específica del Detalle',
  `cantidad` float NOT NULL COMMENT 'Cantidad',
  `umedida` varchar(10) NOT NULL COMMENT 'Unidad de medida',
  `descripcion` varchar(255) NOT NULL COMMENT 'La descripción del bien',
  `precio` float NOT NULL COMMENT 'El precio del bien',
  PRIMARY KEY  (`iddetalle`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 COMMENT 'Guarda detalles de las NEAS';
