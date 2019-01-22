<?php

include "rytfunctions.php" ;
include "vars.php" ;

session_start() ;

unset($_SESSION['musicresults']) ;
$_SESSION['musicresults'] = "" ;


//Our session variable in question will be called results

//So if our query is set, then we will begin to search the database
if(isset($_GET["query"]) && (isset($_GET["search"])) && (strlen($_GET["query"]) > 1)) {


  $query = $_GET["query"] ;

  $conn = new mysqli("localhost", $dbuser, $dbpass, $db);

  // If there's no connection error
  if (!$conn->connect_error)
  {

    //echo "1" ;
    //If we can prepare a statement
    if($stmt = $conn->prepare("SELECT id FROM music WHERE (title LIKE '%".$query."%')"))
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

          $_SESSION['musicresults'] = "<p>Showing ".count($vidIdArray)." result(s) for '".$query."'</p>" ;

          $arrayIndex = 0 ;
          while($arrayIndex < count($vidIdArray)) {
            list($vidTitle, $channel, $date, $vidId, $seed) = generateEmbed($vidIdArray[$arrayIndex]) ;

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

            $_SESSION['musicresults'] = $_SESSION['musicresults'].
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

            $arrayIndex++ ;
          }

        }
        //This is if we don't get any results back
        else {
          unset($_SESSION['musicresults']) ;
        }


      }
      //submit the statement to mysql

    }

    //echo "Connected." ;

  }


}
//So if our query is set, then we will begin to search the database
else if(isset($_GET["query"]) && (isset($_GET["artistsearch"])) && (strlen($_GET["query"]) > 1)) {


  $query = $_GET["query"] ;

  $conn = new mysqli("localhost", $dbuser, $dbpass, $db);

  // If there's no connection error
  if (!$conn->connect_error)
  {

    //echo "1" ;
    //If we can prepare a statement
    if($stmt = $conn->prepare("SELECT id FROM music WHERE (channel LIKE '%".$query."%')"))
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

          $_SESSION['musicresults'] = "<p>Showing ".count($vidIdArray)." result(s) for '".$query."'</p>" ;

          $arrayIndex = 0 ;
          while($arrayIndex < count($vidIdArray)) {
            list($vidTitle, $channel, $date, $vidId, $seed) = generateEmbed($vidIdArray[$arrayIndex]) ;

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

            $_SESSION['musicresults'] = $_SESSION['musicresults'].
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

            $arrayIndex++ ;
          }

        }
        //This is if we don't get any results back
        else {
          unset($_SESSION['musicresults']) ;
        }


      }
      //submit the statement to mysql

    }

    //echo "Connected." ;

  }


}
else if(isset($_GET["datequery"]) && (isset($_GET["datesearchbutton"]))) {


  $query = $_GET["datequery"] ;
  $monthFix = preg_split("~-~", $query);
  if($monthFix[1] == 1) {
    $monthFix[1] = "January" ;
  }
  else if($monthFix[1] == 2) {
    $monthFix[1] = "February" ;
  }
  else if($monthFix[1] == 3) {
    $monthFix[1] = "March" ;
  }
  else if($monthFix[1] == 4) {
    $monthFix[1] = "April" ;
  }
  else if($monthFix[1] == 5) {
    $monthFix[1] = "May" ;
  }
  else if($monthFix[1] == 6) {
    $monthFix[1] = "June" ;
  }
  else if($monthFix[1] == 7) {
    $monthFix[1] = "July" ;
  }
  else if($monthFix[1] == 8) {
    $monthFix[1] = "August" ;
  }
  else if($monthFix[1] == 9) {
    $monthFix[0] = "September" ;
  }
  else if($monthFix[1] == 10) {
    $monthFix[1] = "October" ;
  }
  else if($monthFix[1] == 11) {
    $monthFix[1] = "November" ;
  }
  else if($monthFix[1] == 12) {
    $monthFix[1] = "December" ;
  }

  $query = $monthFix[1]."-".(int)$monthFix[2]."-".$monthFix[0] ;

  $conn = new mysqli("localhost", $dbuser, $dbpass, $db);

  // If there's no connection error
  if (!$conn->connect_error)
  {

    echo "1" ;
    //If we can prepare a statement
    if($stmt = $conn->prepare("SELECT id FROM music WHERE (date LIKE '%".$query."%')"))
    {
      echo "2" ;

      //Bind the two variables to the two question marks.  s means the variables will be strings.
      //$stmt->bind_param();
      //I do not need this because I am not binding anything to the statement

      $stmt->execute();

      //If there was a result
      if($stmt->bind_result($vidString))
      {

        echo "3" ;
        //if we can get the values from the result
        $arrayIndex = 0 ;
        while($stmt->fetch()) {

          $vidIdArray[$arrayIndex] = $vidString ;

          echo "4" ;

          $arrayIndex++ ;

        }

        //This is where we work on displaying the results, actually building HTML for our results
        if(count($vidIdArray) > 0) {

          $_SESSION['musicresults'] = "<p>Showing ".count($vidIdArray)." result(s) for '".$query."'</p>" ;

          $arrayIndex = 0 ;
          while($arrayIndex < count($vidIdArray)) {
            list($vidTitle, $channel, $date, $vidId, $seed) = generateEmbed($vidIdArray[$arrayIndex]) ;

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


            $_SESSION['musicresults'] = $_SESSION['musicresults'].
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

            $arrayIndex++ ;
          }

        }
        //This is if we don't get any results back
        else {
          unset($_SESSION['musicresults']) ;
        }


      }
      //submit the statement to mysql

    }

    //echo "Connected." ;

  }


}

else {


  unset($_SESSION['musicresults']) ;

}

header("Location: search.php");
exit();

?>
