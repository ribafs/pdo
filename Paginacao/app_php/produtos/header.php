<?php
include_once("../includes/db_connect.php");
include_once("../includes/functions.php");

$stmt = $pdo->prepare("SELECT COUNT(*) FROM clientes");
$stmt->execute();
$rows = $stmt->fetch();

// get total no. of pages
$total_pages = ceil($rows[0]/$row_limit);
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <title><?=$titleMenu?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="../assets/css/style.css" rel="stylesheet" type="text/css" />
    <style type="text/css">
    .panel-footer {
        padding: 0;
        background: none;
    }
    </style>
</head>
