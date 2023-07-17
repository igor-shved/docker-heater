<?php
  // [SECOND LINE OF 'self-test.php' FILE]
  include("./include/functions.php");
  
  $sep = "#$^";
  $sep2 = "*&^";
  
  function printArray($name, $arr)
  {
    global $sep;
    $res = "var $name=[";
    //print "$sep";
    $len = count($arr);
    
    for ( $i = 0; $i < $len; $i++ )
    {
      $res .= $arr[$i];
      if ( $i != $len - 1 )
        $res .= ",";
    }
    
    return $res."];<br>";
  }     
  
  function printArray2($name, $arr)
  {
    global $sep;    
    global $sep2;
    
    $len = count($arr);
     $res = "var $name=<br>[";
    //print $sep;
    for ( $i = 0; $i < $len; $i++ )
    {
      $len2 = count( $arr[$i] );
       
      //print $sep2;
                  
      for ( $j = 0; $j < $len2; $j++ )
      {       
        $res .= $arr[$i][$j];
        if ( $j != $len2 - 1 )
          $res .= ",";
      }      
      $res .= "]<br>";             
    } 
    return $res."]<br>";   
  } 
  
  $serverName = $_SERVER['SERVER_NAME'];
  $weAreLocalServer = "false";
   
  if ( strpos($serverName,"log2.com.ua") === FALSE && strpos($serverName,"ilog2.com") === FALSE )
    $weAreLocalServer = "true";   
   
  $requestPass = "false";
  if ( file_exists("settings/auth") )
    $requestPass = "true";
  
  $chatText = file_get_contents("settings/chat");
  $lastChatModify = filemtime("settings/chat");
  
  $rn = explode(";",file_get_contents("outputs/names"));  
      
  // last modify time by controller
  $modeXControllerDate = 0;
  
  // last modify time by user
  $modeXWebUIDate = file_get_contents("outputs/date.x");
  
  $roomsNum = $rn[0];
  
  for ( $i = 0; $i < 17; $i++ )
    $roomsName[$i] = $rn[$i+1];
    
  //$roomsName    = ['Весь Дом','Холл','Кухня','CУ этаж 1','Детская','Спальная','Детская 2','Гостевая','Спортзал']; 
  
  $currentMode  = array();//[7,1,2,1,3,3,4,5,3,4,1,4,3,2,1,4];
  $rightNowTemp = array();//[22,22,23,24,22,18,22,21,22,22,23,24,22,18,22,21];
  $standByTemp  = array();//[18,18,17,20,16,18,18,19,18,18,17,20,16,18,18,19];
  $scheduleIntervalsNum = array();
  $scheduleArrTime = array();
  $scheduleArrTemp = array();
  $scheduleArrIntervalMode = array();
  $updateSettings = array();
  $followAllHouse = array();
    
  $roomsTsensors = [0,0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15];
  $roomsPOutputs = [0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16];
  
  // 2;1;10e;0;0:af;6;7a1de;dbc90;3f52;472c;5abe;0;78;  
  //print "PASSW:".file_get_contents("settings/passw.php");
  
  $ld = explode(";",file_get_contents("datalog/latestdata.php"));
  
  $modeXStrPrev = $ld[23];
  
  $state = 0;
  if ( isset($ld[2]) )
    $state = $ld[2];
  
  $st = file_get_contents("outputs/st");
  $stateDebugStr = $st;
  
  for ( $i = 0; $i < 17; $i++ )
    if ( $st[$i] == 0 )
      $updateSettings[$i] = 0;
    else
      $updateSettings[$i] = 1;  
      
  //$st = file_get_contents("outputs/flags");
  
  $st = decbin(hexdec($ld[24]));
  $len = strlen($st);
  //print "FOLLOW ALL HOUSE:$st:$len";
  
  for ( $i = 0; $i < 16; $i++ )
  {
    if ( $i < $len )
      $followAllHouse[16-$i] = $st[$i];
    else
      $followAllHouse[16-$i] = 0;
  }
  $followAllHouse[0] = 1;
         
  for ( $i = 0; $i < 17; $i++ )
  {
    $parts = explode(":", file_get_contents("outputs/$i") );
    
    $p1 = explode(";",$parts[0]);
    $p2 = explode(";",$parts[1]);
    
    $currentMode[$i] = $p1[1];
    $rightNowTemp[$i] = hexdec($p1[2]) / 10.0;       
    
    $roomsPOutputs[$i] = hexdec($p2[0]);
    $roomsTsensors[$i] = hexdec($p2[1]);
         
    $standByTemp[$i] = hexdec($p2[2]) / 10.0;
    
    $intervalsNum = hexdec($p2[3]); 
    $scheduleIntervalsNum[$i] = $intervalsNum; 
    
    
    if ( $intervalsNum > 10 )
      $intervalsNum = 10;
      
    $j=4; 
         
    for ($j=0; $j < $intervalsNum; $j++ )
    {  
      $val = hexdec($p2[$j+4]); 
        
      $time = substr( $val, 0, count($val) - 4 );
      $scheduleArrTime[$i][$j] = $time;
      
      $temp = substr( $val, -3, 3 ); 
      $scheduleArrTemp[$i][$j] = $temp / 10.0;
    }
    
    $val = hexdec($p2[$j+4]);
    
    //print "VAL=".$val."\n"; 
    
    $fstr = "%0$intervalsNum"."d";
    $istr = sprintf($fstr,$val);
    
    for ( $j = $intervalsNum; $j < 6; $j++ )
      $istr .= "0";
    
    for ( $j = 0; $j < 6; $j++ )    
      $scheduleArrIntervalMode[$i][$j] = $istr[$j];        
  } 
  
  // return arrays in such sequience:
  
  print "SELF-TEST VER 1.4<br>";
  print "Server name: $serverName<br>";
  
  print "script location: ";
  print dirname(__FILE__)."/";
  print basename(__FILE__)."<br>";
  
  print "lockFileAndGetContents('self-test.php'):";
  print explode("\n",lockFileAndGetContents('self-test.php'))[1];
  print "<br>";  
  
  print "file_get_contents('./self-test.php'):";
  print explode("\n",file_get_contents('./self-test.php'))[1];
  print "<br>";   
  
  print "include('self-test/self-test-content'):";
  include('self-test/self-test-content');
  print "<br>";
  
  print "include('./self-test/self-test-content'):";
  include('./self-test/self-test-content');
  print "<br>";  
  
  print "file_get_contents('self-test/self-test-content'):";
  print file_get_contents('self-test/self-test-content');
  print "<br>";
  
  print "file_get_contents('./self-test/self-test-content'):";
  print file_get_contents('./self-test/self-test-content');
  print "<br>";   
 
  print "Latest data: ";
  print_r($ld);
  print "<br>=======<br>";
      
  $res = printArray('currentMode', $currentMode);
  $res .= printArray('rightNowTemp', $rightNowTemp);
  $res .= printArray('standByTemp', $standByTemp);  
  $res .= printArray('scheduleIntervalsNum', $scheduleIntervalsNum);
  $res .= printArray('roomsName', $roomsName);  
  $res .= printArray('roomsTsensors', $roomsTsensors);
  $res .= printArray('roomsPOutputs', $roomsPOutputs);  
  $res .= printArray('updateSettings', $updateSettings);
  $res .= printArray('followAllHouse', $followAllHouse);
  $res .= printArray('followAllHouse2', $followAllHouse);  
  $res .= printArray2('scheduleArrTime',$scheduleArrTime);
  $res .= printArray2('scheduleArrTemp',$scheduleArrTemp);
  $res .= printArray2('scheduleArrIntervalMode',$scheduleArrIntervalMode);  
  
  
  /*var stateX = print $state; 
  var roomsNum = print $roomsNum; ;
  var lastChatModify = print $lastChatModify;
  var modeXStr = print '$modeXStrPrev';
  var modeXStrPrev = print '$modeXStrPrev';
  var modeXWebUIDate2 = ptin modeXStrPrev;    
  var modeXControllerDate = print $modeXControllerDate;;
  var modeXWebUIDate = print $modeXWebUIDate;
  var weAreLocalServer = print $weAreLocalServer; 
  var requestPassVar = print $requestPass;
  var stateDebugStr = print $stateDebugStr;
  var useRmoteServerVar =  print file_get_contents("settings/remote");
  var thisServerUpdateTime = print file_get_contents("outputs/date.x");*/
  
  $sep2 = "<br>"; 
  $res .= $sep2."relay state: ".$state.$sep2."roomsNum: ".$roomsNum.$sep2."lastChatModify: ".$lastChatModify.$sep2."modeXStrPrev:".$modeXStrPrev.$sep2;
  $res .= "modeXControllerDate: ".$modeXControllerDate.$sep2."modeXWebUIDate: ".$modeXWebUIDate.$sep2;
  $res .= "weAreLocalServer: ".$weAreLocalServer.$sep2."requestPass: ".$requestPass.$sep2."stateDebugStr: ".$stateDebugStr.$sep2;
  
  $res .= "remote: ".file_get_contents("settings/remote").$sep2;
  $res .= "date.x: ".file_get_contents("outputs/date.x");
  
  
  $res .= "<br><br>=== SETTINGS ===<br>";
  
  $file = explode("\n",file_get_contents('include/globalsettings.php'));
  for ( $i = 0; $i < count($file); $i++ )
    $res .= $file[$i]."<br>";
      
  print $res;    
  
  $space = "-";
  $dirlist = getDirectoryTree('.','',$space);
   
  require_once('s-check.php'); 
  //print_r($dirlist);

function getDirectoryTree( $outerDir , $x, $space)
{ 
    $dirs = array_diff( scandir( $outerDir ), Array( ".", ".." ) ); 
    $dir_array = Array(); 
    
    foreach( $dirs as $d )
    { 
        if( is_dir($outerDir."/".$d)  )
        { 
            $oldSpace = $space;            
            print $space."-- DIR -- ".$d."<br>";
            $space .= '-';
            $dir_array[ $d ] = getDirectoryTree( $outerDir."/".$d , $x, $space); 
            $space = $oldSpace;
        }else
        { 
            print $space."-- FILE -- ".$d."<br>";
         if (($x)?ereg($x.'$',$d):1) 
            $dir_array[ $d ] = $d; 
        } 
    } 
    return $dir_array; 
} 


?>