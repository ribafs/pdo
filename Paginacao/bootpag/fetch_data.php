<?php
require_once("./classes/crud.php");
$crud = new Crud('clientes',$pdo);

if (isset($_POST["page"])) {
    $page_no = filter_var($_POST["page"], FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_STRIP_HIGH);
    if(!is_numeric($page_no))
        die("Erro recebendo dados de index.php! Invalid page number!!!");
} else {
    $page_no = 1;
}

// get record starting position
$start = (($page_no-1) * $crud->row_limit);

if($crud->sgbd == 'mysql' || $crud->sgbd == 'sqlite'){
    $limit = $start .", ".$crud->row_limit;
}elseif($crud->sgbd == 'pgsql'){
    $limit = $crud->row_limit." OFFSET ".$start;
}

//$results = $crud->pdo->prepare("SELECT * FROM ".$crud->table." ORDER BY id LIMIT ".$limit)  or die('Erro na consulta do fetch_data.php');

// Tabelas com relacionamento
// Supondo que a tabela atual seja pedidos e que pedidos esteja relacionado com clientes pelo campo cliente_id, então:
$tbl_rel = 'categorias'; // Altere para a tabela relacionada, se for o caso
$sql = "SELECT $tbl_rel.descricao as descricao, ".$crud->table.".* FROM ".$crud->table." INNER JOIN $tbl_rel ON $tbl_rel.id = ".$crud->table.".categoria_id ORDER BY id LIMIT ".$limit;
$results = $crud->pdo->prepare($sql)  or die('Erro na consulta do fetch_data.php');

$results->execute();

while($row = $results->fetch(PDO::FETCH_ASSOC)) {
	echo "<tr>";
		/********* Chamada do método rows(); ***************/
						// $row  $desc		$fk
		print $crud->rows($row,'descricao','categoria_id');

		// Tabelas sem relacionamento
		// print $crud->rows($row);

		?>
	    <td><a href="update.php?id=<?=$row['id']?>"><i class="glyphicon glyphicon-edit" title="Editar"></a></td>
	    <td><a onclick="return confirm('Tem certeza de que deseja excluir este registro ?')" href="delete_db.php?id=<?=$row['id']?>"><i class="glyphicon glyphicon-remove-circle" title="Excluir"></a></td>
	    </tr>
<?php
}
print '</table>';
