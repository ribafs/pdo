<?php
require_once('../Classes/Crud.php');
$table = 'clientes';
$crud = new Crud($table);

$id=$_GET['id'];

$reg = $crud->updateReg($id);

$nome = $reg->nome;
$email = $reg->email;
$cpf = $reg->cpf;

require_once('../header.php');
?>
<div class="container">
    <div class="panel panel-default">
        <div class="panel-heading text-center"><b><h3><?=$conn->appTitle?></h3>Atualizar</b></div>
        <div class="row">
        <div class="col-md-3"></div>
        <div class="col-md-6">
            <form method="post" action="">
                <table class="table table-bordered table-responsive table-hover">

                <?=$crud->updateFields($reg)?>

                <input name="id" type="hidden" value="<?=$id?>">
                <tr><td></td><td><input name="enviar" class="btn btn-primary" type="submit" value="Editar">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <input name="enviar" class="btn btn-warning" type="button" onclick="location='index.php'" value="Voltar"></td></tr>
                </table>
            </form>
            <?php require_once('../footer.php'); ?>
        </div>
    <div>
</div>

<?php
if(isset($_POST['enviar'])){
    $id = $_POST['id'];
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $cpf = $_POST['cpf'];

    if($crud->update($id,$nome,$email,$cpf)){
        print 'Registro alterado com sucesso!';
        header('location: index.php');
        exit();
    }else{
        print "Erro ao alterar o registro!<br><br>";
        exit();
    }
}
?>

