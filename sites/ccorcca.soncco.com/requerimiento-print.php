<?php

if (isset($_REQUEST['idreq'])) {
    $idreq = $_REQUEST['idreq'];
} else {
    $idreq = '0';
}

//$idcot = ! isset($_REQUEST['idcot']) ? (int)$idcot : 0;

if($idreq){

$req = fill_req(get_requerimiento($idreq));
$req['fecha'] = fechita($req['fecha']);
$rowsdif = count($req['detalle']);

$pdf = new Cezpdf("A4");
$pdf->ezSetMargins(0,10,22,22);
$pdf->selectFont(FONTS_URL.'Helvetica.afm');
$pdf->ezSetDy(30);
$datacreator = array (
'Title'=>'Requerimiento Nro :'.$req['codigo'],
'Author'=>$req['usuario']['username'],//usuario.username
'Subject'=>'Sistema Generador de Ordenes de Compra y Servicio Para la '.LOG1." ".MUNI,
'Creator'=>'Jonas V. Coaquira Mamani ',
'Producer'=>'jonas.coaquira@gmail.com'
);
$pdf->addInfo($datacreator);
//algunos VAlores
$fecha=strtotime($req['fecha']);
$day=strftime('%d',$fecha);
$mes=strftime('%m',$fecha);
$anio=strftime('%Y',$fecha);
$act_proy=substr(utf8_decode($req['usuario']['proyecto']['descripcion']),0,90);
$area=substr(utf8_decode($req['usuario']['area']['nombre']),0,90);
$nreq=" ".$req['codigo'];
$nreq2=" 000".$req['codigo']." - 2012 - ".$req['usuario']['area']['abreviatura'];
$solicitante=utf8_decode($req['usuario']['nombres']);
$tdet = array('cant'=>'','und'=>'','detalle'=>'');
$opdet = array('showHeadings'=>0,'shaded'=>0,'showLines'=>2,'xPos'=>25,'xOrientation' =>'right','width'=>569,'fontSize'=>9,'cols' => array(
'cant'=>array('justification'=>'center','width'=>59),
'und'=>array('justification'=>'center','width'=>74),
'detalle'=>array('justification'=>'left','width'=>422)),
'innerLineThickness'=>0.005,
'outerLineThickness'=>0.005,
);


$Req=$req['detalle'];
$xi=1;
$inde=0;
$count=1;
$bandera=true;
while ($bandera){
$yi=0;
unset($datadet);
$datadet = array();
while ($rowsdif>$inde and ($yi < 32))
{
if($rowsdif>=$inde){
$yi=$yi+1;
if($Req[$inde]['cantidad']==0){
$cant="";
$und="";
$detalle=utf8_decode("<i>".$Req[$inde]['descripcion']."</i>");
$tm=strlen($Req[$inde]['descripcion']);
if($tm>=100){
$yi=$yi+(int)($tm/100);
}
}else{
$cant=utf8_decode("<i>".$Req[$inde]['cantidad']."</i>");
$und=utf8_decode("<i>".$Req[$inde]['umedida']."</i>");
$detalle=utf8_decode("<i>".$Req[$inde]['descripcion']."</i>");
$tm=strlen($Req[$inde]['descripcion']);
if($tm>=100){
$yi=$yi+(int)($tm/100);
}
}
}
$inde=$inde+1;
$datadet[$yi] = array('cant'=>$cant,'und'=>$und,'detalle'=>$detalle);
}

$newdata = $datadet;
//lineas
$pdf->setStrokeColor(0,0,0);
$pdf->setLineStyle(0.5);

//*********LOGO**********
if(logo){
$logx=95;
$logy=800;
$logz=12;
$pdf->selectFont(FONTS_URL.'Helvetica.afm');
$pdf->addText($logx,$logy,$logz,utf8_decode(LOG1));
$pdf->addText($logx,$logy-14,$logz+2,"<b>".utf8_decode(MUNI2)."</b>");
$pdf->addText($logx,$logy-25,$logz-2,utf8_decode("<i>".LOG2."</i>"));
$pdf->addJpegFromFile(CURRENT_SITE .'logo.jpg',30,755,50,60);

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
$pdf->addText($x-8,$y+6,11,"Nro:".$nreq." - Pg. ".$xi);
//FECHA
$x=$x-15;
$pdf->rectangle($x,$y-35,95,30);
$pdf->line($x+26,$y-35,$x+26,$y-5);
$pdf->line($x+53,$y-35,$x+53,$y-5);
$pdf->line($x,$y-17,$x+95,$y-17);
$pdf->addText($x+5,$y-15,10,utf8_decode("Día"));
$pdf->addText($x+30,$y-15,10,"Mes");
$pdf->addText($x+63,$y-15,10,utf8_decode("Año"));
$pdf->addText($x+5,$y-30,10,"<i>".$day."</i>");
$pdf->addText($x+30,$y-30,10,"<i>".$mes."</i>");
$pdf->addText($x+63,$y-30,10,"<i>".$anio."</i>");

$clix=40;
$cliy=754;
$cliz=8;
$pdf->addText($clix+158,$cliy+4,14,"<b>HOJA DE REQUERIMIENTO</b>");
$y=760;$x=194;$pdf->curve($x,$y,$x,$y+15,$x,$y+15,$x+15,$y+15);
$y1=767;$x1=194;$pdf->curve($x1,$y1,$x1,$y1-15,$x1,$y1-15,$x1+15,$y1-15);
$y2=760;$x2=356;$pdf->curve($x2+15,$y2+15,$x2+30,$y2+15,$x2+30,$y2+15,$x2+30,$y2);
$y3=767;$x3=356;$pdf->curve($x3+15,$y3-15,$x3+30,$y3-15,$x3+30,$y3-15,$x3+30,$y3);
$pdf->line($x,$y,$x1,$y1);//V1
$pdf->line($x2+30,$y2,$x3+30,$y3);//V1
$pdf->line($x+15,$y+15,$x2+15,$y2+15);//H1
$pdf->line($x1+15,$y1-15,$x3+15,$y3-15);//H1
$pdf->addText($clix,$cliy-14,$cliz,"Nro. de Requerimiento : <i><b>".$nreq2."</b></i>");
$pdf->addText($clix,$cliy-28,$cliz,"Nombre del Solicitante : <i>".$solicitante."</i>");
$pdf->addText($clix,$cliy-42,$cliz,"Actividad Proyecto : <i>" .$act_proy."</i>");
$pdf->addText($clix,$cliy-56,$cliz,"Oficina Solicitante : <i>" .$area."</i>");
$pdf->addText($clix,$cliy-70,$cliz,"Sirva(n) se atender los articulos y/o Servicios que se detallan mas abajo: ");

// Firmas
$clix=40;
$cliy=150;
$cliz=8;

$pdf->rectangle($clix+316,$cliy-55,220,65);
$pdf->line($clix+316,$cliy-57,$clix+536,$cliy-57);
$pdf->line($clix+316,$cliy-3,$clix+536,$cliy-3);
$pdf->line($clix+430,$cliy-3,$clix+430,$cliy-55);

$pdf->addText($clix+365,$cliy,$cliz,"<b>AFECTACION PRESUPUESTAL</b>");
$pdf->addText($clix+320,$cliy-17,$cliz,"<b>Sec_Func: .............................</b>");
$pdf->addText($clix+320,$cliy-33,$cliz,"<b>Fte_Fto : ................................</b>");
$pdf->addText($clix+320,$cliy-49,$cliz,"<b>Gasto : ...................................</b>");
$pdf->addText($clix+450,$cliy-50,$cliz,"<b>VoBo Presupuesto</b>");

$pdf->addText($clix+40,$cliy-50,$cliz,"VoBo Gerencia");
$pdf->line($clix+10,$cliy-40,$clix+128,$cliy-40);
$pdf->addText($clix+20,$cliy-120,$cliz,"Firma de Abastecimiento");
$pdf->line($clix+10,$cliy-110,$clix+128,$cliy-110);
$pdf->addText($clix+225,$cliy-120,$cliz,"Jefe Inmediato");
$pdf->line($clix+200,$cliy-110,$clix+305,$cliy-110);
$pdf->addText($clix+410,$cliy-120,$cliz,"Solicitante");
$pdf->line($clix+350,$cliy-110,$clix+493,$cliy-110);


//Curvas - Cuerpo
$y=660;$x=20;$pdf->curve($x,$y,$x,$y+15,$x,$y+15,$x+15,$y+15);
$y1=180;$x1=20;$pdf->curve($x1,$y1,$x1,$y1-15,$x1,$y1-15,$x1+15,$y1-15);
$y2=660;$x2=545;$pdf->curve($x2+15,$y2+15,$x2+30,$y2+15,$x2+30,$y2+15,$x2+30,$y2);
$y3=180;$x3=545;$pdf->curve($x3+15,$y3-15,$x3+30,$y3-15,$x3+30,$y3-15,$x3+30,$y3);
$pdf->line($x,$y,$x1,$y1);//V1
$pdf->line($x2+30,$y2,$x3+30,$y3);//V1
$pdf->line($x+15,$y+15,$x2+15,$y2+15);//H1
$pdf->line($x1+15,$y1-15,$x3+15,$y3-15);//H1

$y=644;
$x=30;
$z=9;
$pdf->addText($x+225,$y+17,$z,"<b>ARTICULOS</b>");
$pdf->addText($x+9,$y,$z,"<b>CANT.</b>");
$pdf->addText($x+70,$y,$z,"<b>U.MED.</b>");
$pdf->addText($x+230,$y,$z,"<b>DESCRIPCION</b>");

$pdf->line(20,655,575,655);//H1
$pdf->line(20,640,575,640);//H1
$pdf->line(79,655,79,165);//V1
$pdf->line(153,655,153,165);//V1


$pdf->ezSetDy(-200);
$pdf->ezTable($newdata,$tdet,'',$opdet);
//$pdf->addText(395,800,15,$inde." ".$rowsdif);
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
