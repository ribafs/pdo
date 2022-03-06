<?php

class Connection
{
    public $host = 'localhost';
    public $db   = 'magic-fw';
    public $user = 'postgres';
    public $pass = 'postgres';
    public $sgbd = 'pgsql';      // Opções: pgsql ou mysql
	public  $pdo;
	private $port = 5432;
	private $charset = 'utf-8';
    public $lang = 'portugues'; // portugues or english
    public $links_per_page = 12; // Links por página
    public $rows_per_page = 8; // Registros por página
    public $appName = 'Criador Automático de Aplicativos';

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
                    // As linhas abaixo devem ser comentadas quando em produção
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
                    // As linhas abaixo devem ser comentadas quando em produção
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
                    // As linhas abaixo devem ser comentadas quando em produção
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


    // Nomes das tabelas do banco atual
	public function tableNames(){
        $con = new Connection();
        $sgbd = $con->sgbd;

		try {
		  if($sgbd=='mysql'){
 		    $sql="SHOW TABLES";
		  }elseif($sgbd=='pgsql'){
			$sql="SELECT relname FROM pg_class WHERE relname !~ '^(pg_|sql_)' AND relkind = 'r';";
		  }elseif($sgbd=='sqlite'){
            $sql='SELECT name FROM sqlite_master WHERE type = "table"';
          }
		  $tableList = array();		  
		  $res = $this->pdo->prepare($sql);
		  $res->execute();
		  while($cRow = $res->fetch())
		  {
			$tableList[] = $cRow[0];
		  }
		  return $tableList;// array
		}catch (PDOException $p){
			print $p->getMessage();
			exit;
		}
	}
}

