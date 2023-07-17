<?php 
  include("./include/functions.php");
  
  $serverName = $_SERVER['SERVER_NAME'];
  $ilog2Server = false;
  if ( strpos($serverName,"log2.com.ua") === FALSE && strpos($serverName,"ilog2.com") === FALSE )
    require("./include/globalsettings.php");
  else
  {
    require("../../include/globalsettings.php");
    $ilog2Server = true;
  }
    
  $remoteServerUpdateTime = 0;  
  if ( isset($_GET['date'] ))
    $remoteServerUpdateTime = $_GET['date'];
    
  $thisServerUpdateTime = file_get_contents("outputs/date.x");
  
  $separator = "#&^";
  if ( isset($_GET['sendnew']) && $thisServerUpdateTime < $remoteServerUpdateTime )
  { 
    $serverName = $_SERVER['SERVER_NAME'];
                 
    if ( strpos($serverName,"log2.com.ua") === FALSE && strpos($serverName,"ilog2.com") === FALSE )
      $hostThis = "$serverName:$localPort/$pathClean"; 
    else
      $hostThis = $serverName."/live/".basename(__DIR__); 
        
    $newContent = urlencode($_GET['content']);    
    $newData = explode($separator,$_GET['content']);
          
    $hash = trim(file_get_contents("http://$hostThis/get-remote.php?date=0&getnew&getlocal=$newContent"));
    
    if ( $newData[1] == $hash )
    {
      // force to reload server web ui  - we will get it in latest data and ui will reload
      file_put_contents("outputs/reload.x","1",LOCK_EX); 
    }
    else
      $hash = "";
    
    print $hash;
    return;
  }
  
  if ( isset($_GET['getnew']) && $thisServerUpdateTime > $remoteServerUpdateTime )
  {
     // send data from this server to 'local'
     $allContent = "";
     
    for ( $i = 0; $i < 17; $i++ )
    {
      $fileName = "outputs/$i"; 
      $fileContent = lockFileAndGetContents($fileName);
      
      if ( $fileContent == "error" )
      {
        $allContent = "error";
        break;
      }
      
      $allContent .= $fileName.$separator.$fileContent.$separator;    
    } 
    
    if ( $allContent == "error" )
    {
      print "error";
      return;
    }
      
    $otherFiles = array();
    
    $otherFiles[0] = 'outputs/st'; 
    $otherFiles[1] = 'outputs/names';
    $otherFiles[2] = 'outputs/date.x';
    if ( !$ilog2Server )
      $otherFiles[3] = 'outputs/x.dirty';    
    
    $len = count($otherFiles);
             
    for ( $i = 0; $i < $len; $i++ )
    {
      $fileName = $otherFiles[$i];
      $fileContent = lockFileAndGetContents($fileName);
      
      if ( $fileContent == "error" )
      {
        $allContent = "error";
        break;
      }
      
      $allContent .= $fileName.$separator.$fileContent.$separator;   
    } 
    
    if ( $allContent == "error" )
    {
      print "error";
      return;
    }
      
    print "md5$separator".md5($allContent).$separator.$allContent;
    return;             
  }
  
  $clearStatus = "OK";
  if ( isset($_GET['clear']))
  {
    $dir = basename(__DIR__);
    if ( file_put_contents("outputs/st","00000000000000000:clear-$dir",LOCK_EX) === FALSE )
      $clearStatus = "ERR";   
  }
  
  $chatModifyDate = 0;//filemtime("settings/chat");
  
  print "$thisServerUpdateTime:$chatModifyDate:$clearStatus";
      
  return;
  
?>