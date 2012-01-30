<?php

require_once('home.php');
require_once('redirect.php');

	$smarty->assign ('section_title', TITLE . ' - Error');
	$smarty->assign ('file', 'error.html');
	$smarty->display ('index.html');


?>