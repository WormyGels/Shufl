var player;
var currentSong ;
var currentMax ;
var isCurrentFav ;

//These are the awful global variables I had to make to make favorites work
var globalFavs ;
var favMode ;
var favIndex ;
var favDirection ;

// var playButtonHTML = '<button class="control" id="play" onclick="playPause();"> <img src="img/play.png" id="pausepic" width="60" height="60" draggable="false" /> </button>' ;
// var pauseButtonHTML = '<button class="control" id="play" onclick="playPause();"> <img src="img/pause.png" id="playpic" width="60" height="60" draggable="false" /> </button>';
// var unmutedButtonHTML = '<button class="control" id="mute" onclick="muteUnmute();"> <img src="img/volume.png" id="mutepic" width="60" height="60" draggable="false" /> </button>' ;
// var mutedButtonHTML = '<button class="control" id="mute" onclick="muteUnmute();"> <img src="img/muted.png" id="unmutepic" width="60" height="60" draggable="false" /> </button>' ;

//These are the user settings which I will use as constants that are updated on page load
var globalVolume ;
var region ;
var autogenOff ;

getSettings() ;
getLatest() ;

function generateSong() {

  var xhttp = new XMLHttpRequest();
  xhttp.open('GET', 'buttons.php?newSong='+region+'');
  xhttp.send() ;


  xhttp.onreadystatechange = function() {
    if (xhttp.readyState == 4 && xhttp.status == 200) {
        var vidNumber = xhttp.responseText ;
        gotoSong(vidNumber) ;
        currentSong = vidNumber ;
        currentMax = vidNumber ;
        fillPrevious(currentMax) ;

        //Button gets desynced when the player is paused
        if (player.getPlayerState() == 2) {
          playPause() ;
        }

    }

  };

}

function fillPrevious(song) {

  favMode = false ;

  if (song < 10) {
    song = 10 ;
  }
  else if(song > currentMax) {
    song = currentMax ;
  }

  var xhttp = new XMLHttpRequest();
  xhttp.open('GET', 'buttons.php?lastAmount=10&curNumber='+song);
  xhttp.send() ;

  xhttp.onreadystatechange = function() {
    if (xhttp.readyState == 4 && xhttp.status == 200) {

      var processedResponse = xhttp.responseText ;
      //This separates the videos info into an array of the different videos
      var previousVids = processedResponse.split("<br>") ;

      //last one is just an empty string
      var index = previousVids.length-2 ;

      var topVid = previousVids[index].split("~")[5] ;

      if (topVid < 10) {
        topVid = 10 ;
      }

      document.getElementById("psongcontain").innerHTML = "<button id='prevsongprev' class='prevsongbtn' onclick='fillPrevious("+topVid+");'>Previous</button><button class='prevsongbtn' id='favsong' onclick='favSong();'>Favorite Song</button><button class='prevsongbtnActive' id='normtab' onclick='fillPrevious("+currentSong+");'>Normal</button><button class='prevsongbtn' id='favtab' onclick='fillPreviousFavs(0, 9);'>Favorites</button><div id='prevsongs'></div>" ;

      while (index >= 0) {

        var videoId = previousVids[index].split("~")[0] ;
        var title = previousVids[index].split("~")[1] ;
        var channel = previousVids[index].split("~")[2] ;
        //var date = previousVids[index].split("~")[3] ;
        //var isFav = previousVids[index].split("~")[4] ;
        var vidNum = previousVids[index].split("~")[5] ;

        var div = document.createElement("div");
        div.setAttribute('id', vidNum);
        div.setAttribute('class', 'prevsongdiv');
        div.setAttribute('onclick', 'gotoSong(this.id)')

        if (vidNum == currentSong) {
          div.setAttribute('class', 'prevsongplaying');
        }

        div.innerHTML = '<div id="prevsongcovercontainer"> <img id="prevsongcover" height="120" width="120" src="https://i.ytimg.com/vi/'+videoId+'/hqdefault.jpg" alt="Album Cover"></img> </div> <p id="prevsongname">'+title+' - '+channel+' </p>';

        if (vidNum*1 <= 0) {
          //nothing (this used to do something but now it doesnt and im too lazy to make it pretty)
        }
        else if(vidNum*1 > currentMax*1) {
          //nothing (this used to do something but now it doesnt and im too lazy to make it pretty)
        }
        else {
          document.getElementById("prevsongs").appendChild(div);
        }


        index = index-1 ;

      }

      var botVid = vidNum*1 + 9 ;
      if (botVid > currentMax*1) {
        botVid = currentMax ;
      }

      var button = document.createElement("button");
      button.setAttribute('class', "prevsongbtn") ;
      button.setAttribute('id', "prevsongnext") ;
      button.setAttribute('onclick', "fillPrevious("+botVid+");") ;
      button.innerHTML = "Next" ;

      document.getElementById("psongcontain").appendChild(button);

      if (isCurrentFav == 1) {
        document.getElementById("favsong").innerHTML = "Unfavorite Song" ;
      }
      else {
        document.getElementById("favsong").innerHTML = "Favorite Song" ;
      }

    }

  };

}

