<?php

require_once('home.php');
require_once('redirect.php');
//$pager = false;

if (isset($_REQUEST['iduser'])) { 
    $iduser = $_REQUEST['iduser'];
} else {
    $iduser = '0';
}

//$iduser = ! empty($_REQUEST['iduser']) ? (int)$iduser : 0;
$smarty->assign ('autipos', $autipos);

	if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
		if ( validate_required(array(
			'Nombres' => $_POST['nombres'], 
			'E-Mail' => $_POST['email'],
			'Usuario' => $_POST['username']))) {
			
			$ok = true;
			if($_POST['pwd']!=$_POST['pwd2']) {
				$ok = false;
				$msg = "Las contrase&ntilde;as no coinciden.";
			}else{
				$pwd = trim($_POST['pwd']);
				if(empty($pwd)&&(!$iduser)) {
					$ok = false;
					$msg .= "La contrase&ntilde;a es un campo requerido.";
				}
					
			}
			
			if($ok) :
				$user_values = array(
					'nombres' => $_POST['nombres'],
					'email' => $_POST['email'],
					'username' => $_POST['username'],
					'usertype' => $_POST['usertype'],
                                        'idarea' => $_POST['idarea'],
                                        'idproyecto' => $_POST['idproyecto'],  
				);
				
				if($iduser&&(!empty($_POST['pwd']))) {
					$user_values['pwd'] = md5(trim($_POST['pwd']));
				}elseif(empty($iduser)){
                                    $user_values['pwd'] = md5(trim($_POST['pwd']));
                                }
				
				$user_values = array_map('strip_tags', $user_values);
				
				$id = save_user($iduser, $user_values);
				if($id) $iduser = 0;
			endif;
		} else 
			$msg = "Ya existe el usuario '$user_values[username]'.";
	}
	
	$users = get_users();
	if($users) {
		foreach($users as $k=>$user) {
			$users[$k]['rango'] = $autipos[$user['usertype']];
		}
	}

        $pager = false;
	$projs = get_projs();
        $areas = get_areas();
        
	if($iduser) {
		$smarty->assign ('user', get_user($iduser));
	}
	
	$smarty->assign ('RESULTS', $bcrs->get_navigation());
	$smarty->assign ('users', $users);
	
        $smarty->assign ('areas', $areas);
        $smarty->assign ('projs', $projs);
	$smarty->assign ('msg', $msg);
	$smarty->assign ('section_title', TITLE . ' - Usuarios');
	$smarty->assign ('file', 'usuarios.html');
	$smarty->display ('index.html');

?>
