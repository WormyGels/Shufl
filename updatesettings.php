<?php

session_start() ;

include "vars.php" ;

if (isset($_POST['volume'])) {

  // Create connection
  $conn = new mysqli("localhost", $dbuser, $dbpass, $db);

  // Check connection
  if($stmt = $conn->prepare("UPDATE users SET volume=? WHERE id=?"))
  {

    $stmt->bind_param("ii", $volume, $id);
    $volume = $_POST['volume'] ;
    $id = $_SESSION['id_user'] ;

    //Execute the prepared statement
    $stmt->execute();

  }

  //header('Location: settings.php');


}

if(isset($_POST['genre']) && isset($_POST['autoplay'])) {

  // Create connection
  $conn = new mysqli("localhost", $dbuser, $dbpass, $db);

  // Check connection
  if($stmt = $conn->prepare("UPDATE users SET region=?, autoplayoff=? WHERE id=?"))
  {

    $stmt->bind_param("sii", $genre, $autoplay, $id);
    $genre = $_POST['genre'] ;
    $autoplay = $_POST['autoplay'] ;
    $id = $_SESSION['id_user'] ;

    //Execute the prepared statement
    $stmt->execute();

  }

  //Redirect back to settings since they aren't supposed to see this page (javascript will handle this eventually)
  header('Location: settings.php');

}
else if(isset($_GET['getsettings'])) {

  // Create connection
  $conn = new mysqli("localhost", $dbuser, $dbpass, $db);

  // Check connection
  if($stmt = $conn->prepare("SELECT volume, region, autoplayoff, cursong FROM users WHERE id=?"))
  {

    $stmt->bind_param("i", $id);
    $id = $_SESSION['id_user'] ;

    //Execute the prepared statement
    $stmt->execute();

    //If there was a result
    if($stmt->bind_result($volume, $region, $autoplay, $cursong))
    {
      //If we can get the variables from the result
      if($stmt->fetch()) {
        echo $volume."~".$region."~".$autoplay."~",$cursong ;

      }
    }

  }

}
else if (isset($_GET['cursongupdate'])) {

  // Create connection
  $conn = new mysqli("localhost", $dbuser, $dbpass, $db);

  // Check connection
  if($stmt = $conn->prepare("UPDATE users SET cursong=? WHERE id=?"))
  {

    $stmt->bind_param("ii", $song, $id);
    $id = $_SESSION['id_user'] ;
    $song = $_GET['cursongupdate'] ;

    //Execute the prepared statement
    if($stmt->execute()) {
      echo "success" ;
    }

  }

}

?>
