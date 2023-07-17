<?php
  $num = strip_tags($_GET['x']);      
  $s = htmlspecialchars(file_get_contents("outputs/$num"));
  
  print "x3jK:".$s;

?>