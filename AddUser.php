<?php
//$ok_cookie  متغیری جهت کنترل لوگین صحیح کاربر
include('topmain.php');
//------------
if ($ok_cookie) {	header("location:/");	exit;}
$rand1 = rand();	$rand2 = rand();	$rand3 = rand(); $rand4 = rand(); $rand5 = rand();

if (isset($_REQUEST['reg_type']))   $reg_type = intval($_REQUEST['reg_type']);				else $reg_type = 0;
if (($reg_type>1) or ($reg_type<0) ) $reg_type=0;
//------------------------------------------------
$user_register = $row_setting['user_type_register'];
if ($user_register==2) $reg_type=0; // فقط موبایل
if ($user_register==3) $reg_type=1; // فقط ایمیل
//------------------------------------------------


if ($reg_type==0) {
	$title = 'ثبت نام با شماره تلفن همراه';	
	$rotetr = 'لطفا شماره تلفن همراه خود را وارد نمایید';
	$link2 = "<a class='LinkFilm1'  href='/register-email/'>ثبت نام با ایمیل</a>";
	$link3 = "<a class='LinkFilm1'  href='/register/'>شماره موبایل را اشتباه وارد کرده اید؟</a>";
	$input_tag = "<input name='RegisterMobile' id='RegisterMobile' class='form-control' style='direction: ltr; text-align: left;'  placeholder='9*********'  value='' onkeydown='if(event.keyCode == 13){RegisterAccountNew(1);}' onkeypress='return isNormalNumber(event)'>";
}else { 
	$title = 'ثبت نام با ایمیل';	
	$rotetr = 'لطفا ایمیل خود را وارد نمایید';
	$link2 = "<a class='LinkFilm1'  href='/register'>ثبت نام با شماره تلفن همراه</a>";
	$link3 = "<a class='LinkFilm1'  href='/register-email/'>ایمیل را اشتباه وارد کرده اید؟</a>";
	$input_tag = "<input name='RegisterEmail' id='RegisterEmail' class='form-control' style='direction: ltr; text-align: left;' type='email' placeholder='ایمیل' autocomplete='email' value='' onkeydown='if(event.keyCode == 13){RegisterAccountNew(1);}'>";
}
if (($user_register==2) or ($user_register==3)) { $link2='';  } 

$input_tag	 .= "\r\n <input type='hidden' id='reg_type' name='reg_type' value='$reg_type'>";
$messageerror='';
$title_main = $title;
if (($row_setting['google_client_id']!='') and ($user_register!=2) ) { 
	require_once 'oauth_config.php';
	$authUrl = build_auth_url();
	$authUrl = htmlspecialchars($authUrl);
}
include('top.php'); 
//---------------------------------

