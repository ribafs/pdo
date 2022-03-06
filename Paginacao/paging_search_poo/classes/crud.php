<?php

class Crud
{
    
    public $table;
    public $pdo;
    public $sql;

    public function __construct($pdo,$table){
        $this->pdo = $pdo;
        $this->table = $table;
    }

    // Número de campos da tabela atual
    function numFields(){
//        $sql = "SELECT * FROM $this->table ORDER BY id LIMIT 0,1"; // LIMIT 0,1 para retornar apenas o primeiro resultado que atende (mais rápido)
        $sql = "SELECT * FROM $this->table ORDER BY id LIMIT 1 OFFSET 0"; // LIMIT 0,1 para retornar apenas o primeiro resultado que atende (mais rápido)
        $sth = $this->pdo->query($sql);
        $numFields = $sth->columnCount();
        return $numFields;
    }

    // Nome de campo pelo número $x
    function fieldName($x){
        $sql = "SELECT * FROM $this->table ORDER BY id LIMIT 1 OFFSET 0";
        $sth = $this->pdo->query($sql);
        $meta = $sth->getColumnMeta($x);
        $field = $meta['name'];
        return $field;
    }

    // Retorna o número de registros de uma consulta sql fornecida. Ex: "SELECT id FROM " . $this->table;
    public function numRegs($sql)
    {
        $this->sql = $sql;

        $prep_state = $this->pdo->prepare($this->sql);
        $prep_state->execute();

        $num = $prep_state->rowCount(); //Returns the number of rows affected by the last SQL statement
        return $num;
    }

    function readAll($from_record_num, $records_per_page){

        $query = "SELECT * FROM " . $this->table . " ORDER BY id ASC LIMIT {$records_per_page} OFFSET {$from_record_num}";

/* mysql - LIMIT:  {$from_record_num}, {$records_per_page}"; */
     
        $stmt = $this->pdo->prepare( $query );
        $stmt->execute();
     
        return $stmt;
    }

    // used for paging products
    public function countAll(){
     
        $query = "SELECT id FROM " . $this->table . "";
     
        $stmt = $this->pdo->prepare( $query );
        $stmt->execute();
     
        $num = $stmt->rowCount();
     
        return $num;
    }

    function readOne(){
     
        $query = "SELECT name, price, description, category_id, image
            FROM " . $this->table . "
            WHERE id = ?
            LIMIT 0,1";
     
        $stmt = $this->pdo->prepare( $query );
        $stmt->bindParam(1, $this->id);
        $stmt->execute();
     
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
     
        $this->name = $row['name'];
        $this->price = $row['price'];
        $this->description = $row['description'];
        $this->category_id = $row['category_id'];
        $this->image = $row['image'];
    }

    // read products by search term
    public function search($search_term, $from_record_num, $records_per_page){
     
        // select query
        $field = $this->fieldName(1);
        $query = "select * from ".$this->table." WHERE $field LIKE :$search_term order by id LIMIT ?, ?";
     
        // prepare query statement
        $stmt = $this->pdo->prepare( $query );
     
        // bind variable values
        $search_term = "%{$search_term}%";
        $stmt->bindParam(1, $search_term);
        $stmt->bindParam(2, $search_term);
        $stmt->bindParam(3, $from_record_num, PDO::PARAM_INT);
        $stmt->bindParam(4, $records_per_page, PDO::PARAM_INT);
     
        // execute query
        $stmt->execute();
     
        // return values from database
        return $stmt;
    }

    // Gerar a string "$nome = :$nome, $email = :$email, ..."; para o método update()
    function updateSet(){
	    $set='';
        $numFields = $this->numFields();
            
        for($x=1;$x<$numFields;$x++){
            $field = $this->fieldName($x);
		    // A linha abaixo gerará a linha: $nome = 'Nome do cliente';
	        $$field = $_POST[$field];

		    if($x<$numFields-1){
			    if($x==0) continue;
			    $set .= "$field = :$field,";
		    }else{
			    if($x==0) continue;
			    $set .= "$field = :$field";
		    }
	    }
        return $set;
    }

    // Retorna algo como: ":nome, :email, :data_nasc, cpf" para métodos como insert()
    function insertStr(){
        $numFields = $this->numFields();
	    $fields = '';
	    $values = '';

        for($x=1;$x<$numFields;$x++){
            $field = $this->fieldName($x);

		    // Este if gera o seguinte código para a variável $fields = "nome, email, data_nasc, cpf" (exemplo para clientes)
		    // E também para a variável $values = ":nome, :email, :data_nasc, cpf"
		    if($x<$numFields-1){
                $fields .= "$field,";
                $values .= ":$field, ";
		    }else{
                $fields .= "$field";
                $values .= ":$field";
		    }
	    }
        $insertStr = "($fields) VALUES ($values)";
        return $insertStr;
    }

