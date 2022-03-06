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
}

