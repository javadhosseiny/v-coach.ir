<?php
require_once "encode.php";
$decid = urldecode(md5_decrypt($_REQUEST['id'], $_REQUEST['key']));
header("Content-type: image/png");
$img = imagecreatetruecolor(55, 25);
$black = imagecolorallocate($img, 255, 255, 255);
$red = imagecolorallocate($img, 236, 134, 72);
$bgline = imagecolorallocate($img, 81, 102, 125);
$bg = imagecolorallocate($img, 131, 152, 175);
imagefill($img, 0, 0, $bg);

imageline($img,0,6,75,6,$bgline);
imageline($img,0,12,75,12,$bgline);
imageline($img,0,18,75,18,$bgline);

imageline($img,7,0,7,25,$bgline);
imageline($img,14,0,14,25,$bgline);
imageline($img,21,0,21,25,$bgline);
imageline($img,28,0,28,25,$bgline);
imageline($img,35,0,35,25,$bgline);
imageline($img,42,0,42,25,$bgline);
imageline($img,49,0,49,25,$bgline);
imageline($img,56,0,56,25,$bgline);
imageline($img,63,0,63,25,$bgline);
imageline($img,70,0,70,25,$bgline);


imageline($img,3,6,70,18,$red);
imagestring($img, 5, 5, 5, $decid, $black);
//imagettftext($img,20,0,10,20,$brown,"/path/arial.ttf",$decid);
//imagepng($img, '', 75);
imagepng($img);
//imagejpeg($img);
imagedestroy($img);
?>