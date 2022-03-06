<!DOCTYPE html>
<html lang="en">
<head>
    <title>AJAX Pagination using PHP & MySQL</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" integrity="sha384-HSMxcRTRxnN+Bdg0JdbxYKrThecOKuH5zCYotlSAcp1+c8xmyTe9GYg1l9a69psu" crossorigin="anonymous">

    <style type="text/css">
    .panel-footer {
        padding: 0;
        background: none;
    }
    </style>
</head>
<body>
<br/>
<div class="container">
    <div class="panel panel-default">
        <div class="panel-heading text-center"><h3>jQuery PHP Pagination Demo</h3></div>

<?php 
require_once './Classes/Conn.php';
require_once './Classes/Crud.php';
$crud = new Crud('customers');

// Busca
if(isset($_GET['keyword'])){
    $keyword=$_GET['keyword'];
    $rows = $crud->search($keyword);
}
print '<h3 align="center">Paginação</h3>';
print '</div>';

print '<div class="container" align="center">';

if(count($rows) > 0){
?>
<div class="container" align="center">
  <table class="table table-hover">
    <tr>
      <th>ID</th><th>Name</th><th>E-mail</th><th>City</th>
    </tr>
 
<?php
    foreach ($rows as $row){
      print '<tr>';
        print '<td>'.$row['id'].'</td><td>'.$row['name'].'</td><td>'.$row['email'].'</td><td>'.$row['city'].'</td>';
      print '</tr>';
    } 
    echo "</table>";

}else{
    print '<h3>Nenhum Registro encontrado!</h3>';
}
?>
<br>
<input name="send" class="btn btn-warning" type="button" onclick="location='index.php'" value="Voltar">
</div>

