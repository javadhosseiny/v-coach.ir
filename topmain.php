<?php
//---------------
session_start();
$schema = 'HomePage';
$ListProductSchema=array();
if (isset($RunTop)===false) $RunTop=0;
$ErrorMsg='';
$upload_path_main = "/Files/";
require_once('includee/cn_front.php');
require_once('includee/function.php');
require_once('includee/date2.php');
require_once('./them_function.php');
$protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
$server_name = $_SERVER['SERVER_NAME'] ;
if (substr($server_name, 0, 4) !== 'www.') {
    // اگر شروع نشده باشد، "www." را به ابتدای آن اضافه می کند
//    $server_name = 'www.' . $server_name;
}
$sitenamelink = $protocol . $server_name;
$canonical = $sitenamelink;
$cpage = basename($_SERVER["PHP_SELF"]) ;
if (isset($_SERVER['HTTP_REFERER']))  $refer  = urldecode($_SERVER['HTTP_REFERER']); else $refer  = '';
if ($refer!='') {	$refer = str_replace($sitenamelink,'', $refer);}
$runpage  = basename($_SERVER['SCRIPT_NAME']);
$page  	  = urldecode($_SERVER['REQUEST_URI']);
$MainUrl =  "$protocol$_SERVER[HTTP_HOST]";
$main_url =  "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$main_url_decode = urldecode($main_url);
$main_url_escaped = htmlspecialchars($main_url, ENT_QUOTES, 'UTF-8');
//echo "runpage=$runpage <BR> page=$page <BR> MainUrl = $MainUrl <BR>  main_url=$main_url ";
$ip = getRealIpAddr();
get_refer();
//$ip = $_SERVER["HTTP_X_REAL_IP"];if (trim($ip)=='')   $ip = $_SERVER["REMOTE_ADDR"];
$ddate	 = new Date();	
$nowdate = $ddate->format("%Y/%m/%d");		
$nowtime = $ddate->format("%H:%M:%S");	
$nowyear = $ddate->format("%Y");
$nowdatetime = $nowdate.$nowtime; 
$shartdatetime = "and CONCAT(pdate,ptime)<='$nowdatetime'  ";
$shartdatetimeWeblog = "and CONCAT(date,time)<='$nowdatetime' ";
//-----------------------------------------------------------------------------------------
$ListTypeSend = array(1=>'منتظر ارسال', 2=>'ارسال شده', 3=>'توزیع شده', 4=>'برگشت داده شده', 5=>'لغو شده');
$ListTypePayment= array(1=>'پرداخت شده', 2=>'منتظر پرداخت', 3=>'پرداخت در محل', 4=>'برگشت داده شده');
$ListTypeOrder = array(1=>'سفارش تکمیل نشده', 2=>'سفارش پیگیری نشده', 3=>'در حال پیگیری', 4=>'انجام شده', 5=>'لغو شده');
$month_name_farsi=array('','فروردين','ارديبهشت','خرداد' ,'تير','مرداد','شهریور','مهر','آبان','آذر','دي'    ,'بهمن' ,'اسفند');


//-----------------------------------------------  vairable setting 
$meta	= '';
$query		= pdo_query("select * from `setting` LIMIT 0,1");
$row_setting= pdo_fetch($query);
if ($row_setting=='') {
//	$num = pdo_rowcount($query);	if ($num ==0) {	echo 'site not config';	exit;}
	$ErrorMsg="تنظیمات سایت صحیح نمی باشد <BR>";
}else{
	// در صورت نبودن لوگو برای اخبار و محصولات این عکس به عنوان لوگو نمایش مییابد
	$logofile 	 = $row_setting['logofile'];  
	if ($logofile!='')  $logofile 	 = $upload_path_main.$logofile;
	$logopage = $logofile;
	// برای صفحه نخست به عنوان تایتل سئو نمایش می یابد
	$title		 = $row_setting['tetr_firstpage'];		
	$title_main  = $title;
	// پیش فرض متا و در صورت اخبار و محصولات توضیحات آنها می آید
	$meta 		= $row_setting['meta'];
	$meta_dsc	= $row_setting['meta'];
	$keyword	= $row_setting['keyword'];
	$price_unit	= $row_setting['price_unit'];
	$shop_addres= $row_setting['shop_addres'];
}
//------------------------------------------ read sms setinng
$row_sms = array();
$query = pdo_query("select * from `sms_portal` where active=1 and isdeleted=0 ");
while ($row_temp = pdo_fetch($query)) {
	if ($row_temp['default_send']==1) $row_sms[0] = $row_temp;
	if ($row_temp['default_otp1']==1) $row_sms[1] = $row_temp;
	if ($row_temp['default_otp2']==1) $row_sms[2] = $row_temp;
	if ($row_temp['default_otp3']==1) $row_sms[3] = $row_temp;
	if ($row_temp['default_otp4']==1) $row_sms[4] = $row_temp;
	if ($row_temp['default_otp5']==1) $row_sms[5] = $row_temp;
		
}
//$sms_config = isset($row_sms[1]) ? $row_sms[1] : null;
//if (empty($sms_config)) {echo  'not config';} else {echo 'config'; var_dump($sms_config);}	exit;

