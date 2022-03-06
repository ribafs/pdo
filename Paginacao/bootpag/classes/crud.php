<?php

require_once 'connection.php';
$conn = new Connection();
$pdo = $conn->pdo;

/* Classe que trabalha com um crud, lidando com uma tabela por vez, que é fornecida a cada instância, desde a conexão com o banco */

class Crud extends Connection
{

	public  $table;
	public $pdo;

	public function __construct($table, $pdo){
		$this->table = $table;
		$this->pdo = $pdo;
	}

	// Número de campos da tabela atual
	public function numFields(){		
		$sql = "SELECT * FROM $this->table limit 1";
		$sth = $this->pdo->query($sql);
		return $sth->columnCount();
	}

	// Nome de campo pelo número $x
	public function fieldName($x){
		$sql = "SELECT * FROM $this->table limit 1";
		$sth = $this->pdo->query($sql);
		$meta = $sth->getColumnMeta($x);
		return $meta['name'];
	}

	// Labels para index.php da grid principal
	public function labels(){
		$labels='';
		for($x=0;$x<$this->numFields();$x++){
			$labels .= '<th>'.ucFirst($this->fieldName($x))."</th>\n";
		}		
		$labels .= '<th colspan="2">Ação</th>';
		return $labels;
	}

	// Retornar registros para fetch_data.php (grid principal), parâmetros: $row, $desc - descricão da tabela relacionada e $fk da tabela atual
	public function rows($row,$desc=null,$fk=null){
		$rs='';
		for($x=0;$x<$this->numFields();$x++){
			$fd = $this->fieldName($x);
			if($fd == $fk) {
				$rs .= '<td>'.$row["$desc"]."</td>\n";
			}else{
				$rs .= '<td>'.$row["$fd"]."</td>\n";
			}
		}
		return $rs;
	}

	// Argumentos:
	// $tbl - nome da tabela relacionada
	// $id - nome do campo id da tabela relacionada
	// $desc - nome do campo para exibir na combo da tabela relacionada
	// $fk - nome do campo que é chame estrangeira na tabela atual
	public function inputsInsert($fk=null,$tbl=null,$id=null,$desc=null){
		$inp='';
		for($x=1;$x<$this->numFields();$x++){
			$fd = $this->fieldName($x);
			if($fd !== $fk || $fk == 'n') {
			$inp .='<tr><td><b>'.ucfirst($fd)."</td><td><input type=\"text\" name=\"$fd\" size=\"25\"></td></tr>\n";
			}

			if($fd == $fk){
				$sql="SELECT $id,$desc FROM $tbl order by $desc";
				$sth = $this->pdo->query($sql) or die('Falha na consulta combo_insert()!');

				$i=0;
				$combo='';
				while($r = $sth->fetch(PDO::FETCH_ASSOC)){
					$data[]=$r;
					$fid = $data[$i][$id];
					$fdesc = $data[$i][$desc];
					$combo .= "<option value=\"$fid\">$fdesc</option>\n";
					$i++;
				}					

				$inp .="<tr><td><b>".ucfirst($fd)."</td><td><select name=".$fd." id=\"cat_id\">\n".
					'<option value="0">Selecione</option>'."\n";
				$inp .= $combo;
				$inp .='</select></td></tr>';				
			}
		}
		$inp .= "\n".'<tr><td></td><td><input class="btn btn-primary" name="send" type="submit" value="Cadastrar" onClick="return empty()">&nbsp;&nbsp;&nbsp;'.
            "\n".'<input class="btn btn-warning" name="send" type="button" onclick="location=\'index.php\'" value="Voltar"></td></tr>';
		return $inp;
	}

