<?php
@session_start();
@extract($_REQUEST);

mysql_connect($mysql_host,$mysql_user,$mysql_pass);
mysql_select_db($mysql_db);

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
	$s = mysql_query("SELECT * FROM usuarios WHERE id = '$id'");
	$r = mysql_fetch_array($s);
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