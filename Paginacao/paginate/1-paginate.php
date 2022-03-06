<?php
// (A) DATABASE
// (A1) DATABASE SETTINGS - CHANGE TO YOUR OWN!
define('DB_HOST', 'localhost');
define('DB_NAME', 'testes');
define('DB_CHARSET', 'utf8');
define('DB_USER', 'root');
define('DB_PASSWORD', '');
define('PER_PAGE', '10'); // ENTRIES PER PAGE

// (A2) CONNECT TO DATABASE
try {
  $pdo = new PDO(
    "mysql:host=". DB_HOST .";dbname=". DB_NAME .";charset=". DB_CHARSET,
    DB_USER, DB_PASSWORD, [
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]
  );
} catch (Exception $ex) { exit($ex->getMessage()); }

// (B) TOTAL NUMBER OF PAGES
$stmt = $pdo->prepare("SELECT CEILING(COUNT(*) / ".PER_PAGE.") `pages` FROM `users`");
$stmt->execute(); 
$pageTotal = $stmt->fetchColumn();

// (C) GET ENTRIES FOR CURRENT PAGE
// (C1) LIMIT (X, Y) FOR SQL QUERY
$pageNow = isset($_GET['page']) ? $_GET['page'] : 1 ;
$limX = ($pageNow - 1) * PER_PAGE;
$limY = PER_PAGE;

// (C2) SQL FETCH
$stmt = $pdo->prepare("SELECT * FROM `users` ORDER BY `id` LIMIT $limX, $limY");
$stmt->execute();
$users = $stmt->fetchAll();

// (D) OUTPUT HTML ?>
<!DOCTYPE html>
<html>
  <head>
    <title>
      Pagination Example
    </title>
    <link rel="stylesheet" href="2-paginate.css">
  </head>
  <body>
    <h1>USERS</h1>
    <!-- (D1) USER LIST -->
    <ul><?php
    foreach ($users as $u) {
      printf("<li>%u %s</li>", $u['id'], $u['name']);
    }
    ?></ul>

    <!-- (D2) PAGINATION -->
    <div class="pagination" id="pagination"><?php
    for ($i=1; $i<=$pageTotal; $i++) {
      printf("<a %shref='1-paginate.php?page=%u'>%u</a>", 
        $i==$pageNow ? "class='current' " : "", $i, $i
      );
    }
    ?></div>
  </body>
</html>
