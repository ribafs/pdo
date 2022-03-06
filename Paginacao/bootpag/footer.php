
<div class="container rodape" align="center">
    <i>"Criado" por <a href="https://ribafs.org" target="_blank">Ribamar FS</a></i>
</div>
<br>
<script>
// Função que impede o cadastro com a combo vazia e pode ser usada em vários arquivos que incluem footer.php
// Sendo usado no insert.php
function empty() {
    var x;
    x = document.getElementById("cat_id").value;
    if (x == "0") {
        alert("Campo categoria_id é obrigatório!");
		frm.nome.focus();
        return false;
    };
}

// Pedir confirmação de exclusão:
// onclick="return confirm('Tem certeza de que deseja excluir este registro ?')";

</script>
</body>
</html>
