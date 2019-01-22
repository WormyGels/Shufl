<?php
session_start() ;

include "rytfunctions.php" ;
include "vars.php" ;

//Turns off that notice, which exists.
error_reporting(0);

$table = $_SESSION['id_user'].$_SESSION['username'] ;
$table = cleanUser($table) ;

if(isset($_GET["newSong"])) {

	$genre = $_GET["newSong"] ;

	//crawlVideos will generate a random string, search youtube for it, then add it and info about it to the database.
	crawlVideos($table, $genre, 25, 3, 3) ;
	//This function returns the LATEST video added to the table, it will just reutrn the id.
	$vidNumber = getLatestVid($table) ;

	echo $vidNumber ;

}
else if(isset($_GET["goto"])) {

	$vidNumber = $_GET["goto"] ;
	//I will make use of the rest of the info later
	list($vidTitle, $channel, $date, $vidString, $seed, $fav) = generateEmbed($vidNumber, $table) ;

	$simpleTime = simplifyTime($date) ;

	//Echoing the vid number so that it can be recieved by javascript.
	echo $vidString.'~'.$vidTitle.'~'.$channel.'~'.$simpleTime.'~'.$fav.'~'.$vidNumber ;

}
else if(isset($_GET["getLatest"])) {

	$vidNumber = getLatestVid($table) ;


	echo $vidNumber ;
}
else if(isset($_GET["lastAmount"]) && isset($_GET["curNumber"])) {

	$quantity = $_GET["lastAmount"] ;
	$curNumber = $_GET["curNumber"] ;

	$index = 0 ;
	while ($index < $quantity) {

		list($vidTitle, $channel, $date, $vidString, $seed, $fav) = generateEmbed($curNumber, $table) ;

		echo $vidString.'~'.$vidTitle.'~'.$channel.'~'.$simpleTime.'~'.$fav.'~'.$curNumber.'<br>' ;

		$curNumber = $curNumber - 1 ;

		$index = $index + 1 ;
	}

}
else if(isset($_GET["fav"])) {

	$conn = new mysqli("localhost", $dbuser, $dbpass, $db);

	// If there's no connection error
	if (!$conn->connect_error)
	{

		//echo "1" ;
		//If we can prepare a statement
		if($stmt = $conn->prepare("SELECT id FROM ".$table." WHERE fav=1"))
		{
			//echo "2" ;

			//Bind the two variables to the two question marks.  s means the variables will be strings.
			//$stmt->bind_param();
			//I do not need this because I am not binding anything to the statement

			$stmt->execute();

			//If there was a result
			if($stmt->bind_result($vidId))
			{

				//echo "3" ;
				//if we can get the values from the result
				$arrayIndex = 0 ;
				while($stmt->fetch()) {

					$vidIdArray[$arrayIndex] = $vidId ;

					$arrayIndex++ ;

				}

				$index = count($vidIdArray)-1 ;

				while ($index >= 0) {
					list($vidTitle, $channel, $date, $vidString, $seed, $fav) = generateEmbed($vidIdArray[$index], $table) ;

					echo $vidString.'~'.$vidTitle.'~'.$channel.'~'.$simpleTime.'~'.$fav.'~'.$vidIdArray[$index].'<br>' ;

					$index = $index - 1 ;
				}
			}
		}
	}

}
else if(isset($_GET['favoritesong']) && isset($_GET['favunfav'])) {

	$song = $_GET['favoritesong'] ;
	$favUnfav = $_GET['favunfav'] ;

	favVideo($song, $favUnfav, $table) ;



}
//This function will get the vid id for external pages wallpaper when logged in
else if(isset($_GET['background'])) {

	$conn = new mysqli("localhost", $dbuser, $dbpass, $db);

	// If there's no connection error
	if (!$conn->connect_error)
	{

		//echo "1" ;
		//If we can prepare a statement
		if($stmt = $conn->prepare("SELECT cursong FROM users WHERE id=?"))
		{

			$stmt->bind_param("i", $id);

			$id = $_SESSION['id_user'] ;

			$stmt->execute() ;

			//If there was a result
			if($stmt->bind_result($curSong))
			{
				$stmt->fetch() ;

				list($vidTitle, $channel, $date, $vidString, $seed, $fav) = generateEmbed($curSong, $table) ;
				echo $vidString ;



			}
		}
	}
}
?>
