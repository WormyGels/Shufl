window.onload = function() {
  hotkeys() ;
  updateVidCount() ;

};


setInterval(function updateVidCount() {


  var xhttp = new XMLHttpRequest();

  xhttp.open('GET', 'vidcount.php', true);
  xhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

  xhttp.send() ;

  xhttp.onreadystatechange = function() {
    if (xhttp.readyState == 4 && xhttp.status == 200) {

      var newVidCount = xhttp.responseText ;

      if (newVidCount != "error") {
        document.getElementById("latestVid").innerHTML = newVidCount ;
      }


      //window.alert(newVidCount) ;

    }

    else {
      //window.alert("First if statement did not fire.");
    }

  };


}, 3000) ;


function hotkeys() {

  document.onkeyup = function(e) {
    var key = e.keyCode ? e.keyCode : e.which;

    console.log(key) ;

    //space, and down arrow respectivley
    if ((key == 32) || (key == 40)) {
      window.location.replace("buttons.php?new=");
     }
     //left arrow
     else if (key == 37) {
       window.location.replace("buttons.php?back=");

     }
     //right arrow
     else if (key == 39) {
       window.location.replace("buttons.php?forward=");

     }
  }

}