function fillPreviousFavs(bottom, top) {

  favMode = true ;

  var xhttp = new XMLHttpRequest();
  xhttp.open('GET', 'buttons.php?fav');
  xhttp.send() ;

  xhttp.onreadystatechange = function() {
    if (xhttp.readyState == 4 && xhttp.status == 200) {

      var processedResponse = xhttp.responseText ;
      //This separates the videos info into an array of the different videos
      var previousVids = processedResponse.split("<br>") ;
      globalFavs = previousVids ;

      if(bottom < 0) {
        bottom = 0 ;
        top = 9 ;
      }
      if((top > previousVids.length-2) && (previousVids.length-2 >= 9)) {
        top = previousVids.length-2 ;
        bottom = (previousVids.length-2) - 9 ;
      }
      else if (previousVids.length-2 < 9) {
        top = previousVids.length-2 ;
        bottom = 0 ;
      }

      var index = top ;

      // var topVid = previousVids[top].split("~")[5] ;
      // var botVid = previousVids[bottom].split("~")[5] ;

      document.getElementById("psongcontain").innerHTML = "<button id='prevsongprev' class='prevsongbtn' onclick='fillPreviousFavs("+(bottom+9)+", "+(top+9)+");'>Previous</button><button class='prevsongbtn' id='favsong' onclick='favSong();'>Favorite Song</button><button class='prevsongbtn' id='normtab' onclick='fillPrevious("+currentSong+");'>Normal</button><button class='prevsongbtnActive' id='favtab' onclick='fillPreviousFavs(0, 9);'>Favorites</button><div id='prevsongs'></div>" ;


      while (index >= bottom) {

        var videoId = previousVids[index].split("~")[0] ;
        var title = previousVids[index].split("~")[1] ;
        var channel = previousVids[index].split("~")[2] ;
        //var date = previousVids[index].split("~")[3] ;
        //var isFav = previousVids[index].split("~")[4] ;
        var vidNum = previousVids[index].split("~")[5] ;

        var div = document.createElement("div");
        div.setAttribute('id', vidNum);
        div.setAttribute('class', 'prevsongdiv');
        div.setAttribute('onclick', 'gotoSong(this.id)')

        if (vidNum == currentSong) {
          div.setAttribute('class', 'prevsongplaying');
          favIndex = index ;
        }

        div.innerHTML = '<div id="prevsongcovercontainer"> <img id="prevsongcover" height="120" width="120" src="https://i.ytimg.com/vi/'+videoId+'/hqdefault.jpg" alt="Album Cover"></img> </div> <p id="prevsongname">'+title+' - '+channel+' </p>';


        document.getElementById("prevsongs").appendChild(div);



        index = index-1 ;

      }

      var button = document.createElement("button");
      button.setAttribute('class', 'prevsongbtn');
      button.setAttribute('id', "prevsongnext") ;
      button.setAttribute('onclick', "fillPreviousFavs("+(bottom-9)+", "+(top-9)+");") ;
      button.innerHTML = "Next" ;

      document.getElementById("psongcontain").appendChild(button);

      if (isCurrentFav == 1) {
        document.getElementById("favsong").innerHTML = "Unfavorite Song" ;
      }
      else {

        document.getElementById("favsong").innerHTML = "Favorite Song" ;
      }

    }

  };

}

function gotoLast() {
  gotoSong(currentSong) ;
  updateVolume(globalVolume) ;
}

