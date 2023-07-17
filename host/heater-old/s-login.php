<?php session_start();
 
  if ( !isset($_POST['login']))
    return;
   
  if ( !isset($_POST['passw']))
    return;
    
  $login = strip_tags($_POST['login']);
  $passw = strip_tags($_POST['passw']);
  
  $pl = explode(";",htmlspecialchars(file_get_contents("settings/passw")));
  
  if ( $login === $pl[0] && md5($passw) === $pl[1] )
  {
    $_SESSION['lg_78D39asxJ'] = $login;
	  $_SESSION['us_3ZkLp68mY'] = md5($passw);
    $_SESSION['auth_82sdfaE'] = "23EiP0SgJWk#";
  
    print hashme($login.$passw);
  }    
  else
    print "FAIL";
    
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