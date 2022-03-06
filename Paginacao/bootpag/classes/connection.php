<?php

class Connection
{
	private $host = 'localhost';
	private $db   = 'simple-fw';
	private $user = 'root';
	private $pass = 'root';
	public  $sgbd = 'mysql';// Testados: mysql, pgsql e sqlite
	public  $pdo;
	private $port = 3306;
	private $charset = 'utf-8';

	public $row_limit = 10; // Registros por página
	public $links_pages = 12; // Links da barra de paginação

    public $appName = 'Controle de Estoque';

    public function __construct(){

		switch ($this->sgbd){
			case 'mysql':
				try {
					$dsn = $this->sgbd.':host='.$this->host.';dbname='.$this->db.';port='.$this->port;
					$this->pdo = new PDO($dsn, $this->user, $this->pass);
					// Boa exibição de erros
					$this->pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES,false);
					$this->pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

					$this->pdo->query('SET NAMES utf8');
					return $this->pdo;

				}catch(PDOException $e){
					echo '<br><br><b>Código</b>: '.$e->getCode().'<hr><br>';
					echo '<b>Mensagem</b>: '. $e->getMessage().'<br>';// Usar estas linhas no catch apenas em ambiente de testes/desenvolvimento
					echo '<b>Arquivo</b>: '.$e->getFile().'<br>';
					echo '<b>Linha</b>: '.$e->getLine().'<br>';
					exit();
				}
				break;

			case 'pgsql':
				try {
					$dsn = $this->sgbd.':host='.$this->host.';dbname='.$this->db.';port='.$this->port;
					$this->pdo = new PDO($dsn, $this->user, $this->pass);

					// Boa exibição de erros
					$this->pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES,false);
					$this->pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

					return $this->pdo;

				}catch(PDOException $e){
					echo '<br><br><b>Código</b>: '.$e->getCode().'<hr><br>';
					echo '<b>Mensagem</b>: '. $e->getMessage().'<br>';// Usar estas linhas no catch apenas em ambiente de testes/desenvolvimento
					echo '<b>Arquivo</b>: '.$e->getFile().'<br>';
					echo '<b>Linha</b>: '.$e->getLine().'<br>';
					exit();
				}
				break;

			case 'sqlite':
				try {
					$this->pdo = new PDO('sqlite:/home/ribafs/estoque.db');

					// Boa exibição de erros
					$this->pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES,false);
					$this->pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

					return $this->pdo;

				}catch(PDOException $e){
					echo '<br><br><b>Código</b>: '.$e->getCode().'<hr><br>';
					echo '<b>Mensagem</b>: '. $e->getMessage().'<br>';// Usar estas linhas no catch apenas em ambiente de testes/desenvolvimento
					echo '<b>Arquivo</b>: '.$e->getFile().'<br>';
					echo '<b>Linha</b>: '.$e->getLine().'<br>';
					exit();
				}
				break;
			case 'default':
				break;
/*
            case 'ibase':
                $conn = new PDO("firebird:dbname={$name}", $user, $pass);
                break;
            case 'oci8':
                $conn = new PDO("oci:dbname={$name}", $user, $pass);
                break;
            case 'mssql':
                $conn = new PDO("mssql:host={$host},1433;dbname={$name}", $user, $pass);
                break;
*/				
		}
	}
}

