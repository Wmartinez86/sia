<?php

require_once('home.php');
require_once('redirect.php');
//$pager = true;

if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
	if ( validate_required(array(
		'Nombre Entidad' => $_POST['nombre_entidad'], 
		'Tipo de Entidad' => $_POST['tipo_entidad'],
                'Ubicación' => $_POST['ubicacion'],
                'RUC de la Entidad' => $_POST['ruc_entidad'],))) {
		
		$option_values = array(
			'nombre_entidad' => $_POST['nombre_entidad'],
			'tipo_entidad' => $_POST['tipo_entidad'],
			'ubicacion' => $_POST['ubicacion'],
			'ruc_entidad' => $_POST['ruc_entidad'],
			'logo' => $_POST['logo'],
		);
		
		$option_values = array_map('strip_tags', $option_values);
		
                foreach($option_values as $name => $description) {
                    save_option($name, $description);
                }
                $msg = "Las opciones fueron guardadas";
	} else 
		$msg = "Hubo un error al guardar las opciones.";
}

$options = get_options();

$smarty->assign ('options', $options);
$smarty->assign ('msg', $msg);
$smarty->assign ('section_title', TITLE . ' - Opciones');
$smarty->assign ('file', 'opciones.html');
$smarty->display ('index.html');

?>
