<?php

require_once('home.php');
require_once('redirect.php');


$ordenes = array();
$projs = get_projs();
$users = get_admins();

if(isset($_GET['excel'])) {
  
  $op = htmlspecialchars($_GET['op']);
  switch($op) {
    case 'search':
        if(!is_admin()) {
            header("location: error.php");
            exit();
        }
        $idproyecto = htmlspecialchars($_GET['idproyecto']);
        $iduser = htmlspecialchars($_GET['iduser']);
        $ordenes = search_orden_compra($idproyecto, $iduser);

    break;
    case 'number':
        $codigo = htmlspecialchars($_GET['codigo']);
        $ordenes = get_ordenes_by_codigo($codigo, 'compra');
    break;
    case 'proveedor':
        $nombre = htmlspecialchars($_GET['nombre']);
        $ordenes = get_ordenes_by_nombre_prov($nombre, 'compra');
    break;
    case 'ruc':
        $ruc = htmlspecialchars($_GET['ruc']);
        $ordenes = get_ordenes_by_ruc_prov($ruc, 'compra');
    break;
  }
  
  $ordenes = fill_compras($ordenes);
  
  $atotal = 0;
  foreach($ordenes as $k=>$v) {
    $ordenes[$k]['stotal'] = supertotal($v['detalle'], 'total');
    $atotal += supertotal($v['detalle'], 'total');
  }
  
  require_once(INCLUDE_PATH . 'phpexcel/Classes/PHPExcel.php');
  $objPHPExcel = new PHPExcel();

  $objPHPExcel->getProperties()->setCreator("CDTI")
                                                          ->setLastModifiedBy("CDTI")
                                                          ->setTitle("Informe de Órdenes")
                                                          ->setSubject("Informe de Órdenes")
                                                          ->setDescription("Lista de órdenes")
                                                          ->setKeywords("cdti informe")
                                                          ->setCategory("cdti");

  // Set default font
  $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial');
  $objPHPExcel->getDefaultStyle()->getFont()->setSize(10);

  // Add some data, resembling some different data types
  //Cabeceras
  $objPHPExcel->getActiveSheet()->setCellValue('A1', 'Código');
  $objPHPExcel->getActiveSheet()->setCellValue('B1', 'Documento');
  $objPHPExcel->getActiveSheet()->setCellValue('C1', 'Sec Func');
  $objPHPExcel->getActiveSheet()->setCellValue('D1', 'Proyecto');
  $objPHPExcel->getActiveSheet()->setCellValue('E1', 'Proveedor');
  $objPHPExcel->getActiveSheet()->setCellValue('F1', 'RUC Proveedor');
  $objPHPExcel->getActiveSheet()->setCellValue('G1', 'Facturado a');
  $objPHPExcel->getActiveSheet()->setCellValue('H1', 'Fecha');
  $objPHPExcel->getActiveSheet()->setCellValue('I1', 'Creado por');
  $objPHPExcel->getActiveSheet()->setCellValue('J1', 'Total');


  $counter = 2;
  if($ordenes) {
      foreach($ordenes as $k => $orden) {
          $objPHPExcel->getActiveSheet()->setCellValue(sprintf('A%s', $counter), $orden['codigo']);
          $objPHPExcel->getActiveSheet()->setCellValue(sprintf('B%s', $counter), $orden['doc']['nombre'] . ": " . $orden['nrodoc']);
          $objPHPExcel->getActiveSheet()->setCellValue(sprintf('C%s', $counter), sprintf("%s", $orden['proyecto']['sec_func']));
          $objPHPExcel->getActiveSheet()->setCellValue(sprintf('D%s', $counter), $orden['proyecto']['descripcion']);
          $objPHPExcel->getActiveSheet()->setCellValue(sprintf('E%s', $counter), $orden['proveedor']['razonsocial']);
          $objPHPExcel->getActiveSheet()->setCellValue(sprintf('F%s', $counter), $orden['proveedor']['ruc']);
          $objPHPExcel->getActiveSheet()->setCellValue(sprintf('G%s', $counter), $orden['facturarto']);
          $objPHPExcel->getActiveSheet()->setCellValue(sprintf('H%s', $counter), $orden['fecha']);
          $objPHPExcel->getActiveSheet()->setCellValue(sprintf('I%s', $counter), $orden['usuario']['username']);
          $objPHPExcel->getActiveSheet()->setCellValue(sprintf('J%s', $counter), $orden['stotal']);
          $counter++;
      }
  }

  // Cabeceras negrita
  $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
  $objPHPExcel->getActiveSheet()->getStyle('B1')->getFont()->setBold(true);
  $objPHPExcel->getActiveSheet()->getStyle('C1')->getFont()->setBold(true);
  $objPHPExcel->getActiveSheet()->getStyle('D1')->getFont()->setBold(true);
  $objPHPExcel->getActiveSheet()->getStyle('E1')->getFont()->setBold(true);
  $objPHPExcel->getActiveSheet()->getStyle('F1')->getFont()->setBold(true);
  $objPHPExcel->getActiveSheet()->getStyle('G1')->getFont()->setBold(true);
  $objPHPExcel->getActiveSheet()->getStyle('H1')->getFont()->setBold(true);
  $objPHPExcel->getActiveSheet()->getStyle('I1')->getFont()->setBold(true);
  $objPHPExcel->getActiveSheet()->getStyle('J1')->getFont()->setBold(true);

  $objPHPExcel->getActiveSheet()->setAutoFilter(sprintf('A1:J%s', $counter));	// Always include the complete filter range!

  // Rename sheet
  $objPHPExcel->getActiveSheet()->setTitle('Ordenes Compra');

  // Set active sheet index to the first sheet, so Excel opens this as the first sheet
  $objPHPExcel->setActiveSheetIndex(0);


  header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
  header('Content-Disposition: attachment;filename="ordenes-compra-' . time() .'.xlsx"');
  header('Cache-Control: max-age=0');

  $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
  $objWriter->save('php://output');
  exit;
}

