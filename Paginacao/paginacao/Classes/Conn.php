<?php

class Conn
{
    private $host = "127.0.0.1";
    private $user = "root"; // change to yours
    private $pass = "root"; // change to yours
    private $db = "testes";
    public $row_limit = 5;
    public $pdo;

    // connect to mysql
    public function __construct(){
        try {
            $this->pdo = new PDO("mysql:host=$this->host;dbname=$this->db", $this->user, $this->pass);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $this->pdo;
        } catch (PDOException $err) {
            die("Error! " . $err->getMessage());
        }
    }
}

$conn = new Conn();
?>

