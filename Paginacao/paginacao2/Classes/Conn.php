<?php

class Conn
{
    private $host = "127.0.0.1";
    private $user = "root";
    private $pass = "root";
    private $db = "testes";
    public $rowLimit = 5;
    protected $pdo;
    public $sgbd = 'mysql';

    // connect to mysql
    public function __construct(){
        try {
            $this->pdo = new PDO("$this->sgbd:host=$this->host;dbname=$this->db", $this->user, $this->pass);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $this->pdo;
        } catch (PDOException $err) {
            die("Error! " . $err->getMessage());
        }
    }
}

$conn = new Conn();
?>

