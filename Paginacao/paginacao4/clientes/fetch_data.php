<?php
include_once("../Classes/Crud.php");
$table = 'clientes';
$crud = new Crud($table);

if (isset($_POST["page"])) {
    $page_no = filter_var($_POST["page"], FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_STRIP_HIGH);
    if(!is_numeric($page_no))
        die("Error fetching data! Invalid page number!!!");
} else {
    $page_no = 1;
}

$start = (($page_no-1) * $conn->regsPerPage);
$results = $crud->paginacao($start);
$results->execute();

// get record starting position
$start = (($page_no-1) * $conn->linksPerPage);

while($row = $results->fetch(PDO::FETCH_ASSOC)) {
    echo "<tr>" . $crud->rowFields($row);
?>
     <td><a href="update.php?id=<?=$row['id']?>&table=<?=$table?>"><i class="glyphicon glyphicon-edit" title="Update"></a></td>
     <td><a onclick="return confirm('Realmente excluir o cliente <?=$row['id']?> ?')" href="delete.php?id=<?=$row['id']?>&table=<?=$table?>"><i class="glyphicon glyphicon-remove-circle" title="Delete"></a></td></tr>
    <?php
    }
?>
