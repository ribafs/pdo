PDO tem 3 modos de manipulação de erros:

    PDO::ERRMODE_SILENT age como o mysql_* onde você precisa checar cada resultado e então verificar em $db->errorInfo(); para ver os detalhes dos erros.
    PDO::ERRMODE_WARNING dispara avisos PHP
    PDO::ERRMODE_EXCEPTION dispara PDOException. Este é o modo que devemos usar. Este age muito como die(mysql_error()); quando não é capturado, mas diferente de die(), a PDOException pode ser capturada e manipulada normalmente, se você optar por fazê-lo

<?php
try {
    //connect as appropriate as above
    $db->query('olá'); //invalid query!
} catch(PDOException $ex) {
    echo "Ocorreu um erro!";
    print($ex->getMessage());
}

$dbh->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

Em desenvolvimento:

ini_set('display_errors', 1);

Em produção
ini_set('display_errors', 0);
ini_set('log_errors', 1);

try {
    $pdo->prepare("INSERT INTO users VALUES (NULL,?,?,?,?)")->execute($data);
} catch (PDOException $e) {
    $existingkey = "Integrity constraint violation: 1062 Duplicate entry";
    if (strpos($e->getMessage(), $existingkey) !== FALSE) {
        // Take some action if there is a key constraint violation, i.e. duplicate name
    } else {
        throw $e;
    }
}


Tratamento de erro para quando o registro já existe:

if($e->getCode == 23000{
	print 'Registro já existe';
}

	function executeDML($sql, $arrayParametros, $conn)
	{
	   //echo "<pre>SQL= " .$sql. "</pre>";
	   //echo "<br>" . var_dump($this->conn);
	    try{
		$stmt = $conn->prepare($sql);
		
		for($i=0; $i<sizeof($arrayParametros); $i++)
		{
		    $stmt->bindParam($i+1, $arrayParametros[$i]);
		}
		$stmt->execute();
		
		}catch(PDOException $e) {
		   echo $e->getMessage();
		}
	}

    try {
        $insertStr = $crud->insertStr();

        $sql = "INSERT INTO $table $insertStr";
        $sth = $crud->pdo->prepare($sql);    

        for($x=1;$x<$numFields;$x++){
            $field = $crud->fieldName($x);
		    $sth->bindParam(":$field", $_POST["$field"], PDO::PARAM_INT);
	    }
        $sth->execute();

         print "<script>location='./index.php?table=$table';</script>";
    } catch (Exception $e) {
        if($e->getCode() == '22007'){
            print '<h3>Data vazia ou inválida</h3>';
        }
		echo '<br><br><b>Código</b>: '.$e->getCode().'<hr><br>';
		echo '<b>Mensagem</b>: '. $e->getMessage().'<br>';// Usar estas linhas no catch apenas em ambiente de testes/desenvolvimento
		echo '<b>Arquivo</b>: '.$e->getFile().'<br>';
		echo '<b>Linha</b>: '.$e->getLine().'<br>';
		exit();
    }