	// ARgumentos:
	// $tbl - nome da tabela relacionada
	// $id - nome do campo id da tabela relacionada
	// $reg - para receber a variável de update.php
	// $desc - nome do campo para exibir na combo da tabela relacionada
	// $fk - nome do campo que é chame estrangeira na tabela atual
	public function inputsUpdate($fk=null,$reg=null,$tbl=null,$id=null,$desc=null){
		$inp='';
		for($x=1;$x<$this->numFields();$x++){
			$fd = $this->fieldName($x);
			if($fd !== $fk || $fk == 'n') {
				$inp .='<tr><td><b>'.ucfirst($fd).'</td><td><input type="text" name="'.$fd.'" value="'.$reg->$fd.'" size="25"></td></tr>'."\n";
			}
			
			if($fd == $fk){
				$sql="SELECT $id,$desc FROM $tbl order by $desc";
				$sth = $this->pdo->query($sql) or die('Falha na consulta combo_insert()!');

				$i=1;
				$combo='';
				while($r = $sth->fetch(PDO::FETCH_ASSOC)){
					$fid = $r[$id];
					$fdesc = $r[$desc];
					if($fid == $reg->$fd) {
						$sel = ' selected ';
					}else{
						$sel = '';
					}			
					$combo .= "<option value='".$fid."' $sel>".$fdesc."</option>";
					$i++;
				}					

				$inp .="<tr><td><b>".ucfirst($fd)."</td><td><select name=".$fd." id=\"cat_id\">\n";
				$inp .= $combo;
				$inp .='</select></td></tr>';				
			}
		}
		$inp .= '<input type="hidden" name="id" value="'.$reg->$fd.'" size="25"></td></tr>'."\n";
		$inp .= "\n".'<tr><td></td><td><input class="btn btn-primary" name="send" type="submit" value="Atualizar">&nbsp;&nbsp;&nbsp;'.
            "\n".'<input class="btn btn-warning" name="send" type="button" onclick="location=\'index.php\'" value="Voltar"></td></tr>';
		return $inp;
	}

	private function fieldsUpdate(){

		$x=1;
		$field = '';
		foreach($_POST as $key=>$fields){
			if($key == 'send' || $key == 'id') continue;
			if($x<count($_POST)-2){
				$field .= "$key = :$key, ";
			}else{
				$field .= "$key = :$key";
			}
			$x++;
		}
		return $field;
	}

	public function update(){
		$fields = $this->fieldsUpdate();
		$sql = 'UPDATE '.$this->table.' SET '. $fields .' WHERE id = :id';

		$sth = $this->pdo->prepare($sql);

		$fields = array_keys($_POST);

		foreach ($fields as $field) {
			if($field == 'send') continue;
			$sth->bindValue(":$field", $_POST[$field]);
		}

	   if($sth->execute()){
		    return true;
		}else{
		    return false;
		}
	}

	public function delete(){
		$id = $_GET['id'];
		$sql = 'DELETE FROM '.$this->table.' WHERE id = :id';
		$sth = $this->pdo->prepare($sql);
		$sth->bindParam(':id', $id, PDO::PARAM_INT);   

		if( $sth->execute()){
		    return true;
		}else{
		    return false;
		}
	}

	public function insert(){
  
		$fields = array_keys($_POST);
		$c=0;
		// Pegar o número do último elemento
		foreach($fields as $key=>$val){
			$key;
		}
		// Remover o último elemento 'send'
		unset($fields[$key]);

		$names = implode('`, `', $fields);
		$values = implode(', :', $fields);
	
		$sql = "INSERT INTO ".$this->table." ( `".$names."` ) VALUES ( :".$values." )";	
		
		$sth = $this->pdo->prepare($sql);

		foreach ($fields as $field) {
			if($field == 'send') continue;
			$sth->bindValue(":$field", $_POST[$field]);
		}

		$boolean = $sth->execute();

		if ($boolean){
			return true;
		} else {
			return false;
		}   

		// Observe que não se lança mensagens texto no retorno dos métodos, apenas true ou false. É uma boa prática.
		// Somente os métodos após criar uma instância quando chamamos os mesmos e então lançamos as mensagens de acordo com o retorno

	}

    // Nomes das tabelas do banco atual
	public function tableNames(){
		try {
		  if($this->sgbd=='mysql'){
 		    $sql="SHOW TABLES";
		  }elseif($this->sgbd=='pgsql'){
			$sql="SELECT relname FROM pg_class WHERE relname !~ '^(pg_|sql_)' AND relkind = 'r';";
		  }elseif($this->sgbd=='sqlite'){
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
