<?php require_once('s-check.php');
 
  $set = strip_tags($_GET['set']);     
  
  $res = false;
  if ( $set == "true" )
    $res = touch("settings/auth");
  else
    $res = unlink("settings/auth"); 
    
  if ( $res != TRUE )
    print "error";

?>