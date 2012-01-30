<?php
require_once('init.php');

if(isset($_SESSION['loginuser'])) {
	$session_active = true;
	$smarty->assign("loginuser", $_SESSION['loginuser']);
	if(basename($_SERVER['PHP_SELF'])=='login.php')
		header("Location: " . BASE_URL . "index.php");
}
?>