<?php 
//echo '<BR>time='.time();
if (!isset($upload_path) ) { $upload_path = $upload_path_main; /* ../Files/ */  }
if (!isset($userfilename) ) {  $userfilename='userfile';} // نام متغیر آپلود عکس
if (!isset($default_name) ) {  $default_name='';} //پیش فرض اول اسم فایل
if (!isset($allowedfiletypes) ) {  $allowedfiletypes='jpg,jpeg,gif,png,bmp,ico';}
if (!isset($max_filesize)) {$max_filesize = 1024000 ; } // Maximum filesize in BYTES (currently 1MB).
if (!isset($contor_file)) {$contor_file = -1 ; } // پیش فرض برای آپلود یک عکس
if (!isset($after_name) ) {  $after_name='';} //پیش فرض آخر فایل


if ($contor_file==-1) {
	$filename=strtolower($_FILES[$userfilename]['name']);
	$from=$_FILES[$userfilename]['tmp_name'];
} else {
	$filename=strtolower($_FILES[$userfilename]['name'][$contor_file]);
	$from=$_FILES[$userfilename]['tmp_name'][$contor_file];
}
$allowed_filetypes=explode(',',$allowedfiletypes);
list($width_image_tmp, $height_image_tmp, $type_image_tmp) = getimagesize($from);
if (!(is_uploaded_file($from))) {
	echo "<script>alert('".$_FILES[$userfilename]['error']."اشکال شماره  ".CHR(13).'سيستم قادر به آپلود اين  فايل نمي باشد'."')</script>";
	echo '<script>history.back(-1);</script>';
	exit;
}		
$filesizetmp = filesize($from);
$toInfo=pathinfo($filename);

$Ext=strtolower($toInfo['extension']);
if(!in_array($Ext,$allowed_filetypes)) {
	echo "<script>alert('مجاز به آپلود می باشند $allowedfiletypes تنها فایل هایی با پسوندهای');</script>";
	echo '<script>history.back(-1);</script>';
	exit;
}
if (($filesizetmp > $max_filesize) and ($max_filesize!=0) ) {
	echo "<script>alert('حجم فایل آپلودی [$filesizetmp بایت] بیش از سقف تعیین شده [$max_filesize بایت] می باشد');</script>";
	echo '<script>history.back(-1);</script>';
	exit;
}
if(!is_dir($upload_path)) {	mkdir($upload_path , 0777);}
if(!is_dir($upload_path)) {
	echo "<script>alert('متأسفم چنین مسیری $upload_path در سرور جهت ذخیره فایل آپلودی وجود ندارد')</script>";
	echo '<script>history.back(-1);</script>';
	exit;
}
if(!is_writable($upload_path)) chmod($upload_path, 777);
if(!is_writable($upload_path)) {
	echo "<script>alert('متأسفم مسیر تعیین شده در سرور [ $upload_path ] امکان ذخیره فایل آپلودی را ندارد')</script>";
	echo '<script>history.back(-1);</script>';
	exit;
}
if ((intval($width_image_tmp)==0) or (intval($height_image_tmp)==0) ) {
	echo "<script>alert('فایل آپلودی تصویر نمی باشد')</script>";
	echo '<script>history.back(-1);</script>';
	exit;
}
if (isset($image_upload_width)) { 
	if ($width_image_tmp>$image_upload_width) {
		echo "<script>alert('متأسفم عرض تصویر آپلودی از مقدار تعیین شده $image_upload_width بزرگتر می باشد')</script>";
		echo '<script>history.back(-1);</script>';
		exit;
	}
}
if (isset($image_upload_height)) { 
	if ($height_image_tmp>$image_upload_height) {
		echo "<script>alert('متأسفم ارتفاع تصویر آپلودی از مقدار تعیین شده $image_upload_height بزرگتر می باشد')</script>";
		echo '<script>history.back(-1);</script>';
		exit;
	}
}
$Pfile='';
//----------------------------------
$ddate=new Date();				
$nowdatetimeFile = $ddate->format("%Y-%m-%d_%H-%M-%S");		
$lastdir = substr($upload_path, -7);
$basepath = $upload_path;
if ($lastdir=='/image/') {
	$Nyear = $ddate->format("%Y"); $Nmonth = $ddate->format("%m"); 
	$upload_path2 = $upload_path . $Nyear.'/';
	if(!is_dir($upload_path2)) {
		mkdir($upload_path2 , 0777);
		if(!is_dir($upload_path2)) {
			echo "<script>alert('متأسفم امکان ایجاد چنین مسیری $upload_path2 در سرور جهت ذخیره فایل آپلودی وجود ندارد')</script>";
			echo '<script>history.back(-1);</script>';
			exit;
		}
	}
	$upload_path2 .= $Nmonth.'/';
	if(!is_dir($upload_path2)) {
		mkdir($upload_path2 , 0777);
		if(!is_dir($upload_path2)) {
			echo "<script>alert('متأسفم امکان ایجاد چنین مسیری $upload_path2 در سرور جهت ذخیره فایل آپلودی وجود ندارد')</script>";
			echo '<script>history.back(-1);</script>';
			exit;
		}
	}
	$Pfile = $Nyear.'/' .$Nmonth.'/';
	$upload_path = $upload_path2;
}
//------------------------------------
$nowdatetimeM = str_replace(array('/',':',' '),array('','','_'),$nowdatetime);
if ($after_name=='') $after_name = '_'. rand(100,999);
 $Target = $default_name . $nowdatetimeM . $after_name . '.' . $Ext;
//echo "<script>alert('default_name=$default_name---upload_path=$upload_path');</script>";
move_uploaded_file($from, $upload_path.$Target);
$NameAfterUpload= $Pfile . $Target;

?>