//This function sets the currentMax for the player, it does not get the last watched video
function getLatest() {

  var xhttp = new XMLHttpRequest();
  xhttp.open('GET', 'buttons.php?getLatest');
  xhttp.send() ;

  xhttp.onreadystatechange = function() {
    if (xhttp.readyState == 4 && xhttp.status == 200) {
      var vidNumber = xhttp.responseText ;
      currentMax = vidNumber ;

        //Start them off with one song
        if (currentMax == 0) {
          generateSong() ;
        }

    }

  };

}

// function gotoLatest() {
//
//   var xhttp = new XMLHttpRequest();
//   xhttp.open('GET', 'buttons.php?getLatest');
//   xhttp.send() ;
//
//   xhttp.onreadystatechange = function() {
//     if (xhttp.readyState == 4 && xhttp.status == 200) {
//       var vidNumber = xhttp.responseText ;
//       gotoSong(vidNumber) ;
//       currentSong = vidNumber ;
//       currentMax = vidNumber ;
//       //fillPrevious(currentSong) ;
//
//     }
//
//   };
//
// }

function gotoSong(songId) {

  if (songId < 1) {
    songId = 1 ;
  }

  if (favMode) {
    //Find the place in the favArray w/ song ID
    var index = 0 ;
    while (index < globalFavs.length) {
      if (globalFavs[index].split("~")[5] == songId) {
        var finalIndex = index ;
        index = globalFavs.length ;
      }

      index++ ;
    }

    favIndex = finalIndex ;
  }

  var xhttp = new XMLHttpRequest();
  xhttp.open('GET', 'buttons.php?goto='+songId);
  xhttp.send() ;

  xhttp.onreadystatechange = function() {
    if (xhttp.readyState == 4 && xhttp.status == 200) {
        var processedResponse = xhttp.responseText ;
        var videoId = processedResponse.split("~")[0] ;
        var title = processedResponse.split("~")[1] ;
        var channel = processedResponse.split("~")[2] ;
        var date = processedResponse.split("~")[3] ;
        var isFav = processedResponse.split("~")[4] ;
        isCurrentFav = isFav ;
        //currentSong = processedResponse.split("~")[5] ;

        var embedURL = "https://www.youtube.com/embed/" + videoId + "?autoplay=1" ;

        //Sets the playing ID on the page to the embedURL generated above
        player.loadVideoById(videoId) ;
        //player.setVolume(5) ;

        currentSong = songId ;


        //Sets all of the artist information, a bit worrying that this is quite modular.

        document.getElementById('cover').outerHTML = '<img id="cover" src="https://i.ytimg.com/vi/'+videoId+'/hqdefault.jpg" draggable="false" alt="Album Cover">' ;
        document.getElementById('background').innerHTML = '<img class="wallpaper" src="https://i.ytimg.com/vi/'+videoId+'/hqdefault.jpg" draggable="false" alt="Album Cover">' ;
        document.getElementById('song').innerHTML = "<strong>" + title + "</strong>" ;
        document.getElementById('artist').innerHTML = channel ;
        document.getElementById('date').innerHTML = date ;
        document.getElementById('ytlink').outerHTML = '<a class="sinfo" onclick="linkClick();" id="ytlink" href="https://www.youtube.com/watch?v='+videoId+'" target="_blank">View on YouTube</a>' ;

        if (document.getElementById("play").value == "paused") {
          playPause() ;
        }

        //This is pretty awful, but this changes the color for the selected song if it is on screen, and updates it accordingly.
        var prevsongs = document.getElementsByClassName("prevsongplaying") ;
        var index = 0 ;
        while (index < prevsongs.length) {
          prevsongs[index].className = "prevsongdiv" ;
          index = index*1 + 1 ;
        }

        if (document.getElementById(songId) != null) {
          document.getElementById(songId).className = "prevsongplaying" ;
        }
        else {
          if (favMode) {
            if (favDirection == true) {
              fillPreviousFavs((favIndex-9), favIndex) ;
            }
            else if (favDirection == false) {
              fillPreviousFavs(favIndex, (favIndex+9)) ;
            }
          }
          else {
            fillPrevious(songId*1) ;
          }
        }

    }

    if (isCurrentFav == 1 && document.getElementById("favsong") != null) {
      document.getElementById("favsong").innerHTML = "Unfavorite Song" ;
    }
    else {
      if (document.getElementById("favsong") != null) {
        document.getElementById("favsong").innerHTML = "Favorite Song" ;
      }
    }

    if (player.getPlayerState() == 2) {
      playPause() ;
    }

    updateLatestSongDb(songId) ;

  };

}

