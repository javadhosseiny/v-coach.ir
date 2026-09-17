<?php
// function number_format ---> php function for split 3,3,3 تابع برای نمایش اعداد به صورت سه رقم سه رقم
//function _clean($str){
//return is_array($str) ? array_map('_clean', $str) : str_replace('\\', '\\\\', strip_tags(trim(htmlspecialchars((get_magic_quotes_gpc() ? stripslashes($str) : $str), ENT_QUOTES))));
//}

//usage call it somewhere in beginning of your script
//_clean($_POST);
//_clean($_GET);
//_clean($_REQUEST);// and so on..

function f_n($number) {
	$persian = array('۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹');
    $english = range(0, 9);
    return str_replace($english, $persian, $number);
}
function FarsiNumber($number) {
	$persian = array('۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹');
    $english = range(0, 9);
    return str_replace($english, $persian, $number);
}
function EnglishNumber($number) {
	$persian = array('۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹');
	$arabic  = array('۰', '۱', '۲', '۳', '٤', '٥', '٦', '٧', '۸', '۹');
    $english = range(0, 9);
	$ret = $number;
	$ret = str_replace($persian, $english, $ret);
	$ret = str_replace($arabic, $english, $ret);
    return $ret;
}
function ArabicNumber($number) {
	$persian = array('۰', '۱', '۲', '۳', '٤', '٥', '٦', '٧', '۸', '۹');
    $english = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9');
    return str_replace($english, $persian, $number);
}

function validatePassword($password, $level=1){
#Minimum 6 characters Maximum 30, at least one letter and one number:
if ($level==1) { 

if (mb_strlen($password,'utf-8')<4) return false; else return true;
//این الگورتیم با اون اسم رمز طولانی و ... نمی سازه
$reg = '/^(?=.*[a-z])(?=.*\d)[a-zA-Z\d]{6,90}$/'; 
$password= strtolower($password);
}
#Minimum 8 characters Maximum 30, at least one uppercase letter, one lowercase letter and one number :
if ($level==2) $reg ="/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,90}$/";
#Minimum eight characters, at least one uppercase letter, one lowercase letter, one number and one special character:
if ($level==3) $reg ="/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*\W)[a-zA-Z\d\W]{8,90}$/";
return preg_match($reg, $password);
}

function validatePasswordHigh($password){
        #must contain 8 characters, 1 uppercase, 1 lowercase and 1 number
        return preg_match('/^(?=^.{8,}$)((?=.*[A-Za-z0-9])(?=.*[A-Z])(?=.*[a-z]))^.*$/', $password);
}

function validateUsername($username, $IsEmpty=0) {
	#alphabet, digit, @, _ and . are allow. Minimum 4 character. Maximum 50 characters (email address may be more)
	if ($IsEmpty==0) {
		if (($username=='')) return true;
	}
	return preg_match('/^[a-zA-Z\d_@.]{4,30}$/i', $username);
}

function validateIP($IP){ 
	return preg_match('/^(([1-9]?[0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5]).){3}([1-9]?[0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])$/',$IP); 
}

function validateEmail($email, $IsEmpty=0){	
	if ($IsEmpty==0) {
		
		if (($email=='')) return true;
	}
	return filter_var($email, FILTER_VALIDATE_EMAIL); 
}

function validateNumber($number, $IsEmpty=0){	
	$number = trim($number);
	if ($IsEmpty==0) {
		if ( empty($number) )  return true; 
	}
	return preg_match("/^[0-9]*$/", $number); 
}

function validateTime($time){	
	$time = trim($time);
//	if ( empty($time) )  return true; 
	return preg_match("/(2[0-3]|[01][0-9]):([0-5][0-9])/", $time);
}

function NormalText($input) {
	$find = array('/',';','\\','"',"'");
	$replace = '';
	return str_replace($find,$replace,$input) ;	
}

function validateDate($date){	
	$date = trim($date);
	if ($date=='') return true;
	return preg_match("/^[0-9]{4}\/[0-1][0-9]\/[0-3][0-9]$/",$date); 
}

function validateDate2($date){	
	$date = trim($date);
	return preg_match("/^[0-9]{4}\/[0-1][0-9]\/[0-3][0-9]$/",$date); 
}

function validatePhone($phone){ 
	$phone = trim($phone);
	if (($phone=='')) return true;
	return preg_match("/^[0-9+\-]*$/", $phone); 
}
function validatePhone2($phone){ 
	$phone = trim($phone);
	return preg_match("/^[0-9+\-]*$/", $phone); 
}

function validateURL3($website) {	
	if ($website=='') return true;
	return preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i", $website); 
}
function validateURL2($website) {	
	return preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i", $website); 
}
function validateURL1($website, $IsEmpty=1) {	
	if ($IsEmpty==0) {  if ($website=='') return true;	}
	return (filter_var($website, FILTER_VALIDATE_URL));
}
//-------------------------------------------------------
function validateURL($url,$IsEmpty=1){
	$url = trim($url);
	if($IsEmpty==0 && $url==''){return true;}
	if($url==''){		return false;	}

	return preg_match('~^https?://~i',$url);
	/*
	if(mb_substr($url,0,7,'utf-8')=='http://' || mb_substr($url,0,8,'utf-8')=='https://'){
		return true;
	}

	return false;
	*/
}
//-------------------------------------------------------
function validateStrEnglish($str){	
	if (empty($str)) return true;
	return preg_match("/[^A-Za-z0-9]+/", $str); 
}

function pdo_disconnect($connection=null) {
	global $connection;
	if (isset($connection))   {  $connection=null; } 
}

//---------------------------
function pdo_query($query, $array_prepare='', $show_query=0) {
	global $connection;
	
	//ی و کاف عربی تبدیل می شوند به ی و ک فارسی
//	$query = str_replace("ي","ی",str_replace("ك","ک",$query));
	if ($show_query==1) {
			echo "<div align=left dir=ltr> $query </div>";
			if ($array_prepare<>'')	var_dump($array_prepare);
	}
	try {
//		$ret = $connection->query($query);
		$ret = $connection->prepare($query); 
		if ($array_prepare=='')	{ 	$ret->execute(); } 
			else { $ret->execute($array_prepare); 	}
		
	} catch (PDOException $pe) {
		die("Error in Run sql $query :" . $pe->getMessage());
	}	
    return $ret;
}
 
 
//----------------
function pdo_fetch_array($query) {
	$row=$query->fetch(PDO::FETCH_BOTH);
	return $row;
}

//----------------
function pdo_fetchAll($query) {
	$row=$query->fetchAll(PDO::FETCH_ASSOC);
	return $row;
}
//----------------
function pdo_fetchAllColumn($query) {
	$row=$query->fetchAll(PDO::FETCH_COLUMN);
	return $row;
}
//----------------
function pdo_fetch($query) {
	$row=$query->fetch(PDO::FETCH_ASSOC);
	return $row;
}
//----------------
function pdo_fetch_andis($query) {
	$row=$query->fetch(PDO::FETCH_NUM);
	return $row;
}

//----------------
function pdo_count($query) {
	$num = $query->fetchColumn();  
	return $num;
}
//----------------
function pdo_rowcount($query) {
	$num = $query->rowCount(); 
	return $num;
} 
function pdo_closecursor($query) {
	$ret = $query->closeCursor(); 
	return $ret;
	
}
function pdo_GetAutoIncrementId($TableName) {
	$query = pdo_query("SELECT * FROM information_schema.TABLES WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = '$TableName';");
	$row = pdo_fetch($query);
	return $row['AUTO_INCREMENT'];
}

//---------- ویژه خروجی گرفتن یک ستون از داخل تابع pdo_fetchAll
function getColumnValues($rows, $column){
    return array_column($rows, $column);
}
//---------------------------
function CleanPost($input_var,$striptags=0,$delimeter="'") {
	if (is_array($input_var)) {
		$output_var= array();
		foreach ($input_var as $key => $value) {
//			echo "<div align='left' style='direction:ltr'> key=$key - value=$value</div>";
			$value= Clean($value,$striptags,$delimeter);
//			echo "<div align='left' style='direction:ltr'> key=$key - value=$value</div>";
			$output_var[$key]=$value;
			
		}
		return $output_var;
	}else {
		return Clean($input_var,$striptags,$delimeter);
	}
}	
//---------------------------
function Clean($input_var,$striptags=0,$delimeter="'") {
	if ($striptags==1) $input_var = strip_tags(($input_var)); // erase tag html 
	$input_var = str_replace('\x00','',str_replace("\x1a","",$input_var));
    if($delimeter=='"') {
        return str_replace('"',"'",$input_var);
        return str_replace('"','""',str_replace("\\","\\\\",$input_var));
    } else {
        return str_replace("'","''",str_replace("\\","\\\\",$input_var));
    }
}
//---------------------------
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
//-------------------------
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

function CreateTreeView_Link($array, $currentParent, $currLevel = 0, $prevLevel = -1) {
	global $groupid, $groupname, $parentid;
	foreach ($array as $categoryId => $category) {
		if ($currentParent == $category['PARENT']) {
			if ($currLevel > $prevLevel) echo " <ul>  \r\n"; 
			if ($currLevel == $prevLevel) echo " </li>  \r\n";
			echo "<li id='$category[ID]'><a href='$category[LINK]' >$category[NAME]</a>  \r\n";
			if ($currLevel > $prevLevel) { $prevLevel = $currLevel; }
			$currLevel++; 
			CreateTreeView_Link($array, $categoryId, $currLevel, $prevLevel);
			$currLevel--;               
		}
	}
	if ($currLevel == $prevLevel) echo " </li>  </ul> \r\n";
}   
function FoundChild($array, $idd) {
	$found= false;
	foreach ($array as $categoryId => $category) {
		if ($idd == $category['PARENT']) {
			$found = true;
			break;
		}
	}
	return $found;
}

function ListTreeView_Link($array, $currentParent, $currLevel = 0, $prevLevel = -1, $oldid='') {
	foreach ($array as $categoryId => $category) {
		if ($currentParent == $category['PARENT']) {
			if ($currLevel > $prevLevel) 
				if ($currLevel==0) echo "\r\n <ul> \r\n"; else echo "\r\n<ul class='collapse' id='$oldid'> \r\n";
			if ($currLevel == $prevLevel) echo " </li>  \r\n";
			$idd = 'list'.$category['ID'];
			$OkChild = FoundChild($array,$category['ID']);
			$class1='';			$class2='';
			if ($OkChild) {
				$class1= " class='hasChild'" ; 
				$class2= " data-bs-toggle='collapse' data-bs-target='#$idd' aria-expanded='false' role='button' aria-controls='$idd' " ; 
			}
			echo "<li$class1><a href='$category[LINK]' $class2>$category[NAME]</a>";
			if ($currLevel > $prevLevel) { $prevLevel = $currLevel; }
			$currLevel++; 
			ListTreeView_Link($array, $categoryId, $currLevel, $prevLevel, $idd);
			$currLevel--;               
		}
	}
	if ($currLevel == $prevLevel) echo " </li>\r\n </ul> \r\n";
}   

function Farsi2Arabic($query) {
		$query = str_replace("ی","ي",str_replace("ک","ك",$query));
		return $query;

}

Function CreateTree_AddGroup($array, $currentParent, $currLevel = 0, $prevLevel = -1,$YesId=1) {
	global $groupid, $groupname, $parentid;
	foreach ($array as $categoryId => $category) {
		if ($currentParent == $category['PARENT']) {
			if ($currLevel > $prevLevel) echo " <ul>  \r\n"; 
			if ($currLevel == $prevLevel) echo " </li>  \r\n";
			if ($YesId==1) {
				echo "<li id='$category[ID]'>$category[ID] - ".$category['NAME'] ." \r\n";
			} else {
				echo "<li id='$category[ID]'>".$category['NAME'] ." \r\n";
			}
			if ($currLevel > $prevLevel) { $prevLevel = $currLevel; }
			$currLevel++; 
			CreateTree_AddGroup($array, $categoryId, $currLevel, $prevLevel, $YesId);
			$currLevel--;               
		}
	}
	if ($currLevel == $prevLevel) echo " </li>  </ul> \r\n";
}   

Function createCombbox($array, $currentParent, $currLevel = 0, $prevLevel = -1, $rtl=0) {
global $groupid, $groupname, $parentid;
foreach ($array as $categoryId => $category) {
	if ($currentParent == $category['PARENT']) {
		$tetrparent ='';
		if ($rtl==0) {
			$tetrparent = ' ' . str_repeat('—',($currLevel)*2) ;
			if ($currLevel>0) $tetrparent .= ' » ' ;
			$tetrparent .= $category['NAME'];
		}else{
			$tetrparent = $category['NAME'];
			$tetrparent .= '' . str_repeat('=',($currLevel)*2) ;
			if ($currLevel>0) $tetrparent .= '» ';
		}
		$sel='';
		if ($groupid==$category['ID']) {
			$sel='selected';  
			$groupname = $category['NAME'];
		}
		echo "<option value='".$category['ID']."' $sel > $tetrparent </option>";
		if ($currLevel > $prevLevel) { $prevLevel = $currLevel; }
		$currLevel++; 
		createCombbox($array, $categoryId, $currLevel, $prevLevel, $rtl);
		$currLevel--;               
	}
}
}   

function s_d($pagenumber, $vartool) {
	if (gettype($pagenumber)!='integer') $pagenumber=intval($pagenumber);
	$pagenumber = "$pagenumber";
	$tool1 = strlen($pagenumber);
	if ( ($tool1<0) or ($tool1>$vartool) ) return str_repeat('0',$varool);
	$tmp = str_repeat('0',($vartool-$tool1)) . $pagenumber;
	return $tmp;
}
function read_group_with_link($SqlCommand) {
	$result=pdo_query($SqlCommand);
	$arrayCategories = array();
	$oklist=0;
	while ($row_row=$result->fetch(PDO::FETCH_ASSOC))  {
		$oklist=1;
		$arrayCategories[$row_row['id_main']] = 
		array(
			"ID" => $row_row['id_main'],"PARENT" => $row_row['parent'],"NAME" => $row_row['name'], "LINK"=>'/archive/'.$row_row['id_main'] 
		);   
	}
	return $arrayCategories;
}

function read_group($SqlCommand,$idmain='id_main') {
	$result=pdo_query($SqlCommand);
	$arrayCategories = array();
	$oklist=0;
	while ($row_row=$result->fetch(PDO::FETCH_ASSOC))  {
		$oklist=1;
		$arrayCategories[$row_row[$idmain]] = 
		array(
			"ID" => $row_row[$idmain],"PARENT" => $row_row['parent'], "NAME" =>  $row_row['name']
		);   
	}
	return $arrayCategories;
}
//----------------------------------------
function get_parent_id_group($id_temp, $bankname_temp) {
	$ListId = explode(',',$id_temp);
	$ListId =array_filter($ListId);	
	$JamKol = count($ListId);
	$ListJavab = array();
	$javab=0;
	$ListReturn='';
	if ($JamKol==0) return $ListReturn;
	foreach ($ListId as $key => $value) {
		$idd = $value;
		while (true) {
			$result=pdo_query("select id_main,parent from $bankname_temp where id_main=$idd ",'',0 );
			$row2    = $result->fetch(PDO::FETCH_ASSOC);					
			if ($row2!='') {
				$ListJavab[$javab] = $idd;
				$javab++;
				$idd= $row2['parent'];
				if ($idd==0) break;
			}
		}
	}
	if ($javab>0) {
		$ListJavab= array_unique($ListJavab);
		$ListReturn=implode(',',$ListJavab);
	}
	return $ListReturn;
}
//--------------------------------
function encrypt($string, $key) {
  $result = '';
  for($i=0; $i<strlen($string); $i++) {
    $char = substr($string, $i, 1);
    $keychar = substr($key, ($i % strlen($key))-1, 1);
    $char = chr(ord($char)+ord($keychar));
    $result.=$char;
  }
 
  return base64_encode($result);
}
//--------------------------------
function decrypt($string, $key) {
  $result = '';
  $string = base64_decode($string);
 
  for($i=0; $i<strlen($string); $i++) {
    $char = substr($string, $i, 1);
    $keychar = substr($key, ($i % strlen($key))-1, 1);
    $char = chr(ord($char)-ord($keychar));
    $result.=$char;
  }
 
  return $result;
}
//--------------------------------
function EncryptDecrypt($input_text, $type_code, $salt_text='javad2839') {
	$options = 0; 
	$ciphering = "AES-128-CTR"; 
	$encryption_iv = '1234567891011121'; // Non-NULL Initialization Vector for encryption 
	$iv_length = openssl_cipher_iv_length($ciphering); 
	if ($type_code==1) { 	// code text
		// Use openssl_encrypt() function to encrypt the data 
		$encryption = openssl_encrypt($input_text, $ciphering, $salt_text, 
			$options, $encryption_iv); 
		return $encryption;
	}else{					// decode text
	  // Use openssl_decrypt() function to decrypt the data 
		$decryption=openssl_decrypt ($input_text, $ciphering,$salt_text,
				 $options, $encryption_iv); 
		return $decryption; 
	}
}
//--------------------------------
function EncryptDecrypt_base64($input_text, $type_code) {
	if ($type_code==1) { 	// code text
		return  base64_encode(base64_encode(base64_encode($input_text)));
	}else {
		return base64_decode(base64_decode(base64_decode($input_text)));
	}
}
//--------------------------------
function base62_encode($num, $sec=1) {
	if ($sec>0) {
		$SECRET = 40000;	
		$num = $num ^ $SECRET;
	}
    $chars = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $base = strlen($chars);
    $str = '';

    while ($num > 0) {
        $str = $chars[$num % $base] . $str;
        $num = floor($num / $base);
    }

    return $str;
}
//--------------------------------------------
function base62_decode($str, $sec=1) {
    $chars = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $base = strlen($chars);
    $num = 0;

    for ($i = 0; $i < strlen($str); $i++) {
        $num = $num * $base + strpos($chars, $str[$i]);
    }
	if ($sec>0) {
		$SECRET = 40000;	
		$num = $num ^ $SECRET;
	}

    return $num;
}
//--------------------------------

