<?php
require_once('topmain.php');
//------------
//$ok_cookie  متغیری جهت کنترل لوگین صحیح کاربر
if ($ok_cookie) {	header("location:/");	exit;}

if (isset($_REQUEST['reg_type']))   $reg_type = intval($_REQUEST['reg_type']);else $reg_type = 0;
if (($reg_type>1) or ($reg_type<0) ) $reg_type=0;
//------------------------------------------------
$user_register = $row_setting['user_type_register'];
if ($user_register==2) $reg_type=0; // فقط موبایل
if ($user_register==3) $reg_type=1; // فقط ایمیل
$user_type_login = $row_setting['user_type_login'];
$OkPass = false;		
$OkOtp	= false;
if ($user_type_login==1) $OkPass = true;
if ($user_type_login==2) $OkOtp  = true;
if ($user_type_login==3) { $OkPass = true;		$OkOtp  = true; }
//------------------------------------------------
//

if ($reg_type==0) {
	$title = 'ورود از طریق شماره تلفن همراه';	
	$rotetr = 'لطفا شماره تلفن همراه خود را وارد نمایید';
	$link2 = "<a class='LinkFilm1'  href='/login-email/'>ورود از طریق ایمیل</a>";
	$link3 = "<a class='LinkFilm1'  href='/recover/'>رمز عبور خود را فراموش کرده ام</a>";
	$link4 = "<a class='LinkFilm1'  href='/login/'>شماره موبایل خود را اشتباه وارده کرده ام</a>";
	$input_tag = "<input dir='ltr' name='LoginMobile' id='LoginMobile' type='text' class='form-control' aria-label='Sizing example input'
						aria-describedby='inputGroup-sizing-default'  value='' placeholder='9*********' onkeydown='if(event.keyCode == 13){LoginAccountNew();}' onkeypress='return isNormalNumber(event)' />";
}else { 
	$title = 'ورود از طریق ایمیل';	
	$rotetr = 'لطفا ایمیل خود را وارد نمایید';
	$link2 = "<a class='LinkFilm1'  href='/login/'>ورود از طریق شماره تلفن همراه</a>";
	$link3 = "<a class='LinkFilm1'  href='/recover-email/'>رمز عبور خود را فراموش کرده ام</a>";
	$link4 = "<a class='LinkFilm1'  href='/login-email/'>ایمیل خود را اشتباه وارده کرده ام</a>";
	$input_tag = "<input dir='ltr' name='LoginEmail' id='LoginEmail' type='text' class='form-control' aria-label='Sizing example input'						aria-describedby='inputGroup-sizing-default'  value='' placeholder='ایمیل' autocomplete='email' onkeydown='if(event.keyCode == 13){LoginAccountNew();}' />";
}
if (($user_register==2) or ($user_register==3)) { $link2='';  } 
$title_main = $title;
$input_tag	 .= "\r\n <input type='hidden' id='reg_type' name='reg_type' value='$reg_type'>";
$messageerror='';
//------------------------------------
if ($row_setting['google_client_id']!='') { 
	require_once 'oauth_config.php';
	$authUrl = build_auth_url();
	$authUrl = htmlspecialchars($authUrl);
}
include('top.php'); 
if ((isset($ErrorMsg2)) and ($ErrorMsg2!='')) { 	
	echo "<script>message('$ErrorMsg2', 'error'); </script>";
//	echo "<div class='row'></div>"; 	ShowMessage("$ErrorMsg2",0); 
}
?>
<style>
.circle {    position: relative;    width: 60px;    height: 60px;    margin: auto;}

#timeText {    position: absolute;    top: 50%;    left: 50%;    transform: translate(-50%, -50%);
    font-size: 12px;    font-weight: bold;}

#resendBtn {    display: inline-block;    margin-top: 20px;    text-decoration: none;    color: #999;
    pointer-events: none;    opacity: 0.5;}

#resendBtn.active {    color: #00aaff;    pointer-events: auto;    opacity: 1;    cursor: pointer;}
</style>

