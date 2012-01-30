<?php

require_once('home.php');

$_SESSION = array();
unset($session_active);

if (isset($_COOKIE[session_name()])) {
	setcookie(session_name(), '', time()-42000, '/');
}

session_destroy();

@ header('Expires: Wed, 11 Jan 1984 05:00:00 GMT');
@ header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
@ header('Cache-Control: no-cache, must-revalidate, max-age=0');
@ header('Pragma: no-cache');
@ header('Refresh=5; location=' . BASE_URL);


$smarty->assign ('section_title', TITLE . " - Hasta Pronto");
$smarty->assign ('file', 'logout.html');
$smarty->display ('index.html');

?>
