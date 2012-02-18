<?php
define("BASE_PATH", $_SERVER['DOCUMENT_ROOT'] . "/");
define("INCLUDE_PATH", BASE_PATH . "includes/");
define("SITES_PATH", BASE_PATH . "sites/");
define("MY_SITE", $_SERVER['SERVER_NAME'] . "/");
define("CURRENT_SITE", SITES_PATH . MY_SITE);

include_once(CURRENT_SITE . "settings.php"); 
require_once(INCLUDE_PATH . "functions.php");

define("BASE_URL", site_url());
define("IMAGES_URL", BASE_URL . "images/");
define("STYLES_URL", BASE_URL . "styles/");
define("SCRIPTS_URL", BASE_URL . "scripts/");
define("DATA_PATH", BASE_PATH. "data/");
define("FONTS_URL","fonts/");
define("forma",true);
define("CHARSET", "utf-8");
define("NUM_ITEMS", 15);

// Estados de orden
define('ORDEN_ACTIVA', 1);
define('ORDEN_CANCELADA', 2);
define('ORDEN_CONGELADA', 3);

error_reporting(E_ALL);

setlocale(LC_TIME, 'esp_PER');

$aordenes = array(
				'o'=>"Orden de Servicio",
				'c'=>"Orden de Compra"
			);
			
$atipos = array(
				'1'=>"Gastos de Inversión",
				'2'=>"Gastos Corrientes"
			);
			
$autipos = array(
				'1'=>"Administrador",
				'2'=>"Operador",
                                '3'=>"Personal de Almacén"
			);
?>