if ($RunTop==1) return; // اگر قسمتی از سامانه تا به اینجا را نیاز داشت بخواند
//-------------------
$ok_cookie = false;		$usernameid=0;		$usernamefarsi='';				$userphoto='';
$row_user = checkLogin();
if ($row_user) {
	$ok_cookie = true;
	$usernameid 	= $row_user['id_main'];
	$usernamefarsi 	= $row_user['name'];
	$usermobile 	= $row_user['mobile'];
	$useremail      = $row_user['email'];
	$userpassword   = $row_user['password'];
	$userphoto		= $row_user['userphoto'];
	$useridostan 	= $row_user['id_ostan'];
	$useridcity 	= $row_user['id_city'];
	$useraddres		= $row_user['addres'];
	$userzipcode	= $row_user['zipcode'];
	if ($userphoto!='') $userphoto = $upload_path_main . $userphoto;
	//------------------------- check field refer_page
	if (empty($row_user['ref_page'])) {
		if (!empty($_COOKIE['refer_page']))   {    
			$refer_page = $_COOKIE['refer_page'];
			pdo_query("update `user_account`  set ref_page=:ref_page where id_main='$usernameid' ", array(":ref_page"=>$refer_page),0);
			setcookie('refer_page', '', time() - 3600, '/');
			unset($_COOKIE['refer_page']);
		}
	}
	//-------------------------
	include('logeventsuser.php');
}else{
	logout();
}
//--------------------------------------
//------------------ read cookie
/*
$ok_cookie = false;		
$cookie_name = 'student_user';	
$cookie_pass = 'student_pass';
$cookie_type = 'student_type';
if (isset($_COOKIE[$cookie_name]) &&  isset($_COOKIE[$cookie_pass]) &&  isset($_COOKIE[$cookie_type])  ) {
	$student_user = clean($_COOKIE[$cookie_name]); // در کوکی به صورت کد شده ذخیره شده md5()
	$student_pass = clean($_COOKIE[$cookie_pass]); // در کوکی به صورت دو بار کد شده ذخیره شده 
	$student_type = intval($_COOKIE[$cookie_type]); // نام کاربری براساس موبایل یا ایمیل
	if ($student_type==1) $UsernameField = 'mobile'; else $UsernameField = 'email'; 
	$student_user = md5($student_user);
	$student_pass = md5($student_pass);
	$query_array = array(':userName'=>$student_user, ':passWord'=> $student_pass );
	$query=pdo_query("select * from `user_account` where  md5(md5($UsernameField))=:userName and password=:passWord and active=1 and isdeleted=0  ",$query_array,0);
	$row_student = pdo_fetch($query);
	if ($row_student!='') {
		$ok_cookie = true;
		$usernameid 	= $row_student['id_main'];
		$usernamefarsi 	= $row_student['name'];
		$usermobile 	= $row_student['mobile'];
		$useremail      = $row_student['email'];
		$userpassword   = $row_student['password'];
		$userphoto		= $row_student['userphoto'];
		$useridostan 	= $row_student['id_ostan'];
		$useridcity 	= $row_student['id_city'];
		$useraddres		= $row_student['addres'];
		$userzipcode	= $row_student['zipcode'];
		if ($userphoto!='') $userphoto = $upload_path_main . $userphoto;
		include('logeventsuser.php');
	}else {
		@setcookie ($cookie_name);
		@setcookie ($cookie_pass);
		@setcookie ($cookie_type);
		unset($_COOKIE[$cookie_name]);		
		unset($_COOKIE[$cookie_pass]);		
		unset($_COOKIE[$cookie_type]);		
	}
}
*/
$user_last_edit = $usernamefarsi;
$ip_last_edit   = $ip;
$date_last_edit = "$nowdate $nowtime";

