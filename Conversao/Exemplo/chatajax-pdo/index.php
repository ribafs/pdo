<?php
include "config/config.php";
include "config/funciones.php";

if(!isset($p)){
	$p = "inicio";
}

if(isset($registrar)){
	$scu = $link->query("SELECT * FROM usuarios WHERE user = '$user'");
	if($scu->rowCount()>0){
		alert("Este usuario ya esta en uso");
		redir("");
	}
	
	mysql_query("INSERT INTO usuarios (nombre,user,pass) VALUES ('$nombre','$user','$pass')");
	alert("Te has registrado satisfactoriamente");
	$s = $link->query("SELECT * FROM usuarios WHERE user = '$user'");
	$r = $s->fetch(PDO::FETCH_ASSOC);
	$_SESSION['id'] = $r['id'];
	redir("./");
}

if(isset($enviar)){
	$s = $link->query("SELECT * FROM usuarios WHERE user = '$userlogin' AND pass = '$passlogin'");
	if($s->rowCount()>0){
		$r = $s->fetch(PDO::FETCH_ASSOC);
		$_SESSION['id'] = $r['id'];
		alert("Bienvenido ".$r['nombre']);
		redir("./");
	}else{
		alert("Los datos son invalidos");
		redir("");
	}
}

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8"/>
	<link rel="stylesheet" href="css/estilo.css"/>
	<script type="text/javascript" src="jquery.js"></script>
	<title>Chat</title>
</head>
<body>

	<?php
	if(isset($_SESSION['id'])){
		?>
		Hola <?=nombre($_SESSION['id'])?>, Bienvenido <a href="?p=salir">Salir</a>
		<br>
		<br>

		<?php
		if(file_exists("modulos/".$p.".php")){
			include "modulos/".$p.".php";
		}else{
			echo "<i>El modulo seleccionado no existe</i> <a href='./'>Regresar</a>";
		}
	}else{
		if($p!="registrate"){
		?>
		<center>
			<br><br><br>
			<h1>¡Conectate Ahora!</h1><br><br>
			<form method="post" action="">
				<input class="campo" type="text" name="userlogin" placeholder="Usuario"/><br><br>
				<input class="campo" type="password" name="passlogin" placeholder="Contraseña"/><br><br>
				<button class="boton" name="enviar">Ingresar</button><br><br>
				<a href="?p=registrate">Si no tienes cuenta ¡Registrate!</a>
			</form>
		</center>
		<?php
		}else{
			?>
			<center>
			<br><br><br>
			<h1>¡Registrate Ahora!</h1><br><br>
			<form method="post" action="">
				<input class="campo" type="text" name="nombre" placeholder="Nombre"/><br><br>
				<input class="campo" type="text" name="user" placeholder="Usuario"/><br><br>
				<input class="campo" type="password" name="pass" placeholder="Contraseña"/><br><br>
				<button class="boton" name="registrar">Registrate</button><br><br>
				<a href="./">¿Ya tienes cuenta? ¡Inicia Sesión!</a>
			</form>
		</center>
			<?php
		}
	}
	?>

</body>
</html>
