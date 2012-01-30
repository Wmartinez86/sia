<?php
	
include_once (INCLUDE_PATH . 'smarty/Smarty.class.php'); 

$smarty = new Smarty;
$smarty->template_dir		= DATA_PATH . "templates";
$smarty->compile_dir		= DATA_PATH . "templates_c";

$smarty->assign("baseurl", BASE_URL);
$smarty->assign("imgsurl", IMAGES_URL);
$smarty->assign("scriptsurl", SCRIPTS_URL);
$smarty->assign("stylesurl", STYLES_URL);


$_SERVER['PHP_SELF'] = htmlspecialchars(preg_replace('`(\.php).*$`', '$1', $_SERVER['PHP_SELF']), ENT_QUOTES, 'utf-8');
$smarty->assign("self", $_SERVER['PHP_SELF']);
	
?>