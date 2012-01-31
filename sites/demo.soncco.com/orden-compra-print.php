<?php

$idorden = ! empty($_REQUEST['idorden']) ? (int)$_REQUEST['idorden'] : 0;

if($idorden){

$orden = fill_compra(get_orden_compra($idorden));
$orden['fecha'] = fechita($orden['fecha']);
$rowsdif = count($orden['detalle']);

$pdf = new Cezpdf("A4");
$pdf->ezSetMargins(0,10,15,15);
$pdf->selectFont(FONTS_URL.'Helvetica.afm');
$pdf->ezSetDy(30);
//print_r(get_user($orden["createdby"]));
//algunos VAlores
$fecha=strtotime($orden['fecha']);
$day=strftime('%d',$fecha);
$mes=strftime('%m',$fecha);
$anio=strftime('%Y',$fecha);

$norden="<b> ".utf8_decode($orden['codigo'])."</b>";
$cliente=utf8_decode($orden['proveedor']['razonsocial'])."</i>";
$ruc1=$orden['proveedor']['ruc'];
$dir=utf8_decode($orden['proveedor']['direccion']);
$telefono=utf8_decode($orden['proveedor']['telefono']);
$ref=substr(utf8_decode($orden['doc']['nombre']."-".$orden['nrodoc']),0,100);
$fact=utf8_decode($orden['facturarto']);
$fatruc=$orden['fruc'];

$sec_func= $orden['proyecto']['sec_func'];
$programa= $orden['proyecto']['programa'];
$prod_pry= $orden['proyecto']['prod_pry'];
$act_ai_obra= $orden['proyecto']['act_ai_obra'];
$funcion= $orden['proyecto']['funcion'];
$division_func= $orden['proyecto']['division_func'];
$grupo_func= $orden['proyecto']['grupo_func'];
$meta= $orden['proyecto']['meta'];
$finalidad= $orden['proyecto']['finalidad'];
$descripcion=utf8_decode($orden['proyecto']['descripcion']);
$fte=utf8_decode($orden['fuente']['nombre']);
$cad_fun=$programa.".".$prod_pry.".".$act_ai_obra.".".$funcion.".".$division_func.".".$grupo_func.".".$meta.".".$finalidad;
$destino=utf8_decode($orden['destino']);

//lineas
$pdf->setStrokeColor(0,0,0);
$pdf->setLineStyle(0.5);


//salto de Linea
$pdf->ezSetDy(-200);
$tdet = array('clasf'=>'', 'cant'=>'','und'=>'','detalle'=>'','punit'=>'','ptotal'=>'');
$opdet = array('showHeadings'=>0,'shaded'=>0,'showLines'=>0,'xPos'=>18,'xOrientation' =>'right','width'=>569,'fontSize'=>8,'cols' => array(
'clasf'=>array('justification'=>'center','width'=>86),
'cant'=>array('justification'=>'center','width'=>54),
'und'=>array('justification'=>'center','width'=>40),
'detalle'=>array('justification'=>'left','width'=>252),
'punit'=>array('justification'=>'right','width'=>55),
'ptotal'=>array('justification'=>'right','width'=>82)),
'innerLineThickness'=>1,
'outerLineThickness'=>1,

);
$totalidad=0;
foreach($orden['detalle'] as $k => $v) {
$totalidad += $orden['detalle'][$k]['total'];
}
$rowsdif=$rowsdif;
if(($rowsdif %26)!=0){
$count=(int)($rowsdif/26)+1;
}else{
$count=$rowsdif/26;
}
$ff=true;
$total=0;
$j=-1;
$xi=1;
$inde=0;
$count=1;
$bandera=true;
while ($bandera){
$yi=0;
unset($datadet);
$datadet = array();
while ($rowsdif>$inde and ($yi < 26))
{
if($rowsdif>=$inde){
$yi=$yi+1;
if($orden['detalle'][$inde]['cantidad']==0){
$clasf="";
$cant="";
$und="";
$detalle=utf8_decode($orden['detalle'][$inde]['descripcion']);
$tm=strlen($orden['detalle'][$inde]['descripcion']);
if($tm>=60){
$yi=$yi+(int)($tm/60);
}
$punit="";
$ptotal="";
}else{
$clasf=$orden['detalle'][$inde]['especifica'];
$cant=$orden['detalle'][$inde]['cantidad'];
$und=$orden['detalle'][$inde]['umedida'];
$detalle=utf8_decode($orden['detalle'][$inde]['descripcion']);
$tm=strlen($orden['detalle'][$inde]['descripcion']);
if($tm>=60){
$yi=$yi+(int)($tm/60);
}
$detalle=utf8_decode($orden['detalle'][$inde]['descripcion']);
$punit=$orden['detalle'][$inde]['precio'];
$ptotal=$orden['detalle'][$inde]['total'];
$punit=number_format($punit, 2, ".", ",");
$ptotal=number_format($ptotal, 2, ".", ",");
}
}
$inde=$inde+1;
$datadet[$xi][] = array('clasf'=>"<i>".$clasf."</i>", 'cant'=>"<i>".$cant."</i>",'und'=>"<i>".$und."</i>",'detalle'=>utf8_decode("<i>".$detalle."</i>"),'punit'=>"<i>".$punit."</i>",'ptotal'=>"<i>".$ptotal."</i>");
}
$newdata = $datadet[$xi];
$son=convertir($totalidad, true);
$total=$totalidad;

//*********LOGO**********
if(true){
$logx=95;
$logy=800;
$logz=12;
$pdf->addText($logx,$logy,$logz,utf8_decode(LOG1));
$pdf->addText($logx,$logy-14,$logz+2,"<b>".utf8_decode(MUNI2)."</b>");
$pdf->addText($logx,$logy-25,$logz-2,utf8_decode("<i>".LOG2."</i>"));
$pdf->addJpegFromFile(CURRENT_SITE .'logo.jpg',30,755,50,60);
}

//Curvas - Cuerpo
$y=660;$x=13;$pdf->curve($x,$y,$x,$y+15,$x,$y+15,$x+15,$y+15);
$y1=28;$x1=13;$pdf->curve($x1,$y1,$x1,$y1-15,$x1,$y1-15,$x1+15,$y1-15);
$y2=660;$x2=552;$pdf->curve($x2+15,$y2+15,$x2+30,$y2+15,$x2+30,$y2+15,$x2+30,$y2);
$y3=28;$x3=552;$pdf->curve($x3+15,$y3-15,$x3+30,$y3-15,$x3+30,$y3-15,$x3+30,$y3);
$pdf->line($x,$y,$x1,$y1);//V1
$pdf->line($x2+30,$y2,$x3+30,$y3);//V1
$pdf->line($x+15,$y+15,$x2+15,$y2+15);//H1
$pdf->line($x1+15,$y1-15,$x3+15,$y3-15);//H1
//CURVAS-Clientes
$y=727;$x=13;$pdf->curve($x,$y,$x,$y+15,$x,$y+15,$x+15,$y+15);
$y1=695;$x1=13;$pdf->curve($x1,$y1,$x1,$y1-15,$x1,$y1-15,$x1+15,$y1-15);
$y2=727;$x2=552;$pdf->curve($x2+15,$y2+15,$x2+30,$y2+15,$x2+30,$y2+15,$x2+30,$y2);
$y3=695;$x3=552;$pdf->curve($x3+15,$y3-15,$x3+30,$y3-15,$x3+30,$y3-15,$x3+30,$y3);
$pdf->line($x,$y,$x1,$y1);//V1
$pdf->line($x2+30,$y2,$x3+30,$y3);//V1
$pdf->line($x+15,$y+15,$x2+15,$y2+15);//H1
$pdf->line($x1+15,$y1-15,$x3+15,$y3-15);//H1
//DETALLE
$pdf->line(13,655,582,655);//H1
$pdf->line(13,640,582,640);//H1
$pdf->line(13,288,582,288);//H1
$pdf->line(13,273,582,273);//H1

$pdf->line(99,655,99,288);//V1
$pdf->line(153,655,153,288);//V1
$pdf->line(193,655,193,288);//V1

$pdf->line(445,675,445,273);//V1
$pdf->line(500,655,500,273);//V1
//Autorizacion SIAF
$pdf->line(310,273,310,200);//V1
$pdf->line(310,262,582,262);//H1
$pdf->rectangle(420,222,15,15);
$pdf->rectangle(420,204,15,15);
$pdf->rectangle(530,222,15,15);
$pdf->rectangle(530,204,15,15);


$pdf->addText(474,747,10,"<b>Pag. </b><i>".$xi."</i>");
$pdf->addText(473,800,13,"<b>Nro </b>".$norden);
$pdf->addText(106,750,15,"<b>ORDEN DE COMPRA-GUIA DE INTERNAMIENTO</b>");
//Fecha de la Orden
$x=489;$y=800;
$x=$x-15;
$pdf->rectangle($x,$y-35,95,30);
$pdf->line($x+26,$y-35,$x+26,$y-5);
$pdf->line($x+53,$y-35,$x+53,$y-5);
$pdf->line($x,$y-17,$x+95,$y-17);
$pdf->addText($x+5,$y-15,10,"Dia");
$pdf->addText($x+30,$y-15,10,"Mes");
$pdf->addText($x+63,$y-15,10,"A�o");
$pdf->addText($x+5,$y-30,10,"<i>".$day."</i>");
$pdf->addText($x+30,$y-30,10,"<i>".$mes."</i>");
$pdf->addText($x+63,$y-30,10,"<i>".$anio."</i>");
//Datos del cliente
$clix=17;
$cliy=729;
$cliz=8;
$pdf->addText($clix,$cliy,$cliz,"<b>SE�OR(ES): </b><i>".$cliente."</i>");
$pdf->addText($clix+458,$cliy,$cliz,"<b>TELF: </b><i>".$telefono."</i>");
$pdf->addText($clix+458,$cliy-14,$cliz,"<b>RUC: </b><i>".$ruc1."</i>");
$pdf->addText($clix,$cliy-14,$cliz,"<b>DIRECCION: </b><i>".$dir."</i>");
$pdf->addText($clix,$cliy-28,$cliz,"<b>REFERENCIA: </b><i>".$ref."</i>");
$pdf->addText($clix,$cliy-42,$cliz,"<b>FACTURAR A NOMBRE DE: </b><i>".$fact." - Ruc Nro. ".$fatruc."</i>");

//Titulos Cuerpo
$zin="10";
$pdf->addText(230,661,$zin,"<b>ARTICULOS</b>");
$pdf->addText(480,661,$zin,"<b>VALORES</b>");
$pdf->addText(30,644,$zin,"<b>CODIGO</b>");
$pdf->addText(110,644,$zin,"<b>CANT.</b>");
$pdf->addText(155,644,$zin,"<b>U.MED</b>");
$pdf->addText(290,644,$zin,"<b>DESCRIPCION</b>");
$pdf->addText(448,644,$zin,"<b>UNITARIO</b>");
$pdf->addText(520,644,$zin,"<b>TOTAL</b>");
//Autorizacion
$pdf->line(13,200,582,200);//H1
$pdf->line(13,187,582,187);//H1
$pdf->line(13,120,582,120);//H1
$pdf->line(310,200,310,120);//V1
$pdf->line(168,187,168,120);//V1
//Titulos Autorizacion
$pdf->addText(95,190,$zin,"<b>AUTORIZACION DE COMPRA</b>");
$pdf->addText(385,190,$zin,"<b>DISTRIBUCION CONTABLE</b>");
$pdf->addText(45,125,7,"GERENTE MUNICIPAL");
$pdf->addText(30,133,6,"....................................................................");
$pdf->addText(190,125,7,"JEFE DE ABASTECIMIENTOS");
$pdf->addText(180,133,6,"....................................................................");
$pdf->addText(400,175,9,"<b>CUENTAS POR PAGAR</b>");
$pdf->addText(338,140,10,"<b>S/. </b>");
$pdf->addText(378,135,6,".........................................................................................................");
$afex=260;
$afey=110;
$afez=10;
$pdf->addText(418,137,14,"<i>".number_format($total, 2, ".", ",")."</i>"); //CUENTAS POR PAGAR
//NOTA
$notx=20;
$noty=36;
$notz=8;
$notw=9;
$pdf->addText($notx,$noty+$notw*8,$notz,"<b>NOTA :</b> Esta orden es NULA sin la firma del ");
$pdf->addText($notx,$noty+$notw*7,$notz,"Jefe de Abastecimiento");
$pdf->addText($notx,$noty+$notw*5,$notz,"Cada Orden de compra se debe facturar por ");
$pdf->addText($notx,$noty+$notw*4,$notz,"separado en Original y tres (3) copias y ");
$pdf->addText($notx,$noty+$notw*3,$notz,"remitirlas a la Direccion de Presupuestos.");
$pdf->addText($notx,$noty+$notw*2,$notz,"Contabilidad.");
$pdf->addText($notx,$noty,$notz,"Nos reservamos el dereho de devolver la");
$pdf->addText($notx,$noty-$notw,$notz,"mercaderia que no esta de acuerdo con nuestras ");
$pdf->addText($notx,$noty-$notw*2,$notz,"especificaciones");

//TOTAL TEXT
$tox=454;
$toy=276;
$toz=10;
$pdf->addText($tox,$toy,$toz,"<b>TOTAL</b>");
$pdf->addText(18,$toy,$toz,"<b>SON : </b><i>".$son."</i>");
$pdf->addText($tox+50,$toy,$toz,"s/. ".number_format($total, 2, ".", ","));
//Presupuesto
$prex=18;
$prey=204;
$prez=9;
$pdf->addText($prex,$prey+60,$prez,"<b>Sec_Func :</b><i>".$sec_func."</i>");
$pdf->addText($prex,$prey+48,$prez,"<b>Fte_Fto :</b><i>".$fte."</i>");
$pdf->addText($prex,$prey+36,$prez,"<b>Cad_Func :</b><i>".$cad_fun."</i>");
$pdf->addText($prex,$prey+24,$prez,"<b>Destino :</b><i>".$destino."</i>");
$pdf->addText($prex,$prey+12,$prez,"<b>Meta :</b><i>".substr($descripcion,0,50)."-</i>");
$pdf->addText($prex,$prey,$prez,"".substr($descripcion,50,65).".</i>");
// SIAF expediente
$pez=9;
$pdf->addText(410,$prey+60,$pez,"<b>EXPEDIENTE SIAF</b>");
$pdf->addText(318,$prey+40,$pez,"<b>Nro. Certificado :</b>");
$pdf->addText(397,$prey+36,8,"..................");
$pdf->addText(318,$prey+21,$pez,"<b>Compromiso Anual</b>");
$pdf->addText(318,$prey+5,$pez,"<b>Devengado</b>");
$pdf->addText(458,$prey+40,$pez,"<b>Nro Siaf :</b>");
$pdf->addText(506,$prey+36,8,"..................");
$pdf->addText(458,$prey+21,$pez,"<b>Compromiso</b>");
$pdf->addText(458,$prey+5,$pez,"<b>Girado</b>");

//aFECTACION pRESUPUESTAL
$pdf->addText(245,110,9,"<b>AFECTACION PRESUPUESTAL</b>");

$pdf->line(210,120,210,13);//V1
$px=214;
$py=100;
$pz=8;
$pdf->addText($px,$py,$pz,"<b>programa</b>");
$pdf->addText($px,$py-9,$pz,"<b>prod_pry</b>");
$pdf->addText($px,$py-18,$pz,"<b>act_ai_obra</b>");
$pdf->addText($px,$py-27,$pz,"<b>funcion</b>");
$pdf->addText($px,$py-36,$pz,"<b>division_func</b>");
$pdf->addText($px,$py-45,$pz,"<b>grupo_func</b>");
$pdf->addText($px,$py-54,$pz,"<b>meta</b>");
$pdf->addText($px,$py-63,$pz,"<b>finalidad</b>");
$pdf->addText($px,$py-72,$pz,"<b>descripcion</b>");
$px=300;
$pdf->addText($px,$py,$pz,"<i>".$programa."</i>");
$pdf->addText($px,$py-9,$pz,"<i>".$prod_pry."</i>");
$pdf->addText($px,$py-18,$pz,"<i>".$act_ai_obra."</i>");
$pdf->addText($px,$py-27,$pz,"<i>".$funcion."</i>");
$pdf->addText($px,$py-36,$pz,"<i>".$division_func."</i>");
$pdf->addText($px,$py-45,$pz,"<i>".$grupo_func."</i>");
$pdf->addText($px,$py-54,$pz,"<i>".$meta."</i>");
$pdf->addText($px,$py-63,$pz,"<i>".$finalidad."</i>");
$pdf->addText($px,$py-72,$pz,"<i>".substr($descripcion,0,26)."</i>");
$pdf->addText($px-86,$py-81,$pz,"<i>".substr($descripcion,26,46)."</i>");
$py=$py-2;
$pdf->addText($px,$py,$pz,".................................");
$pdf->addText($px,$py-9,$pz,".................................");
$pdf->addText($px,$py-18,$pz,".................................");
$pdf->addText($px,$py-27,$pz,".................................");
$pdf->addText($px,$py-36,$pz,".................................");
$pdf->addText($px,$py-45,$pz,".................................");
$pdf->addText($px,$py-54,$pz,".................................");
$pdf->addText($px,$py-63,$pz,".................................");

//RECIBI CONFORME
$pdf->addText(458,110,9,"<b>RECIBI CONFORME</b>");
$pdf->addText(428,32,8,"<b>DIA</b>");
$pdf->addText(480,32,8,"<b>MES</b>");
$pdf->addText(540,32,8,"<b>A�O</b>");
$pdf->line(210,108,582,108);//H1
$pdf->line(410,30,582,30);//H1
$pdf->line(410,41,582,41);//H1
$pdf->line(410,120,410,13);//V1
$pdf->line(460,41,460,13);//V1
$pdf->line(520,41,520,13);//V1
$pdf->addText(445,53,6,"....................................................................");
$pdf->addText(465,45,7,"JEFE DE ALMACEN");


//Tabla
$pdf->ezTable($newdata,$tdet,'',$opdet);
$pdf->setStrokeColor(0,0,0);
$pdf->setLineStyle(0.5);

if ($inde>$rowsdif-1){
$bandera=false;
}
else{
$pdf->ezNewPage();
$xi=$xi+1;
}
$pdf->ezSetDy(-200);

}
$pdf->ezStream();
}else{
$smarty->display ('error.html');
}
?>