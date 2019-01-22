<?php
function cleanUser($username) {
	return str_replace(array('#', '@', '&', '=', '-', '~', '`', '!', '*', '^', '%', '[', ']', '+', '-', '$', '(', ')', '{', '}'), '', $username) ;
}


//This function adds the video id (as in the video number), the date, and the title to the database
function insertDb($table, $id, $title, $channel, $seed)
{

	//Getting the date for later
	$arrayDate = getdate();
	$date = ($arrayDate["hours"].":".$arrayDate["minutes"]."-".$arrayDate["month"]."-".$arrayDate["mday"]."-".$arrayDate["year"]) ;


	//All of these shenanigans are adding the ids to the database

	include "vars.php" ;

	$conn = new mysqli("localhost", $dbuser, $dbpass, $db);

	// If there's no connection error
	if (!$conn->connect_error)
	{
		//If we can prepare the mysql statement
		if($stmt = $conn->prepare("INSERT INTO ".$table." (vidstring, date, title, channel, seed) VALUES (?, ?, ?, ?, ?)"))
		{
			//Bind the variables
			$stmt->bind_param("sssss", $id, $date, $title, $channel, $seed);

			//$stmt->execute() ;

			//This ADDS to the database
			if($stmt->execute()) //don't know why this is in an if statement, but it crashes w/o it
			{
				//echo "/Success" ;
			}
			else
			{
				//echo "/First failed" ;

			}

			//echo "/Did prepare." ;
		}


		//echo "/Connected" ;

	}


}

function getLatestVid($table)
{

	include "vars.php" ;

	$conn = new mysqli("localhost", $dbuser, $dbpass, $db);

	//This one retrieves the latest number from the database, important for the new button
	//$curVidResult = $conn->query("SELECT id FROM vids ORDER BY id DESC LIMIT 1") ;
	$result = mysqli_query($conn, "SELECT id FROM ".$table." ORDER BY id DESC LIMIT 1");

	if (mysqli_num_rows($result) > 0) {
		// output data of each row
		while($row = mysqli_fetch_assoc($result)) {
			$curVidId = $row["id"] ;
		}


	}

	return $curVidId ;


}

//This function will retrieve all of the info at a particular video number and return it
function generateEmbed($vidNumber, $table)
{

	include "vars.php" ;

	$conn = new mysqli("localhost", $dbuser, $dbpass, $db);

	// If there's no connection error
	if (!$conn->connect_error)
	{

		//echo "1" ;
		//If we can prepare a statement
		if($stmt = $conn->prepare("SELECT title, channel, date, vidstring, seed, fav FROM ".$table." WHERE id=?"))
		{
			//echo "2" ;

			//Bind the two variables to the two question marks.  s means the variables will be strings.
			$stmt->bind_param("s", $vidNumber);

			$stmt->execute();

			//If there was a result
			if($stmt->bind_result($vidTitle, $channel, $vidDate, $vidId, $vidSeed, $fav))
			{

				//echo "3" ;
				//if we can get the values from the result
				if($stmt->fetch())
				{

					//echo "4" ;
				}

			}
			//submit the statement to mysql

		}

		//echo "Connected." ;

	}

	return array($vidTitle, $channel, $vidDate, $vidId, $vidSeed, $fav) ;
}


//This function sets the vid ID favorite status to the target value
function favVideo($vidId, $value, $table) {

	include "vars.php" ;

	$conn = new mysqli("localhost", $dbuser, $dbpass, $db);

	// If there's no connection error
	if (!$conn->connect_error)
	{
		//If we can prepare the mysql statement
		if($stmt = $conn->prepare("UPDATE ".$table." SET fav = ".$value." WHERE id = ".$vidId.""))
		{

			//$stmt->execute() ;

			//This ADDS to the database
			if($stmt->execute()) //don't know why this is in an if statement, but it crashes w/o it
			{
				//echo "/Success" ;
			}
			else
			{
				//echo "/First failed" ;

			}

			//echo "/Did prepare." ;
		}


		//echo "/Connected" ;

	}


}

