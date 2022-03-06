<?php
class Conn
{
    private $host = "127.0.0.1";
    private $user = "root";
    private $pass = "root";
    private $db = "estoque";
    protected $pdo;
    public $sgbd = 'mysql';
	public $regsPerPage = 8; // Registros por página
    public $linksPerPage = 23;
    public $appTitle = '<a href="../index.php" title="Voltar ao menu">Estoque Simplificado</a>';

    private $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];

    // connect to mysql
    public function __construct(){
        try {
            $this->pdo = new PDO("mysql:host=$this->host;dbname=$this->db", $this->user, $this->pass, $this->options);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $this->pdo;
        } catch (PDOException $err) {
            die("Error! " . $err->getMessage());
        }
    }
}

$conn = new Conn();

