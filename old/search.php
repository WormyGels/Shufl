<?php

session_start() ;

if(isset($_SESSION['musicresults'])) {

  $results =
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
  $results = $results.$_SESSION['musicresults'] ;
  $results = $results.
  "
  </table>
  " ;

}
else {


  $results = "<p>No results found.</p>" ;

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

      <div id="searchtitle">

        <h1>Search by Title</h1>

        <form action="searchdb.php" method="get">

        <input type="text" id="searchbox" name="query" autocomplete="off">
        <button type="submit" name="search" id="searchbutton">
          <img src="img/search.png" width="100" height="49" />
        </button>

        </form>

      </div>

      <div id = "searchartist">

        <h1>Search by Artist</h1>

        <form action="searchdb.php" method="get">

        <input type="text" id="searchbox" name="query" autocomplete="off">
        <button type="submit" name="artistsearch" id="searchbutton">
          <img src="img/search.png" width="100" height="49" />
        </button>

        </form>

      </div>


      <div id="searchdate">

        <h1>Search by Date</h1>

        <form action="searchdb.php" method="get">

          <input type="date" id="datepicker" name="datequery">
          <button type="submit" name="datesearchbutton" id="datesearchbutton">
            <img src="img/search.png" width="100" height="49" />
          </button>

        </form>


      </div>

      <br>

      '.$results.'


			</div>


		</body>
	</html>

' ;


echo $page ;


?>
