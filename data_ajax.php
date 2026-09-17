<?php 
$RunTop=2; //  فایل تاب مین تا بعد از خواندن کوکی ورود کاربر خوانده شود
require_once('topmain.php');
//-----------------------------------------------------------------------
$host 	 = $_SERVER['HTTP_HOST'];
$origin  = $_SERVER['HTTP_ORIGIN']  ?? '';
$referer = $_SERVER['HTTP_REFERER'] ?? '';
$allowed = ["http://$host", "https://$host", "http://www.$host", "https://www.$host"];
$ok = false;
// 1. اگر ORIGIN هست → بررسی کن
if (!empty($origin)) {    if (in_array($origin, $allowed)) {        $ok = true;    }}
// 2. اگر ORIGIN نبود → برو سراغ REFERER
elseif (!empty($referer)) {
    foreach ($allowed as $a) {
        if (strpos($referer, $a) === 0) { $ok = true; break;}
    }
}
else {  $ok = false;  }
if (!$ok) {    echo json_encode([-1, 'دسترسی غیرمجاز']); RETURN;    exit;}
//-----------------------------------------------------------------------
$type	= intval($_POST['type']);
if ( ($type==0) ) {echo json_encode(array(-1,'اطلاعات اشتباه'));	return ;}
$ErrorMsg='';
//----------------------------------------------------------------------
$sms_time = 5;  // مدت وقفه زمان ارسال کد فعال سازی برای 
$sms_time = $row_setting['sms_time_users'];


//----------------------------------------------------------------------
$shop_name 		= $row_setting['shop_name'];
$sms_list_send  = explode(',',$row_setting['sms_list_send']);
$mobile_manager	= $row_setting['sms_manager'];

