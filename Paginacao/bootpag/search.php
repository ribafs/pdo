<?php 
require_once('./header.php');
require_once '../classes/crud.php';
$crud = new Crud('clientes',$pdo);

// Busca
if(isset($_GET['keyword'])){
    $keyword=$_GET['keyword'];
	// Pegar o nome do segundo campo
    $field = $crud->fieldName(1);

    $sql = "select * from ".$crud->table." WHERE $field LIKE :keyword order by id";
    $sth = $crud->pdo->prepare($sql);
    $sth->bindValue(":keyword", $keyword."%");
    $sth->execute();
	$nr = $sth->rowCount();
    $rows =$sth->fetchAll(PDO::FETCH_ASSOC);
}
print '<h3 align="center">'.$crud->appName.'</h3>';
print '</div>';

print '<div class="container" align="center">';
print '<h4>Registro(s) encontrado(s): '.$nr.'</h4>';

if(count($rows) > 0){
?>
	<div class="container" align="center">
    <table class="table table-hover">
    <tr>
		<?php print $crud->labels(); ?>
    </tr>
 
<?php
    // Loop através dos registros recebidos
    foreach ($rows as $row){
		?>
        <tr>
			<?php	print $crud->rows($row); ?>

            <td><a href="update.php?id=<?=$row['id']?>"><i class="glyphicon glyphicon-edit" title="Editar"></a></td>
            <td><a href="delete.php?id=<?=$row['id']?>"><i class="glyphicon glyphicon-remove-circle" title="Excluir"></a></td></tr>
        </tr>
	<?php
    } 
    echo "</table>";

}else{
    print '<h3>Nenhum Registro encontrado!</h3>';
}
?>

<input name="send" class="btn btn-warning" type="button" onclick="location='index.php'" value="Voltar">
</div>
<br>
<?php require_once('./footer.php'); ?>
