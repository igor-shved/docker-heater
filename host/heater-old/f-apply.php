<?php require_once('s-check.php'); 
 if ( !isset($_GET['vals']) )
  return;
  $valGet = strip_tags($_GET['vals']);
  $vals = explode(";",$valGet);
  $num = $vals[0];  
  $state = 0;  
  
  $xOut = "";
  $xOutFileName = "outputs/flags";
  
  if ( file_exists($xOutFileName) )
    $file = fopen($xOutFileName,"r+");
  else
    $file = fopen($xOutFileName,"w");
  
  $hash = hashme($valGet);
  
  $hash2 = strip_tags($_GET['hash']); 
  if ( $hash == $hash2 ) 
  {
    //print "OK";
    //return;
    
    if (flock($file, LOCK_EX)) 
    {
      if ( file_exists($xOutFileName ) )
        $xOut = file_get_contents($xOutFileName);
        
      if ( $xOut == $valGet )
      {
          print "ntc";
          flock($file, LOCK_UN); // снятие блокировки
          fclose($file);           
          return;
      }                           
        
      ftruncate($file, 0); // очищаем файл
      fwrite($file, $valGet) or die("Ошибка записи"); // запись
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