function updateLatestSongDb(songId) {

  var xhttp = new XMLHttpRequest();
  xhttp.open('GET', 'updatesettings.php?cursongupdate='+songId);
  xhttp.send() ;

  xhttp.onreadystatechange = function() {
    if (xhttp.readyState == 4 && xhttp.status == 200) {
    }
  };
}

function onYouTubeIframeAPIReady(videoId) {

  player = new YT.Player('frame', {
    width: 0,
    height: 0,
    videoId: videoId ,
    playerVars: {
      autoplay: 0 ,
      controls: 0 ,
      showinfo: 0 ,
      modestbranding: 1 ,

    },
    events: {
      'onReady': initialize,
      'onStateChange': onPlayerStateChange,
      'onError': onError
    }
  });

}

function initialize() {
  //This is fired after the youtube frame loads
  gotoLast() ;
  updateVolume(globalVolume) ;

}

//Turns out they have an onError event, thank god
function onError() {
  vidError() ;
}

function onPlayerStateChange(event) {
  //This is fired after ANYTHING happens to the youtube frame

  if (player.getPlayerState() == 1) {
    setInterval(vidTime, 500) ;

  }
  else if (player.getPlayerState() == 2) {
    clearInterval() ;
  }
  else if (player.getPlayerState() == 0) {
    clearInterval() ;

    //A desync issue if the video is scrolled to the end
    playPause() ;

    if ((currentSong == currentMax) && (autogenOff == 0)) {
      generateSong() ;
    }
    else {
      forwards() ;
    }

  }
  else if (player.getPlayerState() == -1) {
    clearInterval() ;
  }

}

function wait(ms){
   var start = new Date().getTime();
   var end = start;
   while(end < start + ms) {
     end = new Date().getTime();
  }
}

function getSettings() {

  var xhttp = new XMLHttpRequest();
  xhttp.open('GET', 'updatesettings.php?getsettings');
  xhttp.send() ;

  xhttp.onreadystatechange = function() {
    if (xhttp.readyState == 4 && xhttp.status == 200) {
        var unprocessedSettings = xhttp.responseText ;
        globalVolume = unprocessedSettings.split("~")[0] ;
        region = unprocessedSettings.split("~")[1] ;
        autogenOff = unprocessedSettings.split("~")[2] ;
        currentSong = unprocessedSettings.split("~")[3] ;

    }

  };

}

function favSong() {

  if (isCurrentFav == 1) {
    var value = 0 ;
    isCurrentFav = 0 ;
    document.getElementById("favsong").innerHTML = "Favorite Song" ;
  }
  else {
    var value = 1 ;
    isCurrentFav = 1 ;
    document.getElementById("favsong").innerHTML = "Unfavorite Song" ;

  }

  var xhttp = new XMLHttpRequest();
  xhttp.open('GET', 'buttons.php?favoritesong='+currentSong+'&favunfav='+value);
  xhttp.send() ;

  xhttp.onreadystatechange = function() {
    if (xhttp.readyState == 4 && xhttp.status == 200) {
      if (favMode) {
        fillPreviousFavs(0, 9) ;
      }

    }

  };

}

function formatTime(time){
    time = Math.round(time);

    var minutes = Math.floor(time / 60),
    seconds = time - minutes * 60;

    seconds = seconds < 10 ? '0' + seconds : seconds;

    return minutes + ":" + seconds;
}

//Function that will change the video info to an error displayed to the user
function vidError() {

  document.getElementById('cover').outerHTML = '<img id="cover" src="img/nosong.png" draggable="false" alt="Album Cover">' ;
  document.getElementById('background').innerHTML = '<img class="wallpaper" src="img/nosong.png" draggable="false" alt="Album Cover">' ;
  document.getElementById('song').innerHTML = "<strong>Song Unavailable</strong>" ;
  document.getElementById('artist').innerHTML = "This song is either deleted from YouTube or is blocking external websites from access" ;

}

