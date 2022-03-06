<?php

class Db{
	private $host=Config::HOST;
	private $user=Config::USER;
	private $pass=Config::PASS;
	private $db=Config::DB;
	private $sgbd=Config::SGBD;		
	private $port=Config::PORT;
	public $pdo;	
	private $dsn;	
	
	
	public function __construct(){		
		try {   
			$this->dsn=$this->sgbd.':host='.$this->host.';port='.$this->port.';dbname='.$this->db;
			$this->pdo = new PDO($this->dsn, $this->user, $this->pass);
			$this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}catch (PDOException $p){
			print $p->getMessage();
			exit;
		}
	}

	public function query($sql){
		try{
	    	return $this->pdo->query($sql);
		}catch (PDOException $p){
			print $p->getMessage();
			exit;
		}
	}

	public function rowsCount($sql){
		try {   
			$res = $this->pdo->query($sql);
			$count = $res->rowCount();
			return $count;
		}catch (PDOException $p){
			print $p->getMessage();
			exit;
		}
	}

	public function escape($str){
		try {   
			return $this->pdo->quote($str);
		}catch (PDOException $p){
			print $p->getMessage();
			exit;
		}
	}

	public function select($sql){
		try {   	
			$q = $this->pdo->query($sql) or die("failed!");		
			while($r = $q->fetch(PDO::FETCH_ASSOC)){
				$data[]=$r;
			}
			return $data;
		}catch (PDOException $p){
			print $p->getMessage();
			exit;
		}
	}

	public function update($strSql,$strUpd){
		try {
			$q = $this->pdo->prepare($strSql);
			$q->execute($strUpd);		
			return true;		
		}catch (PDOException $p){
			print $p->getMessage();
			exit;
		}
	}

	public function insert($strSql,$strIns){
		try {
			$q = $this->pdo->prepare($strSql);
			$q->execute($strIns);
			return true;
		}catch (PDOException $p){
			print $p->getMessage();
			exit;
		}
	}
	
	public function delete($id,$sql,$del){	
		try {
			$q = $this->pdo->prepare($sql);
			$q->execute($del);
			return true;	
		}catch (PDOException $p){
			print $p->getMessage();
			exit;
		}
	}	
	// Crédito para as funções do CRUD acima: http://www.w3programmers.com/crud-with-pdo-and-oop-php/

	public function getCharset(){
		try {
			$sql='';
			if($this->sgbd=='mysql'){
				$sql = "SELECT COLLATION('abc')";
			}elseif($this->sgbd=='pgsql'){
				$sql = "select datcollate from pg_database where datname='$this->db'";
			}
			$charset = $this->pdo->query( $sql );
			return $charset->fetch( PDO::FETCH_NUM )[0];
		}catch (PDOException $p){
			print $p->getMessage();
			exit;
		}
	}

	public function oneReg($sql){
		try {
			$sth = $this->pdo->prepare($sql);
			$sth->execute();
			$result = $sth->fetch(PDO::FETCH_ASSOC);
			return $result;
		}catch (PDOException $p){
			print $p->getMessage();
			exit;
		}
	}

	public function allRegs($sql){
		try {
			$sth = $this->pdo->prepare($sql);
			$sth->execute();
			$rows = $sth->fetchAll();
			return $rows;
		}catch (PDOException $p){
			print $p->getMessage();
			exit;
		}
	}

	public function fieldType($table, $fldName){	
		try {
			if($this->sgbd =='mysql'){
				$sql="SHOW COLUMNS as col FROM $table WHERE Field = '$fldName'";
			}elseif($this->sgbd =='pgsql'){
				$sql="SELECT data_type as col FROM information_schema.columns WHERE table_name = '$table' and 					column_name='$fldName' ORDER BY ordinal_position;";
			}
			$res = $this->pdo->query($sql);
			$type = $res->fetch();
			return $type['col'];
		}catch (PDOException $p){
			print $p->getMessage();
			exit;
		}
	}

	public function fieldName($sql,$nr){	
		try {
			$select = $this->pdo->query($sql);
			$meta = $select->getColumnMeta($nr);
			$field = $meta['name'];
			return $field;
		}catch (PDOException $p){
			print $p->getMessage();
			exit;
		}
	}