	// Labels para o grid.php
	public function labels(){
		$labels='<tr>';
		for($x=0;$x<$this->numFields();$x++){
			$labels .= '<th>'.ucFirst($this->fieldName($x))."</th>\n";
		}		
		$labels .= '<th colspan="2">Ação</th></tr>';
		return $labels;
	}

	// Retornar registros para o grid.php 
    // Parâmetros: $row - virá do grid.php, $desc - descricão da tabela relacionada e $fk da tabela atual
	public function rows($row,$desc=null,$fk=null){
		$rs='<tr>';
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
/*
    // Retorna string para método update(): 
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
*/
    // Renderiza uma combo onde tem um text para insert.php quando existe campo relacionado
	public function comboInsert($fldId,$fldDesc=null,$tbl){	
		$sql="SELECT $fldId,$fldDesc FROM $tbl order by $fldDesc LIMIT 0,1";
		$sth = $this->pdo->query($sql) or die('Falha na consulta combo_insert()!');
		
		$i=0;
		$combo='';
		while($r = $sth->fetch(PDO::FETCH_ASSOC)){
			$data[]=$r;
			$fi = $data[$i][$fldId];
			$fd = $data[$i][$fldDesc];
			$combo .= '<option value="'.$fi.'">'.$fd.'</option>'."\n";
			$i++;
		}		
		return $combo;
	}

    // Renderizar uma combo no arquivo update.php quando existe campo relacionado e traz o valor do campo default com selected
    public function comboUpdate1($idRel, $displayRel, $tableRel,$fk,$reg){
            $upd = '';

            $rows = $this->pdo->query("SELECT $idRel,$displayRel FROM $tableRel order by $idRel LIMIT 0,1");
            $upd .= "<tr><td><b>".ucfirst($fk)."</td><td><select name=\"$fk\">\n";

            foreach($rows as $row)
            {
                if($reg->$fk == $row[$idRel]){
                    $upd .= '<option value="'.$row[$idRel].'" selected>'. $row[$displayRel] . '</option>'."\n";
                }else{
                    $upd .= '<option value="'.$row[$idRel].'">'. $row[$displayRel] . '</option>'."\n";
                }
            }
            $upd .= '</td></tr>';
            return $upd;
    }

    // Renderizar uma combo no arquivo update.php quando existe campo relacionado e traz o valor do campo default com selected para o campo 2
    public function comboUpdate2($idRel, $displayRel, $tableRel,$fk,$reg){
            $upd = '';

            $rows = $this->pdo->query("SELECT $idRel,$displayRel FROM $tableRel order by $idRel LIMIT 0,1");
            $upd .= "<tr><td><b>".ucfirst($fk)."</td><td><select name=\"$fk\">\n";

            foreach($rows as $row)
            {
                if($reg->$fk == $row[$idRel]){
                    $upd .= '<option value="'.$row[$idRel].'" selected>'. $row[$displayRel] . '</option>'."\n";
                }else{
                    $upd .= '<option value="'.$row[$idRel].'">'. $row[$displayRel] . '</option>'."\n";
                }
            }
            $upd .= '</td></tr>';
            return $upd;
    }

    // Retorna o tipo do campo atual
	public function fieldType($table, $fldName){	
		try {
			if($this->sgbd =='mysql'){
				$sql="SHOW COLUMNS as col FROM $table WHERE Field = '$fldName' order by $fldName LIMIT 0,1";
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

    // Retorna o tamanho de campo atual
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

    // Retorna o nome do campo que é a cheve primária da tabela atual, mysql e pgsql
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

    // Retorna somente o nome dos campos que são chave estrangeira na tabela atual. Não suporta chaves compostas çor mais de um campo
	public function foreignKey(){
        $con = new Connection();
        $sgbd = $con->sgbd;

		try {
			if($sgbd=='mysql'){
			$sql = "select column_name, concat(referenced_table_name, '.', referenced_column_name) as 'references' from information_schema.key_column_usage where referenced_table_name is not null and table_name=".$this->table.";";	
			}elseif($sgbd='pgsql'){
				$sql="SELECT
    kcu.column_name
FROM 
    information_schema.table_constraints AS tc 
    JOIN information_schema.key_column_usage AS kcu
      ON tc.constraint_name = kcu.constraint_name
      AND tc.table_schema = kcu.table_schema
    JOIN information_schema.constraint_column_usage AS ccu
      ON ccu.constraint_name = tc.constraint_name
      AND ccu.table_schema = tc.table_schema
WHERE constraint_type = 'FOREIGN KEY' AND tc.table_name='$this->table';";
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

			if($regs){			
				return $regs;// Retorna somente o primeiro campo FK
			}else{
				//print '<b>This table dont have a foreign key</b>';
                //exit;
			}
		}catch (PDOException $p){
			print $p->getMessage();
			exit;
		}
	}
}
