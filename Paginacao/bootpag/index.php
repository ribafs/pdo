<?php
require_once("./classes/crud.php");
$crud = new Crud('clientes',$pdo);

$stmt = $crud->pdo->prepare("SELECT COUNT(*) FROM $crud->table");
$stmt->execute();
$rows = $stmt->fetch();

// get total no. of pages
$total_pages = ceil($rows[0]/$crud->row_limit);

$fld = $crud->fieldName(1);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <title><?=$crud->appName?></title>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="./css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="./css/style.css" rel="stylesheet" type="text/css" />
    <style type="text/css">
    .panel-footer {
        padding: 0;
        background: none;
    }
    </style>
</head>
<body>
<br>
<div class="container">
    <div class="panel panel-default panel-heading">
    <div class="cabecalho"><h3><?=$crud->appName?></h3></div>
    <div class="row">
        <!-- Form de busca-->
        <div class="col-md-9">
            <form action="search.php" method="get" >
            <div class="pull-right topo" style="padding-left: 0;"  >
              <span class="pull-right">  
                <label class="control-label" for="palavra" style="padding-right: 5px;">
                  <input type="text" value="" placeholder="<?=ucfirst($fld)?> ou parte" class="form-control" name="keyword">
                </label>
                </span>
              <button class="btn btn-primary">Busca</button>&nbsp;
            </div>
            </form>
        </div>
	</div>
    <table class="table table-bordered table-hover">
         <thead>
              <tr>	<!-- ********* Chamada do método labels(); ***************-->
					<?php print $crud->labels(); ?>
               </tr>
            </thead>
            <tbody id="pg-results">
            </tbody>
        </table>
        <div class="panel-footer text-center">
            <div class="pagination"></div>
        </div>
</div>

<script src="./js/jquery.min.js" type="text/javascript"></script>			  
<script src="./js/bootstrap.min.js" type="text/javascript"></script>
<script src="./js/jquery.bootpag.min.js" type="text/javascript"></script>

<script type="text/javascript">

$(document).ready(function() {
    $("#pg-results").load("fetch_data.php");
    $('.pagination').bootpag({
        total: <?php echo $total_pages; ?>,
        page: 1,
        maxVisible: <?php echo $crud->links_pages; ?>,
        leaps: true,
        firstLastUse: true,
        first: 'Primeiro',//←
        last: 'Último',//→
        wrapClass: 'pagination',
        activeClass: 'active',
        disabledClass: 'disabled',
        nextClass: 'next',
        prevClass: 'prev',
        lastClass: 'last',
        firstClass: 'first'
    }).on("page", function(e, page_num){
        $("#results").prepend('<div class="loading-indication"><img src="ajax-loader.gif" /> Loading...</div>');
		$("#pg-results").load("fetch_data.php", {"page": page_num});
    }); 	
});
</script>

<?php require_once './footer.php'; ?>
