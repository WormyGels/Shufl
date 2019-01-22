<?php

include "rytfunctions.php" ;

error_reporting(0);

$latestVid = getLatestVid() ;

if ($latestVid != null) {

  echo $latestVid ;

}
else {
  echo "error" ;
}





?>