function vidTime() {

  //Preventing errors
  if (player.getPlayerState() != -1) {
    var currentTime = player.getCurrentTime() ;
    var duration = player.getDuration() ;
  }
  else {
    var currentTime = 0 ;
    var duration = 0 ;
  }

  //var remainingTime = duration - currentTime ;

  //Only update if there is a difference, probably not the best solution, but the only one I could think of without getting super advanced.
  if (document.getElementById("time").innerHTML != formatTime(currentTime) + " / " + formatTime(duration)) {
    document.getElementById("time").innerHTML = formatTime(currentTime) + " / " + formatTime(duration) ; //In the future you might want to make this remaining time, but having sync issues

    //This prevents the progress bar from being in the center, might not be applicable when I switch to javascript
    if (currentTime == 0) {
        document.getElementById("seek").value = 0;
    }
    else {
      document.getElementById("seek").value = (currentTime/duration)*1000 ;
    }

  }

}

function updateSeek(time) {
  //Pause the video if it is playing to avoid glitchy audio scrubbing.
  if ((player.getPlayerState() == 1)) {
    playPause() ;
  }
  player.seekTo(time*(.001)*player.getDuration()) ;
  vidTime(time) ;

}

//I combined these into one and it seems more consistent so far.
function playPause() {

  //If the player is PLAYING, then we can pause the video
  if ((player.getPlayerState() == 1)) {
    player.pauseVideo() ;
    //defaulting to the hover images because the mouse should always be over the image when pausing/playing
    document.getElementById("play").src = "img/play.png" ;
    document.getElementById("play").value = "pausenoclick" ;
  }
  //If the video is PAUSED, then we can play the video and update the buttons
  else if ((player.getPlayerState() == 2)) {
    player.playVideo() ;
    //defaulting to the hover images because the mouse should always be over the image when pausing/playing
    document.getElementById("play").src = "img/pause.png" ;
    document.getElementById("play").value = "playingnoclick" ;
  }
  else if (player.getPlayerState() == 0) {
    document.getElementById("play").src = "img/pause.png" ;
    document.getElementById("play").value = "playingnoclick" ;
  }

  //Yeah this was a really stupid way to attack this.

  /*
  if (document.getElementById("play").value == "paused") {
    try {
      player.playVideo() ;
      document.getElementById("play").outerHTML = pauseButtonHTML ;
      document.getElementById("play").value = "playing" ;
    }
    catch(e) {
      //do nothing in this case, this will only fire in rare cases on video load
    }

  }
  else {
    try {
      document.getElementById("play").outerHTML = playButtonHTML ;
      document.getElementById("play").value = "paused" ;
      player.pauseVideo() ;
    }
    catch(e) {
      //do nothing in this case, this will only fire in rare cases on video load
    }

  }
  */

}

function muteUnmute() {

  if (player.isMuted()) {
    document.getElementById("mute").src = "img/volume.png" ;
    document.getElementById("mute").value = "unmutednoclick" ;
    player.unMute() ;
  }
  else if (player.isMuted() == false) {
    document.getElementById("mute").src = "img/muted.png" ;
    document.getElementById("mute").value = "mutednoclick" ;
    player.mute() ;
  }

  /*
  if (document.getElementById("mute").value == "unmuted") {
    document.getElementById("mute").outerHTML = mutedButtonHTML ;
    document.getElementById("mute").value = "muted" ;
    player.mute() ;
  }
  else {
    document.getElementById("mute").outerHTML = unmutedButtonHTML ;
    document.getElementById("mute").value = "unmuted" ;
    player.unMute() ;
  }
  */
}

