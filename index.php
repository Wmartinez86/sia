<?php

require_once('home.php');
require_once('redirect.php');
//$pager = true;

$smarty->assign ('section_title', TITLE . ' - Bienvenido');
$smarty->display ('index.html');

?>