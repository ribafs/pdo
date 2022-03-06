<?php
check_admin();
no_dr();
?>
<h1><i class="fa fa-file-text"></i> Lista de Administradores</h1>
<hr>
<table class="table table-striped">
<tr>
<th>Nombre</th>
<th>Email</th>
<th>Usuario</th>
<th><i class="fa fa-cog fa-spin"></i></th>
</tr>
<?php
$s = $link->query("SELECT * FROM accounts WHERE rol = 1");
while($r=$s->fetch(PDO::FETCH_ASSOC)){
	?>
	<tr>
	<td><?=$r['nombre']?></td>
	<td><?=$r['email']?></td>
	<td><?=$r['user']?></td>
	<td><a href=""><i class="fa fa-eye" data-toggle="tooltip" title="Ver Administrador"></i></a>
	 	 &nbsp; 
		<a href=""><i class="fa fa-trash-o" data-toggle="tooltip" title="Eliminar"></i></a>
		 &nbsp; 
		<a href=""><i data-toggle="tooltip" title="Modificar" class="fa fa-edit"></i></a>
	</tr>
	<?php
}
?>
</table>
<br>
<a href="?p=adm_add_adm">
<button class="btn btn-primary"><i class="fa fa-plus-circle"></i> Agregar Administrador</button>
</a>
