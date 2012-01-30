<?php

        /**
         * $allowed es la lista de páginas que son permitidas 
         * a los usuarios que no son administradores
         */
	$allowed = array(
					 "/error.php",
					 "/index.php",
                                         "/requerimiento.php",
                                         "/requerimiento-lista.php",
                                         "/requerimientonew.php",
                                         "/requerimiento-print.php",
                                         "/borrar-detalle-requerimiento.php",
					 "/",
					 );

	if(!isset($session_active)) {
		header("Location: ". BASE_URL . "login.php?r=" . $_SERVER['PHP_SELF']);
		exit();
	}

	if(!is_admin($_SESSION['loginuser']['iduser'])) {
		if(!in_array($_SERVER['PHP_SELF'], $allowed)) {
			header("Location: ". BASE_URL . "error.php");
			exit();
		}
	}
?>