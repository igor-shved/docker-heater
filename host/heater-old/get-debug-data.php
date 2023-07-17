<?php

  include("./include/functions.php");
  
  print lockFileAndGetContents("outputs/x.dirty")."<br>";
  print lockFileAndGetContents("outputs/st")."<br>";
?>