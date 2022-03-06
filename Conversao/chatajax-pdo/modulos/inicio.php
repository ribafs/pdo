<section id="mensajes">

</section>
<section id="enviar">
<input type="text" id="msg" placeholder="Escribe algo..." class="campo" style="width:98%;" onkeyup="chequearenviar(event)"/>
</section>
<section id="oculto">
	</section>

<script>
	function recargarmensajes(){
		$.ajax({
			url : "ajax/mensajes.php",
			type : "post",
			success:function(response){
				$("#mensajes").html(response);
			}
		});
}

function chequearenviar(e){
	if(e.which==13){
		enviar_mensaje();
	}
}

function enviar_mensaje(){
	var mensaje = $("#msg").val();

	var parametros = {
		"mensaje" : mensaje
	};

	$.ajax({
		type : "post",
		data : parametros,
		url : "ajax/enviar.php",
		success:function(response){
			$("#oculto").html(response);
			$("#msg").val('');
			recargarmensajes();
		}
	})
}

setInterval("recargarmensajes()",1000);
</script>