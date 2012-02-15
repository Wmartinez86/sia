<?php

if (isset($_REQUEST['idcot'])) {
    $idcot = $_REQUEST['idcot'];
} else {
    $idcot = '0';
}
if($idcot){ 

	$cot = fill_cot(get_cotizacion($idcot));
	$cot['fecha'] = fechita($cot['fecha']);
	$rowsdif = count($cot['detalle']);

$pdf = new Cezpdf("A4");
$pdf->ezSetMargins(0,10,22,22);
$pdf->selectFont(FONTS_URL.'Helvetica.afm');
$pdf->ezSetDy(30);
$datacreator = array (
					'Title'=>'Cotizacion Nro :'.$cot['codigo'],
					'Author'=>$cot['usuario']['username'],//usuario.username
					'Subject'=>'Sistema Generador de Ordenes de Compra y Servicio Para la '.LOG1." ".MUNI,
					'Creator'=>'Jonas V. Coaquira Mamani ',
					'Producer'=>'jonas.coaquira@gmail.com'
					);
$pdf->addInfo($datacreator);

//algunos VAlores
//print_r(get_user($cot["createdby"]));
$fecha=strtotime($cot['fecha']);
$day=strftime('%d',$fecha);
$mes=strftime('%m',$fecha);
$anio=strftime('%Y',$fecha);
$ncot=" <i>".$cot['codigo']."</i>";
$referencia=" <i>".substr(utf8_decode($cot['referencia']),0,90)."</i>";
//Datos de Tabla
$tdet = array('cant'=>'','und'=>'','detalle'=>'','punit'=>'','marca'=>'','ptotal'=>'');
$opdet = array('showHeadings'=>0,'shaded'=>0,'showLines'=>2,'xPos'=>25,'xOrientation' =>'right','width'=>569,'fontSize'=>8,'cols' => array(
	'cant'=>array('justification'=>'center','width'=>40),
	'und'=>array('justification'=>'center','width'=>40),
	'detalle'=>array('justification'=>'left','width'=>292),
	'marca'=>array('justification'=>'right','width'=>53),
	'punit'=>array('justification'=>'right','width'=>55),
	'ptotal'=>array('justification'=>'right','width'=>75)),
	'innerLineThickness'=>0.005,
	'outerLineThickness'=>0.005,
	);

$Cot=$cot['detalle'];
$xi=1;
$inde=0;
$count=1;
$bandera=true;
while ($bandera){
$yi=0;
	unset($datadet);
	$datadet = array();
	while ($rowsdif>$inde and ($yi < 34))
	{
		if($rowsdif>=$inde){
				$yi=$yi+1;
				if($Cot[$inde]['cantidad']==0){
					$cant="";		
					$und="";
					$detalle="<i>".utf8_decode($Cot[$inde]['descripcion'])."</i>";
						$tm=strlen($Cot[$inde]['descripcion']);
						if($tm>=70){
							$yi=$yi+(int)($tm/70);										
						}					
				}else{
					$cant=utf8_decode("<i>".$Cot[$inde]['cantidad']."</i>");		
					$und=utf8_decode("<i>".$Cot[$inde]['umedida']."</i>");
					$detalle="<i>".utf8_decode($Cot[$inde]['descripcion'])."</i>";
						$tm=strlen($Cot[$inde]['descripcion']);
						if($tm>=70){
							$yi=$yi+(int)($tm/70);										
						}					
				}
			}
			$punit="";		
			$ptotal="";
			$inde=$inde+1;
			$datadet[$yi] = array('cant'=>$cant,'und'=>$und,'detalle'=>$detalle,'marca'=>"",'punit'=>$punit,'ptotal'=>$ptotal);
		}
	//lineas
	
	$pdf->setStrokeColor(0,0,0);
	$pdf->setLineStyle(0.5);

	//*********LOGO**********
	if(true){
		$logx=95;
		$logy=800;
		$logz=12;	
		$pdf->selectFont(FONTS_URL.'Helvetica.afm');
		$pdf->addText($logx,$logy,$logz,utf8_decode(LOG1)); 
		$pdf->addText($logx,$logy-14,$logz+2,"<b>".utf8_decode(MUNI2)."</b>"); 
		$pdf->addText($logx,$logy-25,$logz-2,utf8_decode("<i>".LOG2."</i>")); 
		$pdf->addJpegFromFile(CURRENT_SITE .'logo.jpg',30,755,50,60);//		$pdf->selectFont(FONTS_URL.'Courier.afm');
	}
	//Curvas - Cuerpo
	$y=815;$x=13;$pdf->curve($x,$y,$x,$y+15,$x,$y+15,$x+15,$y+15);
	$y1=28;$x1=13;$pdf->curve($x1,$y1,$x1,$y1-15,$x1,$y1-15,$x1+15,$y1-15);
	$y2=815;$x2=552;$pdf->curve($x2+15,$y2+15,$x2+30,$y2+15,$x2+30,$y2+15,$x2+30,$y2);
	$y3=28;$x3=552;$pdf->curve($x3+15,$y3-15,$x3+30,$y3-15,$x3+30,$y3-15,$x3+30,$y3);
	$pdf->line($x,$y,$x1,$y1);//V1
	$pdf->line($x2+30,$y2,$x3+30,$y3);//V1
	$pdf->line($x+15,$y+15,$x2+15,$y2+15);//H1
	$pdf->line($x1+15,$y1-15,$x3+15,$y3-15);//H1
	//Nro de Cotizacion
	$x=489;$y=800;
	$pdf->rectangle($x-10,$y,90,20);
	$pdf->addText($x-8,$y+6,11,"Nro:".$ncot." - Pg.".$xi);
	//FECHA
	$x=$x-15;
	$pdf->rectangle($x,$y-35,95,30);
	$pdf->line($x+26,$y-35,$x+26,$y-5);
	$pdf->line($x+53,$y-35,$x+53,$y-5);
	$pdf->line($x,$y-17,$x+95,$y-17);
	$pdf->addText($x+5,$y-15,10,"<b>Dia</b>");
	$pdf->addText($x+30,$y-15,10,"<b>Mes</b>");
	$pdf->addText($x+63,$y-15,10,utf8_decode("<b>Año</b>"));
	$pdf->addText($x+5,$y-30,10,$day);
	$pdf->addText($x+30,$y-30,10,$mes);
	$pdf->addText($x+63,$y-30,10,$anio);
	
	$clix=40;
	$cliy=754;
	$cliz=8;
	$pdf->addText($clix+155,$cliy+4,14,"<b>SOLICITUD DE COTIZACION</b>");
	$y=760;$x=194;$pdf->curve($x,$y,$x,$y+15,$x,$y+15,$x+15,$y+15);
	$y1=767;$x1=194;$pdf->curve($x1,$y1,$x1,$y1-15,$x1,$y1-15,$x1+15,$y1-15);
	$y2=760;$x2=356;$pdf->curve($x2+15,$y2+15,$x2+30,$y2+15,$x2+30,$y2+15,$x2+30,$y2);
	$y3=767;$x3=356;$pdf->curve($x3+15,$y3-15,$x3+30,$y3-15,$x3+30,$y3-15,$x3+30,$y3);
	$pdf->line($x,$y,$x1,$y1);//V1
	$pdf->line($x2+30,$y2,$x3+30,$y3);//V1
	$pdf->line($x+15,$y+15,$x2+15,$y2+15);//H1
	$pdf->line($x1+15,$y1-15,$x3+15,$y3-15);//H1
	$pdf->addText($clix,$cliy-14,$cliz,"<b>REFERENCIA :</b>         ".$referencia);
	$pdf->line($clix+75,$cliy-15,$clix+420,$cliy-15);
	$pdf->addText($clix,$cliy-28,$cliz,utf8_decode("<b>SEÑOR(ES) :</b>"));
	$pdf->line($clix+75,$cliy-30,$clix+420,$cliy-30);
	$pdf->addText($clix,$cliy-42,$cliz,"<b>DIRECCION :</b>");
	$pdf->line($clix+75,$cliy-43,$clix+420,$cliy-43);
	$pdf->addText($clix,$cliy-56,$cliz,"<b>RUC :</b>");
	$pdf->line($clix+75,$cliy-57,$clix+198,$cliy-57);
	$pdf->addText($clix+200,$cliy-56,$cliz,"<b>TELEFONO(FAX) :</b>");
	$pdf->line($clix+290,$cliy-57,$clix+420,$cliy-57);
	$pdf->addText($clix,$cliy-70,$cliz,utf8_decode("<i><b>Sirva(n)se cotizarnos PRECIOS NETOS incluídos los impuestos de los siguientes ITEMS que se detallan a continuación: </b></i>"));
	$pdf->addText($clix+423,$cliy-56,$cliz,"<b>RNP :</b> (SI) (NO) *");
	
	//$pdf->selectFont(FONTS_URL.'Courier.afm');
	$y=644;
	$x=30;
	$z=9;
	$pdf->addText(245,$y+17,$z,"<b>ARTICULOS</b>");
	$pdf->addText(500,$y+17,$z,"<b>PRECIO</b>");
	$pdf->addText($x,$y,$z,"<b>Cant.</b>");
	$pdf->addText($x+35,$y,$z,"<b>U.Med</b>");
	$pdf->addText($x+220,$y,$z,utf8_decode("<b>Descripción</b>"));
	$pdf->addText($x+375,$y,$z,"<b>Marca</b>");
	$pdf->addText($x+425,$y,$z,"<b>Unitario</b>");
	$pdf->addText($x+495,$y,$z,"<b>TOTAL</b>");
		//Curvas - Cuerpo
	$y=660;$x=20;$pdf->curve($x,$y,$x,$y+15,$x,$y+15,$x+15,$y+15);
	$y1=180;$x1=20;$pdf->curve($x1,$y1,$x1,$y1-15,$x1,$y1-15,$x1+15,$y1-15);
	$y2=660;$x2=545;$pdf->curve($x2+15,$y2+15,$x2+30,$y2+15,$x2+30,$y2+15,$x2+30,$y2);
	$y3=180;$x3=545;$pdf->curve($x3+15,$y3-15,$x3+30,$y3-15,$x3+30,$y3-15,$x3+30,$y3);
	$pdf->line($x,$y,$x1,$y1);//V1
	$pdf->line($x2+30,$y2,$x3+30,$y3);//V1
	$pdf->line($x+15,$y+15,$x2+15,$y2+15);//H1
	$pdf->line($x1+15,$y1-15,$x3+15,$y3-15);//H1
	$pdf->line(20,655,575,655);//H1
	$pdf->line(20,640,575,640);//H1
	$pdf->line(60,655,60,165);//V1
	$pdf->line(100,655,100,165);//V1
	$pdf->line(392,655,392,165);//V1
	$pdf->line(447,675,447,165);//V1
	$pdf->line(500,655,500,165);//V1
	
		//Cuerpo
			$newdata = $datadet;
			$pdf->ezSetDy(-200);
			$pdf->ezTable($newdata,$tdet,'',$opdet);

	// Firmas
	$clix=40;
	$cliy=150;
	$cliz=8;
	$pdf->addText($clix,$cliy,$cliz,"<b>INDICAR:</b> MARCA, CARACTERISTICAS, INCLUIR I.G.V EN EL PRECIO");
//	$pdf->line($clix-20,$cliy+30,$clix+535,$cliy+30);
	$pdf->addText($clix+420,$cliy+18,$cliz,"<b>TOTAL:</b>");
	$pdf->addText($clix,$cliy-15,$cliz,"- Si por cualquier causa no esta en condiciones de cotizar, sirva(n) se Ud.(s) firmar y devolver este documento");
	$pdf->addText($clix,$cliy-30,$cliz,"- Si esta en condiciones de cotizar, sirva(n)se Ud.(s) hacerlo, firmar y devolver este documento en SOBRE CERRADO");
	$pdf->addText($clix,$cliy-50,$cliz,"Plazo de entrega: ");
	$pdf->addText($clix+300,$cliy-50,$cliz,"Fecha:");
	$pdf->addText($clix+20,$cliy-120,$cliz,utf8_decode("Firma de Autorización"));
	$pdf->addText($clix+30,$cliy-130,$cliz,"(Abastecimiento)");
	if(forma){
		$pdf->addText($clix+210,$cliy-120,$cliz,"Firma de Cotizador");
		$pdf->line($clix+200,$cliy-110,$clix+305,$cliy-110);
	}
	$pdf->addText($clix+360,$cliy-120,$cliz,"Firma y Sello de Proveedor");
	$pdf->line($clix+10,$cliy-110,$clix+128,$cliy-110);
	$pdf->line($clix+350,$cliy-110,$clix+493,$cliy-110);


	
	$pdf->line($clix+85,$cliy-55,$clix+285,$cliy-55);
	$pdf->line($clix+340,$cliy-55,$clix+485,$cliy-55);
	
	if ($inde>$rowsdif-1){
		$bandera=false;
		}
	else{		
		$pdf->ezNewPage();
		$xi=$xi+1;
	}
}	
$pdf->ezStream();
}else{
	$smarty->display ('error.html');
}
?>
