<?php
require_once('../Classes/Crud.php');
$table = 'clientes';
$crud = new Crud($table);

$id=$_GET['id'];

$reg = $crud->deleteReg($id);

$id = $reg->id;
$nome = $reg->nome;
$email = $reg->email;
$cpf = $reg->cpf;

if($crud->delete($id)){
    echo 'Registro excluído com sucesso';
    header('location: index.php');
}

?>
