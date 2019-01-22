<?php

session_start() ;

include "rytfunctions.php" ;

if(isset($_SESSION['id_user']) && isset($_SESSION['username'])) {
  $welcomePage = false ;
  $username = $_SESSION['username'] ;
  $userId = $_SESSION['id_user'] ;
}
else {
  $welcomePage = true ;
}

if ($welcomePage == true) {
  $html =
  '
  <!DOCTYPE html>
    <html>
      <head>
        <title>Shufl - Welcome</title>
        <link href="https://fonts.googleapis.com/css?family=Saira" rel="stylesheet">
        <link rel="stylesheet" href="style.css">
        <script src="jquery.min.js"></script>
        <script src="background-blur.min.js"></script>
      </head>

      <body class="welcomebody">

        <div id="background">
          <img id="nologin" class="wallpaper" src="img/background2.png" draggable="false" alt="Album Cover">
        </div>

        <div id="navbar">
          <div id="navbuttons">
          <a href="index.php"><img src="img/logo.png" id="logo" height="36" draggable="false" /></a>
            <div id="navlogin">
              <span id="player" class="slogregnav">Home</span>
              <a id="register" class="logregnav" href="register.php">Register</a>
              <a id="login" class="logregnav" href="login.php">Login</a>
              <a id="help" class="logregnav" href="help.php">Help</a>
            </div>
          </div>
        </div>

        <div class="wsections" id="welcome">
          <div id="bannercont">
            <img class="spanbanner" src="img/banner5.png" draggable="false" />
            <div id="loginbuttons">
              <a class="logreg" href="register.php">Register</a> <a class="logreg" href="login.php">Login</a>
            </div>
          </div>
        </div>

        <div class="wsections" id="winfo">
          <div id="infocont">
            <p class="wheading"><strong>What is Shufl?</strong></p>
            <br>
            <p class="wbody">
              There are millions of undiscovered and underviewed songs on YouTube that are auto-uploaded by publishers. Due to the algorithm
              that mediates the content of the website, there has not been an effective way to find and listen to these songs.
            </p>
            <br>
            <p class="wbody">
              Shufl uses the magic of random generation to search the depths of YouTube for this auto-generated music.
            </p>
          </div>
          <img id="ytlogo" src="img/shatteredytlogo.png" draggable="false" />
        </div>

      </body>
    </html>
  ' ;
}
else {

  $volume = getVolume($userId) ;

  $html =
  '
  <!DOCTYPE html>
    <html>
      <head>
        <title>Shufl - Player</title>
        <link href="https://fonts.googleapis.com/css?family=Saira" rel="stylesheet">
        <link rel="stylesheet" href="style.css">
        <script src="main.js"></script>
        <script src="bg.js"></script>
        <script src="https://www.youtube.com/iframe_api"></script>
      </head>

      <body class="body">

      <div id="preload">
        <img src="img/click/backwards.png" id="logo" height="54" draggable="false" />
        <img src="img/click/forwards.png" id="logo" height="54" draggable="false" />
        <img src="img/click/muted.png" id="logo" height="54" draggable="false" />
        <img src="img/click/new.png" id="logo" height="54" draggable="false" />
        <img src="img/click/pause.png" id="logo" height="54" draggable="false" />
        <img src="img/click/play.png" id="logo" height="54" draggable="false" />
        <img src="img/click/volume.png" id="logo" height="54" draggable="false" />
      </div>

      <div id="background">
      </div>

      <div id="navbar">
        <div id="navbuttons">
          <a href="index.php"><img src="img/logo.png" id="logo" height="36" draggable="false" /></a>
          <div id="navlogin">
            <span id="username" ><strong>'.$username.'</strong></span>
            <span id="player" class="slogregnav">Player</span>
            <a id="help" class="logregnav" href="help.php">Help</a>
            <a id="settings" class="logregnav" href="settings.php">Settings</a>
            <a id="logout" class="logregnav" href="logout.php">Logout</a>
          </div>
        </div>
      </div>

        <div id="nowplaying">

          <h1>Now Playing</h1>

          <div id="covercontainer">
            <img id="cover" src="img/nosong.png" alt="Album Cover">
          </div>
          <br>
          <div id="info">
            <p class="sinfo" id="song">n/a</p>
            <br>
            <p class="sinfo" id="artist">n/a</p>
            <br>
            <p class="sinfo" id="date">n/a</p>
            <br>
            <a class="sinfo" onclick="linkClick();" id="ytlink" href="/" target="_blank">n/a</a>
          </div>
          <div id="framecontainer">
            <div id="frame"></div>
          </div>
        </div>

        <div id="psongcontain">
          <div id="prevsongs">
          </div>
        </div>


        <div id="playercontrols">
          <div id="controlbar">
            <input class="control" id="newSong" type="image" src="img/new.png" draggable="false" width="54" onmousedown="controlClick(this);" onmouseup="controlClick(this);" onmouseout="leaveBtn(this);" onclick="generateSong();" />
            <input class="control" id="back" type="image" src="img/backwards.png" draggable="false" width="54" onmousedown="controlClick(this);" onmouseup="controlClick(this);" onmouseout="leaveBtn(this);" onclick="backwards();" />
            <input class="control" value="playingnoclick" id="play" type="image" src="img/pause.png" draggable="false" width="54" onmousedown="controlClick(this);" onmouseup="controlClick(this);" onmouseout="leaveBtn(this);" onclick="playPause();" />
            <input class="control" id="forwards" type="image" src="img/forwards.png" draggable="false" width="54" onmousedown="controlClick(this);" onmouseup="controlClick(this);" onmouseout="leaveBtn(this);" onclick="forwards();" />
            <input class="control" id="seek" type="range" min="0" max="1000" value="0" oninput="updateSeek(this.value);"></input>
            <p class="control" id="time">0:00 / 0:00</p>
            <input class="control" id="mute" value="unmutednoclick" type="image" src="img/volume.png" draggable="false" width="60" onmousedown="controlClick(this);" onmouseup="controlClick(this);" onmouseout="leaveBtn(this);" onclick="muteUnmute();" />
            <input class="control" id="volume" type="range" value='.$volume.' oninput="updateVolume(this.value);"></input>
          </div>
        </div>

      </body>
    </html>

  ' ;

}

echo $html ;


?>
