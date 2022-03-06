<?php

/* Classe com funções úteis em geral */

Class Util
{


    // Copiar uma pasta com todos os arquivos e subpastas recursivamente
    // Crédito - https://stackoverflow.com/questions/2050859/copy-entire-contents-of-a-directory-to-another-using-php#2050909
    function copyDir($src,$dst) { 
        $dir = opendir($src); 
        @mkdir($dst); 
        while(false !== ( $file = readdir($dir)) ) { 
            if (( $file != '.' ) && ( $file != '..' )) { 
                if ( is_dir($src . '/' . $file) ) { 
                    recurse_copy($src . '/' . $file,$dst . '/' . $file); 
                } 
                else { 
                    copy($src . '/' . $file,$dst . '/' . $file); 
                } 
            } 
        } 
        closedir($dir); 
    } 
    // Caso a pasta de destino não exista será criada
    // copyDir('j381/installation', 'joomla3/installation');

    // Procura por substring em string e salta a iteração do laço antes incrementando o mesmo
    public function skip_string($string, $sub){
	    $pos = strpos($string, $sub);
	    if($pos !== false)
        {
		    $x++;
		    //continue;
	    }
    }

	/* 
	   Criação de combo para campos select em arquivos insert quando em tabela relacionada com outra
	   Como usar no arquivo insert.php:
			<tr><td><b>Usuário</td><td><select name="user_id">		
			<option value="">Selecione</option>
			<?php print $crud->comboInsert('id','username','users');?>
			</select></td></tr>	
	
	   Parâmetros: 
	   $fldId - id da tabela categorias
	   $fldDesc - descricao da tabela categorias
	   $tbl - nome da tabela relacionada, exemplo: categorias
	*/
	public function comboInsert($fldId,$fldDesc=null,$tbl){	
		$sql="SELECT $fldId,$fldDesc FROM $tbl order by $fldDesc";
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

	/*
	   Criar combo para campos do tipo select em arquivo update quando em tabela relacionada com outra
	   Como usar no arquivo update.php:
		 <tr><td><b>Usuário</td><td><select name="user_id">		
			<?php print $crud->comboUpdate('id', 'username', 'users', $reg);?>
		  </select></td></tr>
	
	   Parâmetros: 
	   $fldId - id da tabela categorias
	   $fldDesc - descricao da tabela categorias
	   $tbl - nome da tabela relacionada, exemplo: categorias
	   $fldCli - nome do campo da tabela atual que se relaciona com outra tabela, exemplo: cat_id
	   $rg - variável apenas para inserir a $reg em update.php
	*/
	public function comboUpdate($id,$desc=null,$fk,$tbl,$reg){	
		$sql="SELECT $id,$desc FROM $tbl order by $desc";
		$sth = $this->pdo->query($sql) or die('Falha na consulta combo_insert()!');
		
		$i=0;
		$combo='';
		while($r = $sth->fetch(PDO::FETCH_ASSOC)){
			$data[]=$r;
			
			$fi = $data[$i][$id];
			$fd = $data[$i][$desc];
			if($fi == $reg->$fk) {
				$sel = ' selected ';
			}else{
				$sel = '';
			}			
			$combo .= "<option value='".$fi."' $sel>".$fd."</option>";
			$i++;
		}		
		return $combo;
	}

// Tipo d campo
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

// Tamanho de campo
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

// PK
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

// FK
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

}
