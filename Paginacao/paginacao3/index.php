<?php
include_once("Classes/Crud.php");
$crud = new Crud('customers');
$totalPages = $crud->totalPages();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Pagination using PHP Bootpag</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" integrity="sha384-HSMxcRTRxnN+Bdg0JdbxYKrThecOKuH5zCYotlSAcp1+c8xmyTe9GYg1l9a69psu" crossorigin="anonymous">

    <style type="text/css">
    .panel-footer {
        padding: 0;
        background: none;
    }
    </style>
</head>
<body>
<br/>
<div class="container">
    <div class="panel panel-default">
        <div class="panel-heading text-center"><h3>PHP Pagination Bootpag</h3></div>
            <!-- Adicionar registro -->
            <div class="text-left col-md-2 top">
                <a href="add.php" class="btn btn-primary pull-left">
                    <span class="glyphicon glyphicon-plus"></span> Adicionar
                </a>
            </div>

        <div class="row">
            <!-- Form de busca-->
            <div class="col-md-12">
                <form action="search.php" method="get" >
                <div class="pull-right topo" style="padding-left: 0;">
                  <span class="pull-right">  
                    <label class="control-label" for="palavra" style="padding-right: 5px;">
                      <input type="text" value="" placeholder="name or parte" class="form-control" name="keyword">
                    </label>
                    </span>
                  <button class="btn btn-primary">Busca</button>&nbsp;
               </div>
               </form>
            </div>
	    </div>


        <table class="table table-bordered table-hover">
            <thead>
                <tr><!-- Criar função para imprimir os nomes dos campos da tabela atual -->
                    <?php
                        $fields = $crud->displayFields();
                        foreach($fields as $field){
                            print '<th>'.$field.'</th>';
                        }
                    ?>
                </tr>
            </thead>
            <tbody id="pg-results">
            </tbody>
        </table>
        <div class="panel-footer text-center">
            <div class="pagination"></div>
        </div>
    </div>
</div>

<!-- Latest compiled and minified JavaScript -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js" integrity="sha384-aJ21OjlMXNL5UyIl/XNwTMqvzeRMZH2w8c5cRVpzpU8Y5bApTppSuUkhZXN0VxHd" crossorigin="anonymous"></script>    
<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootpag/1.0.7/jquery.bootpag.min.js" type="text/javascript"></script>

<script type="text/javascript">
$(document).ready(function() {
    $("#pg-results").load("fetch_data.php");
    $(".pagination").bootpag({
        total: <?php echo $totalPages; ?>,
        page: 1,
        maxVisible: 5
    }).on("page", function(e, page_num){
        e.preventDefault();
        /*$("#results").prepend('<div class="loading-indication"><img src="ajax-loader.gif" /> Loading...</div>');*/
        $("#pg-results").load("fetch_data.php", {"page": page_num});
    });
});
</script>

</body>
</html>