if ($RunTop==2) return; // اگر قسمتی از سامانه تا به اینجا را نیاز داشت بخواند
//-------------------------------------------------------------------------------
$TableGroup = 'group_main';
$result  = pdo_query("select * from `$TableGroup` where isdeleted=0 and active=1 order by parent, idsort ",'',0);
$GroupAll  = array();			$GroupMenu = array();		$GroupSearch=array();
$GroupProduct = array();		$GroupWeblog = array();
$HomePageLink ='';
while($row = pdo_fetch($result)) { 	
	$id		= $row['id'];
	$photo  = $row['photo_group'];
	if ($photo!='') $photo = $upload_path_main . $photo;
	$link  = $row['url_link'];
	if ($link=='') {
		if ($row['type_group']==1) {$link="/content/$id";}
		if ($row['type_group']==2) {$link="/gallery/$id";}
		if ($row['type_group']==3) {$link="/forms/$id";}
		if ($row['type_group']==4) {$link="/CategoryProduct/$id";}
		if ($row['type_group']==5) {$link="/CategoryWeblog/$id";}
	}
	if ($row['homepage']==1) $HomePageLink = $link;
	$GroupAll[$row['id']] = array("ID" => $row['id'],	"PARENT" => $row['parent'], 	"NAME" =>  $row['name'], "NEWPAGE"=> $row['open_newpage'], "SHOW"=>$row['show_in_menu'], "DOWNMENU"=>$row['show_down_page'], "PHOTO"=>$photo, "LINK"=>$link ); 
	if ($row['show_in_menu']==1) {
	$GroupMenu[$row['id']] = array("ID" => $row['id'],	"PARENT" => $row['parent'], 	"NAME" =>  $row['name'], "NEWPAGE"=> $row['open_newpage'], "SHOW"=>$row['show_in_menu'], "DOWNMENU"=>$row['show_down_page'], "PHOTO"=>$photo, "LINK"=>$link );
	}
	if ($row['type_group']==5) {
		$tetr_name = $row['name'];
		if ($row['parent']!=0) $tetr_name = "--> ".$tetr_name;
		$GroupSearch[$row['id']] = $tetr_name;
		$GroupProduct[$row['id']] = array("ID" => $row['id'], "PARENT" => $row['parent'], "NAME" => $row['name']);   
	}
	if ($row['type_group']==5) {
		$GroupWeblog[$row['id']] = array("ID" => $row['id'], "PARENT" => $row['parent'], "NAME" => $row['name']);   
	}
}

if ($RunTop==3) return; // اگر قسمتی از سامانه تا به اینجا را نیاز داشت بخواند
//---------------------------- get list of abzarak of them
$query = pdo_query("SELECT * FROM `them`  where active=1 and isdeleted=0");
$row_them = pdo_fetch($query);
if ($row_them=='')  $ErrorMsg="تمی برای این سایت تعریف نشده است <BR>";
$id_them = $row_them['id'];
//---------------------------- read shopping card : CountShoppingCard - PriceShoppingCard - ListShoppingCard
//.....

//$ok_cookie = true;		$usernamefarsi = 'سیدمحمدجوادحسینی';		$usernameid = 20;		// for test
//------------------------------------------------------------------------------------------
//درست کار نمی کند وقتی روی خانه کلیک می کنید صفحه پیش فرض خانه می آید
//if ($runpage == 'index.php') {	if ($HomePageLink!='') {header("location: $HomePageLink"); exit;}}
//--------------------------------------------- update useronline and visitor in site table
if ($row_setting['active_loger']==1) {
	SaveUserLog();
}
$useronline = GetUserOnline();
pdo_query("UPDATE setting SET onlineuser = '$useronline' , visituser =visituser+1 ") ;

