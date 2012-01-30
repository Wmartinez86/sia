<?php

require_once('home.php');
require_once('redirect.php');

$smarty->assign ('section_title', TITLE . ' - Opciones del Sistema');
$smarty->assign ('file', 'sistema.html');
$smarty->display ('index.html');

?>