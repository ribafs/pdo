<?php
require_once('Classes/Crud.php');
$crud = new Crud('customers');

$id=$_GET['id'];

$sth = $crud->pdo->prepare("SELECT * from customers WHERE id = :id");
$sth->bindValue(':id', $id, PDO::PARAM_STR);
$sth->execute();

$reg = $sth->fetch(PDO::FETCH_OBJ);
$id = $reg->id;
$name = $reg->name;
$email = $reg->email;
$city = $reg->city;

if($crud->delete($id)){
    echo 'Registro excluído com sucesso';
    header('location: index.php');
}

?>
