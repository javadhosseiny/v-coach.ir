<?php
include('topmain.php');
$bankname='group_main';
if (isset($_REQUEST['id']))   	$id = intval($_REQUEST['id']);		else $id = 0;
if (isset($_REQUEST['pass']))   $pass = ($_REQUEST['pass']);		else $pass = '';
$query=pdo_query("select * from `$bankname` where id='$id' and type_group='3' and active='1' and isdeleted=0 ",'',0);
$row_content = pdo_fetch($query);
//var_dump($row_content);exit;
if ($row_content=='') {	$ErrorMsg .= "کد مطلب موردنظر صحیح نمی باشد";} else{
	$name  	= $row_content['name'];				$dsc 			= $row_content['dsc'];
	$them	= $row_content['them'];				$photo_group	= $row_content['photo_group'];
	$password			= $row_content['password'];
	$meta_title			= $row_content['meta_title'];
	$meta_description 	= $row_content['meta_description'];
	$url_redirect 		= $row_content['url_redirect'];
	if ($photo_group!='') {
		if (!validateURL($photo_group)) $photo_group = $upload_path_main.$photo_group;
		$photo_group=$logopage;
	}
	if ($url_redirect!='') header("Location: $url_redirect");
	if ($meta_title!='') $meta = $meta_title;
	if ($meta_description!='') $meta_dsc = $meta_description;
}
$title_main=$name;
include('top.php');
if ($password!='') { 	if (CheckPassword($password) === false) { include('bottom.php');  return;} }
$capcha = $row_content['ok_capcha'];

?>
<main>
	<div class="col-12">
		<div id="content">
			<div class="about">
				<div class="page-content-about">
                <h2 class='m-4 text-center'><?php echo $name;?></h2>
					<div class="page-content-about-paragraph text-justify">
					<?php 
					echo $dsc;
					$idform = $id;
					$ErrorMsg='';
					$bankname2 = 'group_form'; 	
					$bankname3 = 'form_content'; 	
					//-----------------------------------------
					$query= pdo_query("select * from `$bankname2` where id_form='$idform' and isdeleted='0' order by idsort ",'',0);
					$row_field  = pdo_fetchall($query);
					$MainFormCode         = FormTag();
					$JavaScriptValidation = JavaScript();
					$PhpValidation        = PhpScript();
					//-----------------------------------------
					//شروع آماده سازی متغیرها و ذخیره و یا اصلاح در جدول
					$FoundRec = false;

					//تشخیص اینکه آیا این فرم امکان آپلود فایل و یا تصویر را دارد یا خیر
					$OkAttach= False;
					for ($iz=0; $iz<count($row_field);$iz++) {
						if (in_array($row_field[$iz]['field_type'], array(7, 10)) ) $OkAttach= true;
					}	
					$AttachFile = ($OkAttach) ? " enctype='multipart/form-data' " : " ";
					//-----------------------------------------
					$CapchaString = GetCapcah($capcha);
					echo "
					$JavaScriptValidation
					<main>
						<div class='container' id='main_form'>
							<div class='rounded mt-4 p-4'>
							$matnactive
							$matnedit
							</div>
							<form  $AttachFile  method='post' id='myform' name='myform' onsubmit='return SaveForm();'> 
							<section class='search-box row d-flex d-md-flex d-lg-flex d-xl-flex' style='margin: 18px 0; width: 100%'>
								<input type='hidden' name='_formpass' value='$_formpass'>
								<input type='hidden' name='idform' value='$idform'>
								<input type='Hidden' name='_submit' value='ok'>
								<input type='Hidden' name='type' value='101'>
								$MainFormCode
								$CapchaString
								<div class='col-12 m-2'></div>
								<div class='col-12 col-xs-12 col-sm-12 col-md-6 col-lg-6' style='margin:auto;'>
									<button class='btn btn-danger w-100' type='submit' >تأیید</button>
								</div>
								<BR>
							</section>
							</form>
						</div>
					</main>";
					?>
					</div>
                </div>
            </div>
        </div>
    </div>