//------------------------------------------------------------------------
function GetUserOnline() {
	global $ip,$page;
	$table = 'visitor_online';
	$MaxVisitIp = 500;
	$MaxTimeIp = 1 * 60 * 60; // 60 min
	$MaxTimeOnline= 5 * 60; // 5 min
	$timenow = time();
	
	$useronline = 0;
	pdo_query("DELETE FROM $table WHERE unix_timestamp() - lastvisit >= $MaxTimeIp");
	$uo_result = pdo_query("SELECT lastvisit,contor FROM $table WHERE ip = '$ip'");
	$row_temp =pdo_fetch($uo_result);
	if($row_temp=='') {
		pdo_query("INSERT INTO $table (ip, lastvisit, contor, pagevisit) VALUES('$ip', unix_timestamp(),'1','$page')",'',0);
	} else {
		$temp_contor = $row_temp['contor'];
		if ($MaxVisitIp>0) {if ($temp_contor>$MaxVisitIp) exit;		}
		pdo_query("UPDATE $table SET lastvisit = unix_timestamp(), contor=contor+1, pagevisit='$page' WHERE ip = '$ip' ",'',0);
	}
	$uo_result = pdo_query("SELECT count(*) FROM $table where unix_timestamp() - lastvisit <= $MaxTimeOnline  ");
	$useronline = pdo_count($uo_result);
	return $useronline;
	
}
//------------------------------------------------------------------------
function SaveUserLog() {
	global $nowdate, $nowtime,$ip,$refer,$page, $row_setting;
	$City=''; $Country='';
	$query = pdo_query("select * from visitor_ip where ip='$ip' ",'',0);
	$row_temp = pdo_fetch($query);
	if ($row_temp=='') {
		if ($row_setting['active_search_ip']==0) {
			$iplink   = @file_get_contents("http://ip-api.com/json/$ip");
			if ($iplink) {
				$ipstatus =json_decode($iplink,true);
				if ($ipstatus['status'] =='success') {
					$Country = $ipstatus['country'];
					$City = $ipstatus['city'];
				}
			}
		}
		if ($Country!='') {
			$ip2 = ip2long($ip);
			$query=pdo_query("SELECT * FROM ip2country WHERE ('$ip2') >= start_ip AND ('$ip2') <= end_ip;",'',0);
			$row = pdo_fetch($query);
			if ($row!='') { $Country= $row['country']; }
		}
//		if ($Country!='') {
			$query = pdo_query("insert into visitor_ip VALUES ('$ip','$Country','$City'); ",'',0);
//		}
	}else{
		$Country = $row_temp['country'];
		$City    = $row_temp['city'];
	}
	$query = pdo_query("select id,date from visitor_daily where date='$nowdate' ");
	$row_temp = pdo_fetch($query);
	if ($row_temp=='') {
		$query = pdo_query("insert into visitor_daily (date) values ('$nowdate'); ",'',0);
		$idrec = pdo_lastinsert();
	}else{
		$idrec = $row_temp['id'];
	}
	$os 	= getOS();
	$browser= getBrowserNew();
	$lang 	= getUserBaseLanguage();
	$mobile = isMobile();
	$tv 	= isSmartTV();
	$IpUniq = ListIpinDay();
	//--------------------------
	$SqlUpdate= '';
	$temp = ", os_other=os_other+1  ";
	if (strpos($os, "Windows")!==false) 	$temp = ", os_win=os_win+1  ";
	if (strpos($os, "Mac")!==false) 		$temp = ", os_mac=os_mac+1  ";
	if (in_array($os, array('Linux','Unix','Ubuntu')))	$temp = ", os_linux=os_linux+1  ";
	if (in_array($os, array('iPhone','iPod','iPad')))	$temp = ", os_iphone=os_iphone+1  ";
	if (in_array($os, array('Android','BlackBerry','Mobile')))	$temp = ", os_android=os_android+1  ";
	$SqlUpdate .= $temp;
	switch($browser) {
		case 'Internet Explorer': 	$SqlUpdate .= ", br_ie=br_ie+1  "; break;
		case 'Firefox': 			$SqlUpdate .= ", br_firefox=br_firefox+1  "; 			break;
		case 'Safari': 				$SqlUpdate .= ", br_safari=br_safari+1  "; break;
		case 'Edge': 				$SqlUpdate .= ", br_edge=br_edge+1  "; break;
		case 'Chrome': 				$SqlUpdate .= ", br_chrome=br_chrome+1  "; break;
		case 'Opera': 				$SqlUpdate .= ", br_opera=br_opera+1  "; break;
		case 'Netscape': 			$SqlUpdate .= ", br_netscape=br_netscape+1  "; break;
		default:					$SqlUpdate .= ", br_other=br_other+1  "; break;
	}
	if ($mobile==1)	$SqlUpdate .= ", device_mobile=device_mobile+1  "; 
		else if ($tv==1) $SqlUpdate .= ", device_tv=device_tv+1  "; 
			else 			$SqlUpdate .= ", device_desktop=device_desktop+1  "; 
			
	$temp = ", lang_other=lang_other+1  ";
	if (in_array('fa', $lang)) $temp = ", lang_fa=lang_fa+1  ";
	if (in_array('ar', $lang)) $temp = ", lang_ar=lang_ar+1  ";
	if (in_array('en', $lang)) $temp = ", lang_en=lang_en+1  ";
	$SqlUpdate .= $temp;
	if ($refer!='') {
		$refer2=str_replace(array('https://','http://','www.','/'),'',$refer);
		if (mb_substr($refer2,0,7)=='google.') $SqlUpdate .= ", ref_google=ref_google+1  ";

	}
	if ($IpUniq) 					$SqlUpdate .= ", visitor=visitor+1  ";
	if (isBotDetected())			$SqlUpdate .= ", count_robot=count_robot+1  ";
	//--------------------------
	$query = pdo_query("update visitor_daily set pageview=pageview+1 $SqlUpdate where id='$idrec' ",'',0);
	//-------------------
	$langStr = implode(',', $lang);
	$user_agent = $_SERVER['HTTP_USER_AGENT'];
	$query=pdo_query("insert into `visitor` (date , time, ip, refer_link, visit_link, country, city, os, browser, lang, user_agent)  values ('$nowdate', '$nowtime', '$ip', '$refer','$page', '$Country', '$City','$os', '$browser', '$langStr', '$user_agent')",'',0) ;
	
}
//-----------------------------
function ListIpinDay() {
	global $ip;
	$table = '';
	$uo_result = pdo_query("SELECT count(*) FROM `visitor_online` WHERE ip = '$ip'");
	$total =pdo_count($uo_result);
	if ($total==0) return true; else return false;
}
//-----------------------------
function getRealIpAddr() {
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

//-------------- checkLogin
function checkLogin(){
    if(isset($_SESSION['user'])){
		/*
		$id_user = intval($_SESSION['user']['id_main']);
		$query=pdo_query("select * from `user_account` where id_main='$id_user' and active=1 and isdeleted=0 ") ;
		$row_user  = pdo_fetch($query);
		if ($row_user=='') return false;
		$_SESSION['user']=$row_user;
		*/
        return $_SESSION['user'];
    }
	if(isset($_COOKIE['token'])){
		$token  = clean($_COOKIE['token']);
        $query  = pdo_query("SELECT * FROM user_token WHERE token=:token LIMIT 1", array(':token'=>$token),0);
		$row	= pdo_fetch($query);
        if($row && strtotime($row['expires_at']) > time()){
			$id_user = $row['id_user'];
			$query=pdo_query("select * from `user_account` where id_main='$id_user' and active=1 and isdeleted=0 ") ;
			$row_user  = pdo_fetch($query);
			if ($row_user=='') return false;
            $_SESSION['user']=$row_user;
            return $row_user;
        }
		$query  = pdo_query("delete FROM user_token WHERE token=:token ", array(':token'=>$token),0);
    }
    return false;
}

//-------------- logout
function logout(){
    if(!empty($_COOKIE['token'])){
		$token = clean($_COOKIE['token']);
		if ($usernameid==0) {
	        $query = pdo_query("DELETE FROM user_token WHERE token=:token", array(':token'=>$token),0);
		}else{
        	$query = pdo_query("DELETE FROM user_token WHERE id_user='$usernameid' ");
		}
		setcookie("token","",time()-3600,"/");
	//    session_destroy(); ---> بقیه سیشن ها رو هم از بین می بره لذا نیازی به استفاده ازش نیست
		if (isset($_SESSION['user'])) unset($_SESSION['user']);		
		if (isset($_COOKIE['token'])) unset($_COOKIE['token']);	
    }
}
//----------------------------------------------------------
function Login_User($id, $row='') {
	global $ip, $nowdatetime , $row_setting;
	
	$shop_name 		= $row_setting['shop_name'];	
	$timelogin = time();
	$id= intval($id);
	if (!is_array($row)) {
		$query=pdo_query("select * from `user_account` where id_main='$id' and active=1 and isdeleted=0 ") ;
		$row  = pdo_fetch($query);
		if ($row=='') return -1;
		
	}else {
		if ($id<=0) return -1;
	}
	$family = $row['name'];
	if ($row['active_mail_phone'] ==1) $fieldname='mobile'; else $fieldname='email';
	
	$query=pdo_query("update `user_account` set mismatch=0, lastlogin='$timelogin' where id_main='$id'");


    $token = bin2hex(random_bytes(32));
	$expire_time = time() + (60*60*24*3); // 3 روز
    $expire_db = date("Y-m-d H:i:s", $expire_time);	


    $query = pdo_query("INSERT INTO user_token (id_user, token, date, ip, expires_at) 
	VALUES ('$id', '$token','$nowdatetime', '$ip', '$expire_db' )	",'',0);
	
    setcookie("token",$token,['expires'=>$expire_time,'path'=>'/']);
	// ارسال ایمیل به کاربر به هنگام ورود به سایت
	$email_to_user=explode(',',$row_setting['email_to_user']);
	if (in_array(2,$email_to_user)) {			
		if ($row['active_mail_phone'] ==2) {
			$Message = " کاربر [$family] شما وارد پنل خود در فروشگاه  [$shop_name] شدید <BR> $shop_name";
			$OkSendEmail = SendEmail('',$email,"ورود به پنل کاربری [$shop_name]",$Message,'',0);
		}
	}
}

function get_refer() {
	if (!empty($_COOKIE['refer_page'])) {    return;}	
    if(isset($_SESSION['user']))		{ 	return; }

    $refer = null;
    if (!empty($_GET['utm_source'])) {
        $refer = $_GET['utm_source'];
	} 
    // 2. fallback به HTTP_REFERER
    if (empty($refer) && !empty($_SERVER['HTTP_REFERER'])) {

        $referrer = $_SERVER['HTTP_REFERER'];

        // فقط دامنه یا صفحه خلاصه‌شده (برای جلوگیری از داده سنگین)
        $parts = parse_url($referrer);

        if (!empty($parts['host'])) {
			$currentHost = $_SERVER['HTTP_HOST'];
			if ($parts['host'] !== $currentHost) {
                $refer = $parts['host'];
                // اگر مسیر هم خواستی
                //if (!empty($parts['path'])) { $refer .= $parts['path'];}
            }			
            $refer = $parts['host'];
        }
    }

    if (empty($refer) && !empty($_SERVER['HTTP_USER_AGENT'])) {

        $ua = strtolower($_SERVER['HTTP_USER_AGENT']);

        if (strpos($ua, 'instagram') !== false) { $refer = 'instagram';       }

        if (strpos($ua, 'telegram') !== false) 	{ $refer = 'telegram';        }

        if (strpos($ua, 'bale') !== false) {		$refer = 'bale';        }
        if (strpos($ua, 'whatsapp') !== false) {	$refer = 'whatsapp';	}
        if (strpos($ua, 'eitaa') !== false) {	$refer = 'eitaa';	}
		
		
    }
	// 3. اگر مقدار داریم → ذخیره
    if (!empty($refer)) {
        setcookie('refer_page', $refer, time() + (60 * 60 * 24 * 30), '/', '', false, true);
        $_COOKIE['refer_page'] = $refer;
    }
//	echo "refer=$refer";	
	//echo "<script>console.log('$refer');</script>";
	
}
?>
