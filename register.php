<?php
session_start() ;

require "vars.php" ;
require "rytfunctions.php" ;

$exists = false ;
$unmatchedPassword = "" ;

if (isset($_POST['pass']) && isset($_POST['pass2']) && ($_POST['pass'] != $_POST['pass2'])) {
	$unmatchedPassword = '<br><p class="wbody"> Your passwords do not match.</p>' ;

}
else if(isset($_POST['user']) && isset($_POST['pass']) && isset($_POST['pass2']) && ($_POST['user'] != "") && ($_POST['pass'] != ""))
{

	// Create connection
	$conn = new mysqli("localhost", $dbuser, $dbpass, $db);

	// Check connection
	if ($conn->connect_error)
	{
		die("Connection failed: " . $conn->connect_error);
    $html =
    '
    Something went wrong.
    ' ;
	}
  else {

    $stmt = $conn->prepare("SELECT id from users WHERE username=?") ;
    $stmt->bind_param("s", $username) ;
    $username = $_POST['user'];

    if($stmt->execute()) {
      if($stmt->fetch()) {
        $exists = true ;
      }
      else {
        $exists = false ;
      }
    }

		$volume = 50 ;

    if($exists == false) {

    	$stmt = $conn->prepare("INSERT INTO users (username, password, region, volume) VALUES (?, ?, ?, ?)");
    	$stmt->bind_param("sssi", $username, $password, $region, $volume);

    	// set parameters and execute
    	$username = $_POST['user'];
    	$password = $_POST['pass'];
    	$password = $saltLeft.$password.$saltRight ;
    	$password = hash('ripemd160', $password);
			$region = "04rlf" ;

    	if($stmt->execute()) {

        $stmt2 = $conn->prepare("SELECT id FROM users WHERE username=?") ;
        $stmt2->bind_param("s", $username) ;
        $stmt2->execute() ;
        $stmt2->bind_result($id) ;
        $stmt2->fetch() ;
        $stmt2->fetch() ;

				$cleanUsername = cleanUser($username) ;

        //This creates the table
        if($conn->query("CREATE TABLE ".$id.$cleanUsername." (
        id INT NOT NULL AUTO_INCREMENT,
        vidstring TEXT NOT NULL,
        date TEXT NOT NULL,
        title TEXT NOT NULL,
        channel TEXT NOT NULL,
        seed TEXT NOT NULL,
        fav BOOLEAN NOT NULL,
        primary key (id)
        )")) {
        }

				//Just go ahead and log the little guy in
				$_SESSION['id_user'] = $id;
				$_SESSION['username'] = $username ;
				$_SESSION['password'] = $password ;

				//header('Location: index.php');

				$html =
				'
				<!DOCTYPE html>
					<html>
						<head>
							<title>Shufl - First Time</title>
							<link href="https://fonts.googleapis.com/css?family=Indie+Flower" rel="stylesheet">
							<link rel="stylesheet" href="style.css">
						</head>
					</html>

					<body class="welcomebody">

					<div id="background">
					<img id="nologin" class="wallpaper" src="img/background2.png" draggable="false" alt="Album Cover">
					</div>

						<div id="navbar">
							<div id="navbuttons">
							<a href="index.php"><img src="img/logo.png" id="logo" height="36" draggable="false" /></a>
								<div id="navlogin">
									<span id="username" ><strong>'.$username.'</strong></span>
									<a id="help" class="logregnav" href="help.php">Help</a>
									<a id="settings" class="logregnav" href="settings.php">Settings</a>
									<a id="logout" class="logregnav" href="logout.php">Logout</a>
								</div>
							</div>
						</div>


						<div id="firsttime">
							<p class="wheading">Welcome</p>
							<p class="wbody">It looks like this is your first time using Shufl. Would you like a brief overview on how to use the service?</p>
							<br>
							<a class="logreg" id="yes" href="help.php"><strong>Yes, please!</strong></a>
	            <a class="logreg" id="no" href="index.php">No, I am fine.</a>
						</div>
					</body>

				' ;

      }
      else {
        //Misc error
      }
    }
    else {
      $exists = true ;
    }
}

}
else if (isset($_SESSION['id_user'])) {
  //redirect to player
	header('Location: index.php');

}
if(!isset($_POST['user']) || !isset($_POST['pass']) || ($_POST['user'] == "") || ($_POST['pass'] == "") || $exists == true || $unmatchedPassword != "") {

	if($exists == true) {
		$existsTag = "<br><p class='wbody'>Username already exists</p>" ;
	}
	else {
		$existsTag = "" ;
	}

	$html =
	'
	<!DOCTYPE html>
	  <html>
	    <head>
	      <title>Shufl - Register</title>
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
				<p class="wheading"><strong>Registration</strong></p>
		    <form method="post">
		      <input class="logregbox" placeholder="Username" type="text" name="user">
					<br>
		      <input class="logregbox" placeholder="Password (case sensitive)" type="password" name="pass">
		      <br>
					<input class="logregbox" placeholder="Confirm your Password" type="password" name="pass2">
		      <br>
		      <input class="sbtn" value="Register" type="submit" name="submit">
		    </form>
				'.$existsTag.'
				'.$unmatchedPassword.'
				<p class="wbody">Already have an account? <a id="login" class="misclink" href="login.php">Log in.</a></p>
			</div>
	    </body>
	  </html>

	' ;

}

echo $html ;


?>
