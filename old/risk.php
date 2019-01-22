<?php

session_start() ;

include "vars.php" ;
include "rytfunctions.php" ;

//Turns off that notice, which exists.
error_reporting(0);


$table =
"
<table id='searchresults' style='width:100%''>
  <tr>
    <th>Video Number</th>
    <th>Video ID</th>
    <th>Title</th>
    <th>Artist</th>
    <th>Date Rolled</th>
    <th id='watch'>Watch</th>
  </tr>
" ;


$conn = new mysqli("localhost", $dbuser, $dbpass, $db);

// If there's no connection error
if (!$conn->connect_error)
{

  //echo "1" ;
  //If we can prepare a statement
  if($stmt = $conn->prepare("SELECT id FROM music WHERE fav = 1"))
  {
    //echo "2" ;

    //Bind the two variables to the two question marks.  s means the variables will be strings.
    //$stmt->bind_param();
    //I do not need this because I am not binding anything to the statement

    $stmt->execute();

    //If there was a result
    if($stmt->bind_result($vidString))
    {

      //echo "3" ;
      //if we can get the values from the result
      $arrayIndex = 0 ;
      while($stmt->fetch()) {

        $vidIdArray[$arrayIndex] = $vidString ;

        //echo "4" ;

        $arrayIndex++ ;

      }

      //This is where we work on displaying the results, actually building HTML for our results
      if(count($vidIdArray) > 0) {

        $results = "<h1>Risk-worthy Videos:</h1>" ;

        $arrayIndex = (count($vidIdArray) - 1) ;
        while($arrayIndex >= 0) {
          list($vidTitle, $channel, $date, $vidId, $seed) = generateEmbed($vidIdArray[$arrayIndex]) ;

          //This will split the different parts of the date into an arary for neater displaying
          $dateSplit = preg_split("~-~", $date);
          //This is for making the time a bit more friendly
          $friendlyTime = preg_split("~:~", $dateSplit[0]) ;
          //It would be nice to set it to a relevant time zone that isn't in central europe, but that is actually more complicated then it should be

          //Removing communism/military
          if ($friendlyTime[0] > 12) {
          	$friendlyTime[0] = ($friendlyTime[0] - 12) ;
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


          $results = $results.
          '
          <tr>
            <td>'.$vidIdArray[$arrayIndex].'</td>
            <td>'.$vidId.'</td>
            <td>'.$vidTitle.'</td>
            <td>'.$channel.'</td>
            <td>'.$friendlyDate.'</td>
            <td>
              <form action="buttons.php" method="get">
                  <input type="hidden" name="vidNum" value="'.$vidIdArray[$arrayIndex].'" />
                  <button type="submit" name="seek" id="playbutton">
                    <img src="img/play-s.png" width="100" height="49" />
                  </button>
            </form>

          </td>
          </tr>

          ' ;

          $arrayIndex-- ;
        }

      }
      //This is if we don't get any results back


    }
    //submit the statement to mysql

  }

  //echo "Connected." ;

}

if (isset($results)) {


  $table = $table.$results ;
  $table = $table.
  "
  </table>
  " ;

}
else {
  $table = "<h1>There are no Risk-worthy videos at this time. God help us all. <br></h1><p><small>(and save the queen)</small></p>" ;
}


$page =
'
<!DOCTYPE html>
	<html>
		<head>

			<link href="https://fonts.googleapis.com/css?family=Anton|Cabin|Nunito" rel="stylesheet">
			<link rel="stylesheet" href="searchstyle.css">
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

      <br>

      '.$table.'


			</div>


		</body>
	</html>

' ;


echo $page ;


?>
