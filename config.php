<?php
//define("BASE_PATH", dirname(__FILE__) . "/");
define("BASE_PATH", $_SERVER['DOCUMENT_ROOT'] . "/");
define("INCLUDE_PATH", BASE_PATH . "includes/");

// Multisitio
$this_site = $_SERVER['SERVER_NAME'];
$this_site = explode(".", $this_site);
$this_site = $this_site[0];

switch($this_site) {
	case 'lares':
		$db_params = array(
		        'db_host' => 'localhost',
		        'db_name' => 'sonccoc_lares',
		        'db_user' => 'sonccoc_lares',
		        'db_pass' => '$ordenlares'
		);
	break;
	case 'ccarhuayo':
		$db_params = array(
		        'db_host' => 'localhost',
		        'db_name' => 'sonccoc_ccarhua',
		        'db_user' => 'sonccoc_ccarhua',
		        'db_pass' => '$ordencarhua'
		);
	break;
	case 'pucyura':
		$db_params = array(
		        'db_host' => 'localhost',
		        'db_name' => 'sonccoc_pucyura',
		        'db_user' => 'sonccoc_pucyura',
		        'db_pass' => '$ordenpucyura'
		);
	break;
	case 'quinota':
		$db_params = array(
		        'db_host' => 'localhost',
		        'db_name' => 'sonccoc_quinota',
		        'db_user' => 'sonccoc_quinota',
		        'db_pass' => '$ordenquinota'
		);
	break;
	case 'zurite':
		$db_params = array(
		        'db_host' => 'localhost',
		        'db_name' => 'sonccoc_zurite',
		        'db_user' => 'sonccoc_zurite',
		        'db_pass' => '$ordenzurite'
		);
	break;
	default:	
		$db_params = array(
		        'db_host' => 'localhost',
		        'db_name' => 'sia',
		        'db_user' => 'root',
		        'db_pass' => 'root'
		);
}

require_once INCLUDE_PATH . 'functions.php';

define("BASE_URL", site_url());
define("IMAGES_URL", BASE_URL . "images/");
define("STYLES_URL", BASE_URL . "styles/");
define("SCRIPTS_URL", BASE_URL . "scripts/");
define("DATA_PATH", BASE_PATH. "data/");
define("FONTS_URL","fonts/");
define("forma",true);
define("CHARSET", "utf-8");
define("NUM_ITEMS", 15);

error_reporting(E_ALL);

setlocale(LC_TIME, 'esp_PER');

$aordenes = array(
				'o'=>"Orden de Servicio",
				'c'=>"Orden de Compra"
			);
			
$atipos = array(
				'1'=>"Gastos de Inversion",
				'2'=>"Gastos Corrientes"
			);
			
$autipos = array(
				'1'=>"Administrador",
				'2'=>"Operador"
			);
?>
