<?php
$hostname = "localhost";
$username = "root";
$password = "root";
$database = "estoque";
$rowsLimit = 8;
$appTitle = 'Estoque Simplificado';

// connect to mysql
try {
    $pdo = new PDO("mysql:host=$hostname;dbname=$database", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $err) {
    die("Error! " . $err->getMessage());
}
