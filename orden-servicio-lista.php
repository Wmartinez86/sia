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
          $ordenes = search_orden_servicio($idproyecto, $iduser);

      break;
      case 'number':
          $codigo = htmlspecialchars($_GET['codigo']);
          $ordenes = get_ordenes_by_codigo($codigo, 'servicio');
      break;
      case 'proveedor':
          $nombre = htmlspecialchars($_GET['nombre']);
          $ordenes = get_ordenes_by_nombre_prov($nombre, 'servicio');
      break;
      case 'ruc':
          $ruc = htmlspecialchars($_GET['ruc']);
          $ordenes = get_ordenes_by_ruc_prov($ruc, 'servicio');
      break;
  }
  
  $ordenes = fill_servicios($ordenes);
  
  $atotal = 0;
  foreach($ordenes as $k=>$v) {
    $ordenes[$k]['stotal'] = supertotal($v['detalle'], 'precio');
    $atotal += supertotal($v['detalle'], 'precio');
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
  $objPHPExcel->getActiveSheet()->setCellValue('A1', 'Código Orden');
  $objPHPExcel->getActiveSheet()->setCellValue('B1', 'Cantidad');
  $objPHPExcel->getActiveSheet()->setCellValue('C1', 'Und');
  $objPHPExcel->getActiveSheet()->setCellValue('D1', 'Descripción');
  $objPHPExcel->getActiveSheet()->setCellValue('E1', 'Precio Unitario');
  $objPHPExcel->getActiveSheet()->setCellValue('F1', 'Total');
  $objPHPExcel->getActiveSheet()->setCellValue('G1', 'Sec Func');
  $objPHPExcel->getActiveSheet()->setCellValue('H1', 'Proyecto');
  $objPHPExcel->getActiveSheet()->setCellValue('I1', 'Proveedor');
  $objPHPExcel->getActiveSheet()->setCellValue('J1', 'RUC Proveedor');
  $objPHPExcel->getActiveSheet()->setCellValue('K1', 'Fecha');


  $counter = 2;
  if($ordenes) {
      foreach($ordenes as $k => $orden) {
        foreach($orden['detalle'] as $det => $detalle) {
          $objPHPExcel->getActiveSheet()->setCellValue(sprintf('A%s', $counter), $orden['codigo']);
          $objPHPExcel->getActiveSheet()->setCellValue(sprintf('B%s', $counter), $detalle['cantidad']);
          $objPHPExcel->getActiveSheet()->setCellValue(sprintf('C%s', $counter), $detalle['umedida']);
          $objPHPExcel->getActiveSheet()->setCellValue(sprintf('D%s', $counter), $detalle['descripcion']);
          $objPHPExcel->getActiveSheet()->setCellValue(sprintf('E%s', $counter), $detalle['precio']);
          $objPHPExcel->getActiveSheet()->setCellValue(sprintf('F%s', $counter), $detalle['precio'] * $detalle['cantidad']);
          $objPHPExcel->getActiveSheet()->setCellValue(sprintf('G%s', $counter), sprintf("%s", $orden['proyecto']['sec_func']));
          $objPHPExcel->getActiveSheet()->setCellValue(sprintf('H%s', $counter), $orden['proyecto']['descripcion']);
          $objPHPExcel->getActiveSheet()->setCellValue(sprintf('I%s', $counter), $orden['proveedor']['razonsocial']);
          $objPHPExcel->getActiveSheet()->setCellValue(sprintf('J%s', $counter), $orden['proveedor']['ruc']);
          $objPHPExcel->getActiveSheet()->setCellValue(sprintf('K%s', $counter), $orden['fecha']);
          $counter++;
        }
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
  $objPHPExcel->getActiveSheet()->getStyle('K1')->getFont()->setBold(true);

  $objPHPExcel->getActiveSheet()->setAutoFilter(sprintf('A1:K%s', $counter));	// Always include the complete filter range!

  // Rename sheet
  $objPHPExcel->getActiveSheet()->setTitle('Ordenes Servicio');

  // Set active sheet index to the first sheet, so Excel opens this as the first sheet
  $objPHPExcel->setActiveSheetIndex(0);


  header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
  header('Content-Disposition: attachment;filename="ordenes-servicio-' . time() .'.xlsx"');
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
            $ordenes = search_orden_servicio($idproyecto, $iduser);
            
            $smarty->assign('idproyecto', $idproyecto);
            $smarty->assign('iduser', $iduser);
            
        break;
        case 'number':
            $codigo = htmlspecialchars($_GET['codigo']);
            $ordenes = get_ordenes_by_codigo($codigo, 'servicio');
            $smarty->assign('codigo', $codigo);
        break;
        case 'proveedor':
            $nombre = htmlspecialchars($_GET['nombre']);
            $ordenes = get_ordenes_by_nombre_prov($nombre, 'servicio');
            $smarty->assign('nombre', $nombre);
        break;
        case 'ruc':
            $ruc = htmlspecialchars($_GET['ruc']);
            $ordenes = get_ordenes_by_ruc_prov($ruc, 'servicio');
            $smarty->assign('ruc', $ruc);
        break;
    }
} else {
    $pager = true;
    $ordenes = get_ordenes_servicio();
}

if($ordenes){
	$ordenes = fill_servicios($ordenes);
}

if($pager) $smarty->assign ('RESULTS', $bcrs->get_navigation());
$smarty->assign ('ordenes', $ordenes);
$smarty->assign ('projs', $projs);
$smarty->assign ('users', $users);
if(isset($op)) {
  $smarty->assign ('op', $op);
  $smarty->assign ('querystring', strip_tags($_SERVER['QUERY_STRING']));
}
$smarty->assign ('section_title', TITLE . ' - &Oacute;rdenes de Servicio');
$smarty->assign ('file', 'orden-servicio-lista.html');
if((stristr(MY_SITE, 'carhuayo'))) $smarty->assign('memo', TRUE);
$smarty->display ('index.html');

?>