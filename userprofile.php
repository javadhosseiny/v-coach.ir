<?php
include('topmain.php');
$title_main = 'ویرایش پروفایل';
if (isset($_REQUEST['gmail']))   $gmail = ($_REQUEST['gmail']);else $gmail = '';
include('top.php'); 
//------------
if (!$ok_cookie) {	
	echo "
	<div class='row' style='margin:auto; display: inline-block; width:100%;'>
	<div class='col-12 mt-4 mb-4 bg-danger rounded text-center w-50 p-2 text-white' style='margin:auto;'>
	جهت ویرایش پروفایل ابتدا در سایت ثبت نام کرده و یا وارد اکانت کاربری خود شوید <BR> <a href='/login/'>لینک ورود</a>

	</div></div>";
	include('bottom.php');
	exit;
	
//	header("location:/");	exit;
}
$active_mail_phone = $row_user['active_mail_phone']; // 1 --> mobile --- 2--> email
$maxsize_image= $row_setting['maxsizeimage'];
if ($row_setting['show_ostan_city']==0) {$display_city = 'd-none';} else {$display_city='';}
//--------------------------------
if (isset($_POST['submit_form'])) {
	$bankname = 'user_account';
	$ReferPage  = $_SERVER['HTTP_REFERER'];		
	$HostScript = '://'.$_SERVER["HTTP_HOST"];
	$ok_refer = strpos($ReferPage, $HostScript);	
	if ($ok_refer) { 
		$id_main_select=" and id_main != '$usernameid' "; 
		$name       = trim($_POST['name']);
		$email    	= trim($_POST['email']);
		$mobile    	= trim($_POST['mobile']);
		$phone    	= trim($_POST['phone']);
		$user_photo	= trim($_POST['user_photo']);
		$birthday_day 	= s_d(intval($_POST['birthday_day']),2);
		$birthday_month = s_d(intval($_POST['birthday_month']),2);
		$birthday_year 	= intval($_POST['birthday_year']);
		if ( empty($birthday_day) || $birthday_day == '00' || 
     		empty($birthday_month) || $birthday_month == '00' || 
     		$birthday_year == 0 ) {		
			$birthday=''; 
		} else {
			$birthday = "$birthday_year/$birthday_month/$birthday_day";
		}
		$ErrorMsg = '';
		if ($name=='') 				 $ErrorMsg .= "تعیین نام و نام خانوادگی الزامی است<BR> ";   
		if (!validateEmail($email,0))$ErrorMsg .= "پست الکترونیکی صحیح نمی باشد<BR> ";   
		
		$id_ostan	= intval($_POST['id_ostan']);
		$id_city	= intval($_POST['id_city']);
/*		
		if ($id_ostan==0) 			 $ErrorMsg .= "تعیین استان الزامی است<BR> ";   
		if ($id_city==0) 			 $ErrorMsg .= "تعیین شهر الزامی است<BR> ";   
		$addres    	= trim($_POST['addres']);		$zipcode    = trim($_POST['zipcode']);
		if ($addres=='') 			 $ErrorMsg .= "تعیین آدرس الزامی است<BR> ";   
		if ($zipcode=='') 			 $ErrorMsg .= "تعیین کدپستی الزامی است<BR> ";   
*/		
		if ($mobile!='') { // نام کاربری تکراری بود
			$sql_query = array(':mobile'=>$mobile);
			$QueryUniq = pdo_query("select count(*) from `$bankname` where isdeleted=0 and mobile=:mobile $id_main_select ",$sql_query,0); 
			$Count=pdo_count($QueryUniq);
			if ( $Count != 0 ) { 
				$ErrorMsg .= "از این شماره موبایل[$mobile] قبلا استفاده شده است، لطفا تغییر دهید<BR>";  
			} 
		}
		if ($email!='') { // نام کاربری تکراری بود
			$sql_query = array(':email'=>$email);
			$QueryUniq = pdo_query("select count(*) from `$bankname` where  isdeleted=0 and email=:email $id_main_select ",$sql_query,0); 
			$Count=pdo_count($QueryUniq);
			if ( $Count != 0 ) { 
				$ErrorMsg .= "از این ایمیل[$email] قبلا استفاده شده است، لطفا تغییر دهید<BR>";  
			} 
		}
		if ($ErrorMsg !='') { 			
			echo "
			<div class='row' style='margin:auto; display: inline-block; width:100%;'>
			<div class='col-12 mt-4 bg-danger rounded text-center w-50 p-2 text-white' style='margin:auto;'>$ErrorMsg
			</div></div>";
		}else{
			//----------------------------------------
			$max_filesize = $maxsize_image * 1024 * 1024 ;
			$upload_path = "Files/users/";
			$default_name	= 'user_' ; //used in uploadfile code 
			$userfilename="userphoto";
			if (!empty($_FILES[$userfilename]["name"])) {
				include("./uploadimage_admin.php");
				$user_photo = 'users/'.$NameAfterUpload;
			} 
			//---------------------
			if ($active_mail_phone==1) { // for mobile
				$query_array = array(':name'=>$name, ':phone'=>$phone, ':email'=>$email, ':birthday'=>$birthday,  ':userphoto'=>$user_photo );	
				$SQL_QUERY="update `$bankname`  set
					name=:name, email=:email, phone=:phone, birthday=:birthday, userphoto=:userphoto, 
					id_ostan='$id_ostan', id_city='$id_city', 
					ip_last_edit='$ip_last_edit',user_last_edit='$user_last_edit',date_last_edit='$date_last_edit' 	
					where id_main='$usernameid' ";
			}else{
				$query_array = array(':name'=>$name, ':mobile'=>$mobile, ':phone'=>$phone, ':birthday'=>$birthday, ':userphoto'=>$user_photo );	
				$SQL_QUERY="update `$bankname`  set
					name=:name, mobile=:mobile, phone=:phone, birthday=:birthday, userphoto=:userphoto,
					id_ostan='$id_ostan', id_city='$id_city', 
					ip_last_edit='$ip_last_edit',user_last_edit='$user_last_edit',date_last_edit='$date_last_edit' where id_main='$usernameid' ";
			}
			$sql=pdo_query($SQL_QUERY,$query_array,0);
			$query=pdo_query("select * from  `$bankname` where id_main='$usernameid' ");
			$row_user = pdo_fetch($query);
			$_SESSION['user']=$row_user;
			echo "<script>message('اطلاعات پروفایل با موفقیت بروزرسانی شد','success');</script>";
			$email_to_user=explode(',',$row_setting['email_to_user']);
			$shop_name = $row_setting['shop_name'];
			if (in_array(5,$email_to_user)) {			
				if (($email!='') ) {
					$Message = " کاربر [$name] اطلاعات کاربری شما در فروشگاه  [$shop_name] بروزرسانی شد <BR> $shop_name";
					$OkSendEmail = SendEmail('',$email,"بروزرسانی اطلاعات کاربری شما در [$shop_name]",$Message,'',0);
				}
			}
			
		}
	}
}
//--------------------------------- خواندن اطلاعات حتما حتی بعد از ذخیره کردن انجام شود
$row_student = $row_user;
$name	=$row_student['name'];					$email	=$row_student['email'];
$mobile	=$row_student['mobile'];				$phone	=$row_student['phone'];
//$idmeli	=$row_student['idmeli'];				$addres	=$row_student['addres'];
//$zipcode=$row_student['zipcode'];				

