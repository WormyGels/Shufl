var xhttp = new XMLHttpRequest();
xhttp.open('GET', 'buttons.php?background');
xhttp.send() ;



xhttp.onreadystatechange = function() {
  if (xhttp.readyState == 4 && xhttp.status == 200) {
      var videoId = xhttp.responseText ;

      document.getElementById('background').innerHTML = '<img class="wallpaper" src="https://i.ytimg.com/vi/'+videoId+'/hqdefault.jpg" draggable="false" alt="Album Cover">' ;

  }

};
