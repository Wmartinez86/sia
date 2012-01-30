<?php

require_once('home.php');
require_once('redirect.php');

if (isset($_REQUEST['idorden'])) {
    $idorden = $_REQUEST['idorden'];
} else {
    $idorden = '0';
}

if($idorden){
	$orden = fill_compra(get_orden_compra($idorden));
	$rowsdif = count($orden['detalle']);
	$rows = 20-(int)$rowsdif;
	if($rows<=0) $rows = 0;
$pdf = new Cezpdf("A4",'landscape');
$pdf->ezSetMargins(0,10,15,15);
$pdf->selectFont(FONTS_URL.'Helvetica.afm');

//algunos Valores
$orden['fecha'] = fechita($orden['fecha']);
$fecha=strtotime($orden['fecha']);
$day=strftime('%d',$fecha);;
$mes=strftime('%m',$fecha);;
$anio=strftime('%Y',$fecha);;

$norden="<b> ".$orden['codigo']."</b>";
$cliente=$orden['proveedor']['nombre'];
$ruc1=$orden['proveedor']['ruc'];
$dir=$orden['proveedor']['direccion'];
$ref=$orden['doc']['nombre']."-".$orden['nrodoc'];
$fact=$orden['facturarto'];
$fatruc=$orden['fruc'];

$fte=$orden['fuente']['nombre'];
$rubro=$orden['rubro'];
$secfunc=$orden['proyecto']['descripcion'];
$fun=$orden['proyecto']['funcion'];
$prog=$orden['proyecto']['programa'];
$sprog=$orden['proyecto']['sub_programa'];
$act=$orden['proyecto']['act_proy'];
$comp=$orden['proyecto']['componente'];
$cadfun=$fun." ".$prog." ".$sprog." ".$act." ".$comp;
$destino=$orden['pecosa']['destino'];

//lineas
$pdf->setStrokeColor(0,0,0);
$pdf->setLineStyle(0.5);
$pdf->rectangle(10,10,817,575);//borde
//cuadros
$tm=35;
$tx=16;
$ty=109;
$pdf->rectangle($tx,$ty+388,483,$tm);//Dependencia
$pdf->rectangle($tx+490,$ty+388,183,$tm);//Fecha
$pdf->rectangle($tx+681,$ty+388,120,$tm);//Nro O/C
$tf=10;
$pdf->addText($tx+6,$ty+428,$tf,"<b>Dependencia del Solicitante:</b>".$orden['pecosa']['dependencia']);
$pdf->addText($tx+6,$ty+410,$tf,"<b>Solicito Entregar a :</b>".$orden['pecosa']['entregarto']);
$pdf->addText($tx+6,$ty+397,$tf,"<b>con destino a :</b>".$destino);
$pdf->addText($tx+555,$ty+391,$tf,"<b>lugar y fecha</b>");
$pdf->line($tx+505,$ty+400,$tx+655,$ty+400);
$pdf->addText($tx+505,$ty+407,$tf,"<b>Quiñota, </b>".$day."/".$mes."/".$anio);
$pdf->addText($tx+684,$ty+400,$tf+2,"<b>O/C Nro:</b>".$norden);
$pdf->rectangle($tx,$ty,483,382);//Articulos solicitados
$pdf->rectangle($tx+490,$ty,311,382);//orden de Despacho

//DETALLE
$lix=$ty;
$lix2=$ty+366;
$pdf->line($tx+35,$lix2,$tx+483,$lix2);//H1
$pdf->line($tx,$lix2-15,$tx+483,$lix2-15);//H1
$pdf->line($tx+490,$lix2,$tx+801,$lix2);//H1
$pdf->line($tx+490,$lix2-15,$tx+767,$lix2-15);//H1

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
$pdf->addText(540,73,12,"<b>N°(".$rowsdif.") </b>".$son." Inclusive ");
$pdf->rectangle(510,64,170,40);

$pdf->line(686,78,810,78);
$pdf->addText(704,68,10,"Fecha de Recepción");

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

//*********LOGO**********
$logx=25;
$logy=570;
$logz=10;
$pdf->addText($logx,$logy,$logz,enie(LOG1));
$pdf->addText($logx,$logy-14,$logz+2,"<b>Quiñota</b>");
//$pdf->addText($logx+13,$logy-25,$logz-2,"Chumbivilcas - Cusco");
//Titulo Numeracion

$pdf->addText(268,767,20,"<b>Nro </b>");
$pdf->addText(306,568,14,"<b>PEDIDO DE COMPROBANTE DE SALIDA</b>");

//salto de Linea
$p=540-$ty;
$re=-($p-328);
$pdf->ezSetDy($re);

$tdet = array('items'=>'', 'cant'=>'','und'=>'','detalle'=>'','spacio'=>'','clasf'=>'','cant2'=>'','punit'=>'','ptotal'=>'');

$opdet = array('showHeadings'=>0,'shaded'=>0,'showLines'=>0,'xPos'=>21,'xOrientation' =>'right','width'=>569,'fontSize'=>9,'cols' => array(
'items'=>array('justification'=>'center','width'=>35),
'cant'=>array('justification'=>'center','width'=>54),
'und'=>array('justification'=>'center','width'=>40),
'detalle'=>array('justification'=>'left','width'=>354),
'spacio'=>array('justification'=>'left','width'=>7),
'clasf'=>array('justification'=>'center','width'=>86),
'cant2'=>array('justification'=>'center','width'=>54),
'punit'=>array('justification'=>'right','width'=>55),
'ptotal'=>array('justification'=>'right','width'=>82)),
'innerLineThickness'=>0.5,
'outerLineThickness'=>0.5,
);
$total=0;

for ($xi=0; $xi<=$rowsdif-1; $xi+=1){
	$items=$xi+1;
	$clasf=$orden['detalle'][$xi]['especifica'];
	$cant=$orden['detalle'][$xi]['cantidad'];
	$und=$orden['detalle'][$xi]['umedida'];
	$detalle=$orden['detalle'][$xi]['descripcion'];
	$punit=$orden['detalle'][$xi]['precio'];
	$ptotal=$orden['detalle'][$xi]['total'];
	$punit=number_format($punit, 2, ".", ",");
	$total=$total+$ptotal;
	$ptotal=number_format($ptotal, 2, ".", ",");
	$datadet[] = array('items'=>$items,'cant'=>$cant,'und'=>$und,'detalle'=>enie($detalle),'spacio'=>'','clasf'=>$clasf,'cant2'=>$cant,'punit'=>$punit,'ptotal'=>$ptotal);
}
	$datadet[] = array('items'=>"",'cant'=>"",'und'=>"",'detalle'=>"",'spacio'=>'','clasf'=>"",'cant2'=>"",'punit'=>"<b>TOTAL :</b>",'ptotal'=>number_format($total, 2, ".", ","));
$son=convertir($total, true);
$total=number_format($total, 2, ".", ",");
$pdf->ezTable($datadet,$tdet,'',$opdet);
$pdf->ezStream();
}else{
	$smarty->display ('error.html');
}
?>