$birthday	= $row_student['birthday'];
$birthday_year  = mb_substr($birthday,0,4,'utf-8');
$birthday_month = mb_substr($birthday,5,2,'utf-8');
$birthday_day   = mb_substr($birthday,8,2,'utf-8');
$user_photo=$row_student['userphoto'];
if (($gmail==$email) and ($email!='') and ($gmail!='')) { 
	echo "<script>message('با تشکر از ثبت نام  شما، رمز پیش فرض شما همان ایمیل شما می باشد [$email] در صورت تمایل از بخش تغییر رمز می توانید آن را تغییر دهید', 'success'); </script>";
}
$id_ostan=$row_student['id_ostan'];				
$id_city=$row_student['id_city'];
if ($id_ostan==0) 	 $id_ostan = $row_setting['id_ostan'];
if ($id_city==0) 	 $id_city  = $row_setting['id_city'];
//----------------------------------------------
$ListOstan = array();	
$query  = pdo_query("select id,name from `ostan` ",'',0);
while ($row=pdo_fetch($query)) {
	$ListOstan[$row['id']] = $row['name'];
}

?>
<script>
$(document).ready(function () {
	<?php 
	ShowPhotojs('user_photo','userphoto');
	?>
	ChangeCity($("#id_ostan").val(),<?php echo $id_city;?>);
})
function ChangeCity(id, idold) {
	$("#loading").show();
	$('#id_city').html("<option value='0'></option>");
	$.ajax({
		type: 'POST',
		url: '/data_ajax.php',
		data: { type: 8, id: id ,idold: idold},
		dataType: 'json',
		success: function (data, status, xhr) {
			$("#loading").hide();
			if (data[0]<0) { message(data[1], 'error') }
			if (data[0]==1) { 	
				$('#id_city').html("");
				$('#id_city').html(data);
			}
			
		},
		error: function (jqXhr, textStatus, errorMessage) {alert(' textStatus = '+textStatus+' Error: ' + errorMessage);}
	});		
}	

