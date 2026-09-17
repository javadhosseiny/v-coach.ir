<?php
session_start();
$strings = '123456789';
$i = 0;
$characters = 4;
$code = '';
while ($i < $characters){ 
	$code .= substr($strings, mt_rand(0, strlen($strings)-1), 1);
	$i++;
} 
$_SESSION['captcha'] = $code;	

//echo '<input name="rndval" value="'.md5($code).'" type="hidden">';

$decid = $code;
header("Content-type: image/png");
$img = imagecreatetruecolor(47, 25);
$black = imagecolorallocate($img, 255, 255, 255);
$red = imagecolorallocate($img, 236, 134, 72);
$bgline = imagecolorallocate($img, 81, 102, 125);
$bg = imagecolorallocate($img, 131, 152, 175);
imagefill($img, 0, 0, $bg);

imageline($img,0,6,75,6,$bgline);
imageline($img,0,18,75,18,$bgline);

imageline($img,7,0,7,25,$bgline);
imageline($img,21,0,21,25,$bgline);
imageline($img,35,0,35,25,$bgline);
imageline($img,42,0,42,25,$bgline);
imageline($img,70,0,70,25,$bgline);


imageline($img,3,6,70,18,$red);
imagestring($img, 5, 5, 5, $decid, $black);
imagepng($img);
imagedestroy($img);

	
	
	
	
	
	
?>