<div class="container">
    <div class="row">
        <!-- Form de busca-->
        <div class="col-md-4">
            <form action="search.php" method="get" >
            <div class="pull-left" style="padding-left: 0;"  >
              <span class="pull-right">  
                <label class="control-label" for="keyword" style="padding-right: 0;">
                  <input type="hidden" name="table" value="<?=$table?>">
                  <input type="text" value="" placeholder="Nome ou parte" class="form-control" name="keyword">
                </label>
                </span>
              <button class="btn btn-info">Busca</button>
            </div>
            </form>
        </div>

        <div class="col-md-5">
            <h3 align="center"><?=ucfirst($crud->table)?></h3>
        </div>

<!-- Botão para adicionar novo registro-->
<?php
// Insert
echo "<div class='right-button-margin col-md-3'>";
    echo "<a href='insert.php?table='".$table."' class='btn btn-primary pull-right'>";
        echo "<span class='glyphicon glyphicon-plus'></span> Novo Registro";
    echo "</a>";
echo "</div>";
 
// display the products if there are any
if($total_rows>0){
 
echo "<div class='row'>";
    echo "<table class='table table-hover table-responsive table-bordered'>";

        // Método labels()
        print $crud->labels();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){ 
            extract($row);
        
            // Método rows()
            print $crud->rows($row);
 
            echo "<td>"; 
                echo "<a href='update_product.php?id={$id}' class='btn btn-info left-margin btn-xs'>";
                    echo "<span class='glyphicon glyphicon-edit'></span> Edit";
                echo "</a>";
 
                // delete product button
                echo "<a delete-id='{$id}' class='btn btn-danger delete-object btn-xs'>";
                    echo "<span class='glyphicon glyphicon-remove'></span> Delete";
                echo "</a>";
 
            echo "</td>";
         echo "</tr>"; 
        }
    echo "</table>
</div>";

echo "</div><div class='row'>";
    // paging buttons
    include_once './pagination.php';
echo "</div></div>";
}
 
// tell the user there are no products
else{
    echo "<div class='alert alert-danger'>No products found.</div>";
}
?>
