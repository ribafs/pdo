<?php
$table = 'clientes';

include_once './classes/connection.php';
include_once './classes/crud.php';
//include_once 'objects/category.php';
 
$con = new Connection();
$pdo = $con->pdo;

$crud = new Crud($pdo, $table);
//$category = new Category($db);
 
include_once "./header.php";

// page given in URL parameter, default page is one
$page = isset($_GET['page']) ? $_GET['page'] : 1; 
 
// set number of records per page
$records_per_page = $con->rows_per_page;
 
// calculate for the query LIMIT clause
$from_record_num = ($records_per_page * $page) - $records_per_page;

$stmt = $crud->readAll($from_record_num, $records_per_page);
 
// specify the page where paging is used
$page_url = "./index.php?";
 
// count total rows - used for pagination
$total_rows=$crud->countAll();
 
// grid.php controls how the register list will be rendered
include_once "./grid.php";
 
// layout_footer.php holds our javascript and closing html tags
include_once "./footer.php";
?>
