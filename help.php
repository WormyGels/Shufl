<?php

session_start() ;

$tutorial = '

  <div class="tutsections" id="player">
    <div class="tutleft">
      <p class="wheading">Using the player</p>
      <p class="wbody">Once you have created an account, you will be presented with the player.</p>
      <br>
      <p class="wsubheading"><i>Generating a new song</i></p>
      <p class="wbody">Click the <strong>"plus"</strong> button in the bottom left-hand corner of the screen to generate a new song. (fig. 3)</p>
      <br>
      <p class="wsubheading"><i>Navigating Previous Generations</i></p>
      <p class="wbody">Below the information on the current song, are the list of previously generated songs. Click on a song
      to listen to it. (fig. 4)</p>
      <br>
      <p class="wbody">Click <strong>"previous"</strong> at the top of the song list to see older generations. (fig. 5) To see more recent generations, click <strong>"next"</strong>. (fig. 6)</p>
      <br>
      <p class="wsubheading"><i>Adding a Song to Favorites</i></p>
      <p class="wbody">Click <strong>"Favorite Song"</strong> to add the currently playing song to your favorites. (fig. 7)</p>
      <br>
      <p class="wsubheading"><i>Accessing Favorites</i></p>
      <p class="wbody">Click <strong>"Favorites"</strong> on the right side of the player below the now playing info, to access your
      favorites. (fig. 8) <br> To switch back to the previous generations, click <strong>"Normal"</strong>. (fig. 9)</p>
      <br>
      <p class="wbody">The favorite song list is navigated just like the regular song list.</p>
    </div>
    <div class="tutimgs">
      <img class="tutimg" src="img/tut/gensong.png" width="480" >
      <img class="tutimg" src="img/tut/prevgen.png" width="480" >
      <img class="tutimg" src="img/tut/favorite.png" width="480" >
    </div>
  </div>


  <div class="tutsections" id="topright">
    <div class="tutleft">
      <p class="wheading">Settings and logging out</p>
      <p class="wbody">In the top right-hand corner of the screen next to your username, you will see a link to settings and a button to logout. (fig. 10)</p>
      <br>
      <p class="wbody">Click <strong>"Logout"</strong> to logout.</p>
      <br>
      <p class="wbody">Click <strong>"Settings"</strong> to access settings. (fig. 11)</p>
      <br>
      <p class="wbody">The settings menu lets you change certain aspects about the song generation such as:</p>
      <ul>
        <li><p class="wbody">Genre of Music</p></li>
        <li><p class="wbody">Auto-generation of music after song ends</p></li>
      </ul>
      <p class="wbody">After making your desired changes, click <strong>"Update Settings"</strong> to change your settings. These settings do not affect any previously generated songs.</p>
    </div>
    <div class="tutimgs">
      <img class="tutimg" src="img/tut/topright.png" width="480" >
    </div>
</div>

<div class="tutsections" id="conclusion">
  <p class="wheading">That is all!</p>
  <p class="wbody">You are good to go! You can access this page at any time by clicking <strong>"Help"</strong> in the top right.</p>
</div>

' ;

if(isset($_SESSION['id_user']) && isset($_SESSION['username'])) {
  $welcomePage = false ;
  $username = $_SESSION['username'] ;
}
else {
  $welcomePage = true ;
}

if (($welcomePage == true)) {
  $html =
  '
  <!DOCTYPE html>
    <html>
      <head>
        <title>Shufl - Help</title>
        <link href="https://fonts.googleapis.com/css?family=Indie+Flower" rel="stylesheet">
        <link rel="stylesheet" href="style.css">
      </head>

      <body class="welcomebody">

      <div id="background">
      <img id="nologin" class="wallpaper" src="img/background2.png" draggable="false" alt="Album Cover">
      </div>

        <div id="navbar">
          <div id="navbuttons">
            <a href="index.php"><img src="img/logo.png" id="logo" height="36" draggable="false" /></a>
            <div id="navlogin">
              <a id="player" class="logregnav" href="index.php">Home</a>
              <a id="register" class="logregnav" href="register.php">Register</a>
              <a id="login" class="logregnav" href="login.php">Login</a>
              <span id="help" class="slogregnav">Help</span>
            </div>
          </div>
        </div>

        <div id="tutorial">
          <div class="tutsections" id="tutheader">
            <p class="wheading"><strong>Getting Started with Shufl</strong></p>
            <p class="wbody">Thank you for using Shufl! Here are a few things to get you started on your journey of music discovery.</p>
          </div>

          <div class="tutsections" id="tutregister">
            <div class="tutleft">
              <p class="wheading"<strong>Registration / Logging In</strong></p>
              <p class="wbody">To use Shufl, you will need an account. No worries! It is 100% free.</p>
              <br>
              <p class="wsubheading"><i>Creating an account</i></p>
              <p class="wbody">Click <a class="tutlink" href="register.php">"Register"</a> in the top right-hand corner of the screen. (fig. 1)</p>
              <br>
              <p class="wbody">Enter your desired username and password into the form, and click the button that says "Register". (fig. 2)</p>
              <br>
              <p class="wbody">Once you have created an account you will automatically be signed in and given the option to return to this page.</p>
              <br>
              <p class="wsubheading"><i>Logging In</i></p>
              <p class="wbody">If you already have an account, you just need to log in.</p>
              <br>
              <p class="wbody">
                Click <a class="tutlink" href="login.php">"Login"</a> in the top right-hand corner of the screen to get to the login screen.
                The login screen is very similar to the registration screen. Just enter your registered username and password.
              </p>

            </div>
            <div class="tutimgs">
              <img class="tutimg" src="img/tut/register2.png" width="480" >
            </div>
          </div>

        '.$tutorial.'

        </div>
      </body>
    </html>
  ' ;
}

else {
  $html =
  '
  <!DOCTYPE html>
    <html>
      <head>
        <title>Shufl - Help</title>
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
              <span id="help" class="slogregnav">Help</span>
              <a id="settings" class="logregnav" href="settings.php">Settings</a>
              <a id="logout" class="logregnav" href="logout.php">Logout</a>
            </div>
          </div>
        </div>

        <div id="tutorial">
          <div class="tutsections" id="tutheader">
            <p class="wheading"><strong>Getting Started with Shufl</strong></p>
            <p class="wbody">Thank you for using Shufl! Here are a few things to get you started on your journey of music discovery.</p>
          </div>

        '.$tutorial.'

        </div>

      </body>
    </html>
  ' ;

}

echo $html ;

?>