//----------------------------------------------
if ($type==1) {
	$name 		= $_POST['comment_name'];
	$email 		= $_POST['comment_mail'];
	$mobile		= $_POST['comment_mobile'];
	$subject	= $_POST['comment_subject'];
	$message	= $_POST['comment_message'];
	$userid = 0;
	if ($ok_cookie) {
		$userid = $usernameid;
		$name   = $usernamefarsi;
		$mobile = $usermobile;
		$email 	= $useremail;
	}
//	var_dump($_POST);
	if ($name=='')  	$ErrorMsg .= "تعیین نام و نام خانوادگی الزامی است<BR> ";   
	if ( ($email=='') and ($mobile=='') ) 	$ErrorMsg .= " تعیین ایمیل و یا موبایل الزامی است<BR> ";   
	if ($subject=='') 	$ErrorMsg .= "تعیین عنوان پیام الزامی است<BR> ";   
	if ($message=='') 	$ErrorMsg .= "تعیین متن پیغام الزامی است<BR> ";   
	if ($row_setting['ok_capcha_email']==1)  { 
		$sec_code1  = $_POST['CapchaSecCode1'];		
		$sec_code2  = $_POST['CapchaSecCode2'];
		if (md5($sec_code1) != $sec_code2) { $ErrorMsg .='کد کپچا صحیح نمی باشد'; } 
	}
	if ($row_setting['ok_capcha_email']==2)  { 
		$secretKey = $row_setting['google_secretkey'];
    	$recaptchaResponse = $_POST['g-recaptcha-response'] ?? '';
		// اگر توکنی ارسال نشده بود
    	if (empty($recaptchaResponse)) {        
			$ErrorMsg .='خطا: لطفاً چک‌باکس "من ربات نیستم" را تیک بزنید.';
		}else{
		// ارسال درخواست به گوگل با cURL
			$url = 'https://www.google.com/recaptcha/api/siteverify';
			$data = [
				'secret'   => $secretKey,
				'response' => $recaptchaResponse,
				'remoteip' => $_SERVER['REMOTE_ADDR']
			];

			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
			$response 	= curl_exec($ch);
			$curlError 	= curl_error($ch);
			curl_close($ch);
			if ($curlError) {
        		$ErrorMsg .='خطا در برقراری ارتباط با سرور کپچا.';
    		}else {
				$responseKeys = json_decode($response, true);
				// بررسی نتیجه راست‌آزمایی
				if (!empty($responseKeys['success'])) {
//					echo "تایید شد! فرم با موفقیت ثبت شد. (امتیاز: " . $responseKeys['score'] . ")";
				} else {
					// اعتبار کپچا تایید نشد یا امتیاز خیلی پایین است (ربات)
					$ErrorMsg .= "خطا: اعتبارسنجی کپچا ناموفق بود. لطفاً دوباره تلاش کنید.";
				}
			}	
		}
	}
	if ($row_setting['ok_capcha_email']==3)  { 
		$secretKey = $row_setting['google_secretkey'];
    	$recaptchaResponse = $_POST['recaptchaToken'] ?? '';	
		// اگر توکنی ارسال نشده بود
    	if (empty($recaptchaResponse)) {        
			$ErrorMsg .='خطا: کپچا ارسال نشده است.';
		}else{
		// ارسال درخواست به گوگل با cURL
			$url = 'https://www.google.com/recaptcha/api/siteverify';
			$data = [
				'secret'   => $secretKey,
				'response' => $recaptchaResponse,
				'remoteip' => $_SERVER['REMOTE_ADDR']
			];

			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
			$response 	= curl_exec($ch);
			$curlError 	= curl_error($ch);
			curl_close($ch);
			if ($curlError) {
        		$ErrorMsg .='خطا در برقراری ارتباط با سرور کپچا.';
    		}else {
				$responseKeys = json_decode($response, true);
				// بررسی نتیجه راست‌آزمایی
				if ($responseKeys['success'] && isset($responseKeys['score']) && $responseKeys['score'] >= 0.5) {
//					echo "تایید شد! فرم با موفقیت ثبت شد. (امتیاز: " . $responseKeys['score'] . ")";
				} else {
					$ErrorMsg .= "خطا: رفتار شما شبیه به ربات تشخیص داده شد.";
				}
			}
    	}		
	}
	if ($ErrorMsg!='') {echo json_encode(array(-1,$ErrorMsg));	return ;}
	$query_array = array(':name'=>$name, ':email'=>$email, ':mobile'=>$mobile, ':subject'=>$subject, ':message'=>$message,  ':date'=>$nowdate, ':time'=>$nowtime, ':ip'=>$ip);
	$SQL_QUERY=("insert into `email` 
	( name, subject,  email, mobile, message, date, time, ip, folder, userid) values 
	( :name, :subject, :email, :mobile, :message, :date, :time, :ip, 'root','$userid')");
	$sql=pdo_query($SQL_QUERY,$query_array,0);
	echo json_encode(array(1,"اطلاعات شما با موفقیت ثبت شد، در صورت نیاز با شما تماس گرفته خواهد شد"));
}
if ($type==2) {
	//typebank ---> 1 : product ---- 2 : weblog
	$idnews		= intval($_POST['idnews']);
	$idnazar	= intval($_POST['idnazar']);
	$name 		= $_POST['comment_name'];
	$email 		= $_POST['comment_email'];
	$message	= $_POST['comment_message'];
	$iduser		= intval($_POST['iduser']);
	$typebank	= intval($_POST['typebank']);
	if (($typebank<=0) or ($typebank>2)) $typebank=1;
	if ($name=='')  	$ErrorMsg .= "تعیین نام و نام خانوادگی الزامی است<BR> ";   
	if ($message=='') 	$ErrorMsg .= "تعیین متن پیغام الزامی است<BR> ";   
	if ($row_setting['ok_capcha_comment']==1)  { 
		$secCode  = $_POST['secCode'];		
		$sec2Code = $_POST['sec2Code'];
		if (md5($secCode) != $sec2Code) { $ErrorMsg .="کد کپچا صحیح نمی باشد <BR>"; } 
		if ($idnazar == 0) {
			$token_var = 'token_nazar0_'.$idnews; 
			$ok_token = ( (isset($_SESSION[$token_var]) && $_SESSION[$token_var]==$sec2Code) );
		} else {
			$token_var = 'token_nazar1_'.$idnews; 
			$ok_token = ( (isset($_SESSION[$token_var]) && $_SESSION[$token_var]==$sec2Code) );
		}
//		echo "<BR>sec2Code=$sec2Code<BR>";
//		if ($_SESSION[$token_var]==$sec2Code) echo 'ok_session'; else echo 'not ok';
//		echo "<BR>token_var= $token_var= $_SESSION[$token_var]";		var_dump($_SESSION);
		if (!$ok_token) {$ErrorMsg .="کد کپچا صحیح نمی باشد 2<BR>"; } 
		
	}
	if ($typebank==1)  $bank='product'; else $bank='weblog'; 
	
	$query=pdo_query("select tetr from `$bank` where id='$idnews' ") ;
	$row_rec = pdo_fetch($query);
	if ($row_rec=='') {$ErrorMsg .="کد مطلب وارده صحیح نمی باشد <BR>";}
	$subject = $row_rec['tetr'];
	
	
	if ($row_setting['ok_save_comment']==1) {
		$ttime = substr($nowtime,0,5);
		$sql=pdo_query("select count(*) from `nazar` where idrec='$idnews' and id_user='$usernameid' and left(time,5)='$ttime' ",'',0);
		$JamRec = pdo_count($sql);
		if ($JamRec>0) { $ErrorMsg .='امکان ثبت نظر در یک دقیقه بیش از یکبار مقدور نیست<BR>'; } 
	}
	if ($ErrorMsg!='') {echo json_encode(array(-1,$ErrorMsg));	return ;}
	$country=''; $city='';
//	$iplink   = @file_get_contents("http://ip-api.com/json/$IP");
//	$ipstatus = @json_decode($iplink,true);
//	if ($ipstatus['status'] ='success') {$country = $ipstatus['country'];	$city = $ipstatus['city']; }

	$query_array = array(':name'=>$name, ':email'=>$email, ':message'=>$message,  ':date'=>$nowdate, ':time'=>$nowtime, ':ip'=>$ip);
	$SQL_QUERY="insert into `nazar` 
				(idrec, id_user, name, subject, email, message, date, time, ip, folder, parentid, tablename, country, city) 	values 
				('$idnews', '$iduser', :name, '$subject', :email, :message,  :date, :time, :ip, 'root', '$idnazar', '$bank', '$country', '$city')";
	$sql = pdo_query($SQL_QUERY, $query_array, 0);
	echo json_encode(array(1,"نظر شما با موفقیت ثبت شد"));
	
}
// خواندن نظرات در لیست نظرات ذیل هر مطلب
if ($type==3) {
	//typebank ---> 1 : product ---- 2 : weblog
	$idnews    	= intval($_POST['idnews']);
	$startrec  	= intval($_POST['startrec']);
	$maxread  	= intval($_POST['maxread']);
	$typebank  		= intval($_POST['typebank']);
	if ($idnews==0) $ErrorMsg .= "کد مطلب موردنظر اشتباه است";
	if ($startrec<=0) $maxread=0;
	if ($maxread<=0) $maxread=20;
	if (($typebank<=0) or ($typebank>2)) $typebank=1;
	if ($ErrorMsg!='') {echo json_encode(array(-1,$ErrorMsg));	return ;}
	if ($typebank==1)  $bank='product'; else $bank='weblog'; 
	$MaxReadComment = $maxread;
	//----------------------------
	$OkComment = true;
	if ($row_setting['ok_save_comment']==1) {
		if (!$ok_cookie) $OkComment= false;
	}
	//-----------------------------------------
	
	$endrec = $startrec + $maxread;
	$query2=pdo_query("select * from `nazar` where idrec='$idnews' and ok='y' and deleted='f' and parentid='0'  and tablename='$bank' order by date desc, time desc limit $startrec,$MaxReadComment ",'',0);
	$JamRec = pdo_rowcount($query2);
	if ($JamRec==0) {echo json_encode(array(1,''));	return ;}
	$counter=$startrec;
	$ret='';
	while ($row_comment = pdo_fetch($query2)) {
		$counter++;
		$id_comment = $row_comment['id'];
		$message = nl2br($row_comment['message']);			$answer = nl2br($row_comment['answer']);
		$country = $row_comment['country'];					$city = $row_comment['city'];
		$email 	 = $row_comment['email'];
		if ($email!='') $email = "<div class='post-meta Visit'><i class='fa fa-envelope'></i> $email </div>";
		
		if ($answer!='') $answer="<p class='text-danger'><span class='bg-danger text-white p-1'> پاسخ: </span><BR>$answer</p>";
		if ($country!='') $country = "<div class='post-meta'><i class='fa fa-flag'></i> $country</div>";
		if ($city!='') $city = "<div class='post-meta'><i class='fa fa-map-marker'></i> $city</div>";
		if ($OkComment) {
			$StrReplay = "<div class='reply text-left'><a onclick=\"ReplayComment('header$id_comment',$id_comment)\" class='comment-reply-link' style='cursor:pointer;'>پاسخ دادن</a></div>";
		}
		//----------------------
		$ListAnswerThisComment = ListCommentAnswer($id_comment, $bank, $idnews, $OkComment);
		//-----------------------
		$ret .= "
		<li class='comment-even'>
			<div class='comment-body'>
				<header id='header$id_comment' class='row comment-meta' style='gap:10px;'>
					<div class='post-meta date'>$counter |</div>
					<div class='post-meta date'><i class='fa fa-calendar'></i> $row_comment[date] </div>
					<div class='post-meta author'><i class='fa fa-clock-o'></i> $row_comment[time] </div>
					<div class='post-meta'><i class='fa fa-user'></i> $row_comment[name]  </div>
					$email
					$country
					$city
				</header>
				<p>$message</p>
				$answer
				$StrReplay
			</div>
		</li>
		$ListAnswerThisComment
		";
	}
	if ($counter>=$endrec) { 
		$ret .= "
		<span id='ContinueComment'>
		<div class='col-12 text-center bg-success text-white rounded p-2 mt-5'>
		<a onclick='ReadCommentNew($counter,$idnews, $MaxReadComment)' class='mbtn' style='cursor:pointer;'>خواندن نظرات بیشتر</a>
		</div>		
		</span>
		"; 
	} 
	echo json_encode(array(1,$ret));
}
//------------------------------------------------- ثبت نام کاربر
if ($type==4) {
		if ($ok_cookie) {	echo json_encode(array(-1,"شما در سایت وارد شده اید")); return;	}
		if (isset($_POST['parameter']))	{		
			$parameter = ($_POST['parameter']); 
			if (isset($parameter['idtype'])) 	 $_POST['idtype'] = $parameter['idtype'];
			if (isset($parameter['reg_type'])) 	 $_POST['reg_type'] = $parameter['reg_type'];
			if (isset($parameter['reg_value']))  $_POST['reg_value'] = $parameter['reg_value'];
			if (isset($parameter['ActiveCode'])) $_POST['ActiveCode'] = $parameter['ActiveCode'];
			if (isset($parameter['family']))	 $_POST['family'] = $parameter['family'];
			if (isset($parameter['password1']))	 $_POST['password1'] = $parameter['password1'];
			if (isset($parameter['gender']))	 $_POST['gender'] = $parameter['gender'];
		} else{ echo json_encode(array(-1,"اطلاعاتی ارسال نشده است")); return;	}
//			var_dump($parameter);
		//تعیین مرحله ثبت نامه
		if (isset($_POST['idtype']))	$idtype = intval($_POST['idtype']); else $idtype = 0;
		if ($idtype==0) $idtype=1;
		//تعیین موبایل و ایمیل بودن
		if (isset($_POST['reg_type']))	$reg_type = intval($_POST['reg_type']); else $reg_type = 0;
		//مقدار موبایل و یا ایمیل
		if (isset($_POST['reg_value']))	$reg_value = clean($_POST['reg_value']); else $reg_value='';
		//کد فعال سازی
		if (isset($_POST['ActiveCode']))$ActiveCode = intval(EnglishNumber($_POST['ActiveCode'])); 
			else $ActiveCode = 0;
		if (isset($_POST['family']))	$family = clean($_POST['family']); 			 else $family='';
		if (isset($_POST['password1']))	$password1 = clean($_POST['password1']); else $password1='';
		if (isset($_POST['gender']))	$gender = intval($_POST['gender']); else $gender='0';
		//var_dump($_POST);echo "<BR>family=$family<BR>";
		$mobile = ''; $email ='';
		if ($reg_type==0) {
			$reg_value = EnglishNumber($reg_value);
			$OkRegValue = validateNumber($reg_value,1);
			if ( ($OkRegValue) and (substr($reg_value,0,1)!='0')) $reg_value = "0".$reg_value;
			$fieldtetr = ' موبایل ';
			$fieldname='mobile';
			$mobile = $reg_value;
//			if ((strlen($mobile)<10) or (strlen($mobile)>15) ) $OkRegValue= false;
			if ((mb_strlen($mobile,'utf-8')!=11)  ) $OkRegValue= false;
			$tetr2 = " شماره $reg_value پیامک شد. ";
			$active_mail_phone = 1;
		} else {
			$OkRegValue = validateEmail($reg_value,1);
			$fieldtetr = ' ایمیل ';
			$fieldname='email';
			$email = $reg_value;
			$tetr2 = " ایمیل $reg_value ارسال شد. ";
			$active_mail_phone = 2;
		}
		if (!$OkRegValue) {
			echo json_encode(array(-1,"$fieldtetr وارد شده صحیح نمی باشد")); return;	
		}
		if (($idtype==2) and (($ActiveCode<=0) or($ActiveCode>9999)) )  {
			echo json_encode(array(-1,"لطفا کد فعال سازی را به درستی وارد نمایید")); return;	
		}
		if (($idtype==3) )  {
			if ( ($family=='') or ($password1=='') or ($gender==0) )   {
				echo json_encode(array(-1,"لطفا اطلاعات خود را به درستی وارد نمایید")); return;	
			}	
			if ( (mb_strlen($family,'utf-8')<5) or (mb_strlen($family,'utf-8')>30) )   {
				echo json_encode(array(-1,"لطفا نام و نام خانوادگی خود را به درستی وارد نمایید")); return;	
			}
			$OkPassword = validatePassword($password1,1);
			if (!$OkPassword) {
				echo json_encode(array(-1," لطفا شرایط ثبت رمز را به درستی رعایت نمایید")); return;	
			}
			if ($gender==0) {
				echo json_encode(array(-1," لطفا جنسیت را ثبت کنید")); return;	
			}
		}

		$query_array = array(':reg_value'=>$reg_value);
		$query=pdo_query("select count(*) from `user_account` where $fieldname=:reg_value and isdeleted=0 ",$query_array,0) ;
		$FoundRec = pdo_count($query);
		if ($FoundRec>0) {
			echo json_encode(array(-1,"شما قبلا با این $fieldtetr ثبت نام کرده اید <BR> جهت دسترسی به اکانت خود می توانید از قابلیت <a href='/recover/' target='_blank' style='color:blue;'>فراموشی رمز عبور</a> استفاده نمایید")); 
			return;	
		}
		$query_array2 = array(':mobile'=>$mobile, ':email'=>$email);	

		if ($idtype==1) {
			if ($row_setting['sms_register']==0) {
				echo json_encode(array(2,"success"));
				return;
			}
			// ارسال otp به موبایل و یا ایمیل اگر پترن دار بود با الگو و اگر نبود با پیامک معمولی
			list($code_return,$message_return) = SendOtp($reg_type, $reg_value, 'register' );
			echo json_encode(array($code_return,$message_return));
			return;
			
			
		}
		if ($idtype==2) {
			$query = pdo_query("SELECT * FROM user_active_code WHERE status=0 and activecode='$ActiveCode' and $fieldname=:reg_value ",$query_array,0);
			$row = pdo_fetch($query);
			if ($row=='') {
				echo json_encode(array(-1,"کد وارد شده صحیح نمی باشد "));
				return;	
			}
			$id_main =$row['id_main'];
			$query = pdo_query("update user_active_code set status='1' WHERE id_main='$id_main'",'',0);
			echo json_encode(array(2,"success"));
		}
		if ($idtype==3) {
			$id_ostan 	= $row_setting['id_ostan'];
			$id_city 	= $row_setting['id_city'];
			$password= md5($password1);
			$query_array3 = array(':name'=>$family, ':mobile'=>$mobile, ':email'=>$email);	
			$SQL_QUERY="insert into `user_account` 
			(name, mobile, email, active, active_code, date, active_mail_phone , password, date_last_edit, id_ostan, id_city, gender) 
			values 
			(:name, :mobile, :email, 1, '','$nowdate', $active_mail_phone, '$password', '$date_last_edit', 
			'$id_ostan', '$id_city', '$gender' )";
			$sql	 = pdo_query($SQL_QUERY,$query_array3,0);
			$id_user = pdo_lastinsert();
			//---------------------------------
			$Ok = Login_User($id_user);
			if ($Ok==-1) { echo json_encode(array(-1,"ورود درست انجام نشد")); return;}
			//---------------------------------
			AfterLoginUser($id_user);
			//------------------------------------------------------
			$sms_config = isset($row_sms[0]) ? $row_sms[0] : null;
			
			// ارسال پیامک به مدیر جهت ثبت نام کاربر
			if (in_array(1,$sms_list_send)) {			
				// درصورتی که ثبت نام از طریق شماره موبایل بود و شماره موبایل مدیر پر باشد
				if (($reg_type==0) and ($mobile_manager!='')) {						
					$Message = " کاربر [$family] با شماره موبایل [$mobile] در فروشگاه ثبت نام کرد \r\n $shop_name";
					list($ValueReturn, $MessageReturn)= SendSmsFromTable($mobile_manager, $Message, $sms_config);
//					list($ValueReturn, $MessageReturn,$smsdeliveri) = EnjineSendSms($mobile_manager, $Message);
//					if ($ValueReturn<0) { echo json_encode(array(-1,"$MessageReturn")); } // نیازی به هشدار نیست
				}
			}
			// ارسال پیامک به کاربر به محض ثبت نام
			if (in_array(2,$sms_list_send)) {			
				if (($reg_type==0)) {						
					$Message = " کاربر [$family] شما با موفقیت در فروشگاه $shop_name ثبت نام کردید \r\n $shop_name";
					list($ValueReturn, $MessageReturn)= SendSmsFromTable($mobile, $Message, $sms_config);
//					list($ValueReturn,$MessageReturn,$smsdeliveri) = EnjineSendSms($mobile, $Message);
//					if ($ValueReturn<0) { echo json_encode(array(-1,"$MessageReturn")); } // نیازی به هشدار نیست
				}
			}
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
			//----------------------------
			echo json_encode(array(3,"success"));
		}	
}
//----------------------------- بازیابی رمز عبور
if ($type==5) {
		if ($ok_cookie) {	echo json_encode(array(-1,"شما در سایت وارد شده اید")); return;	}
		if (isset($_POST['parameter']))	{		
			$parameter = ($_POST['parameter']); 
			if (isset($parameter['idtype'])) 	 $_POST['idtype'] = $parameter['idtype'];
			if (isset($parameter['reg_type'])) 	 $_POST['reg_type'] = $parameter['reg_type'];
			if (isset($parameter['reg_value']))  $_POST['reg_value'] = $parameter['reg_value'];
			if (isset($parameter['ActiveCode'])) $_POST['ActiveCode'] = $parameter['ActiveCode'];
			if (isset($parameter['password1']))	 $_POST['password1'] = $parameter['password1'];
			if (isset($parameter['password2']))	 $_POST['password2'] = $parameter['password2'];
		} else{ echo json_encode(array(-1,"اطلاعاتی ارسال نشده است")); return;	}
//			var_dump($parameter);
		//تعیین مرحله ثبت نامه
		if (isset($_POST['idtype']))	$idtype = intval($_POST['idtype']); else $idtype = 0;
		if ($idtype==0) $idtype=1;
		//تعیین موبایل و ایمیل بودن
		if (isset($_POST['reg_type']))	$reg_type = intval($_POST['reg_type']); else $reg_type = 0;
		//مقدار موبایل و یا ایمیل
		if (isset($_POST['reg_value']))	$reg_value = clean($_POST['reg_value']); else $reg_value='';
		//کد فعال سازی
		if (isset($_POST['ActiveCode']))$ActiveCode = intval(EnglishNumber($_POST['ActiveCode'])); 
			else $ActiveCode = 0;
		if (isset($_POST['password1']))	$password1 = clean($_POST['password1']); else $password1='';
		if (isset($_POST['password2']))	$password2 = clean($_POST['password2']); else $password2='';
		$mobile = ''; $email ='';
		$timesend = time();
		$otp_validity_seconds = intval($sms_time * 60); // مدت اعتبار OTP (بر حسب ثانیه)
		$valid_from_time = $timesend - $otp_validity_seconds; 
		$oneclock	= time() - ((60*60)+1);
		$MainActiveCode = mt_rand(1000, 9999);		
		
		if ($reg_type==0) {
			$reg_value = EnglishNumber($reg_value);
			$OkRegValue = validateNumber($reg_value,1);
			if ( ($OkRegValue) and (substr($reg_value,0,1)!='0')) $reg_value = "0".$reg_value;
			$fieldtetr = ' موبایل ';
			$fieldname='mobile';
			$mobile = $reg_value;
			if ((strlen($mobile)<10) or (strlen($mobile)>15) ) $OkRegValue= false;
			$tetr2 = " شماره $reg_value پیامک شد. ";
			$active_mail_phone = 1;
		} else {
			$OkRegValue = validateEmail($reg_value,1);
			$fieldtetr = ' ایمیل ';
			$fieldname='email';
			$email = $reg_value;
			$tetr2 = " ایمیل $reg_value ارسال شد. ";
			$active_mail_phone = 2;
		}
		if (!$OkRegValue) {
			echo json_encode(array(-1,"$fieldtetr وارد شده صحیح نمی باشد")); return;	
		}
		if (($idtype==2) and (($ActiveCode<=0) or($ActiveCode>9999)) )  {
			echo json_encode(array(-1,"لطفا کد فعال سازی را به درستی وارد نمایید")); return;	
		}
		if (($idtype==3) )  {
			if ( ($password1=='') or ($password2=='') )   {
				echo json_encode(array(-1,"لطفا اطلاعات خود را به درستی وارد نمایید")); return;	
			}	
			$OkPassword = validatePassword($password1,1);
			if (!$OkPassword) {
				echo json_encode(array(-1," لطفا شرایط ثبت رمز را به درستی رعایت نمایید")); return;	
			}
			if ($password1!=$password2) {
				echo json_encode(array(-1," تکرار رمز عبور صحیح نیست")); return;	
			}
		}

		$query_array = array(':reg_value'=>$reg_value);
		$query=pdo_query("select * from `user_account` where $fieldname=:reg_value and isdeleted=0 and active=1 ",$query_array,0) ;
		$FoundRow = pdo_fetch($query);
		if ($FoundRow=='') {
			echo json_encode(array(-1,"شما تاکنون ثبت نام نکرده اید، لطفا در سایت ثبت نام نمایید")); 
			return;	
		}
		$id_user = $FoundRow['id_main'];
		$query_array2 = array(':mobile'=>$mobile, ':email'=>$email);	

		if ($idtype==1) {
			list($code_return,$message_return) = SendOtp($reg_type, $reg_value, 'recover' );
			echo json_encode(array($code_return,$message_return));
			return;
		}
		if ($idtype==2) {
			$query = pdo_query("SELECT * FROM user_active_code WHERE activecode='$ActiveCode' and $fieldname=:reg_value ",$query_array,0);
			$row = pdo_fetch($query);
			if ($row=='') {
				echo json_encode(array(-1,"کد وارد شده صحیح نمی باشد "));
				return;	
			}
			$id_main =$row['id_main'];
			pdo_query("update user_active_code set status='1' WHERE id_main='$id_main'",'',0);
			echo json_encode(array(2,"success"));
		}
		if ($idtype==3) {
			$password= md5($password1);
			$sql=pdo_query("update `user_account` set password=:password, date_last_edit='$date_last_edit' where id_main='$id_user' ",array(':password'=>$password),0) ;
			$Ok = Login_User($id_user);
			if ($Ok==-1) { echo json_encode(array(-1,"ورود درست انجام نشد")); return;}
			//---------------------------
			AfterLoginUser($id_user);
			//---------------------------
			$shop_name = $row_setting['shop_name'];
			$sms_list_send  = explode(',',$row_setting['sms_list_send']);
			// ارسال پیامک به کاربر به محض تغییر رمز
			if (in_array(5,$sms_list_send)) {			
				if (($reg_type==0)) {						
					$Message = " تغییر رمز شما در فروشگاه $shop_name با موفقیت انجام شد \r\n $shop_name";
					$sms_config = isset($row_sms[0]) ? $row_sms[0] : null;
					list($ValueReturn, $MessageReturn)= SendSmsFromTable($mobile, $Message, $sms_config);			
//					list($ValueReturn,$MessageReturn,$smsdeliveri) = EnjineSendSms($mobile, $Message);
				}
			}
			// ارسال ایمیل به کاربر به هنگام تغییر رمز
			$email_to_user=explode(',',$row_setting['email_to_user']);
			if (in_array(4,$email_to_user)) {			
				if (($reg_type==1) ) {
					$Message = " کاربر [$family] تغییر رمز شما در فروشگاه  $shop_name با موفقیت انجام شد <BR> $shop_name";
					$OkSendEmail = SendEmail('',$email,"تغییر رمز اکانت شما در $shop_name",$Message,'',0);
				}
			}
			//----------------------------
			echo json_encode(array(3,"success"));
		}	
}

//----------------------------- ورود به پنل
if ($type==6) {
	if ($ok_cookie) {	echo json_encode(array(-1,"شما در سایت وارد شده اید")); return;	}
//		var_dump($_POST);

	//تعیین مرحله ثبت نامه
	if (isset($_POST['idtype']))	$idtype = intval($_POST['idtype']); else $idtype = 0;
	if ($idtype==0) $idtype=1;
	//تعیین موبایل و ایمیل بودن
	if (isset($_POST['reg_type']))	$reg_type = intval($_POST['reg_type']); else $reg_type = 0;
	//مقدار موبایل و یا ایمیل
	if (isset($_POST['reg_value']))	$reg_value = clean($_POST['reg_value']); else $reg_value='';
	//رمز
	if (isset($_POST['reg_pass']))$reg_pass = clean($_POST['reg_pass']); 	else $reg_pass = '';
	//نوع ثبت پسورد و یا ا تی پی
	if (isset($_POST['reg_no']))	$reg_no = clean($_POST['reg_no']); 	else $reg_no = '';
	//کد فعال سازی ارسال شده توسط پیامک و یا ایمیل
	if (isset($_POST['active_code']))	$active_code = clean($_POST['active_code']); 	else $active_code = '';
	$mobile = ''; $email ='';
	if ($reg_type==0) {
		$reg_value = EnglishNumber($reg_value);
		$OkRegValue = validateNumber($reg_value,1);
		if ( ($OkRegValue) and (substr($reg_value,0,1)!='0')) $reg_value = "0".$reg_value;
		
		$fieldtetr = ' موبایل ';
		$fieldname='mobile';
		$mobile = $reg_value;
		if ((strlen($mobile)<10) or (strlen($mobile)>15) ) $OkRegValue= false;
		$active_mail_phone = 1;
	} else {
		$OkRegValue = validateEmail($reg_value,1);
		$fieldtetr = ' ایمیل ';
		$fieldname='email';
		$email = $reg_value;
		$active_mail_phone = 2;
	}
	if (!$OkRegValue) {
		echo json_encode(array(-1,"$fieldtetr وارد شده صحیح نمی باشد - mobile=[$mobile]")); return;	
	}
	$usernameid=0;
	$PeriodTime = 1 * 3600; // زمان مسدودی اکانت
	$timelogin = time();
	$pa_user = ($reg_value);
	$query=pdo_query("select * from `user_account` where isdeleted=0 and active=1 and ($fieldname)=:pa_user",array(':pa_user'=>$pa_user),0) ;
	$row=pdo_fetch($query);
	if ($row=='') {	echo json_encode(array(-1,"[$fieldtetr] وارده شده وجود ندارد")); return;	}
	if ( ($row['mismatch']>5) and (($row['lastlogin']+$PeriodTime)>$timelogin) ) {	
		echo json_encode(array(-1,"شما رمز اکانت خود را بیش از 5 بار اشتباه زده اید، لذا اکانت شما برای مدت 1 ساعت مسدود می گردد")); 
		return;	
	}
	//-------------------------------------
	if ($reg_no=='otp'){	
		list($code_return,$message_return) = SendOtp($reg_type, $reg_value, 'login' );
		echo json_encode(array($code_return,$message_return));
		return;
	}
	//-------------------------------------
	if ($reg_no=='otp_verify'){	
		$query2 = pdo_query("SELECT * FROM user_active_code WHERE status=0 and activecode='$active_code' and $fieldname=:reg_value ",array(':reg_value'=>$reg_value),0);
		$row_activecode = pdo_fetch($query2);
		if ($row_activecode=='') {
			echo json_encode(array(-1,"کد وارد شده صحیح نمی باشد "));
			return;	
		}
		$id_main =$row_activecode['id_main'];
		$query2 = pdo_query("update user_active_code set status='1' WHERE id_main='$id_main'",'',0);
		//-------------------------------------------
		$usernameid = $row['id_main'];
	}
	//-------------------------------------  login with password
	if ($reg_no=='pass'){			
		$pa_pass = ($reg_pass);
		if ( ($reg_pass=='') )	{
			echo json_encode(array(-1,"اسم رمز خالی است")); return;	
		}
		//-------------------------------
		$OkLogin 	= true;
		$DbId		= $row['id_main'];
		$DbPassword = trim($row['password']);
		if ($DbPassword != md5($pa_pass) ) { $OkLogin=false; }
		//-------------------------------
		if (!$OkLogin) {
			$query=pdo_query("update `user_account` set mismatch=(mismatch+1), lastlogin='$timelogin' where id_main='$DbId' ",'',0);
			echo json_encode(array(-1,"رمز وارد شده اشتباه است")); 
			return;	
		}
		$usernameid = $row['id_main'];
	}
	//---------------------------
	$Ok = Login_User($usernameid, $row);
	if ($Ok==-1) { echo json_encode(array(-1,"ورود درست انجام نشد")); return;}
	//---------------------------
	AfterLoginUser($usernameid);
	//---------------------------
	echo json_encode(array(1,"success"));

}
//----------------------------- تغییر شهر با تغییر استان
if ($type==8) {
	$id		= isset($_POST['id']) ? intval($_POST['id']) : 0 ;
	if (isset($_POST['idold'])) $idold	= intval($_POST['idold']); else $idold=0;
	$result  = pdo_query("select * from `city` where id_ostan=$id ",'',0);
	$arrayCity = '';
	while($row = pdo_fetch($result)) { 	
		if ($idold==$row['id']) $sel= " selected "; else $sel="";
		$arrayCity  .= "<option value='$row[id]' $sel>$row[name]</option> \r\n";
	}
	echo json_encode(array(1,$arrayCity));	
	
}
//----------------------------- افزودن به لیست علاقه مندی ها
if ($type==9) {
	$id		= intval($_POST['id']);
	$result  = pdo_query("select * from `product` where id='$id' and active=2 and isdeleted=0 ",'',0);
	$row = pdo_fetch($result);
	if ($row=='') {echo json_encode(array(-1,'چنین محصولی یافت نشد'));	return;}
	if ($ok_cookie) {
		$result = pdo_query("select * from `user_fav` where id_user='$usernameid' and id_product='$id'",'',0);
		$newrow	= pdo_fetch($result);
		if ($newrow!='') {
			$newid = $newrow['id_main'];
			$result = pdo_query("delete from `user_fav` where id_main='$newid'",'',0);
			echo json_encode(array(2,'از لیست علاقه مندی حذف شد'));
		}	else {
			$result = pdo_query("insert into `user_fav` (id_user, id_product, date, ip) values ('$usernameid', '$id', '$date_last_edit', '$ip_last_edit')",'',0);
			echo json_encode(array(1,'به لیست علاقه مندی  اضافه شد'));	
		}
	} else {
		$keyreturn = 1;
		$message = "به لیست علاقه مندی اضافه شد";
		$CookieTime = time()+ (1 * 24* 3600);  // 1 day
		if (isset($_COOKIE['ListWish'])) {
			$ListWish = array_filter(explode(',',$_COOKIE['ListWish']));
			if (count($ListWish)>0) {
				$key = array_search($id, $ListWish);
				if ($key !== false) {
				    unset($ListWish[$key]);
				    $ListWishStr = implode(',',$ListWish);
					setcookie ("ListWish", $ListWishStr,$CookieTime); 
					$message = "از لیست علاقه مندی حذف شد";
					$keyreturn = 2;
				} else {
				    $ListWishStr = implode(',',$ListWish).",$id,";
					setcookie ("ListWish", $ListWishStr,$CookieTime); 
				}
			}else{
				setcookie ("ListWish", "$id,",$CookieTime); 
			}
		}else{
			setcookie ("ListWish", "$id,",$CookieTime); 
		}
		echo json_encode(array($keyreturn,$message));	
	}
}
//----------------------------- افزودن به سبد خرید
if ($type==10) {

/*	if (!$ok_cookie) { 	
		echo json_encode(array(-1,"قبل از خرید لطفا در سایت ثبت نام کرده و یا وارد شوید"));		return;
	}
*/	
	if ($ok_cookie) { $id_user = $usernameid;} else { 
		if (isset($_SESSION['id_user'])) {    
			$id_user = $_SESSION['id_user'];
		} else {
			$id_user = generate_uuid_v4();
			$_SESSION['id_user'] = $id_user;		
		}
	}
	$id			= intval($_POST['id']);			// کد محصول
	$id_price	= intval($_POST['idprice']); 	// کد قیمت
	$number		= intval($_POST['number']); 	// تعداد سفارش محصول
	if ($number==0) $number=1;
	$result  = pdo_query("select * from `product` where id='$id' and active=2 and isdeleted=0 ",'',0);
	$row = pdo_fetch($result);
	if ($row=='') { echo json_encode(array(-1,'چنین محصولی یافت نشد'));	 return;  }
	$result  = pdo_query("select * from `product_price` where id='$id_price' and id_product='$id' ",'',0);
	$row_price = pdo_fetch($result);
	if ($row_price=='') { echo json_encode(array(-1,'چنین محصولی با این قیمت یافت نشد'));  return;	}
	$cash = $row_price['cash'];
	if ($row_price['cash_unlimited']==1) $cash=9999999;
	if ($cash<=0) {echo json_encode(array(-1,'این محصول موجودی ندارد')); return;	 }
//	if ($ok_cookie) {
		$result = pdo_query("select * from `user_order` where id_user='$id_user' and id_product='$id' and id_price='$id_price' ",'',0);
		$newrow	= pdo_fetch($result);
		$validtime = time();
		if ($newrow!='') {
			$newid = $newrow['id_main'];
			$newnumber = $newrow['number'] + $number;
			$result = pdo_query("update `user_order` set number='$newnumber' where id_main='$newid'",'',0);
			echo json_encode(array(1,"تعداد محصول به [$newnumber] افزایش یافت"));
		}	else {
			$result = pdo_query("insert into `user_order` (id_user, id_product, id_price, number, date, ip, validtime) values ('$id_user', '$id', '$id_price', '$number', '$date_last_edit', '$ip_last_edit', '$validtime')",'',0);
			echo json_encode(array(1,'به لیست خرید  اضافه شد'));	
		}
/*
	} else {
		if (!isset($_SESSION['cart'])) {    $_SESSION['cart'] = [];		}
		$unique_cart_key = $id . '_' . $id_price;

		if (array_key_exists($unique_cart_key, $_SESSION['cart'])) {
			// محصول با این کد قیمت قبلاً اضافه شده است، فقط تعداد را زیاد کن
			$_SESSION['cart'][$unique_cart_key]['number'] += $number;
			$newnumber = $_SESSION['cart'][$unique_cart_key]['number'];
			echo json_encode(array(1,"تعداد محصول در سبد خرید به [$newnumber] افزایش یافت"));
		} else {
			// آیتم جدید است
			$_SESSION['cart'][$unique_cart_key] = [
				'id_product' => $id,    // ذخیره کد محصول
				'id_price' => $id_price,    // ذخیره کد قیمت
				'number' => $number
			];
			echo json_encode(array(1,'به سبد خرید اضافه شد'));	
		}
	}
*/	
}
//----------------------------- مقایسه محصول
if ($type==11) {
	$id			= intval($_POST['id']);			// کد محصول
	$result  = pdo_query("select * from `product` where id='$id' and active=2 and isdeleted=0 ",'',0);
	$row = pdo_fetch($result);
	if ($row=='') { echo json_encode(array(-1,'چنین محصولی یافت نشد'));	return;}
	$keyreturn = 1;
	$message = "به بخش مقایسه ها اضافه شد";
	$CookieTime = time()+ (10 * 24 * 3600);  // 1 day
	if (isset($_COOKIE['ListCompare'])) {
		$ListCompare = array_filter(explode(',',$_COOKIE['ListCompare']));
		if (count($ListCompare)>0) {
			$key = array_search($id, $ListCompare);
			if ($key !== false) {
				unset($ListCompare[$key]);
				$ListWishStr = implode(',',$ListCompare);
				setcookie ("ListCompare", $ListWishStr,$CookieTime); 
				$message = "از بخش مقایسه ها حذف شد";
				$keyreturn = 2;
			} else {
				if (count($ListCompare)>3) { 
					$message = "نهایت تعداد مقایسه محصول 4 مورد می باشد";
					$keyreturn = 2;
				}else{
					$ListWishStr = implode(',',$ListCompare).",$id,";
					setcookie ("ListCompare", $ListWishStr,$CookieTime); 
				}
			}
		}else{
			setcookie ("ListCompare", "$id,",$CookieTime); 
		}
	}else{
		setcookie ("ListCompare", "$id,",$CookieTime); 
	}
	echo json_encode(array($keyreturn,"$message"));	
}
//----------------------------- پر کردن بخش سبد خرید در قسمت هدر سایت
if ($type==12) {
//	echo json_encode(array(-1,'',0,0,0, 0)); return;
//	if (!$ok_cookie) { echo json_encode(array(1,'',0,''));	 return;}
	/*
	data[0]: // -1: unsuccefully --- 0: succefully
	data[1]; // content of shopcard
	data[2]; // count of buy
	data[3]; // total price of buy
	*/

	if ($ok_cookie) { $id_user = $usernameid;} else { 
		if (isset($_SESSION['id_user'])) {    $id_user = $_SESSION['id_user']; } else {
			echo json_encode(array(1,'',0,''));	 return;
		}
	}

	$query = pdo_query("SELECT    uo.id_main, uo.id_product, uo.id_price, uo.number, p.tetr, p.leds, p.photo_product, pp.price1, pp.price2 	from  user_order uo
	JOIN    product p ON uo.id_product = p.id
	JOIN    product_price pp ON uo.id_price = pp.id
	where uo.id_user='$id_user' order by uo.date desc
	");
/* 
SELECT    uo.id_main, uo.id_product, uo.id_price, uo.number, p.tetr, p.leds, p.photo_product, pp.price1, pp.price2, ppp.content 	from  user_order uo
	JOIN    product p ON uo.id_product = p.id
	JOIN    product_price pp ON uo.id_price = pp.id
    join    product_price_property ppp On   uo.id_price = ppp.id_price
	where uo.id_user='1' order by uo.date desc
	در این حالت محصولات که ویژگی خاصی ندارد نمی آورد
*/	
	$counter=0;
	$PriceAll = 0;
	$content = '';
	$content2= '';
	while ($row=pdo_fetch($query)) {
		$counter++;
		$price=  $row['price1'];		
		if ($row['price2'] > 0) 		$price=$row['price2'];
		//-------------------
		$product_content='';
		$id_price=$row['id_price'];
		$query2 = pdo_query("select content from `product_price_property` where id_price='$id_price'");
		$row2 	= pdo_fetch($query2);
		if ($row2!='') $product_content = $row2['content'];
		//-------------------
		$id    = $row['id_main'];		$photo = $row['photo_product'];
		$tetr  = $row['tetr'];			$tetr2 = $row['leds'];
		$number = $row['number'];
		$idproduct=$row['id_product'];
		$tetr_seo = LinkSeo($tetr);
		if ($product_content!='') 	$tetr = "$tetr <span class='text-danger' style='font-size:smaller'>[$product_content]</span>";
		
		$link = "/product/$idproduct/$tetr_seo";
		if ($photo=='') {$photo=$logofile;} 
			else{	if (!validateURL($photo)) $photo = $upload_path_main.$photo;}
		$price_tag = ' ' . number_format($price) . ' ' . $price_unit ;
		$PriceAll  += ($price * $number);
		$priceall_tag = number_format($price * $number) . ' ' . $price_unit ;
		$content .= "
		<li class='mini-cart-item'>
			<div class='mini-cart-item-content'>
				<a href='#' onclick='DelShopping($id)' class='mini-cart-item-close'>
					<i class='mdi mdi-close'></i>
				</a>
				<a href='#' class='mini-cart-item-image d-block'><img src='$photo'></a>
				<a href='$link' target='_blank'>
					<span class='product-name-card'>$tetr</span>
				</a>
				<div class='variation'>
					<span class='variation-n'></span>
					<p class='mb-0'>$tetr2 </p>
				</div>
				<div class='header-basket-list-item-color-badge' dir='rtl' style='color:black;'>
					$number عدد
					&nbsp;&nbsp;
				</div>
				<div style='direction:rtl;color:black;font-size:12px;margin-top:10px;'>$price_tag</span>
			</div>
		</li>
		";
		$content2 .= "
		<tr>
		<th scope='row' class='product-cart-name'>$counter
			<div class='product-thumbnail-img'>
				<a href='#'><img src='$photo'></a>
				<div class='product-remove'>
					<a onclick='DelShopping($id)' class='remove' style='cursor:pointer;' >
						<i class='mdi mdi-close'></i>
					</a>
				</div>
			</div>
			<div class='product-title'>
				<a href='$link' target=_blank>$tetr</a>
				<div class='variation'>
					<div class='seller'>$tetr2</div>
				</div>
			</div>
		</th>
		<td class='product-cart-price'>
			<span class='amount'>$price_tag</span>
		</td>
		<td class='product-cart-quantity'>
			<div class='required-number before'>
			<div class='quantity'>
				<input type='number' min='1' max='100' step='1' value='$number' onkeypress='return isNormalNumber(event)'  oninput='enforceMax(this)' onchange='ChangeCount($id,this.value);'  id='Pcount$id' readonly  >
				<div class='quantity-nav'>
					<div class='quantity-button quantity-up' onclick=\"UpDown('#Pcount$id',1,$id);\" >+</div>
					<div class='quantity-button quantity-down'  onclick=\"UpDown('#Pcount$id',-1,$id);\" >-</div>
				</div>					
			</div>
			</div>
		</td>
		<td class='product-cart-Total'>
			<span class='amount'>$priceall_tag</span>
		</td>
		</tr>
		";
	}
	$PriceAllTag = number_format($PriceAll) . ' ' . $price_unit;
	if ($content2=='') {
		$content2='<tr><td colspan="4"><div class="w-100" style="margin:auto;margin-top:40px;margin-bottom:40px;">
		<div class="bg-danger rounded text-center w-75" style="margin:auto;padding:20px;color:white;">سبد خرید خالی است</div></div></td></tr>'; 
	}
	echo json_encode(array(1,$content,$counter,$PriceAllTag,$content2, $PriceAll));
}
//--------------------------------------- حذف از سبد خرید
if ($type==13) {
//	if (!$ok_cookie) { echo json_encode(array(-1,'لطفا ابتدا وارد اکانت کاربری خود شوید'));	 return;}
	if ($ok_cookie) { $id_user = $usernameid;} else { 
		if (isset($_SESSION['id_user'])) {    $id_user = $_SESSION['id_user']; } else {
			echo json_encode(array(1,'',0,''));	 return;
		}
	}

	$id			= intval($_POST['id']);			// کد محصول
	$result  = pdo_query("select * from `user_order` where id_main='$id' and id_user='$id_user' ",'',0);
	$row = pdo_fetch($result);
	if ($row=='') { 
		echo json_encode(array(-1,'چنین محصولی در سبد شما یافت نشد'));	 
		return; 
	}else{
		$result  = pdo_query("delete from `user_order` where id_main='$id' ",'',0);
		echo json_encode(array(1,'محصول از سبد شما حذف شد'));	 
	}
}	
//--------------------------------------- تغییر تعداد در سبد خرید
if ($type==14) { 
//	if (!$ok_cookie) { echo json_encode(array(-1,'لطفا ابتدا وارد اکانت کاربری خود شوید'));	 return;}
	if ($ok_cookie) { $id_user = $usernameid;} else { 
		if (isset($_SESSION['id_user'])) {    $id_user = $_SESSION['id_user']; } else {
			echo json_encode(array(1,'',0,''));	 return;
		}
	}
	
	$id			= intval($_POST['id']);			// کد محصول
	$number		= intval($_POST['number']); 	// تعداد سفارش محصول
	if ($number==0) $number=1;
	
	$result  = pdo_query("select * from `user_order` where id_main='$id' and id_user='$id_user' ",'',0);
	$row = pdo_fetch($result);
	if ($row=='') { echo json_encode(array(-1,'چنین محصولی در سبد شما یافت نشد'));	 return; }
	$result  = pdo_query("update `user_order` set number='$number' where id_main='$id' ",'',0);
	echo json_encode(array(1,'سبد شما بروزرسانی شد'));	 
}	

//--------------------------------------- ذخیره سفارش از روی سبد خرید به همراه پرداخت آن
if ($type==15) { 
	if (!$ok_cookie) { echo json_encode(array(-1,"جهت ثبت سفارش الزاما باید در سایت ثبت نام کرده و وارد شوید <BR> <hr style='background-color:white;'>
	سبد خرید شما ذخیره شده و به محض ثبت نام و یا ورود مجددا برای شما نمایش داده می شود<BR>
	<hr style='background-color:white;'>
	<a href='/register/' style='color:#3673f7;' target=_blank>لینک ثبت نام</a> | <a href='/login/' style='color:#3673f7;' target=_blank>لینک ورود</a>"));	 return;}
	//----------------------------------------------------
	// بررسی وجود توکن
	if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token'])) {
		echo json_encode([-1, 'درخواست نامعتبر (CSRF)']);
		return;
	}

	// بررسی برابر بودن
	if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
		echo json_encode([-1, 'توکن نامعتبر']);
		return;
	}
	
	//----------------------------------------------------
	$userid = 0;
	$user_create   = '';
	if ($ok_cookie) {
		$userid = $usernameid;
		$user_create = $usernamefarsi;
	}

	$idrec		= intval($_POST['idrec']);
	$redirect	= intval($_POST['redirect']);
	$idaddres	= intval($_POST['idaddres']);
	
//	$PriceAll	= intval($_POST['PriceAll']);	// جمع مبالغ سفارش ها
//	$PriceKOL	= intval($_POST['PriceKOL']);	// جمع مبالغ سفارش ها و هزینه ارسال
	$PriceAll	= floatval($_POST['PriceAll']);	// جمع مبالغ سفارش ها
	$PriceKOL	= floatval($_POST['PriceKOL']);	// جمع مبالغ سفارش ها و هزینه ارسال
	$weight_send= intval($_POST['weight_all']);	// مجموع وزن مرسوله ارسالی
	$sender		= intval($_POST['sender']);		// کد روش ارسال
	$price_send	= intval($_POST['send_price']); // هزینه ارسال
	$payment	= intval($_POST['payment']); 	// کد روش پرداخت مبلغ
	$dsc		= $_POST['dsc'];				// توضیحات سفارش
	$dsc_payment= $_POST['dsc_payment'];		// توضیحات واریز مطلب
	$discount_code 	= $_POST['discount_code'];	// کد تخفیف
	$discount_price = $_POST['discount_price'];	// مبلغ تخفیف
	$shipping_time	= $_POST['shipping_time'];	// زمان ارسال
	
	if ($idaddres==0) 	$ErrorMsg .= "تعیین آدرس الزامی است<BR> ";   
	if ($sender==0) 	$ErrorMsg .= "تعیین روش ارسال الزامی است<BR> ";   
	if ($payment==0) 	$ErrorMsg .= "تعیین روش پرداخت هزینه الزامی است<BR> ";   
	if ($PriceAll==0) 	$ErrorMsg .= "جمع مبلغ سفارش ها برابر با صفر است<BR> ";   
	if ($ErrorMsg!='') {echo json_encode(array(-1,$ErrorMsg));	return ;}
	//-----------------------------------------  ریز سفارش خرید
	$query 	= pdo_query("select * from `user_order` where id_user='$userid' ");
	$tedad	= pdo_rowcount($query);
	if ($tedad==0) {echo json_encode(array(-1,"سبد خرید خالی می باشد"));	return ;}
	//---------------------------- جستجو در جدول آدرس ها
	$query 	= pdo_query("select * from `user_addres` where id_main='$idaddres' ");
	$row  	= pdo_fetch($query);
	if ($row=='')  {echo json_encode(array(-1,"کد آدرس یافت نشد"));	return ;}
	$id_addres_customer=$row['id_main'];	
	$id_ostan		= $row['id_ostan'];
	$id_city		= $row['id_city'];
	$addres			= $row['addres'];
	$zipcode		= $row['zipcode'];
	$plaque			= $row['plaque'];
	$unit			= $row['unit'];
	$gps			= $row['gps'];
	$reciver_name   = $row['recip_name'];
	$reciver_mobile = $row['recip_mobile'];
	$tetr_addres	= $row['tetr'];
	if ($reciver_name=='') 	 $reciver_name 	 = $usernamefarsi;
	if ($reciver_mobile=='') $reciver_mobile = $usermobile;
	//---------------------------- جستجو در جدول روش های ارسال
	$query 	= pdo_query("select * from `send_title` where id_main='$sender' ");
	$row  	= pdo_fetch($query);
	if ($row=='')  {echo json_encode(array(-1,"کد ارسال کننده مرسوله یافت نشد"));	return ;}
	$id_send=$row['id_main'];			$name_send = $row['name'];			$type_send = $row['id_type'];
	//---------------------------- جستجو در جدول درگاه های پرداخت
	$query 	= pdo_query("select * from `payment_getway` where id_main='$payment' ");
	$row  	= pdo_fetch($query);
	if ($row=='')  {echo json_encode(array(-1,"کد درگاه پرداخت یافت نشد"));	return ;}
	$id_payment		= $row['id_main'];				$name_payment 	= $row['name'];			
	$type_payment 	= $row['id_type'];				$account_number	= $row['account_number'];
	//--------------------------- اعمال کد تخفیف
	$price_discount	= 0;	$id_discount	= 0;	$name_discount	= '';
	$code_discount	= '';	$type_discount	= 0;
	$price_all = CalculatePriceAll($userid);
	if ($discount_code!='') {
		$result_discount  = json_decode(ProcessDiscount($discount_code, $price_all, $price_send, $userid));
		if ($result_discount[0]<0) { 
			echo json_encode($result_discount);
			return;  // مخصوص کدهای خطا و یا استفاده های مجدد از کد تخفیف
		} else {
			$price_discount	= $result_discount[1];
			$id_discount	= $result_discount[3];
			$name_discount	= $result_discount[4];
			$code_discount	= $result_discount[5];
			$type_discount	= $result_discount[6];
		}
	}
	//------------------------------------
	$price_payment = $price_all + $price_send - $price_discount;
	$PriceKOL = $price_payment;
	//-------------------------------------------------ذخیره تیتر فاکتور 
	$query_array = array(':dsc'=>$dsc, ':dsc_payment'=>$dsc_payment, ':shipping_time'=>$shipping_time);
	$status_order	= 1;	// سفارش تکمیل نشده
	$status_send 	= 1;	// منتظر ارسال
	$status_payment	= 2; 	// منتظر پرداخت
	// مربوط به تخفیف که هزینه استفاده نمی شود
	//id_discount, name_discount, code_discount, type_discount, price_discount,
	$SQL_QUERY="insert into `order_title` 
	(date_save, date, id_customer, name_customer, mobile_customer, email_customer, id_addres_customer,  id_ostan, id_city, addres, zipcode, plaque, unit, gps, tetr_addres,  reciver_name, reciver_mobile, id_send, name_send, type_send, price_send, weight_send, status_send, id_payment, name_payment, type_payment, account_number, price_payment, dsc_payment, status_payment, status_order, dsc, price_total,
	id_discount, name_discount, code_discount, type_discount, price_discount, shipping_time, 
	user_create, ip_last_edit, date_last_edit) 
	values 
	('$nowdate $nowtime', '$nowdate', '$usernameid', '$usernamefarsi', '$usermobile', '$useremail', 
	'$id_addres_customer', '$id_ostan', '$id_city', '$addres', '$zipcode', '$plaque', '$unit', '$gps', '$tetr_addres', '$reciver_name', '$reciver_mobile', 
	 '$id_send', '$name_send', '$type_send', '$price_send', '$weight_send', '$status_send',
	 '$id_payment', '$name_payment', '$type_payment', '$account_number', '$price_payment', :dsc_payment, '$status_payment', '$status_order', :dsc, '$price_all', 
	 '$id_discount', '$name_discount', '$code_discount', '$type_discount', '$price_discount', :shipping_time, 
	 '$user_create', '$ip', '$nowdate $nowtime')
	 ";
	 
	$sql=pdo_query($SQL_QUERY,$query_array,0);
	if ($idrec==0) {	$last_id = pdo_lastinsert();}	else {
		$last_id=$idrec;
		$query = pdo_query("delete from `order_detils` where id_order='$idrec' ");
	}
	//------------------------------------------------------------------------ ذخیره ریز فاکتور
	$query = pdo_query("SELECT    uo.id_main, uo.id_product, uo.id_price, uo.number, p.tetr, p.leds, p.photo_product, pp.weight, pp.price1, pp.price2 	from  user_order uo
	JOIN    product p ON uo.id_product = p.id
	JOIN    product_price pp ON uo.id_price = pp.id
	where uo.id_user='$usernameid' order by uo.date desc
	");
	while ($row2=pdo_fetch($query)){
		$id_product	= $row2['id_product'];
		$id_price	= $row2['id_price'];
		$fullname	= $row2['tetr'];
		$number		= $row2['number'];
		$weight		= $row2['weight'];
		$price		= $row2['price1'];
		$price_discount	= $row2['price2'];
		if ($price_discount > 0) $price0=$price_discount; else $price0=$price;
		$total_price = ($number * $price0);
		//-------------------
		$product_content='';
		$query3 = pdo_query("select content from `product_price_property` where id_price='$id_price'");
		$row3 	= pdo_fetch($query3);
		if ($row3!='') $product_content = $row3['content'];
		if ($product_content!='')  $fullname = "$fullname [$product_content]";
		//----------------------------
		
		
		$query2= pdo_query("insert into `order_detils` 
		(id_order, id_product, id_price, name, number, price, price_discount, weight, total_price) 
		values 
		('$last_id', '$id_product', '$id_price', '$fullname', '$number', '$price', '$price_discount', '$weight', '$total_price' ) ",'',0);
	}
	$sql=pdo_query("delete from `user_order` where id_user='$usernameid' ");
	//----------------------------------------------------------------- ذخیره اطلاعات کد تخفیف
	// درصورتی کد عددی تخفیف از جدول مربوطه به دست آمده بود
	if ($id_discount!=0) {
		$query3 = pdo_query("insert into `discount_used` (id_discount, id_user, id_order, ip_last_edit, date_last_edit, user_last_edit) values ('$id_discount', '$userid', '$last_id', '$ip_last_edit', '$date_last_edit', '$user_last_edit'  )");
		$query3 = pdo_query("update `discount` set count_used=(count_used+1) where id_main='$id_discount' ");
	}
	unset($_SESSION['csrf_token']);
	//-----------------------------------------------------------------
	$shop_name 			= $row_setting['shop_name'];
	$email_manager		= $row_setting['email_manager'];
	$email_to_manager	= explode(',',$row_setting['email_to_manager']);
	$email_to_user		= explode(',',$row_setting['email_to_user']);
	$sms_list_send  	= explode(',',$row_setting['sms_list_send']);
	$mobile_manager		= $row_setting['sms_manager'];
	//---------------------------------------------
	$ok_payment= false;
	if ($redirect==1) { // ارجاع به درگاه پرداخت
		list($code,$message) = SendRequestPayment($last_id);
		if ($code<=0) {
			$newlink = "<a href='/myorder/$last_id'>سفارش من</a>";			
			$message = "سفارش شما با موفقیت ثبت شد <BR> جهت مشاهده لیست سفارشات به لینک ذیل مراجعه کنید <BR> $newlink <BR>  در بخش درگاه پرداخت <BR> <div class='col-12 bg-danger'>$message</div> ";
			
		}else{
			$ok_payment= true;
		}
		// درصورتی که متغیر کد برابر با 2 باشد به آدرس موجود در متغیر مسیج ریدایرکت میشود
		$final_result = json_encode(array(2,$code,$message));
		
	}else{
		$newlink = "<a href='/myorder/$last_id'>سفارش من</a>";
		$final_result  = json_encode(array(1,"سفارش شما با موفقیت ثبت شد <BR> جهت مشاهده لیست سفارشات به لینک ذیل مراجعه کنید <BR> $newlink"));
	}
	//---------------------------------------------------------
	//ارسال پیامک و یا ایمیل های موردنظر بعد از پرداخت قطعی
	$sms_config = isset($row_sms[0]) ? $row_sms[0] : null;
	if ($ok_payment) {
		// ارسال پیامک به مدیر جهت ثبت سفارش جدید 
		if (in_array(3,$sms_list_send)) {			
			if ($mobile_manager!='') {	
				$Message = " کاربر [$usernamefarsi] به مبلغ [$PriceKOL][$price_unit] از فروشگاه شما خرید کرد \r\n $shop_name";
				list($ValueReturn, $MessageReturn)= SendSmsFromTable($mobile_manager, $Message, $sms_config);		
//				list($ValueReturn,$MessageReturn,$smsdeliveri) = EnjineSendSms($mobile_manager, $Message);
			}
		}
		// ارسال پیامک به کاربر به هنگام ثبت سفارش جدید
		if (in_array(4,$sms_list_send)) {			
			if ($usermobile!='') {
				$Message = " شما  به مبلغ [$PriceKOL][$price_unit] از فروشگاه [$shop_name] خرید کردید \r\n شماره سفارش: $last_id ";
				list($ValueReturn, $MessageReturn)= SendSmsFromTable($usermobile, $Message, $sms_config);		
//				list($ValueReturn,$MessageReturn,$smsdeliveri) = EnjineSendSms($usermobile, $Message);
			}
		}
		// ارسال ایمیل به مدیر به هنگام خرید از فروشگاه
		if (in_array(1,$email_to_manager)) {			
			if ( ($email_manager!='') ) {
				$Message = " کاربر [$usernamefarsi] به مبلغ [$PriceKOL] [$price_unit] از فروشگاه شما خرید کرد <BR> $shop_name ";
				$OkSendEmail = SendEmail('',$email_manager," خرید در فروشگاه $shop_name",$Message,'',0);
			}
		}
		// ارسال ایمیل به کاربر به هنگام خرید از فروشگاه
		$email_to_user=explode(',',$row_setting['email_to_user']);
		if (in_array(1,$email_to_user)) {			
			if ($useremail!='') {
				$Message = " شما به مبلغ [$PriceKOL] [$price_unit] از فروشگاه $shop_name خرید کردید<BR> شماره سفارش شما: $last_id";
				$OkSendEmail = SendEmail('',$email,"خرید از فروشگاه $shop_name",$Message,'',0);
			}
		}
	}
	//------------------------
	echo $final_result;
}	
//--------------------------------------- پرداخت فاکتور یا سفارش
if ($type==16) { 
	if (!$ok_cookie) { echo json_encode(array(-1,'لطفا ابتدا وارد اکانت کاربری خود شوید'));	 return;}
	$userid = 0;
	$user_create   = '';
	if ($ok_cookie) {
		$userid = $usernameid;
		$user_create = $usernamefarsi;
	}
	$id_order		= intval($_POST['id_order']);
	$dsc_payment	= clean($_POST['dsc']);
	list($code,$message) = SendRequestPayment($id_order, $dsc_payment);
	echo json_encode(array($code,$message));
	
}
//--------------------------------------- جستجوی حرف به حرف در بخش مقایسه ها
if ($type==17) { 
	$searchVar	= clean(trim($_POST['searchVar']));
	if (mb_strlen($searchVar,'UTF-8')<3) {echo json_encode(array(0,"")); return;}
	$pdo_array = array("%$searchVar%");		
	$query 		= pdo_query("select id,tetr from `product` where isdeleted=0 and active=2 and binary `tetr`  like  ?  limit 0,5",$pdo_array,0);
	$countall	= pdo_rowcount($query);
	if ($countall==0) { echo json_encode(array(0,"")); return;}
	$ret = "<ul>";
    while($row = pdo_fetch($query)){
		$id		= $row['id'];
		$tetr 	= htmlspecialchars($row['tetr']);
        $ret .= "<li onclick='AddDelRec($id);'>$tetr</li>";
    }
    $ret .= "</ul>";	
	echo json_encode(array(1,$ret));

}
//--------------------------------------- اعمال کد تخفیف
if ($type==18) { 
	$discount	= clean(trim($_POST['discount']));		// کد تخفیف
	$price_all	= intval(trim($_POST['price_all']));		// جمع کل فاکتور
	$price_send = intval(trim($_POST['price_send']));	// هزینه ارسال
	$id_user	= clean(trim($_POST['id_user']));		// کد کاربر خریدار
	if ($price_all==0) { echo json_encode(array(-1,"فاکتور مبلغی ندارد")); return;}
	if ($id_user=='') { echo json_encode(array(-1,"کد کاربر مشخص نشده است")); return;}
	$price_all = CalculatePriceAll($id_user);
	$result = ProcessDiscount($discount, $price_all, $price_send, $id_user);
	echo $result;
}
//-------------------------------------- ثبت آدرس
if ($type==19) {
	if (!$ok_cookie) { echo json_encode(array(-1,"جهت ثبت سفارش الزاما باید در سایت ثبت نام کرده و وارد شوید <BR> <hr style='background-color:white;'>
	سبد خرید شما ذخیره شده و به محض ثبت نام و یا ورود مجددا برای شما نمایش داده می شود<BR>
	<hr style='background-color:white;'>
	<a href='/register/' style='color:#3673f7;' target=_blank>لینک ثبت نام</a> | <a href='/login/' style='color:#3673f7;' target=_blank>لینک ورود</a>"));	 return;}
	$userid = 0;
	$user_create   = '';
	if ($ok_cookie) {
		$userid = $usernameid;
		$user_create = $usernamefarsi;
	}
	$_POST = CleanPost($_POST);
	$idrec		= intval($_POST['idrec_addres']);
	$id_ostan	= intval($_POST['id_ostan']);
	$id_city	= intval($_POST['id_city']);
	$addres		= $_POST['addres'];
	$plaque		= EnglishNumber($_POST['plaque']);
	$unit		= EnglishNumber($_POST['unit']);
	$zipcode	= EnglishNumber($_POST['zipcode']);
	$tetr		= $_POST['tetr'];
	$gps		= $_POST['gps'];
	$distance_to_shop = $_POST['distance_to_shop'];
	if (isset($_POST['recip_me'])) $recip_me=1; else $recip_me=0;
	$recip_name	= $_POST['recip_name'];
	$recip_mobile= EnglishNumber($_POST['recip_mobile']);
	$recip_email	= '';
	if ($id_ostan==0) 	$ErrorMsg .= "تعیین استان الزامی است<BR> ";   
	if ($id_city==0) 	$ErrorMsg .= "تعیین شهر الزامی است<BR> ";   
	if ($addres=='') 	$ErrorMsg .= "تعیین آدرس الزامی است<BR> ";   
	if ($plaque=='') 	$ErrorMsg .= "تعیین پلاک الزامی است<BR> ";   
	if ($unit=='') 		$ErrorMsg .= "تعیین واحد الزامی است<BR> ";   
//	if ($zipcode=='') 	$ErrorMsg .= "تعیین کدپستی الزامی است<BR> ";   
//	if (mb_strlen($zipcode,'utf-8')!=10) $ErrorMsg .= "کدپستی صحیح نمی باشد<BR> ";   
	if ($tetr=='')		$ErrorMsg .= "تعیین عنوان آدرس الزامی است<BR> ";   
	// اگر در تنظیمات قید شده باشد که الزاما باید نقطه gps ثبت شود
	if ( ($row_setting['must_give_gps']==1) and ($row_setting['show_gps']==1) ) {
		if ($gps=='')		$ErrorMsg .= "تعیین مکان آدرس الزامی است<BR> ";   
//		if ($distance_to_shop=='') $ErrorMsg .= "فاصله تا فروشگاه مشخص نشده است<BR> ";   
	}
	if ($recip_me==1) {
		if ($recip_name=='')  	$ErrorMsg .= "تعیین نام و نام خانوادگی تحویل گیرنده الزامی است<BR> ";   
		if (($recip_mobile=='') ) $ErrorMsg .= "تعیین  موبایل تحویل گیرنده الزامی است<BR> ";   
	}else {
		$recip_name 	= $usernamefarsi;
		$recip_mobile= $usermobile;
		$recip_email	= $useremail;
		
	}
	if ($ErrorMsg!='') {echo json_encode(array(-1,$ErrorMsg));	return ;}
	
	//-------------------------------------------------ذخیره تیتر فاکتور 
	$query_array = array(':recip_name'=>$recip_name, ':tetr'=>$tetr, ':recip_mobile'=>$recip_mobile, ':addres'=>$addres, ':zipcode'=>$zipcode, ':plaque'=>$plaque, ':unit'=>$unit, ':gps'=>$gps, ':distance_to_shop'=>$distance_to_shop);
	if ($idrec==0) {
		$SQL_QUERY="insert into `user_addres` 
		(id_user, tetr, id_ostan, id_city, addres, plaque, unit, gps, distance_to_shop, zipcode, recip_me, recip_name, recip_mobile, recip_email, ip_last_edit, date_last_edit) 
		values 
		('$userid', :tetr, '$id_ostan', '$id_city', :addres, :plaque, :unit, :gps, :distance_to_shop, :zipcode, '$recip_me', :recip_name, :recip_mobile, '$recip_email', '$ip_last_edit', '$date_last_edit')
		 ";
		$Message = "اطلاعات آدرس با موفقیت ذخیره شد";
	}else{
		$SQL_QUERY="update `user_addres`  set tetr=:tetr, id_ostan='$id_ostan', id_city='$id_city', addres=:addres, plaque=:plaque, unit=:unit, gps=:gps, distance_to_shop=:distance_to_shop, zipcode=:zipcode, recip_me='$recip_me', recip_name=:recip_name, recip_mobile=:recip_mobile, recip_email='$recip_email', ip_last_edit='$ip_last_edit', date_last_edit='$date_last_edit' where id_main='$idrec'
		 ";
		$Message = "اطلاعات آدرس با موفقیت اصلاح شد";
	}
	$sql=pdo_query($SQL_QUERY,$query_array,0);
	if ($idrec==0) {	$last_id = pdo_lastinsert();}	else {		$last_id=$idrec;	}
	list($title,$dsc_text,$dsc_web) = GetFullAddres($last_id);
	echo json_encode(array(1,"$Message", $last_id, $dsc_text));	
	return ;
	
}
//-------------------------------------- خواندن آدرس
if ($type==20) {
	if (!$ok_cookie) { echo json_encode(array(-1,"جهت ثبت سفارش الزاما باید در سایت ثبت نام کرده و وارد شوید <BR> <hr style='background-color:white;'>
	سبد خرید شما ذخیره شده و به محض ثبت نام و یا ورود مجددا برای شما نمایش داده می شود<BR>
	<hr style='background-color:white;'>
	<a href='/register/' style='color:#3673f7;' target=_blank>لینک ثبت نام</a> | <a href='/login/' style='color:#3673f7;' target=_blank>لینک ورود</a>"));	 return;}
	$userid = 0;
	$user_create   = '';
	if ($ok_cookie) {
		$userid = $usernameid;
		$user_create = $usernamefarsi;
	}
	$_POST = CleanPost($_POST);
	$idrec		= intval($_POST['idrec']);
	$temp = pdo_query("select id_main, id_ostan, id_city, id_sector, addres, plaque, unit, gps, distance_to_shop, zipcode, tetr, recip_me, recip_name, recip_mobile from `user_addres` where id_main='$idrec' and id_user='$userid' ");
	$row  = pdo_fetch($temp);
	if ($row=='') {echo json_encode(array(-1,"چنین آدرسی یافت نشد"));	return ;}
	echo json_encode(array(1,$row));	
	return ;
	
}
//--------------------------------------- ویژه فرم ها
if ($type==101) { 
//	var_dump($_POST);
	$idform = intval($_POST['idform']);
	$query 	= pdo_query("select * from `group_main` where id='$idform' and active='1' and isdeleted='0' ");
	$row	= pdo_fetch($query);
	if ($row=='') { echo json_encode(array(-1,'کد فرم موردنظر یافت نشد'));	 return;}
	$query2	= pdo_query("select * from `group_form` where id_form='$idform' and isdeleted='0' order by idsort ");
	$CoutnField	= pdo_rowcount($query2);
	if ($CoutnField==0) { echo json_encode(array(-1,'برای فرم موردنظر فیلدی یافت نشد'));	 return;}
	$upload_path = "/files/forms/$idform/";
	$row_field  = pdo_fetchall($query2);
	clean($_POST);
	//---------------------------------------------------------------------
	$capcha = $row['ok_capcha'];
	if ($capcha==1) {
		$CapchaSecCode1 = $_POST['CapchaSecCode1'];
		$CapchaSecCode2 = $_POST['CapchaSecCode2'];
		if (md5($CapchaSecCode1) != $CapchaSecCode2) {
			 echo json_encode(array(-1,'لطفا کد کپچا را درست وارد نمایید'));	 return;
		}
	}
	if ($capcha==2) {
		$OkCapchaGoogle = false;
		$sitekey   = $row_setting['google_sitekey'];
		$secretkey = $row_setting['google_secretkey'];
		if (($sitekey!='') and ($secretkey!='') ) {
			if(isset($_POST['g-recaptcha-response']) && !empty($_POST['g-recaptcha-response'])) {
				$verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secretkey.'&response='.$_POST['g-recaptcha-response']);
				$responseData = json_decode($verifyResponse);
				$OkCapchaGoogle = $responseData->success;
			}
			if (!$OkCapchaGoogle) {echo json_encode(array(-1,'لطفا کد کپچا را درست وارد نمایید'));	 return;}
		}
	}
	$groupname  = $row['form_label'];
	if ($groupname!='') $groupname = "$groupname\r\n";
	//---------------------------------------------------------------------
	$ListTypeField=array(0=>'',1=>"متن تک خطی", 2=>"متن چند خطی", 3=>"فهرست کشویی",  4=>"عددی (مبلغ)",  5=>"چک لیست",  6=>"رادیو لیست",  7=>"فایل - تصویر", 8=>"رمز عبور", 9=>"خط جداکننده",  10=>"فایل", 11=>"تاریخ شمسی", 12=>"عددی", 13=>'تاریخ میلادی');

	$ErrorMsg = '';
	$ListFieldName = ''; $ListFieldVariable=''; $EditSql='';	$MessageEmail='';
	for ($iz=0; $iz<count($row_field);$iz++) {
//		var_dump($row_field[$iz]);
		$fieldname 	= "f_".$row_field[$iz]['id_main']; 	// نام انگلیسی (اصل) فیلد
		$label     	= $row_field[$iz]['tetr'];			// نام فارسی (لیبل) فیلد
		$label 		= str_replace(':','', $label);
		$typefield 	= $row_field[$iz]['field_type'];  		// نوع فیلد
		$mustfill  	= intval($row_field[$iz]['must_answer']);  // الزام محتوا
		$defultvalue= $row_field[$iz]['default_value']; 	// مقدار اولیه
		$error_msg	= $row_field[$iz]['error_msg']; 		// متن پیغام خطا
		$fieldanswer= $row_field[$iz]['list_answer'];		// لیست پاسخ ها
		$listanswer = (explode("\r\n",$fieldanswer )); 		//جوابها
		$max_length = $row_field[$iz]['max_length'];				// نهایت اندازه ورودی
		$readonly	= $row_field[$iz]['readonly'];			// فقط خواندنی
		$multianswer= $row_field[$iz]['multianswer'];		// چند پاسخ بتواند بگیرد
		$regex_field= $row_field[$iz]['regex_field'];		// رگولار اکسپریشن
		$uniq_field = $row_field[$iz]['uniq_field'];		// غیرتکراری بودن محتوا
		$save_label	= $row_field[$iz]['save_label'];			// ثبت به عنوان برچسب
		//-----------------------------------------------
		switch ($typefield) {
			case 4:		// مبلغ
				$$fieldname = str_replace(",","",EnglishNumber(trim($_POST[$fieldname]))); 
				break;
			case 12:	// عدد ساده
				$$fieldname = str_replace(",","",EnglishNumber(trim($_POST[$fieldname]))); 
				break;
			case 13:// اسم رمز
				if ($$fieldname!='')	$$fieldname = md5(md5($$fieldname));
				break;
			case 10:  // file
			case 7:   // image file
				$contor_file = -1;
				$$fieldname = $_POST["file_$fieldname"];
				$upload_path = "files/forms/$idform/";
				if ($max_length==0) $max_length=2;
				$max_filesize = $max_length *1024* 1024 ;
				$AcceptPhp = '';
				if (($typefield==7) and ($fieldanswer=='') ){ 
					$AcceptPhp= "jpe,jpg,jpeg,png,pcx,gif,bmp,ico,svg,webp,tif,tiff"; 
				}
				$Jamlistanswer =count($listanswer);
				
				if (($Jamlistanswer>0) and ($fieldanswer!='')) {
					for ($j=0;$j<$Jamlistanswer;$j++){
						$Extended = trim($listanswer[$j]);
						if ($Extended!='') {$AcceptPhp .= "$Extended,"; }
					}
					if ($AcceptPhp!='')  $AcceptPhp 	 = substr($AcceptPhp,0,-1); 
				}
				$allowedfiletypes	= $AcceptPhp ; 
				$tablename_folder	= $fieldname.'_' ; // قسمت اول اسم فایل پس از آپلود
				$userfilename="upload_$fieldname";
				if ($multianswer==1) {	
					$file_count = count($_FILES[$userfilename]['name']);	
					if (!empty($_FILES[$userfilename]["name"][0])) {
						for ($contor_file = 0; $contor_file < $file_count; $contor_file++) {
							$ErrorMsg3='';
							include("uploadfile_form.php");
							
							if ($ErrorMsg3!='') {
								$ErrorMsg .=  $ErrorMsg3 . '<BR>';
							}else{
								$$fieldname .= "$idform/".$NameAfterUpload ."\r\n";
							}
						}
					}
				}else{
					if (!empty($_FILES[$userfilename]["name"])) {
						$ErrorMsg3='';
						include("uploadfile_form.php");
						//if ($ErrorMsg3!='') {echo json_encode(array(-1,$ErrorMsg3));	 return;}
						$ErrorMsg .=  $ErrorMsg3 ;
						$$fieldname = "$idform/".$NameAfterUpload;
					} 
				}
				break;
			case 3 : // listbox
				if ($multianswer==1) {	
					if (isset($_POST[$fieldname]))  $$fieldname = ','.implode(',',$_POST[$fieldname]).',';  
						else $$fieldname = '';
				}else{
					$$fieldname = $_POST[$fieldname];
				}
				break;
			case 5 : // check box   // تبدیل آرایه به تک متغیر
				if (isset($_POST[$fieldname]))  $$fieldname = ','.implode(',',$_POST[$fieldname]).',';  
					else $$fieldname = '';
				break;	
			default:
				$$fieldname = $_POST[$fieldname];
		}
		// -------------------- الزام پر بودن محتوا برای تمامی متغیرها
		if ($mustfill==1) {
			If ($$fieldname=='') $ErrorMsg .= " مقدار « $label » خالی می باشد <BR>";
		}
		//------------------------
		if ($regex_field!='') {
			$regex_field = "'$regex_field'";
			if ((is_valid_regex($regex_field))) {
				echo "\r\n regex_field=$regex_field \r\n $fieldname=".$$fieldname;
				if (in_array($typefield, array(1,2,4,8,11,12,13)) )  	{ 
					if (preg_match($regex_field, $$fieldname)) { }else{
						$ErrorMsg = " اطلاعات مربوط به [$label] صحیح نمی باشد";
					}
				}
			}
		}
		// -------------------- تست غیر تکراری بودن محتوای فیلد
		if ($uniq_field==1) { 
			$query2= pdo_query("select count(*) from `forms_content` where id_forms='$idform' and JSON_EXTRACT(content, '$.$fieldname') = '$$fieldname' ");
			$jamkol= pdo_count($query2);
			if ($jamkol>0) $ErrorMsg .= " مقدار « $label » با عبارت [$$fieldname] تکراری می باشد <BR>";
		}
		if ($save_label==1) { $groupname .= $$fieldname."\r\n";}
		$pdo_array["$fieldname"] = $$fieldname;
		
	}
	if ($ErrorMsg != '') {	echo json_encode(array(-1,$ErrorMsg));	 return;	}
	$SuccessMesage= $row['form_success_msg'];
	if ($SuccessMesage=='') $SuccessMesage="اطلاعات شما با موفقیت ثبت شد";

//	var_dump($pdo_array);
	$content = json_encode($pdo_array, JSON_UNESCAPED_UNICODE);
	$pdo_array2 = array(':groupname'=>$groupname, ':content'=>$content);
	$sql=pdo_query("insert into `forms_content`  (id_forms, id_user, content, groupname, user_last_edit, ip_last_edit, date_last_edit ) values ('$idform', '$usernameid', :content, :groupname, '$usernamefarsi', '$ip', '$nowdate $nowtime') ",$pdo_array2,0);
	echo json_encode(array(1,$SuccessMesage));	 
	return;
//	var_dump($_FILES);
//	var_dump($_POST);
}
//-------------------------------------------------------------------------
//-------------------------------------------------------------------------
//-------------------------------------------------------------------------	
//---------------------------------------------------------
function ListCommentAnswer($idcomment, $bankname, $id, $OkComment) { 
	$query2 = pdo_query("select * from `nazar` where idrec='$id' and deleted='f' and ok='y' and tablename='$bankname' and parentid='$idcomment' order by date desc, time desc  ",'',0);
	$counter2=0;
	$ret = '';
	while ($row_comment2 = pdo_fetch($query2)) {
		$counter2++;
		$id_comment = $row_comment2['id'];
		$message = nl2br($row_comment2['message']);			$answer = nl2br($row_comment2['answer']);
		$country = $row_comment2['country'];				$city = $row_comment2['city'];
		$email 	 = $row_comment2['email'];
		if ($email!='') $email = "<div class='post-meta Visit'><i class='fa fa-envelope'></i> $email </div>";
		
		if ($answer!='') $answer="<p class='text-danger'><span class='bg-danger text-white p-1'> پاسخ: </span><BR>$answer</p>";
		if ($country!='') $country = "<div class='post-meta'><i class='fa fa-flag'></i> $country</div>";
		if ($city!='') $city = "<div class='post-meta'><i class='fa fa-map-marker'></i> $city</div>";
		
		$ret .= "
		<li class='comment-even'>
			<div class='comment-body mr-5' style='background-color:#f3f9f1;line-height:20px;padding-top:10px;padding-bottom:1px;'>
				<header id='header$id_comment' class='row comment-meta' style='gap:10px;'>
					<div class='post-meta date'>$counter2 |</div>
					<div class='post-meta date'><i class='fa fa-calendar'></i> $row_comment2[date] </div>
					<div class='post-meta author'><i class='fa fa-clock-o'></i> $row_comment2[time] </div>
					<div class='post-meta'><i class='fa fa-user'></i> $row_comment2[name]  </div>
					$email
					$country
					$city
				</header>
				<p>$message</p>
				$answer
			</div>
		</li>
		";
	}
	return $ret;
}
//-------------------------------------------------------------------------
//-------------------------------------------------------------------------
//-------------------------------------------------------------------------
//--------------------------------------
function SendRequestPayment($id_order, $dsc_payment='') {
	global $row_setting, $sitenamelink, $ListTypePayment, $usernameid;
	global $user_last_edit, $ip_last_edit, $date_last_edit;

	$tetrsite = $row_setting['tetrsite'];
	if (($id_order==0) ) return array(-1,'شماره سفارش صحیح نمی باشد');


	$query4 	= pdo_query("select * from `order_title` where id_main='$id_order' and isdeleted='0' ");
	$row_order 	= pdo_fetch($query4);
	if ($row_order=='') 	return array(-1,'شماره سفارش صحیح یافت نشد');
	$status_payment		= $row_order['status_payment'];
	$StrStatusPayment 	= $ListTypePayment[$status_payment];
	if ($status_payment!=2) return array(-1,"امکان پرداخت برای این سفارش وجود ندارد، چرا که این سفارش در وضعیت [$StrStatusPayment] می باشد");
	//-----------------------------------
	$amount 		= $row_order['price_payment'];
	$date_save		= $row_order['date_save'];
	list($MaxHour, $StrDifTime) = DifTimeNow($date_save);
	if ($row_setting['delay_payment_order']>0) {
		if ($row_setting['delay_payment_order']<$MaxHour) {
			return array(-1,'تاریخ اعتبار برای پرداخت این سفارش اتمام یافت است');
		}
	}
	$name_customer 	= $row_order['name_customer'];
	$mobile_customer= $row_order['mobile_customer'];
	$email_customer = $row_order['email_customer'];
	
	//---------------------------
	$id_payment		= $row_order['id_payment'];
	$query3 = pdo_query("select * from `payment_getway` where id_main='$id_payment' and active='1' ");
	$row_payment = pdo_fetch($query3);
	if ($row_payment=='') return array(-1,'کد درگاه پرداخت پیدا نشد');
	$payment_type 	= $row_payment['id_type'];
	$acceptor_code 	= $row_payment['acceptor_code'];
	if (trim($row_setting['price_unit'])!='ریال'){		$amount = $amount * 10;	}
	// واریز به حساب و فیش بانکی
	if ($payment_type==1) {
		$query_array = array(':dsc_payment'=>$dsc_payment);
		$query = pdo_query("update `order_title` set dsc_payment=:dsc_payment where id_main='$id_order'",$query_array,0);
		return array(0,'در حالت فیش واریزی ، بعد از تأییدیه مدیر مبنی بر اطلاعات دریافت در بخش اطلاعات پرداخت این سفارش پرداخت شده می گردد');
	}
	//زرین پال
	if ($payment_type==2) {
		$callback_url = $sitenamelink . "/verify/" . $id_order .'?';
		$data = array("merchant_id" => $acceptor_code, "amount" => $amount,
			"callback_url" => $callback_url,
			"description" => " خرید [$name_customer] از [$tetrsite]",
			"metadata" => ["email" =>$email_customer, "mobile" => $mobile_customer, "order_id"=>"$id_order"],
		);
//		file_put_contents("log.txt", print_r($data, true), FILE_APPEND);		
		$jsonData = json_encode($data);
		$ch = curl_init('https://api.zarinpal.com/pg/v4/payment/request.json');
		curl_setopt($ch, CURLOPT_USERAGENT, 'ZarinPal Rest Api v1');
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
		curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'Content-Type: application/json',
			'Content-Length: ' . strlen($jsonData)
		));

		$result = curl_exec($ch);
		$err = curl_error($ch);
		$result = json_decode($result, true, JSON_PRETTY_PRINT);
		curl_close($ch);

		if ($err) {
			return array(-1,"cURL Error #:" . $err);
		} else {
			if (empty($result['errors'])) {
				if ($result['data']['code'] == 100) {
					$authority = $result['data']["authority"];
					$query = pdo_query("insert into `order_receipt` (id_user, id_order, id_payment, authority, amount, last_date, last_ip) values ('$usernameid', '$id_order', '$id_payment', '$authority', '$amount', '$date_last_edit', '$ip_last_edit'  ) ");
					$redirect = "https://www.zarinpal.com/pg/StartPay/" .  $authority;
					return array(1, $redirect);
				}
			} else {
				$message =  'کد خطا: ' . $result['errors']['code'] . ' - متن خطا:' . $result['errors']['message'];
				return array(-1,$message);

			}
		}
		
	}
	//-------------
	return array(-1,"درگاه مربوطه درست تعریف نشده است");
}
//------------- بررسی اینکه آیا این رگولار صحیح است یا خیر
function is_valid_regex($regex) {
	if (trim($regex)=='') return false;
	$regex2 = "'$regex'";
    @preg_match($regex2, '');
    $error_code = preg_last_error();
    return ($error_code === PREG_NO_ERROR);
}
//------------------------------------------
function AfterLoginUser($id_user) {
	if ($id_user<=0) return -1;
	if (isset($_SESSION['id_user'])) {    
		$id_user_session = $_SESSION['id_user'];
		$result = pdo_query("update `user_order` set id_user='$id_user' where id_user='$id_user_session' ");
		unset($_SESSION['id_user']);
	} 
	//----------- اگر قبلا علاقه مندی ها داشته
	if (isset($_COOKIE['ListWish'])) {
		$ListWish = array_filter(explode(',',$_COOKIE['ListWish']));
		if (count($ListWish)>0) {
			foreach ($ListWish as $key=>$value) {
				$id_product = $value;
				$result = pdo_query("select count(*) from `user_fav` where id_user='$usernameid' and id_product='$id_product'",'',0);
				$count	= pdo_count($result);
				if ($count==0) {
					$result = pdo_query("insert into `user_fav` (id_user, id_product, date, ip) values ('$usernameid', '$id_product', '$date_last_edit', '$ip_last_edit')",'',0);
				}
			}
		}
		@setcookie('ListWish');
		unset($_COOKIE['ListWish']);		
	}
	
}
//--------------------------------------------------
function ProcessDiscount($discount, $price_all, $price_send, $id_user) {
	global $nowdate;
	$pdo_array = array("discount"=>$discount);		
	$query 		= pdo_query("select * from `discount` where code=:discount",$pdo_array,0);
	$countall	= pdo_rowcount($query);
	if ($countall==0) { return json_encode(array(-1,"چنین کد تخفیفی وجود ندارد")); }
	$row_discount = pdo_fetch($query);
	if ($row_discount=='') { return json_encode(array(-1,"محتوا کد تخفیف خالی است")); }
	$id_discount	= $row_discount['id_main'];
	$max_price		= $row_discount['max_price'];
	$id_topics  	= $row_discount['id_topics'];
	$id_type		= $row_discount['id_type'];
	$price_rec 		= $row_discount['price'];
	$darsad_rec		= $row_discount['darsad'];
	//-------------------------------
	if ($row_discount['max_used']!=0) {
		if ($row_discount['count_used'] >= $row_discount['max_used']) {
			return json_encode(array(-1,"ظرفیت تعداد استفاده از این کد تخفیف تکمیل شده است")); return;
		}
	}
	if ($row_discount['date_start']!='') {
		if ($nowdate < $row_discount['date_start']) {
			return json_encode(array(-1,"زمان استفاده از این کد تخفیف شروع نشده است")); return;
		}
	}
	if ($row_discount['date_end']!='') {
		if ($nowdate > $row_discount['date_end']) {
			return json_encode(array(-1,"زمان استفاده از این کد تخفیف پایان یافته است")); return;
		}
	}
	if ($row_discount['min_order']!=0) {
		if ($price_all < $row_discount['min_order']) {
			return json_encode(array(-1,"حداقل خرید موردنیاز این کد تخفیف انجام نشده است")); return;
		}
	}
	if ($row_discount['max_order']!=0) {
		if ($price_all > $row_discount['max_order']) {
			return json_encode(array(-1,"مبلغ این فاکتور فراتر از ظرفیت این کد تخفیف می باشد")); return;
		}
	}
	//------------------- تست عدم استفاده قبلی توسط کاربر از این کد تخفیف
	$query3 = pdo_query("select * from `discount_used` where id_discount='$id_discount' and id_user='$id_user' ");
	$tedad	= pdo_rowcount($query3);
	if ($tedad>0) {
			return json_encode(array(-1,"شما قبلا از این کد تخفیف استفاده کرده اید")); 
	}
	//------------------------------------------------------------------
	if ($id_type==3) { // ارسال رایگان
		$price_discount = $price_send;
		$price_final 	= $price_all ;
	}
	if ($id_type==2) { // مبلغ ثابت
		$price_discount = $price_rec;
		$price_final = $price_all + $price_send - $price_rec ;
	}
	if ($id_type==1) { // درصد
		$price_all2 = $price_all;
		if (!empty($id_topics)) {	
			$price_all2 = NewPriceAll($id_topics,$id_user, $price_all);
		}
		$price_discount = (int)floor($price_all2 * ($darsad_rec/100));
		if ($max_price>0) {
			if ($price_discount> $max_price) {$price_discount = $max_price;	}
		}
		$price_final = $price_all + $price_send - $price_discount ;
	}
	return json_encode(array(1, $price_discount, $price_final, $row_discount['id_main'] , $row_discount['name'], $row_discount['code'], $row_discount['id_type']) ); 
}

//------------------------------------
function CalculatePriceAll($id_user) {
	$query = pdo_query("SELECT    uo.id_main, uo.id_product, uo.id_price, uo.number, p.tetr, p.leds, p.photo_product, p.id_topics, pp.weight, pp.price1, pp.price2 	from  user_order uo 
	JOIN    product p ON uo.id_product = p.id 
	JOIN    product_price pp ON uo.id_price = pp.id
	where uo.id_user='$id_user' order by uo.date desc
	");
	$PriceAll=0;
	while ($row=pdo_fetch($query)) {
		$price=  $row['price1'];
		if ($row['price2'] > 0) $price=$row['price2'];
		$price_rec = $price * $row['number'];
		$PriceAll += $price_rec;
	}
	return $PriceAll;
	
}
//------------------------------------
function NewPriceAll($list_topics,$id_user, $price_all) {
	if (empty($list_topics)) return $price_all;
	$query = pdo_query("SELECT    uo.id_main, uo.id_product, uo.id_price, uo.number, p.tetr, p.leds, p.photo_product, p.id_topics, pp.weight, pp.price1, pp.price2 	from  user_order uo 
	JOIN    product p ON uo.id_product = p.id 
	JOIN    product_price pp ON uo.id_price = pp.id
	where uo.id_user='$id_user' order by uo.date desc
	");
	$ListTopics = array_filter(explode(',',$list_topics));
	$counter=0;
	$SumWeight=0;
	$PriceAll=0;
	while ($row=pdo_fetch($query)) {
		$counter++;
		$price=  $row['price1'];
		if ($row['price2'] > 0) $price=$row['price2'];
		$price_rec = $price * $row['number'];
		$id_topics = $row['id_topics'];
		$okrec = true;
		if (!empty($id_topics)) {
			$list_temp = array_filter(explode(',',$id_topics));
			foreach($list_temp as $key=>$value) {
				if (!(in_array($value,$ListTopics))) $okrec = false;
				break;
			}
		}
		//-------------------
		if ($okrec) {
			$PriceAll += $price_rec;
		}
	}
	return $PriceAll;
	
}

//-------------------------------------------------------------------------
function SendOtp($reg_type, $reg_value, $type_otp='') {
	global $sms_time, $row_setting, $title;
	global $date_last_edit, $ip_last_edit;
	global $mobile, $email;
	global $MainActiveCode;
	global $row_sms;


	$MainActiveCode = mt_rand(1000, 9999);		
	$fieldname = ($reg_type==0) ? 'mobile' : 'email';
	$timesend = time();
	$otp_validity_seconds = intval($sms_time * 60); // مدت اعتبار OTP (بر حسب ثانیه)
	$valid_from_time = $timesend - $otp_validity_seconds; 


	$query_array = array(':reg_value'=>$reg_value);	
	$query = pdo_query("SELECT * FROM user_active_code WHERE status=0  AND timesend >= $valid_from_time and $fieldname=:reg_value ORDER BY timesend DESC LIMIT 1",$query_array);
	$row   = pdo_fetch($query);
	if ($row!='') {
		$id_main = $row['id_main'];
		$shart =  ($timesend - $row['timesend']) <= $sms_time * 60;
		if ($shart) {
			$time_need = ($sms_time * 60) - ($timesend - $row['timesend']) ;
			return array(-1,"از آخرین ارسال کد فعال سازی حداقل $sms_time دقیقه باید گذشته باشد <BR> زمان باقی مانده [$time_need] ثانیه"); 
		}
		$update=true;
	} else {
		$update=false;
	}
	// نوع ارسال otp
	if ($type_otp=='register') {
		$Message = " کد ثبت نام: $MainActiveCode \r\n ". $row_setting['shop_name'];
		$sms_config = isset($row_sms[1]) ? $row_sms[1] : null;
	}
	if ($type_otp=='login') {
		$Message = " کد فعال سازی: $MainActiveCode \r\n ". $row_setting['shop_name'];
		$sms_config = isset($row_sms[1]) ? $row_sms[2] : null;
	}
	if ($type_otp=='recover') {
		$Message = " کد بازیابی: $MainActiveCode \r\n ". $row_setting['shop_name'];
		$sms_config = isset($row_sms[3]) ? $row_sms[3] : null;
	}
		
	if ($reg_type==0) {
		/*
		if ( ($row_setting['sms_pattern'] !='') and ($row_setting['sms_apikey']!='') and ($row_setting['sms_variable']!='') and  ($row_setting['smsnumber']!='') ) {
			list($ValueReturn, $MessageReturn, $smsdeliveri) = 
			SendSmsWithPattern($row_setting['sms_apikey'], $row_setting['sms_pattern'],$row_setting['smsnumber'],  $mobile, $row_setting['sms_variable'], $MainActiveCode );
		} else{
			list($ValueReturn,$MessageReturn,$smsdeliveri) = EnjineSendSms($mobile, $Message);
		}
		*/
		if (!empty($sms_config)) {
			list($ValueReturn, $MessageReturn)= SendPatternFromTable($mobile, array($MainActiveCode), $sms_config); 
		}else{
			$sms_config = isset($row_sms[0]) ? $row_sms[0] : null;
			list($ValueReturn, $MessageReturn)= SendSmsFromTable($mobile, $Message, $sms_config);

		}
		if ($ValueReturn<0) { return array(-1,"$MessageReturn"); }
		//------------------------------------------------------------
	}else{
//		$Message=" کد ثبت نام: $MainActiveCode ";
		$Message = nl2br($Message) ;
		$OkSendEmail = SendEmail('',$email,"ثبت نام در سایت $title",$Message,'',0);
		if (!$OkSendEmail) {
			return array(-1,"سیستم قادر به ارسال به ایمیل $email نمی باشد");
		}
	}
	$query_array2 = array(':mobile'=>$mobile, ':email'=>$email);		
	if ($update) {
		$SQL_QUERY="update `user_active_code` set timesend='$timesend', activecode='$MainActiveCode', contor = (contor+1), date_last_edit='$date_last_edit', ip_last_edit='$ip_last_edit' where id_main='$id_main'";
		$sql=pdo_query($SQL_QUERY,'',0);
	}else{
		$SQL_QUERY="insert into `user_active_code` (mobile, email, activecode, timesend, status, date_last_edit, ip_last_edit) values (:mobile, :email, '$MainActiveCode','$timesend', '0', '$date_last_edit', '$ip_last_edit')";
		$sql=pdo_query($SQL_QUERY,$query_array2,0);
	}
	if ($reg_type==0) {
		$tetr2 = " شماره $reg_value پیامک شد. ";
	} else {
		$tetr2 = " ایمیل $reg_value ارسال شد. ";
	}

	return array(1,"یک کد به " . $tetr2 . " لطفا کد را وارد نمایید ");

}

?> 

