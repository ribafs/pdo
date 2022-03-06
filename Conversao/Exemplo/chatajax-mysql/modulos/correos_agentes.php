<?php
check_admin();
if(isset($enviar)){
	$sc = mysql_query("SELECT * FROM correos_clientes WHERE correo = '$correo'");
	if(mysql_num_rows($sc)>0){
		alert("El correo ya esta agregado.");
		redir("");
	}
	mysql_query("INSERT INTO correos_clientes (correo) VALUES ('$correo')");
	alert("Correo Agregado");
	redir("");
}
if(isset($e)){
	mysql_query("DELETE FROM correos_clientes WHERE id = '$e'");
	alert("Correo eliminado");
	redir("?p=correos_clientes");
}
?>
<h1><i class="fa fa-envelope"></i> Manejar correo de clientes</h1>
<br>
<hr>
<form method="post" action="">
	<div class="form-group">
		<input type="text" class="form-control" placeholder="Correo a agregar" name="correo"/>
	</div>
	<div class="form-group">
		<button class="btn btn-primary" type="submit" name="enviar"><i class="fa fa-check"></i> Agregar Correo</button>
	</div>
</form>
<br>
<hr>
<br>
<h1><i class="fa fa-file-text"></i> Lista de correos</h1>
<br>
<table class="table table-striped">
	<tr>
		<th>Correo</th>
		<th>Acciones</th>
	</tr>
	<?php
	$sc = mysql_query("SELECT * FROM correos_clientes");
	while($rc=mysql_fetch_array($sc)){
		?>
		<tr>
			<td><?=$rc['correo']?></td>
			<td><a href="?p=correos_clientes&e=<?=$rc['id']?>"><i class="fa fa-times" data-toggle="tooltip"></i></a></td>
		</tr>
		<?php
	}
	?>
</table>