<?php
$hostname = "localhost";
$username = "root"; // change to yours
$password = "root"; // change to yours
$database = "testes";
$rowsLimit = 8;

// connect to mysql
try {
    $pdo = new PDO("mysql:host=$hostname;dbname=$database", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $err) {
    die("Error! " . $err->getMessage());
}
