<br>
        <div class="container footer">
            <i style="font-size:10px">"Criado" por <a href="http://ribafs.org" target="_blank">Ribamar FS</a></i>
        </div>

        <!-- bootbox code o CDN bootstrap já pega o jquery-->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>  
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/4.4.0/bootbox.min.js"></script>

        <script>
            $(document).on("click", ".delete", function(e) {
                var link = $(this).attr("href"); // "get" the intended link in a var
                e.preventDefault();    
                bootbox.confirm({    
                title: "Exclusão?",
                message: "Tem certeza de que deseja excluir este registro?",
                buttons: {
                    cancel: {
                        label: '<i class="fa fa-times"></i> Cancelar'
                    },
                    confirm: {
                        label: '<i class="fa fa-check"></i> Excluir'
                    }
                },
                callback: function (result) {
                    if(result==true){
                        document.location.href = link;  // if result, "set" the document location 
                        $('#delete').show();      
                    }
                }    
                });
            });
        </script>

    </body>
</html>
