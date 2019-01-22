<?php

session_start();

// remove all session variables
session_unset();

// destroy the session
session_destroy();

//redirect to mainpage
header('Location: index.php');

?>
