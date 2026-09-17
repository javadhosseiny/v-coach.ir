<?php
include('topmain.php');
$title_main='ارتباط با ما';
$canonical  = "$canonical/contactus/";
include('top.php');
$shop_map = '';
if ($row_setting['shop_map']=='') {
	if ($row_setting['gps_shop']!='') {
		list($lang,$long) = explode(',',$row_setting['gps_shop']);
		$shop_map = "<iframe title='map-iframe' src='https://neshan.org/maps/iframe/places/091e71f25d22ae4856d2149efc639b66#$lang-$long-18z-0p/$lang/$long' width='100%' height='450' allowFullScreen loading='lazy' ></iframe>";
	}
}
?>
<script>
function okcheck() {
	/*
	if ($('#comment_name').val()=='') {
		message('لطفا نام و نام خانوادگی خود را  وارد نمایید','error',0,'comment_name');
		return false;
	}
	if ( ($('#comment_mail').val()=='') && ($('#comment_mobile').val()=='') ) {
		message('لطفا ایمیل و یا شماره موبایل خود را وارد نمایید','error',0,'comment_mail');
		return false;
	}
	if ( ($('#comment_mail').val()!='') ) {
		if (checkEmail($("#comment_mail").val())===false) {
			message('لطفا ایمیل خود را به درستی وارد نمایید','error',0,'comment_mail');
			return false;
		}
	}
	*/
	if ($('#comment_subject').val()=='') {
		message('لطفا موضوع خود را وارد نمایید','error',0,'comment_subject');
		return false;
	}
	if ($('#comment_message').val()=='') {
		message('لطفا متن پیام خود را وارد نمایید','error',0,'comment_message');
		return false;
	}
	<?php if ($row_setting['ok_capcha_email']==1)  { ?>
	if ($('#sec_code').val()=='') {
		message('لطفا کد کپچا خود را وارد نمایید','error',0,'sec_code');
		return false;
	}
	<?php } ?>
	<?php 
	if (($row_setting['ok_capcha_email'] == 3) && !empty($row_setting['google_sitekey']) && !empty($row_setting['google_secretkey'])) {	
	   ?>
// دریافت توکن کپچا پیش از ارسال Ajax
    if (typeof grecaptcha !== 'undefined') {
        grecaptcha.ready(function() {
            grecaptcha.execute('<?php echo $row_setting['google_sitekey'];?>', {action: 'submit'})
            .then(function(token) {
                // ست کردن توکن درون اینپوت مخفی
                $('#recaptchaToken').val(token);

                // ارسال Ajax پس از دریافت موفق توکن
                sendAjaxForm();
            })
            .catch(function(error) {
                console.error('خطای کپچا:', error);
                message('خطا در اعتبارسنجی کپچا. لطفاً مجدداً تلاش کنید.', 'error');
            });
        });
    } else {
        message('کپچا هنوز بارگذاری نشده است. لطفاً چند لحظه صبر کنید.', 'error');
    }
	
	<?php } else {  ?>
	sendAjaxForm();
	<?php  } ?>
	
	return false;
}
function sendAjaxForm() {
    $.ajax({
        type: 'POST',
        url: '/data_ajax.php',
        data: $("#myform").serialize(), // اکنون recaptchaToken هم همراه دیتا ارسال می‌شود
        dataType: 'json',
        success: function (data, status, xhr) {
            if (data[0] < 0) { 
                message(data[1], 'error'); 
            }
            if (data[0] == 1) { 
                var new_message = data[1];
                message(data[1], 'success'); 
                new_message = "<div class='text-center bg-success text-white rounded p-5 m-5'><BR>" + new_message + "<BR><BR></div>";
                $("#main_form").html(new_message);
            }
        },
        error: function (jqXhr, textStatus, errorMessage) {
            alert('textStatus = ' + textStatus + ' Error: ' + errorMessage);
        }
    });
}
</script>