<script>
//--------------------------
function LoginAccountNew(id=0) {
	var reg_type = $("#reg_type").val();
	if (reg_type==0) {
		var LoginUser = document.getElementById("LoginMobile");
		var TetrField = 'شماره موبایل';
	}else{
		var LoginUser = document.getElementById("LoginEmail");
		var TetrField = 'ایمیل';
	}
	if (LoginUser.value=='') {
		message('لطفا مقدار ['+TetrField+'] را وارد نمایید', 'error'); 
		LoginUser.focus(); 
		return; 	
	}
	<?php if ($OkPass) { ?>
	if (id==0) {
		LoginPassword = '';
		LoginPassword = document.getElementById("LoginPassword");
		if (LoginPassword.value=='') { 
			message('لطفا مقدار [اسم رمز] را وارد نمایید', 'error'); 
			LoginPassword.focus(); 
			return; 	
		}
		document.getElementById("submit1").style.pointerEvents='none';	
		$.ajax({
			type: 'POST',
			url: '/data_ajax.php',
			data: { type:6, reg_type:reg_type, reg_value:LoginUser.value,reg_pass:LoginPassword.value, reg_no:'pass'  },
			dataType: 'json',
			success: function (data, status, xhr) {
				document.getElementById("submit1").style.pointerEvents='auto';	
				if (data[0]<0) { 
					message(data[1], 'error') 
				}
				if (data[0]==1) { 
					window.location= '/'; //final_link;
				}
			},
			error: function (jqXhr, textStatus, errorMessage) {alert(' textStatus = '+textStatus+' Error: ' + errorMessage);}
		});		
		return;
	}
	<?php } ?>
	<?php if ($OkOtp ) { ?>
	if (id==1) {
		document.getElementById("submit2").style.pointerEvents='none';	
		$.ajax({
			type: 'POST',
			url: '/data_ajax.php',
			data: { type:6, reg_type:reg_type, reg_value:LoginUser.value,reg_pass:'', reg_no:'otp' },
			dataType: 'json',
			success: function (data, status, xhr) {
				document.getElementById("submit2").style.pointerEvents='auto';	
				if (data[0]<0) { 
					message(data[1], 'error') 
				}
				if (data[0]==1) { 
					document.getElementById('panel1').style.display='none';
					document.getElementById('panel2').style.display='block';
					startOtpTimer();
				
				}
			},
			error: function (jqXhr, textStatus, errorMessage) {alert(' textStatus = '+textStatus+' Error: ' + errorMessage);}
		});		
		return;
	}
	if (id==2) {
		 active_code = document.getElementById("ActiveCode").value;
		 if (active_code=='') {
			 message('لطفا مقدار [کد فعال سازی] را وارد نمایید', 'error'); 
			 return;
		 }
		document.getElementById("submit3").style.pointerEvents='none';	
		$.ajax({
			type: 'POST',
			url: '/data_ajax.php',
			data: { type:6, reg_type:reg_type, reg_value:LoginUser.value,reg_pass:'', reg_no:'otp_verify', active_code:active_code  },
			dataType: 'json',
			success: function (data, status, xhr) {
				document.getElementById("submit3").style.pointerEvents='auto';	
				if (data[0]<0) { 
					message(data[1], 'error') 
				}
				if (data[0]==1) { 
					window.location= '/'; //final_link;
				
				}
			},
			error: function (jqXhr, textStatus, errorMessage) {alert(' textStatus = '+textStatus+' Error: ' + errorMessage);}
		});		
		return;
	}
	
	<?php } ?>


}
</script>
<script>
function startOtpTimer() {


	let totalTime = <?php echo $row_setting['sms_time_users'] * 60;?>; // ثانیه
	let timeLeft = totalTime;

	let circle = $("#progressCircle");
	let timeText = $("#timeText");
	let resendBtn = $("#resendBtn");

	let radius = 24;
	let circumference = 2 * Math.PI * radius;

	circle.css("stroke-dasharray", circumference);

	function updateCircle() {
		let percent = timeLeft / totalTime;
		let offset = circumference * (1 - percent);
		circle.css("stroke-dashoffset", offset);
	}

	function formatTime(seconds) {
		let m = Math.floor(seconds / 60);
		let s = seconds % 60;
		return (m < 10 ? "0"+m : m) + ":" + (s < 10 ? "0"+s : s);
	}

	function runTimer() {

		timeText.text(formatTime(timeLeft));
		updateCircle();

		timeLeft--;

		if (timeLeft < 0) {
			clearInterval(countdown);

			timeText.text("00:00");
			resendBtn.addClass("active");
		}
	}

	// 🔥 بخش localStorage (قبل از شروع تایمر)
	let expire = localStorage.getItem("otp_expire");

	if (expire) {
		let diff = Math.floor((expire - Date.now()) / 1000);
		if (diff > 0) {
			timeLeft = diff;
		}
	} else {
		localStorage.setItem("otp_expire", Date.now() + (totalTime * 1000));
	}

	let countdown = setInterval(runTimer, 1000);

	// کلیک روی ارسال مجدد
	resendBtn.on("click", function (e) {
		e.preventDefault();

		if (!resendBtn.hasClass("active")) return;

		resendBtn.removeClass("active");

		timeLeft = totalTime;

		// ذخیره زمان جدید
		localStorage.setItem("otp_expire", Date.now() + (totalTime * 1000));

		countdown = setInterval(runTimer, 1000);
		
		LoginAccountNew(1);	
	});
};
</script>