</main>
<?php 
include('bottom.php');
//-----------------------------------------
function JavaScript() {
	global $row_field, $capcha;
	$HeaderPage ='';
	$JavaCode = "";
	$FarsiJs = 0;		$DateShamsi=0;		$DateGhamari=0;		$DateMiladi=0;
	$DefaultDateShamsi= '';
	for ($iz=0; $iz<count($row_field);$iz++) {
		$fieldname 	= "f_".$row_field[$iz]['id_main']; 	// نام انگلیسی (اصل) فیلد
		$label     	= $row_field[$iz]['tetr'];			// نام فارسی (لیبل) فیلد
		$label 		= str_replace(':','', $label);
		$typefield 	= $row_field[$iz]['field_type'];  		// نوع فیلد
		$mustfill  	= intval($row_field[$iz]['must_answer']);  // الزام محتوا
		$defultvalue= $row_field[$iz]['default_value']; 	// مقدار اولیه
		$error_msg	= $row_field[$iz]['error_msg']; 		// متن پیغام خطا
		$fieldheight= intval($row_field[$iz]['height_field']); // ارتفاع ویژه تکست اریه
		$fieldanswer= $row_field[$iz]['list_answer'];		// لیست پاسخ ها
		$listanswer = (explode("\r\n",$fieldanswer )); 		//جوابها
		$max_length = $row_field[$iz]['max_length'];				// نهایت اندازه ورودی
		$readonly	= $row_field[$iz]['readonly'];			// فقط خواندنی
		$multianswer= $row_field[$iz]['multianswer'];		// چند پاسخ بتواند بگیرد
		$regex_field= $row_field[$iz]['regex_field'];			// رگولار اکسپریشن
		if ($error_msg=='') $error_msg = "اطلاعات مربوط به [$label] صحیح نمی باشد";
		//-------------
		if ($regex_field!='') {
			if (mb_substr($regex_field,0,1,'utf-8')!='/') 	$regex_field = "/".$regex_field;
			if (mb_substr($regex_field,-1,1,'utf-8')!='/') 	$regex_field = $regex_field . "/";
		}
		//-----------
		if (in_array($typefield, array(11)) ) 	{ $DateShamsi  = 1;}
		if (in_array($typefield, array(13)) ) 	{ $DateMiladi  = 1;}
		if (in_array($typefield, array(14)) )  	{ $DateGhamari = 1;}
		if (in_array($typefield, array(7,10)) ) {  // file and photo upload
			$accept = '';
			$accept2='';
			if (($typefield==7) and ($fieldanswer=='') ) { 
				$accept= "'.jpe','.jpg','.jpeg','.png','.pcx','.gif','.bmp','.ico','.svg','.webp','.tif','.tiff'"; 
			}
			$Jamlistanswer =count($listanswer);
			if (($Jamlistanswer>0) and ($fieldanswer!='')) {
				for ($j=0;$j<$Jamlistanswer;$j++){
					$Extended = trim($listanswer[$j]);
					if ($Extended!='') {$accept .= "'.$Extended',"; }
				}
				if ($accept!='')  $accept 	 = substr($accept,0,-1); 
			}
			if ($accept!='')  $accept2	 = str_replace(array("'","."),'', $accept);

			
			if ($max_length==0) $max_length=2;
			$JavaCode .= "
			var AcceptExt    = '$accept2';
			var max_filesize = $max_length * 1024 * 1024;
			var CancelForm = false;
			var input = document.getElementById('upload_$fieldname');
			for (var i = 0; i < input.files.length; i++) {
				userfile     = input.files[i].name;
				userfilesize = input.files[i].size;
				if (userfile != '')  {
					if (AcceptExt!='') {
						var extfile = userfile.substr(userfile.lastIndexOf('.'));
						extfile = extfile.toLowerCase();
						if ([$accept].indexOf(extfile) < 0) {
							message('  تنها فایلهایی با پسوند [$accept2] مجاز به معرفی در این بخش هستند <BR> ['+userfile+']'); 
							CancelForm = true;
							break;
						}
					}
					if (userfilesize > max_filesize) {
						message('حجم فایل انتخابی برای $label بیش از $max_length مگابایت می باشد','error',0,'');
						CancelForm = true;
						break;
					}
				}
			}
			if (CancelForm) { 
				form.upload_$fieldname.focus();
				return false;
			}
			
			";
		}
		if ($mustfill==1) {
		if ($error_msg=='') $error_msg = "وارد کردن اطلاعات مربوط به [$label] الزامی می باشد";
		switch ($typefield) {
		case 10:  // file
		case 7:   // image file
			$JavaCode .= "
			var input = document.getElementById('upload_$fieldname');
			if ( (form.file_$fieldname.value == '' ) && (input.files.length==0) ) {
				message('$error_msg','error',0,'');
				form.upload_$fieldname.focus();
				return false;
			} 
			";
			break;
		case 5:   // لیست چک
			$JavaCode .= "
			if (  checkListCheckForm(form, '$fieldname')<=0)  {	
				message('$error_msg','error',0,'$fieldname');
				return false;
			} 
			";
			break;
		case 6:   // رادیو باتن
			$JavaCode .= "
			var selectedRadio = $('input[name=\"$fieldname\"]:checked').val();
			if ( selectedRadio )  {	}else{
				message('$error_msg','error',0,'$fieldname');
				return false;
			} 
			";
			break;
		default:
			$JavaCode .= "
			if ($('#$fieldname').val()=='') {
				message('$error_msg','error',0,'$fieldname');
				return false;
			}
			";
		}
		}
		if (is_valid_regex($regex_field)) {
		// text - textarea - number full (price) - file image - passowrd - file attach - date shamsi - number - date-miladi
			if (in_array($typefield, array(1,2,4,8,11,12,13)) )  	{ 
				if ($error_msg=='') $error_msg = " اطلاعات مربوط به [$label] صحیح نمی باشد";
				$regex_field = preg_replace('/\\\\+/', '\\', $regex_field); 
				$JavaCode .= "
				var myRegex    = $regex_field;
				var inputValue = $('#$fieldname').val();
				if (inputValue!='') {
					if (myRegex.test(inputValue)===false) { 
					message('$error_msg','error',0,'$fieldname');
					return false;
					}
				}
				";
			}
		}
	}
	
$StrDefaultDateShamsi ='';
if ($capcha==1) { 	
	$JavaCode .= "
	var Capcha1 = $('#CapchaSecCode1').val();
	var Capcha2 = $('#CapchaSecCode2').val();
	if (Capcha1 == '' ) {
		message('لطفا کد کپچا را درست وارد نمایید','error',0,'CapchaSecCode1');
		return false;
	}
	if (MD5(Capcha1) != Capcha2) {
		message('لطفا کد کپچا را درست وارد نمایید','error',0,'CapchaSecCode1');
		return false;
	}
	";
}
if ($capcha==2) { 	
	$JavaCode .= "
	const recaptchaResponse = grecaptcha.getResponse();
	if (recaptchaResponse.length === 0) {
		message('لطفا کپچا گوگل را تایید نمایید','error',0,'');
		return false;
	}
	";
}
///-----------------
$StrDefaultDateShamsi = <<<EOD
var DefaultDateShamsi = {
    placeholder: "روز / ماه / سال"
    , twodigit: true
    , closeAfterSelect: true
    , buttonsColor: "blue"
    , forceFarsiDigits: true
    , markToday: true
    , markHolidays: true
    , highlightSelectedDay: true
    , sync: true
    , gotoToday: true
}
EOD;
if ($DateShamsi!=1)  {$StrDefaultDateShamsi='';}
if ($DateShamsi==1)  {
$HeaderPage .= <<<EOD
<link rel="stylesheet" type="text/css" href="/assets/kamadatepicker/kamadatepicker.css">
<script src="/assets/kamadatepicker/kamadatepicker.js"></script>

EOD;
}
	
if ($DateGhamari==1) 
$HeaderPage .= <<<EOD
<link rel="stylesheet" type="text/css" href="/assets/images/calendar-arabic.css">
<script type="text/javascript" src="/assets/js/calendar-arabic-enjine-jquery.js"></script>
<script type="text/javascript" src="/assets/js/calendar-arabic.js"></script>

EOD;

	if ($DateMiladi==1) 
$HeaderPage .= <<<EOD
<link rel="stylesheet" type="text/css" href="/assets/images/calendar-english.css">
<script type="text/javascript" src="/assets/js/calendar-english.js"></script>
<script type="text/javascript" src="/assets/js/calendar-english-enjine.js"></script>

EOD;

$HeaderPage .=	<<<EOD
<script type='text/javascript'> 
$StrDefaultDateShamsi
function SaveForm() {
	var form = document.myform; 
	$JavaCode
	var form = $('#myform')[0]; 
	var formData = new FormData(form);
	
	$.ajax({
		type: 'POST',
		url: '/data_ajax.php',
 		data: formData, 
    	processData: false, 
    	contentType: false, 		
		dataType: 'json',
		success: function (data, status, xhr) {
			if (data[0]<0) { message(data[1], 'error') }
			if (data[0]==1) { 
				new_message = "<div class='col-11 text-center bg-success text-white rounded p-5 m-5'><BR>" + data[1] + "<BR><BR></div>";
				$("#main_form").html(new_message);
			}
		},
		error: function (jqXhr, textStatus, errorMessage) {alert(' textStatus = '+textStatus+' Error: ' + errorMessage);}
	});	
	
	return false;
}

</script>	
EOD;
	return $HeaderPage;
}