<div class="container-main">
	<div class="col-12">
		<div class="contact-us">
			<div class="contact-us-section" style='border:unset;'>
			<div class="box-title mb-3" style='display:block;'><?php echo $title_main;?></div>
			<div class="row">
				<div class="col-sm-12 col-md-4 d-flex align-items-center justify-content-center flex-column mb-3">
					<img src="<?php echo $logofile;?>" alt="<?php echo $title_main;?>" style='max-width:400px; max-height:400px;' />
					<h6>
					<?php echo $row_setting['shop_addres'];?><BR><BR>
					شماره تماس: <?php echo $row_setting['shop_phone'];?>
					</h6>
				</div>
				<div class="col-sm-12 col-md-8">
					<form id='myform' name='myform' method='post' action=''> 
						<input type='hidden' name='type' value='1' >
						<div class="row" id='main_form'>
							<div class="col-12 col-sm-12 col-md-6 my-2">
								<h6>نام و نام خانوادگی</h6>
								<input  name="comment_name" id="comment_name"  dir=rtl type="text" class='form-control'  value="">
							</div>
							<div class="col-12 col-sm-12 col-md-6 my-2">
								<h6>ایمیل</h6>
								<input  name="comment_mail" id="comment_mail" dir="ltr" class='form-control' type="text" value="">
							</div>
							<div class="col-12 col-sm-12 col-md-6 my-2">
								<h6>موبایل</h6>
								<input  name="comment_mobile" id="comment_mobile" dir="ltr" class='form-control' type="text" value="">
							</div>
							<div class="col-12 col-sm-12 col-md-6 my-2">
								<h6>موضوع<span class="text-danger">*</span></h6>
								<input  name="comment_subject" id="comment_subject" dir=rtl class='form-control'  type="text">
							</div>
							<div class="col-sm-12 col-md-12 my-2">
								<h6>متن <span class="text-danger">*</span></h6>
								<textarea name="comment_message"  id="comment_message" class="form-control"placeholder="متن مورد نظر شما ..." rows="6" dir=rtl></textarea>
							</div>
							<?php 
							echo GetCapcah();
							?>
							<div class="col-xs-12 col-sm-12 col-md-12 col-lg-3">
								<h6 style="color: transparent"></h6>
								<button class="btn btn-success w-100"  type="submit" onclick="return okcheck();">ارسال</button>
								<input type="Hidden" name="submitok" value = "ok" >
							</div>
						</div>
					</form>
				</div>
			</div>
			<div class="row mt-5 mb-5"><div class='col-12'><?php echo $shop_map;?></div></div>
			<div class="contact-us-row m-3"><?php echo $row_setting['contactus'];?></div>
			
			</div>
		</div>
	</div>
</div>
<?php
include('bottom.php');

//-----------------------------------------
function GetCapcah() {
	global $row_setting;
	$capcha=$row_setting['ok_capcha_email'];
	if ($capcha>1) {
		if ( ($row_setting['google_sitekey']=='') or ($row_setting['google_secretkey']=='')) {
			$capcha=1;
		}	
	}

	
	if ($capcha==0) { 	return '';}
	if ($capcha==1) { 	
		require_once "includee/encode.php";
		$strings = '0123456789';	$i = 0;	$characters = 5;	
		$i = 0;	$characters = 5;	
		$verify_string = '';
		while ($i < $characters){ 
			$verify_string .= substr($strings, mt_rand(0, strlen($strings)-1), 1);	$i++;
		} 
		
		$verify_string_hash = md5($verify_string);
		$key = md5(rand(0,999));
		$encid = urlencode(md5_encrypt($verify_string, $key));
		$captcha_image = "<img class='secimg' style='height:40px;' src='/CodeGenerator/?id=$encid&key=$key'>";
		
		$CapchaString = "
		<div class='col-12'>
			<label class='col-form-label'>
			کد کپچا:  <span class='text-danger'> * </span><span class='text-success'> من ربات نیستم</span> 
			</label> <BR>
		$captcha_image
		<input type='Hidden' id='CapchaSecCode2' name='CapchaSecCode2' value='$verify_string_hash'>
		<input type='text' id='CapchaSecCode1'  name='CapchaSecCode1' class='form-control' dir='ltr' size='6' style='width:70px;display: unset;margin-bottom:20px;' >
		<script src='/vendor/js/md5.js'></script>
		</div>
		";
	}
	if ($capcha==2) { 	
		$sitekey   = $row_setting['google_sitekey'];
		$secretkey = $row_setting['google_secretkey'];
		if (($sitekey=='') or ($secretkey=='') ) return '';
		$CapchaString = "
		<script src='https://www.google.com/recaptcha/api.js?hl=fa'></script>
		<div class='col-12  mt-2'>
			<div class='g-recaptcha' id='rcaptcha' data-sitekey='$sitekey'></div>				
		</div>
		";
	}	
	if ($capcha==3) { 	
		$sitekey   = $row_setting['google_sitekey'];
		$secretkey = $row_setting['google_secretkey'];
		if (($sitekey=='') or ($secretkey=='') ) return '';
		$CapchaString = "
		<script src = 'https://www.google.com/recaptcha/api.js?render=$sitekey&hl=fa'></script>
		<input type='hidden' name='recaptchaToken' id='recaptchaToken' value=''>
		";
	}	

	return $CapchaString;
}

?>

			
				

