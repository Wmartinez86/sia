<?php

require_once('home.php');
require_once('redirect.php');

$smarty->assign ('atipos', $atipos);
$smarty->assign ('section_title', TITLE . ' - Bienvenido');
$smarty->assign ('file', 'cotizacion.html');
$smarty->display ('index.html');

?>