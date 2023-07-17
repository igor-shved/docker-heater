<?php
  // have to change if update.php and all other files are in differen folders
  $path = ".";
  // folder name where all files of home heater web-ui are installed 
  $pathClean = "heater"; // path betwen http://server.org/$pathClean/get-remote.php
  $localPort = 80;
   
  $webFontKitPath = "include/"; 
  $iconsPath = "icons";
  $jsPath = "";
  $scriptPath = "";
  
  // UNCOMENT THIS STRINGS IF PATHs are not working
  //$webFontKitPath = "$pathClean/$webFontKitPath"; 
  //$iconsPath = "$pathClean/$iconsPath";
  //$jsPath = "$pathClean/"; 
  //$scriptPath = "$pathClean/";
  
  
  // iLOG2.COM SETUP: (ilog2.com - server supported by house4u)
  // REMOTE SERVER SETTINGS: may be ilog2.com or customer's own remote server
  $folderCode = "sxyetl"; // code folder in cloud ilog2.com
  
  $remoteHost = "ilog2.com";
  $pathFolder = "heat-engine"; // path to update.php on the remote server
  
  // mySERVER.COM SETUP: (customer's own server)
  // UNCOMENT THIS TWO STRINGS TO RUN SCRIPT ON OWN REMOTE SERVER
  //$remoteHost = "house4u.com.ua";
  //$pathFolder = "monitor2"; // path to update.php on the remote server
  
  $serverName = $_SERVER['SERVER_NAME'];
  if ( $serverName == "" || $serverName == "_" )
    $serverName = "localhost";        
?>   

