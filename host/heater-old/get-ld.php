<?php require_once('s-check.php');
  
  include("./include/functions.php");
  
  print "pL1Se#".lockFileAndGetContents("datalog/latestdata.php");
  print "#".lockFileAndGetContents("outputs/date.x");
  print "#".filemtime("settings/chat");
  print "#".lockFileAndGetContents("outputs/reload.x");
?>