<?php
require_once('../header.php');
require_once('../Classes/Crud.php');
$table = 'clientes';
$crud = new Crud($table);
?>

<div class="container">
    <div class="panel panel-default">
        <div class="panel-heading text-center"><h3><b><?=$conn->appTitle?></h3>Adicionar</b></div>
        <div class="row">

        <div class="col-md-3"></div>
        <div class="col-md-6">

        <table class="table table-bordered table-responsive table-hover">    
            <form method="post" action="add.php">         
            <?php
                print $crud->formAdd();
            ?>  
            <tr><td></td><td><input class="btn btn-primary" name="enviar" type="submit" value="Cadastrar">&nbsp;&nbsp;&nbsp;
            <input class="btn btn-warning" name="enviar" type="button" onclick="location='index.php'" value="Voltar"></td></tr>
            </form>
        </table>
        </div>
    </div>
</div>

<?php
if(isset($_POST['enviar'])){
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $cpf = $_POST['cpf'];

    if($crud->insert($nome,$email,$cpf)){
        echo 'Registro adicionado com sucesso';
        header('location: index.php');
        exit();
    }
}

require_once('../footer.php');
?>

