<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>	 <meta http-equiv="Content-Type" content="text/html; charset=utf-8">  </head>


<?php 
	if (!isset($contor_file) ) { 	$contor_file = '-1';  }
	if (!isset($upload_path) ) { 		include('../sitename.php');		$upload_path = '../'.$vsitename."/";  }
	if (!isset($userfilename) ) {  $userfilename='userfile';}
	if (!isset($default_name_after_upload) ) {  $default_name_after_upload='';}
	if (!isset($tablename_folder) ) {  $tablename_folder='';}
	
	if ($contor_file==-1) {
		$filename=strtolower($_FILES[$userfilename]['name']);
		$from=$_FILES[$userfilename]['tmp_name'];
	} else {
		$filename=strtolower($_FILES[$userfilename]['name'][$contor_file]);
		$from=$_FILES[$userfilename]['tmp_name'][$contor_file];
	}
	if (!isset($allowedfiletypes) ) {  $allowedfiletypes='jpg,jpeg,gif,png,bmp,ico';}
	$allowed_filetypes=explode(',',$allowedfiletypes);
	list($width_image_tmp, $height_image_tmp, $type_image_tmp) = getimagesize($from);
	if (!isset($max_filesize)) {$max_filesize = 104857600 ; } // Maximum filesize in BYTES (currently 100MB).
	if ($max_filesize==0) {$max_filesize = 104857600 ; } // Maximum filesize in BYTES (currently 100MB).

	if ( !is_uploaded_file($from) ) {
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
			if($filesizetmp > $max_filesize) {
				echo "<script>alert('حجم فایل آپلودی [$filesizetmp بایت] بیش از سقف تعیین شده [$max_filesize بایت] می باشد');</script>";
				echo '<script>history.back(-1);</script>';
				exit;
			}

			if(!is_dir($upload_path)) {
				mkdir($upload_path , 0777);
			}
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
			
			$file = basename($filename,'.'.$Ext); 
			require_once("../includee/date2.php");			$ddate=new Date();				$nowdatetime = $ddate->format("%Y-%m-%d_%H-%M-%S");		
			$Pfile='';
			$basepath = $upload_path;

			if (substr($upload_path, -7)=='/image/') {
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
				$basepath = $upload_path2;
			}
			//-------
				$file = $tablename_folder.$nowdatetime.$default_name_after_upload;
				$uniqID=date('U');
				$to = $file.'.'.$Ext;
				if (file_exists($basepath .$to)) {				$to = $file.'_'.$uniqID.'.'.$Ext;			}
			//--------
			move_uploaded_file($from, $basepath.$to);
			$UploadFileName= $Pfile.$to; //$to;

	
?>