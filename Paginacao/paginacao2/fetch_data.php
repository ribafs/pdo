<?php
include_once("Classes/Conn.php");
include_once("Classes/Crud.php");
$table = 'customers';
$crud = new Crud($table);

if (isset($_POST["page"])) {
    $page_no = filter_var($_POST["page"], FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_STRIP_HIGH);
    if(!is_numeric($page_no))
        die("Error fetching data! Invalid page number!!!");
} else {
    $page_no = 1;
}

// get record starting position
$start = (($page_no-1) * $conn->rowLimit);
$rows = $crud->results($start);

//while($row = $results->fetch(PDO::FETCH_ASSOC)) {
foreach($rows as $row){
    echo "<tr>" . 
    "<td>" . $row->id . "</td>" . 
    "<td>" . $row->name . "</td>" . 
    "<td>" . $row->email . "</td>" . 
    "<td>" . $row->city . "</td>" . 
    "</tr>";
}
?>
