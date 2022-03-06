<?php
include "../config/config.php";
include "../config/funciones.php";
$s = mysql_query("SELECT * FROM mensajes ORDER BY id DESC");
while($r=mysql_fetch_array($s)){
	$q = mysql_query("SELECT * FROM usuarios WHERE id = '".$r['id_envia']."'");
	$ru = mysql_fetch_array($q);
	$usuario = $ru['nombre'];
	?>
	<div style="background: rgba(0,0,0,0.06);margin-top:3px;padding:5px;">
	<b style="color:#08f;"><?=$usuario?></b> <?=$r['texto']?> <small style="color:#aaa;float:right;" ><?=fecha_hora($r['fecha'])?></small>
	</div>
	<?php
}
?>