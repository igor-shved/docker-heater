<?php require_once('s-check.php');
 
  if ( !isset($_GET['set']) )
    return;
    
  $newPass = md5(strip_tags($_GET['set']));     

  $pl = explode(";",file_get_contents("settings/passw"));
  $newLine = "$pl[0];$newPass";
  
  $res = file_put_contents("settings/passw",$newLine);
  
  if ( $res == strlen($newLine) )
    print hashme(strip_tags($_GET['set']));
    //print $newLine.":".$res;
    
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