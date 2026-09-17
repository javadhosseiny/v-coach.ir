<?php
require_once 'oauth_config.php';
//var_dump($_SESSION);
$ErrorMsg2= '';
// 1) Check for errors or missing params
if (isset($_GET['error'])) {
    // The user denied or an error occurred
    $ErrorMsg2='خطای دریافتی از گوگل: <BR>' . htmlspecialchars($_GET['error']); 
    include('LoginUser.php');    exit;
}
if ((!isset($_GET['code'])) or  (!isset($_GET['state'])) ) {
    $ErrorMsg2='پاسخ صحیحی از گوگل دریافت نشد';
    include('LoginUser.php');    exit;
}
// 2) Validate state
if ( (empty($_SESSION['oauth2_state'])) or ($_GET['state'] !== $_SESSION['oauth2_state']) ) {
    $ErrorMsg2='لینک ارتباطی از سایت ما به گوگل صحیح نمی باشد<BR>وضعیت نامعتبر، (احتمال CSRF)';
    include('LoginUser.php');    exit;
}
// Optional: unset state to prevent reuse
unset($_SESSION['oauth2_state']);


// 3) Exchange code for tokens

$code = $_GET['code'];
$code_verifier = isset($_SESSION['pkce_code_verifier']) ? $_SESSION['pkce_code_verifier'] : null;
if (!$code_verifier) {
    $ErrorMsg2  =   'کد لینک از سایت ما به گوگل صحیح نمی باشد<BR>تأییدکننده PKCE وجود ندارد';
    include('LoginUser.php');    exit;
}
unset($_SESSION['pkce_code_verifier']);

$postFields = [
    'code' => $code,
    'client_id' => GOOGLE_CLIENT_ID,
    'client_secret' => GOOGLE_CLIENT_SECRET,
    'redirect_uri' => GOOGLE_REDIRECT_URI,
    'grant_type' => 'authorization_code',
    'code_verifier' => $code_verifier
];

//var_dump($_SESSION);
//var_dump($_GET);
//var_dump($postFields);

try {
    $res = http_post(GOOGLE_TOKEN_ENDPOINT, $postFields);
} catch (Exception $e) {
    $ErrorMsg2  =   'خطا در توکن ارسالی به گوگل:  ' . $e->getMessage();
    include('LoginUser.php');    exit;
}
//var_dump($res);
if ($res['status'] !== 200) {
    $ErrorMsg2  =   'وضعیت نقطه پایان توکن از سمت گوگل ' . $res['status'] . ' - ' . htmlspecialchars($res['body']);
    include('LoginUser.php');    exit;
}

$tokens = json_decode($res['body'], true);
// Contains access_token, id_token, expires_in, refresh_token (if requested), scope, token_type

if (empty($tokens['id_token'])) {
    $ErrorMsg2  =   'توکن دریافتی از گوگل خالی می باشد';
    include('LoginUser.php');    exit;
}

// 4) Verify id_token (basic): use Google's tokeninfo endpoint
$idinfo = verify_id_token($tokens['id_token']);
if (!$idinfo) {
    $ErrorMsg2  =   'توکن دریافتی از گوگل صحیح نمی باشد.';
    include('LoginUser.php');    exit;
}
//--------------------------------
$userphoto='';
$id_sub = $idinfo['sub'];
if ($idinfo['name'])    $name = $idinfo['name']; else $name='';
if ($idinfo['email'])   $email = $idinfo['email']; else $email='';
if ($idinfo['picture']) $picture = $idinfo['picture']; else $picture='';
if (($name=='') or ($email=='')) {
    $ErrorMsg2  =   'اطلاعات دریافتی از گوگل شامل نام و یا ایمیل نمی باشد';
    include('LoginUser.php');    exit;
}
//-------------------------------------------
$active_mail_phone=2;   // فعال از طریق ایمیل
$query_array = array(':email'=>$email);
$query  = pdo_query("select * from `user_account` where email=:email and isdeleted=0 ",$query_array,0) ;
$row    = pdo_fetch($query);
if ($row!='') {
    // به محض ورود مجدد با اکانت گوگل پسورد به همان نام ایمیل تغییر می یابد
//    $password= md5($email);
//    $query2  = pdo_query("update `user_account` set password='$password' where email=:email and isdeleted=0 ",$query_array,0) ;
    $NewRec=false;
    //$active_mail_phone=3;   
    $id_user = $row['id_main'];
}else {
    if ($picture!='') {
        $nowdatetimeM = str_replace(array('/',':',' '),array('','','_'),$nowdatetime);    
        $photo = file_get_contents($picture);
        if ($photo) {
            $userphoto = "users/$nowdatetimeM.jpg";
            file_put_contents("Files/$userphoto",$photo);
        }
    }

    $NewRec=true;
    $password= md5($email);
    $query_array3 = array(':name'=>$name, ':email'=>$email);	
    $SQL_QUERY="insert into `user_account` (name, email, mobile, active, date, active_mail_phone , password, userphoto) 
    values (:name, :email, '', 1, '$nowdate', $active_mail_phone, '$password', '$userphoto' )";
    $sql     = pdo_query($SQL_QUERY,$query_array3,0);
	$id_user = pdo_lastinsert();
    
}
$Ok = Login_User($id_user);
if ($Ok==-1) { 
    $ErrorMsg2  =   'ورود درست انجام نشده است';
    include('LoginUser.php');    exit;
}
//---------------------------
if (!$NewRec) { header("location:/"); return;}
//-----------------------------
$family = $name;
$reg_type=1;
$shop_name = $row_setting['shop_name'];
// ارسال ایمیل به مدیر به هنگام ثبت نام کاربر
$email_to_manager=explode(',',$row_setting['email_to_manager']);
$email_manager = $row_setting['email_manager'];
if (in_array(2,$email_to_manager)) {			
    if (($reg_type==1) and ($email_manager!='')) {						
        $Message = " کاربر [$family] با ایمیل [$email] در فروشگاه ثبت نام کرد <BR> $shop_name ";
        $OkSendEmail = SendEmail('',$email_manager,"ثبت نام در سایت $shop_name",$Message,'',0);
    }
}
// ارسال ایمیل به کاربر به هنگام ثبت نام
$email_to_user=explode(',',$row_setting['email_to_user']);
if (in_array(6,$email_to_user)) {			
    if (($reg_type==1) ) {
        $Message = " کاربر [$family]شما با موفقیت در فروشگاه $shop_name ثبت نام کردید <BR> $shop_name";
        $OkSendEmail = SendEmail('',$email,"ثبت نام در سایت $shop_name",$Message,'',0);
    }
}
header("location:/profile/?gmail=$email");