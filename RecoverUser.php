<?php
//$ok_cookie  متغیری جهت کنترل لوگین صحیح کاربر
include('topmain.php');
//------------
if ($ok_cookie) {
	header("location:/");
	exit;
}

if (isset($_REQUEST['reg_type']))   $reg_type = intval($_REQUEST['reg_type']);else $reg_type = 0;
if (($reg_type>1) or ($reg_type<0) ) $reg_type=0;

//------------------------------------------------
$user_register = $row_setting['user_type_register'];
if ($user_register==2) $reg_type=0; // فقط موبایل
if ($user_register==3) $reg_type=1; // فقط ایمیل
//------------------------------------------------


if ($reg_type==0) {
	$title = 'بازیابی رمز عبور از طریق شماره تلفن همراه';	
	$rotetr = 'لطفا شماره تلفن همراه خود را وارد نمایید';
	$link2 = "<a class='LinkFilm1'  href='/recover-email/'>بازیابی رمز عبور از طریق ایمیل</a>";
	$link3 = "<a class='LinkFilm1'  href='/recover/'>شماره را اشتباه وارد کرده اید؟</a>";
	$input_tag = "<input dir='ltr' name='RecoverMobile' id='RecoverMobile' type='text' class='form-control' aria-label='Sizing example input'
						aria-describedby='inputGroup-sizing-default'  value='' placeholder='9*********' onkeydown='if(event.keyCode == 13){RecoverAccountNew(1);}' onkeypress='return isNormalNumber(event)' />";
}else { 
	$title = 'بازیابی رمز عبور از طریق ایمیل';	
	$rotetr = 'لطفا ایمیل خود را وارد نمایید';
	$link2 = "<a class='LinkFilm1'  href='/recover/'>بازیابی رمز عبور از طریق شماره تلفن همراه</a>";
	$link3 = "<a class='LinkFilm1'  href='/recover-email/'>ایمیل را اشتباه وارد کرده اید؟</a>";
	$input_tag = "<input dir='ltr' name='RecoverEmail' id='RecoverEmail' type='text' class='form-control' aria-label='Sizing example input' aria-describedby='inputGroup-sizing-default'  value='' placeholder='ایمیل' autocomplete='email' onkeydown='if(event.keyCode == 13){RecoverAccountNew(1);}' />";
}
if (($user_register==2) or ($user_register==3)) { $link2='';  } 

$input_tag	 .= "\r\n <input type='hidden' id='reg_type' name='reg_type' value='$reg_type'>";
$messageerror='';
include('top.php'); 
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
function RecoverAccountNew(idtype) {
	var reg_type = $("#reg_type").val();
	if (reg_type==0) {
		var Register = document.getElementById("RecoverMobile");
		var final_link = '/login/';
		var TetrField = 'شماره موبایل';
	}else{
		var Register = document.getElementById("RecoverEmail");
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
		 password1  = document.getElementById("PassWord1").value;
		 password2  = document.getElementById("PassWord2").value;
		 if ( (password1=='') || (password2=='') ) {
			 message('لطفا مقادیر اسم رمز را به صورت کامل وارد نمایید','error');
			 return;
		 }
 		 parameter.ActiveCode = ActiveCode;
 		 parameter.password1 = password1;
 		 parameter.password2 = password2;
		 document.getElementById("submit3").style.pointerEvents='none';	
		 
	}
	$.ajax({
		type: 'POST',
		url: '/data_ajax.php',
		data: { type:5, parameter},
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
</script>
<main>
	<div class="row col-12">
	<div class="container" style='background-color: white;  margin: 40px auto;  border-radius: 7px;  padding: 15px;'>
		<div class="tab-content" id="nav-tabContent">
			<div class="tab-pane fade show active p-0 m-0" id="nav-login" role="tabpanel" aria-labelledby="nav-login-tab">
				<!-- header title -->
				<div class="header-title"><div><span class='text-success' style='white-space: nowrap;'>فراموشی رمز عبور</span></div><div class="head-line"></div></div>
				<div id='panel1' >
					<div class="row d-flex justify-content-center">
						<div class="col-sm-12 col-md-6 my-2">
							<h6><?php echo $title; ?></h6>
							<div style='margin-top:10px;margin-bottom:20px;color:gray;'><?php echo $rotetr; ?> </div> 
							<?php echo $input_tag; ?> 
							<BR>
						</div>
						<div class="col-12 text-center">
						  <button id='submit1' class="btn btn-success w-50" type="button" onclick='RecoverAccountNew(1);'>دریافت رمز یکبار مصرف</button>
						</div>
						<div style='float:left;margin-top:50px;color:white;'><?php echo $link2; ?></div> 
					</div>
				</div>
				<div id='panel2' style="display:none;">
					<div class="row d-flex justify-content-center">
						<div class="col-sm-12 col-md-6 my-2">
							<h6>بازیابی رمز عبور</h6>
							<div id='tetr2' style='margin-top:10px;margin-bottom:20px;color:gray;'></div> 
							<input name='ActiveCode' id='ActiveCode' style=' direction: ltr; text-align: left;' class='form-control' placeholder='کد فعال سازی'  value='' onkeydown='if(event.keyCode == 13){RecoverAccountNew(2);}' onkeypress='return isNormalNumber(event)'>			
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
						  <button id='submit2' class="btn btn-success w-50" type="button" onclick='RecoverAccountNew(2);'>تایید</button>
						</div>
						<div style='text-align: center;margin-top:50px;color:white;'><?php echo $link3; ?></div> 
					</div>
				</div>
				<div id='panel3' style="display:none;">
					<div class="row d-flex justify-content-center">
						<div class="col-sm-12 col-md-6 my-2">
							<h6>بازیابی رمز عبور</h6>
							<div style='margin-top:10px;margin-bottom:20px;color:gray;'>
							لطفا اطلاعات زیر را تکمیل کنید <BR>
							رمز عبور باید حداقل 6 کاراکتر و ترکیبی از اعداد و حروف باشد
							</div> 
							
							<input name='PassWord1'  id='PassWord1' type='password' style='direction: ltr; text-align: left;' class='form-control' placeholder='رمز عبور'  value=''>
							<div style='margin-top:10px;margin-right:60px;color:gray;'>	
								
							</div> 
							<input name='PassWord2' id='PassWord2' type='password' style='direction: ltr; text-align: left;' class='form-control' placeholder='تکرار رمز عبور'  value=''>
							<BR>
						</div>
						<div class="col-12 text-center">
						  <button id='submit3' class="btn btn-success w-50" type="button" onclick='RecoverAccountNew(3);'>تایید</button>
						</div>
						<div style='text-align: center;margin-top:50px;color:white;'><?php echo $link3; ?></div> 
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
                                            <h2>حساب کاربری شما با موفقیت تغییر رمز یافت</h2>
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
			</div>
  		</div>
	</div>
</div>
</main>
<?php 
include('bottom.php');
?> 