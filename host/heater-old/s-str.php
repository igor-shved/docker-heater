<?php require_once('s-check.php');
 
  if ( !isset($_GET['code']) || !isset($_GET['str']) || !isset($_GET['hash']) )
  {
    print "er1";
    return;
  }
  
  $code = strip_tags($_GET['code']);
  $str  = strip_tags($_GET['str']);
  $hash = strip_tags($_GET['hash']);
  
  $hash2 = hashme($str);
  
  if ( $hash != $hash2 )
  {
    print "er2";
    return;
  }  
  
  if ( $code == 0 )
  {
    $xOutFileName = "settings/mode.x";
    //file_put_contents("outputs/date.x",time(),LOCK_EX);
  }
  else
  if ( $code == 1 )
  {
    if ( file_put_contents("outputs/reload.x","0",LOCK_EX) === FALSE )
      print "err ".$code;
    else
      print "OK-$code";
      
    return;
  }
  if ( $code == 2 )
  {
    if ( file_put_contents("settings/remote",$str,LOCK_EX) === FALSE )
      print "err ".$code;
    else
      print $hash2;
      
    return;  
  }
  else 
  {
    print "er3";
    return;
  }
    
  if ( !file_exists($xOutFileName ) )
    touch($xOutFileName);  // create empty file
      
  $file = fopen($xOutFileName,"r+");
  
  if (flock($file, LOCK_EX)) 
  {
    ftruncate($file, 0); // очищаем файл
    fwrite($file, $str) or die("Ошибка записи"); // запись
    fflush($file);
    flock($file, LOCK_UN); // снятие блокировки
    fclose($file);
    
    print $hash2;
          
  }
  else
  {
    print "LOCK_EX: $xOutFileName err";
    fclose($file);      
    flock($fstate, LOCK_UN);
    fclose($fstate);
    return;
  }
  
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
?>