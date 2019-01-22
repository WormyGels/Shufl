<?php

session_start() ;

if(isset($_SESSION['id_user']) && isset($_SESSION['username'])) {
  $welcomePage = false ;
  $username = $_SESSION['username'] ;
  $userId = $_SESSION['id_user'] ;

  include "vars.php" ;

  // Create connection
  $conn = new mysqli("localhost", $dbuser, $dbpass, $db);

  // Check connection
  if ($conn->connect_error)
  {
    die("Connection failed: " . $conn->connect_error);
    $html =
    '
    Something went wrong logging in.
    ' ;
  }


  if($stmt = $conn->prepare("SELECT volume, region, autoplayoff FROM users WHERE id=?"))
  {

    $stmt->bind_param("s", $userId);
    //Execute the prepared statement
    $stmt->execute();

    //If there was a result
    if($stmt->bind_result($volume, $genre, $autoplay))
    {
      //If we can get the variables from the result
      if($stmt->fetch()) {

      }
    }
  }

}
else {
  $welcomePage = true ;
}

if ($welcomePage == true ) {
  //redirect to main page
  header('Location: index.php');
}

$isAll = "" ;
$isChristian = "" ;
$isClassical = "" ;
$isCountry = "" ;
$isElectronic = "" ;
$isHiphop = "" ;
$isIndie = "" ;
$isJazz = "" ;
$isAsia = "" ;
$isLA = "" ;
$isPop = "" ;
$isReggae = "" ;
$isBlues = "" ;
$isRock = "" ;
$isSoul = "" ;

if ($genre == "04rlf") {
  $isAll = "selected" ;
}
else if ($genre == "02mscn") {
  $isChristian = "selected" ;
}
else if ($genre == "0ggq0m") {
  $isClassical = "selected" ;
}
else if ($genre == "01lyv") {
  $isCountry = "selected" ;
}
else if ($genre == "02lkt") {
  $isElectronic = "selected" ;
}
else if ($genre == "0glt670") {
  $isHiphop = "selected" ;
}
else if ($genre == "05rwpb") {
  $isIndie = "selected" ;
}
else if ($genre == "03_d0") {
  $isJazz = "selected" ;
}
else if ($genre == "028sqc") {
  $isAsia = "selected" ;
}
else if ($genre == "0g293") {
  $isLA = "selected" ;
}
else if ($genre == "064t9") {
  $isPop = "selected" ;
}
else if ($genre == "06cqb") {
  $isReggae = "selected" ;
}
else if ($genre == "06j6l") {
  $isBlues = "selected" ;
}
else if ($genre == "06by7") {
  $isRock = "selected" ;
}
else if ($genre == "0gywn") {
  $isSoul = "selected" ;
}

if ($autoplay == 0) {
  $enabled = "checked" ;
  $disabled = "" ;
}
else {
  $enabled = "" ;
  $disabled = "checked" ;
}


$html =
'
<!DOCTYPE html>
  <html>
    <head>
      <title>Shufl - Settings</title>
      <link href="https://fonts.googleapis.com/css?family=Indie+Flower" rel="stylesheet">
      <link rel="stylesheet" href="style.css">
      <script src="bg.js"></script>
    </head>

    <body class="welcomebody">

    <div id="background">
    </div>

    <div id="navbar">
      <div id="navbuttons">
        <a href="index.php"><img src="img/logo.png" id="logo" height="36" draggable="false" /></a>
        <div id="navlogin">
          <span id="username" ><strong>'.$username.'</strong></span>
          <a id="player" class="logregnav" href="index.php">Player</a>
          <a id="help" class="logregnav" href="help.php">Help</a>
          <span id="settings" class="slogregnav">Settings</span>
          <a id="logout" class="logregnav" href="logout.php">Logout</a>
        </div>
      </div>
    </div>

    <div id="settingsform">
      <p class="wheading"><strong>Settings:</strong></p>
      <form method="post" action="updatesettings.php">
        <div id="genreform">
          <p class="wbody"><strong>Genre:</strong></p>
          <p class="wbody">(Note: YouTube genres may not always be 100% accurate)</p>
          <select class="settingsbox" name="genre">
            <option '.$isAll.' value="04rlf">All Genres</option>
            <option '.$isChristian.' value="02mscn">Christian</option>
            <option '.$isClassical.' value="0ggq0m">Classical</option>
            <option '.$isCountry.' value="01lyv">Country</option>
            <option '.$isElectronic.' value="02lkt">Electronic</option>
            <option '.$isHiphop.' value="0glt670">Hip-hop</option>
            <option '.$isIndie.' value="05rwpb">Indie</option>
            <option '.$isJazz.' value="03_d0">Jazz</option>
            <option '.$isAsia.' value="028sqc">Music of Asia</option>
            <option '.$isLA.' value="0g293">Music of Latin America</option>
            <option '.$isPop.' value="064t9">Pop</option>
            <option '.$isReggae.' value="06cqb">Reggae</option>
            <option '.$isBlues.' value="06j6l">Rhythm and blues</option>
            <option '.$isRock.' value="06by7">Rock</option>
            <option '.$isSoul.' value="0gywn">Soul</option>
          </select>
        </div>
        <div id="autogenform">
          <p class="wbody"><strong>Auto-generate on song end:</strong></p>
          <input class="settingsradio" value="0" type="radio" name="autoplay" '.$enabled.' >Enabled
          <input class="settingsradio" value="1" type="radio" name="autoplay" '.$disabled.' >Disabled
          <br>
          <input class="settingsbtn" value="Update Settings" type="submit" name="submit">
        </div>
      </form>

    </div>


    </body>
' ;

echo $html ;

?>
