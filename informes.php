<?php

require_once('home.php');
require_once('redirect.php');
//$pager = true;
$status = 0;
$postback = isset($_GET['orden']);
if($postback) {
	$orden = $_GET['orden'];
	$idproyecto = $_GET['idproyecto'];
	$iddoc = $_GET['iddoc'];
	$idfuente = $_GET['idfuente'];
	$status = $_GET['status'];
	$fecha1 = $_GET['fecha1'];
	$fecha2 = $_GET['fecha2'];
	$ruc = (strlen($_GET['ruc'])>0) ? $_GET['ruc'] : '';
	$search_values = array(
			'iddoc' => $_GET['iddoc'],
			'idproyecto' => $_GET['idproyecto'],
			'idfuente' => $_GET['idfuente'],
			'status' => $_GET['status']
	);
	
	$prov = get_prov_by_ruc($ruc);
	$search_values['idproveedor'] = $prov['idproveedor'];
	if(strlen($ruc)<11&&!empty($ruc))
		$search_values['idproveedor'] = 'otro';
	$ordenes = search_ordenes($search_values, $bcdb->$orden, fechita($fecha1), fechita($fecha2));
	if($ordenes) {
		if($orden == "ordencompra") {
			$ordenes = fill_compras($ordenes);
		}else{
			$ordenes = fill_servicios($ordenes);
		}
		$atotal = 0;
		$field = ($orden=="ordencompra") ? 'total' : 'precio';
		foreach($ordenes as $k=>$v) {
			$ordenes[$k]['stotal'] = supertotal($v['detalle'], $field);
			$atotal += supertotal($v['detalle'], $field);
		}
		$smarty->assign ('atotal', $atotal);
	}
	
	
	$smarty->assign ('ordenes', $ordenes);
	$smarty->assign ('postback', $postback);
	$smarty->assign ('idproyecto', $idproyecto);
	$smarty->assign ('iddoc', $iddoc);
	$smarty->assign ('idfuente', $idfuente);
	$smarty->assign ('ruc', $ruc);

	$smarty->assign ('fecha1', $fecha1);
	$smarty->assign ('fecha2', $fecha2);
	$smarty->assign ('orden', $orden);
	
	if(isset($_GET['excel'])) {
            
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
            $objPHPExcel->getActiveSheet()->setCellValue('K1', 'Fecha');
            
            
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
                    $objPHPExcel->getActiveSheet()->setCellValue(sprintf('K%s', $counter), $orden['fecha']);
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
            $objPHPExcel->getActiveSheet()->getStyle('K1')->getFont()->setBold(true);
            
            $objPHPExcel->getActiveSheet()->setAutoFilter(sprintf('A1:K%s', $counter));	// Always include the complete filter range!

            // Rename sheet
            $objPHPExcel->getActiveSheet()->setTitle('Ordenes');

            // Set active sheet index to the first sheet, so Excel opens this as the first sheet
            $objPHPExcel->setActiveSheetIndex(0);


            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="informe' . time() .'.xlsx"');
            header('Cache-Control: max-age=0');

            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            $objWriter->save('php://output');
            exit;
            
	} elseif(isset($_GET['excelproductos'])) {
            
            require_once(INCLUDE_PATH . 'phpexcel/Classes/PHPExcel.php');
            
            $objPHPExcel = new PHPExcel();

            $objPHPExcel->getProperties()->setCreator("CDTI")
                                                                    ->setLastModifiedBy("CDTI")
                                                                    ->setTitle("Informe de Órdenes")
                                                                    ->setSubject("Informe de Órdenes")
                                                                    ->setDescription("Lista de productos por ordenes")
                                                                    ->setKeywords("cdti informe")
                                                                    ->setCategory("cdti");

            // Set default font
            $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial');
            $objPHPExcel->getDefaultStyle()->getFont()->setSize(10);

            // Add some data, resembling some different data types
            //Cabeceras
            $objPHPExcel->getActiveSheet()->setCellValue('A1', 'cadena');
            $objPHPExcel->getActiveSheet()->setCellValue('B1', 'sec_func');
            $objPHPExcel->getActiveSheet()->setCellValue('C1', 'meta');
            $objPHPExcel->getActiveSheet()->setCellValue('D1', 'codigo_orden');
            $objPHPExcel->getActiveSheet()->setCellValue('E1', 'especifica');
            $objPHPExcel->getActiveSheet()->setCellValue('F1', 'cantidad');
            $objPHPExcel->getActiveSheet()->setCellValue('G1', 'descripcion');
            $objPHPExcel->getActiveSheet()->setCellValue('H1', 'precio_unitario');
            
            
            $counter = 2;
            if($ordenes) {
                foreach($ordenes as $k => $orden) {
                    $sec_func = $orden['proyecto']['sec_func'];
                    $cadena = cadena_funcional($orden['proyecto']);
                    $meta = $orden['proyecto']['descripcion'];
                    $codigo = $orden['codigo'];
                    foreach($orden['detalle'] as $j => $detalle) {
                        $objPHPExcel->getActiveSheet()->setCellValue(sprintf('A%s', $counter), $cadena);
                        $objPHPExcel->getActiveSheet()->setCellValue(sprintf('B%s', $counter), $sec_func);
                        $objPHPExcel->getActiveSheet()->setCellValue(sprintf('C%s', $counter), $meta);
                        $objPHPExcel->getActiveSheet()->setCellValue(sprintf('D%s', $counter), $codigo);
                        $objPHPExcel->getActiveSheet()->setCellValue(sprintf('E%s', $counter), $detalle['especifica']);
                        $objPHPExcel->getActiveSheet()->setCellValue(sprintf('F%s', $counter), $detalle['cantidad']);
                        $objPHPExcel->getActiveSheet()->setCellValue(sprintf('G%s', $counter), $detalle['descripcion']);
                        $objPHPExcel->getActiveSheet()->setCellValue(sprintf('H%s', $counter), $detalle['precio']);
                    }
                    $counter++;
                }
            }
            
            // Filtro
            $objPHPExcel->getActiveSheet()->setAutoFilter(sprintf('A1:H%s', $counter));	// Always include the complete filter range!

            // Rename sheet
            $objPHPExcel->getActiveSheet()->setTitle('Ordenes');

            // Set active sheet index to the first sheet, so Excel opens this as the first sheet
            $objPHPExcel->setActiveSheetIndex(0);


            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="productos' . time() .'.xlsx"');
            header('Cache-Control: max-age=0');

            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            $objWriter->save('php://output');
            exit;
            
        }
}
//d($ordenes);
$smarty->assign ('status', $status);

$projs = get_projs();
$provs = get_provs();
$fuentes = get_fuentes();
$docs = get_docs();
$especs = get_especificas();

$smarty->assign ('atipos', $atipos);
$smarty->assign ('projs', $projs);
$smarty->assign ('provs', $provs);
$smarty->assign ('fuentes', $fuentes);
$smarty->assign ('docs', $docs);
$smarty->assign ('especs', $especs);

$smarty->assign ('section_title', TITLE . ' - Informes');
$smarty->assign ('file', 'informes.html');
$smarty->display ('index.html');

?>
