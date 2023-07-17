<?php require_once('s-check.php');
 
  $xOutFileName = "settings/chat";
     
  if ( isset($_GET['getdate']) )
  {
    print filemtime($xOutFileName);
    return;
  }
 
  if ( isset($_GET['getfile']) )
  {
    print file_get_contents($xOutFileName);
    return;
  }
 
  if ( !isset($_GET['msg']) )
    return;
  
  $msg = strip_tags($_GET['msg'])."\n";  
  
      
  $file = fopen($xOutFileName,"a+");  
  
  if (flock($file, LOCK_EX)) 
  {    
    //ftruncate($file, 0); // очищаем файл
    fwrite($file, $msg) or die("Ошибка записи"); // запись
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
  } 
  
  print filemtime($xOutFileName);           
  return;
?>