<?php
$hostname = "localhost";
$username = "root";
$password = "";
$database = "estoque";
$row_limit = 5;

// connect to mysql
try {
    $pdo = new PDO("mysql:host=$hostname;dbname=$database", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $err) {
    die("Error! " . $err->getMessage());
}
