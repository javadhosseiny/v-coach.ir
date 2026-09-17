<?php 
$ErrorMsg3 = '';
//echo '<BR>time='.time();
if (!isset($upload_path) ) { $upload_path = $upload_path_main; /* ../Files/ */  }
if (!isset($userfilename) ) {  $userfilename='userfile';} // نام متغیر آپلود عکس
if (!isset($default_name) ) {  $default_name='';} //پیش فرض اول اسم فایل
if (!isset($allowedfiletypes) ) {  $allowedfiletypes='jpe,jpg,jpeg,png,pcx,gif,bmp,ico,svg,webp,tif,tiff';}
if (!isset($max_filesize)) {$max_filesize = 2*1024000 ; } // Maximum filesize in BYTES (currently 1MB).
if (!isset($contor_file)) {$contor_file = -1 ; } // پیش فرض برای آپلود یک عکس
if (!isset($after_name) ) {  $after_name='';} //پیش فرض آخر فایل
if (!isset($typefield) ) {  $typefield=7;} //نوع فایل آپلودی اگر تعریف نشده بود تصویر باشد
if (!isset($SendPlatform) ) {  $SendPlatform=0;}
$Target='';
//echo "userfilename=$userfilename";var_dump($_FILES);
//echo "\r\n filename=".$_FILES[$userfilename]['name'];
if ($contor_file==-1) {
	$filename=strtolower($_FILES[$userfilename]['name']);
	$from=$_FILES[$userfilename]['tmp_name'];
} else {
	$filename=strtolower($_FILES[$userfilename]['name'][$contor_file]);
	$from=$_FILES[$userfilename]['tmp_name'][$contor_file];
}
//echo "<BR><BR> filename= $filename --- from=$from  ----allowedfiletypes=$allowedfiletypes";

$allowed_filetypes=explode(',',$allowedfiletypes);
if (!(is_uploaded_file($from))) {
	$Error1 = $_FILES[$userfilename]['error'];
	if (is_array($Error1)) { $Error1 = implode('-',$Error1); }
	$ErrorMsg3 = "[$Error1] اشکال <BR> سيستم قادر به آپلود اين  فايل نمي باشد";
	return;
}		
$filesizetmp = filesize($from);
$toInfo=pathinfo($filename);

$Ext=strtolower($toInfo['extension']);
if(!in_array($Ext,$allowed_filetypes)) {
	$ErrorMsg3 = "مجاز به آپلود می باشند $allowedfiletypes تنها فایل هایی با پسوندهای";
	return;
}
if (($filesizetmp > $max_filesize) and ($max_sizefile!=0) ) {
	$ErrorMsg3 = "حجم فایل آپلودی [$filesizetmp بایت] بیش از سقف تعیین شده [$max_filesize بایت] می باشد";
	return;
}
//echo "<BR> filename= $filename --- from=$from  ---- target=$Target";

if(!is_dir($upload_path)) {	mkdir($upload_path , 0777);}
if(!is_dir($upload_path)) {
	$ErrorMsg3 = "متأسفم چنین مسیری $upload_path در سرور جهت ذخیره فایل آپلودی وجود ندارد";
	return;
}
if(!is_writable($upload_path)) chmod($upload_path, 777);
if(!is_writable($upload_path)) {
	$ErrorMsg3 = "متأسفم مسیر تعیین شده در سرور [ $upload_path ] امکان ذخیره فایل آپلودی را ندارد";
	return;
}
if ($typefield==7) {
	list($width_image_tmp, $height_image_tmp, $type_image_tmp) = getimagesize($from);
	
	if ((intval($width_image_tmp)==0) or (intval($height_image_tmp)==0) ) {
		$ErrorMsg3 = "فایل آپلودی تصویر نمی باشد";
		return;
	}
}
$Pfile='';
//----------------------------------
$ddate=new Date();				
$nowdatetimeFile = $ddate->format("%Y-%m-%d_%H-%M-%S");		
//------------------------------------
if ($after_name=='') $after_name = '_'. rand(1000,9999);
//if ($default_name=='') {	$nowdatetimeFile = $ddate->format("%Y%m%d");  $after_name='';}
 $Target = $default_name . $nowdatetimeFile . $after_name . '.' . $Ext;
move_uploaded_file($from, $upload_path.$Target);
$NameAfterUpload= $Pfile . $Target;

?>