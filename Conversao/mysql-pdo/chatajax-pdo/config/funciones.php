<?php
@session_start();
@extract($_REQUEST);
/*
mysql_connect($mysql_host,$mysql_user,$mysql_pass);
mysql_select_db($mysql_db);
*/
$link = new PDO("mysql:host=$mysql_host;dbname=$mysql_db", $mysql_user, $mysql_pass);

function alert($msj){
	?>
	<script type="text/javascript">
		alert("<?=$msj?>");
	</script>
	<?php
}

function redir($url){
	?>
	<script type="text/javascript">
		window.location="<?=$url?>";
	</script>
	<?php
	die();
}

function nombre($id){
    global $link;
	$s = $link->query("SELECT * FROM usuarios WHERE id = '$id'");
	$r = $s->fetch(PDO::FETCH_ASSOC);
	return $r['nombre'];
}

function fecha_hora($fecha){
	$e = explode("-",$fecha);
	$ano = $e[0];
	$mes = $e[1];
	$e2 = explode(" ",$e[2]);
	$dia = $e2[0];
	$e3 = explode(":",$e2[1]);
	$hora = $e3[0];
	$minuto = $e3[1];

	return $dia." / ".$mes." / ".$ano." ".$hora.":".$minuto;
}

?>
