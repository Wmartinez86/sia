<?php

function get_user ($iduser) {
	global $bcdb;
        $user = $bcdb->get_row("SELECT * FROM $bcdb->usuarios WHERE iduser = '$iduser'");
        $user = user_extra_data($user);
        return $user;
}

function user_extra_data($user) {
    $area = get_area($user['idarea']);
    $proyecto = get_proj($user['idproyecto']);
    $user['area'] = $area;
    $user['proyecto'] = $proyecto;
    return $user;
}

function get_user_by_username($username) {
	global $bcdb;
	$user = $bcdb->get_row("SELECT * FROM $bcdb->usuarios WHERE username = '$username'");
        $user = user_extra_data($user);
        return $user;
}

function get_users () {
	global $bcdb, $bcrs, $pager;
	
	$sql = "SELECT * 
			FROM $bcdb->usuarios
			WHERE iduser != 1 
			ORDER BY iduser";
	$users = ($pager) ? $bcrs->get_results($sql) : $bcdb->get_results($sql);
        
        foreach($users as $k => $user) {
            $user = user_extra_data($user);
            $users[$k] = $user;
        }
        
	return $users;
}

function save_user($iduser, $user_values) {
	global $bcdb, $msg;

	if ($bcdb->get_var("SELECT count(username) FROM $bcdb->usuarios WHERE iduser != '$iduser' AND username = '$user_values[username]'") ) {
		$msg = "Ya existe otro usuario con el mismo 'username'.";
		return false;
	}
	
	if ( $iduser && get_user($iduser) ) {
		unset($user_values['username']); // We don't want someone 'accidentally' update username
	}		
	
	$user_values['iduser'] = $iduser;
	
	if ( ($query = insert_update_query($bcdb->usuarios, $user_values)) &&
		$bcdb->query($query) ) {
		if (empty($iduser))	
			$iduser = $bcdb->insert_id;
		
		$msg = "Los datos han sido guardados satisfactoriamente";
		
		return $iduser;
	}
	$msg = "Hubo un problema al intentar guardar el usuario";
	return false;
}

function remove_user ($iduser) {
	global $bcdb;
	$bcdb->query("DELETE FROM $bcdb->usuarios WHERE iduser = $iduser");
}

function is_admin () {
	global $bcdb;
        $iduser = $_SESSION['loginuser']['iduser'];
        $a = $bcdb->get_var("SELECT usertype FROM $bcdb->usuarios WHERE iduser = $iduser");
	return ($a=="1");
}

?>
