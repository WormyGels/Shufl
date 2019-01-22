<?php
session_start() ;

$incorrect = false ;

include "vars.php" ;

if(isset($_POST['user']) && isset($_POST['pass'])) {

  // Create connection
  $conn = new mysqli("localhost", $dbuser, $dbpass, $db);

  // Check connection
  if ($conn->connect_error)
  {
    die("Connection failed: " . $conn->connect_error);
  }


  if($stmt = $conn->prepare("SELECT id, username FROM users WHERE username=? and password=?"))
  {

    $stmt->bind_param("ss", $username, $password);
    $username = $_POST['user'];
    $password = $_POST['pass'];
    $password = $saltLeft.$password.$saltRight ;
    $password = hash('ripemd160', $password);

    //Execute the prepared statement
    $stmt->execute();

    //If there was a result
    if($stmt->bind_result($id, $username))
    {
      //If we can get the variables from the result
      if($stmt->fetch()) {
        $_SESSION['id_user'] = $id;
        $_SESSION['username'] = $username ;
        $_SESSION['password'] = $password ;

        //redirect to player
        header('Location: index.php');

      }
      else {
        $incorrect = true ;
      }
    }
    else {
      $incorrect = true ;
    }
  }
}
//If we're logged in already..
else if(isset($_SESSION['id_user'])) {

  //redirect to player
  header('Location: index.php');

}

if ($incorrect == true) {
  $incorrectTag = "<br><p class='wbody'>Username or password is incorrect.</p>" ;
}
else {
  $incorrectTag = "" ;
}

$html =
'
<!DOCTYPE html>
  <html>
    <head>
      <title>Shufl - Login</title>
      <link href="https://fonts.googleapis.com/css?family=Indie+Flower" rel="stylesheet">
      <link rel="stylesheet" href="style.css">
    </head>

    <body class="welcomebody">

    <div id="background">
    <img id="nologin" class="wallpaper" src="img/background2.png" draggable="false" alt="Album Cover">
    </div>

    <div id="navbar">
      <div id="navbuttonscenter">
      <a href="index.php"><img src="img/logo.png" id="logo" height="36" draggable="false" /></a>
      </div>
    </div>

    <div id="loginregisterform">
      <p class="wheading"><strong>Login</strong></p>
      <form method="post">
        <input class="logregbox" placeholder="Username" type="text" name="user">
        <br>
        <input class="logregbox" placeholder="Password (case sensitive)" type="password" name="pass">
        <br>
        <input class="sbtn" value="Login" type="submit" name="submit">
      </form>
      '.$incorrectTag.'
      <p class="wbody">Don\'t have an account? <a id="register" class="misclink" href="register.php">Register.</a></p>
    </div>
    </body>
  </html>

' ;


echo $html ;


?>
