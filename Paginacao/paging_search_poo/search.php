<?php 
$table = 'clientes';

require_once('./header.php');
require_once('./classes/connection.php');
require_once('./classes/crud.php');

$con = new Connection();
$sgbd = $con->sgbd;
$crud = new Crud($con->pdo,$table);

// Search
if(isset($_GET['keyword'])){
    $keyword = $_GET['keyword'];
    $field = $crud->fieldName(1);// IMPORTANTE: Busca sempre pelo segundo campo. Caso queira pelo terceiro mudar para 2

    if($sgbd == 'mysql'){
        $sql = "select * from $table WHERE $field LIKE :keyword order by id";
    }elseif($sgbd == 'pgsql'){
        $sql = "select * from $table WHERE $field ILIKE :keyword order by id"; // Para procurar de forma case insensitive
    }

    $sth = $crud->pdo->prepare($sql);
    $sth->bindValue(":keyword", $keyword."%");
    $sth->execute();
	$nr = $sth->rowCount();
    $rows =$sth->fetchAll(PDO::FETCH_ASSOC);
}

print '<div class="container" align="center">';
print '<h4>Registro(s) encontrado(s): '.$nr.'</h4>';

// Converter o if a seguir em função
if(count($rows) > 0){
	print '<div class="container" align="center">';
    echo "<div class='row'>";
    echo '<table class="table table-hover table-responsive table-bordered">';
    echo "<tr>";

    $numFields = $crud->numFields();
        
    for($x=0;$x<$numFields;$x++){
        $field = $crud->fieldName($x);
      	?>
        <th><?=ucfirst($field)?></th>
       	<?php
    }

	print '<th colspan="2">Ação</th>';
    echo "</tr>";
 
    // Loop através dos registros recebidos
    foreach ($rows as $row){
        echo "<tr>";
            for($x=0;$x<$numFields;$x++){
                $field = $crud->fieldName($x);
		        ?>
		        <td><?=$row[$field]?></td>
		        <?php
            }
			?>
            <td><a href="./update.php?id=<?=$row['id']?>&table=<?=$table?>" class="btn btn-primary btn-xs">Editar-<i class="glyphicon glyphicon-edit" title="Editar"></a></td>
            <td><a href="./delete_db.php?id=<?=$row['id']?>&table=<?=$table?>" class="btn btn-danger btn-xs" onClick="return confirm('Tem certeza de que deseja excluir este registro?')">Excluir-<i class="glyphicon glyphicon-remove-circle" title="Excluir"></a></td></tr>
			<?php
        echo "</tr>";
    } 
    echo "</table></div>";

}else{
    echo "<div class='alert alert-danger'><h3>No register found.</h3></div>";
}

?>

<input name="enviar" class="btn btn-warning" type="button" onclick="location='index.php?table=<?=$table?>'" value="Voltar">
</div>

<?php require_once('./header.php'); ?>