function captcha2($style='',$characters=5) {
	require_once "encode.php";
	$strings = '0123456789';	
	$i = 0;	
	$verify_string = '';
	while ($i < $characters){ 
		$verify_string .= substr($strings, mt_rand(0, strlen($strings)-1), 1);	$i++;
	} 
	$verify_string_hash = md5($verify_string);
	$key = md5(rand(0,999));
	if ($style=='') $style='width:60px;height:35px;';
	$encid = urlencode(md5_encrypt($verify_string, $key));
//	$TempImage = image_captcha($encid,$key);
	$TempImage = "/includee/secureCodeGenerator.php?id=$encid&key=$key";
	echo "<img class='img-thumbnail'  src='$TempImage' align='right' style='$style'> ";
	echo "<input name='rndval' value='$key' type='hidden'>";
	return $verify_string_hash;
}
//--------------------------------
function SendEmail($From,$To,$Subject,$Message,$FromName='',$ShowError=1) {
	$server_name = str_replace("www.", "", strtolower($_SERVER['SERVER_NAME']));
	if ($From=='') {		$From = "info@".$server_name;	}
	if ($Subject=='') {	$Subject = " $server_name پاسخ به مطلب شما از طرف سایت ";	}
	require_once("class.phpmailer.php"); 
	$mail=new PHPMailer();
	$mail->CharSet = 'UTF-8';
	$mail->From=$From;	
	$mail->FromName=$FromName; 	
	$mail->AddAddress($To, "");	
	$mail->Subject    = $Subject;
	$mail->IsHTML(true);	
	$mail->IsMail();		
	$mail->Body = $Message;
	if($mail->Send()) {	
		return true;
	} else {
		if ($ShowError==1) { echo '<!-- '. htmlentities($mail->ErrorInfo) . ' --> '; }
		return false;
	}
}
//--------------------------------
//function CoinSms($type,$SmsUser, $SmsPass) {
function CoinSms($row) {
	if ( (!isset($row)) or (!is_array($row)) or (count($row)==0) ) {
		return array(-1,"اطلاعات سامانه ارسال پیامک ، صحیح نمی باشد");
	}
	$type 		= $row['smstype'];
	$SmsUser	= $row['smsuser'];
	$SmsPass	= $row['smspass'];
	
	if ($type==1) {
		$SmsWSDL  = 'http://www.tsms.ir/soapWSDL/?wsdl';
		try {
			$api = new SoapClient($SmsWSDL);
			$temp = $api->UserInfo($SmsUser,$SmsPass);
			$AboutSms= $temp[0];
			if (property_exists($AboutSms, 'credit')) { 
				$price = $AboutSms->credit;
				return array(1, "مبلغ اعتبار: [".number_format($price)."] ریال",$price);
			} else { return array(-1, "نام کاربری و یا اسم رمز اشتباه است"); }
		}
		catch(Exception $e) { 
			$SendSms = false;
			$ErrorMsg = "خطا به دلیل". $e->getMessage(); 
			$ErrorMsg = str_replace("\n","",(str_replace("'","`", str_replace('"',"`", $ErrorMsg))));
			return array(-1,$ErrorMsg);
		} 	
			
	}
	if ($type==2) {
		$url = "https://ippanel.com/services.jspd";
		$param = array('uname'=>$SmsUser, 'pass'=>$SmsPass, 'op'=>'credit');
					
		$handler = curl_init($url);             
		curl_setopt($handler, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($handler, CURLOPT_POSTFIELDS, $param);                       
		curl_setopt($handler, CURLOPT_RETURNTRANSFER, true);
		$response2 = curl_exec($handler);
		
		$response2 = json_decode($response2);
		if (is_null($response2[0]))  return array(-1, "هیچ پاسخی از سمت سرور ippanel دریافت نشد"); 
		if ($response2[0]==962) return array(-1, "نام کاربری و یا اسم رمز صحیح نمی باشد"); 
		if ($response2[0]==0)return array(1, "مبلغ اعتبار: [".number_format($response2[1])."] ریال", $response2[1]);
		return array($response2[0],  $response2[1]);
		
	}
	if ($type==3) {
		$url = "https://panel.signalads.com/webservice/url/send.php";
		$param = array('username'=>$SmsUser, 'password'=>md5($SmsPass), 'method'=>'getcredit');
		$handler = curl_init($url);             
		curl_setopt($handler, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($handler, CURLOPT_POSTFIELDS, $param);                       
		curl_setopt($handler, CURLOPT_RETURNTRANSFER, true);
		$response = curl_exec($handler);
		$curl_error = @curl_error($handler);
		curl_close($handler);
		if ($curl_error) {   
			return array(-1, "خطای cURL در مرحله دریافت مسیر آپلود: $curl_error"); 
		}
		if (($response === false) or (is_null($response)) ) {
			return array(-1, "هیچ پاسخی از سمت سرور segnalads دریافت نشد"); 
		}
		$ErrorMsg = '';
		switch ($response) {
			case 0:		$ErrorMsg = "نام کاربری و یا اسم رمز اشتباه است"; break;
			case 5:		$ErrorMsg = "پنل کاربری غیرفعال است"; break;
			case 6:		$ErrorMsg = "پنل کاربری منقضی شده است"; break;
			case 22: 	$ErrorMsg = "تمامی ورودی ها به درستی وارد نشده است"; break;
			case 33:	$ErrorMsg = "نوع متغیر متد درست وارد نشده است"; break;
		}
		
		if ($ErrorMsg !='')  return array(-1,$ErrorMsg); 
		$final_price = floatval($response);
		return array(1, "میزان اعتبار: [".($final_price)."] پیامک "); // معلوم نشد تومان هست یا پیامک
	}
	//مدل دوم برای سامانه سیگنال با استفاده از apikey
	if ($type==33) {
		$apikey = $row['apikey'];
		if ($apikey=='') { return array(-1,'برای این سامانه کد توکن (apikey) تعریف نشده است ');}
		$url = "https://transmitor.signalads.com/api_v1/user/credit";
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
		curl_setopt($ch, CURLOPT_HTTPHEADER, ["Authorization: Bearer $apikey"]);
		$response = curl_exec($ch);
		$curl_error = @curl_error($ch);
		curl_close($ch);
		if ($curl_error) {   
			return array(-1, "خطای cURL در مرحله دریافت مسیر آپلود: $curl_error"); 
		}
		if (($response === false) or (is_null($response)) ) {
			return array(-1, "هیچ پاسخی از سمت سرور segnalads دریافت نشد"); 
		}
		$responseArray = json_decode($response, true);		
		if (is_array($responseArray)) {
			$final_price = floatval($responseArray['data']['credit']);
			return array(1, "میزان اعتبار: [".($final_price)."] پیامک "); // معلوم نشد تومان هست یا پیامک
		}else{
			return array(-1, "پاسخ صحیحی از سامانه سیگنال دریافت نشد"); // معلوم نشد تومان هست یا پیامک
		}
		
	}
	return array(-1,'Error');
}
//--------------------------------
function SendSms($ToListMobile,$SmsMessage) {
	
	global $SmsUser, $SmsUser, $SmsPass, $SmsNumber, $SmsWSDL , $SmsUrlSend;
	global $row_setting;
	$SmsUser     = $row_setting['smsuser'];
	$SmsPass     = $row_setting['smspassword'];
	$SmsNumber   = $row_setting['smsnumber'];
	
	if ( ($SmsUser=='') or ($SmsPass=='') or ($SmsNumber=='') ) {
		return array(-100,"تنظیمات اولیه اکانت کاربری سامانه پیامکی صحیح نمی باشد");
	}
	$SmsWSDL     = 'http://www.tsms.ir/soapWSDL/?wsdl';
	$SmsUrlSend  = "http://tsms.ir/url/tsmshttp.php?from=$SmsNumber&username=$SmsUser&password=$SmsPass&to=";  //&to=$to
	$SmsUrlDeliveri  = "http://tsms.ir/url/tsmshttp.php?from=$SmsNumber&username=$SmsUser&password=$SmsPass&deliver20=";//&deliver20=$smsid';
	$SmsUrlGetXML    = "http://www.tsms.ir/url/recived_sms_xml.php?username=$SmsUser&password=$SmsPass&from=$SmsNumber";
	$SmsUrlGetCredit = "http://tsms.ir/url/tsmshttp.php?from=$SmsNumber&username=$SmsUser&password=$SmsPass&credit=what";
	
	//$ToListMobile = "09121536501,09121536500,09121128345";
	if ($ToListMobile=='') return array(-1,"شماره دریافت کننده ثبت نشده است");
	try {
		$api = new SoapClient($SmsWSDL);
		
		$AboutSms = $api->UserInfo($SmsUser,$SmsPass);
//		var_dump($AboutSms);
//		$price = $AboutSms[0]->credit;
//		echo "AboutSms = $api->UserInfo($SmsUser,$SmsPass);";		var_dump($AboutSms);		echo "price=$price";
//		$price = intval($price);	if ($price<=0) return array(-1,"اعتبار کافی جهت ارسال پیامک وجود ندارد");
		$mclass='';		
		$messagid=rand();
		$IdSendSms=$api->sendSmsGroup($SmsUser,$SmsPass,$SmsNumber,$ToListMobile,$SmsMessage,$mclass,$messagid);
		$IdSendSms= @$IdSendSms[0];
		if ($IdSendSms<0) {
			$ErrorMsg = '';
			switch ($IdSendSms) {
				case -1:	$ErrorMsg = "خطاي ناشناخته دوباره تلاش نمایید"; break;
				case -3:	$ErrorMsg = "خطاي ناشناخته دوباره تلاش نمایید"; break;
				case -5:	$ErrorMsg = "متنی جهت ارسال وجود ندارد"; break;
				case -6: 	$ErrorMsg = "تلفن همراه ناصحیح می باشد"; break;
				case -8:	$ErrorMsg = "نام کاربري و یا کلمه عبور نادرست می باشد"; break;
				case -11:	$ErrorMsg = "عدم وجود شماره پیامک"; break;
				case -12:	$ErrorMsg = "تعداد درخواستی بیش از حد مجاز"; break;
				case -13:	$ErrorMsg = "ورودي نا معتبر"; break;
				case -14:	$ErrorMsg = "اعتبار کافی نمی باشد"; break;
				case -15:	$ErrorMsg = "ورودي نا معتبر"; break;
				case -16:	$ErrorMsg = "آرایه هاي ارسال پیام متناظر نمی باشد"; break;
				case -17:	$ErrorMsg = "تعداد ارسال پیامک بیشتر از حد مجاز می باشد"; break;
				case -24:	$ErrorMsg = "پیامی به سرور ارسال نشده است"; break;
				case -37:	$ErrorMsg = "تعداد تلفنها بیشتر از حد مجاز ارسال گروهی می باشد"; break;
				case -38:	$ErrorMsg = "تعداد تلفنها براي دریافت دلیوري بیشتر از 100 عدد می باشد"; break;
				case -42:	$ErrorMsg = "موبایل وجود ندارد"; break;
				case -43:	$ErrorMsg = "پیامی براي دریافت وجود ندارد"; break;
				default:
					$ErrorMsg ="خطای غیرمعمول";

			}
			
			$ErrorMsg = "$ErrorMsg - کد خطا: $IdSendSms";
			if ($ErrorMsg !='')  return array(-1,$ErrorMsg); 
			
		}
		$SmsReturnCode = $api-> WsdlCheckSend($SmsUser,$SmsPass,$messagid);
		$SmsReturnCode = $SmsReturnCode[0];
		if ($SmsReturnCode<0) { 
			return array(-1,"سیستم به دلیل عدم اتصال به سرور سامانه پیامک، امکان ارسال پیامک ها را نداشته لطفا پس از چند لحظه مجددا ارسال نمایید");
		}
		$SendSms = True;
		return array(0,'پیامک با موفقیت ارسال شد');
	}
	catch(Exception $e) { 
		$SendSms = false;
		$ErrorMsg = "\n خطا به دلیل". $e->getMessage(); 
		return array(-1,$ErrorMsg);
	} 	
}	
//-------------------------------------------------------------
//--------------------------------
function SendSmsTsms($ToListMobile, $SmsMessage, $row) {
	

	if ( (!isset($row)) or (!is_array($row)) or (count($row)==0) ) {
		return array(-1,"اطلاعات سامانه ارسال پیامک ، صحیح نمی باشد",0);
	}
	$smsuser	= $row['smsuser'];
	$smspass	= ($row['smspass']);
	$smsnumber	= $row['smsnumber'];
	//example for $ToListMobile = array('09121536501','09121536500','09121128345');
	if ($ToListMobile=='') 	return array(-1,"شماره دریافت کننده ثبت نشده است",0);
	if ($SmsMessage=='') 	return array(-1,"متن پیامک خالی است",0);
	if ( ($smsuser=='') or ($smspass=='') or ($smsnumber=='') ) {
		return array(-100,"تنظیمات اولیه اکانت کاربری سامانه پیامکی صحیح نمی باشد",0);
	}
	
	$url = "http://tsms.ir/url/tsmshttp.php";
	$params = [
		'username'  => $smsuser,
		'password'  => $smspass,
		'from'      => $smsnumber,
		'to'        => $ToListMobile,
		'message'   => $SmsMessage
	];

	$query = http_build_query($params);
	$finalUrl = $url . '?' . $query;
	$handler = curl_init($finalUrl);
	curl_setopt($handler, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($handler, CURLOPT_FOLLOWLOCATION, true);
	$response = curl_exec($handler);
	$curl_error = @curl_error($handler);
	curl_close($handler);
	if ($curl_error) {   
		return array(-1," خطای cURL در مرحله دریافت مسیر آپلود: $curl_error ",0); 
	}
	if (($response === false) or (is_null($response)) ) {
		return array(-1," هیچ پاسخی از سمت سرور سامانه tsms دریافت نشد ",0); 
	}
	if ((is_null($response)) or ($response=='') ) {
		return array(-1," پاسخ درستی از سمت سامانه tsms دریافت نشد",0); 		
	}
	$ErrorMsg = '';
	switch ($response) {
		case 1:	$ErrorMsg = "ﺧﻄﺎ در ﺳﺮوﻫﺎي ﻃﻮﺑی اس ام اس"; break;
		case 2:	$ErrorMsg = "در ارﺳﺎلUDHدار ﻣﺘﻦ ﺑﯿﺶ از ﯾک پﯿﺎﻣک اﺳﺖ"; break;
		case 3:	$ErrorMsg = "ﺷﻤﺎره ﺗﻠﻔﻦ ﻫﻤﺮاه ارﺳﺎﻟی اﺷﺘﺒﺎه اﺳﺖ"; break;
		case 4: $ErrorMsg = "ﻣﺘﻐﯿﯿﺮ ﻫﺎي ارﺳﺎل ﺑﺎ ﺧﻄﺎ ﻣﻮاﺟﻪ ﻣی ﺑﺎﺷﺪ"; break;
		case 5:	$ErrorMsg = "ﻣﺘﻦ ارﺳﺎﻟی ﺧﺎﻟی ﻣی ﺑﺎﺷﺪ"; break;
		case 6:	$ErrorMsg = "ﺷﻤﺎره ﺗﻠﻔﻦ ﻫﻤﺮاه ﺧﺎﻟی ﻣی ﺑﺎﺷﺪ"; break;
		case 7:	$ErrorMsg = "نام کاربری و یا کلمه عبور اشتباه است"; break;
		case 8:	$ErrorMsg = "خطا در سرور پیامک، لطفا دوباره تلاش کنید"; break;
	}
	if ($ErrorMsg !='') {
		$ErrorMsg = "$ErrorMsg - کد خطا: $response";
		 return array(-1,$ErrorMsg,0); 
	}
	return array(0,'پیامک با موفقیت ارسال شد', $response);
}	
//-------------------------------------------------------------
//--------------------------------
function CreateIranPanel($SmsUser, $SmsPass) {
	$url = "https://ippanel.com/services.jspd";
	$param = array('uname'=>$SmsUser, 'pass'=>$SmsPass, 'op'=>'credit');
	$handler = curl_init($url);             
	curl_setopt($handler, CURLOPT_CUSTOMREQUEST, "POST");
	curl_setopt($handler, CURLOPT_POSTFIELDS, $param);                       
	curl_setopt($handler, CURLOPT_RETURNTRANSFER, true);
	$response2 = curl_exec($handler);
	$response2 = json_decode($response2);
	$res_code = $response2[0];
	$res_data = $response2[1];
	if ($res_code==0)	return intval($res_data);
		else return '-999999';
}


//--------------------------------
function SendSmsIranPanel($ToListMobile, $SmsMessage, $SmsUser, $SmsPass, $SmsNumber) {
	//example for $ToListMobile = array('09121536501','09121536500','09121128345');
	if ($ToListMobile=='') return array(-1,"شماره دریافت کننده ثبت نشده است",0);
	if ($SmsMessage=='') return array(-1,"متن پیامک خالی است",0);
	if ( ($SmsUser=='') or ($SmsPass=='') or ($SmsNumber=='')) return array(-1,"تنظیمات اتصال به سامانه پیامکی خالی می باشد",0);
	if (!(is_array($ToListMobile)))  $ToListMobile = array($ToListMobile);
	$url = "https://ippanel.com/services.jspd";

//	$price = CreateIranPanel($SmsUser,$SmsPass);
//	if ($price=='-999999') array(-1,"اتصال به پنل جهت دریافت اعتبار ممکن نبود");
//	if ($price<=0) array(-1,"اعتبار کافی جهت ارسال پیامک وجود ندارد");

	$param = array('uname'=>$SmsUser, 'pass'=>$SmsPass, 'from'=>$SmsNumber, 'message'=>$SmsMessage, 'to'=>json_encode($ToListMobile), 'op'=>'send');
	//VAR_DUMP($param);
	$handler = curl_init($url);             
	curl_setopt($handler, CURLOPT_CUSTOMREQUEST, "POST");
	curl_setopt($handler, CURLOPT_POSTFIELDS, $param);                       
	curl_setopt($handler, CURLOPT_RETURNTRANSFER, true);
	$response2 = curl_exec($handler);
	if (!$response2) { return array(-1,'پاسخی از سمت سامانه پیامکی دریافت نشد',0); }
	$response2 = @json_decode($response2);
	if (!is_array($response2)) {return array(-1,'پاسخی از سمت سامانه پیامکی دریافت نشد2',0); }
	$res_code = $response2[0];
	$res_data = $response2[1];
	if ($res_code==0) { return array(0,'پیامک با موفقیت ارسال شد',$res_data); }
	$ErrorMsg = '';
	switch ($res_code) {
		case 1:		$ErrorMsg = "متن پیام خالی است"; break;
		case 2:		$ErrorMsg = "کاربر محدود شده است"; break;
		case 3:		$ErrorMsg = "خط ارسال کننده به مالک متعلق نیست"; break;
		case 4: 	$ErrorMsg = "گیرندگان خالی است"; break;
		case 5:		$ErrorMsg = "اعتبار کافی نیست"; break;
		case 7:		$ErrorMsg = "خط موردنظر برای ارسال انبوه مناسب نیست"; break;
		case 9:		$ErrorMsg = "خط موردنظر در این ساعت امکان ارسال ندارد"; break;
		case 98:	$ErrorMsg = "حداکثر تعداد گیرندگان رعایت نشده است"; break;
		case 99:	$ErrorMsg = "اپراتور خط ارسالی قطع می باشد"; break;
		default:
			$ErrorMsg =$res_data;

	}
	
	$ErrorMsg = "$ErrorMsg - کد خطا: $res_code";
	if ($ErrorMsg !='')  return array(-1,$ErrorMsg,$res_data); 
}

//----------------------------
function SendSmsWithPattern($apikey, $SmsPattern, $SmsSender, $SmsReciver, $SmsVariable, $SmsCode) {
	//succesfully
//Array ( [status] => OK [code] => 200 [error_message] => [data] => Array ( [message_id] => 1200416238 ) ) 	
// error
//Array ( [status] => Forbidden [code] => 403 [error_message] => Number not assign1 [data] => ) 
	if ( ($apikey=='') or ($SmsPattern=='') or ($SmsSender=='') or ($SmsReciver=='') or ($SmsVariable=='') or ($SmsCode=='')  ) return array(-1,"یکی از پارامترهای ارسال اطلاعات از طریق پترن خالی می باشد",0);
	$url = "https://api2.ippanel.com/api/v1/sms/pattern/normal/send";
	$variable = ["$SmsVariable" => $SmsCode ];
	$data = json_encode(['code' => $SmsPattern, 'sender' => $SmsSender, 'recipient' => $SmsReciver, 'variable' => $variable	]);

	$handler = curl_init($url);

	curl_setopt($handler, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($handler, CURLOPT_POST, true);
	curl_setopt($handler, CURLOPT_POSTFIELDS, $data);
	curl_setopt($handler, CURLOPT_HTTPHEADER, [
		'accept: */*', 'apikey: ' . $apikey, 'Content-Type: application/json'	]);
	$response = curl_exec($handler);
	if (curl_errno($handler)) { return array(-1,curl_error($handler)); }
	if (!$response) { return array(-1,'پاسخی از سمت سامانه پیامکی دریافت نشد'); }
    $response2 = @json_decode($response, true);
	if (!is_array($response2)) {return array(-1,'پاسخی از سمت سامانه پیامکی دریافت نشد2'); }
	$code 			= isset($response2['code']) ? $response2['code'] : 0;
	$error_message 	= isset($response2['error_message']) ? $response2['error_message'] : '';
	$message_id 	= isset($response2['message_id']) ? $response2['message_id'] : 0;
	if ($code==200) { return array(0,'پیامک با موفقیت ارسال شد',$message_id); }

	$ErrorMsg = "$error_message - کد خطا: $code";
	return array(-1,$ErrorMsg,$res_data); 
}
//-------------------------------------------------------------------
function getCreditIranPanel($SmsUser, $SmsPass) {

		$url = "https://ippanel.com/services.jspd";
		$param = array('uname'=>$SmsUser, 'pass'=>$SmsPass, 'op'=>'credit');
					
		$handler = curl_init($url);             
		curl_setopt($handler, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($handler, CURLOPT_POSTFIELDS, $param);                       
		curl_setopt($handler, CURLOPT_RETURNTRANSFER, true);
		$response2 = curl_exec($handler);
		
		$response2 = json_decode($response2);
		$res_code = $response2[0];
		$res_data = $response2[1];
		return array($response2[0], $response2[1]);
	
}
//------------------------------------
function SendSmsSignalOld($ToListMobile, $SmsMessage, $smsuser, $smspass, $smsnumber  ) {
	if ($ToListMobile=='') 	return array(-1,"شماره دریافت کننده ثبت نشده است",0);
	if ($SmsMessage=='') 	return array(-1,"متن پیامک خالی است",0);
	
	$smspass	= md5($smspass);
	

	if ((is_array($ToListMobile)))  $ToListMobile = explode(',',$ToListMobile);
	if (mb_substr($SmsMessage,-5)!='لغو11') { 	$SmsMessage .= " لغو11";}


//https://www.yourdomain.com/webservice/url/send.php?method=sendsms&format=json&from=10001234&to=9121234567,9127654321&text=example&type=0&username=test&password=123456
	$url = "https://panel.signalads.com/webservice/url/send.php";
	$params = [
		'method'	=> 'sendsms',
		'format'	=> 'html',
		'from'		=> $smsnumber,
		'to'		=> $ToListMobile,
		'text'		=> $SmsMessage,
		'type'		=> '0',
		'username'	=> $smsuser,
		'password'	=> $smspass,
		
	];
//	var_dump($params);
//	$jsonData = json_encode($params);
	$handler = curl_init($url);
	curl_setopt($handler, CURLOPT_CUSTOMREQUEST, "POST");
	curl_setopt($handler, CURLOPT_POSTFIELDS, $params);                       
	curl_setopt($handler, CURLOPT_RETURNTRANSFER, true);
	$response = curl_exec($handler);
	$curl_error = @curl_error($handler);
	curl_close($handler);
	if ($curl_error) {   
		return array(-1, "خطای cURL در مرحله دریافت مسیر آپلود: $curl_error",0); 
	}
	if (($response === false) or (is_null($response)) ) {
		return array(-1, "هیچ پاسخی از سمت سرور segnalads دریافت نشد",0); 
	}
	$ErrorMsg = '';
	switch ($response) {
		case 0:		$ErrorMsg = "نام کاربری و یا اسم رمز اشتباه است"; break;
		case 1:		$ErrorMsg = "اعتبار کافی برای ارسال نمی باشد"; break;
		case 2:		$ErrorMsg = "شماره اختصاصی فرستنده معتبر نمی باشد"; break;
		case 4:		$ErrorMsg = "امکان ارسال غیرفعال می باشد"; break;
		case 5:		$ErrorMsg = "پنل کاربری غیرفعال است"; break;
		case 6:		$ErrorMsg = "پنل کاربری منقضی شده است"; break;
		case 7:		$ErrorMsg = "متن پیام خالی می باشد"; break;
		case 9:		$ErrorMsg = "هیچ گیرنده ای مشخص نشده است"; break;
		case 10:	$ErrorMsg = "محدودیت زمانی ارسال از خطوط عمومی"; break;
		case 11:	$ErrorMsg = "خطای نامشخص - با مدیرسامانه پیامکی تماس بگیرید"; break;
		case 16:	$ErrorMsg = "تعداد آرایه وارد شده بیش از 150 مورد می باشد"; break;
		case 22: 	$ErrorMsg = "تمامی ورودی ها به درستی وارد نشده است"; break;
		case 33:	$ErrorMsg = "نوع متغیر متد درست وارد نشده است"; break;
	}
	
	if ($ErrorMsg !='')  return array(-1,$ErrorMsg,0); 
	return array(1, "پیامک با موفقیت ارسال شد ", $response ); 
}	
//-------------------------------------------
function SendSmsIranPanelNew($ToListMobile, $SmsMessage, $row) {
	if ( (!isset($row)) or (!is_array($row)) or (count($row)==0) ) {
		return array(-1,"اطلاعات سامانه ارسال پیامک ، صحیح نمی باشد",0);
	}
	$smsuser	= $row['smsuser'];
	$smspass	= ($row['smspass']);
	$smsnumber	= $row['smsnumber'];
	//example for $ToListMobile = array('09121536501','09121536500','09121128345');
	if ($ToListMobile=='') 	return array(-1,"شماره دریافت کننده ثبت نشده است",0);
	if ($SmsMessage=='') 	return array(-1,"متن پیامک خالی است",0);
	if ( ($smsuser=='') or ($smspass=='') or ($smsnumber=='')) 
		return array(-1,"تنظیمات اتصال به سامانه پیامکی خالی می باشد",0);
	
	if (!(is_array($ToListMobile)))  $ToListMobile = array($ToListMobile);
	
	$url = "https://ippanel.com/services.jspd";


	$param = array('uname'=>$smsuser, 'pass'=>$smspass, 'from'=>$smsnumber, 'message'=>$SmsMessage, 'to'=>json_encode($ToListMobile), 'op'=>'send');
	$handler = curl_init($url);             
	curl_setopt($handler, CURLOPT_CUSTOMREQUEST, "POST");
	curl_setopt($handler, CURLOPT_POSTFIELDS, $param);                       
	curl_setopt($handler, CURLOPT_RETURNTRANSFER, true);
	$response2 = curl_exec($handler);
	if (!$response2) { return array(-1,'پاسخی از سمت سامانه پیامکی دریافت نشد',0); }
	$response2 = @json_decode($response2);
	if (!is_array($response2)) {return array(-1,'پاسخی از سمت سامانه پیامکی دریافت نشد2',0); }
	$res_code = $response2[0];
	$res_data = $response2[1];
	if ($res_code==0) { return array(0,'پیامک با موفقیت ارسال شد',$res_data); }
	$ErrorMsg = '';
	switch ($res_code) {
		case 1:		$ErrorMsg = "متن پیام خالی است"; break;
		case 2:		$ErrorMsg = "کاربر محدود شده است"; break;
		case 3:		$ErrorMsg = "خط ارسال کننده به مالک متعلق نیست"; break;
		case 4: 	$ErrorMsg = "گیرندگان خالی است"; break;
		case 5:		$ErrorMsg = "اعتبار کافی نیست"; break;
		case 7:		$ErrorMsg = "خط موردنظر برای ارسال انبوه مناسب نیست"; break;
		case 9:		$ErrorMsg = "خط موردنظر در این ساعت امکان ارسال ندارد"; break;
		case 98:	$ErrorMsg = "حداکثر تعداد گیرندگان رعایت نشده است"; break;
		case 99:	$ErrorMsg = "اپراتور خط ارسالی قطع می باشد"; break;
		default:
			$ErrorMsg =$res_data;

	}
	
	$ErrorMsg = "$ErrorMsg - کد خطا: $res_code";
	if ($ErrorMsg !='')  return array(-1,$ErrorMsg,$res_data); 
}
//----------------------------------------
//--------------------------------
function SendSmsSignalWithOutApikey($ToListMobile, $SmsMessage, $row) {
	//example for $ToListMobile = array('09121536501','09121536500','09121128345');
	if ( (!isset($row)) or (!is_array($row)) or (count($row)==0) ) {
		return array(-1,"اطلاعات سامانه ارسال پیامک ، صحیح نمی باشد",0);
	}
	if ($ToListMobile=='') 	return array(-1,"شماره دریافت کننده ثبت نشده است",0);
	if ($SmsMessage=='') 	return array(-1,"متن پیامک خالی است",0);
	
	$smsuser	= $row['smsuser'];
	$smspass	= md5($row['smspass']);
	$smsnumber	= $row['smsnumber'];
	

	if ((is_array($ToListMobile)))  $ToListMobile = explode(',',$ToListMobile);
	if (mb_substr($SmsMessage,-5)!='لغو11') { 	$SmsMessage .= " لغو11";}


//https://www.yourdomain.com/webservice/url/send.php?method=sendsms&format=json&from=10001234&to=9121234567,9127654321&text=example&type=0&username=test&password=123456
	$url = "https://panel.signalads.com/webservice/url/send.php";
	$params = [
		'method'	=> 'sendsms',
		'format'	=> 'html',
		'from'		=> $smsnumber,
		'to'		=> $ToListMobile,
		'text'		=> $SmsMessage,
		'type'		=> '0',
		'username'	=> $smsuser,
		'password'	=> $smspass,
		
	];
//	var_dump($params);
//	$jsonData = json_encode($params);
	$handler = curl_init($url);
	curl_setopt($handler, CURLOPT_CUSTOMREQUEST, "POST");
	curl_setopt($handler, CURLOPT_POSTFIELDS, $params);                       
	curl_setopt($handler, CURLOPT_RETURNTRANSFER, true);
	$response = curl_exec($handler);
	$curl_error = @curl_error($handler);
	curl_close($handler);
	if ($curl_error) {   
		return array(-1, "خطای cURL در مرحله دریافت مسیر آپلود: $curl_error",0); 
	}
	if (($response === false) or (is_null($response)) ) {
		return array(-1, "هیچ پاسخی از سمت سرور segnalads دریافت نشد",0); 
	}
//	var_dump($response);
	$ErrorMsg = '';
//	echo "response=$response";
	switch ($response) {
		case 0:		$ErrorMsg = "نام کاربری و یا اسم رمز اشتباه است"; break;
		case 1:		$ErrorMsg = "اعتبار کافی برای ارسال نمی باشد"; break;
		case 2:		$ErrorMsg = "شماره اختصاصی فرستنده معتبر نمی باشد"; break;
		case 4:		$ErrorMsg = "امکان ارسال برای این اکانت غیرفعال می باشد"; break;
		case 5:		$ErrorMsg = "پنل کاربری غیرفعال است"; break;
		case 6:		$ErrorMsg = "پنل کاربری منقضی شده است"; break;
		case 7:		$ErrorMsg = "متن پیام خالی می باشد"; break;
		case 9:		$ErrorMsg = "هیچ گیرنده ای مشخص نشده است"; break;
		case 10:	$ErrorMsg = "محدودیت زمانی ارسال از خطوط عمومی"; break;
		case 11:	$ErrorMsg = "خطای نامشخص - با مدیرسامانه پیامکی تماس بگیرید"; break;
		case 16:	$ErrorMsg = "تعداد آرایه وارد شده بیش از 150 مورد می باشد"; break;
		case 22: 	$ErrorMsg = "تمامی ورودی ها به درستی وارد نشده است"; break;
		case 33:	$ErrorMsg = "نوع متغیر متد درست وارد نشده است"; break;
	}
	
	if ($ErrorMsg !='')  return array(-1,$ErrorMsg . "<BR> $response ",0); 
	return array(1, "پیامک با موفقیت ارسال شد ", $response ); 
}
//--------------------------------
function SendSmsSignal($ToListMobile, $SmsMessage, $row) {
	//example for $ToListMobile = array('09121536501','09121536500','09121128345');
	if ( (!isset($row)) or (!is_array($row)) or (count($row)==0) ) {
		return array(-1,"اطلاعات سامانه ارسال پیامک ، صحیح نمی باشد",0);
	}
	if ($ToListMobile=='') 	return array(-1,"شماره دریافت کننده ثبت نشده است",0);
	if ($SmsMessage=='') 	return array(-1,"متن پیامک خالی است",0);
	
	$apikey = $row['apikey'];
	$smsnumber	= $row['smsnumber'];
	if ($apikey=='')  		return array(-1,'برای این سامانه کد توکن (apikey) تعریف نشده است ',0);

	if ((is_array($ToListMobile)))  $ToListMobile = explode(',',$ToListMobile);
	if (mb_substr($SmsMessage,-5)!='لغو11') { 	$SmsMessage .= " لغو11";}


//	$date = new DateTime('now', new DateTimeZone('Asia/Tehran'));	$date = $date->format('Y-m-d H:i');

	$url = "https://transmitor.signalads.com/api_v1/send/simple";
	$params = [
		'from'		=> $smsnumber,
		'numbers'	=> [$ToListMobile],
		'message'	=> $SmsMessage,
//		'send_at'   => $date,
	];
	$jsonData = json_encode($params);
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
	curl_setopt($ch, CURLOPT_HTTPHEADER, ["Authorization: Bearer $apikey", "Content-Type: application/json", "Content-Length: " . strlen($jsonData)]);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);                       
	$response = curl_exec($ch);
	$curl_error = @curl_error($ch);
	curl_close($ch);
	if ($curl_error) {   
		return array(-1, "خطای cURL در مرحله دریافت مسیر آپلود: $curl_error",0); 
	}
	if (($response === false) or (is_null($response)) ) {
		return array(-1, "هیچ پاسخی از سمت سرور segnalads دریافت نشد",0); 
	}
	$responseData = json_decode($response, true);		
	if (json_last_error() !== JSON_ERROR_NONE) {		return [-1, "خطا در decode پاسخ API",0];	}
	
	//-------------------------------- ✅ حالت موفق
	if (isset($responseData['success']) && $responseData['success'] === true) {
		return [1, "پیامک با موفقیت ارسال شد", $responseData['id']];
	}	

	//--------------------------------
	// ❌ حالت خطا (آرایه‌ای)
	if (isset($responseData[0])) {
		$error = $responseData[0];

		$msg = $error['message'] ?? 'خطای نامشخص';

		// ترجمه خطاها
		switch ($msg) {
			case 'Field required':
				return [-1, "برخی فیلدهای اجباری ارسال نشده‌اند",0];
			
			case 'JSON decode error':
				return [-1, "فرمت JSON ارسالی اشتباه است",0];
			
			case 'Value error, فرمت گیرندگان باید لیستی از شماره‌ها باشد':
				return [-1, "فرمت شماره‌ها اشتباه است (باید آرایه باشد)",0];
			
			default:
				return [-1, $msg,0];
		}
	}

	//--------------------------------
	// ❌ حالت خطا (detail)
	if (isset($responseData['detail'])) {
		return [-1, $responseData['detail'],0];
	}

	//--------------------------------
	// ❌ حالت ناشناخته
	return [-1, "پاسخ نامشخص از سرور", $responseData];
}
//-------------------------------------------------------------------
function usermanagament_two_char($str)
{
    if (strlen($str) == 1) {
        $str = "0" . $str;
    }
    return $str;
}

function usermanagement_getDate()
{
    $s_d = preg_split('/-/', date("Y-m-d"));
    $s_d_j = gregorian_to_jalali($s_d[0], $s_d[1], $s_d[2]);
    return usermanagament_two_char($s_d_j[0]) . "/" . usermanagament_two_char($s_d_j[1]) . "/" . usermanagament_two_char($s_d_j[2]);
}

function usermanagement_getPastDate()
{
    $s_d = preg_split('/-/', date("Y-m-d"));
    $s_d_j = gregorian_to_jalali($s_d[0], $s_d[1], $s_d[2]);
    $s_d_j[2] = $s_d_j[2] - 2;
    if ($s_d_j[2] == 0) {
        if ($s_d_j[1] < 7) {
            $s_d_j[2] = 31;
        } else {
            $s_d_j[2] = 30;
        }
        $s_d_j[1]--;
    }elseif ($s_d_j[2] < 0) {
        if ($s_d_j[1] < 7) {
            $s_d_j[2] = 30;
        } else {
            $s_d_j[2] = 29;
        }
        $s_d_j[1]--;
    }
    return usermanagament_two_char($s_d_j[0]) . "/" . usermanagament_two_char($s_d_j[1]) . "/" . usermanagament_two_char($s_d_j[2]);
}

function usermanagement_getDate2()
{
    $s_d = preg_split('/-/', date("Y-m-d"));
    $s_d_j = gregorian_to_jalali($s_d[0], $s_d[1], $s_d[2]);
    return date('l') . ' ' . usermanagament_two_char($s_d_j[0]) . "/" . usermanagament_two_char($s_d_j[1]) . "/" . usermanagament_two_char($s_d_j[2]);
}

function usermanagement_getDateForSaveFile()
{
    $s_d = preg_split('/-/', date("Y-m-d"));
    $s_d_j = gregorian_to_jalali($s_d[0], $s_d[1], $s_d[2]);
    return usermanagament_two_char($s_d_j[0]) . "-" . usermanagament_two_char($s_d_j[1]) . "-" . usermanagament_two_char($s_d_j[2]);
}

function usermanagement_getTimeForSaveFile()
{
    return date("H-i-s");
}

function usermanagement_getDateTimeForSaveFile()
{
    return usermanagement_getDateForSaveFile() . "-" . usermanagement_getTimeForSaveFile();
}

function usermanagement_getTime()
{
    return date("H:i:s");
}

function usermanagement_getDateTime()
{
    return usermanagement_getDate() . " - " . usermanagement_getTime();
}

function convertToSeconde($time)
{
    $str_time = $time;
    sscanf($str_time, "%d:%d:%d", $hours, $minutes, $seconds);
    $time_seconds = isset($seconds) ? $hours * 3600 + $minutes * 60 + $seconds : $hours * 60 + $minutes;
    return $time_seconds;
}

function getDateRangeNumber($strDateFrom, $strDateTo) {
	if ( ($strDateFrom=='') or ($strDateTo=='') ) return 0;
    $strDateFrom = jalali_to_gregorian($strDateFrom);
    $strDateTo   = jalali_to_gregorian($strDateTo);

    $iDateFrom = strtotime($strDateFrom);;
    $iDateTo   = strtotime($strDateTo);
	$diff = ($iDateTo - $iDateFrom);	
	$days =  round($diff / (60 * 60 * 24));	
	return $days;
}

function AddDayToDate($strDateFrom, $day) {
	if ( ($strDateFrom=='') or ($day==0) ) return '';
    $strDateFrom = jalali_to_gregorian($strDateFrom);
    $iDateFrom   = strtotime($strDateFrom);;
    $iDateTo   	 = ($day * (60 * 60 * 24));
	$NewDateTime = ($iDateFrom+$iDateTo);	
	$NewDate     =  date("Y/m/d",$NewDateTime ) ;
	$NewDate2  	 =  gregorian_to_jalali_str($NewDate);
	return $NewDate2;
}

function getDateRange($strDateFrom, $strDateTo)
{
    // takes two dates formatted as YYYY-MM-DD and creates an
    // inclusive array of the dates between the from and to dates.

    // could test validity of dates here but I'm already doing
    // that in the main script
    $strDateFrom = jalali_to_gregorian($strDateFrom);
    $strDateTo   = jalali_to_gregorian($strDateTo);
    $aryRange = array();

    $iDateFrom = mktime(1, 0, 0, substr($strDateFrom, 5, 2), substr($strDateFrom, 8, 2), substr($strDateFrom, 0, 4));
    $iDateTo = mktime(1, 0, 0, substr($strDateTo, 5, 2), substr($strDateTo, 8, 2), substr($strDateTo, 0, 4));
    if ($iDateTo >= $iDateFrom) {
        $d = date('Y/m/d', $iDateFrom);
        $d = explode('/', $d);
        $d = gregorian_to_jalali($d[0], $d[1], $d[2]);
        array_push($aryRange, $d[0] . '/' . usermanagament_two_char($d[1]) . '/' . usermanagament_two_char($d[2]));
        while ($iDateFrom < $iDateTo) {
            $iDateFrom += 86400; // add 24 hours
            $d = date('Y/m/d', $iDateFrom);
            $d = explode('/', $d);
            $d = gregorian_to_jalali($d[0], $d[1], $d[2]);
            array_push($aryRange, $d[0] . '/' . usermanagament_two_char($d[1]) . '/' . usermanagament_two_char($d[2]));
        }
    }
    return $aryRange;
}

function getDiffTime($t1, $t2)
{
    $t1 = convertToSeconde($t1);
    $t2 = convertToSeconde($t2);
    if ($t2>$t1)
        $dif = $t2 - $t1;
    else
        $dif = $t1 - $t2;
    //$dif = $t2 - $t1;
    $hour = (integer)($dif / 60);
    $minut = $dif % 60;
    $hour = strlen($hour) >= 2 ? $hour : '0' . $hour;
    $minut = strlen($minut) >= 2 ? $minut : '0' . $minut;
    return $hour . ':' . $minut;
}

function timePlus($t1, $t2)
{
    $t1 = convertToSeconde($t1);
    $t2 = convertToSeconde($t2);
    $dif = $t2 + $t1;
    $hour = (integer)($dif / 60);
    $minut = $dif % 60;
    $hour = strlen($hour) >= 2 ? $hour : '0' . $hour;
    $minut = strlen($minut) >= 2 ? $minut : '0' . $minut;
    return $hour . ':' . $minut;
}

function getDay($strDate='')
{
    if (trim($strDate) != '') {
        $strDate = jalali_to_gregorian($strDate);
        $str = date('l', strtotime($strDate));
        $str = str_replace("Saturday", "شنبه", $str);
        $str = str_replace("Sunday", "یکشنبه", $str);
        $str = str_replace("Monday", "دوشنبه", $str);
        $str = str_replace("Tuesday", "سه شنبه", $str);
        $str = str_replace("Wednesday", "چهارشنبه", $str);
        $str = str_replace("Thursday", "پنج شنبه", $str);
        $str = str_replace("Friday", "جمعه", $str);
        return $str;
    } else {
        return '';
    }
}

function getDay3($strDate='')
{
    if (trim($strDate) != '') {
        $strDate = jalali_to_gregorian($strDate);
        $str = date('l', strtotime($strDate));
        return $str;
    } else {
        return '';
    }
}

function getDay2($strDate='')
{
    //$strDate = usermanagement_getDate();
    if (trim($strDate) != '') {
        $strDate = jalali_to_gregorian($strDate);
        $str = date('l', strtotime($strDate));
        $str = str_replace("Saturday", "0", $str);
        $str = str_replace("Sunday", "1", $str);
        $str = str_replace("Monday", "2", $str);
        $str = str_replace("Tuesday", "3", $str);
        $str = str_replace("Wednesday", "4", $str);
        $str = str_replace("Thursday", "5", $str);
        $str = str_replace("Friday", "6", $str);
        return $str;
    } else {
        return '';
    }
}
function jalaliToG($strDate){
    if (trim($strDate) != '') {
        $plitedDate = jalali_to_gregorian($strDate);
        $strDate = str_replace('/','-', $plitedDate);
    }
    return $strDate;
}

function div($a, $b)
{
   return (int) ($a / $b);
} 

function GetMonth1($MonthNum=0) {
	if (($MonthNum==0) or ($MonthNum>12) ) return '';
	$j_month_name = array("", "فروردین", "اردیبهشت", "خرداد", "تیر","مرداد", "شهریور", "مهر", "آبان", "آذر","دی", "بهمن", "اسفند");
	return $j_month_name[$MonthNum];
}	

function GetMonth2($MonthNum=0) {
	if (($MonthNum==0) or ($MonthNum>12) ) return '';
//	return date("F", mktime(0, 0, 0, $monthNum, 10));
	$j_month_name = array("", "January", "February", "March", "April","May", "June", "July", "August", "September","October", "November", "December");
	return $j_month_name[$MonthNum];
}	

function GetMonth3($MonthNum=0) {
	if (($MonthNum==0) or ($MonthNum>12) ) return '';
	$j_month_name = array('', 'محرم', 'صفر', 'ربيع الأول', 'ربيع الثاني', 'جمادى الأولى', 'جمادى الآخرة', 'رجب', 'شعبان', 'رمضان', 'شوال', 'ذو القعدة', 'ذو الحجة');	
	return $j_month_name[$MonthNum];
}	

function DetailsDate($strDate) {
	$Year  = 0;
	$Month = 0;
	$Day   = 0;
	$MonthName='';
	$DayName='';
    if (trim($strDate) == '') {
		return array($Year, $Month, $Day, $MonthName,$DayName);
    }
	$Year = substr($strDate,0,4);
	$Month = substr($strDate,5,2);
	$Day = substr($strDate,8,2);
	$MonthName = GetMonth1(intval($Month));
	$DayName   = getDay($strDate);
	return array($Year, $Month, $Day, $MonthName,$DayName);
}

//-------------------------------------------
function gregorian_to_jalali($g_y, $g_m, $g_d)
{
	$g_days_in_month = array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
	$j_days_in_month = array(31, 31, 31, 31, 31, 31, 30, 30, 30, 30, 30, 29);
	$j_month_name = array("", "فروردین", "اردیبهشت", "خرداد", "تیر","مرداد", "شهریور", "مهر", "آبان", "آذر","دی", "بهمن", "اسفند");

//   global $g_days_in_month;   global $j_days_in_month;
   
   $gy = $g_y-1600;
   $gm = $g_m-1;
   $gd = $g_d-1;

   $g_day_no = 365*$gy+div($gy+3,4)-div($gy+99,100)+div($gy+399,400);

   for ($i=0; $i < $gm; ++$i)
      $g_day_no += $g_days_in_month[$i];
   if ($gm>1 && (($gy%4==0 && $gy%100!=0) || ($gy%400==0)))
      /* leap and after Feb */
      ++$g_day_no;
   $g_day_no += $gd;
 
   $j_day_no = $g_day_no-79;
 
   $j_np = div($j_day_no, 12053);
   $j_day_no %= 12053;
 
   $jy = 979+33*$j_np+4*div($j_day_no,1461);

   $j_day_no %= 1461;
 
   if ($j_day_no >= 366) {
      $jy += div($j_day_no-1, 365);
      $j_day_no = ($j_day_no-1)%365;
   }
 
   for ($i = 0; $i < 11 && $j_day_no >= $j_days_in_month[$i]; ++$i) {
      $j_day_no -= $j_days_in_month[$i];
   }
   $jm = $i+1;
   $jd = $j_day_no+1;


   return array($jy, $jm, $jd);
}
//-------------------------------------------
function jalali_to_gregorian($InputDate)
{
	if ($InputDate=='') return '';
	list($j_y, $j_m, $j_d) = explode('/',$InputDate); 
//	echo "<div align=left dir=ltr> j_y=$j_y - j_m = $j_m - j_d = $j_d </div>";
//	global $g_days_in_month;	global $j_days_in_month;
$g_days_in_month = array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
$j_days_in_month = array(31, 31, 31, 31, 31, 31, 30, 30, 30, 30, 30, 29);
$j_month_name = array("", "فروردین", "اردیبهشت", "خرداد", "تیر","مرداد", "شهریور", "مهر", "آبان", "آذر","دی", "بهمن", "اسفند");

	



	$jy = $j_y-979;
	$jm = $j_m-1;
	$jd = $j_d-1;

	$j_day_no = 365*$jy + div($jy, 33)*8 + div($jy%33+3, 4);
	for ($i=0; $i < $jm; ++$i)
	$j_day_no += $j_days_in_month[$i];

	$j_day_no += $jd;

	$g_day_no = $j_day_no+79;

	$gy = 1600 + 400*div($g_day_no, 146097); /* 146097 = 365*400 + 400/4 - 400/100 + 400/400 */
	$g_day_no = $g_day_no % 146097;

	$leap = true;
	if ($g_day_no >= 36525) /* 36525 = 365*100 + 100/4 */
	{
		$g_day_no--;
		$gy += 100*div($g_day_no,  36524); /* 36524 = 365*100 + 100/4 - 100/100 */
		$g_day_no = $g_day_no % 36524;

		if ($g_day_no >= 365)
		$g_day_no++;
		else
		$leap = false;
	}

	$gy += 4*div($g_day_no, 1461); /* 1461 = 365*4 + 4/4 */
	$g_day_no %= 1461;

	if ($g_day_no >= 366) {
		$leap = false;

		$g_day_no--;
		$gy += div($g_day_no, 365);
		$g_day_no = $g_day_no % 365;
	}

	for ($i = 0; $g_day_no >= $g_days_in_month[$i] + ($i == 1 && $leap); $i++)
	$g_day_no -= $g_days_in_month[$i] + ($i == 1 && $leap);
	$gm = $i+1;
	$gd = $g_day_no+1;
	return "$gy/".usermanagament_two_char($gm)."/".usermanagament_two_char($gd);

	return array($gy, $gm, $gd);
}

function gregorian_to_jalali_str($InputDate){
	
	if ($InputDate=='') return '';
	$InputDate = str_replace('-','/',$InputDate);
	$parts = explode(' ', $InputDate, 2);	
	if (count($parts) === 2) {
		$InputDate = $parts[0];
		$dtime    = $parts[1];
	} else {
		$dtime = '';           // یا '00:00:00' یا null
		// $InputDate بدون تغییر می‌مونه
	}	
	/*
	if (strpos($InputDate, ' ') !== false) {
	list($InputDate, $dtime) = explode(' ',$InputDate);
	}else{
		$dtime='';
	}
	*/
	list($g_y, $g_m, $g_d) = explode('/',$InputDate); 
	if ($dtime!='') $dtime = " $dtime";

	$g_days_in_month = array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
	$j_days_in_month = array(31, 31, 31, 31, 31, 31, 30, 30, 30, 30, 30, 29);
	$j_month_name = array("", "فروردین", "اردیبهشت", "خرداد", "تیر","مرداد", "شهریور", "مهر", "آبان", "آذر","دی", "بهمن", "اسفند");

//   global $g_days_in_month;   global $j_days_in_month;
   
   $gy = $g_y-1600;
   $gm = $g_m-1;
   $gd = $g_d-1;

   $g_day_no = 365*$gy+div($gy+3,4)-div($gy+99,100)+div($gy+399,400);

   for ($i=0; $i < $gm; ++$i)
      $g_day_no += $g_days_in_month[$i];
   if ($gm>1 && (($gy%4==0 && $gy%100!=0) || ($gy%400==0)))
      /* leap and after Feb */
      ++$g_day_no;
   $g_day_no += $gd;
 
   $j_day_no = $g_day_no-79;
 
   $j_np = div($j_day_no, 12053);
   $j_day_no %= 12053;
 
   $jy = 979+33*$j_np+4*div($j_day_no,1461);

   $j_day_no %= 1461;
 
   if ($j_day_no >= 366) {
      $jy += div($j_day_no-1, 365);
      $j_day_no = ($j_day_no-1)%365;
   }
 
   for ($i = 0; $i < 11 && $j_day_no >= $j_days_in_month[$i]; ++$i) {
      $j_day_no -= $j_days_in_month[$i];
   }
   $jm = $i+1;
   $jd = $j_day_no+1;

	return "$jy/".usermanagament_two_char($jm)."/".usermanagament_two_char($jd).$dtime;

	return array($jy, $jm, $jd);
}

//-----بدست آوردن نوع جدول از نوع جدول پدر اصلی
function parent_group($id_group, $id_parent, $TableGroup ) {
	$parent2= $id_parent;
	while ($parent2>0) {
		$query2  = pdo_query("select * from $TableGroup where id_main='$parent2' limit 0,1",'',0);		
		$row2    = $query2->fetch(PDO::FETCH_ASSOC);		
		$parent2 = $row2['parent'];
		$id_group = $row2['id_main'];
	}
	return $id_group;
}
//--------------------------------
function tableExists($pdo, $table) {

    // Try a select statement against the table
    // Run it in try/catch in case PDO is in ERRMODE_EXCEPTION.
    try {
        $result = $pdo->query("SELECT 1 FROM $table LIMIT 1");
    } catch (Exception $e) {
        // We got an exception == table not found
        return FALSE;
    }

    // Result is either boolean FALSE (no table found) or PDOStatement Object (table found)
    return $result !== FALSE;
}
//--------------
function get_extended($link) {
	$ext = pathinfo($link, PATHINFO_EXTENSION);
	return $ext;
}
//-------------------
function pdo_lastinsert() {
	global $connection;
    $lastid = $connection->lastInsertId(); 	
	return $lastid;
}

//-----------------------------------------
function WaitWindow($message,  $TimeRemove) {
	echo "
	<div id='WaitWindow' class='w-100' style='margin:auto;margin-top:40px;margin-bottom:40px;'>
		<div class='bg-danger rounded text-center w-75' style='margin:auto;padding:20px;color:white;'>
		<a class='text-right' href='#' onclick=\"$('#WaitWindow').remove()\" ><i class='far fa-times-circle' style='color:white;'></i></a>
		$message
		</div>
	</div>";
}	
			
//-----------------------------------------
function ShowMessage($message,  $OkExit=0, $bgcolor='bg-danger') {
	
	echo "
	<div class='w-100' style='margin:auto;margin-top:40px;margin-bottom:40px;'>
		<div class='$bgcolor rounded text-center w-75' style='margin:auto;padding:20px;color:white;'>$message</div>
	</div>
	";
	if ($OkExit!=0) {	exit;	}
}
//-----------------------------------------
function ShowMessageNew($message, $ReturnLink='', $ReturnStr='', $OkExit=0, $bgcolor='') {
	if ($ReturnStr=='') {	$ReturnStr = 'بازگشت';}
	if ($bgcolor=='') {	$bgcolor = 'bg-danger';}
	$Extended='';
	if ($ReturnLink!='') {	
		$Extended =  " <div class='mt-2'> <a href='$ReturnLink' >
		<h6 style='color:blue'>$ReturnStr</h6> </a></div>";	
	}
	echo "
	<div class='$bgcolor rounded text-center w-100  p-2' >
	<h5 class='text-white'>$message $Extended</h5>
	</div>";

	if ($OkExit!=0) {		exit;		}
	
}


//-----------------------------------------
function ShowMessage2($message,  $OkExit=0) {
	
	echo "
 	<div  style='margin:auto;margin-top:40px;margin-bottom:40px;background-color:red;color:white;padding:20px;text-align: center; border-radius:5px;width:90%;'>
		<span>$message</span>
	</div>
	";
	if ($OkExit!=0) {		exit;		}
}

//----------------
function CheckTable($TableName) {
  global $db_name; // db_name variable in cn.php = databasename
  $result = pdo_query("SELECT table_name FROM information_schema.tables WHERE table_schema = '$db_name' AND table_name = '$TableName'  ");
  $exists = true;
  if (pdo_rowcount($result)==0) $exists = FALSE;
  return $exists;    
}

//----------------
function CheckField($TableName,$FieldName) {
  $result = pdo_query("SHOW COLUMNS FROM `$TableName` LIKE '$FieldName'");
  $exists = true;
  if (pdo_rowcount($result)==0) $exists = FALSE;
  return $exists;    
}
//----------------------
//مناسب برای جستجو در جداول کوچک که مستقیم تمامی رکوردهای با با دستور 
//pdo_fetchAll
//خوانده ایم
function SearchArray($ArrayName, $Field, $Value) {
	$key = array_search($Value, array_column($ArrayName, $Field));
	return $key;
}
//--------------------------
function read_create_tree($query_tree, $id_tree, $parentid_tree, $title_tree) {

	 $result=pdo_query($query_tree,'',0);
	 $arrayCategories = array();
	 $oklist=0;
	 while($row = pdo_fetch($result)) { 	
	 	$oklist=1;
		$arrayCategories[$row[$id_tree]] = array(
		"ID" => $row[$id_tree],
		"PARENT" => $row[$parentid_tree], 
		"NAME" =>  $row[$title_tree]);     
	}
	return $arrayCategories;
}	
//--------------------------

function file_get_contents_curl( $url ) {

  $ch = curl_init();

  curl_setopt( $ch, CURLOPT_AUTOREFERER, TRUE );
  curl_setopt( $ch, CURLOPT_HEADER, 0 );
  curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
  curl_setopt( $ch, CURLOPT_URL, $url );
  curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, TRUE );

  $data = curl_exec( $ch );
  curl_close( $ch );

  return $data;

}
//--------------------------

function file_get_contents_curl_ssl($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_REFERER, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3000); // 3 sec.
    curl_setopt($ch, CURLOPT_TIMEOUT, 10000); // 10 sec.
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}

//--------------------
function Translate($key) {
	global $ListLang;
	$temp = isset($ListLang[$key]) ? $ListLang[$key] : '';
	return $temp;
}
//--------------------
function convert_html_to_text($html, $ignore_error = false) {
	return Soundasleep\Html2Text::convert($html, $ignore_error);
}
//--------------------
function fix_newlines($text) {
	return Soundasleep\Html2Text::fixNewlines($text);
}
//-------------
function htmlent2xml($s) {
    return preg_replace_callback("/(&[a-zA-Z][a-zA-Z0-9]*;)/",function($m){
       $c = html_entity_decode($m[0],ENT_HTML5,"UTF-8");
       # return htmlentities($c,ENT_XML1,"UTF-8"); -- see update below

       $convmap = array(0x80, 0xffff, 0, 0xffff);
       return mb_encode_numericentity($c, $convmap, 'UTF-8');
    },$s);
}
//-------------------------------------------
function gregorian_to_hijri($InputDate)
{
	if ($InputDate=='') return '';
	list($year, $month, $day) = explode('/',$InputDate); 
	
	if($year > 1582 or ($year==1581 and $month > 9 and $day > 14)){
		$int1=(int)(($month-14)/12);
		$jd=(int)((1461*($year+4800+$int1))/4)+(int)((367*($month-2-(12*($int1))))/12)-(int)((3*((int)(($year+4900+$int1)/100)))/4)+$day-32075;
	 }else{
		$jd=(367*$year)-(int)((7*($year+5001+(int)(($month-9)/7)))/4)+(int)((275*$month)/9)+$day+1729777;
	 }
	 $l=$jd-1948440+10632;
	 $n=(int)(($l-1)/10631);
	 $l=$l-10631*$n+354;
	 $j=(((int)((10985-$l)/5316))*((int)((50*$l)/17719)))+(((int)($l/5670))*((int)((43*$l)/15238)));
	 $l=$l-((int)((30-$j)/15))*((int)((17719*$j)/50))-((int)($j/16))*((int)((15238*$j)/43))+29;
	 $month=(int)((24*$l)/709);
	 $day=$l-(int)((709*$month)/24);
	// $day++;
	 $year=(30*$n)+$j-30;
	return "$year/".usermanagament_two_char($month)."/".usermanagament_two_char($day);
	
//	return array($year,$month,$day);
}

//-------------
function ChangeShowDate($input_date, $type_date=2) {
	global $lang;
	if (!(isset($lang))) $lang='en';
	if (trim($input_date)=='') return '';
	if (mb_strlen($input_date,'UTF-8')>10) 
		$input_date= mb_substr($input_date,0,10,'UTF-8');
	$input_date = str_replace("-","/", $input_date);
	switch ($lang) {
		case 'fa':
			list($year, $month, $day) = explode("/",$input_date);
			$month = GetMonth1(intval($month));
			$ret = "$day $month $year";
			break;
		case 'en': // type_date==1 --> $direction
			$new_date = jalali_to_gregorian($input_date);
			list($year, $month, $day) = explode("/",$new_date);
			$month = GetMonth2(intval($month));
			$ret = " $year $month  $day";
			break;
		case 'ar': 
			$new_date = jalali_to_gregorian($input_date);
			$new_date = gregorian_to_hijri($new_date);
			list($year, $month, $day) = explode("/",$new_date);
			$month = GetMonth3(intval($month));
			$ret = "$day $month $year";
			break;
		default:
			$new_date = jalali_to_gregorian($input_date);
			$ret = $new_date;
			return $ret;
			
	}
	return $ret;
	
	
}
//----------------------------
function CronJobReport($websiteid, $url, $rate) {
	global $nowdatetime;
    $check = @get_headers($url); 
    if ($check) {
        $active=1;
        $temp1 = explode(" ",$check[0]);
        $status = $temp1[1];
        $rate=1;
		$log = implode("\r\n", $check);
		$log = str_replace('"','',$log);
		$log = str_replace("'","",$log);
		$log = str_replace(",","",$log);
    }else{
        $active = 0;
        $status = 0;
        $rate = $rate - 1;
		$log = '';
    }
    $query_report = pdo_query("update `websites` set last_date_report = '$nowdatetime', rate_report='$rate' where id='$websiteid' ");
    $query_report = pdo_query("insert into `website_report` (websiteid, url, active, status, date, log) values ('$websiteid','$url','$active', '$status', '$nowdatetime', '$log' )  ");
}
//---------------اضافه و کم کردن ثانیه از تاریخ
function AddSecondeToDate($strDateTimeFrom, $second) {
//	$strDateTimeFrom = "1402/01/15 00:40:00";	$second = -3600;
	if ( ($strDateTimeFrom=='') or ($second==0) ) return '';
	list($strDateFrom, $strTimeFrom) = explode(' ' , $strDateTimeFrom);
    $strDateFrom = jalali_to_gregorian($strDateFrom);
    $iDateFrom   = strtotime($strDateFrom . $strTimeFrom);;
	$NewDateTime = ($iDateFrom+$second);	
	$NewDate     =  date("Y/m/d",$NewDateTime ) ;
	$NewTime	 =  date("H:i:s",$NewDateTime ) ;
	$NewDate2  	 =  gregorian_to_jalali_str($NewDate);
	$NewDate2 = "$NewDate2 $NewTime";
	return $NewDate2;
}
//-------------------------------
function ChangeDate($date, $operand='')
{
	//تاریخ شمسی می گیرد و با عملگرد افزایش و یا کاهش تاریخ آن را اعمال م کند
//example:	$operand = "+1 day";
    $dateM = jalali_to_gregorian($date);
	$dateM = new DateTime($dateM);
	$dateM->modify($operand);
	$dateM = $dateM->format('Y/m/d') ;
	$newdate =  gregorian_to_jalali_str($dateM);
	return $newdate;
}
//-------------------------------

function OkNumber($input) {
	if ((gettype($input)=='integer') or (gettype($input)=='double') ) return $input;
	$input = str_replace(",","", $input);
	return intval($input);
}
//-------------------------------

function filesize_formatted($path)
{
    $size = filesize($path);
    $units = array( 'B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
    $power = $size > 0 ? floor(log($size, 1024)) : 0;
    return number_format($size / pow(1024, $power), 2, '.', ',') . ' ' . $units[$power];
}
//--------------
function SendToWhatsapp360($linksms, $phone,$send_content) {
//	echo "<BR>$linksms, <BR>$phone,<BR>$send_content";
	
	$request = new HTTP_Request2();
//	var_dump($request);
	$request->setUrl($linksms);
	$request->setMethod(HTTP_Request2::METHOD_POST);
	$request->setConfig(array('follow_redirects' => TRUE));
	$request->addPostParameter(array('phonenumber' => $phone,	  'text' => $send_content));
	try {
	  	$response = $request->send();
//		var_dump($response);
		$GetStatus = $response->getStatus();
		$getBody   = $response->getBody();
	  	return array($GetStatus, $getBody);
	}
	catch(HTTP_Request2_Exception $e) {
		  $temp = 'Error: ' . $e->getMessage();
 		  return array(-2, $temp);
	}	
}
//-----------------------------------
function GetPhotoJsFront($VarMain, $VarStr1, $VarStr2) {
	global $upload_path_main, $maxsizephoto;
	$temp1 = $VarStr2."1";
	$temp2 = $VarStr2."2";
	$temp3 = $VarStr2."3";
	$temp4 = $VarStr2."4";
/*
	if ($VarMain!='') {
		echo "<a id='$temp4' href='$upload_path_main$VarMain' target=_blank title='دانلود تصویر'>
		<i class='fa fa-download'></i></a>  ";
		$photo_size = @filesize_formatted("$upload_path_main$VarMain");
		echo "حجم تصویر: ". $photo_size;
	}else{
		echo "<BR>";
	}
*/	
	echo "
	<input class='form-control' style='direction:ltr;' readonly type='hidden' name='$VarStr1' id='$VarStr1' value='$VarMain' > 
	<div class='dropify-wrapper'>
		<input class='fileinput' type='file' accept='image/x-png,image/gif,image/jpeg'  name='$VarStr2' id='$VarStr2' onchange='readURL(this,this.name);'> 
		<img id='$temp1' src='' style='width:100%;height:100%;' >
		<div id='$temp2' class='icondelete' style='display:none;' title='حذف تصویر'  onclick=\"delimage('$VarStr1','$VarStr2');\" >
			<i class='fa fa-times-circle' style='color:red;'></i>
		</div>
		<div id='$temp3' class='fileInputIconCentered' >
			<i class='fa fa-upload' style='font-size:50px;' title='انتخاب فایل'></i><BR>انتخاب تصویر
			<BR><span class='text-danger' style='font-size:9pt;'> حداکثر حجم ".($maxsizephoto )." مگابایت </span>
		</div>
	</div>
	
	";	
}
//-----------------------------------
function GetPhotoJs($VarMain, $VarStr1, $VarStr2) {
	global $upload_path_main, $maxsizephoto;
	$temp1 = $VarStr2."1";
	$temp2 = $VarStr2."2";
	$temp3 = $VarStr2."3";
	$temp4 = $VarStr2."4";
	if ($VarMain!='') {
		echo "<a id='$temp4' href='$upload_path_main$VarMain' target=_blank title='دانلود تصویر'>
		<i class='fa fa-download'></i></a>  ";
		$photo_size = @filesize_formatted("$upload_path_main$VarMain");
		echo "حجم تصویر: ". $photo_size;
	}else{
		echo "<BR>";
	}
	echo "
	<input class='form-control' style='direction:ltr;' placeholder='فایل تصویر در سرور' readonly type='text' name='$VarStr1' id='$VarStr1' value='$VarMain' > 
	<div class='dropify-wrapper'>
		<input class='fileinput' type='file' accept='image/x-png,image/gif,image/jpeg'  name='$VarStr2' id='$VarStr2' onchange='readURL(this,this.name);'> 
		<img id='$temp1' src='' style='width:100%;height:100%;' >
		<div id='$temp2' class='icondelete' style='display:none;' title='حذف تصویر'  onclick=\"delimage('$VarStr1','$VarStr2');\" >
			<i class='fa fa-times-circle' style='color:red;'></i>
		</div>
		<div id='$temp3' class='fileInputIconCentered' >
			<i class='fa fa-upload' style='font-size:50px;' title='انتخاب فایل'></i><BR>انتخاب تصویر
			<BR><span class='text-danger' style='font-size:9pt;'> حداکثر حجم ".($maxsizephoto )." مگابایت </span>
		</div>
	</div>
	
	";	
}
//---------------------------------------------
function ShowPhotoJs($VarStr1, $VarStr2) {
	global $upload_path_main;
	$temp1 = $VarStr2."1";
	$temp2 = $VarStr2."2";
	$temp3 = $VarStr2."3";
	
	echo "
    var imageurl = $('#$VarStr1').val();
	if (imageurl!='') { 
		var ImgId    = '#$temp1';
		var DeleteId = '#$temp2';	
		var IconId   = '#$temp3';
		var ImgContent = '$upload_path_main' + imageurl;
        $(ImgId).attr('src', ImgContent);
        $(DeleteId).css('display', 'block');	
		$(IconId).css('display', 'none');        
		
	}
	";	
}
//-------------------------------------------
function GetListField($table, $type=0, $exclude='') {
	$ret = array();
	$result = pdo_query("SHOW COLUMNS FROM $table ");
	if (pdo_rowcount($result) > 0) {
		$counter=0;
		while ($row = pdo_fetch($result)) {
			$ret[$counter]= $row['Field'];
			$counter++;
		}
	}
	if ($exclude!='') {
		$List2 = array_filter(explode(',',$exclude));
		$List3 = array_filter($ret, function($value) use ($List2) {return !in_array($value, $List2);});
		$ret = $List3;
	}
	if ($type==0) return $ret;
	$temp = implode("', '",$ret);
	$tmp = "$".$table ." = array('$temp'); ";
	return $tmp;

}
//-------------------------------------------
function InsertCode($table) {
	$result = pdo_query("SHOW COLUMNS FROM $table ");
	$ListField='';		$ListVar = "";		$ListArray= "";
	if (pdo_rowcount($result) > 0) {
		$counter=0;
		while ($row = pdo_fetch($result)) {
			$field = $row['Field'];
			$ListField .= "$field, ";
			$ListVar   .= ":$field, ";
			$ListArray   .= "':$field'=>$$field, ";
			$counter++;
		}
	}
	
	if ($counter==0) return '';
	$ListField 	= mb_substr($ListField,0,-2,'utf8');
	$ListVar 	= mb_substr($ListVar,0,-2,'utf8');
	$ListArray	= mb_substr($ListArray,0,-2,'utf8');
	$temp = "
	#SQL_QUERY= \"insert into `#bankname` 	($ListField)  \r\n
	values \r\n 	($ListVar) \" \r\n;
	#query_array = array($ListArray); ";
	$temp = str_replace("#","$",$temp);
	return "<div align=left dir=ltr>$temp</div>";

}
//-------------------------------------------
function UpdateCode($table) {
	$result = pdo_query("SHOW COLUMNS FROM $table ");
	$update='';		$ListArray= "";
	if (pdo_rowcount($result) > 0) {
		$counter=0;
		while ($row = pdo_fetch($result)) {
			$field = $row['Field'];
			$update .= "$field=:$field, ";
			$ListArray   .= "':$field'=>$$field, ";
			$counter++;
		}
	}
	
	if ($counter==0) return '';
	$update 	= mb_substr($update,0,-2,'utf8');
	$temp = "
	#SQL_QUERY= \"update `#bankname`  set $update	  \r\n where id_main=#id_main \" \r\n
	#query_array = array($ListArray); ";
	$temp = str_replace("#","$",$temp);
	return "<div align=left dir=ltr>$temp</div>";

}
//---------------------------------------
function GetTetrField($id, $table, $idfile='id_main', $namefield='name') {
	$temp_query = pdo_query("select $namefield from $table where $idfile='$id'",'',0);
	$temp_row   = pdo_fetch($temp_query);
	if ($temp_row=='') return '';
	return $temp_row[$namefield];
}
//---------------------------------------
function OkNumber2($input) {
	$input = EnglishNumber($input);
	$input = trim(str_replace(",","", $input));
	return intval($input);
}
//---------------------------------------
//-----------------------------------------------------------------
function getOS() { 
	$user_agent = $_SERVER['HTTP_USER_AGENT'];
	$os_platform =   "Unkhown";
	$os_array =   array(
		'/windows nt 10/i'      =>  'Windows 10',
		'/windows nt 6.3/i'     =>  'Windows 8.1',
		'/windows nt 6.2/i'     =>  'Windows 8',
		'/windows nt 6.1/i'     =>  'Windows 7',
		'/windows nt 6.0/i'     =>  'Windows Vista',
		'/windows nt 5.2/i'     =>  'Windows Server 2003/XP x64',
		'/windows nt 5.1/i'     =>  'Windows XP',
		'/windows xp/i'         =>  'Windows XP',
		'/windows nt 5.0/i'     =>  'Windows 2000',
		'/windows me/i'         =>  'Windows ME',
		'/win98/i'              =>  'Windows 98',
		'/win95/i'              =>  'Windows 95',
		'/win16/i'              =>  'Windows 3.11',
		'/macintosh|mac os x/i' =>  'Mac OS X',
		'/mac_powerpc/i'        =>  'Mac OS 9',
		'/linux/i'              =>  'Linux',
		'/unix/i'               =>  'Unix',
		'/ubuntu/i'             =>  'Ubuntu',
		'/iphone/i'             =>  'iPhone',
		'/ipod/i'               =>  'iPod',
		'/ipad/i'               =>  'iPad',
		'/android/i'            =>  'Android',
		'/blackberry/i'         =>  'BlackBerry',
		'/webos/i'              =>  'Mobile'
	);

	foreach ( $os_array as $regex => $value ) { 
		if ( preg_match($regex, $user_agent ) ) {
			$os_platform = $value;
		}
	}   
	return $os_platform;
}

/**
 * Kullanicinin kullandigi internet tarayici bilgisini alir.
 * 
 * @since 2.0
 */
function getBrowserNew() {
	$user_agent = $_SERVER['HTTP_USER_AGENT'];

	$browser        = "Unkhown";
	$browser_array  = array(
		'/msie/i'       =>  'Internet Explorer',
		'/firefox/i'    =>  'Firefox',
		'/safari/i'     =>  'Safari',
		'/chrome/i'     =>  'Chrome',
		'/edge/i'       =>  'Edge',
		'/opera/i'      =>  'Opera',
		'/netscape/i'   =>  'Netscape',
//		'/maxthon/i'    =>  'Maxthon',
//		'/konqueror/i'  =>  'Konqueror',
//		'/mobile/i'     =>  'Mobile Browser',
	);

	foreach ( $browser_array as $regex => $value ) { 
		if ( preg_match( $regex, $user_agent ) ) {
			$browser = $value;
		}
	}
	return $browser;
}

function getUserBaseLanguage() {
    global $_SERVER;
    $accept_languages           = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
    $accept_languages_arr       = explode(",",$accept_languages);
    foreach($accept_languages_arr as $accept_language) {
        preg_match ("/^(([a-zA-Z]+)(-([a-zA-Z]+)){0,1})(;q=([0-9.]+)){0,1}/" , $accept_language, $matches );
        if (!isset($matches[6])) {	$matches[6]=1;	}
        if (!isset($matches[4])) {	$matches[4]='';	}
		$result2[] = $matches[1];
        $result[$matches[1]] = array(
            'lng_base'  => $matches[2],
            'lng_ext'   => $matches[4],
            'lng'       => $matches[1],
            'priority'  => $matches[6],
            '_str'      => $accept_language,
        );
    }
//    return $result;
	return $result2;
//	return implode(',', $result2);
}
//------------------------------------------------------
function isMobile() {
    return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
}
//------------------------------------
function isSmartTV() {
    $userAgent = $_SERVER['HTTP_USER_AGENT'];

    // List of common smart TV user agent keywords
    $smartTVKeywords = [
        'SmartTV',
        'Samsung',
        'LG',
        'Sony',
        'Roku',
        'AppleTV',
        'Android TV',
        'WebOS',
        'Tizen'
    ];

    foreach ($smartTVKeywords as $keyword) {
        if (stripos($userAgent, $keyword) !== false) {
            return 1; // It's a smart TV
        }
    }
    return 0; // Not a smart TV
}
//--------------------------------------------
function isRobot() {
    $userAgent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';

    // List of typical robot user agent strings
    $robotStrings = array(
        'Googlebot',
        'Googlebot-Image',
        'Googlebot-Video',
        'Googlebot-Mobile',
        'Mediapartners-Google',
        'AdsBot-Google',
        'APIs-Google',
        'Google Web Preview',
        'FeedFetcher-Google',
        'Google-Read-Aloud',
        'bingbot',
        'Baiduspider',
        'YandexBot',
        'DuckDuckBot',
        'Slackbot',
        // Add other strings for other known robots
    );

    foreach ($robotStrings as $botString) {
        if (stripos($userAgent, $botString) !== false) {
            return true;
        }
    }

    return false;
}
//--------------------------------------
function isBotDetected() {

    if ( preg_match('/abacho|accona|AddThis|AdsBot|ahoy|AhrefsBot|AISearchBot|alexa|altavista|anthill|appie|applebot|arale|araneo|AraybOt|ariadne|arks|aspseek|ATN_Worldwide|Atomz|baiduspider|baidu|bbot|bingbot|bing|Bjaaland|BlackWidow|BotLink|bot|boxseabot|bspider|calif|CCBot|ChinaClaw|christcrawler|CMC\/0\.01|combine|confuzzledbot|contaxe|CoolBot|cosmos|crawler|crawlpaper|crawl|curl|cusco|cyberspyder|cydralspider|dataprovider|digger|DIIbot|DotBot|downloadexpress|DragonBot|DuckDuckBot|dwcp|EasouSpider|ebiness|ecollector|elfinbot|esculapio|ESI|esther|eStyle|Ezooms|facebookexternalhit|facebook|facebot|fastcrawler|FatBot|FDSE|FELIX IDE|fetch|fido|find|Firefly|fouineur|Freecrawl|froogle|gammaSpider|gazz|gcreep|geona|Getterrobo-Plus|get|girafabot|golem|googlebot|\-google|grabber|GrabNet|griffon|Gromit|gulliver|gulper|hambot|havIndex|hotwired|htdig|HTTrack|ia_archiver|iajabot|IDBot|Informant|InfoSeek|InfoSpiders|INGRID\/0\.1|inktomi|inspectorwww|Internet Cruiser Robot|irobot|Iron33|JBot|jcrawler|Jeeves|jobo|KDD\-Explorer|KIT\-Fireball|ko_yappo_robot|label\-grabber|larbin|legs|libwww-perl|linkedin|Linkidator|linkwalker|Lockon|logo_gif_crawler|Lycos|m2e|majesticsEO|marvin|mattie|mediafox|mediapartners|MerzScope|MindCrawler|MJ12bot|mod_pagespeed|moget|Motor|msnbot|muncher|muninn|MuscatFerret|MwdSearch|NationalDirectory|naverbot|NEC\-MeshExplorer|NetcraftSurveyAgent|NetScoop|NetSeer|newscan\-online|nil|none|Nutch|ObjectsSearch|Occam|openstat.ru\/Bot|packrat|pageboy|ParaSite|patric|pegasus|perlcrawler|phpdig|piltdownman|Pimptrain|pingdom|pinterest|pjspider|PlumtreeWebAccessor|PortalBSpider|psbot|rambler|Raven|RHCS|RixBot|roadrunner|Robbie|robi|RoboCrawl|robofox|Scooter|Scrubby|Search\-AU|searchprocess|search|SemrushBot|Senrigan|seznambot|Shagseeker|sharp\-info\-agent|sift|SimBot|Site Valet|SiteSucker|skymob|SLCrawler\/2\.0|slurp|snooper|solbot|speedy|spider_monkey|SpiderBot\/1\.0|spiderline|spider|suke|tach_bw|TechBOT|TechnoratiSnoop|templeton|teoma|titin|topiclink|twitterbot|twitter|UdmSearch|Ukonline|UnwindFetchor|URL_Spider_SQL|urlck|urlresolver|Valkyrie libwww\-perl|verticrawl|Victoria|void\-bot|Voyager|VWbot_K|wapspider|WebBandit\/1\.0|webcatcher|WebCopier|WebFindBot|WebLeacher|WebMechanic|WebMoose|webquest|webreaper|webspider|webs|WebWalker|WebZip|wget|whowhere|winona|wlm|WOLP|woriobot|WWWC|XGET|xing|yahoo|YandexBot|YandexMobileBot|yandex|yeti|Zeus/i', $_SERVER['HTTP_USER_AGENT'])
    ) {
        return true; // 'Above given bots detected'
    }

    return false;

} // End :: isBotDetected()

//----------------------------------------
function GetParentWithListId($id_temp, $array, $type_return=1) {
	// $type_return=1 ---> return array

	$ListId = explode(',',$id_temp);
	$ListId =array_filter($ListId);	
	$JamKol = count($ListId);
	$ListJavab = array();
	if ($type_return==1) $ListReturn=$ListJavab; else $ListReturn='';
	$javab=0;
	if ($JamKol==0) return $ListReturn;
	foreach ($ListId as $key => $value) {
		$idd = $value;
		while (true) {
			$row2  = $array[$idd];
			if ($row2!='') {
				$ListJavab[$javab] = $idd;
				$javab++;
				$idd= $row2['PARENT'];
				if ($idd==0) break;
			}
		}
	}
	if ($javab>0) {
		$ListJavab= array_unique($ListJavab);
		if ($type_return==1) $ListReturn=$ListJavab; else $ListReturn=implode(',',$ListJavab);
	}
	return $ListReturn;
}
//-------------------------------------------------------------
function LinkSeo($tetr) {
	$list1 = array("'",'"',' ');
	$list2 = array("",'','_');
	$ret = str_replace($list1, $list2, $tetr);
	return $ret;
}
//-------------------------------------------------------------
function EnjineSendSms($mobile,$Message) {
	global $row_setting;
	global $user_last_edit, $ip_last_edit, $date_last_edit;

	if ($row_setting['smstype']==0) { 
//		echo json_encode(array(-1,"هیچ سامانه پیامکی فعال نیست")); 
		return array(-1,"هیچ سامانه پیامکی فعال نیست",0);
	}
	$smsnumber = $row_setting['smsnumber'];
	//-------------------------------------------------
	if ($row_setting['smstype']==1) { 
		list($ValueReturn,$MessageReturn) = SendSms($mobile,$Message);
		$smsdeliveri=0;
	}
	if ($row_setting['smstype']==2) { 
		list($ValueReturn,$MessageReturn,$smsdeliveri) = SendSmsIranPanel($mobile, $Message, $row_setting['smsuser'], $row_setting['smspassword'], $row_setting['smsnumber']);
		if (is_null($smsdeliveri)) $smsdeliveri=0;
	}
	if ($row_setting['smstype']==3) { 
		@list($ValueReturn,$MessageReturn,$smsdeliveri) = SendSmsSignalOld($mobile, $Message, $row_setting['smsuser'], $row_setting['smspassword'], $row_setting['smsnumber']);
		if (is_null($smsdeliveri)) $smsdeliveri=0;
	}
	if ($ValueReturn<0) { 
		return array(-1,$MessageReturn,0);
	}
	//ثبت متن پیامک ارسالی در لوگ اس ام اس
	$sql=pdo_query("insert into `logsms` 
	(smsfrom, smsto, smsmessage, typesms, smsdeliveri, ip_last_edit, date_last_edit) 
	values ('$smsnumber','$mobile', '$Message', 1, '$smsdeliveri', '$ip_last_edit','$date_last_edit' )",'',0);
	return array($ValueReturn,$MessageReturn,$smsdeliveri);
}
//--------------------------------------------------------------------------
function DifTimeNow($date_save) {
	if ($date_save=='') return array(0,'');
	list($date1, $time1)=explode(' ', $date_save);
	$date1 = jalali_to_gregorian($date1);
	$given_timestamp  	= strtotime("$date1 $time1");
	$current_timestamp  = time();
	$time_difference_in_seconds = $current_timestamp - $given_timestamp;
	// محاسبه تعداد روزها
	// یک روز = 86400 ثانیه (24 * 60 * 60)
	$days = floor($time_difference_in_seconds / 86400);
	$time_difference_in_seconds %= 86400; // باقیمانده ثانیه‌ها بعد از جدا کردن روزها

	// محاسبه تعداد ساعت‌ها
	// یک ساعت = 3600 ثانیه (60 * 60)
	$hours = floor($time_difference_in_seconds / 3600);
	$time_difference_in_seconds %= 3600; // باقیمانده ثانیه‌ها بعد از جدا کردن ساعت‌ها

	// محاسبه تعداد دقیقه‌ها
	// یک دقیقه = 60 ثانیه
	$minutes = floor($time_difference_in_seconds / 60);
	$StrDifTime = '';
	if ($days!=0) $StrDifTime .= " [$days] روز قبل ";
	if ($hours!=0) $StrDifTime .= " [$hours] ساعت قبل ";
	if ($minutes!=0) $StrDifTime .= " [$minutes] دقیقه قبل ";
	$MaxHour = ($days*24)+$hours;
	return array($MaxHour, $StrDifTime);
	
	
}
//--------------------- ایجاد یه یونیک آی دی 16 رقم
function generate_uuid_v4(): string {
    // تولید 16 بایت تصادفی امن
    $data = random_bytes(16);

    // تنظیم بیت‌های نسخه (Version 4: 0100)
    // بایت ششم (Index 6) باید با 4 شروع شود
    $data[6] = chr(ord($data[6]) & 0x0f | 0x40); 
    
    // تنظیم بیت‌های نوع (Variant: 10xx)
    // بایت هشتم (Index 8) باید با 8, 9, a, یا b شروع شود
    $data[8] = chr(ord($data[8]) & 0x3f | 0x80); 

    // فرمت‌دهی خروجی به صورت استاندارد UUID: 8-4-4-4-12
    return vsprintf('%s%s-%s-%s-%s-%s', str_split(bin2hex($data), 4));
}
//-----------------------------------------------------------------
/// show message without boostrap
function ShowMessage3($message, $OkExit=0, $colorType='danger') {
//    $message = htmlspecialchars(nl2br($message), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
	
    // تعیین رنگ پس‌زمینه مشابه Bootstrap
    switch($colorType) {
        case 'success':
            $bg = '#28a745'; // سبز
            break;
        case 'warning':
            $bg = '#ffc107'; // زرد
            break;
        case 'info':
            $bg = '#17a2b8'; // آبی
            break;
        case 'primary':
            $bg = '#007bff'; // آبی پررنگ
            break;
        case 'dark':
            $bg = '#343a40'; // تیره
            break;
        default:
            $bg = '#dc3545'; // danger (قرمز)
    }

    echo "
	<html>
	<head></head>
	<body dir='rtl'>
    <div style='width:100%;display:flex; justify-content:center; margin-top:40px; margin-bottom:40px; auto'>
        <div style='width:75%;background:$bg;border-radius:6px;padding:20px;color:#fff;text-align:center;
            font-family:Tahoma, sans-serif;font-size:16px; white-space:pre-wrap; word-wrap: break-word; overflow-wrap: break-word; word-break: break-word;'>
            $message
        </div>
    </div>
	</body>
	</html>
    ";

    if ($OkExit != 0) {
        exit;
    }
}
//-------------------
function SafeHtmlTruncate(string $text, int $max_length, string $encoding = 'UTF-8'){
    $ellipsis = '...';
    
    // 1. محاسبه طول واقعی متن (بدون تگ‌های HTML)
    // این روش دقیق‌تر است: ابتدا تگ‌ها را حذف کرده، سپس طول واقعی را چک می‌کنیم
    $pure_text = strip_tags($text);

    if (mb_strlen($pure_text, $encoding) <= $max_length) {
        return $text;
    }
    
    // --- ۱. برش متن تا جایی که تعداد کاراکترهای "خالص" به max_length برسد ---
    
    // یافتن موقعیت دقیق برش در متن اصلی (با در نظر گرفتن تگ‌ها)
    $char_count = 0;
    $cut_position = 0;
    
    // عبور از کاراکترها برای شمارش دقیق (با پشتیبانی از UTF-8)
    for ($i = 0; $i < mb_strlen($text, $encoding); $i++) {
        $char = mb_substr($text, $i, 1, $encoding);
        
        // اگر کاراکتر، بخشی از تگ HTML نباشد
        if ($char !== '<') {
            $char_count++;
            if ($char_count > $max_length) {
                // نقطه برش را پیدا کردیم و 1 کاراکتر قبل از پایان متن اصلی است
                $cut_position = $i; 
                break;
            }
        }
        
        // اگر به تگ برخورد کنیم، موقعیت برش را به‌روز می‌کنیم
        $cut_position = $i + 1;
        
        // اگر به '<' رسیدیم، باید تا '>' جلو برویم تا تگ کامل شود.
        if ($char === '<') {
            $next_gt = mb_strpos($text, '>', $i, $encoding);
            if ($next_gt !== false) {
                $i = $next_gt; // پرش به انتهای تگ
                $cut_position = $i + 1;
            }
        }
    }
    
    // بریدن متن اصلی تا موقعیت محاسبه شده
    $trimmed_text = mb_substr($text, 0, $cut_position, $encoding);
    
    // --- ۲. شناسایی تگ‌های باز و بسته نشده (روش پشته) ---
    
    // عبارت باقاعده دقیق‌تر برای یافتن تگ‌ها (شامل تگ‌های با خصوصیات و /)
    $pattern = '/<\s*(\/?\s*[\w]+)(?:\s+[^>]*)*>/i';
    
    // پیدا کردن تمام تگ‌ها در متن بریده شده
    preg_match_all($pattern, $trimmed_text, $tags);
    
    // پشته (Stack) برای نگهداری تگ‌های باز
    $open_tags = [];
    $self_closing_tags = ['br', 'hr', 'img', 'input', 'meta', 'link', 'area', 'base', 'col', 'command', 'embed', 'keygen', 'param', 'source', 'track', 'wbr'];

    foreach ($tags[1] as $tag) {
        $tag_name = strtolower(trim(ltrim($tag, '/')));
        
        // اگر تگ بسته بود
        if (strpos($tag, '/') === 0) {
            // از پشته حذف کن (آخرین تگ باز متناظر)
            if (($key = array_search($tag_name, $open_tags)) !== false) {
                unset($open_tags[$key]);
            }
        } 
        // اگر تگ باز بود و جزو تگ‌های خود-بسته (self-closing) نبود
        elseif (!in_array($tag_name, $self_closing_tags)) {
            // اضافه کردن به ابتدای پشته (LIFO)
            array_unshift($open_tags, $tag_name);
        }
    }
    
    // --- ۳. بستن تگ‌های باقی‌مانده ---
    $close_tags = '';
    
    // بستن تگ‌ها به ترتیب معکوس (LIFO: آخرین باز شده، اولین بسته می‌شود)
    foreach ($open_tags as $tag) {
        $close_tags .= '</' . $tag . '>';
    }

    // --- ۴. بازگرداندن متن نهایی ---
    // افزودن تگ‌های بسته در انتهای متن بریده شده
    return $trimmed_text . $ellipsis . $close_tags;
}

//--------------------------------------------
function GetWeblogTopics($input, $WithLink=0) {
	$protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
	$server_name = $_SERVER['SERVER_NAME'] ;
	if (substr($server_name, 0, 4) !== 'www.') {		$server_name = 'www.' . $server_name;	}
	$sitenamelink = $protocol . $server_name;
	
	$result  = pdo_query("select * from `group_main` where isdeleted=0 and active=1 order by parent, idsort ",'',0);
	$GroupProduct 	= array();		
	$GroupWeblog	 = array();
	while($row = pdo_fetch($result)) { 	
		if ($row['type_group']==4) {
			$GroupProduct[$row['id']] = array("ID" => $row['id'], "PARENT" => $row['parent'], "NAME" => $row['name']);  
		}
		if ($row['type_group']==5) {
			$GroupWeblog[$row['id']] = array("ID" => $row['id'], "PARENT" => $row['parent'], "NAME" => $row['name']);   
		}
	}
	$ret = '';
	$ListTopics = array_filter(explode(',',$input));
	if (count($ListTopics)==0) return '';

	foreach($ListTopics as $key=>$value) {
		$temp = $GroupWeblog[$value];
		$id     = $temp['ID'];		$parent = $temp['PARENT'];			$tetr = $temp['NAME'];
		if ($WithLink>0) {
			$ret  .= "<a href='$sitenamelink/CategoryWeblog/$id'>$tetr</a> - ";
		}else{
			$ret  .= "$tetr - ";
		}
	}
	if ($ret!='') $ret = mb_substr($ret,0,-3);
	return $ret;
}
//--------------------------------------------
function GetPrdocutTopics($input, $WithLink=0) {
	$protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
	$server_name = $_SERVER['SERVER_NAME'] ;
	if (substr($server_name, 0, 4) !== 'www.') {		$server_name = 'www.' . $server_name;	}
	$sitenamelink = $protocol . $server_name;
	
	$result  = pdo_query("select * from `group_main` where isdeleted=0 and active=1 order by parent, idsort ",'',0);
	$GroupProduct 	= array();		
	$GroupWeblog	 = array();
	while($row = pdo_fetch($result)) { 	
		if ($row['type_group']==4) {
			$GroupProduct[$row['id']] = array("ID" => $row['id'], "PARENT" => $row['parent'], "NAME" => $row['name']);  
		}
		if ($row['type_group']==5) {
			$GroupWeblog[$row['id']] = array("ID" => $row['id'], "PARENT" => $row['parent'], "NAME" => $row['name']);   
		}
	}
	$ret = '';

	$ListTopics = array_filter(explode(',',$input));
	if (count($ListTopics)==0) return '';

	foreach($ListTopics as $key=>$value) {
		$temp = $GroupProduct[$value];
		$id     = $temp['ID'];		$parent = $temp['PARENT'];			$tetr = $temp['NAME'];
		if ($WithLink>0) {
			$ret  .= "<a href='$sitenamelink/CategoryProduct/$id'>$tetr</a> - ";
		}else{
			$ret  .= "$tetr - ";
		}
	}
	if ($ret!='') $ret = mb_substr($ret,0,-3);
	return $ret;
}
//--------------------------------------------
function is_valid_mobile_ir($mobile, $IsEmpty=0) {
    // حذف فاصله و کاراکترهای اضافی
    $mobile = trim($mobile);
	if ($IsEmpty==0) {
		if (($mobile=='')) return true;
	}

    // اگر با 98+ شروع شده باشد تبدیل شود به 0
    if (preg_match('/^\+?98/', $mobile)) {
        $mobile = preg_replace('/^\+?98/', '0', $mobile);
    }

    // اگر با 0098 شروع شده باشد
    if (preg_match('/^0098/', $mobile)) {
        $mobile = preg_replace('/^0098/', '0', $mobile);
    }

    // حالا چک می‌کنیم طول و ساختار صحیح باشد
    // الگوی مناسب: 09xxxxxxxxx (11 رقم)
    if (preg_match('/^09[0-9]{9}$/', $mobile)) {
        return true;
    }

    return false;
}
//------------------------------
function GetRealIp() {
    if (!empty($_SERVER['HTTP_CF_CONNECTING_IP'])) {
        // اگر از کلودفلر استفاده می‌کنید
        $ip = $_SERVER['HTTP_CF_CONNECTING_IP'];
    } elseif (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        // اگر از پراکسی‌های دیگر استفاده می‌کنید
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        // برای پراکسی‌های متداول
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        // در حالت عادی
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}
//-----------------------------
function GetFullAddres($id, $row='') {
	global $usernamefarsi;
	if (($id==0) and ($row=='')) return array('','','');
	if ($id>0) {
		$temp = pdo_query("select * from `user_addres` where id_main='$id'");
		$row  = pdo_fetch($temp);
	}
	$title = $row['tetr'];
	$ret = '';
	$id_ostan = $row['id_ostan'];
	if ($id_ostan!=0) {
		$temp   = pdo_query("select id,name from `ostan` where id='$id_ostan' ",'',0);
		$row2	= pdo_fetch($temp);
		$ret .= " استان $row2[name] - ";
	}
	$id_city  = $row['id_city'];
	if ($id_city!=0) {
		$temp   = pdo_query("select id,name from `city` where id='$id_city' ",'',0);
		$row2	= pdo_fetch($temp);
		$ret .= " شهر $row2[name] - ";
	}
	$id_sector= $row['id_sector'];
	if ($id_sector!=0) {
		$temp   = pdo_query("select id,name from `sector` where id='$id_sector' ",'',0);
		$row2	= pdo_fetch($temp);
		$ret .= " محله $row2[name] - ";
	}
	$ret .= " $row[addres] - کدپستی: $row[zipcode]";
	if (!isset($usernamefarsi)) $usernamefarsi='';
	$dsc_text = "$ret \r\n تحویل گیرنده: $row[recip_name] | $row[recip_mobile]";
	$dsc_web  = "$ret <BR> <b>تحویل گیرنده:</b> $row[recip_name] | $row[recip_mobile]";
	return array($title, $dsc_text, $dsc_web);
//	return $ret;
	
}



//-------------------------------------------------------------
function SendSmsFromTable($mobile,$Message, $row) {
	global $user_last_edit, $ip_last_edit, $date_last_edit;

	if ( (!isset($row)) or (!is_array($row)) or (count($row)==0) ) {
		return array(-1,"اطلاعات سامانه ارسال پیامک ، صحیح نمی باشد",0);
	}
	if ($row['smsnumber']=='')  return array(-1,"شماره ارسال کننده مربوط به این سامانه ثبت نشده است");
	
	if ($mobile=='') return array(-1,'شماره موبایل گیرنده ارسال نشده') ;
	if ($Message=='') return array(-1,'متن پیامک ارسال نشده') ;
	

	$idrec 		= $row['id_main'];
	$tetr		= $row['name'];
	$smsnumber 	= $row['smsnumber'];
	if (mb_substr($Message,-5)!='لغو11') { 	$Message .= " لغو11";}
	
	//-------------------------------------------------
	if ($row['smstype']==1) { 
		// استفاده از روش قدیم کتابخانه soap جهت ارسال پیامک
		//	list($ValueReturn,$MessageReturn) = SendSms($mobile,$Message);		$smsdeliveri=0;
		list($ValueReturn,$MessageReturn,$smsdeliveri) =  SendSmsTsms($mobile, $Message, $row);
	}
	if ($row['smstype']==2) { 
		list($ValueReturn,$MessageReturn,$smsdeliveri) = SendSmsIranPanelNew($mobile, $Message, $row);
	}
	if ($row['smstype']==3) { 
//		list($ValueReturn,$MessageReturn,$smsdeliveri) = SendSmsSignalWithOutApikey($mobile, $Message, $row);
		@list($ValueReturn,$MessageReturn,$smsdeliveri) = SendSmsSignal($mobile, $Message, $row);
		if (is_null($smsdeliveri)) $smsdeliveri=0;
	}
		if (is_null($smsdeliveri)) $smsdeliveri=0;
	if ($ValueReturn<0) { 
		return array(-1,$MessageReturn,0);
	}
	//ثبت متن پیامک ارسالی در لوگ اس ام اس
	$sql=pdo_query("insert into `logsms` 
	(smsfrom, smsto, smsmessage, typesms, smsdeliveri, idrec, name, ip_last_edit, date_last_edit, user_last_edit) 
	values ('$smsnumber','$mobile', '$Message', 1, '$smsdeliveri', '$idrec', '$tetr', '$ip_last_edit','$date_last_edit', '$user_last_edit' )",'',0);
	return array($ValueReturn,$MessageReturn);
}
//---------------------------------------------------------------------------
function SendPatternFromTable($mobile, $variable, $row) {
	global $user_last_edit, $ip_last_edit, $date_last_edit;

	if ($mobile=='') return array(-1,'شماره موبایل گیرنده ارسال نشده') ;
	if ( (!isset($row)) or (!is_array($row)) or (count($row)==0) ) {
		return array(-1,"اطلاعات سامانه ارسال پیامک ، صحیح نمی باشد");
	}
	if ( (!isset($variable)) or (!is_array($variable)) or (count($variable)==0) ) {
		return array(-1,"اطلاعات متغیرهای ارسالی برای پیامک ، صحیح نمی باشد");
	}
	
	if ($row['apikey']=='')  	return array(-1,"apikey مربوط به این سامانه ثبت نشده است");
	if ($row['message']=='')  	return array(-1,"الگوی متن ارسالی، مربوط به این سامانه ثبت نشده است");
	if ($row['id_pattern']=='') return array(-1,"کد پترن  مربوط به این سامانه ثبت نشده است");
	if ($row['smsnumber']=='')  return array(-1,"شماره ارسال کننده مربوط به این سامانه ثبت نشده است");
	if ($row['code_pattern1']=='')  return array(-1,"متغیر پترن مربوط به این سامانه ثبت نشده است");
	if (count($variable)>1) {
		for($iz=1;$iz<count($variable);$iz++) {
			$ij=$iz+1;
			$temp = "code_pattern".$ij;
			if ($row[$temp]=='')  {
				return array(-1,"متغیر پترن $ij مربوط به این سامانه ثبت نشده است");
				break;
			}
			
		}
	}
	$idrec 		= $row['id_main'];
	$tetr		= $row['name'];
	$smsnumber  = $row['smsnumber'];
	//------------------ ایجاد متن ارسالی
	$Message = $row['message'];
	foreach($variable as $key=>$value) {
		$temp = 'code_pattern'.($key+1);
		$_search = $row[$temp];
		$_replace = $value;
		$Message = str_replace($_search, $_replace, $Message);
	}
	if (mb_substr($Message,-5)!='لغو11') { 	$Message .= " لغو11";}
	
	//------------------------------------------------------
	if ($row['smstype']==1) { 
		@list($ValueReturn,$MessageReturn,$smsdeliveri) =  SendSmsTsms($mobile, $Message, $row);
	}
	if ($row['smstype']==2) { 
		@list($ValueReturn,$MessageReturn,$smsdeliveri) = SendPatternIranPanel($mobile, $variable, $row);
	}
	if ($row['smstype']==3) { 
		@list($ValueReturn,$MessageReturn,$smsdeliveri) = SendPatternSignal($mobile, $variable, $row);
	}
	if (is_null($smsdeliveri)) $smsdeliveri=0;
	if ($ValueReturn<0) { 
		return array(-1,$MessageReturn,0);
	}
	//ثبت متن پیامک ارسالی در لوگ اس ام اس
	$sql=pdo_query("insert into `logsms` 
	(smsfrom, smsto, smsmessage, typesms, smsdeliveri, idrec, name, ip_last_edit, date_last_edit, user_last_edit) 
	values ('$smsnumber','$mobile', '$Message', 1, '$smsdeliveri', '$idrec', '$tetr', '$ip_last_edit','$date_last_edit', '$user_last_edit' )",'',0);
	return array($ValueReturn,$MessageReturn);
}
//---------------------------------------------------------------------------
function SendPatternIranPanel($mobile, $variable, $row) {
	if ( (!isset($row)) or (!is_array($row)) or (count($row)==0) ) {
		return array(-1,"اطلاعات سامانه ارسال پیامک ، صحیح نمی باشد");
	}
	if ($mobile=='') 	return array(-1,"شماره دریافت کننده ثبت نشده است",0);
	
	$apikey 	= $row['apikey'];
	if ($apikey=='')  		return array(-1,'برای این سامانه کد توکن (apikey) تعریف نشده است ');
	
	$id_pattern = $row['id_pattern'];
	if ($id_pattern=='')  		return array(-1,'کد پترن در سامانه ippanel در سامانه تعریف نشده است ');
	
	$smsnumber	= $row['smsnumber'];
	if ($smsnumber=='')  		return array(-1,'شماره ارسال کننده تعریف نشده است ');
	
	$new_var 	= array();
	for($iz=0;$iz<count($variable);$iz++) {
		$ij=$iz+1;
		$temp = "code_pattern".$ij;
		if ($row[$temp]!='')  {
			$temp2 = $row[$temp];
			$new_var = ["$temp2" => $variable[$iz] ];
		}
	}
	
	$url = "https://api2.ippanel.com/api/v1/sms/pattern/normal/send";
	$data = json_encode(['code' => $id_pattern, 'sender' => $smsnumber, 'recipient' => $mobile, 'variable' => $new_var	]);

	$handler = curl_init($url);
	curl_setopt($handler, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($handler, CURLOPT_POST, true);
	curl_setopt($handler, CURLOPT_POSTFIELDS, $data);
	curl_setopt($handler, CURLOPT_HTTPHEADER, [
		'accept: */*', 'apikey: ' . $apikey, 'Content-Type: application/json'	]);
	$response = curl_exec($handler);
	if (curl_errno($handler)) { return array(-1,curl_error($handler)); }
	if (!$response) { return array(-1,'پاسخی از سمت سامانه پیامکی دریافت نشد'); }
    $response2 = @json_decode($response, true);
	if (!is_array($response2)) {return array(-1,'پاسخی از سمت سامانه پیامکی دریافت نشد2'); }
	$code 			= isset($response2['code']) ? $response2['code'] : 0;
	$error_message 	= isset($response2['error_message']) ? $response2['error_message'] : '';
	$message_id 	= isset($response2['message_id']) ? $response2['message_id'] : 0;
	if ($code==200) { return array(0,'پیامک با موفقیت ارسال شد',$message_id); }

	$ErrorMsg = "$error_message - کد خطا: $code";
	return array(-1,$ErrorMsg,$res_data); 
}
//---------------------------------------------------------------------------
function SendPatternSignal($mobile, $variable, $row) {
	if ( (!isset($row)) or (!is_array($row)) or (count($row)==0) ) {
		return array(-1,"اطلاعات سامانه ارسال پیامک ، صحیح نمی باشد");
	}
	if ($mobile=='') 	return array(-1,"شماره دریافت کننده ثبت نشده است",0);
	
	$apikey = $row['apikey'];
	if ($apikey=='')  		return array(-1,'برای این سامانه کد توکن (apikey) تعریف نشده است ');

	$smsnumber	= $row['smsnumber'];
	if ($smsnumber=='')  		return array(-1,'شماره ارسال کننده تعریف نشده است ');

	$id_pattern = $row['id_pattern'];
	if ($id_pattern=='')  		return array(-1,'کد پترن در سامانه سیگنال در سامانه تعریف نشده است ');
	//-----------------------------------------------------------------
	$new_var 	= array();
	for($iz=0;$iz<count($variable);$iz++) {
		$ij=$iz+1;
		$temp = "code_pattern".$ij;
		if ($row[$temp]!='')  {
			$temp2 = $row[$temp];
			$new_var = ["$temp2" => "'".$variable[$iz]."'" ];
		}
	}
	//-----------------------------------------------------------------
    $postData = [
	"from" => $smsnumber, 
	"pattern_id" => $id_pattern, 
	"number" => $mobile, 
	"parameters" => $new_var
	];
    $ch = curl_init();

    curl_setopt_array($ch, [
        CURLOPT_URL => "https://transmitor.signalads.com/api_v1/patterns/send",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode($postData),
        CURLOPT_HTTPHEADER => ["accept: */*","Authorization: Bearer $apikey","Content-Type: application/json"],
    ]);

    $response = curl_exec($ch);
//	echo "<pre>"; print_r($postData); echo "</pre>";
//	var_dump($response);

    // خطای curl
    if (curl_errno($ch)) {
        return [-1, 'خطا در اتصال به سرور: ' . curl_error($ch),0];
    }
    curl_close($ch);
	if (($response === false) or (is_null($response)) ) {
		return array(-1, "هیچ پاسخی از سمت سرور segnalads دریافت نشد",0); 
	}
    $result = json_decode($response, true);
    // بررسی success
    if (isset($result['success']) && $result['success'] === true) {
        return [1,'پیامک با موفقیت ارسال شد', $result['data']['id'] ];
    }
    // مدیریت خطاها
    if (isset($result['error'])) {
    	$message = (isset($result['messages'])) ? implode(' - ', $result['messages']) : 'خطای ناشناخته';
		return [-1, $result['error'] . " - $message",0]; 
    }
	return [-1, "اطلاعات درستی از سمت سامانه پیامکی دریافت نشد",0];
}
//------------------------------------------
/**
 * تبدیل تاریخ میلادی به قمری (الگوریتم کویتی/قراردادی)
 */
function gregorianToHijri($gy, $gm, $gd) {
    // 1. تبدیل به روز جولیان (Julian Day)
    if ($gm < 3) {
        $gy -= 1;
        $gm += 12;
    }
    
    $a = (int)($gy / 100);
    $b = (int)(2 - $a + (int)($a / 4));
    
    // فرمول دقیق برای تبدیل تاریخ میلادی به عدد صحیح روز جولیان
    $jd = (int)(365.25 * ($gy + 4716)) + (int)(30.6001 * ($gm + 1)) + $gd + $b - 1524;

    // 2. تنظیمات تقویم قمری (قراردادی)
    $l = $jd - 1948440 + 10632;
    $n = (int)(($l - 1) / 10631);
    $l = $l - 10631 * $n + 354;
    
    $j = (int)((int)((10985 - $l) / 5316)) * (int)((int)((50 * $l) / 17719)) + (int)((int)($l / 5670)) * (int)((int)((43 * $l) / 15238));
    $l = $l - (int)((int)((30 - $j) / 15)) * (int)((int)((17719 * $j) / 50)) - (int)((int)($j / 16)) * (int)((int)((15238 * $j) / 43)) + 29;
    
    // 3. استخراج ماه، روز و سال
    $m = (int)((24 * $l) / 709);
    $d = (int)($l - (int)((709 * $m) / 24));
    $y = (int)(30 * $n + $j - 30);

    return [
        'year'  => $y,
        'month' => $m,
        'day'   => $d,
        'format'=> "$y/$m/$d"
    ];
}
//------------------------------------------
function gregorianToHijri2($gy, $gm, $gd, $offset = 0) {
    // تبدیل تاریخ میلادی به روز جولیان
    if ($gm < 3) {
        $gy -= 1;
        $gm += 12;
    }
    
    $a = (int)($gy / 100);
    $b = (int)(2 - $a + (int)($a / 4));
    
    // اضافه کردن پارامتر offset برای تنظیم دستی اختلاف روز
    $jd = (int)(365.25 * ($gy + 4716)) + (int)(30.6001 * ($gm + 1)) + $gd + $b - 1524 + $offset;

    // محاسبات تقویم قمری
    $l = $jd - 1948440 + 10632;
    $n = (int)(($l - 1) / 10631);
    $l = $l - 10631 * $n + 354;
    
    $j = (int)((int)((10985 - $l) / 5316)) * (int)((int)((50 * $l) / 17719)) + (int)((int)($l / 5670)) * (int)((int)((43 * $l) / 15238));
    $l = $l - (int)((int)((30 - $j) / 15)) * (int)((int)((17719 * $j) / 50)) - (int)((int)($j / 16)) * (int)((int)((15238 * $j) / 43)) + 29;
    
    $m = (int)((24 * $l) / 709);
    $d = (int)($l - (int)((709 * $m) / 24));
    $y = (int)(30 * $n + $j - 30);

    return [
        'year'  => $y,
        'month' => $m,
        'day'   => $d,
        'format'=> "$y/" . str_pad($m, 2, "0", STR_PAD_LEFT) . "/" . str_pad($d, 2, "0", STR_PAD_LEFT)
    ];
}


//----------------------------------------------------
function detectRows($json){
    $data = json_decode($json,true);

    if(!$data) return [];

    // اگر مستقیم آرایه بود
    if(isset($data[0])){
        return $data;
    }

    // پیدا کردن اولین آرایه چند رکوردی
    foreach($data as $k=>$v){

        if(is_array($v) && isset($v[0])){
            return $v;
        }
    }

    return [];
}
//ه آرایه‌های تو‌در‌تو را تبدیل کند به یک آرایه یا متن ساده و تخت 🌱
//----------------------------------------------------
function flattenRow($array,$prefix=''){
    $result = [];
    foreach($array as $k=>$v){

        $newKey = ($prefix=='') ? $k : $prefix.'.'.$k;

        if(is_array($v)){

            if(isset($v[0]) && !is_array($v[0])){
                $result[$newKey] = implode(' | ',$v);
            }
            else{
                $result += flattenRow($v,$newKey);
            }

        }else{
            $result[$newKey] = $v;
        }
    }
    return $result;
}
//---------------------------------
//-----------------------------------------------------
function ConvertNumberValue($value){
    $value = trim($value);
    if($value==''){        return '';    }
    $value = EnglishNumber($value); // اعداد فارسی و عربی
    // حذف جداکننده هزارگان
    $value = str_replace(',','',$value);
    // حذف فاصله
    $value = str_replace(' ','',$value);
    return $value;
}
//-----------------------------------------------------
function IsNumericField($fieldType){
    $fieldType = strtolower($fieldType);

    return (
        strpos($fieldType,'int') !== false ||
        strpos($fieldType,'bigint') !== false ||
        strpos($fieldType,'float') !== false ||
        strpos($fieldType,'double') !== false ||
        strpos($fieldType,'decimal') !== false
    );
}
//---------------------------------
function ListContentInArray($Arrayname, $var) {
	if (!is_array($Arrayname)) return '';
	if ( (empty($var)) or (empty($Arrayname)) ) return '';
	$List1 = array_filter(explode(',', $var));	
	$ret = '';
	foreach($List1 as $key=>$value) {
		$temp = $Arrayname[$value] ?? '';
		if (!empty($temp))	$ret .= $temp . ',';
	}
	return $ret;
}