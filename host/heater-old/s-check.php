<?php session_start();
  $auth = true;
  
  if ( file_exists("settings/auth") )    
  	if ( !isset($_SESSION['lg_78D39asxJ'])||!isset($_SESSION['us_3ZkLp68mY'])||!isset($_SESSION['auth_82sdfaE'])||$_SESSION['auth_82sdfaE']!="23EiP0SgJWk#")
    {
      $auth = false;
      header("Location: index.php");      
    }              
?>