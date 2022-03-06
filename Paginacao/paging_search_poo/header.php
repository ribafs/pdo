<?php
require_once('./classes/connection.php');
$con = new Connection();
?>
<!DOCTYPE HTML>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link rel="shortcut icon" href="./assets/images/favicon.ico" type="image/x-icon" sizes="16x16 24x24 32x32 64x64"/>

        <title><?=$con->appName?></title>
 
        <link rel="stylesheet" type="text/css" href="./assets/css/bootstrap.min.css" />
        <link rel="stylesheet" type="text/css" href="./assets/css/style.css" />
        <script
			  src="https://code.jquery.com/jquery-2.2.4.min.js"
			  integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44="
			  crossorigin="anonymous"></script>
    </head>
    <body>
        <br>
        <div class="container header">
            <div align="center">
                <h1><a href="./index.php" title="Voltar ao Menu Principal"><?=$con->appName?></a></h1>
            </div>
        </div>
        <br>
