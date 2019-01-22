<?php

session_start() ;

echo $_GET["new"];


$_SESSION['test'] = "Hello world" ;

header("Location: index.php");
exit();


?>