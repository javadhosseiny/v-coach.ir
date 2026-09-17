<?php
include('topmain.php');
//------------
if (!$ok_cookie) {	header("location:/");	exit;}
$title_main = 'تغییر اسم رمز';
$OkSaveRec=false;
$Error = '';
//--------------------------------
if ( (isset($_POST['submit_form'])) and (intval($_POST['submit_form'])==1)) {
	$bankname = 'user_account';
	$ReferPage  = $_SERVER['HTTP_REFERER'];		
	$HostScript = '://'.$_SERVER["HTTP_HOST"];
	$ok_refer = strpos($ReferPage, $HostScript);	
	if ($ok_refer) { 
		$oldpassword= trim($_POST['oldpassword']);
		$password1	= trim($_POST['password1']);
		$password2 	= trim($_POST['password2']);
		if ( $oldpassword=='' )  $Error .= "رمز سابق خالی است";
		if ( ($password1=='') or ($password2=='') ) $Error .= "اسم رمز ها صحیح نمی باشد";
		if ($password1!=$password2) 	$Error .= "اسم رمز و تکرار آن باهم برابر نمی باشد<BR> ";
		if ($oldpassword==$password1) 	$Error .= "اسم رمز سابق و جدید باهم برابر می باشد، لطفا تغییر دهید<BR> ";
		if ($oldpassword!='') {
			$password= md5($oldpassword);
			$query=pdo_query("select * from `user_account` where  id_main='$usernameid' and password='$password' and active=1 and isdeleted=0  ",'',0);
			$Count = pdo_rowcount($query);
			$row=pdo_fetch($query);
			if ( $Count == 0 ) { $Error .= "رمز سابق صحیح نمی باشد<BR>";  	} 
			
		}

		if ($Error =='') { 			
			$password 	= md5($password1);
			$sql=pdo_query("update `$bankname`  set password='$password', ip_last_edit='$ip_last_edit', user_last_edit='$user_last_edit', date_last_edit='$date_last_edit' 	where id_main='$usernameid' ",'',0);
			$OkSaveRec=true;
		}		
		
	}
}
include('top.php'); 
if ($Error !='') { 			
	echo "
	<div class='row' style='margin:auto; display: inline-block; width:100%;'>
	<div class='col-12 mt-4 bg-danger rounded text-center w-50 p-2 text-white' style='margin:auto;'>$Error
	</div></div>";
}	
if ($OkSaveRec) 	{
	echo "<div class='row'></div>"; 
	ShowMessage('عملیات تغییر رمز با موفقیت انجام شد',0,'bg-success'); 
}

?>
<script>

//--------------------------
function check() {
	if ($('#oldpassword').val()=='') {
		message('لطفا رمز سابق خود را وارد نمایید','error',0,'oldpassword');
		return false;
	}
	if ($('#password1').val()=='') {
		message('لطفا رمز اول خود را وارد نمایید','error',0,'password1');
		return false;
	}
	if ($('#password2').val()=='') {
		message('لطفا رمز دوم خود را وارد نمایید','error',0,'password2');
		return false;
	}
	if ($('#password1').val()!=$('#password2').val()) {
		message('عبارات رمز اول و تکرار آن باهم برابر نیستند','error',0,'password1');
		return false;
	}
	if ($('#oldpassword').val()==$('#password1').val()) {
		message('رمز سابق و جدید یکسان است، لطفا رمز جدید را تغییر دهید','error',0,'password1');
		return false;
	}
	return true;
}
</script>
<main>
	<div class="row col-9" style='margin:auto;'>
	<div class="container" style='background-color: white;  margin: 40px auto;  border-radius: 7px;  padding: 15px;'>
		<div class="tab-content" id="nav-tabContent">
			<div class="tab-pane fade show active p-0 m-0" id="nav-login" role="tabpanel" aria-labelledby="nav-login-tab">
				<!-- header title -->
				<div class="header-title">
					<div><span class='text-success' style='white-space: nowrap;'>تغییر اسم رمز</span></div>
					<div class="head-line"></div>
				</div>
			<form action="" enctype="multipart/form-data" id="formEdit" method="post"  name="formEdit" onsubmit="return check();" >
			<input type='hidden' name='submit_form' value='1'>
			<div class="row">
				<div class="col-6 col-md-3"></div>
				<div class="col-12 col-md-6">
					<div class="form-group">
						<label class="form-label">اسم رمز قبلی</label>
						<input class="form-control" value="" id="oldpassword" name="oldpassword" type="password" style='direction:ltr;'>
					</div>
				</div>
				<div class="col-6 col-md-3"></div>
				<div class="col-6 col-md-3"></div>
				<div class="col-12 col-md-6">
					<div class="form-group">
						<label class="form-label">اسم رمز</label>
						<input class="form-control" value="" id="password1" name="password1" type="password" style='direction:ltr;'>
					</div>
				</div>
				<div class="col-6 col-md-3"></div>
				<div class="col-6 col-md-3"></div>
				<div class="col-12 col-md-6">
					<div class="form-group">
						<label class="form-label">تکرار اسم رمز</label>
						<input class="form-control" value=""  id="password2" name="password2"  type="password" style='direction:ltr;'>
					</div>
				</div>
				<div class="col-12 btn-list text-left">
					<button type="submit" class="btn btn-success">
						<i class="fa fa-check ml-1"></i>  ذخیره  
					</button>
				</div>
			</div>
			</form>
			</div>
  		</div>
	</div>
</div>
</main>
<?php 
include('bottom.php');
?> 