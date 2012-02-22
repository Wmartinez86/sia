<?php

require_once('home.php');
require_once('redirect.php');

$pager = true;
$pecosas = get_pecosas();

if($pecosas){
    $pecosas = fill_pecosas($pecosas);
}


if($pager) $smarty->assign ('RESULTS', $bcrs->get_navigation());
$smarty->assign ('pecosas', $pecosas);
$smarty->assign ('section_title', TITLE . ' - Productos del almacén');
$smarty->assign ('file', 'pecosa-lista.html');
$smarty->display ('index.html');

?>