//------------------------------------
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
function RegisterAccountNew(idtype) {
	var reg_type = $("#reg_type").val();
	if (reg_type==0) {
		var Register = document.getElementById("RegisterMobile");
		var final_link = '/login/';
		var TetrField = 'شماره موبایل';
	}else{
		var Register = document.getElementById("RegisterEmail");
		var final_link = '/login-email/';
		var TetrField = 'ایمیل';
	}
	RegisterValue = Register.value;
	if (RegisterValue=='') { 
		message('لطفا مقدار ['+TetrField+'] را وارد نمایید', 'error'); 
		Register.focus(); 
		return; 	
	}
	var parameter = {
		idtype: idtype,
		reg_type: reg_type,
		reg_value: RegisterValue,
	};
	var ActiveCode =0;
	if (idtype==1) {
		document.getElementById("submit1").style.pointerEvents='none';	
	}
	if (idtype==2) {
		 ActiveCode = document.getElementById("ActiveCode").value;
		 if (ActiveCode=='') {
			 message('لطفا مقدار [کد فعال سازی] را وارد نمایید', 'error'); 
			 return;
		 }
		 parameter.ActiveCode = ActiveCode;
		 document.getElementById("submit2").style.pointerEvents='none';	
	}
	if (idtype==3) {
		 ActiveCode = document.getElementById("ActiveCode").value;
		 family 	= document.getElementById("NameFamily").value;
		 password1  = document.getElementById("PassWord1").value;
		 gender     = $('input[name="Gender"]:checked').val();
		 if ((family=='') || (password1=='') || (gender === undefined)  ) {
		     message('لطفا مقادیر نام، اسم رمز و جنسیت را به صورت کامل وارد نمایید', 'error');
			 return;
		 }
 		 parameter.ActiveCode = ActiveCode;
 		 parameter.family = family;
 		 parameter.password1 = password1;
		 parameter.gender = gender;
		 document.getElementById("submit3").style.pointerEvents='none';	
		 
	}
	$.ajax({
		type: 'POST',
		url: '/data_ajax.php',
		data: { type:4, parameter},
		dataType: 'json',
		success: function (data, status, xhr) {
			document.getElementById("submit1").style.pointerEvents='auto';	
			document.getElementById("submit2").style.pointerEvents='auto';	
			document.getElementById("submit3").style.pointerEvents='auto';	
			if (data[0]<0) { 
				message(data[1], 'error') 
			}
			if (data[0]==1) { 
				NewContent = data[1];
				document.getElementById('ShowError1').style.visibility='hidden';
				document.getElementById('panel1').style.display='none';
				document.getElementById('panel2').style.display='block';
				document.getElementById('panel3').style.display='none';
				document.getElementById('panel4').style.display='none';
				document.getElementById('tetr2').innerHTML = NewContent;
				startOtpTimer();
			}
			if (data[0]==2) { 
				document.getElementById('panel1').style.display='none';
				document.getElementById('panel2').style.display='none';
				document.getElementById('panel3').style.display='block';
				document.getElementById('panel4').style.display='none';
			}
			if (data[0]==3) { 
				document.getElementById('panel1').style.display='none';
				document.getElementById('panel2').style.display='none';
				document.getElementById('panel3').style.display='none';
				document.getElementById('panel4').style.display='block';
//				window.location= '/'; //final_link;
			}
		},
		error: function (jqXhr, textStatus, errorMessage) {alert(' textStatus = '+textStatus+' Error: ' + errorMessage);}
	});		
}
</script>
<script>
function startOtpTimer() {


	let totalTime = <?php echo $row_setting['sms_time_users'] * 60;?>; // ثانیه
	let timeLeft = totalTime;

	let $circle = $("#progressCircle");
	let $timeText = $("#timeText");
	let $resendBtn = $("#resendBtn");

	let radius = 24;
	let circumference = 2 * Math.PI * radius;

	$circle.css("stroke-dasharray", circumference);

	function updateCircle() {
		let percent = timeLeft / totalTime;
		let offset = circumference * (1 - percent);
		$circle.css("stroke-dashoffset", offset);
	}

	function formatTime(seconds) {
		let m = Math.floor(seconds / 60);
		let s = seconds % 60;
		return (m < 10 ? "0"+m : m) + ":" + (s < 10 ? "0"+s : s);
	}

	function runTimer() {

		$timeText.text(formatTime(timeLeft));
		updateCircle();

		timeLeft--;

		if (timeLeft < 0) {
			clearInterval(countdown);

			$timeText.text("00:00");
			$resendBtn.addClass("active");
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
	$resendBtn.on("click", function (e) {
		e.preventDefault();

		if (!$resendBtn.hasClass("active")) return;

		$resendBtn.removeClass("active");

		timeLeft = totalTime;

		// ذخیره زمان جدید
		localStorage.setItem("otp_expire", Date.now() + (totalTime * 1000));

		countdown = setInterval(runTimer, 1000);
		
		RegisterAccountNew(1);	
	});
};

function togglePassword() {
    var password = document.getElementById('PassWord1');
    var eye = document.querySelector('#passwordEye i');

    if (password.type === 'password') {
        password.type = 'text';

        eye.classList.remove('fa-eye');
        eye.classList.add('fa-eye-slash');
    } else {
        password.type = 'password';

        eye.classList.remove('fa-eye-slash');
        eye.classList.add('fa-eye');
    }
}

</script>
<main>
	<div class="row col-12">
	<div class="container" style='background-color: white;  margin: 40px auto;  border-radius: 7px;  padding: 15px;'>
		<div class="tab-content" id="nav-tabContent">
			<div class="tab-pane fade show active p-0 m-0" id="nav-login" role="tabpanel" aria-labelledby="nav-login-tab">
			
				<!-- header title -->
				<div class="header-title">
					<div><span class='text-success' style='white-space: nowrap;'>ثبت نام</span></div>
					<div class="head-line"></div>
				</div>
				<div id='panel1' >
					<div class="row d-flex justify-content-center">
						<div class="col-sm-12 col-md-6 my-2">
							<button class="btn btn-success" style='float:left' type="button" onclick='javascript:window.location="/login/"'>ورود</button>
						
							<h6><?php echo $title; ?></h6>
							<div style='margin-top:10px;margin-bottom:20px;color:gray;'><?php echo $rotetr; ?> </div> 
							<?php echo $input_tag; ?> 
							<BR>
						</div>
						<div class="col-12 text-center">
						  <button id='submit1' class="btn btn-success w-50" type="button" onclick='RegisterAccountNew(1);'>ثبت نام</button>
						</div>
						<div style='float:left;margin-top:50px;color:white;'><?php echo $link2; ?></div> 
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
				</div>
				<div id='panel2' style="display:none;">
					<div class="row d-flex justify-content-center">
						<div class="col-sm-12 col-md-6 my-2">
							<h6>کد فعال سازی</h6>
							<div id='tetr2' style='margin-top:10px;margin-bottom:20px;color:gray;'></div> 
							<input name='ActiveCode' id='ActiveCode' style=' direction: ltr; text-align: left;'  class='form-control' placeholder='کد فعال سازی'  value='' onkeydown='if(event.keyCode == 13){RegisterAccountNew(2);}' onkeypress='return isNormalNumber(event)'>			
							
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

								<!-- <span style='cursor:pointer;' onclick='RegisterAccountNew(1);'>دریافت مجدد کد</span>
								-->
							</div>
							
						</div>
						<div class="col-12 text-center">
						  <button id='submit2' class="btn btn-success w-50" type="button" onclick='RegisterAccountNew(2);'>تایید</button>
						</div>
						<div style='text-align: center;margin-top:50px;color:white;'><?php echo $link3; ?></div> 
					</div>
				</div>
				<div id='panel3' style="display:none;">
					<div class="row d-flex justify-content-center">
						<div class="col-sm-12 col-md-6 my-2">
							<h6>ثبت اطلاعات تکمیلی</h6>
							<div style='margin-top:10px;margin-bottom:20px;color:gray;'>
							لطفا اطلاعات زیر را تکمیل کنید 
							</div> 
							 <input name="NameFamily" id="NameFamily" class="form-control mb-2" placeholder="نام و نام خانوادگی" value="">
							 <div style="position:relative; margin-bottom:10px;">
								<input name="PassWord1" id="PassWord1" type="password"
								   style="direction:ltr; text-align:left; padding-right:45px;" class="form-control" 
								   placeholder="رمز عبور" value="">
								<span onclick="togglePassword()"
										  id="passwordEye"
										  style="
											  position:absolute;
											  right:12px;
											  top:50%;
											  transform:translateY(-50%);
											  cursor:pointer;
											  color:#888;
											  font-size:16px;
											  z-index:10;
										  ">
									<i class="fas fa-eye"></i>
								</span>
							</div>
							<div style="margin-top:5px;margin-bottom:8px;color:#666;">
								جنسیت
							</div>
							<div class="mb-2">
								<label style="margin-left:20px; cursor:pointer;">
									<input type="radio" name="Gender" value="1">
									مرد
								</label>
								<label style="cursor:pointer;">
									<input type="radio" name="Gender" value="2">
									زن
								</label>
							</div>
							<BR>
						</div>
						<div class="col-sm-12 text-center">
						  <button id='submit3' class="btn btn-success w-50" type="button" onclick='RegisterAccountNew(3);'>تکمیل ثبت نام</button>
						</div>
						<div style='text-align: center;margin-top:50px;'>		
							با زدن کلید تکمیل ثبت نام، <a class='LinkFilm1' target=_blank  href='/conditions/'>شرایط و قوانین سایت</a> را می پذیرم
						</div> 
					</div>
				</div>
				<div id='panel4' style="display:none;">
					<div class="row d-flex justify-content-center">
						<div class="col-sm-12 col-md-6 my-2">
                        <div class="account-box" style='  text-align: center;  height: auto;'>
							<img src="<?php echo $logofile;?>" alt="<?php echo $title_main;?>" style='width:150px;' />
                            <div class="Login-to-account mt-4">
                                <div class="account-box-content">
                                    <h4 class="mb-2">خوش آمدید</h4>
                                    <form action="#" class="form-account text-center">
                                        <div class="user-account-welcome">
                                            <img src="/assets/images/man.png">
                                        </div>
                                        <div class="made-account">
                                            <h2>حساب کاربری شما با موفقیت ساخته شد</h2>
                                            <p>اکنون می‌توانید به صفحه‌ای که در آن بودید بازگردید و یا با تکمیل اطلاعات حساب کاربری
                                            خود به کلیه امکانات و
                                            سرویس‌های <?php echo $row_setting['shop_name'];?> و سرویس‌های وابسته به آن دسترسی داشته باشید</p>
                                        </div>
                                        <div class="form-row-account">
                                            <a href='/profile/' class="btn btn-primary btn-login">تکمیل حساب کاربری</a>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
						</div>
					</div>
				</div>
				
				<div style="clear:both;"></div>
				<div id='ShowError1' class="mt-4 mb-4 col-sm-12 col-md-8 my-8 mx-auto" style='padding:10px;border-radius:8px;background-color: #D95C5C;visibility:hidden;text-align:center;'></div>
				
			</div>
  		</div>
	</div>
</div>
</main>
<?php 
include('bottom.php');
?> 