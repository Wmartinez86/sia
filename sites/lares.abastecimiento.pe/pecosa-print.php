<?php

require_once('home.php');
require_once('redirect.php');

if (isset($_REQUEST['idpecosa'])) {
    $idpecosa = $_REQUEST['idpecosa'];
} else {
    $idpecosa = '0';
}

if($idpecosa){
	$pecosa = fill_pecosa(get_pecosa($idpecosa));
	$rowsdif = count($pecosa['detalle']);
	$rows = 20-(int)$rowsdif;
	if($rows<=0) $rows = 0;
$pdf = new Cezpdf("A4",'landscape');
$pdf->ezSetMargins(0,10,15,15);
$pdf->selectFont(FONTS_URL.'Helvetica.afm');

// V a l o r e s
$pecosa['fecha'] = fechita($pecosa['fecha']);
$fecha=strtotime($pecosa['fecha']);
$day=strftime('%d',$fecha);;
$mes=strftime('%m',$fecha);;
$anio=strftime('%Y',$fecha);;

$norden="<b> ".$pecosa['codigo']."</b>";
//d($pecosa);
$dependencia =utf8_decode(" ".$pecosa['dependencia']);
$entregar=utf8_decode(" ".$pecosa['entregar']);
$destino=utf8_decode(" ".$pecosa['destino']);
$entregar=substr($entregar,0,79);
$destino=substr($destino,0,80);

	$tdet = array('items'=>'', 'cant'=>'','und'=>'','detalle'=>'','spacio'=>'','clasf'=>'','cant2'=>'','punit'=>'','ptotal'=>'','oc'=>'');
	
	$opdet = array('showHeadings'=>0,'shaded'=>0,'showLines'=>0,'xPos'=>21,'xOrientation' =>'right','width'=>603,'fontSize'=>9,'cols' => array(
	'items'=>array('justification'=>'center','width'=>35),
	'cant'=>array('justification'=>'center','width'=>54),
	'und'=>array('justification'=>'center','width'=>40),
	'detalle'=>array('justification'=>'left','width'=>354),
	'spacio'=>array('justification'=>'left','width'=>7),
	'clasf'=>array('justification'=>'center','width'=>86),
	'cant2'=>array('justification'=>'center','width'=>54),
	'punit'=>array('justification'=>'right','width'=>55),
	'ptotal'=>array('justification'=>'right','width'=>82),
	'oc'=>array('justification'=>'center','width'=>34)),
	'innerLineThickness'=>0.5,
	'outerLineThickness'=>0.5,
	);
	$total=0;
	
	
	//d($pecosa);
$j=-1;
$xi=1;
$inde=0;
$count=1;
$items=0;
$isnea=false;
$bandera=true;
while ($bandera){
$yi=0;
unset($datadet);
$datadet = array();
while ($rowsdif>$inde and ($yi < 23))
{
	if($rowsdif>=$inde){
		$yi=$yi+1;		
		$clasf=$pecosa['detalle'][$inde]['producto']['especifica'];
		$cant=$pecosa['detalle'][$inde]['cantidad'];
		$und=$pecosa['detalle'][$inde]['producto']['umedida'];
		$detalle=$pecosa['detalle'][$inde]['producto']['descripcion'];
		$tm=strlen($detalle);
			if($tm>=90){
				$yi=$yi+(int)($tm/90);
			}
		$punit=$pecosa['detalle'][$inde]['producto']['precio'];
		$ptotal=$punit*$cant;
		$punit=number_format($punit, 2, ".", ",");
		$total=$total+$ptotal;
		$ptotal=number_format($ptotal, 2, ".", ",");
		if($pecosa['detalle'][$inde]['producto']['orden']['codigo']==null){$isnea=true;$oc="0".$pecosa['codigo'];
		}else{$oc="0".$pecosa['detalle'][$inde]['producto']['orden']['codigo'];	}		
		$items=$items+1;
	}
	$inde=$inde+1;
	$datadet[] = array('items'=>$items,'cant'=>$cant,'und'=>$und,'detalle'=>($detalle),
	'spacio'=>'','clasf'=>$clasf,'cant2'=>$cant,'punit'=>$punit,'ptotal'=>$ptotal,'oc'=>"".$oc);
}

$pdf->setStrokeColor(0,0,0);
$pdf->setLineStyle(0.5);
$pdf->rectangle(10,10,817,575);//borde

//Titulo Numeracion
		$pdf->addText(306,568,14,"<b>PEDIDO DE COMPROBANTE DE SALIDA</b>");
		//*********LOGO**********
		$logx=25;
		$logy=570;
		$logz=10;
		$pdf->addText($logx,$logy,$logz,utf8_decode(LOG1));
		$pdf->addText($logx,$logy-14,$logz+2,"<b>".utf8_decode(MUNI2)."</b>");
		//cuadros
		$tm=35;
		$tx=16;
		$ty=109;
		$pdf->rectangle($tx,$ty+388,483,$tm);//Dependencia
		$pdf->rectangle($tx+490,$ty+388,183,$tm);//Fecha
		$pdf->rectangle($tx+681,$ty+388,120,$tm);//Nro O/C
		$tf=10;
		$pdf->addText($tx+6,$ty+428,$tf,"<b>Dependencia del Solicitante:</b> ".$dependencia);
		$pdf->addText($tx+6,$ty+410,$tf,"<b>Solicito Entregar a :</b> ".$entregar);
		$pdf->addText($tx+6,$ty+397,$tf,"<b>con destino a :</b>".$destino);
		$pdf->addText($tx+555,$ty+391,$tf,"<b>lugar y fecha</b>");
		$pdf->line($tx+505,$ty+400,$tx+655,$ty+400);
		$pdf->addText($tx+505,$ty+407,$tf,"<b>".utf8_decode(MUNI).", </b>".$day."/".$mes."/".$anio);
		$pdf->addText($tx+704,$ty+400,$tf+4,"<b>Nro:</b>".$norden);
		$pdf->rectangle($tx,$ty,483,382);//Articulos solicitados
		$pdf->rectangle($tx+490,$ty,311,382);//orden de Despacho
		
		//DETALLE
		$lix=$ty;
		$lix2=$ty+366;
		$pdf->line($tx+35,$lix2,$tx+483,$lix2);//H1
		$pdf->line($tx,$lix2-15,$tx+483,$lix2-15);//H1
		$pdf->line($tx+490,$lix2,$tx+801,$lix2);//H1
		$pdf->line($tx+490,$lix2-15,$tx+800,$lix2-15);//H1
		
		$pdf->line($tx+35,$lix,$tx+35,$lix2+15);//V1
		$pdf->line($tx+89,$lix,$tx+89,$lix2);//V1
		$pdf->line($tx+129,$lix,$tx+129,$lix2);//V1
		$pdf->line($tx+576,$lix,$tx+576,$lix2);//V1
		$pdf->line($tx+630,$lix,$tx+630,$lix2);//V1
		$pdf->line($tx+685,$lix,$tx+685,$lix2);//V1
		$pdf->line($tx+767,$lix,$tx+767,$lix2);//V1
		
		//Titulos Cuerpo
		$dy=$ty+353;
		$df=10;
		$pdf->addText(130,$dy+16,$df,"<b>ARTICULOS SOLICITADOS</b>");
		$pdf->addText(620,$dy+16,$df,"<b>ORDEN DE DESPACHO</b>");
		
		$pdf->addText(20,$dy,$df,"<b>Items</b>");
		$pdf->addText(65,$dy,$df,"<b>Cant.</b>");
		$pdf->addText(110,$dy,$df,"<b>U.Med</b>");
		$pdf->addText(290,$dy,$df,"<b>Descripcion</b>");
		$pdf->addText(530,$dy,$df,"<b>Codigo</b>");
		$pdf->addText(610,$dy,$df,"<b>Cant</b>");
		$pdf->addText(655,$dy,$df,"<b>Unitario</b>");
		$pdf->addText(735,$dy,$df,"<b>Total</b>");
		//orden de Comrpa
		if($isnea){
			$pdf->addText(792,$dy,$df,"<b>NEA</b>");
		}
		else{
			$pdf->addText(792,$dy,$df,"<b>O/C</b>");
		}
		
		//Cuentas Mayor
		$pdf->addText(30,90,10,"Cuenta del Mayor");
		$pdf->rectangle(25,64,470,40);
		
		$pdf->rectangle(35,67,70,15);
		$pdf->line(35,67,160,67);
		
		$pdf->rectangle(200,87,70,15);
		$pdf->rectangle(200,67,70,15);
		$pdf->line(220,67,325,67);
		$pdf->line(220,87,325,87);
		
		$pdf->rectangle(365,87,70,15);
		$pdf->rectangle(365,67,70,15);
		$pdf->line(405,67,480,67);
		$pdf->line(405,87,480,87);
		//numero de reglones
		$pdf->addText(514,93,10,"Formulario Utilizado Hasta el Reglon");//
		$son=convertir($rowsdif, false);
		$inclusives="<b>Nro(".$rowsdif.") </b>".$son." Inclusive ";
		$t_inc=strlen($inclusives);
		$pdf->addText(540-$t_inc,73,12,$inclusives);
		$pdf->rectangle(510,64,170,40);
		
		$pdf->line(686,78,810,78);
		$pdf->addText(704,68,10,"Fecha de Recepcion");
		
		//FIRMAS  RECIBI CONFORME Y OTROS
		
		$fy=17;
		$pdf->addText(75,$fy,8,"SOLICITANTE");
		$pdf->line(25,$fy+10,180,$fy+10);//FIRMA ABAS
		
		$pdf->addText(255,$fy,8,"JEFE DE ABASTECIMIENTO");
		$pdf->line(226,$fy+10,410,$fy+10);//FIRMA ABAS
		
		$pdf->addText(490,$fy,8,"JEFE DE ALMACEN");
		$pdf->line(455,$fy+10,610,$fy+10);//FIRMA ABAS
		
		$pdf->addText(660,$fy,8,"RECIBI CONFORME");
		$pdf->line(630,$fy+10,790,$fy+10);//FIRMA ABAS
		//salto de Lipecosa
		$pdf->ezSetDy(-103);
		if ($inde>$rowsdif-1){
			$datadet[] = array('items'=>"",'cant'=>"",'und'=>"",'detalle'=>"",
			'spacio'=>'','clasf'=>"",'cant2'=>"",'punit'=>"<b>TOTAL :</b>",'ptotal'=>number_format($total, 2, ".", ","),'oc'=>"");
		}
		$pdf->ezTable($datadet,$tdet,'',$opdet);

if ($inde>$rowsdif-1){
$bandera=false;
}
else{
$pdf->ezNewPage();
$xi=$xi+1;
}
	$pdf->ezSetDy(-30);

}
$pdf->ezStream();
}else{
	$smarty->display ('error.html');
}
?>