	public function fieldLen($table,$fldName){	
		try {
			if($this->sgbd=='mysql'){
			$sql = "SELECT character_maximum_length as len FROM INFORMATION_SCHEMA.columns where table_name='$table' and column_name='$fldName'";
			}elseif($this->sgbd=='pgsql'){
				$sql="SELECT character_maximum_length as len FROM information_schema.columns
				WHERE table_name = '$table' and column_name='$fldName' ORDER BY ordinal_position;";
			}
			$select = $this->pdo->query($sql);
			$field = $select->fetch();
			return $field['len'];
		}catch (PDOException $p){
			print $p->getMessage();
			exit;
		}
	}

	public function notNull($table){	
		try {
			$sql = "SELECT column_name FROM INFORMATION_SCHEMA.columns WHERE 
			table_name = '$table' and is_nullable = 'NO'";
			$sth = $this->pdo->query($sql);
			return $sth->fetchAll();// Retorna números dos campos, de 0 ao último
		}catch (PDOException $p){
			print $p->getMessage();
			exit;
		}
	}

	public function fieldsCount($sql){	
		try {
			$sth = $this->pdo->query($sql);
			$colcount = $sth->columnCount();
			return $colcount;
		}catch (PDOException $p){
			print $p->getMessage();
			exit;
		}
	}

	public function primaryKey($table){	
		try {
			if($this->sgbd=='mysql'){
				$sql = $this->pdo->query("SHOW INDEX FROM $table");
			}elseif($this->sgbd=='pgsql'){
				$sql="SELECT kcu.column_name as Column_name FROM information_schema.table_constraints tc LEFT JOIN information_schema.key_column_usage kcu ON tc.constraint_name = kcu.constraint_name
    WHERE tc.table_name = '$table' AND constraint_type = 'PRIMARY KEY';";
			}
			$qry=$this->pdo->query($sql);
			$pk = $qry->fetch();
			return $pk['column_name'];
		}catch (PDOException $p){
			print $p->getMessage();
			exit;
		}
	}

	public function foreignKey($table){
		try {
			if($this->sgbd=='mysql'){
			$sql = "select column_name, concat(referenced_table_name, '.', referenced_column_name) as 'references' from information_schema.key_column_usage where referenced_table_name is not null and table_name='$table';";	
			}elseif($this->sgbd='pgsql'){
				$sql="SELECT kcu.column_name FROM information_schema.table_constraints AS tc 
    JOIN information_schema.key_column_usage AS kcu ON tc.constraint_name = kcu.constraint_name
    JOIN information_schema.constraint_column_usage AS ccu ON ccu.constraint_name = tc.constraint_name
WHERE constraint_type = 'FOREIGN KEY' AND tc.table_name='pedidos';";
			}
			$res=$this->pdo->query($sql);
		    $nf = $res->columnCount();
			$row=0;
			$regs=array();

			while ($data = $res->fetch(PDO::FETCH_NUM)) {
				for($x=0;$x < $nf;$x++){
					array_push($regs, $data[$x]);
				}
		   		$row++;
			}	
			if($regs[0]){			
				return $regs[0];// Retorna somente o primeiro campo FK
			}else{
				print '<b>This table dont have a foreign key</b>';
			}
		}catch (PDOException $p){
			print $p->getMessage();
			exit;
		}
	}

	public function tableNames(){
		try {
		  if($this->sgbd=='mysql'){
 		    $sql="SHOW TABLES";
		  }elseif($this->sgbd=='pgsql'){
			$sql="SELECT relname FROM pg_class WHERE relname !~ '^(pg_|sql_)' AND relkind = 'r';";
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

	public function combo($sql,$fldStore, $fldDisplay, $selected){
		try {
			$combo='';
			$rs = $this->pdo->query($sql);
			$nr = $rs->rowCount($rs);
			for ($i=0; $i<$nr; $i++) {
				$r = $rs->fetch();
				if($r[$fldStore]==$selected){
					$combo .= "<OPTION VALUE=\"".$r[$fldStore]."\" selected>".$r[$fldDisplay]."</OPTION>\n";
				}else{
					$combo .= "<OPTION VALUE=\"".$r[$fldStore]."\">".$r[$fldDisplay]."</OPTION>\n";
				}
			}
			return $combo;
		}catch (PDOException $p){
			print $p->getMessage();
			exit;
		}
	}
}
