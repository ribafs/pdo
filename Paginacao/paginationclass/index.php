<!-- TEST PAGE USING BOOTSTRAP -->
<!DOCTYPE html>
<html>
<head>
	<title> Pagination Teste </title>
	<link rel='stylesheet' href='css/bootstrap.min.css'>
</head>
<body>
	<section class='container-fluid'>
		<section style='text-align:center;' class='jumbotron'>
			<h1> Pagination Teste </h1>
			<?php
				error_reporting(E_ALL ^ E_NOTICE);
				ini_set("display_errors",1);
				//!important ---- require the config file and the Pagination class
				require_once "config.php";
				require_once "Pagination.class.php";
				//initialize PDO connection and save object to $d
				try{
					$db=new PDO(DSN,USER,PASS);
				}catch(PDOException $e){
					echo "couldn't connect cos of $e";
				}
				//pass the pdo object,the number of registers per page, and the table name to the class. 
				$paginate = new Pagination($db,8,'clientes');
				//call the paginate methods
				$test =$paginate->paginate('id','nome','email','Paginação Teste');
				//print out the returned result
				echo $test;
			?>

		</section>
	</section>
</body>
</html>

	

