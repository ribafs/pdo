<?php

require_once 'Conn.php';

/* Classe que trabalha com um crud, lidando com uma tabela por vez, que é fornecida a cada instância, desde a conexão com o banco */

class Crud extends Conn
{

	public $table;

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

    public function paging($start, $links){
        $ret = $this->pdo->prepare("SELECT * FROM $this->table ORDER BY id LIMIT $start, $links");
    }

    public function insert(){

        if(isset($_POST['enviar'])){
        $name = $_POST['name'];
        $email = $_POST['email'];
        $city = $_POST['city'];

           try{    
               $stmte = $this->pdo->prepare("INSERT INTO customers(name,email,city) VALUES (?, ?, ?)");
               $stmte->bindParam(1, $name , PDO::PARAM_STR);
               $stmte->bindParam(2, $email , PDO::PARAM_STR);
               $stmte->bindParam(3, $city , PDO::PARAM_STR);
               $executa = $stmte->execute();
         
               if($executa){
                    return true;
               }
               else{
                    return false;
               }
           }
           catch(PDOException $e){
              echo $e->getMessage();
           }
        }
    }

    public function delete($id){

        if(isset($_POST['enviar'])){
            $id = $_POST['id'];
            $sql = "DELETE FROM customers WHERE id = :id";
            $sth = $this->pdo->prepare($sql);
            $sth->bindParam(':id', $id, PDO::PARAM_INT);   

            if( $sth->execute()){
                return true;
            }else{
                return false;
            }
        }

    }

    public function update(){
        if(isset($_POST['enviar'])){
            $id = $_POST['id'];
            $name = $_POST['name'];
            $email = $_POST['email'];
            $city = $_POST['city'];

            $sql = "UPDATE customers SET name = :name, email = :email, city = :city WHERE id = :id";

            $sth = $this->pdo->prepare($sql);
            $sth->bindParam(':id', $_POST['id'], PDO::PARAM_INT);   
            $sth->bindParam(':name', $_POST['name'], PDO::PARAM_STR);   
            $sth->bindParam(':email', $_POST['email'], PDO::PARAM_STR);   
            $sth->bindParam(':city', $_POST['city'], PDO::PARAM_STR);   

           if($sth->execute()){
                return true;
            }else{
                return false;
            }
        }

    }
}
