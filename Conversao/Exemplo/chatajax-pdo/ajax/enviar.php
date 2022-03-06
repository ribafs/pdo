<?php
include "../config/config.php";
include "../config/funciones.php";
$mensaje = str_replace(":/","<img src=\'emoticones/plop.jpg\' style=\'width:20px;height:20px;\'/>",$mensaje);
$mensaje = str_replace(":)","<img src=\'emoticones/risa.jpg\' style=\'width:20px;height:20px;\'/>",$mensaje);
$mensaje = str_replace(":D","<img src=\'emoticones/sonrisa.png\' style=\'width:20px;height:20px;\'/>",$mensaje);

$q = $link->query("INSERT INTO mensajes (id_envia,fecha,texto) VALUES ('".$_SESSION['id']."',NOW(),'$mensaje')");
if(!$q){
	alert('Erro');
}
?>
