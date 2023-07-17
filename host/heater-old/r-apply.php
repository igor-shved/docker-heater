<?php require_once('s-check.php'); 
 if ( !isset($_GET['vals']) )
  return;
  $valGet = strip_tags($_GET['vals']);
  $vals = explode(";",$valGet);
  $num = $vals[0];  
  $state = 0;  
  
  $xOut = "";
  $xOutFileName = "outputs/names";
  
  if ( file_exists($xOutFileName) )
    $file = fopen($xOutFileName,"r+");
  else
    $file = fopen($xOutFileName,"w");
  
  $hash = hashme(urldecode($_GET['b64']));
  
  $hash2 = strip_tags($_GET['hash']); 
  if ( $hash == $hash2 ) 
  {
    if (flock($file, LOCK_EX)) 
    {
      if ( file_exists($xOutFileName ) )
        //$xOut = file_get_contents($xOutFileName);
        $xOut = fread($file,filesize($xOutFileName));
        
      if ( $xOut == $valGet )
      {
          print "ntc";
          flock($file, LOCK_UN); // снятие блокировки
          fclose($file);            
          return;
      }       
      
      $roomsOld = explode(";",$xOut);
      $roomsNew = explode(";",$valGet);
      
      $roomsOld[0] = $roomsNew[0];
      $roomsOld[$roomsNew[1]+1] = $roomsNew[2];
      
      $len = count($roomsOld); 
      $names = "";
      for ( $i = 0; $i < $len; $i++ )
      {
        $names .= $roomsOld[$i];
        if ( $i < $len - 1 )
          $names .= ";";
      }
        
      
      //$valGet = iconv("UTF-8","cp1251",$valGet);
      
      //$names = $valGet;//."\n".urldecode($_GET['b64']); 
        
      ftruncate($file, 0); // очищаем файл
      rewind($file);
      fwrite($file, $names) or die("Ошибка записи"); // запись
      fflush($file);
      flock($file, LOCK_UN); // снятие блокировки
      fclose($file);              
    }
    else
    {
      print "LOCK_EX: $xOutFileName err";
      fclose($file);      
      flock($fstate, LOCK_UN);
      fclose($fstate);
      return;
    }
  }
  else    
    print "Hash error: $hash!=$hash2<br>$valGet";
    
      
  print $hash;     
  return;
  
  
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
function base64_url_decode($input) {
 return base64_decode(strtr($input, '-_,', '+/='));
}  
?>