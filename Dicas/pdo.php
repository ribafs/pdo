<?php
// Clase de conexão com o banco de dados usando PDO
//'connection.php';
Class Connection {

    private $server = "mysql:host=localhost;dbname=crud";
    private $user = "root";
    private $pass = "root";
    private $options = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                              PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,);
    protected $con;

    public function openConnection()
    {
     try
     {
      $this->con = new PDO($this->server, $this->user,$this->pass,$this->options);
      return $this->con;
     
      }catch (PDOException $e){
       echo "<br>There is some problem in connection: " . $e->getMessage(); 
      }
    }

     public function closeConnection() {
       $this->con = null;
     }
}

// Criar uma tabela com o PDO
//include_once 'connection.php';

    try
     {
     $database = new Connection();
     $db = $database->openConnection();
     // sql to create table
     $sql = "CREATE TABLE IF NOT EXISTS `estudantes` ( `ID` INT NOT NULL AUTO_INCREMENT , `nome`VARCHAR(40) NOT NULL , `sobrenome` VARCHAR(40) NOT NULL , `email` VARCHAR(40)NOT NULL , PRIMARY KEY (`ID`)) ";
     // use exec() because no results are returned
     $db->exec($sql);
     echo "Table estudantes created successfully<br><br>";
     
     $database->closeConnection();
     }
     catch (PDOException $e)
     {
     echo "<br>There is some problem in connection: " . $e->getMessage();
     }

// Inserir registros no banco
//include_once 'connection.php';

try
{

    $database = new Connection();
    $db = $database->openConnection();
    // inserting data into create table using prepare statement to prevent from sql injections
    $stm = $db->prepare("INSERT INTO estudantes (id,nome,sobrenome,email) VALUES ( :id, :nome, :sobrenome, :email)") ;
    // inserting a record
    $stm->execute(array(':id' => 0 , ':nome' => 'Saquib' , ':sobrenome' => 'Rizwan' , ':email' => 'saquib.rizwan@cloudways.com'));
    echo "New record created successfully";
}

catch (PDOException $e)
{
    echo "<br>There is some problem in connection1: " . $e->getMessage();
}

// Selecionar dados com PDO
//include_once 'connection.php';

try
{
    $database = new Connection();
    $db = $database->openConnection();
    $sql = "SELECT * FROM estudantes " ;

    foreach ($db->query($sql) as $row) {
      echo " ID: ".$row['ID'] . "<br>";
      echo " Nome: ".$row['nome'] . "<br>";
      echo " Sobrenome: ".$row['sobrenome'] . "<br>";
      echo " Email: ".$row['email'] . "<br><br>";
    }
}

catch (PDOException $e)
{
    echo "<br>There is some problem in connection: " . $e->getMessage();
}
// Atualizar dados com PDO
//include_once 'connection.php';

try
{
     $database = new Connection();
     $db = $database->openConnection();
     $sql = "UPDATE `estudantes` SET `nome`= 'yourname' , `sobrenome` = 'your lastname' , `email` = 'your email' WHERE `id` = 1" ;
     $affectedrows  = $db->exec($sql);

   if(isset($affectedrows))
    {
       echo "<br>Record has been successfully updated<br>";
    }          
}
catch (PDOException $e)
{
    echo "<br>There is some problem in connection: " . $e->getMessage();
}

// Excluir registros com PDO

//include_once 'connection.php';

try
{
     $database = new Connection();
     $db = $database->openConnection();
     $sql = "DELETE FROM estudantes WHERE `id` = 2" ;
     $affectedrows  = $db->exec($sql);
   if(isset($affectedrows))
    {
       echo "<br>Record has been successfully deleted";
    }          
}
catch (PDOException $e)
{
   echo "<br>There is some problem in connection: " . $e->getMessage();
}