//-----------------------------------------
function PhpScript() {
	global $row_field;
	$ret='';
	return $ret;
}
//-----------------------------------------
function FormTag() {
global $row_field, $row_old, $upload_path, $them;
//var_dump($row_field);
$ret='';

$ListTypeField=array(0=>'',1=>"متن تک خطی", 2=>"متن چند خطی", 3=>"فهرست کشویی",  4=>"عددی (مبلغ)",  5=>"چک لیست",  6=>"رادیو لیست",  7=>"فایل - تصویر", 8=>"رمز عبور", 9=>"خط جداکننده",  10=>"فایل", 11=>"تاریخ شمسی", 12=>"عددی", 13=>'تاریخ میلادی');

if ($them==4) $ret="<div class='col-12 col-sm-12 col-md-9' style='margin:auto;'>";
for ($iz=0; $iz<count($row_field);$iz++) {
//	$fieldname 	= $row_field[$iz]['fieldname']; 	// نام انگلیسی (اصل) فیلد
	$fieldname 	= "f_".$row_field[$iz]['id_main']; 	// نام انگلیسی (اصل) فیلد
	$label     	= $row_field[$iz]['tetr'];			// نام فارسی (لیبل) فیلد
	$divclass  	= $row_field[$iz]['css_class']; 	// استایل دیو قبل از تگ 
	$typefield 	= $row_field[$iz]['field_type'];  	// نوع فیلد
	$mustfill  	= intval($row_field[$iz]['must_answer']);  // الزام محتوا
	$hint  	   	= $row_field[$iz]['help_msg'];  	// هینت
	$fieldalign = $row_field[$iz]['direction']; 	// راست چین
	$defultvalue= $row_field[$iz]['default_value']; 	// مقدار اولیه
	$fieldheight= intval($row_field[$iz]['height_field']); // ارتفاع ویژه تکست اریه
	$fieldanswer= $row_field[$iz]['list_answer'];	// لیست پاسخ ها
	$listanswer = (explode("\r\n",$fieldanswer )); 	//جوابها
	$readonly	= $row_field[$iz]['readonly'];			// فقط خواندنی
	$max_length = $row_field[$iz]['max_length'];		// نهایت اندازه ورودی
	$regex_field= $row_field[$iz]['regex_field'];		// رگولار اکسپریشن
	$readonly	= $row_field[$iz]['readonly'];			// فقط خواندنی
	$multianswer= $row_field[$iz]['multianswer'];		// چند پاسخ بتواند بگیرد
//	if ($fieldname=='f_10')	 {		$test = $row_field[10];		var_dump($test);	}
	
	
	
	$StrRow     = ($fieldheight==0) ? " rows='1' ": " rows='$fieldheight' ";			//تعداد سطر ویژه textarea
	$StrMustFill= ($mustfill==1)    ? " <span class='text-danger'> * </span> ": " ";	//نمایش ستاره قرمز
	$MaxLength 	= ($max_length>0)   ? " maxlength='$max_length' ": " ";					//تعیین حداکثر حروف
	$StrReadonly= ($readonly==1)    ? " readonly ": " ";								//فقط خواندنی
	$multiple	= ($multianswer==1) ? " multiple ": "";									//چند انتخابی
	if ($fieldalign==0) {		$direction=" dir='rtl' ";		$aligment = " align='right' ";	}else{
		$direction=" dir='ltr' ";		$aligment = " align='left' ";	}
//	if ($regex_field!='')  { $StrPattern	= " pattern='$regex_field' ": " ";	}
	$StrPattern	= '';
	
	$StrHint 	 = "";
	if ($hint !="") {		$StrHint = " <span class='text-success'> $hint </span> ";	}
		
	$ContentField=$defultvalue;
	
	if ($them==1) { // custom
		if ($divclass=='') $divclass="col-12";
		$ret .= "<div class='$divclass'>  <label class='col-12 col-form-label pr-0'>$label $StrMustFill $StrHint</label> ";
	}
	//form-control - form-select -- form-check-input 
	if ($them==2) { // horzintal
		$ret .= "<div class='row col-12 form-group'><label class='col-form-label col-md-3 text-md-left'>$label $StrMustFill $StrHint</label><div class='col-md-9 col-lg-6'>";
	}
	if ($them==3) { // vertical
		if ($divclass=='') $divclass="col-md-9 col-lg-6";
		$ret .= "<div class='row col-12 form-group'><label class='col-form-label col-12'>$label $StrMustFill $StrHint</label><div class='$divclass'>";
	}
	if ($them==4) { // cover
		if ($divclass=='') $divclass="col-md-9 col-lg-6";
		$ret .= "<div class='row col-12 form-group'><label class='col-form-label col-12'>$label $StrMustFill $StrHint</label><div class='col-12'>";
	}

	switch ($typefield) {
		case 1:   // متن تک خطی
			$tag = "<input type='text' id='$fieldname'   name='$fieldname' value='$ContentField' class='form-control' $aligment $direction $MaxLength $StrPattern $StrReadonly>"; 
			break;
		case 2:  // پاراگراف
			$tag = "<textarea id='$fieldname'   name='$fieldname'  $StrRow class='form-control'  $aligment $direction $MaxLength $StrPattern $StrReadonly>$ContentField</textarea>"; 
			break;
		case 3:   // لیست باکس یا فهرست کشویی
			$Jamlistanswer =count($listanswer);
			if ($multianswer==1) $CharArray='[]'; else $CharArray='';
			$tag  = "<select name='$fieldname".$CharArray."' id='$fieldname' class='form-control' $direction $aligment $multiple > \r\n";
			$tag .= "<option value='' class='form-control'></option>";
			if ($Jamlistanswer>0) {
				for ($j=0;$j<$Jamlistanswer;$j++){
					$CheckBoxTetr = $listanswer[$j];
					$Selected = ''; 
					if ($CheckBoxTetr != '') {
						if (strpos($ContentField, $CheckBoxTetr)!== false) $Selected ='selected'; else $Selected ='';
						$tag .= "<option value='$CheckBoxTetr' $Selected class='form-select'> $CheckBoxTetr </option> \r\n" ;
					}
				}
			}	
			$tag .= "</select> \r\n";
			break;
		case 4:   // عددی مبلغ
			$tag = "<input type='Text' id='$fieldname'   name='$fieldname' value='$ContentField' class='form-control' $aligment $direction  $MaxLength onkeypress='return isNormalNumber(event)' onkeyup='this.value =itpro(this.value);' onpaste='PasteonlyNumbers(event);' $StrReadonly>";
			break;
		case 5:   // چک لیست ---. لیستی از تیک دارها که می تواند همگی یا یکی از آنها تیک بخورد
			$Jamlistanswer =count($listanswer);
			$tag = "";
			if ($Jamlistanswer>0) {
				for ($j=0;$j<$Jamlistanswer;$j++){
					$CheckBoxTetr = $listanswer[$j];
					$CheckBoxValue = "$CheckBoxTetr";
					$Selected = ''; 
					if ($CheckBoxTetr != '') {
						if (strpos($ContentField, $CheckBoxValue) !== false) $Selected ='checked'; else $Selected ='';
						$tag .= "
						<input name='$fieldname"."[]' type='checkbox'  id='$fieldname$j' $Selected class='form-check-input'  value='$CheckBoxValue' > 
						<span class='ml-3 mr-3 text-muted' style='font-size:14px;' > $CheckBoxTetr </span> \r\n";
					}
				}
			}	
			break;
		case 6:   // رادیو لیست
			$Jamlistanswer =count($listanswer);
			$tag = "";
			if ($Jamlistanswer>0) {
				for ($j=0;$j<$Jamlistanswer;$j++){
					$CheckBoxTetr = $listanswer[$j];
					$CheckBoxValue = "$CheckBoxTetr";
					$Selected = ''; 
					if ($CheckBoxTetr != '') {
						if (strpos($ContentField, $CheckBoxValue) !== false) $Selected ='checked'; else $Selected ='';
						$tag .= "
						<input name='$fieldname' type='radio'  id='$fieldname$j' $Selected class='form-check-input'  value='$CheckBoxValue' > 
						<span class='ml-3 mr-3 text-muted' style='font-size:14px;' > $CheckBoxTetr </span> \r\n";
					}
				}
			}	
			break;
		case 8:  //اسم رمز1
			$tag = "<input type='password'  id='$fieldname'   name='$fieldname' value='$ContentField' class='form-control'  $aligment $direction $MaxLength $StrReadonly>"; 
			break;
		
		case 9:   // خط جداکننده
			$tag = "<hr>";
			break;
		case 7:   // image file
		case 10:  // file
			$accept = '';			$MaxSize=2;
			if ($max_length>0) 	$MaxSize=$max_length;
				
			$tetr1 = 'انتخاب فایل';
			if (($typefield==7)) {
				if ($fieldanswer==''){ $accept= "image/*";  }
				$tetr1 = 'انتخاب تصویر';
			}
			$Jamlistanswer =count($listanswer);
			if (($Jamlistanswer>0) and ($fieldanswer!='')) {
				for ($j=0;$j<$Jamlistanswer;$j++){
					$Extended = trim($listanswer[$j]);
					if ($Extended!='') {$accept .= ".$Extended,"; }
				}
				if ($accept!='') { $accept 	 = substr($accept,0,-1); }
			}
			if ($accept !='')  $accept = " accept='$accept' ";
			$temp_name = "upload_$fieldname";
			if ($multiple!='') $temp_name .= "[]";
			$tag  = "
			<input id='upload_$fieldname'  type='file' $accept  name='$temp_name' $multiple style='display: none;' onchange= \"ChangeSelectFile('#upload_$fieldname', $MaxSize, '#FileInfo_$fieldname')\";>
			<button type='button' onclick='$(\"#upload_$fieldname\").trigger(\"click\"); return false;' class='btn btn-info'>$tetr1</button>
			<span class='badge bg-danger' style='padding:5px;cursor:pointer;' title='حذف فایل انتخابی'> <i class='fa fa-trash' style='color:white;font-size:18px;' onclick=\"$('#upload_$fieldname').val('');$('#FileInfo_$fieldname').html('');\"></i></span>
			<span id='FileInfo_$fieldname'></span>
			<input type='hidden' id='file_$fieldname' value='' name='file_$fieldname' >
			<script>$('#upload_$fieldname').val(''); $('#FileInfo_$fieldname').html('');</script>
			";
			break;
		case 12:   // عددی
			$tag = "<input type='Text' id='$fieldname'   name='$fieldname' value='$ContentField' class='form-control' $aligment $direction  $MaxLength 
			onkeypress='KeypressAllowNumber2(event)' onpaste='PasteonlyNumbers2(event);' $StrReadonly>";
			break;
		case 11:   // تاریخ شمسی
			$tag = "<input class='form-control' value='$ContentField' id='$fieldname' name='$fieldname' type='text'  title='yyyy/mm/dd' $MaxLength dir='ltr' align='left' $StrReadonly ><script>kamaDatepicker('$fieldname',DefaultDateShamsi);</script>";
			break;
		case 13:   // تاریخ میلادی
			$tag  = "<input  type='text' name='$fieldname' id='$fieldname' class='form-control' style='width:80%;display:unset;' dir='ltr' title='yyyy/mm/dd' value='$ContentField' $MaxLength dir='ltr' align='left' $StrReadonly > \r\n
			<span id='$fieldname-btn' style='cursor:pointer;' onclick=\"displayDatePicker('$fieldname', this);\">  <i class='fa fa-calendar' aria-hidden='true'></i></span>
			\r\n
			<script> setActiveStyleSheet('blue'); var popupCal = Calendar.setup({inputField: '$fieldname', button: '$fieldname-btn', ifFormat: '%Y/%m/%d', dateType: 'gregorian',weekNumbers: false});</script> \r\n ";
			break;			
		case 14:   // تاریخ قمری
			$tag = "
			<script>
			//درست کار نمی کند پس کلا از داخل طراحی فرم برش داشتم
			//$.noConflict(); 
			jQuery(document).ready(function($) {
				$(function() { 
				$('#$fieldname').datepicker(
					{regional: 'ar',showOn: 'button',buttonImage: '/assets/images/calendar.png',buttonImageOnly: true,dateFormat: 'yy/mm/dd',autoSize: true}
				);
				});
			});
			</script>
			<input  type='text' name='$fieldname' id='$fieldname' class='form-control' style='width:90%;display:unset;' dir='ltr'  title='yyyy/mm/dd' value='$ContentField' $StrReadonly > \r\n";
			break;

		default:
			$tag = "<input type='Text' id='$fieldname'   name='$fieldname' value='$ContentField' class='form-control' $aligment $direction  $MaxLength>"; 
	}
	$ret .=  "$tag \r\n" ;
	if ($them==1) { $ret .=  "</div> \r\n";}				// custom
	if ($them==2) { $ret .=  "</div></div> \r\n";}			// horzintal
	if ($them==3) { $ret .=  "</div></div> \r\n";}			// vertical
	if ($them==4) { $ret .=  "</div></div> \r\n";}			// cover 
}
if ($them==4) { $ret .=  "</div>\r\n";}			// cover 

return $ret;
}

