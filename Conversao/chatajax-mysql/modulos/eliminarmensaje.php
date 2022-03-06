<?php
if(rol($_SESSION['id']>0)){
$mysqli->query("DELETE FROM mensajes WHERE id = '$id'");
redir("./");
}
?>