/* Búsqueda y Filtro */
if(isset($_GET['submit'])) {
    $smarty->assign('buscado', "buscado");
    $op = htmlspecialchars($_GET['op']);
    switch($op) {
        case 'search':
            if(!is_admin()) {
                header("location: error.php");
                exit();
            }
            $idproyecto = htmlspecialchars($_GET['idproyecto']);
            $iduser = htmlspecialchars($_GET['iduser']);
            $ordenes = search_orden_compra($idproyecto, $iduser);

            $smarty->assign('idproyecto', $idproyecto);
            $smarty->assign('iduser', $iduser);
            
        break;
        case 'number':
            $codigo = htmlspecialchars($_GET['codigo']);
            $ordenes = get_ordenes_by_codigo($codigo, 'compra');
            $smarty->assign('codigo', $codigo);
        break;
        case 'proveedor':
            $nombre = htmlspecialchars($_GET['nombre']);
            $ordenes = get_ordenes_by_nombre_prov($nombre, 'compra');
            $smarty->assign('nombre', $nombre);
        break;
        case 'ruc':
            $ruc = htmlspecialchars($_GET['ruc']);
            $ordenes = get_ordenes_by_ruc_prov($ruc, 'compra');
            $smarty->assign('ruc', $ruc);
        break;
    }
    
    //d($ordenes);
} else {
    $pager = true;
    $ordenes = get_ordenes_compra();
}

if($ordenes){
	$ordenes = fill_compras($ordenes);
        $atotal = 0;
		$field = 'total';
		foreach($ordenes as $k=>$v) {
			$ordenes[$k]['stotal'] = supertotal($v['detalle'], $field);
		}
}

//d($ordenes[0]['detalle']);
if($pager) $smarty->assign ('RESULTS', $bcrs->get_navigation());
$smarty->assign ('ordenes', $ordenes);
$smarty->assign ('projs', $projs);
$smarty->assign ('users', $users);
if(isset($op)) {
  $smarty->assign ('op', $op);
  $smarty->assign ('querystring', strip_tags($_SERVER['QUERY_STRING']));
}
$smarty->assign ('section_title', TITLE . ' - &Oacute;rdenes de Compra');
$smarty->assign ('file', 'orden-compra-lista.html');
if((stristr(MY_SITE, 'carhuayo'))) $smarty->assign('memo', TRUE); 
$smarty->display ('index.html');

?>