<?php require("./include/globalsettings.php");
  
  $ticket = time();
  $logFileName = "log.txt"; 
  $params = strip_tags($_SERVER['QUERY_STRING']);
  writeResToLog($params);
  
  $weAreLocalServer = false;
  $xdirty = "";
  $seed = 0;
  
  $badCRC = "BAD CRC";
  $str2 = "";  
  $outs = "";
    
  if ( ( isset($_GET['st']) && $_GET['st'] == "0" ) || ( isset($_GET['sh']) && $_GET['sh'] == "0" ) )
    {    
      $fstate = fopen("$path/outputs/x.dirty","r+"); 
      
      if ( $fstate === FALSE )
      {
        
          $res = ":GET x3jK:ERR_X check permission";
          // check permission of files in input folder
          // should be 666
          die($res);
      }
        
        
      if (flock($fstate, LOCK_EX)) // установка исключительной блокировки на запись
      {                   
        //$xdirty = file_get_contents("$path/outputs/x.dirty");
        $xdirty = fread($fstate,1024);   
            
        if ( $xdirty[0] == 1 )
        {
            if ( isset($_GET['rfrsh']) )
            {          
              $xdirty[0] = 0;  // o.dirty                    
            }
            else
            {
              $res = ":GET x3jA:refresh";
              print $res;
              writeResToLog("Ans: ".$res);
              
              flock($fstate, LOCK_UN);
              fclose($fstate);
              return;
            }
        }
      }
      else
      {
        $res = ":GET x3jK:ERR01";
        print $res;
        //print file_get_contents("$path/outputs/x.dirty");
        writeResToLog("Ans: ".$res);
      }  
        
    $allOutsAreZero = true;  
    $outs = clearOutputs();
    
    for ( $i = 0; $i < 17; $i++ )      
      if ($outs[$i] != "0" )
      {
        $allOutsAreZero = false;
        break;
      }      
                 
    //print ":GET x3jK:".$outs;
    
    if ( isset($_GET['cl'] ) && $_GET['cl'] != "0" )  
      $xdirty = "000";    
    
    if ( !$allOutsAreZero )
      $xdirty[1] = 1;  // m.dirty     
      
    ftruncate($fstate, 0); // очищаем файл
    rewind($fstate);
    fwrite($fstate, $xdirty) or die(" set m.dirty error"); // запись
    fflush($fstate);
    flock($fstate, LOCK_UN); // снятие блокировки
    fclose($fstate);                  
  }
   
  if ( isset($_GET['dt']) )
  {
      date_default_timezone_set('UTC');
      
      if ( isset($_GET['code'])) // seams we have data from local server so no decrypt
      {
        $str2 = strip_tags($_GET['dt']); 
        $weAreLocalServer = false;
      }
      else
      {
        $weAreLocalServer = true;        
        require("include/php/functions.php");
      
        $pass = file_get_contents("settings/key");  
        $mesB64Decode = base64_url_decode(strip_tags($_GET['dt']));        
        $encrypted = rc4($pass, $mesB64Decode);
        $encrypted = encRandMy($encrypted);      
        
        $str2 = decodeString($encrypted,0);
      }
      
      $timestamp = time(); // UTC timestamp            
      //$date = gmdate("Y-m-d H:i:s;", $timestamp );
  
      $str = $timestamp.";".$str2;
      
      $fstate = fopen("$path/datalog/latestdata.php","w"); 
      if (flock($fstate, LOCK_EX)) // установка исключительной блокировки на запись
      {
        ftruncate($fstate, 0); // очищаем файл
        fwrite($fstate, $str) or die(" set d.dirty error"); // запись
        fflush($fstate);
        flock($fstate, LOCK_UN); // снятие блокировки         
      }          
      fclose($fstate);
      
      writeResToLog("New data: ".$str);       
        
      $useRemoteServer = file_get_contents("$path/settings/remote");
        
      if ( $remoteHost != "" && $weAreLocalServer )  
      {
        if ( $useRemoteServer == "true" )
        {
          // run this part on local server
          $dt = $str2;//$_GET['dt'];
          
          $st = "";
          $xdirty = file_get_contents("$path/outputs/x.dirty");
          
          //if ( $xdirty == "000" )          
          //  $st = "&clear";
          //else
            $st = "&dirty=$xdirty";
                                   
          $thisServerUpdateTime = file_get_contents("$path/outputs/date.x");
          // send T-vals to remote and get last modify date as answer
          $remoteServerUpdateTime = file_get_contents("http://$remoteHost/$pathFolder/update.php?dt=$dt$st&lmt&date=$thisServerUpdateTime&code=$folderCode");        
          
          //file_put_contents("debug0","$thisServerUpdateTime?$remoteServerUpdateTime",LOCK_EX);
                    
          if ( $thisServerUpdateTime < $remoteServerUpdateTime ) // get data from remote to local
          {
              // DATA <- REMOTE                                  
              $res = file_get_contents("http://$serverName:$localPort/$pathClean/get-remote.php?getnew&update=$thisServerUpdateTime");
              
              if ( $res == "OK" )
                file_put_contents("$path/outputs/date.x",$remoteServerUpdateTime,LOCK_EX);    
                          
              //file_put_contents("debug1","DATA <- REMOTE:$res",LOCK_EX);    
                           
          }
          else              
          if ( $thisServerUpdateTime > $remoteServerUpdateTime )  // send data from local to remote
          {
            // DATA -> REMOTE
            $res = file_get_contents("http://$serverName:$localPort/$pathClean/get-remote.php?sendnew&update=$thisServerUpdateTime");
            //file_put_contents("debug2","DATA -> REMOTE:$res",LOCK_EX);                                                
          } 
        }                                       
      }        
     
      // WE SHOULD NEVER RUN THIS ON LOCAL SERVER!!!   
      if ( !$weAreLocalServer && isset($_GET['lmt']) )
      {
         // run this part on remote server
         $thisServerUpdateTime = file_get_contents("$path/outputs/date.x");                          
         $clearStatus = "OK";   
         
         if ( $thisServerUpdateTime <= $_GET['date'] )
         {
          if ( isset($_GET['dirty']))
          {
            file_put_contents("$path/outputs/x.dirty",$_GET['dirty'],LOCK_EX);
                     
            if ($_GET['dirty'] == 0 )           
              if ( file_put_contents("$path/outputs/st","00000000000000000:clear-mla",LOCK_EX) === FALSE )
                $clearStatus = "ERR";                                           
           }        
         }
         $res = $thisServerUpdateTime;          
         print $res;//.":".$clearStatus;
         writeResToLog("Ans: ".$res);
         return;
      }        
  }
      
  if ( isset($_GET['st']) && $_GET['st'] != "0" )
  {               
      $xouts = decbin(hexdec($_GET['st']));  
      $outs = "";
      $len = strlen($xouts);
      $outsNum = 0;
      for ( $i = 0; $i < $len; $i++ )
      {
        if ( $xouts[$i] == "1" ) // from 0 to 16
        {
          $pin = $len - $i - 1;
          $state = explode(":",file_get_contents("$path/outputs/$pin"));
          $outs = $outs.$state[0].":";
          
          $outsNum++;
        } 
        
        if ( $outsNum > 17 )
          break;         
      }
      
      clearOutputs();  
      $hash = hashme($outs);
      $res = ":GET x3jP:".$outs."crc:$hash:";
      print $res; 
      writeResToLog("Ans: ".$res);
      
      $xdirty[2] = 1;  // d.dirty
      
      ftruncate($fstate, 0); // очищаем файл
      fwrite($fstate, $xdirty) or die(" set d.dirty error"); // запись
      fflush($fstate);
      flock($fstate, LOCK_UN); // снятие блокировки
      fclose($fstate); 
           
      return;                 
  }
  
  if ( isset($_GET['sh']) && $_GET['sh'] != "0" )
  {             
      $xouts = decbin(hexdec($_GET['sh']));
  
      $outs = "";
      $outsNum = 0;
      $len = strlen($xouts);
      for ( $i = 0; $i < $len; $i++ )
      {
        if ( $xouts[$i] == "1" ) // from 0 to 16
        {
          $pin = $len - $i - 1;
          $state = explode(":",file_get_contents("$path/outputs/$pin"));
          $state2 = explode(";",$state[1]);
          
          $st3 = "";
          for ( $j = 0; $j < count($state2)-1;$j++)
          {
            $st3 = $st3.$state2[$j];
            if ( $j != count($state2)-2 )
              $st3 = $st3.";";
          }
            
          $outs = $outs.dechex($pin).";".$st3.":";
          $outsNum++;
        }
        
        if ( $outsNum >= 8 || strlen($outs) > 200 )
          break;        
      }
      
      //clearOutputs();  
      $hash = hashme($outs);
      $res = ":GET x3jS:".$outs."crc:$hash:"; 
      print $res;
      writeResToLog("Ans: ".$res);
      
      $xdirty[2] = 1;  // d.dirty
      
      ftruncate($fstate, 0); // очищаем файл
      fwrite($fstate, $xdirty) or die(" set d.dirty error"); // запись
      fflush($fstate);
      flock($fstate, LOCK_UN); // снятие блокировки
      fclose($fstate); 
      
      // clearOutputs(); // maybe need here
            
      return;   
  }  
  
  /*$allOutsAreZero = true;  
  $outs = clearOutputs();
  
  for ( $i = 0; $i < 17; $i++ )      
    if ($outs[$i] != "0" )
    {
      $allOutsAreZero = false;
      break;
    }*/      
  
  $res = ":GET x3jK:".$outs;            
  print $res; 
  writeResToLog("Ans: ".$res);
  
  /*if ( isset($_GET['cl'] ) && $_GET['cl'] != "0" )  
    $xdirty = "000";    
  
  if ( !$allOutsAreZero )
    $xdirty[1] = 1;  // m.dirty
      
  ftruncate($fstate, 0); // очищаем файл
  fwrite($fstate, $xdirty) or die(" set m.dirty error"); // запись
  fflush($fstate);
  flock($fstate, LOCK_UN); // снятие блокировки
  fclose($fstate); */
            
  return;
  // END -----------------------------
  function clearOutputs()
  {
    global $path;    
    if ( !isset($_GET['cl'] ) )
      return "ERR03";

    $xouts = decbin(hexdec($_GET['cl']));

    $outs = "";
    $outsNum = 0;
    $len = strlen($xouts);
    
    //print $xouts.$len;
    
    $fstate = fopen("$path/outputs/st","r+"); 
    if (flock($fstate, LOCK_EX)) // установка исключительной блокировки на запись
    {
      //error_reporting(E_ALL);
      //$state = file_get_contents("$path/outputs/st");  
      
      $state = fread($fstate,filesize("$path/outputs/st"));     
      //print "Before:".$state."<br>"; 
      //file_put_contents("debug-st","before:".time().$state); 
      for ( $i = 0; $i < $len; $i++ )
      {
        if ( $xouts[$i] == "1" ) // from 0 to 16
        {
          $pin = $len - $i - 1;
          $state[$pin] = 0;
        } 
                          
      }   
      //file_put_contents("debug-st","after:".time().$state); 
      
      ftruncate($fstate, 0); // очищаем файл
      rewind($fstate);
      $num = fwrite($fstate, $state);// or die(" clear error"); // запись
      //print "after:".$state.":$num<br>";
      fflush($fstate);
      flock($fstate, LOCK_UN); // снятие блокировки
    }
    else
      $state = "ERR02";
      
    fclose($fstate);    
    return $state;          
  }
  
  function hashme($str)
  {
    $hash = 0;
    $len = strlen($str);
    if ($len == 0) return $hash;
    for ($i = 0; $i < $len; $i++) 
    {
      $chr   = ord($str[$i]);
      $hash  = (($hash << 5) - $hash) + $chr;
      $hash &= 0xFFFFFFFF;
    }
    return dechex($hash);
  }
  
