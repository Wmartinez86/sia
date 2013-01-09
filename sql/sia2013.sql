-- phpMyAdmin SQL Dump
-- version 3.5.4
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 08-01-2013 a las 12:33:36
-- Versión del servidor: 5.5.28
-- Versión de PHP: 5.3.18

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `sia2013`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `o_areas`
--

CREATE TABLE IF NOT EXISTS `o_areas` (
  `idarea` int(5) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `abreviatura` varchar(6) NOT NULL,
  PRIMARY KEY (`idarea`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

INSERT INTO o_areas(nombre, abreviatura) VALUES ('Oficina de Abastecimiento', 'OA');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `o_cotizacion`
--

CREATE TABLE IF NOT EXISTS `o_cotizacion` (
  `idcot` int(4) NOT NULL AUTO_INCREMENT,
  `codigo` varchar(10) NOT NULL,
  `referencia` text NOT NULL,
  `fecha` date NOT NULL,
  `status` int(1) NOT NULL DEFAULT '1',
  `createdby` int(3) NOT NULL,
  `tipocontrata` enum('o','c') NOT NULL DEFAULT 'c',
  `glosa` text NOT NULL,
  PRIMARY KEY (`idcot`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `o_cuadrocomparativo`
--

CREATE TABLE IF NOT EXISTS `o_cuadrocomparativo` (
  `idcuadro` int(4) NOT NULL AUTO_INCREMENT,
  `idcot` int(4) NOT NULL,
  `fecha` date NOT NULL,
  `idproveedor` int(4) NOT NULL,
  `monto` float NOT NULL,
  `justificacion` varchar(400) DEFAULT NULL,
  `Observacion` varchar(400) DEFAULT NULL,
  `cep` varchar(150) NOT NULL DEFAULT 'los miembros integrantes del Comité Especial',
  PRIMARY KEY (`idcuadro`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `o_detallealmacen`
--

CREATE TABLE IF NOT EXISTS `o_detallealmacen` (
  `iddetalle` int(4) NOT NULL AUTO_INCREMENT COMMENT 'El id del detalle',
  `idennea` int(4) NOT NULL COMMENT 'El id del detalle relacionado en la tabla detallenea',
  `idorden` int(4) NOT NULL COMMENT 'El id de la Orden al que pertenece el producto',
  `cantidad` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT 'La cantidad original',
  `cuantosalio` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT 'Cuanto sale del almacen',
  PRIMARY KEY (`iddetalle`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Guarda los productos que estan en el almacen' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `o_detallecotizacion`
--

CREATE TABLE IF NOT EXISTS `o_detallecotizacion` (
  `iddetalle` int(4) NOT NULL AUTO_INCREMENT,
  `idcot` int(4) NOT NULL,
  `cantidad` decimal(10,2) NOT NULL DEFAULT '0.00',
  `umedida` varchar(10) NOT NULL,
  `descripcion` varchar(255) NOT NULL,
  PRIMARY KEY (`iddetalle`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `o_detallenea`
--

CREATE TABLE IF NOT EXISTS `o_detallenea` (
  `iddetalle` int(4) NOT NULL AUTO_INCREMENT COMMENT 'El id del detalle',
  `idnea` int(4) NOT NULL COMMENT 'El id de la NEA al que pertenece el detalle',
  `especifica` varchar(20) NOT NULL COMMENT 'Específica del Detalle',
  `cantidad` decimal(10,2) NOT NULL DEFAULT '0.00',
  `umedida` varchar(10) NOT NULL COMMENT 'Unidad de medida',
  `descripcion` varchar(255) NOT NULL COMMENT 'La descripción del bien',
  `precio` decimal(10,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`iddetalle`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Guarda detalles de las NEAS' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `o_detalleordencompra`
--

CREATE TABLE IF NOT EXISTS `o_detalleordencompra` (
  `iddetalle` int(4) NOT NULL AUTO_INCREMENT,
  `idorden` int(4) NOT NULL,
  `idespec` int(4) NOT NULL,
  `especifica` varchar(20) NOT NULL,
  `cantidad` decimal(10,2) NOT NULL DEFAULT '0.00',
  `umedida` varchar(10) NOT NULL,
  `descripcion` varchar(255) NOT NULL,
  `precio` decimal(10,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`iddetalle`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `o_detalleordenservicio`
--

CREATE TABLE IF NOT EXISTS `o_detalleordenservicio` (
  `iddetalle` int(4) NOT NULL AUTO_INCREMENT,
  `idorden` int(4) NOT NULL,
  `idespec` int(4) NOT NULL,
  `especifica` varchar(20) NOT NULL,
  `cantidad` decimal(10,2) NOT NULL DEFAULT '0.00',
  `umedida` varchar(10) NOT NULL,
  `descripcion` text NOT NULL,
  `precio` decimal(10,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`iddetalle`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `o_detallepecosa`
--

CREATE TABLE IF NOT EXISTS `o_detallepecosa` (
  `iddetalle` int(4) NOT NULL AUTO_INCREMENT COMMENT 'El id del detalle',
  `idpecosa` int(4) NOT NULL,
  `idenalmacen` int(4) NOT NULL,
  `cantidad` decimal(10,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`iddetalle`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Guarda detalles de las PECOSAS' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `o_detallerequerimiento`
--

CREATE TABLE IF NOT EXISTS `o_detallerequerimiento` (
  `iddetalle` int(4) NOT NULL AUTO_INCREMENT,
  `idreq` int(4) NOT NULL,
  `cantidad` decimal(10,2) NOT NULL DEFAULT '0.00',
  `umedida` varchar(10) NOT NULL,
  `descripcion` varchar(255) NOT NULL,
  PRIMARY KEY (`iddetalle`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `o_documentos`
--

CREATE TABLE IF NOT EXISTS `o_documentos` (
  `iddoc` int(2) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  PRIMARY KEY (`iddoc`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `o_especificas`
--

CREATE TABLE IF NOT EXISTS `o_especificas` (
  `idespec` int(4) NOT NULL AUTO_INCREMENT,
  `codigo` varchar(20) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  PRIMARY KEY (`idespec`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `o_fuentes`
--

CREATE TABLE IF NOT EXISTS `o_fuentes` (
  `idfuente` int(3) NOT NULL AUTO_INCREMENT,
  `codigo` varchar(10) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  PRIMARY KEY (`idfuente`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `o_neas`
--

CREATE TABLE IF NOT EXISTS `o_neas` (
  `idnea` int(4) NOT NULL AUTO_INCREMENT COMMENT 'El id de la NEA',
  `codigo` varchar(10) NOT NULL COMMENT 'Código autogenerado de la NEA',
  `procedencia` varchar(200) DEFAULT NULL COMMENT 'De donde proceden los bienes',
  `idorden` int(4) NOT NULL DEFAULT '0' COMMENT 'La orden de compra de donde provienen los bienes, si no existe es 0',
  `destino` int(4) NOT NULL DEFAULT '1' COMMENT 'Puede ser una obra o el almacén central (Genérico)',
  `fecha` date NOT NULL COMMENT 'La fecha de ingreso de los bienes',
  `observaciones` text NOT NULL COMMENT 'Observaciones que tienen que ver con la NEA',
  `createdby` int(3) NOT NULL COMMENT 'Quién ha creado la NEA',
  PRIMARY KEY (`idnea`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Guarda las NEAS sean con destino o al almacén central' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `o_opciones`
--

CREATE TABLE IF NOT EXISTS `o_opciones` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

INSERT INTO `o_opciones` (`id`, `nombre`, `descripcion`) VALUES
(1, 'tipo_entidad', 'Municipalidad Distrital de'),
(2, 'nombre_entidad', ''),
(3, 'ubicacion', 'Cusco'),
(4, 'ruc_entidad', ''),
(5, 'logo', '1');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `o_ordencompra`
--

CREATE TABLE IF NOT EXISTS `o_ordencompra` (
  `idorden` int(4) NOT NULL AUTO_INCREMENT,
  `iddoc` int(2) NOT NULL,
  `nrodoc` varchar(255) NOT NULL,
  `codigo` varchar(10) NOT NULL,
  `idproyecto` int(4) NOT NULL,
  `idfuente` int(3) NOT NULL,
  `idproveedor` int(4) NOT NULL,
  `facturarto` varchar(255) NOT NULL,
  `fruc` varchar(11) NOT NULL,
  `fecha` date NOT NULL,
  `createdby` int(3) NOT NULL,
  `destino` varchar(150) NOT NULL,
  `status` int(1) NOT NULL DEFAULT '1',
  `glosa` text NOT NULL,
  PRIMARY KEY (`idorden`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `o_ordenservicio`
--

CREATE TABLE IF NOT EXISTS `o_ordenservicio` (
  `idorden` int(4) NOT NULL AUTO_INCREMENT,
  `iddoc` int(2) NOT NULL,
  `nrodoc` varchar(255) NOT NULL,
  `codigo` varchar(10) NOT NULL,
  `idproyecto` int(4) NOT NULL,
  `idfuente` int(3) NOT NULL,
  `idproveedor` int(4) NOT NULL,
  `facturarto` varchar(255) NOT NULL,
  `fruc` varchar(11) NOT NULL,
  `fecha` date NOT NULL,
  `createdby` int(3) NOT NULL,
  `status` int(1) NOT NULL DEFAULT '1',
  `glosa` text NOT NULL,
  PRIMARY KEY (`idorden`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `o_pecosa`
--

CREATE TABLE IF NOT EXISTS `o_pecosa` (
  `idpecosa` int(4) NOT NULL AUTO_INCREMENT COMMENT 'El id de la PECOSA',
  `codigo` varchar(10) NOT NULL,
  `dependencia` varchar(200) NOT NULL COMMENT 'La dependencia del solicitante',
  `entregar` varchar(200) NOT NULL COMMENT 'persona a la Que se va hacer la Entrega',
  `destino` varchar(200) NOT NULL COMMENT 'Destino de los Productos',
  `fecha` date NOT NULL COMMENT 'Fecha de la Creacion de Pecosa',
  `createdby` int(3) NOT NULL COMMENT 'Usuario Creador de Pecosa',
  PRIMARY KEY (`idpecosa`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `o_preciocotizacion`
--

CREATE TABLE IF NOT EXISTS `o_preciocotizacion` (
  `idprecio` int(4) NOT NULL AUTO_INCREMENT,
  `iddetalle` int(4) NOT NULL,
  `idprov` int(4) NOT NULL,
  `precio` float NOT NULL DEFAULT '0',
  PRIMARY KEY (`idprecio`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `o_provcotizacion`
--

CREATE TABLE IF NOT EXISTS `o_provcotizacion` (
  `idprov` int(4) NOT NULL AUTO_INCREMENT,
  `idproveedor` int(4) NOT NULL,
  `idcot` int(4) NOT NULL,
  `plazo` varchar(20) NOT NULL,
  `fecha` date NOT NULL,
  `nprov` int(1) NOT NULL,
  PRIMARY KEY (`idprov`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `o_proveedores`
--

CREATE TABLE IF NOT EXISTS `o_proveedores` (
  `idproveedor` int(4) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) NOT NULL,
  `razonsocial` varchar(255) NOT NULL,
  `direccion` varchar(255) NOT NULL,
  `telefono` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `ruc` varchar(11) NOT NULL,
  PRIMARY KEY (`idproveedor`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `o_proyectos`
--

CREATE TABLE IF NOT EXISTS `o_proyectos` (
  `idproyecto` int(4) NOT NULL AUTO_INCREMENT,
  `sec_func` varchar(4) NOT NULL,
  `programa` varchar(4) NOT NULL,
  `prod_pry` varchar(7) NOT NULL,
  `act_ai_obra` varchar(7) NOT NULL,
  `funcion` varchar(2) NOT NULL,
  `division_func` varchar(3) NOT NULL,
  `grupo_func` varchar(4) NOT NULL,
  `meta` varchar(5) NOT NULL,
  `finalidad` varchar(5) NOT NULL,
  `descripcion` varchar(255) NOT NULL,
  PRIMARY KEY (`idproyecto`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

INSERT INTO `o_proyectos` (`idproyecto`, `sec_func`, `programa`, `prod_pry`, `act_ai_obra`, `funcion`, `division_func`, `grupo_func`, `meta`, `finalidad`, `descripcion`) VALUES
(1, '0', '0', '0', '0', '0', '0', '0', '0', '0', 'Genérico');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `o_requerimientos`
--

CREATE TABLE IF NOT EXISTS `o_requerimientos` (
  `idreq` int(4) NOT NULL AUTO_INCREMENT,
  `codigo` varchar(10) NOT NULL,
  `fecha` date NOT NULL,
  `status` int(1) NOT NULL DEFAULT '1',
  `createdby` int(3) NOT NULL,
  `glosa` text NOT NULL,
  PRIMARY KEY (`idreq`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `o_usuarios`
--

CREATE TABLE IF NOT EXISTS `o_usuarios` (
  `iduser` int(3) NOT NULL AUTO_INCREMENT,
  `nombres` varchar(255) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `username` varchar(100) NOT NULL,
  `pwd` varchar(32) NOT NULL,
  `usertype` int(1) NOT NULL DEFAULT '2',
  `idarea` int(4) NOT NULL,
  `idproyecto` int(4) NOT NULL,
  PRIMARY KEY (`iduser`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

INSERT INTO `o_usuarios` (`iduser`, `nombres`, `email`, `username`, `pwd`, `usertype`, `idarea`, `idproyecto`) VALUES
(1, 'Administrador', 'admin@admin.com', 'admin', 'ea1602eced192386926dc117b95aa699', 1, 1, 1);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
