<?php
require_once 'Conn.php';

class Crud extends Conn
{
	private $table;

	public function __construct($table){
        parent::__construct();
		$this->table = $table;
	}

    public function rows(){
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM $this->table");
        $stmt->execute();
        $rows = $stmt->fetch();
        return $rows;
    }

    public function totalPages(){
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM $this->table");
        $stmt->execute();
        $rows = $stmt->fetch();
        $totalPages = ceil($rows[0]/$this->rowLimit);

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
        $sql = "select * from $this->table WHERE name LIKE :keyword order by id";
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

	// Número de campos da tabela atual
	public function numFields(){
		$sql = "SELECT * FROM $this->table LIMIT 1";
        $sth = $this->pdo->prepare($sql);
        $sth->execute();
        $colcount = $sth->columnCount();

		return $colcount;
	}

	// Nome de campo pelo número $x
	public function fieldName($x){
		$sql = "SELECT * FROM $this->table LIMIT 1";
		$sth = $this->pdo->query($sql);
		$meta = $sth->getColumnMeta($x);

		return $meta['name'];
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

            if($x < $this->numFields() -1){
                $fields .= '<td>' . $row["$fld"] . '</td>'."\n";
	        }else{
                $fields .= '<td>' . $row["$fld"] . '</td>';
            }
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

    // Gerar: ':name'=>$name, ':email'=>$email, ':city'=>$city
    private function executeInsert(){
        $values = '';
        $nf = $this->numFields();

        for($x=1;$x<$nf;$x++){
            $fn = $this->fieldName($x);
            $values .= "\$sth->bindValue( ':$fn', \$_REQUEST[$$fn]);\n<br>";
        }

        return $values;
    }

    public function insert($name,$email,$city){
        $sqlInsert = $this->sqlInsert();
        $sql = "INSERT INTO $this->table SET ".$sqlInsert;
        $sth = $this->pdo->prepare($sql);
// Adicionar o array abaixo
        $execute = $sth->execute([':name'=>$name, ':email'=>$email, ':city'=>$city]); // salvando no banco

        if($execute){
            //echo 'Dados inseridos com sucesso';
            return true;
        }else{
            //echo 'Erro ao inserir os dados';
            return false;
        }
    }

     public function update($id,$name,$email,$city){
        $sth = $this->pdo->prepare($sql);
        $execute = $sth->execute(array(':id'=>$id,':name'=>$name, ':email'=>$email,':city'=>$city));
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

//$crud = new Crud();
//print $crud->rowsCount();
//$crud->indsert = ('Elias EF', 'elias@gmail.com', 'Fortaleza');
//$crud->update = (, 13, 'Elias EF', 'eliasef@gmail.com', 'Fortaleza');
//$crud->delete(5);