function decodeString( $str, $ver )
{
  global $badCRC;
  
  if ( $str == $badCRC )
    return $badCRC;
       
  $pcs = explode(";", $str);
 
 
  $Type = $pcs[1];
  $sensR1 = $pcs[2];
  $sensR2 = $pcs[3];
      
  $haveTx = array();
  $Tx = array();
     
  for ( $i = 0; $i < 8; $i++ )
  {
      $bit = $sensR1&0x01;
      $haveTx[7-$i] = $bit;
          
      $bit = $sensR2&0x01;
      $haveTx[15-$i] = $bit;
          
      //print $bit;                 
      $sensR1 = $sensR1 >> 1; 
      $sensR2 = $sensR2 >> 1; 
  }
      
  $iPcs = 0;

  for ( $i = 0; $i < 16; $i++ )
  {
    if ( $haveTx[$i] == 1 )
    {           
        $Tx[$i] = $pcs[4+$iPcs]/10.0;
        $iPcs++;    
    }
    else
      $Tx[$i]="";
  }
      
  $TxStr = "";
      
  for ( $i = 0; $i < 16; $i++ )
    $TxStr = $TxStr.$Tx[$i].";";
       
  $State =$pcs[4+$iPcs++];
  
  $Mx = $pcs[ 4 + $iPcs++ ].";"; 
  $allHousex = $pcs[ 4 + $iPcs++ ].";"; 
                         
  $HxStr = "";  
  for ( $i = 0; $i < 4; $i++ )
    $HxStr = $HxStr."0;";

  return $Type.";".$State.";".$TxStr.$HxStr.$Mx.$allHousex;
}
function writeResToLog($res)
{
  return;
  
  global $logFileName; 
  global $ticket;
  
  $log = fopen($logFileName,"a");
  fwrite($log,"[$ticket]".$res."\n");
  fclose($log);
}
?>