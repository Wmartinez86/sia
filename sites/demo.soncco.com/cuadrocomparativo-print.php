<?php

if (isset($_REQUEST['idcot'])) { 
    $idcot = $_REQUEST['idcot'];
} else {
    $idcot = '0';
}

//$idcot = ! empty($_REQUEST['idcot']) ? (int)$idcot : 0;
if($idcot){ 
	$idcuadro=get_cuadro_cot($idcot);
	$cuadro = get_cuadro($idcuadro); //idcuadro 	idcot 	fecha 	idproveedor 	monto 	justificacion 	Observacion 	cep
	$cuadro['fecha'] = fechita3($cuadro['fecha']);
	$cot = fill_cot_cuadro(get_cotizacion($idcot));
	$rowsdif = count($cot['detalle']);
	$rows = 30-(int)$rowsdif;
	if($rows<=0) $rows = 0;

$pdf = new Cezpdf("A4",'landscape');
$pdf->ezSetMargins(0,10,15,15);
$pdf->selectFont(FONTS_URL.'Helvetica.afm');
$pdf->ezSetDy(30);
$pdf->setStrokeColor(0,0,0);
$pdf->setLineStyle(0.5);
$datacreator = array (
					'Title'=>'Cuadro comparativo Nro :'.$cuadro['idcuadro'],
//					'Author'=>$cot['usuario']['username'],//usuario.username
					'Subject'=>'Sistema Generador de Ordenes de Compra y Servicio Para la '.LOG1." ".MUNI,
					'Creator'=>'Jonas V. Coaquira Mamani ',
					'Producer'=>'jonas.coaquira@gmail.com'
					);
$pdf->addInfo($datacreator);
//algunos VAlores
$day=12;
$mes=01;
$anio=2011;
$ncot=1234;
//
$tdet = array('items'=>'', 'cant'=>'','und'=>'','detalle'=>'','punit1'=>'','ptotal1'=>'','punit2'=>'','ptotal2'=>'','punit3'=>'','ptotal3'=>'');
$opdet = array('showHeadings'=>0,'shaded'=>0,'showLines'=>0,'xPos'=>21,'xOrientation' =>'right','width'=>569,'fontSize'=>9,'cols' => array(
'items'=>array('justification'=>'center','width'=>35),
'cant'=>array('justification'=>'center','width'=>54),
'und'=>array('justification'=>'center','width'=>40),
'detalle'=>array('justification'=>'left','width'=>261),
'punit1'=>array('justification'=>'right','width'=>55),
'ptotal1'=>array('justification'=>'right','width'=>82),
'punit2'=>array('justification'=>'right','width'=>55),
'ptotal2'=>array('justification'=>'right','width'=>82),
'punit3'=>array('justification'=>'right','width'=>55),
'ptotal3'=>array('justification'=>'right','width'=>82)),	
'innerLineThickness'=>0.0005,
'outerLineThickness'=>0.0005,
);
$ncar=$rowsdif;
if(($ncar %20)!=0){
	$count=(int)($ncar/20)+1;
}else{
	$count=$ncar/20;
}
$ff=true;
$items=0;
$total1=0;		$total2=0;		$total3=0;
$j=-1;
$xi=1;
$inde=0;
$count=1;
$bandera=true;
while ($bandera){
$yi=0;
	unset($datadet);
	$datadet = array();
	while ($rowsdif>$inde and ($yi < 23))
	{
		if($rowsdif>=$inde){
				$yi=$yi+1;
				if($cot['detalle'][$inde]['cantidad']==0){ 
					$cant=""; $und="";
					$detalle=utf8_decode($cot['detalle'][$inde]['descripcion']);
						$tm=strlen($cot['detalle'][$inde]['descripcion']);
						if($tm>=60){
							$yi=$yi+(int)($tm/60);										
						}					
					$punit1="";$ptotal1="";$punit2="";
					$ptotal2="";$punit3="";
					$ptotal3="";
					$total1=$total1+$ptotal1;
					$total2=$total2+$ptotal2;
					$total3=$total3+$ptotal3;
				}else{
					$cant=$cot['detalle'][$inde]['cantidad'];
					$und=$cot['detalle'][$inde]['umedida'];
					$detalle=utf8_decode($cot['detalle'][$inde]['descripcion']);
						$items=$items+1;
						$tm=strlen($cot['detalle'][$inde]['descripcion']);
						if($tm>=60){
							$yi=$yi+(int)($tm/60);										
						}										
					$punit1=$cot['precio1'][$inde]['precio'];
					$ptotal1=$cant*$punit1;
					$punit2=$cot['precio2'][$inde]['precio'];
					$ptotal2=$cant*$punit2;
					$punit3=$cot['precio3'][$inde]['precio'];
					$ptotal3=$cant*$punit3;
					$punit1=number_format($punit1, 2, ".", ",");
					$punit2=number_format($punit2, 2, ".", ",");
					$punit3=number_format($punit3, 2, ".", ",");
					$total1=$total1+$ptotal1;
					$total2=$total2+$ptotal2;
					$total3=$total3+$ptotal3;
					$ptotal1=number_format($ptotal1, 2, ".", ",");
					$ptotal2=number_format($ptotal2, 2, ".", ",");
					$ptotal3=number_format($ptotal3, 2, ".", ",");
				}
			}
			$inde=$inde+1;			
			$datadet[$xi][] = array('items'=>$items,'cant'=>$cant,'und'=>$und,'detalle'=>utf8_decode($detalle),
					'punit1'=>$punit1,'ptotal1'=>$ptotal1,
					'punit2'=>$punit2,'ptotal2'=>$ptotal2,
					'punit3'=>$punit3,'ptotal3'=>$ptotal3);
		}
		
		$newdata = $datadet[$xi];
	//HOJAS	
	$clix=256;
	$cliy=548;
	$cliz=8;	
	$pdf->addText($clix,$cliy,14,"<b>CUADRO COMPARATIVO DE COTIZACIONES</b>");
		$y=$cliy;
		$x=$clix-12; 	
		$y1=$cliy+7;
		$x2=$clix+294;
		$x1=$x;  $y2=$y; $y3=$y1;$x3=$x2; 
		$pdf->curve($x,$y,$x,$y+15,$x,$y+15,$x+15,$y+15);
		$pdf->curve($x1,$y1,$x1,$y1-15,$x1,$y1-15,$x1+15,$y1-15);
		$pdf->curve($x2+15,$y2+15,$x2+30,$y2+15,$x2+30,$y2+15,$x2+30,$y2);
		$pdf->curve($x3+15,$y3-15,$x3+30,$y3-15,$x3+30,$y3-15,$x3+30,$y3);
		$pdf->line($x,$y,$x1,$y1);//V1
		$pdf->line($x2+30,$y2,$x3+30,$y3);//V1
		$pdf->line($x+15,$y+15,$x2+15,$y2+15);//H1
		$pdf->line($x1+15,$y1-15,$x3+15,$y3-15);//H1	
//		$pdf->addText($clix+120,$cliy-20,10,"<b>Pagina </b>.".$xi." de ".($count+1));

		$x=$clix+415;
		$y=$cliy+15;
	// LOGO
		$logx=95;
		$logy=520;
		$logz=12;	
		$pdf->addText($logx,$logy,$logz,utf8_decode(LOG1)); 
		$pdf->addText($logx,$logy-14,$logz+2,"<b>".utf8_decode(MUNI2)."</b>"); 
		$pdf->addText($logx,$logy-25,$logz-2,utf8_decode("<i>".LOG2."</i>")); 
		$pdf->addJpegFromFile(CURRENT_SITE .'logo.jpg',30,480,50,60);
	
	$tm=55;
	$tx=16;
	$ty=109;
	$tf=9;
	$pdf->addText($tx+6,$ty+355,$tf,"<b>Fecha: </b><i>".$cuadro['fecha']."</i>");
	$pdf->addText($tx+6,$ty+340,$tf,"<b>Cotizacion Nro: </b><i>".$cot['codigo']."</i>");
	$pdf->addText($tx+6,$ty+325,$tf,"<b>Referencia: </b><i>".substr(utf8_decode($cot['referencia']),0,40)."</i>");
	$pdf->addText($tx+6,$ty+310,$tf,"<b>Cotización elaborada el: </b><i>".$cot['fecha']."</i>");
	
	$pdf->addText($tx+256,$ty+358,$tf,"<b>Nombre o Razon Social:</b>");
	$pdf->addText($tx+286,$ty+325,$tf,"<b>Plazo de Entrega:</b>");
	$pdf->addText($tx+322,$ty+309,$tf,"<b>Nro de RUC:</b>");

	//Titulos Cuerpo
	$dy=$ty+275;
	$df=10;
	$pdf->addText(160,$dy+16,$df,"<b>ARTICULOS SOLICITADOS</b>");
	$pdf->addText(542,$dy+103,$df,"<b>POSTORES / PROVEEDORES</b>");
	$pdf->addText(542,$dy+16,$df,"<b>PRECIOS NETOS OFERTADOS</b>");
	
	$pdf->addText(19,$dy,$df,"<b>Items</b>");
	$pdf->addText(65,$dy,$df,"<b>Cant.</b>");
	$pdf->addText(110,$dy,$df,"<b>U.Med</b>");
	$pdf->addText(240,$dy,$df,"<b>Descripcion</b>");
	
	$pdf->addText(695,$dy,$df,"<b>Unit.</b>");
	$pdf->addText(760,$dy,$df,"<b>Total</b>");
	$pdf->addText(420,$dy,$df,"<b>Unit.</b>");
	$pdf->addText(485,$dy,$df,"<b>Total</b>");
	$pdf->addText(558,$dy,$df,"<b>Unit.</b>");
	$pdf->addText(623,$dy,$df,"<b>Total</b>");

	//Lineas Generales
	$tx=16;
	$ty=30;
		$y=396;
		$x=16; 	
		$y1=382;
		$x2=801;
		$x1=$x;  $y2=$y; $y3=$y1;$x3=$x2; 
		$pdf->curve($x,$y,$x,$y+15,$x,$y+15,$x+15,$y+15);
		$pdf->line($x,$y,$x1,$y1);//V1
		$pdf->line($x+15,$y+15,$x2+15,$y2+15);//H1
		$y=484;
		$x=406; 	
		$y1=348;
		$x2=787;
		$x1=$x;  $y2=$y; $y3=$y1;$x3=$x2; 
		$pdf->curve($x,$y,$x,$y+15,$x,$y+15,$x+15,$y+15);
		$pdf->curve($x2+15,$y2+15,$x2+30,$y2+15,$x2+30,$y2+15,$x2+30,$y2);
		$pdf->line($x,$y,$x1,$y1);//V1
		$pdf->line($x2+30,$y2,$x3+30,$y3);//V1
		$pdf->line($x+15,$y+15,$x2+15,$y2+15);//H1

	$lix=$ty;
	$lix2=$lix+366;
	$pdf->line($tx+35,$lix2,$tx+801,$lix2);//H1
	$pdf->line($tx+390,$lix+398,$tx+801,$lix+398);//H1 prove	
	$pdf->line($tx+390,$lix+414,$tx+801,$lix+414);//H1 prove	
	$pdf->line($tx+390,$lix+452,$tx+801,$lix+452);//H1 prove	
	
	$pdf->line($tx+35,$lix+12,$tx+35,$lix2+15);//V1
	$pdf->line($tx+89,$lix+12,$tx+89,$lix2);//V1
	$pdf->line($tx+129,$lix+12,$tx+129,$lix2);//V1
	
	$pdf->line($tx+664,$lix+12,$tx+664,$lix2);//V1 55 proveedor 3
	$pdf->line($tx+664,$lix2+16,$tx+664,$lix2+85);//V1 55 proveedor 3
	$pdf->line($tx+719,$lix+12,$tx+719,$lix2);//V1 82
	
	$pdf->line($tx+582,$lix+12,$tx+582,$lix2);//V1 55 proveedor 2
	$pdf->line($tx+527,$lix2+16,$tx+527,$lix2+85);//V1 55 proveedor 3
	$pdf->line($tx+527,$lix+12,$tx+527,$lix2);//V1 82
		
	$pdf->line($tx+445,$lix+12,$tx+445,$lix2);//V1 55 proveedor 1
	$pdf->line($tx+390,$lix+12,$tx+390,$lix2);//V1 82
	
	//line cuerpo
	$pdf->rectangle(16,42,801,340);//borde
	
	//Valores del Proveedores .$cot['prov1']['nombre']
	$dpx=410;
	$dpy=417;
//	$dpf=418;
	$pdf->addText($dpx,$dpy,$df,$cot['prov1']['ruc']);
	$pdf->addText($dpx+138,$dpy,$df,$cot['prov2']['ruc']);
	$pdf->addText($dpx+276,$dpy,$df,$cot['prov3']['ruc']);
	$dpy=432;
	$pdf->addText($dpx,$dpy,$df,$cot['prov1']['plazo']);
	$pdf->addText($dpx+138,$dpy,$df,$cot['prov2']['plazo']);
	$pdf->addText($dpx+276,$dpy,$df,$cot['prov3']['plazo']);
	//Datos del Proveedor 	
	$p1=nombre_spa(utf8_decode($cot['prov1']['razonsocial']));
	$p2=nombre_spa(utf8_decode($cot['prov2']['razonsocial']));
	$p3=nombre_spa(utf8_decode($cot['prov3']['razonsocial']));
	// postor 1
	$pdf->addText($dpx,$dpy+39,$df,$p1[0]);
	$pdf->addText($dpx,$dpy+27,$df,$p1[1]);
	$pdf->addText($dpx,$dpy+15,$df,$p1[2]);
	// postor 2
	$pdf->addText($dpx+138,$dpy+39,$df,$p2[0]);
	$pdf->addText($dpx+138,$dpy+27,$df,$p2[1]);
	$pdf->addText($dpx+138,$dpy+15,$df,$p2[2]);
	// postor 3
	$pdf->addText($dpx+276,$dpy+39,$df,$p3[0]);
	$pdf->addText($dpx+276,$dpy+27,$df,$p3[1]);
	$pdf->addText($dpx+276,$dpy+15,$df,$p3[2]);
		$pdf->ezSetDy(-212);
		$pdf->ezTable($newdata,$tdet,'',$opdet);	


	if ($inde>$rowsdif-1){
		$bandera=false;
		}
	else{		
		$pdf->ezNewPage();
		$xi=$xi+1;
	}
	$pdf->selectFont(FONTS_URL.'Helvetica.afm');
}
	
	$pdf->ezNewPage();
	$pdf->ezSetMargins(0,10,60,60);
	$pdf->rectangle(20,20,790,555);//borde
	$clix=256;
	$cliy=548;
	$cliz=8;	
	$pdf->addText($clix,$cliy,14,"<b>ACTA DE OTORGAMIENTO DE LA BUENA PRO</b>");
		$y=$cliy;
		$x=$clix-12; 	
		$y1=$cliy+7;
		$x2=$clix+294;
		$x1=$x;  $y2=$y; $y3=$y1;$x3=$x2; 
		$pdf->curve($x,$y,$x,$y+15,$x,$y+15,$x+15,$y+15);
		$pdf->curve($x1,$y1,$x1,$y1-15,$x1,$y1-15,$x1+15,$y1-15);
		$pdf->curve($x2+15,$y2+15,$x2+30,$y2+15,$x2+30,$y2+15,$x2+30,$y2);
		$pdf->curve($x3+15,$y3-15,$x3+30,$y3-15,$x3+30,$y3-15,$x3+30,$y3);
		$pdf->line($x,$y,$x1,$y1);//V1
		$pdf->line($x2+30,$y2,$x3+30,$y3);//V1
		$pdf->line($x+15,$y+15,$x2+15,$y2+15);//H1
		$pdf->line($x1+15,$y1-15,$x3+15,$y3-15);//H1	

	$pdf->ezSetDy(-70);
	$fecha=$cuadro['fecha'];
	$entidad=TITLE;
	$firmara=$cuadro['cep'];
	$cotizacion=$cot['codigo'];	
	$ref=$cot['referencia'];
	$prov1[1]=$cot['prov1']['razonsocial'];
	$prov1[2]=$cot['prov1']['ruc'];
	$prov1[3]=$cot['prov1']['direccion'];
	$prov1[4]=$cot['prov2']['razonsocial'];
	$prov1[5]=$cot['prov2']['ruc'];
	$prov1[6]=$cot['prov2']['direccion'];
	$prov1[7]=$cot['prov3']['razonsocial'];
	$prov1[8]=$cot['prov3']['ruc'];
	$prov1[9]=$cot['prov3']['direccion'];
	$plazo1[1]=$cot['prov1']['plazo'];
	$plazo1[2]=$cot['prov2']['plazo'];
	$plazo1[3]=$cot['prov3']['plazo'];
	$adjudicado='prov'.$cuadro['idproveedor'];
	$prov1[10]=$cot[$adjudicado]['razonsocial'];
	$prov1[11]=$cot[$adjudicado]['ruc'];
	$prov1[12]=$cot[$adjudicado]['direccion'];	
	$plazo=$cot[$adjudicado]['plazo'];	
	$Parcial[1]= "S/. ".number_format($total1, 2, ".", ",")." (".convertir($total1, true).")";
	$Parcial[2]= "S/. ".number_format($total2, 2, ".", ",")." (".convertir($total2, true).")";
	$Parcial[3]= "S/. ".number_format($total3, 2, ".", ",")." (".convertir($total3, true).")";
	$TOTAL=$Parcial[$cuadro['idproveedor']];	
	$jutificacion=$cuadro['justificacion'];
	$observaciones=$cuadro['Observacion'];

	/*	

*/
	
$cadenasa = <<<EOD
En la $entidad, siendo el dia $fecha	se reunieron $firmara para la apertura de sobres, evaluacion de propuestas y otorgamiento de la buena Pro, del estudio de Mercado segun la cotizacion nro. $cotizacion. teniendo como referencia el Requerimiento $ref . 

Acto seguido se procede a la apertura de propuestas de las empresas a quienes se les hiso la invitacion Correspondiente, y quienes remitieron en sobres cerrados:

1.- $prov1[1] con Numero de Ruc $prov1[2]
2.- $prov1[4] con Numero de Ruc $prov1[5]
3.- $prov1[7] con Numero de Ruc $prov1[8]
 
Seguidamente se realiza la verificacion de la documentacion presentada, obteniendo el cuadro comparativo Adjunto a la presente acta.

Teniendo las propuestas como sigue:

1.- $prov1[1] con una propuesta Total de $Parcial[1] y un plazo de $plazo1[1]
2.- $prov1[4] con una propuesta Total de $Parcial[2] y un plazo de $plazo1[2]
3.- $prov1[7] con una propuesta Total de $Parcial[3] y un plazo de $plazo1[3]

De lo expuesto. Se resuelve Otorgar la Buena Pro al Postor $prov1[10],  con RUC : $prov1[11] domiciliado en $prov1[12], cuyo monto total propuesto asciende a la cantidad de $TOTAL. Para la contratacion de los bienes/servicios detallados en el cuadro comparativo antes mencionado, monto que incluye IGV  y demas gastos que puedan incidir sobre el costo del bien  a contratar, y un plazo de ejecucion de $plazo contados a partir de la generacion de La Orden de Compra y/o Servicio.	

Teniendo como Justificacion:

$jutificacion

Y teniendo las siguientes observaciones:

$observaciones

Se da por terminada la reunion,  firmando en señal de conformidad los presentes.
EOD;
	
$pdf->ezText(utf8_decode(enie($cadenasa)));
	$clix=140;
	$cliy=180;
	$cliz=9;
	$pdf->addText($clix+20,$cliy-120,$cliz,"Firma de Abastecimiento");
	$pdf->line($clix+10,$cliy-110,$clix+128,$cliy-110);
	$pdf->addText($clix+245,$cliy-120,$cliz,"Firma de Cotizador");
	$pdf->line($clix+240,$cliy-110,$clix+345,$cliy-110);
	
$pdf->ezStream();
}else{
	$smarty->display ('error.html');
}
?>
	