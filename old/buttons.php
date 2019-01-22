<?php

include "rytfunctions.php" ;

session_start() ;

//Turns off that notice, which exists.
error_reporting(0);

$vidNumber = $_SESSION['musicId'] ;

if(isset($_GET["new"])) {

	//crawlVideos will generate a random string, search youtube for it, then add it and info about it to the database.
	crawlVideos(25, 3, 3) ;
	//This function returns the LATEST video added to the database so it just returns its number.
	$vidNumber = getLatestVid() ;

	$_SESSION['musicId'] = $vidNumber ;


}
else if(isset($_GET["forward"])) {

	$vidNumber += 1 ;

	$currentMax = getLatestVid() ;

	//Need to do a check to make sure we don't go over the amount of videos
	if($vidNumber > $currentMax) {
		$vidNumber = $currentMax ;
		$_SESSION['musicId'] = $vidNumber ;
	}
	else {
		$_SESSION['musicId'] = $vidNumber ;

	}

}
else if(isset($_GET["back"])) {

	$vidNumber -= 1 ;

	//This one needs the check to make sure it doesn't go into the negatives
	if($vidNumber < 1) {
		$vidNumber = 1 ;
		$_SESSION['musicId'] = $vidNumber ;
	}
	else {
		$_SESSION['musicId'] = $vidNumber ;
	}

}
else if((isset($_GET["seek"])&&(isset($_GET["seekid"])))) {
	//Need to make sure that it isn't empty, it is greater than 0, and it is less than the latest vid
	if (($_GET["seekid"] != "")&&($_GET["seekid"] > 0)&&($_GET["seekid"] <= getLatestVid())) {
		$vidNumber = $_GET["seekid"] ;
		$_SESSION['musicId'] = $vidNumber ;



	}

}
else if((isset($_GET["seek"])&&(isset($_GET['vidNum'])))) {
	$vidNumber = $_GET['vidNum'] ;
	$_SESSION['musicId'] = $vidNumber ;

}
else if(isset($_GET["fav"])) {
	$vidNumber = $_SESSION['musicId'] ;

	list($vidTitle, $channel, $date, $vidString, $seed, $fav) = generateEmbed($vidNumber) ;

	if ($fav == 0) {
		favVideo($vidNumber, 1) ;
	}
	else {
		favVideo($vidNumber, 0) ;
	}

}


header("Location: index.php");
exit();


?>
