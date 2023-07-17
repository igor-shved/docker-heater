<?php 
 $serverName = $_SERVER['SERVER_NAME'];
  
 if ( strpos($serverName,"log2.com.ua") === FALSE && strpos($serverName,"ilog2.com") === FALSE )
   require("./include/globalsettings.php");
 else
   require("../../include/globalsettings.php");
    
 if ( $remoteHost == "ilog2.com" || $remoteHost == "ilog.com.ua" )
  $remoteHost_ = "$remoteHost/live/$folderCode";
 else
  $remoteHost_  = "$remoteHost/$pathFolder";         
 
 $lmTime = 0;
 if ( isset($_GET['update']))
  $lmTime = $_GET['update'];  
  
 $getNew = isset($_GET['getnew']);
 $sendNew = isset($_GET['sendnew']);
 $getLocal = isset($_GET['getlocal']);
 $clear = isset($_GET['clear']);
 
 $separator = "#&^";
 
 if ( $sendNew )  // send new data to server
 {
    //$serverName = $_SERVER['SERVER_NAME'];
                 
    if ( strpos($serverName,"log2.com.ua") === FALSE && strpos($serverName,"ilog2.com") === FALSE )
      $hostThis = "$serverName:$localPort/$pathClean"; 
    else
      $hostThis = $serverName."/live/".basename(__DIR__); 
    
    //update date.x label
    file_put_contents("outputs/date.x", $lmTime );
         
    // get all settings on this server 
    $newContent = trim(file_get_contents("http://$hostThis/lm_ui_update.php?date=0&getnew"));
    $newContentEncode = urlencode($newContent);
    
    // and send to remote server
    $answer = trim(file_get_contents("http://$remoteHost_/lm_ui_update.php?date=$lmTime&sendnew&content=$newContentEncode"));
    
    $newData = explode($separator,$newContent);
    
    if ( $newData[1] == $answer )
      print "HASH-OK";
    else
      print "HASH-FAIL";
      
    return;
 }
  
 if ( $getNew ) // update data from server ( we take this data from remote server or get from 'getlocal' )
 { 
    // we run this script to replace all existing outputs/* files 
    // list of changes we take from remote server or from local                 
    if ( $getLocal )  
      $remoteData = $_GET['getlocal'];
    else
      $remoteData = trim(file_get_contents("http://$remoteHost_/lm_ui_update.php?date=$lmTime&getnew"));    
    
    $newData = explode($separator,$remoteData);
    
    if ( $newData[0] != 'md5' )
    {
      print "err.1"; 
      
      //file_put_contents("debug", ":$newData[0]:\n:$remoteData" );
      return;
    }
    
    //file_put_contents("debug", 'we are here 1' );
        
    $len = count($newData);
    $allContent = "";
    
    $fileNames = array();
    $fileContents = array();
    
    $i1 = 0;
    $i2 = 0;
    
    for ( $i = 2; $i < $len; $i++ )
    {
      $allContent .= $newData[$i];
      if ( $i < $len - 1 )
        $allContent .= $separator;   
     
      if ( $i % 2 == 0 )
        $fileNames[$i1++] = $newData[$i];
      else
        $fileContents[$i2++] = $newData[$i];    
    }      
    
    $hash = md5($allContent);
    if ( $hash != $newData[1])
    {
      print "err.2"; 
      return;
    }
    
    $allAreOk = true;
    $prevDateX = file_get_contents("outputs/date.x");
    
    for ( $i = 0; $i < $i2; $i++ )
    {
      if ( !lockFileAndRewriteStr($fileNames[$i],$fileContents[$i]) )
        $allAreOk = false;                
      //else
      //print "$fileNames[$i] -> $fileContents[$i]<br>";
    }
    
    if ( !$allAreOk )  
    {
      file_put_contents("outputs/date.x", $prevDateX );
      print "err.3"; 
      return;
    }
    
    if ( $getLocal )  //print "error";
      print $hash;  
    else
      print "HASH OK"; 
           
    return;
 }
 
 $clearStr = "";
 if ( $clear )     
  $clearStr = "&clear";
  
 print file_get_contents("http://$remoteHost_/lm_ui_update.php?date=$lmTime$clearStr");  

 
 return;
 
 function lockFileAndRewriteStr($fileName,$str)
 {
  if ( $fileName == "" )
    return;
    
  $file = fopen($fileName,"r+");
  
  if (flock($file, LOCK_EX)) 
  {      
    ftruncate($file, 0); // очищаем файл
    if ( fwrite($file, $str) === FALSE )
    {
      flock($file, LOCK_UN);
      return false; // запись
    }
    
    fflush($file);
    flock($file, LOCK_UN); // снятие блокировки
    fclose($file); 
    
    return true;     
  }
  else
  {
    fclose($file);      
    flock($fstate, LOCK_UN);
    fclose($fstate);
  } 
  return false;
 }
?>