<main>
	<div class="row col-12">
	<div class="container" style='background-color: white;  margin: 40px auto;  border-radius: 7px;  padding: 15px;'>
		<div class="tab-content" id="nav-tabContent">
			<div class="tab-pane fade show active p-0 m-0" id="nav-login" role="tabpanel" aria-labelledby="nav-login-tab">
				<!-- header title -->
				<div class="header-title">
					<div><span class='text-success'>ورود</span></div>
					<div class="head-line"></div>
				</div>
				<form name='LoginForm' id = 'LoginForm'>
				<div id='panel1' >
					<div class="row d-flex justify-content-center">
						<div class="col-sm-12 col-md-6 my-2">
							<button class="btn btn-success" style='float:left' type="button" onclick='javascript:window.location="/register/"'>ثبت نام</button>
							<h6><?php echo $title; ?></h6>
							<div style='margin-top:10px;margin-bottom:20px;color:gray;'><?php echo $rotetr;?></div> 
							<?php echo $input_tag; ?> 
							<BR> 
							<?php if ($OkPass) {?>
							<input name='LoginPassword'  id='LoginPassword' type='password' class='form-control' aria-label='Sizing example input'						aria-describedby='inputGroup-sizing-default'  placeholder='رمز عبور'  value='' onkeydown='if(event.keyCode == 13){LoginAccountNew();}' >
							<?php } ?>
							<BR>
						</div>
						<div class="col-12 text-center">
							<div style='margin:auto;'>
								<?php if ($OkPass){?>
								<button id='submit1' class="btn btn-success w-50 mb-1" type="button" onclick='LoginAccountNew();'> ورود</button> 
								<?php } ?>
								<?php if ($OkOtp){?>
								<button id='submit2' class="btn btn-primary w-50 mt-1" type="button" onclick='LoginAccountNew(1);'> ارسال رمز یکبار مصرف</button> 
								<?php } ?>
							</div>
							<div style='margin:auto;margin-top:20px;color:white;'><?php echo $link3; ?> </div> 
						</div>
						<?php 
						if (($row_setting['google_client_id']!='') and ($user_register!=2) ) { 
						?>
						<div class="col-12 text-center mt-3">
							<div id="google-signin-container" class='btn btn-primary' style="width: 50%;margin:auto;">
							<a href="<?php echo $authUrl; ?>" style='color:white;'  >ورود با حساب گوگل</a>
							</div>

						</div>

						<?php } ?>
						<div style="clear:both;"></div>
						<div class='col-12' style='float:left;margin-top:50px;color:white;'>	
							<?php echo $link2; ?> 
						</div> 
						<div id='ShowError1' class="col-sm-12 col-md-8 my-8" style='margin:60px;padding:10px;border-radius:8px;background-color: #D95C5C;visibility:hidden;text-align:center;'>
					</div>
				</div>
			</div>
			<?php if ($OkOtp){?>
			<div id='panel2' style="display:none;">
				<div class="row d-flex justify-content-center">
					<div class="col-sm-12 col-md-6 my-2">
						<h6>کد فعال سازی</h6>
						<div id='tetr2' style='margin-top:10px;margin-bottom:20px;color:gray;'></div> 
						<input name='ActiveCode' id='ActiveCode' style=' direction: ltr; text-align: left;'  class='form-control' placeholder='کد فعال سازی'  value='' onkeydown='if(event.keyCode == 13){LoginAccountNew(2);}' onkeypress='return isNormalNumber(event)'>			
						
						<div style='text-align: center;margin:10px;padding:10px;' >
							<div class="circle">
								<svg width="60" height="60">
									<circle cx="30" cy="30" r="24" stroke="#eee" stroke-width="6" fill="none"/>
									<circle id="progressCircle" cx="30" cy="30" r="24"
											stroke="#00aaff" stroke-width="6" fill="none"
											stroke-linecap="round"
											transform="rotate(-90 30 30)"/>
								</svg>
								<div id="timeText"><?php s_d($row_setting['sms_time_users'],2).":00";?></div>
							</div>
							<a href="#" id="resendBtn" class="disabled">ارسال مجدد کد</a>
						</div>
					</div>
					<div class="col-12 text-center">
					  <button id='submit3' class="btn btn-success w-50" type="button" onclick='LoginAccountNew(2);'>تایید</button>
					</div>
					<div style='text-align: center;margin-top:50px;color:white;'><?php echo $link4; ?></div> 
				</div>
			</div>	
			<?php } ?>
			</form>
  		</div>
	</div>
</div>
</main>
<?php 
include('bottom.php');
?> 