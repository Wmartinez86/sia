<?php

$idorden = ! empty($_REQUEST['idorden']) ? (int)$_REQUEST['idorden'] : 0;

if($idorden){

$orden = fill_compra(get_orden_compra($idorden));
$pdf = new Cezpdf("A4",'landscape');
$pdf->ezSetDy(30);
$pdf->setStrokeColor(0,0,0);
$pdf->setLineStyle(0.5);
$pdf->ezSetMargins(0,10,60,60);
$pdf->selectFont(FONTS_URL.'Helvetica.afm');

//algunos VAlores
$norden=utf8_decode($orden['codigo']);
$cliente=utf8_decode($orden['proveedor']['razonsocial'])."  ";
$ruc1=$orden['proveedor']['ruc'];
$sec_func= $orden['proyecto']['sec_func'];
$descripcion=utf8_decode($orden['proyecto']['descripcion']);
$fte=utf8_decode($orden['fuente']['nombre']);


//	$pdf->rectangle(20,20,790,555);//borde
$nameanio=utf8_decode('AÑO DE LA INTEGRACIÓN NACIONAL Y EL RECONOCIMIENTO DE NUESTRA DIVERSIDAD');
$tdet = array('main'=>$nameanio,'centro'=>'', 'copy'=>$nameanio);
$opdet = array('showHeadings'=>1,'shaded'=>0,'showLines'=>0,'xPos'=>31,'xOrientation' =>'right','fontSize'=>11, 'width'=>780,'cols' => array(
'main'=>array('justification'=>'center','width'=>370),
'centro'=>array('justification'=>'left','width'=>40),
'copy'=>array('justification'=>'center','width'=>370)),	
);
	$logx=95;
	$logy=550;
	$logz=12;	
	$pdf->addText($logx,$logy,$logz,utf8_decode(LOG1)); 
	$pdf->addText($logx,$logy-14,$logz+2,"<b>".utf8_decode(MUNI2)."</b>"); 
	$pdf->addText($logx,$logy-25,$logz-2,utf8_decode("<i>".LOG2."</i>")); 
	$pdf->addJpegFromFile(CURRENT_SITE .'logo.jpg',30,510,50,60);
	$logx=500;
	$logy=550;
	$pdf->addText($logx,$logy,$logz,utf8_decode(LOG1)); 
	$pdf->addText($logx,$logy-14,$logz+2,"<b>".utf8_decode(MUNI2)."</b>"); 
	$pdf->addText($logx,$logy-25,$logz-2,utf8_decode("<i>".LOG2."</i>")); 
	$pdf->addJpegFromFile(CURRENT_SITE .'logo.jpg',440,510,50,60);

$opdet2 = array('showHeadings'=>0,'shaded'=>0,'showLines'=>0,'xPos'=>31,'xOrientation' =>'right','fontSize'=>11, 'width'=>780,'cols' => array(
'main'=>array('justification'=>'left','width'=>370),
'centro'=>array('justification'=>'left','width'=>40),
'copy'=>array('justification'=>'left','width'=>370)),	
);
$nmemo=utf8_decode("<b> MEMORANDO Nº              -2012-MDCC-Q/GM.</b>");
$datos['1']=utf8_decode("<b>DE</b>	              : Prof. BERNARDO REGAÑO PRUDENCIO");
$datos['2']=utf8_decode("<b>A</b>	                : Responsable de La oficina de Tesoreria ");
$datos['3']=utf8_decode("<b>ASUNTO</b>     : GIRAR CHEQUE DE ORDEN DE COMPRA Nro ".$norden);
$datos['4']=utf8_decode("<b>FECHA</b>	      : ".utf8_decode(MUNI2).",");
$totalidad=0;
foreach($orden['detalle'] as $k => $v) {
	$totalidad += $orden['detalle'][$k]['total'];
}
$totalidad=number_format($totalidad, 2, ".", ",");
$son=convertir($totalidad, true);
$datos['5']=utf8_decode("Por el intermedio de la presente me dirijo a Ud. Disponiéndole tenga a bien de girar el cheque correspondiente a la meta".$sec_func." ".$descripcion.", Con fuente de financiamiento de ".$fte.", por la suma de ".$totalidad." (".$son.") a nombre de ".$cliente.". Por la contratacion prestado a la Municipalidad en la adquisición detallada en la orden Nº ".$norden.". 

Dar cumplimiento bajo responsabilidad y pagar al proveedor de acuerdo a la orden de compra y los documentos de ley.

Atentamente");
$datos['6']=utf8_decode("DE	      : Prof. BERNARDO REGAÑO PRUDENCIO");
$encabezado[]= array('main'=>"",'centro'=>" ",'copy'=>""); 
$encabezado[]= array('main'=>$nmemo,'centro'=>" ",'copy'=>$nmemo); 
$cuerpo[]= array('main'=>"",'centro'=>" ",'copy'=>""); 
$cuerpo[]= array('main'=>$datos['1'],'centro'=>" ",'copy'=>$datos['1']); 
$cuerpo[]= array('main'=>"",'centro'=>" ",'copy'=>""); 
$cuerpo[]= array('main'=>$datos['2'],'centro'=>" ",'copy'=>$datos['2']); 
$cuerpo[]= array('main'=>"",'centro'=>" ",'copy'=>""); 
$cuerpo[]= array('main'=>$datos['3'],'centro'=>" ",'copy'=>$datos['3']); 
$cuerpo[]= array('main'=>"",'centro'=>" ",'copy'=>""); 
$cuerpo[]= array('main'=>$datos['4'],'centro'=>" ",'copy'=>$datos['4']); 
$cuerpo[]= array('main'=>"",'centro'=>" ",'copy'=>""); 
$cuerpo[]= array('main'=>$datos['5'],'centro'=>" ",'copy'=>$datos['5']); 
$cuerpo[]= array('main'=>"",'centro'=>" ",'copy'=>""); 
//$cuerpo[]= array('main'=>$dequien,'centro'=>" ",'copy'=>$dequien); 

$pdf->ezSetDy(-100);
$pdf->ezTable($encabezado,$tdet,'',$opdet);	
$pdf->ezTable($cuerpo,$tdet,'',$opdet2);	
//d($totalidad);
	
$pdf->ezStream();
}else{

$smarty->display ('error.html');
}

?>
	