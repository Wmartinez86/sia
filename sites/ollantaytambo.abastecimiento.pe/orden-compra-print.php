<?php

$idorden = ! empty($_REQUEST['idorden']) ? (int)$_REQUEST['idorden'] : 0;

if($idorden){

$orden = fill_compra(get_orden_compra($idorden));
$orden['fecha'] = fechita($orden['fecha']);
$rowsdif = count($orden['detalle']);

$pdf = new Cezpdf("LETTER");
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
$cliente=($orden['proveedor']['razonsocial'])."  ";
$ruc1=$orden['proveedor']['ruc'];
$dir=substr(utf8_decode($orden['proveedor']['direccion']),0,70);
$telefono=utf8_decode($orden['proveedor']['telefono']);
$ref=ucwords(mb_strtolower(substr(utf8_decode($orden['doc']['nombre']."-".$orden['nrodoc']),0,92)));
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
$opdet = array('showHeadings'=>0,'shaded'=>0,'showLines'=>0,'xPos'=>18,'xOrientation' =>'right','width'=>586,'fontSize'=>10,'cols' => array(
'clasf'=>array('justification'=>'center','width'=>70),
'cant'=>array('justification'=>'center','width'=>56),
'und'=>array('justification'=>'center','width'=>42),
'detalle'=>array('justification'=>'left','width'=>280),
'punit'=>array('justification'=>'right','width'=>59),
'ptotal'=>array('justification'=>'right','width'=>80)),
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
while ($rowsdif>$inde and ($yi < 16))
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
$datadet[$xi][] = array('clasf'=>" ".$clasf."  ", 'cant'=>" ".$cant."  ",'und'=>" ".$und."  ",'detalle'=>" ".$detalle."  ",'punit'=>" ".$punit."  ",'ptotal'=>" ".$ptotal."  ");
}
$newdata = $datadet[$xi];
$son=convertir($totalidad, true);
$total=$totalidad;

//*********LOGO**********
if(true){
$logx=100;
$logy=750;
$logz=12;
$pdf->addText($logx,$logy,$logz,utf8_decode(LOG1));
$pdf->addText($logx,$logy-14,$logz+2,"<b>".utf8_decode(MUNI2)."</b>");
$pdf->addText($logx,$logy-25,$logz-2,utf8_decode(" ".LOG2."  "));
//$pdf->addJpegFromFile(CURRENT_SITE .'logo.jpg',30,755,50,60);
}

//Curvas - Cuerpo
$y=609;$x=13;$pdf->curve($x,$y,$x,$y+15,$x,$y+15,$x+15,$y+15);
$y1=28;$x1=13;$pdf->curve($x1,$y1,$x1,$y1-15,$x1,$y1-15,$x1+15,$y1-15);
$y2=609;$x2=569;$pdf->curve($x2+15,$y2+15,$x2+30,$y2+15,$x2+30,$y2+15,$x2+30,$y2);
$y3=28;$x3=569;$pdf->curve($x3+15,$y3-15,$x3+30,$y3-15,$x3+30,$y3-15,$x3+30,$y3);
$pdf->line($x,$y,$x1,$y1);//V1
$pdf->line($x2+30,$y2,$x3+30,$y3);//V1
$pdf->line($x+15,$y+15,$x2+15,$y2+15);//H1
$pdf->line($x1+15,$y1-15,$x3+15,$y3-15);//H1
//CURVAS-Clientes
$y=676;$x=13;$pdf->curve($x,$y,$x,$y+15,$x,$y+15,$x+15,$y+15);
$y1=644;$x1=13;$pdf->curve($x1,$y1,$x1,$y1-15,$x1,$y1-15,$x1+15,$y1-15);
$y2=676;$x2=569;$pdf->curve($x2+15,$y2+15,$x2+30,$y2+15,$x2+30,$y2+15,$x2+30,$y2);
$y3=644;$x3=569;$pdf->curve($x3+15,$y3-15,$x3+30,$y3-15,$x3+30,$y3-15,$x3+30,$y3);
$pdf->line($x,$y,$x1,$y1);//V1
$pdf->line($x2+30,$y2,$x3+30,$y3);//V1
$pdf->line($x+15,$y+15,$x2+15,$y2+15);//H1
$pdf->line($x1+15,$y1-15,$x3+15,$y3-15);//H1
//DETALLE
$pdf->line(13,604,599,604);//H1
$pdf->line(13,589,599,589);//H1
$pdf->line(13,288,599,288);//H1
$pdf->line(13,273,599,273);//H1

$pdf->line(83,604,83,288);//V1
$pdf->line(139,604,139,288);//V1
$pdf->line(179,604,179,288);//V1

$pdf->line(462,624,462,273);//V1
$pdf->line(517,604,517,273);//V1
//Autorizacion SIAF
$pdf->line(310,273,310,200);//V1
$pdf->line(310,262,599,262);//H1
$pdf->rectangle(420,222,15,15);
$pdf->rectangle(420,204,15,15);
$pdf->rectangle(530,222,15,15);
$pdf->rectangle(530,204,15,15);


$pdf->addText(491,696,10,"<b>Pag. </b> ".$xi."  ");
$pdf->addText(490,749,13,"<b>Nro </b>".$norden);
$pdf->addText(123,699,15,"<b>ORDEN DE COMPRA-GUIA DE INTERNAMIENTO</b>");
//Fecha de la Orden
$x=506;$y=749;
$x=$x-15;
$pdf->rectangle($x,$y-35,95,30);
$pdf->line($x+26,$y-35,$x+26,$y-5);
$pdf->line($x+53,$y-35,$x+53,$y-5);
$pdf->line($x,$y-17,$x+95,$y-17);
$pdf->addText($x+5,$y-15,10,utf8_decode("Día"));
$pdf->addText($x+30,$y-15,10,"Mes");
$pdf->addText($x+63,$y-15,10,utf8_decode("Año"));
$pdf->addText($x+5,$y-30,10," ".$day."  ");
$pdf->addText($x+30,$y-30,10," ".$mes."  ");
$pdf->addText($x+63,$y-30,10," ".$anio."  ");
//Datos del cliente
$clix=17;
$cliy=678;
$cliz=11;
$pdf->addText($clix,$cliy,$cliz,utf8_decode("<b>SEÑOR(ES): </b> ".$cliente."  "));
$pdf->addText($clix+458,$cliy,$cliz,"<b>TELF: </b> ".$telefono."  ");
$pdf->addText($clix+458,$cliy-14,$cliz,"<b>RUC: </b> ".$ruc1."  ");
$pdf->addText($clix,$cliy-14,$cliz,"<b>DIRECCION: </b> ".$dir."  ");
$pdf->addText($clix,$cliy-28,$cliz,"<b>REFERENCIA: </b> ".$ref."  ");
$pdf->addText($clix,$cliy-42,$cliz,"<b>FACTURAR A NOMBRE DE: </b> ".$fact." - Ruc Nro. ".$fatruc."  ");

//Titulos Cuerpo
$zin="11";
$pdf->addText(230,611,$zin,"<b>ARTICULOS</b>");
$pdf->addText(497,611,$zin,"<b>VALORES</b>");
$pdf->addText(25,593,$zin,"<b>CODIGO</b>");
$pdf->addText(96,593,$zin,"<b>CANT.</b>");
$pdf->addText(141,593,$zin,"<b>U.MED</b>");
$pdf->addText(276,593,$zin,"<b>DESCRIPCION</b>");
$pdf->addText(463,593,$zin,"<b>UNITARIO</b>");
$pdf->addText(537,593,$zin,"<b>TOTAL</b>");
//Autorizacion
$pdf->line(13,200,599,200);//H1
$pdf->line(13,187,599,187);//H1
$pdf->line(13,120,599,120);//H1
$pdf->line(310,200,310,120);//V1
$pdf->line(168,187,168,120);//V1
//Titulos Autorizacion
$pdf->addText(95,190,$zin,"<b>AUTORIZACION DE COMPRA</b>");
$pdf->addText(385,190,$zin,"<b>DISTRIBUCION CONTABLE</b>");
$pdf->addText(19,124,10,"GERENTE ADMINISTRACION");
$pdf->addText(30,135,6,"....................................................................");
$pdf->addText(172,124,10,"JEFE DE ABASTECIMIENTO");
$pdf->addText(180,135,6,"....................................................................");
$pdf->addText(400,175,10,"<b>CUENTAS POR PAGAR</b>");
$pdf->addText(338,140,11,"<b>S/. </b>");
$pdf->addText(378,135,6,".........................................................................................................");
$afex=260;
$afey=110;
$afez=10;
$pdf->addText(418,137,14," ".number_format($total, 2, ".", ",")."  "); //CUENTAS POR PAGAR
//NOTA
$notx=20;
$noty=36;
$notz=10;
$notw=9.2;
$pdf->addText($notx,$noty+$notw*8,$notz,"<b>NOTA :</b> Esta orden es NULA sin la firma");
$pdf->addText($notx,$noty+$notw*7,$notz,"del Jefe de Abastecimiento");
$pdf->addText($notx,$noty+$notw*5,$notz,"Cada Orden de compra se debe facturar");
$pdf->addText($notx,$noty+$notw*4,$notz,"por separado en Original y tres (3) copias");
$pdf->addText($notx,$noty+$notw*3,$notz,utf8_decode("y remitirlas a la Dirección de Presupuesto"));
$pdf->addText($notx,$noty+$notw*2,$notz,"y Contabilidad.");
$pdf->addText($notx,$noty,$notz,"Nos reservamos el derecho de devolver la");
$pdf->addText($notx,$noty-$notw,$notz,utf8_decode("mercadería que no está de acuerdo con "));
$pdf->addText($notx,$noty-$notw*2,$notz,"nuestras especificaciones");

//TOTAL TEXT
$tox=454;
$toy=276;
$toz=11;
$pdf->addText($tox+17,$toy,$toz,"<b>TOTAL</b>");
$pdf->addText(18,$toy,$toz,"<b>SON : </b> ".$son."  ");
$pdf->addText($tox+67,$toy,$toz,"s/. ".number_format($total, 2, ".", ","));
//Presupuesto
$prex=18;
$prey=203;
$prez=10;
$pdf->addText($prex,$prey+60,$prez,"<b>Sec_Func :</b> ".$sec_func."  ");
$pdf->addText($prex,$prey+48,$prez,"<b>Fte_Fto     :</b> ".$fte."  ");
$pdf->addText($prex,$prey+36,$prez,"<b>Cad_Func :</b> ".$cad_fun."  ");
$pdf->addText($prex,$prey+24,$prez,"<b>Destino     :</b> ".$destino."  ");
$pdf->addText($prex,$prey+12,$prez,"<b>Meta          :</b> ".substr($descripcion,0,47)."-  ");
$pdf->addText($prex,$prey,$prez,"".substr($descripcion,47,58).".  ");
// SIAF expediente
$pez=10;
$pdf->addText(410,$prey+60,$pez,"<b>EXPEDIENTE SIAF</b>");
$pdf->addText(318,$prey+40,$pez,"<b>Nro. Certificado :</b>");
$pdf->addText(400,$prey+36,8,"..................");
$pdf->addText(318,$prey+21,$pez,"<b>Compromiso Anual</b>");
$pdf->addText(318,$prey+5,$pez,"<b>Devengado</b>");
$pdf->addText(458,$prey+40,$pez,"<b>Nro Siaf :</b>");
$pdf->addText(506,$prey+36,8,"..................");
$pdf->addText(458,$prey+21,$pez,"<b>Compromiso</b>");
$pdf->addText(458,$prey+5,$pez,"<b>Girado</b>");

//aFECTACION pRESUPUESTAL
$pdf->addText(225,109.5,11,"<b>AFECTACION PRESUPUESTAL</b>");

$pdf->line(210,120,210,13);//V1
$px=214;
$py=98;
$pz=10;
$pdf->addText($px,$py,$pz,"Programa");
$pdf->addText($px,$py-9,$pz,"Prod_pry");
$pdf->addText($px,$py-18,$pz,"Act_ai_obra");
$pdf->addText($px,$py-27,$pz,"Funcion");
$pdf->addText($px,$py-36,$pz,"Division_func");
$pdf->addText($px,$py-45,$pz,"Grupo_func");
$pdf->addText($px,$py-54,$pz,"Meta");
$pdf->addText($px,$py-63,$pz,"Finalidad");
$pdf->addText($px,$py-72,$pz,"Descripcion");
$px=300;
$pdf->addText($px,$py,$pz," ".$programa."  ");
$pdf->addText($px,$py-9,$pz," ".$prod_pry."  ");
$pdf->addText($px,$py-18,$pz," ".$act_ai_obra."  ");
$pdf->addText($px,$py-27,$pz," ".$funcion."  ");
$pdf->addText($px,$py-36,$pz," ".$division_func."  ");
$pdf->addText($px,$py-45,$pz," ".$grupo_func."  ");
$pdf->addText($px,$py-54,$pz," ".$meta."  ");
$pdf->addText($px,$py-63,$pz," ".$finalidad."  ");
$pdf->addText($px,$py-72,$pz," ".substr($descripcion,0,21)."  ");
$pdf->addText($px-86,$py-81,$pz," ".substr($descripcion,21,40)."  ");
/*$py=$py-2;
$pz=8;
$pdf->addText($px,$py,$pz,".................................");
$pdf->addText($px,$py-9,$pz,".................................");
$pdf->addText($px,$py-18,$pz,".................................");
$pdf->addText($px,$py-27,$pz,".................................");
$pdf->addText($px,$py-36,$pz,".................................");
$pdf->addText($px,$py-45,$pz,".................................");
$pdf->addText($px,$py-54,$pz,".................................");
$pdf->addText($px,$py-63,$pz,".................................");
*/
//RECIBI CONFORME
$pdf->addText(458,109.5,11,"<b>RECIBI CONFORME</b>");
$pdf->addText(428,31.5,11,"<b>DIA</b>");
$pdf->addText(480,31.5,11,"<b>MES</b>");
$pdf->addText(540,31.5,11,utf8_decode("<b>AÑO</b>"));
$pdf->line(210,108,599,108);//H1
$pdf->line(410,30,599,30);//H1
$pdf->line(410,41,599,41);//H1
$pdf->line(410,120,410,13);//V1
$pdf->line(460,41,460,13);//V1
$pdf->line(520,41,520,13);//V1
$pdf->addText(445,55,8,".............................................................");
$pdf->addText(465,45,10,"JEFE DE ALMACEN");


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
