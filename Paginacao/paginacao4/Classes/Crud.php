<?php
require_once 'Conn.php';

class Crud extends Conn
{
	private $table;

	public function __construct($table){
        parent::__construct();
		$this->table = $table;
	}

    // Os dois métodos seguintes são básicos, para a criação de outros 
	// Número de campos da tabela atual
	private function numFields(){
		$sql = "SELECT * FROM $this->table LIMIT 1";
        $sth = $this->pdo->prepare($sql);
        $sth->execute();
        $colcount = $sth->columnCount();

		return $colcount;
	}

	// Nome de campo pelo número $x
	private function fieldName($x){
		$sql = "SELECT * FROM $this->table LIMIT 1";
		$sth = $this->pdo->query($sql);
		$meta = $sth->getColumnMeta($x);

		return $meta['name'];
	}

    // Método apra o arquivo 
    public function rows(){
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM $this->table");
        $stmt->execute();
        $rows = $stmt->fetch();
        return $rows;
    }

    // Método para o arquivo add.php
    public function formAdd(){
        $nf = $this->numFields();
        $ret = '';
        for($x=0;$x < $nf; $x++){
            $fn = $this->fieldName($x);
            $ret .="<tr><td>".ucfirst($fn)."</td><td><input type=\"text\" name=\"$fn\"></td></tr>";
        }
        return $ret;
    }

    public function paginacao($start){
        if($this->sgbd == 'mysql'){
            $results = $this->pdo->prepare("SELECT * FROM $this->table ORDER BY id DESC LIMIT $start, $this->regsPerPage");
        }else if($this->sgbd == 'pgsql'){
            $results = $this->pdo->prepare("SELECT * FROM $this->table ORDER BY id DESC LIMIT $this->regsPerPage OFFSET $start");
        }
        return $results;
    }

    public function updateReg($id){
        $sth = $this->pdo->prepare("SELECT id, nome,email,cpf from $this->table WHERE id = :id");
        $sth->bindValue(':id', $id, PDO::PARAM_STR);
        $sth->execute();
        $reg = $sth->fetch(PDO::FETCH_OBJ);

        return $reg;
    }

    public function totalPages(){
        $stmt = $this->pdo->prepare("SELECT * FROM $this->table");
        $stmt->execute();
        $rows = $stmt->rowCount();
        $totalPages = ceil($rows/$this->linksPerPage);

        return $totalPages;
    }

    public function results($start){
        $sql = "SELECT * FROM $this->table ORDER BY id LIMIT $start, $this->rowLimit";
        $smth = $this->pdo->query($sql);
        $smth->execute();
        $rows = $smth->fetchall(PDO::FETCH_OBJ);

        return $rows;
    }

    public function search($keyword){
        $sql = "select * from $this->table WHERE nome LIKE :keyword order by id";
        $sth = $this->pdo->prepare($sql);
        $sth->bindValue(":keyword", $keyword."%");
        $sth->execute();
        $rows =$sth->fetchAll(PDO::FETCH_ASSOC);

        return $rows;
    }

    // Número de registros da tabela atual
    public function rowsCount($where = 'id > 10'){
        // $this->pdo aqui nem precisa ser recebido via construct, pois o parent::__construct() traz da Conn
        if($where){
            $sth = $this->pdo->prepare("select * from $this->table where $where");
        }else{
            $sth = $this->pdo->prepare("select * from $this->table");
        }
        $sth->execute();
        $result = $sth->rowCount();
        return $result;
    }

    public function updateFields($reg){
        $nf = $this->numFields();
        $ret = '';
        for($x=1;$x < $nf; $x++){
            $fn = $this->fieldName($x);
            $fld = $reg->$fn;
            $ret .= "<tr><td><b>".ucfirst($fn)."</td><td><input type=\"text\" name=\"$fn\" value=\"$fld\"></td></tr>\n";
        }

        return $ret;
    }

    public function deleteReg($id){
        $sth = $this->pdo->prepare("SELECT * from $this->table WHERE id = :id");
        $sth->bindValue(':id', $id, PDO::PARAM_STR);
        $sth->execute();
        $reg = $sth->fetch(PDO::FETCH_OBJ);

        return $reg;
    }

    public function displayFields(){
        $nf = $this->numFields();
        $ret = array();
        for($x=0;$x < $nf; $x++){
            array_push($ret, ucfirst($this->fieldName($x)));
        }
        return $ret;
    }

    // Return this: /*  "<td>" . $row['id'] . "</td>" . */
    public function rowFields($row){
	    $fields = '';
        $fld = '';

        for($x=0;$x < $this->numFields();$x++){
            $fld = $this->fieldName($x);

            $fields .= '<td>' . $row["$fld"] . '</td>'."\n";
        }
        return $fields;
    }

    // Gerar: name=:name, email=:email, city=:city
    private function sqlInsert(){
        $fields = '';
        $nf = $this->numFields();

        for($x=1;$x<$nf;$x++){
            $fn = $this->fieldName($x);
            if($x < $nf-1){
                $fields .= "$fn=:$fn, ";
            }else{
                $fields .= "$fn=:$fn";
            }
        }

        return $fields;
    }

    public function insert($nome,$email,$cpf){
        $sqlInsert = $this->sqlInsert();
        $sql = "INSERT INTO $this->table SET ".$sqlInsert;
        $sth = $this->pdo->prepare($sql);

        // A linha abaixo precisa ser ajustada manualmente para cada tabela
        $execute = $sth->execute([':name'=>$nome, ':email'=>$email, ':cpf'=>$cpf]); // salvando no banco

        if($execute){
            //echo 'Dados inseridos com sucesso';
            return true;
        }else{
            //echo 'Erro ao inserir os dados';
            return false;
        }
    }

    // Gerar: nome = :nome, email = :email, cpf = :cpf WHERE id = :id
    private function sqlUpdate(){
        $fields = '';
        $nf = $this->numFields();

        for($x=1;$x<$nf;$x++){
            $fn = $this->fieldName($x);
            if($x < $nf-1){
                $fields .= "$fn = :$fn, ";
            }else{
                $fields .= "$fn = :$fn";
            }
        }
        $fields .= " WHERE id = :id";

        return $fields;
    }

    public function update($id,$nome,$email,$cpf){
        $sqlUpdate = $this->sqlUpdate();

        $sql = "UPDATE $this->table SET ".$sqlUpdate;
        $sth = $this->pdo->prepare($sql);

        // A linha abaixo precisa ser ajustada manualmente para cada tabela
        $execute = $sth->execute(array(':id'=>$id,':nome'=>$nome, ':email'=>$email,':cpf'=>$cpf));

        if($execute){
            //echo 'Dados atualizados com sucesso';
            return true;
        }else{
            //echo 'Erro ao atualizar os dados';
            return false;
        }
     }

    public function delete($id){
        $sql = "DELETE FROM $this->table WHERE id = :id";
        $sth = $this->pdo->prepare($sql);
        $execute = $sth->execute(array(':id'=>$id));

        if( $execute ){
            //Registro excluído com sucesso!
            return true;
        }else{
            //Erro ao exclur o registro!
            return false;
        }
    }
}

