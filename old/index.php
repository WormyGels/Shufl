<?php

include "rytfunctions.php" ;

//Turns off that notice, which exists.
error_reporting(0);

//This will determine the video that is up when the user arrives at the page, in theory it could be anything, but latest vid is alright.

session_start() ;

$latestVideo = getLatestVid() ;

//If we have a videoid in our session then we will set our vidNumber to it, but if we don't we'll just load the last one.
if (isset($_SESSION['musicId'])) {
	$vidNumber = $_SESSION['musicId'] ;
}
else {
	$vidNumber = $latestVideo ;
	$_SESSION['musicId'] = $vidNumber ;
}

//This function spits out all the info about a video at a particular number, in this case we use the latest from the last function
list($vidTitle, $channel, $date, $vidString, $seed, $fav) = generateEmbed($vidNumber) ;

//This will split the different parts of the date into an arary for neater displaying
$dateSplit = preg_split("~-~", $date);
//This is for making the time a bit more friendly
$friendlyTime = preg_split("~:~", $dateSplit[0]) ;
//It would be nice to set it to a relevant time zone that isn't in central europe, but that is actually more complicated then it should be

//Removing communism/military
if ($friendlyTime[0] >= 12) {
	$friendlyTime[0] = ($friendlyTime[0] - 12) ;
	if ($friendlyTime[0] == 0) {$friendlyTime[0] = 12 ;}
	$amPm = "PM" ;
}
else {
	$amPm = "AM" ;
	if ($friendlyTime[0] == 0) {$friendlyTime[0] = 12 ;}
}
//Prevents 8:3 situations
if ($friendlyTime[1] < 10) {
	$friendlyTime[1] = ((string)0).(string)$friendlyTime[1] ;
}
$timeAmPm = $friendlyTime[0].":".$friendlyTime[1]." ".$amPm ;

$friendlyDate = $dateSplit[1]." ".$dateSplit[2].", ".$dateSplit[3]." at ".$timeAmPm." CET" ;

//This is where we determine which pic to use for whether it is favorited or not.
if ($fav == 0) {
	$favPic = "favorite.png" ;
}
else {
	$favPic = "unfavorite.png" ;
}



//This sets up the frame
$videoURL = "https://www.youtube.com/watch?v=".$vidString ;
$videoId = "https://www.youtube.com/embed/".$vidString ;


$videoFrame = '<iframe width="960" height="540" src="'.$videoId.'?rel=0&autoplay=1&ytp-pause-overlay=0" frameborder="0" allowfullscreen></iframe>' ;


$page =
'
<!DOCTYPE html>
	<html>
		<script src="main.js"></script>
		<head>

			<link href="https://fonts.googleapis.com/css?family=Anton|Cabin|Nunito" rel="stylesheet">
			<link rel="stylesheet" href="protostyle.css">
			<link rel="shortcut icon" type="image/x-icon" href="img/favicon.ico"/>
			<title>Random YouTube</title>

			<div class="nav">
				<a class="navbuttons" href="index.php"><strong>Home</strong></a>
				<a class="navbuttons" href="search.php"><strong>Search</strong></a>
				<a class="navbuttons" href="risk.php"><strong>Risk</strong></a>
				<a class="navbuttons" href="about.html"><strong>About</strong></a>

			</div>

		</head>
		<body>

			<div class="ryt">

			<div class="frame">
				'.$videoFrame.'
			</div>


			<div class="rightside" >

				<div class="info">
					<p class="subinfo"><strong>Title:</strong> '.$vidTitle.'</p>
					<br>
					<p class="subinfo"><strong>Artist:</strong> '.$channel.'</p>
					<br>
					<p class="subinfo"><strong>URL: </strong>'.$videoURL.'</p>
					<br>
					<p class="subinfo"><strong>Seed: </strong>'.$seed.'</p>
					<br>
					<p class="subinfo"><strong>Rolled On: </strong>'.$friendlyDate.'</p>
					<br>
					<p class="subinfo"><strong>Song No: </strong>#'.$vidNumber.' /</p> <p id="latestVid">'.$latestVideo.'</p>
				</div>

				<div class="controls">
					<form action="buttons.php" method="get">
						<button type="submit" name="back" id="back" class="button">
							<img src="img/back.png" id="backpic" width="150" height="150" />
						</button>

						<button type="submit" name="new" id="new" class="button">
							<img src="img/play.png" id="newpic" width="150" height="150" />
						</button>

						<button type="submit" name="forward" id="forward" class="button">
							<img src="img/forward.png" id="forwardpic" width="150" height="150" />
						</button>


					</form>


					<div class="seek">
							<form action="buttons.php" method="get">
							<input type="text" name="seekid" class="seekbox" autocomplete="off">
							<button type="submit" name="seek" class="seekbutton">
								<img src="img/seek.png" width="100" height="49" />
							</button>
							</form>
						</div>


						<div class="favbutton">
							<form action="buttons.php" method="get">
							<button type="submit" name="fav" class="fbutton">
								<img src="img/'.$favPic.'" width="100" height="49" />
							</button>
							</form>

						</div>

					</div>





				</div>

			</div>

			</div>

		</body>
	</html>

' ;


echo $page ;

?>
