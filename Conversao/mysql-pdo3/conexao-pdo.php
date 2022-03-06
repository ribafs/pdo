<?php
session_start();
ob_start();

$hasDB = false;
$server = 'localhost';
$user = 'root';
$pass = 'root';
$db = 'testes';

//$link = mysql_connect($server,$user,$pass);
$link = new PDO("mysql:host=$server;dbname=$db", $user, $pass);
?>
