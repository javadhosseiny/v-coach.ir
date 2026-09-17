<?php
// function number_format ---> php function for split 3,3,3 تابع برای نمایش اعداد به صورت سه رقم سه رقم
//function _clean($str){
//return is_array($str) ? array_map('_clean', $str) : str_replace('\\', '\\\\', strip_tags(trim(htmlspecialchars((get_magic_quotes_gpc() ? stripslashes($str) : $str), ENT_QUOTES))));
//}

//usage call it somewhere in beginning of your script
//_clean($_POST);
//_clean($_GET);
//_clean($_REQUEST);// and so on..

function fn($number) {
	$persian = array('۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹');
    $english = range(0, 9);
    return str_replace($english, $persian, $number);
}
function FarsiNumber($number) {
	$persian = array('۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹');
    $english = range(0, 9);
    return str_replace($english, $persian, $number);
}
function validatePasswordHigh($password){
        #must contain 8 characters, 1 uppercase, 1 lowercase and 1 number
        return preg_match('/^(?=^.{8,}$)((?=.*[A-Za-z0-9])(?=.*[A-Z])(?=.*[a-z]))^.*$/', $password);
}

function validateUsername($username) {
	#alphabet, digit, @, _ and . are allow. Minimum 4 character. Maximum 50 characters (email address may be more)
	if (empty($username)) return true;
	return preg_match('/^[a-zA-Z\d_@.]{4,30}$/i', $username);
}

function validateIP($IP){ return preg_match('/^(([1-9]?[0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5]).){3}([1-9]?[0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])$/',$IP); }

function validateNumber($number){	
	$number = trim($number);
	if ( empty($number) ) { return true; }
	return preg_match("/^[0-9]*$/", $number); 
}

function validateTime($time){	
	$time = trim($time);
//	if ( empty($time) )  return true; 
	return preg_match("/(2[0-3]|[01][0-9]):([0-5][0-9])/", $time);
}



function validateDate($date){	
	$date = trim($date);
	if ( empty($date) )  return true; 
	return preg_match("/^[0-9]{4}\/[0-1][0-9]\/[0-3][0-9]$/",$date); 
}

function validateDate2($date){	
	$date = trim($date);
	return preg_match("/^[0-9]{4}\/[0-1][0-9]\/[0-3][0-9]$/",$date); 
}

function validatePhone($phone){ 
	$phone = trim($phone);
	if (empty($phone)) return true;
	return preg_match("/^[0-9+\-]*$/", $phone); 
}
function validateURL($website){	
	if (empty($website)) return true;
	return preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i", $website); 
}
function validateEmail($email){	 
	if (empty($email)) return true;
	return filter_var($email, FILTER_VALIDATE_EMAIL); 
}
function validateStrEnglish($str){	
	if (empty($str)) return true;
	return preg_match("/[^A-Za-z0-9]+/", $str); 
}

$database_reportdbactions=-1;
function database_disconnect($connect=null) {
	global $connect;
	if (isset($connect))   {  @mysql_close($connect); $connect=null; } 
}
$database_report=array();
$database_totaltime=0;
function database_query($query) {
    global $database_report,$database_totaltime,$database_reportdbactions;
    list($msec, $sec) = explode(" ", microtime());
    $start=(((integer)($sec))+((float)($msec)));
	//ی و کاف عربی تبدیل می شوند به ی و ک فارسی
	$query = str_replace("ي","ی",str_replace("ك","ک",$query));
    $ret=mysql_query($query) or die(mysql_error());
    list($msec, $sec) = explode(" ", microtime());
    $finish=(((integer)($sec))+((float)($msec)));
    /*if($database_reportdbactions==-1) {
        $database_reportdbactions=get_setting_value('reportdbactions','silent');
    }
    if($database_reportdbactions==1) {
        errorhandle_add('log',htmlentities(preg_replace('/[\r\n\t]{1}/',' ',$query),ENT_QUOTES|ENT_HTML5,'UTF-8').' RUN IN '.(floor(($finish-$start)*1000)).' MILISECONDS.');
    }*/
    $database_totaltime+=floor(($finish-$start)*1000);
   // echo $query;
    //echo mysql_error($connect);
    return $ret;
}
function database_silentquery($query) {
    global $database_report,$database_totaltime,$database_reportdbactions;
    list($msec, $sec) = explode(" ", microtime());
    $start=(((integer)($sec))+((float)($msec)));
    $ret=mysql_query($query);
    list($msec, $sec) = explode(" ", microtime());
    $finish=(((integer)($sec))+((float)($msec)));
    $database_totaltime+=floor(($finish-$start)*1000);
    return $ret;
}

function clean($val,$mysqlreal=0,$striptags=0,$delimeter="'") {
	if ($striptags==1) $val = strip_tags(trim($val)); // erase tag html 
	$val = str_replace('\x00','',str_replace("\x1a","",$val));
	if ($mysqlreal==1) { $val = mysql_real_escape_string($val); } // filtered ---> \x00, \n, \r, \, ', " and \x1a. ---> چک شود برای کد اینتر و ...
    if($delimeter=='"') {
        return str_replace('"','""',str_replace("\\","\\\\",$val));
    } else {
        return str_replace("'","''",str_replace("\\","\\\\",$val));
    }
//  mysql_real_escape_string  --> filtered ---> \x00, \n, \r, \, ', " and \x1a.
// addslashes  ---> filterd ---> '  "
//$symbol = array(',', ')', '(', "'", '"','!', '?', '/', '[', ']', '+', '=', '#', '\x00', '\n', '\r', '\x1a', '&', '$' );
//$Famili = preg_replace("/<.*? >/", "",$_POST['test1'] );  
//$Famili =str_replace( $symbol ,"",$Famili);      
	
}

function bigintval($value) {
  $value = trim($value);
  if (ctype_digit($value)) {
    return $value;
  }
  $value = preg_replace("/[^0-9](.*)$/", '', $value);
  if (ctype_digit($value)) {
    return $value;
  }
  return 0;
}
function ListComboTree($array, $currentParent, $currLevel = 0, $prevLevel = -1) {
	global $groupid, $groupname, $parentid;
	foreach ($array as $categoryId => $category) {
		if ($currentParent == $category['PARENT']) {
			$tetrparent = '  ' . str_repeat('—',($currLevel)*2) . '» ' .$category[NAME];
			$sel='';
			echo "<option value='$category[ID]' $sel > $tetrparent </option>";
			if ($currLevel > $prevLevel) { $prevLevel = $currLevel; }
			$currLevel++; 
			ListComboTree ($array, $categoryId, $currLevel, $prevLevel);
			$currLevel--;               
		}
	}
}   

function CreateTreeView($array, $currentParent, $currLevel = 0, $prevLevel = -1) {
	global $groupid, $groupname, $parentid;
	foreach ($array as $categoryId => $category) {
		if ($currentParent == $category['PARENT']) {
			if ($currLevel > $prevLevel) echo " <ul>  \r\n"; 
			if ($currLevel == $prevLevel) echo " </li>  \r\n";
			echo "<li id='$category[ID]'>".$category['NAME'] ." \r\n";
			if ($currLevel > $prevLevel) { $prevLevel = $currLevel; }
			$currLevel++; 
			CreateTreeView($array, $categoryId, $currLevel, $prevLevel);
			$currLevel--;               
		}
	}
	if ($currLevel == $prevLevel) echo " </li>  </ul> \r\n";
}   

function Farsi2Arabic($query) {
		$query = str_replace("ی","ي",str_replace("ک","ك",$query));
		return $query;

}
?>
