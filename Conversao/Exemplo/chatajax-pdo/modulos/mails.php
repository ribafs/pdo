<?php
check_admin();
no_dr();
?>
<h1><i class="fa fa-file-text"></i> Lista de Pagos</h1>
<hr>
<table class="table table-striped">
<tr>
<th>Cliente</th>
<th>Fecha</th>
<th>Monto</th>
<th>Estatus</th>
</tr>
<?php
$s = $link->query("SELECT * FROM pagos");
while($r=$s->fetch(PDO::FETCH_ASSOC)){

if($r['pagado']==0){
	$estatus = "<p style='color:red'>No Pagado</p>";
}else{
	$estatus = "<p style='color:green'>Pagado</p>";
}

	$su = $link->query("SELECT * FROM clientes WHERE id = '".$r['id_cliente']."'");
	$ru = $su->fetch(PDO::FETCH_ASSOC);
	?>
	<tr>
	<td><b><?=$ru['user']?></b></td>
	<td><?=fecha_hora($r['fecha'])?></td>
	<td style="color:green;"><?=$r['cant']?> €</td>
	<td><?=$estatus?></td>
	</tr>
	<?php
}
?>
</table>
