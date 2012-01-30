<?php
require_once('home.php');

$fp = fopen ( "termi.csv" , "r" );
$i = 0;
while (( $data = fgetcsv( $fp , 1000 , ";" )) !== FALSE ) { // Mientras hay líneas que leer...
$i++;
if($i!=1) {
	//$sql = "INSERT INTO $bcdb->proyectos VALUES (null, 1, '$data[0]', '$data[1]', '$data[2]', '$data[3]', '$data[4]', '$data[5]', 2009);";
	//echo $sql;
	//$bcdb->query($sql);
 }
}
fclose ( $fp ); 
?>