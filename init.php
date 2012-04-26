<?php
include_once('config.php');
include_once(INCLUDE_PATH . 'ez_sql_core.php');
include_once(INCLUDE_PATH . 'ez_sql_mysql.php');
include_once(INCLUDE_PATH . 'class.ezpdf.php');

# Tablas
$table_prefix = "o_";
$bcdb->opciones	 		= $table_prefix . 'opciones';
$bcdb->fuentes	 		= $table_prefix . 'fuentes';
$bcdb->proveedores		= $table_prefix . 'proveedores';
$bcdb->proyectos	 	= $table_prefix . 'proyectos';
$bcdb->areas	 		= $table_prefix . 'areas';
$bcdb->rubros	 		= $table_prefix . 'rubros';
$bcdb->usuarios	 		= $table_prefix . 'usuarios';
$bcdb->proveedores 		= $table_prefix . 'proveedores';
$bcdb->documentos 		= $table_prefix . 'documentos';
$bcdb->especificas 		= $table_prefix . 'especificas';
$bcdb->ordencompra 		= $table_prefix . 'ordencompra';
$bcdb->ordencomprapadre       	= $table_prefix . 'ordencomprapadre';
$bcdb->detalleordencompra       = $table_prefix . 'detalleordencompra';
$bcdb->ordenservicio            = $table_prefix . 'ordenservicio';
$bcdb->detalleordenservicio     = $table_prefix . 'detalleordenservicio';
$bcdb->cotizacion		= $table_prefix . 'cotizacion';
$bcdb->detallecotizacion        = $table_prefix . 'detallecotizacion';
$bcdb->provcotizacion 		= $table_prefix . 'provcotizacion';
$bcdb->preciocotizacion 	= $table_prefix . 'preciocotizacion';
$bcdb->cuadrocomparativo 	= $table_prefix . 'cuadrocomparativo';
$bcdb->requerimientos	 	= $table_prefix . 'requerimientos';
$bcdb->detallerequerimiento	= $table_prefix . 'detallerequerimiento';
$bcdb->neas             	= $table_prefix . 'neas';
$bcdb->detallenea       	= $table_prefix . 'detallenea';
$bcdb->pecosa             	= $table_prefix . 'pecosa';
$bcdb->detallepecosa       	= $table_prefix . 'detallepecosa';
$bcdb->detallealmacen       	= $table_prefix . 'detallealmacen';

include_once(INCLUDE_PATH . 'smarty-instance.php');

# Funciones independientes
include_once(INCLUDE_PATH . 'formatting-functions.php');
include_once(INCLUDE_PATH . 'pager.class.php');
include_once(INCLUDE_PATH . 'functions.php');
include_once(INCLUDE_PATH . 'numbertotext.php');

# Another functions
include_once(INCLUDE_PATH . 'user-functions.php');
include_once(INCLUDE_PATH . 'prov-functions.php');
include_once(INCLUDE_PATH . 'proj-functions.php');
include_once(INCLUDE_PATH . 'fuentes-functions.php');
include_once(INCLUDE_PATH . 'docs-functions.php');
include_once(INCLUDE_PATH . 'especificas-functions.php');
include_once(INCLUDE_PATH . 'orden-compra-functions.php');
include_once(INCLUDE_PATH . 'orden-servicio-functions.php');
include_once(INCLUDE_PATH . 'cotizacion-functions.php');
include_once(INCLUDE_PATH . 'cuadrocomparativo-functions.php');
include_once(INCLUDE_PATH . 'requerimientos-functions.php');
include_once(INCLUDE_PATH . 'area-functions.php');
include_once(INCLUDE_PATH . 'nea-functions.php');
include_once(INCLUDE_PATH . 'pecosa-functions.php');

# Iniciamos

define("MUNI", get_option('nombre_entidad'));
define("MUNI2", strtoupper(MUNI));
define("LOG1", get_option('tipo_entidad'));
define("LOG2", get_option('ubicacion'));
define("RUCMUNI", get_option('ruc_entidad'));
define("TITLE", sprintf("%s %s", LOG1, MUNI));
define("logo", get_option('logo'));

$smarty->assign("muni", MUNI);
$smarty->assign("muni2", MUNI2);
$smarty->assign("log1", LOG1);
$smarty->assign("log2", LOG2); 
$smarty->assign("rucmuni", RUCMUNI); 

send_headers();

global_sanitize();

$pager = false;

$msg = null;
$smarty->assign_by_ref('msg', $msg);
$smarty->assign('charset', CHARSET);
$smarty->assign('aordenes', $aordenes);

?>
