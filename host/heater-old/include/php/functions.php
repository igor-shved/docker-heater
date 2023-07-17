<?php

$rand_a = bindec("1110101000");
$rand_c = bindec("10101110111");
$rand_m = bindec("1100111101011");

$badCRC = "BAD CRC";
     
function encRandMy($encrypted)
{
    global $seed;
    global $badCRC;
          
    $str = $encrypted;    
    $pcs = explode(";", $str);
    $seed = $pcs[0];  
    
    $seedLen = strlen($seed+"");       
    $len = strlen($str);
    
    for ( $i = $len-1; $i > 0; $i-- )
      if ( $str[$i] == ";")
        break;
        
    $cslen = $len - $i; 
    $CS2 = hexdec(substr($str,$i+1));
           
    $CS = 0;
    for ( $i = $seedLen+1; $i < $len-$cslen; $i++ )
    {
      $rnd = randMy(1,255);
          
      $ordV = ord($encrypted[$i]);
      $xorV =  $ordV ^ $rnd;
      $str[$i] = chr($xorV);
      $CS += $rnd>>1;
    } 
                
    
    if ( $CS == $CS2 )
      return $str;
    else
      return $badCRC;      
}


function base64_url_decode($input) {return base64_decode(strtr($input, '-_,', '+/='));}

function rc4($key, $str) 
{
	$s = array();
	for ($i = 0; $i < 256; $i++) {
		$s[$i] = $i;
	}
	$j = 0;
	for ($i = 0; $i < 256; $i++) {
		$j = ($j + $s[$i] + ord($key[$i % strlen($key)])) % 256;
		$x = $s[$i];
		$s[$i] = $s[$j];
		$s[$j] = $x;
	}
	$i = 0;
	$j = 0;
	$res = '';
	for ($y = 0; $y < strlen($str); $y++) {
		$i = ($i + 1) % 256;
		$j = ($j + $s[$i]) % 256;
		$x = $s[$i];
		$s[$i] = $s[$j];
		$s[$j] = $x;
		$res .= $str[$y] ^ chr($s[($s[$i] + $s[$j]) % 256]);
	}
	return $res;
}

function randMy($low,$high)
{
    global $rand_a;
    global $rand_c;
    global $rand_m;
        
    global $seed;
    
    $seed=($seed*$rand_a+$rand_c)%$rand_m;
    return intval($low+(($high-$low+1)*$seed)/$rand_m);
}
?>