<?php

require_once('home.php');
require_once('redirect.php');
//$pager = true;

$smarty->assign ('section_title', 'Ayuda');
$smarty->assign ('file', 'ayuda.html');
$smarty->display ('index.html');

?>