//This really doesn't behave how I'd like
function updateVolume(volume) {

  if (volume > 0) {
    player.setVolume(volume) ;

    //Update the volume in the DB
    var xhttp = new XMLHttpRequest();
    xhttp.open('POST', 'updatesettings.php', true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send('volume='+volume) ;

  }


}

function forwards() {

  if (favMode) {

    favDirection = true ;

    favIndex = favIndex - 1 ;

    if (favIndex < 0) {
      favIndex = 0 ;
    }
    currentSong = globalFavs[favIndex].split("~")[5] ;
    gotoSong(currentSong) ;

  }

  else {
    if ((currentSong*1 + 1) <= currentMax) {
      currentSong = currentSong*1 + 1 ;
      gotoSong(currentSong) ;
    }
    else {
      gotoSong(currentSong) ;
    }


  }

}

function backwards() {

  if (favMode) {

    favDirection = false ;

    favIndex = favIndex + 1 ;
    if (favIndex >= globalFavs.length-1) {
      favIndex = globalFavs.length-2 ;
    }
    else {
      favIndex = 0 ;
    }
    currentSong = globalFavs[favIndex].split("~")[5] ;
    gotoSong(currentSong) ;

  }

  else {

    if ((currentSong*1 - 1) > 0) {
      currentSong = currentSong*1 - 1 ;
      gotoSong(currentSong) ;
    }
    else {
      gotoSong(currentSong) ;
    }
    // if (document.getElementById("play").value == "paused") {
    //   playPause() ;
    // }

  }

}
//Hey this actually worked
function linkClick() {
  if ((player.getPlayerState() == 1) || player.getPlayerState() == 3) {
    playPause() ;
  }

}

function controlClick(e) {
  if ((e.id == "newSong") && (e.value != "click")) {
    e.src = "img/click/new.png" ;
    e.value = "click" ;
  }
  else if ((e.id == "newSong") && (e.value == "click")) {
    e.src = "img/new.png" ;
    e.value = "noclick" ;
  }
  if ((e.id == "back") && (e.value != "click")) {
    e.src = "img/click/backwards.png" ;
    e.value = "click" ;
  }
  else if ((e.id == "back") && (e.value == "click")) {
    e.src = "img/backwards.png" ;
    e.value = "noclick" ;
  }
  if ((e.id == "forwards") && (e.value != "click")) {
    e.src = "img/click/forwards.png" ;
    e.value = "click" ;
  }
  else if ((e.id == "forwards") && (e.value == "click")) {
    e.src = "img/forwards.png" ;
    e.value = "noclick" ;
  }

  //These are the more complicated ones since they are dynamic
  if ((e.id == "play") && (e.value == "playingnoclick") && (e.value != "pausednoclick") && (e.value != "pausedclick")) {
    e.src = "img/click/pause.png" ;
    e.value = "playingclick" ;
  }
  else if ((e.id == "play") && (e.value == "playingclick") && (e.value != "pausednoclick") && (e.value != "pausedclick")) {
    e.src = "img/pause.png" ;
    e.value = "playingnoclick" ;
  }
  else if ((e.id == "play") && (e.value == "pausenoclick") && (e.value != "playingnoclick") && (e.value != "playingclick")) {
    e.src = "img/click/play.png" ;
    e.value = "pausedclick" ;
  }
  else if ((e.id == "play") && (e.value == "pausedclick") && (e.value != "playingnoclick") && (e.value != "playingclick")) {
    e.src = "img/play.png" ;
    e.value = "pausenoclick" ;
  }

  if ((e.id == "mute") && (e.value == "mutednoclick") && (e.value != "unmutednoclick") && (e.value != "unmutedclick")) {
    e.src = "img/click/muted.png" ;
    e.value = "mutedclick" ;
  }
  else if ((e.id == "mute") && (e.value == "mutedclick") && (e.value != "unmutednoclick") && (e.value != "unmutedclick")) {
    e.src = "img/muted.png" ;
    e.value = "mutednoclick" ;
  }
  else if ((e.id == "mute") && (e.value == "unmutednoclick") && (e.value != "mutednoclick") && (e.value != "mutedclick")) {
    e.src = "img/click/volume.png" ;
    e.value = "unmutedclick" ;
  }
  else if ((e.id == "mute") && (e.value == "unmutedclick") && (e.value != "mutednoclick") && (e.value != "mutedclick")) {
    e.src = "img/volume.png" ;
    e.value = "unmutednoclick" ;
  }
}

function leaveBtn(e) {
  if (e.id == "newSong") {
    e.src = "img/new.png" ;
    e.value = "noclick" ;
  }
  else if (e.id == "back") {
    e.src = "img/backwards.png" ;
    e.value = "noclick" ;
  }
  else if (e.id == "forwards") {
    e.src = "img/forwards.png" ;
    e.value = "noclick" ;
  }
  else if ((e.id == "play") && (e.value == "pausedclick")) {
    e.src = "img/play.png" ;
    e.value = "pausenoclick" ;
  }
  else if ((e.id == "play") && (e.value == "playingclick")) {
    e.src = "img/pause.png" ;
    e.value = "playingnoclick" ;
  }
  else if ((e.id == "mute") && (e.value == "unmutedclick")) {
    e.src = "img/volume.png" ;
    e.value = "unmutednoclick" ;
  }
  else if ((e.id == "mute") && (e.value == "mutedclick")) {
    e.src = "img/muted.png" ;
    e.value = "mutednoclick" ;
  }
}