//This video searches youtube using a randomly generated string and adds it to the database, it returns its seed to show to the user since we dont keep track of that
function crawlVideos($table, $genre, $count, $seedLengthMin, $seedLengthMax)
{

	include "vars.php" ;

	//For the music bot, because of lack of diversity. I do not want duplicate videos.
	//I will be creating isDuplicate for this, and trying to prove that it is NOT a duplicate
	$isDuplicate = true ;

	if ($genre != "") {
		$genreParam = "&topicId=/m/$genre&" ;
	}
	else {
		$genreParam = "&topicId=/m/04rlf&" ;
	}

	while ($isDuplicate) {

		//This while loop makes sure that a seed is never bad
		$videoId = "" ;
		while($videoId == "") {
			$q = generateRandomString(rand($seedLengthMin, $seedLengthMax));
			$url = "https://www.googleapis.com/youtube/v3/search?key=$API_KEY&videoSyndicated=true&maxResults=$count&part=snippet&topicId=/m/04rlf&type=video&q=$q%20\"Auto-generated%20by%20YouTube.\"" ;
			//This is the old line before I added the language settings
			//$url = "https://www.googleapis.com/youtube/v3/search?key=$API_KEY&maxResults=$count&part=snippet&topicId=/m/04rlf&type=video&q=$q%20\"Auto-generated%20by%20YouTube.\"" ;

			$JSON = file_get_contents($url);
			$JSON_Data_search = json_decode($JSON);

	    $index = 0 ;

			//Video ID
			foreach ($JSON_Data_search->{"items"} as $result) {
				//The video title
				$videoTitle = ($result->{"snippet"}->{"title"});
				$videoId = ($result->{"id"}->{"videoId"});
				$channel = ($result->{"snippet"}->{"channelTitle"});

				//Removing -topic from the channel
				$channelSplit = preg_split("~ -~", $channel);
				$channel = $channelSplit[0] ;

	      //This adds the titles to the array
	      $videoTitleArray[$index] = $videoTitle ;
	      $videoIdArray[$index] = $videoId ;
				$channelArray[$index] = $channel ;

	      $index++ ;

			}

		}

		//This is the new stuff that picks a random video from all of the results
		$max = (count($videoIdArray)-1) ;
		$randIndex = rand(0, $max) ;
		$videoId = $videoIdArray[$randIndex] ;
		$videoTitle = $videoTitleArray[$randIndex] ;
		$channel = $channelArray[$randIndex] ;

		//echo $videoId." " ;



		//This is where I see if the video is a duplicate
		$conn = new mysqli("localhost", $dbuser, $dbpass, $db);

		if (!$conn->connect_error)
		{

			if($stmt = $conn->prepare("SELECT title FROM ".$table." WHERE vidstring=?"))
			{

				$stmt->bind_param("s", $videoId);

				$stmt->execute();

				if($stmt->bind_result($vidTitle))
				{

					//If we can fetch something, then this means that it IS a duplicate, so we do nothing.
					if($stmt->fetch())
					{

					}
					//If we can't, then that means that it is NOT a duplicate, so we can go ahead and proceed by ending the loop.
					else {
						$isDuplicate = false ;
					}

				}
				else {

				}

			}

		}


	}

	//Now going to call the MySQL function I made, which adds the video to the database
	insertDb($table, $videoId, $videoTitle, $channel, $q) ;


}


function generateRandomString($length = 10)
{
    return substr(str_shuffle(str_repeat($x = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length / strlen($x)))), 1, $length);
}


function simplifyTime($date) {

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

	return $friendlyDate ;

}

function getVolume($userId) {
	include "vars.php" ;

	$conn = new mysqli("localhost", $dbuser, $dbpass, $db);

	// If there's no connection error
	if (!$conn->connect_error)
	{

		//echo "1" ;
		//If we can prepare a statement
		if($stmt = $conn->prepare("SELECT volume FROM users WHERE id=?"))
		{
			//echo "2" ;

			//Bind the two variables to the two question marks.  s means the variables will be strings.
			$stmt->bind_param("s", $userId);

			$stmt->execute();

			//If there was a result
			if($stmt->bind_result($volume))
			{

				if($stmt->fetch())
				{

				}

			}

		}


	}

	return $volume ;
}

?>
