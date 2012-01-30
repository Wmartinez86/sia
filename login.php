<?php
require_once('home.php');

if ( !empty($_SESSION['loginuser']) ) {
	safe_redirect(BASE_URL);
}

$postback = isset($_POST['username']);
$location = !empty($_REQUEST['r']) ? clean_html($_REQUEST['r']) : BASE_URL;

if($postback){			
	$user = get_user_by_username($_POST['username']);			
	if ( $user ) :
		if( $user['pwd'] == md5($_POST['pwd']) ) :
			session_regenerate_id();
			$_SESSION['loginuser'] = $user;
			safe_redirect($location);
		else:
			$msg = "La contrase&ntilde;a es incorrecta";
		endif;
	else:
		$msg = "El usuario no existe";
	endif;
}

$smarty->assign ('section_title', TITLE . ' - Login');
$smarty->assign ('file', 'login.html');
$smarty->assign ('r', $location);
$smarty->assign ('msg', $msg);
$smarty->display ('index.html');

?>