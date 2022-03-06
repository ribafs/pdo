<?php
if(rol($_SESSION['id']>0)){
$link->query("DELETE FROM mensajes WHERE id = '$id'");
redir("./");
}
?>