//-----------------------------------------
function GetCapcah($capcha) {
	global $row_setting;
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
		<div class='col-12 col-md-4'>
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
		<div class='col-12 col-md-4 mt-2'>
			<div class='g-recaptcha' id='rcaptcha' data-sitekey='$sitekey'></div>				
		</div>
		";
	}	

	return $CapchaString;
}


//------------- بررسی اینکه آیا این رگولار صحیح است یا خیر
function is_valid_regex($regex) {
	if (trim($regex)=='') return false;
//	echo '--->'.mb_substr($regex,-1,1,'utf-8'); exit;
	$regex2 = "'$regex'";
    @preg_match($regex2, '');
    $error_code = preg_last_error();
    return ($error_code === PREG_NO_ERROR);
}
// پترن برای کد ملی
//$pattern 		= ^[0-9۰۱۲۳۴۵۶۷۸۹]{10}$
//$pattern 		= '/^\d{10}$/'; 
//const pattern = /^\d{10}$/; 

//$correct_pattern 	= '/^\d{1,3}(,\d{3})*$/';
//$pattern 			= '/^\d{1,3}(,\d{3})*$/'; 
//$pattern 			= '^d{1,3}(,d{3})*$';
/* تست با زبان php
$input_value = "1,234,567"; 
// $input_value = "123456"; // مثال نامعتبر
// $input_value = "123,45";   // مثال نامعتبر
$pattern = '/^\d{1,3}(,\d{3})*$/';
// حذف فاصله‌های اضافی (در صورت وجود)
$cleaned_value = trim($input_value);
if (preg_match($pattern, $cleaned_value)) { is ok} else { is not ok}


//------------- تست با زبان JavaScript
function validateInput() {
// ۱. تعریف الگوی صحیح برای اعداد با کاما
// (استفاده از \d برای تطبیق ارقام)
const numberPattern = /^\d{1,3}(,\d{3})*$/; 

// ۲. دریافت مقدار فیلد ورودی
const inputField = document.getElementById('numberInput');
const inputValue = inputField.value.trim();

const resultElement = document.getElementById('validationResult');

// ۳. تست کردن مقدار ورودی با استفاده از متد test()
if (numberPattern.test(inputValue)) {
	resultElement.textContent = "✅ ورودی معتبر است.";
	resultElement.style.color = 'green';
	return true;
} else {
	resultElement.textContent = "❌ ورودی نامعتبر است. فقط اعداد فرمت شده با کاما پذیرفته می‌شود.";
	resultElement.style.color = 'red';
	return false;
}
*/
?>

			
				

