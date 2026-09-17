<?php
function captcha() {
	require_once "includee/encode.php";
//	$string = md5(rand(0, microtime()*1000000));
	$strings = '0123456789';	$i = 0;	$characters = 5;	
	$i = 0;	$characters = 5;	
	$verify_string = '';
	while ($i < $characters){ 
		$verify_string .= substr($strings, mt_rand(0, strlen($strings)-1), 1);	$i++;
	} 
	
	$verify_string_hash = md5($verify_string);
	$key = md5(rand(0,999));
	$encid = urlencode(md5_encrypt($verify_string, $key));
	return array("<img class='secimg' src='/CodeGenerator/?id=$encid&key=$key'>",$verify_string_hash); 	
}
?>