//--------------------------
function check() {
	var reg_type = '<?php echo $row_student['active_mail_phone'];?>';
	if (reg_type==2) {
		if ($('#mobile').val()=='') {
			message('لطفا موبایل را وارد کنید','error',0,'mobile');
			return false;
		}
	}
	if (reg_type==1) {
		if ($('#email').val()=='') {
			message('لطفا ایمیل را وارد کنید','error',0,'email');
			return false;
		}
	}
	if ($('#name').val()=='') {
		message('لطفا نام و نام خانوادگی را به درستی وارد نمایید','error',0,'name');
		return false;
	}

	if ($('#email').val()!='') {
		if (!(checkEmail($('#email').val()))) {
			message('لطفا ایمیل خود را به درستی وارد کنید','error',0,'email');
			return false;
		}
	}	
	return true;
}
</script>
<main>
	<div class="row col-12">
	<div class="container" style='background-color: white;  margin: 40px auto;  border-radius: 7px;  padding: 15px;'>
		<div class="tab-content" id="nav-tabContent">
			<div class="tab-pane fade show active p-0 m-0" id="nav-login" role="tabpanel" aria-labelledby="nav-login-tab">
				<!-- header title -->
				<div class="header-title">
					<div><span class='text-success' style='white-space: nowrap;'>ویرایش پروفایل</span></div>
					<div class="head-line"></div>
				</div>
			<form action="" enctype="multipart/form-data" id="formEdit" method="post"  name="formEdit" onsubmit="return check();" >
			<input type='hidden' name='submit_form' value='1'>
			<div class="row">
				<div class="col-md-9">
				<div class='row'>
					<div class="col-12 col-md-4">
						<div class="form-group">
							<label class="form-label">نام و نام خانوادگی<span class='text-danger'>*</span>
							</label>
							<input class="form-control" value="<?php echo $name;?>" id="name" name="name"  type="text" >
						</div>
					</div>
					
					<div class="col-6 col-md-4">
						<div class="form-group">
							<label class="form-label">شماره تلفن</label>
							<input class="form-control" value="<?php echo $phone;?>" id="phone" name="phone"  type="text" style='direction:ltr;' onkeypress="return isNormalNumber(event)" 
							 >
							
						</div>
					</div>
					<div class="col-6 col-md-4">
						<div class="form-group">
							<label class="form-label">شماره موبایل<span class='text-danger'>*</span></label>
							<input class="form-control" value="<?php echo $mobile;?>" id="mobile" name="mobile"  type="text" style='direction:ltr;' onkeypress="return isNormalNumber(event)" <?php if ($active_mail_phone==1) echo 'readonly';?>>
							
						</div>
					</div>
					<div class="col-12 col-md-6">
						<div class="form-group">
							<label class="form-label">پست الکترونیکی</label>
							<input class="form-control" value="<?php echo $email;?>" id="email" name="email" placeholder="" type="email" style='direction:ltr;' 
							<?php if ($active_mail_phone==2) echo 'readonly';?> >
						</div>
					</div>
					<div class="col-6 col-md-4">
						<div class="form-group">
							<label class="form-label">تاریخ تولد</label>
							<div class='row'>
								<div class='col-3 p-0'>
									<select class="form-control" id="birthday_day" name="birthday_day" > 
									<option value='0'>روز</option>
									<?php 
									for ($iz=1;$iz<=31;$iz++) {
										if ($birthday_day==$iz) $sel=" selected "; else $sel='';
										echo "<option value='$iz' $sel>$iz</option>";
									}
									?>
									</select>
								
								</div>
								<div class='col-6 pr-1 pl-1 m-0'>
									<select class="form-control" id="birthday_month" name="birthday_month" > 
									<option value='0'>ماه</option>
									<?php 
									for ($iz=1;$iz<=12;$iz++) {
										$temp = $month_name_farsi[$iz];
										if ($birthday_month==$iz) $sel=" selected "; else $sel='';
										echo "<option value='$iz' $sel>$temp</option>";
									}
									?>
									</select>
								</div>
								<div class='col-3 p-0'>
									<select class="form-control" id="birthday_year" name="birthday_year" > 
									<option value='0'>سال</option>
									<?php 
									$lastyear = $nowyear - 5;
									for ($iz=1300;$iz<=$lastyear;$iz++) {
										if ($birthday_year==$iz) $sel=" selected "; else $sel='';
										echo "<option value='$iz' $sel>$iz</option>";
									}
									?>
									</select>
								</div>
							</div>
						</div>
					</div>
					<div class="col-6 col-md-4 <?php echo $display_city;?>">
						<div class="form-group">
							<label class="form-label">استان محل سکونت</label>
							<select class="form-control select2" id="id_ostan" name="id_ostan" onchange="ChangeCity(this.value);" >
							<?php
								foreach($ListOstan as $key=>$value) {
									$sel = ($key==$id_ostan) ? "selected" : "";
									echo "<option value='$key' $sel>$value</option>";
								}
							
							?>
							</select>
						</div>
					</div>
					<div class="col-6 col-md-4" <?php echo $display_city;?>">
						<div class="form-group">
							<label class="form-label">شهر محل سکونت<span class='text-danger'>*</span></label>
							<select class="form-control" id="id_city" name="id_city" >
							<option value=0></option>
							</select>
							<input type='hidden' name='idcity' id='idcity' value='<?php echo $id_city;?>'>
						</div>
					</div>
					<div class="col-6 col-md-4 d-none">
						<div class="form-group">
							<label class="form-label">کدپستی<span class='text-danger'>*</span></label>
							<input class="form-control" value="<?php echo $zipcode;?>" id="zipcode" name="zipcode"  type="text" style='direction:ltr;' onkeypress="return isNormalNumber(event)" maxlength=10 >
							
						</div>
					</div>
				</div>
				</div>
				<div class="col-md-3">
					<div class="form-group">
						<label class="form-label"> تصویر پروفایل </label> 
						<?php GetPhotoJsFront($user_photo, "user_photo","userphoto" );?>
					</div>
				</div>
				<!--
				<div class="col-12">
					<div class="form-group">
						<label class="form-label">آدرس پستی<span class='text-danger'>*</span></label>
						<input class="form-control" value="<?php echo $addres;?>" id="addres" name="addres"  type="text">
						
					</div>
				</div>
				-->
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