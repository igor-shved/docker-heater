<?php
//require_once('s-check.php');
session_start();
$auth = true;

 if ( !isset($_GET['vals']) )
  return; 
  
  $valGet = strip_tags($_GET['vals']);  
  $vals = explode(";",$valGet);
  $num = $vals[0];  
  $state = 0;
  
  $force = isset($_GET['force']);
  $force2 = isset($_GET['force2']);
  $skipDateX = isset($_GET['skipdatex']);
    
  if ( isset($_GET['mxwebui']) && !$skipDateX )
  {
    $data = strip_tags($_GET['mxwebui']);
    file_put_contents("outputs/date.x",$data,LOCK_EX);
  }
  
  $xOut = "";
  $xOutFileName = "outputs/".$vals[0];
  
  $file = fopen($xOutFileName,"r+");
  
  if (flock($file, LOCK_EX)) 
  {
    if ( file_exists($xOutFileName ) )
    {
      //$xOut = file_get_contents($xOutFileName);
      $xOut = fread($file,filesize($xOutFileName));
    }
      
    //ftruncate($file, 0); // очищаем файл
    //fwrite($file, $valGet) or die("Ошибка записи"); // запись
    //fflush($file);
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
      
      
  if ( $xOut == $valGet && !$force )
  {
      print "ntc";
      return;
  } 
    
  if ( !file_exists("outputs/st") )
  {
    $fd = fopen("outputs/st","w");
    fwrite($fd,"00000000000000000");
    fclose($fd);
  }
    
  //$valGetNew = dechex($num).";".dechex($vals[1]).";".dechex($vals[2]*10).";".dechex($vals[3]).";".dechex($vals[4]).";".dechex($vals[5]).":";
  $valGetNew = dechex($num).";".dechex($vals[1]).";".dechex($vals[2]*10).";".dechex($vals[3]).";".dechex($vals[4]).";0:";
    
  $partsNew = explode(":",$valGet);
  $partsPart2 = explode(";",$partsNew[1]);
  $len = count($partsPart2);
  
  $schedule = "";
  for ($i = 0; $i < $len-2; $i++ )
  {
      $d = $partsPart2[$i];
      //if ( ( $i > 1 || $i == 0 ) && $i != $len - 3 )
      //  $d *= 10;
        
      if (  $i == 2 )
        $d *= 10;        
        
      $schedule = $schedule.dechex($d).";";
  }
  
  $strOldHash = "";
  for ( $i = 0; $i < count($vals)-2; $i++ )
    $strOldHash = $strOldHash.$vals[$i].";";
       
  $oldHash = hashme($strOldHash).";";
  $valGet= $valGetNew.$schedule;  
  
            
  //print "LOCK_EX: $xOutFileName err";
  //return;
   
  // m.dirty - 'outputs/st' был выдан уже контроллеру в ответ
  // d.dirty - 'outputs/x', где x = 0..16 был выдан уже контроллеру в ответ
  
  // o.dirty - данные 'outputs/st' или 'outputs/x' устарели, 
  // контроллер должен начать процедуру запроса данных с начала (refresh)  
  
  // x.dirty = 000 (первый байт = o.dirty, воторой байт m.dirty, третий байт - d.dirty)
   
  $rndStr = generateRandomString(6);
    
  $state = "";
  $fstate = fopen("outputs/st","r+"); 
  if (flock($fstate, LOCK_EX)) // установка исключительной блокировки на запись
  {
    //$state = file_get_contents("outputs/st");   
    $state = fread($fstate,filesize("outputs/st"));        
    
    // write new data
    $file = fopen($xOutFileName,"w");
    if (flock($file, LOCK_EX)) 
    {
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
            
    $mode = $state[$vals[0]];
    
    // need to clear individual flag from WEB-UI
    if ( $force && $mode == 0 )
      $mode = 1;
      
    if ( $force2 )
      $mode = 3;
    
    //if ( $mode == 3 ) // no need to update $mode
    // but have to update x.dirty flag
    
    if ( $xOut != "" )
    {  
      //print "$valGet<br>$xOut<br>";
               
      $partsNew = explode(":",$valGet); // explode params & shedule
      $partsOld = explode(":",$xOut); // explode params & shedule
      
      if ($partsNew[0] != $partsOld[0])
      {
        // if $mode == 0 => mode = 1 // 1 - only temp & states; 
        // if $mode == 1 => mode = 1 
        // if $mode == 2 => mode = 3 // temp & shed
        // so just
        if ( $mode != 1 )
        {
          $mode++;
          //print "a";
        }                      
      }
       
      // compare shedule
      if ( $partsNew[1] != $partsOld[1] )
      {      
        // if $mode == 0 => mode = 2  // only shed
        // if $mode == 1 => mode = 3  // temp & shed 
        // if $mode == 2 => mode = 2
        // so just                              
        if ( $mode != 2 )
        {   
          $mode += 2;
          //print "b";
        }                                   
      } 
      
      if ( $mode > 3 ) // if $mode was 3, we have back it to 3
      {
        $mode = 3;
        //print "c";
      }
      
      // если $mode не равен нулю, то данные изменились (без разницы тут, какие именно)
      // либо расписание, либо режим отопления - это все данные
      // поэтому надо установить флаг устаревших данных, если сервер уже успел их передать
      // контроллеру (d.dirty = 1)
      
      // по идее, после вышеперечисленных операций $mode++ он и так не может быть 
      // не нулевым, поэтому условие вроде лишнее
      
      if ( $mode != 0 )   // d.dirty
      {
        $flag_file = fopen('outputs/x.dirty','r+');
        
        if (flock($flag_file, LOCK_EX)) 
        {
          //$lock_flag = file_get_contents('outputs/x.dirty');
          $lock_flag = fread($flag_file,filesize("outputs/x.dirty"));   
          
          
          //print $lock_flag.":"; 
          
          // o.dirty == 0 && ( d.dirty == 1 || m.dirty == 1 )
          if ( $lock_flag[0] == 0 && ( $lock_flag[2] == 1 || $lock_flag[1] == 1 ) )
          {
            $lock_flag[0] = 1;        // устанавливаем флаг o.dirty - устаревшие данные
            ftruncate($flag_file, 0); // очищаем файл
            rewind($flag_file);
            fwrite($flag_file, $lock_flag) or die("Ошибка записи"); // запись
            fflush($flag_file);            
          }
                                                            
          flock($flag_file, LOCK_UN); // снятие блокировки
          fclose($flag_file);      
        }
        else
        {
          print "LOCK_EX: x.dirty err";
          fclose($flag_file);
          //fclose($file);      
          flock($fstate, LOCK_UN);
          fclose($fstate);
          return;
        }              
      }                                         
    }        
    
    if ( $state[$vals[0]] != $mode )
    {
      $state[$vals[0]] = $mode;            
      $stx = explode(":",$state);
      $state = $stx[0].":".$rndStr;
            
      ftruncate($fstate, 0); // очищаем файл
      rewind($fstate);
      fwrite($fstate, $state) or die("Ошибка записи"); // запись
      fflush($fstate); 
      
      
    } 
    
    //print $state.":".$mode.":";          
    
    flock($fstate, LOCK_UN); // снятие блокировки
    print $oldHash;
  }
  else
    print "LOCK_EX: outputs/st err";
    
  fclose($fstate);
  
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
  
function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}  
?>