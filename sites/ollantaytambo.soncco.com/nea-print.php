<?php

$idnea = ! empty($_REQUEST['idnea']) ? (int)$_REQUEST['idnea'] : 0;

if($idnea){

$nea = fill_nea(get_nea($idnea));
$nea['fecha'] = fechita($nea['fecha']);
$rowsdif = count($nea['detalle']);

$pdf = new Cezpdf("A4");
$pdf->ezSetMargins(0,10,15,15);
$pdf->selectFont(FONTS_URL.'Helvetica.afm');
$pdf->ezSetDy(30);
//print_r(get_user($nea["createdby"]));
//algunos VAlores
$fecha=strtotime($nea['fecha']);
$day=strftime('%d',$fecha);
$mes=strftime('%m',$fecha);
$anio=strftime('%Y',$fecha);
$procedencia = "<b> ".($nea['procedencia'])."</b>";
$nnea="<b> ".utf8_decode($nea['codigo'])."</b>";
//$descripcion=utf8_decode($nea['proyecto']['descripcion']);
$proyecto=get_proj($nea['destino']);
$destino=utf8_decode("Sec_fun:".$proyecto['sec_func']." ".$proyecto['descripcion']);
$destino=substr($destino,0,100);
if($nea['idorden']==0){
    $segun=" ";
}else{
    $orden=  get_orden_compra($nea['idorden']);
    $segun = "<strong> Orden de Compra  Nro. : ".$orden['codigo']."</strong>";
}
//lineas
$pdf->setStrokeColor(0,0,0);
$pdf->setLineStyle(0.005);
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
foreach($nea['detalle'] as $k => $v) {
$totalidad += $nea['detalle'][$k]['total'];
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
if($nea['detalle'][$inde]['cantidad']==0){
$clasf="";
$cant="";
$und="";
$detalle=utf8_decode($nea['detalle'][$inde]['descripcion']);
$tm=strlen($nea['detalle'][$inde]['descripcion']);
if($tm>=60){
$yi=$yi+(int)($tm/60);
}
$punit="";
$ptotal="";
}else{
$clasf=$nea['detalle'][$inde]['especifica'];
$cant=$nea['detalle'][$inde]['cantidad'];
$und=$nea['detalle'][$inde]['umedida'];
$detalle=utf8_decode($nea['detalle'][$inde]['descripcion']);
$tm=strlen($nea['detalle'][$inde]['descripcion']);
if($tm>=60){
$yi=$yi+(int)($tm/60);
}
$detalle=utf8_decode($nea['detalle'][$inde]['descripcion']);
$punit=$nea['detalle'][$inde]['precio'];
$ptotal=$nea['detalle'][$inde]['total'];
$punit=number_format($punit, 2, ".", ",");
$ptotal=number_format($ptotal, 2, ".", ",");
}
}
$inde=$inde+1;
$datadet[$xi][] = array('clasf'=>"<b>".$clasf."</b>", 'cant'=>"<b>".$cant."</b>",'und'=>"<b>".$und."</b>",'detalle'=>utf8_decode("<b>".$detalle."</b>"),'punit'=>"<b>".$punit."</b>",'ptotal'=>"<b>".$ptotal."</b>");
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
$pdf->addText($logx,$logy-25,$logz-2,utf8_decode("<b>".LOG2."</b>"));
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
$pdf->line(13,218,582,218);//H1
//$pdf->line(13,273,582,273);//H1

$pdf->line(99,655,99,218);//V1
$pdf->line(153,655,153,218);//V1
$pdf->line(193,655,193,218);//V1

$pdf->line(445,675,445,200);//V1
$pdf->line(500,655,500,200);//V1
//Autorizacion SIAF


$pdf->addText(474,747,10,"<b>Pag. </b><b>".$xi."</b>");
$pdf->addText(473,800,13,"<b>Nro </b>".$nnea);
$pdf->addText(186,750,15,"<b>NOTA DE ENTRADA AL ALMACEN</b>");
//Fecha de la nea
$x=489;$y=800;
$x=$x-15;
$pdf->rectangle($x,$y-35,95,30);
$pdf->line($x+26,$y-35,$x+26,$y-5);
$pdf->line($x+53,$y-35,$x+53,$y-5);
$pdf->line($x,$y-17,$x+95,$y-17);
$pdf->addText($x+5,$y-15,10,utf8_decode("Día"));
$pdf->addText($x+30,$y-15,10,"Mes");
$pdf->addText($x+63,$y-15,10,utf8_decode("Año"));
$pdf->addText($x+5,$y-30,10,"<b>".$day."</b>");
$pdf->addText($x+30,$y-30,10,"<b>".$mes."</b>");
$pdf->addText($x+63,$y-30,10,"<b>".$anio."</b>");
//Datos del cliente
$clix=17;
$cliy=729;
$cliz=8;
$pdf->addText($clix,$cliy,$cliz,utf8_decode("<b>PROCEDENCIA: </b><b>".$procedencia."</b>"));
$pdf->addText($clix,$cliy-14,$cliz,"<b>CON DESTINO A : </b><b>".$destino."</b>");
$pdf->addText($clix,$cliy-28,$cliz,"<b>SEGUN: </b><b>".$segun."</b>");
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
//Observaciones
$pdf->line(13,200,582,200);//H1
$pdf->line(13,187,582,187);//H1
$pdf->line(13,120,582,120);//H1

//Titulos Autorizacion
$pdf->addText(25,190,$zin,"<b>OBSERVACIONES :</b>");
//TOTAL TEXT
$tox=454;
$toy=206;
$toz=10;
$pdf->addText($tox,$toy,$toz,"<b>TOTAL</b>");
$pdf->addText(18,$toy,$toz,"<b>SON : </b><b>".$son."</b>");
$pdf->addText($tox+50,$toy,$toz,"s/. ".number_format($total, 2, ".", ","));
//Presupuesto
$prex=18;
$prey=204;
$prez=9;
// SIAF expediente
$pez=9;
//aFECTACION pRESUPUESTAL
$pdf->addText(245,110,9,"<b>RESPONSABLE DE ENTREGA</b>");

$pdf->line(210,120,210,13);//V1
$px=214;
$py=100;
$pz=8;
$pdf->addText($px+80,$py-54,$pz,"<b>FIRMA</b>");
$pdf->addText($px,$py-63,$pz,"<b>Nombre : </b>");
$pdf->addText($px,$py-83,$pz,"<b>DNI :</b>");
$px=240;
$py=$py-22;
$pdf->addText($px+12,$py-23,$pz,".................................................");
$pdf->addText($px+10,$py-45,$pz,"......................................................................");
$pdf->addText($px,$py-54,$pz,"...........................................................................");
$pdf->addText($px,$py-62,$pz,"...........................................................................");

$pdf->addText(58,110,9,"<b>VoBo ABASTECIMIENTO</b>");
$pdf->addText(50,33,6,"....................................................................");
$pdf->addText(58,25,7,"JEFE DE ABASTECIMIENTOS");

//RECIBI CONFORME
$pdf->addText(458,110,9,"<b>RECIBI CONFORME</b>");
$pdf->addText(428,32,8,"<b>DIA</b>");
$pdf->addText(480,32,8,"<b>MES</b>");
$pdf->addText(540,32,8,utf8_decode("<b>AÑO</b>"));
$pdf->line(13,108,582,108);//H1
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
