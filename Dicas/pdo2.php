<?php

// config.php
define("DB_HOST", "localhost");  
define("DB_USER", "root");  
define("DB_PASS", "root");  
define("DB_NAME", "testes");  

//include 'database.php';
class Database{  
  private $host = DB_HOST;  
  private $user = DB_USER;  
  private $pass = DB_PASS;  
  private $dbname = DB_NAME;

  private $dbh;  
  private $error;
  private $stmt;

  public function __construct(){  
    // Set DSN  
    $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbname;  

    // Set options  
    $options = array(  
    PDO::ATTR_PERSISTENT => true,  
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION  
    );  

    // Create a new PDO instanace  
    try{  
      $this->dbh = new PDO($dsn, $this->user, $this->pass, $options);  
    }  
    // Catch any errors  
    catch(PDOException $e){  
      $this->error = $e->getMessage();  
    }  
  }  

  public function query($query){  
    $this->stmt = $this->dbh->prepare($query);  
  } 

  public function bind($param, $value, $type = null){
    if (is_null($type)) {  
      switch (true) {  
        case is_int($value):  
          $type = PDO::PARAM_INT;  
          break;  
        case is_bool($value):  
          $type = PDO::PARAM_BOOL;  
          break;  
        case is_null($value):  
          $type = PDO::PARAM_NULL;  
          break;  
        default:  
          $type = PDO::PARAM_STR;  
      }  
    }  
    $this->stmt->bindValue($param, $value, $type);  
  } 

  public function execute(){  
    return $this->stmt->execute();  
  } 

  public function resultset(){  
    $this->execute();  
    return $this->stmt->fetchAll(PDO::FETCH_ASSOC);  
  }

  public function single(){  
    $this->execute();  
    return $this->stmt->fetch(PDO::FETCH_ASSOC);  
  }

  public function rowCount(){  
    return $this->stmt->rowCount();  
  }

  public function lastInsertId(){  
    return $this->dbh->lastInsertId();  
  }

  public function beginTransaction(){  
    return $this->dbh->beginTransaction();  
  }  

  public function endTransaction(){  
    return $this->dbh->commit();  
  }  

  public function cancelTransaction(){  
    return $this->dbh->rollBack();  
  }

  public function debugDumpParams(){  
    return $this->stmt->debugDumpParams();  
  }            
}  
  
// Instantiate database.  
$database = new Database(); 

$database->bind(':fname', 'John');  
$database->bind(':lname', 'Smith');  
$database->bind(':age', '24');  
$database->bind(':gender', 'male');

$database->query('INSERT INTO mytable (fname, lname, age, gender) VALUES (:fname, :lname, :age, :gender)'); 
echo $database->lastInsertId();
$database->execute();

/*
CREATE TABLE mytable (
id int(11) NOT NULL AUTO_INCREMENT,
fname varchar(50) NOT NULL,
lname varchar(50) NOT NULL,
age int(11) NOT NULL,
gender enum('male','female') NOT NULL,
PRIMARY KEY (id)
);

Insert a new record
Firstly you need to instantiate a new database.
$database = new Database();  
$database->query('INSERT INTO mytable (fname, lname, age, gender) VALUES (:fname, :lname, :age, :gender)'); 
*/
