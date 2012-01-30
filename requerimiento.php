<?php

require_once('home.php');
require_once('redirect.php');

$smarty->assign ('section_title', TITLE . ' - Bienvenido');
$smarty->assign ('file', 'requerimiento.html');
$smarty->display ('index.html');

?>