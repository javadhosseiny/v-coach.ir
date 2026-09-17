<!DOCTYPE>
<html>
<head>	 <meta http-equiv="Content-Type" content="text/html; charset=utf-8">  </head>
<?php 
		if (!isset($contor_file) ) { 	$contor_file = '-1';  }
		if (!isset($upload_path) ) { 	$upload_path = '/'.$vsitename."/";  }
		if (!isset($userfilename) ) {  $userfilename='userfile';}
		if (!isset($default_name_after_upload) ) {  $default_name_after_upload='';}
		if (!isset($tablename_folder) ) {  $tablename_folder='';}
		if (!isset($typefield)) {$typefield=7;}
		if ( ($typefield!=7) and ($typefield!=23) ) {echo 'error';exit;}
		
		if ($contor_file==-1) {
			$filename=strtolower($_FILES[$userfilename]['name']);
			$from=$_FILES[$userfilename]['tmp_name'];
		} else {
			$filename=strtolower($_FILES[$userfilename]['name'][$contor_file]);
			$from=$_FILES[$userfilename]['tmp_name'][$contor_file];
		}
//		echo '<br>from='.$from;
		if (!isset($allowedfiletypes) ) {  $allowedfiletypes='jpg,jpeg,gif,png,bmp,ico';}
		$allowed_filetypes=explode(',',$allowedfiletypes);
		if (!isset($max_filesize)) {$max_filesize = 504857600 ; } // Maximum filesize in BYTES (currently 5MB).
		if ($max_filesize==0) {$max_filesize = 504857600 ; } // Maximum filesize in BYTES (currently 5MB).
		if (is_uploaded_file($from)) {
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
				@mkdir($upload_path , 0777);
			}
			if(!is_dir($upload_path)) {
				echo "<script>alert('متأسفم مسیر پیش فرض آرشیو تصاویر وجود ندارد ')</script>";
				echo '<script>history.back(-1);</script>';
				exit;
			}
      		if(!is_writable($upload_path)) chmod($upload_path, 777);
      		if(!is_writable($upload_path)) {
				echo "<script>alert('متأسفم مسیر پیش فرض آرشیو تصاویر قابل ذخیره سازی فایل ها را ندارد')</script>";
				echo '<script>history.back(-1);</script>';
				exit;
			}
			list($width_image_tmp, $height_image_tmp, $type_image_tmp) = getimagesize($from);
			$width_image_tmp = intval($width_image_tmp);		
			$height_image_tmp = intval($height_image_tmp);
//			echo "width_image_tmp =  $width_image_tmp ---- height_image_tmp = $height_image_tmp --- $type_image_tmp";
			if ( ($width_image_tmp==0) or ($height_image_tmp==0) ) {
				echo "<script>alert('متأسفم فایل آپلود شده تصویر نمی باشد')</script>";
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
			require_once("includee/date2.php");			
			$ddate2=new Date();				
			$nowdatetimenow = $ddate2->format("%Y-%m-%d_%H-%M-%S");		
			$Pfile='';
//			$lastdir = substr($upload_path, -7);
			$basepath = $upload_path;


			$file = $tablename_folder.$nowdatetimenow.$default_name_after_upload;
			$uniqID=date('U');
			$to = $file.'.'.$Ext;
			if (file_exists($basepath .$to)) {				$to = $file.'_'.$uniqID.'.'.$Ext;			}
//			echo "<div align=left dir=ltr> from-->$from / target---> $basepath$to </div>";
			move_uploaded_file($from, $basepath.$to);
			$target_file = $basepath.$to;
			if (!file_exists($target_file)) {
				echo "<script>alert('سیستم قادر به کپی فایل تصویر در مقصد نهایی نگردید')</script>";
				echo '<script>history.back(-1);</script>';
			}
			$imagefile= $Pfile.$to; //$to;
		}
		else{
			echo "<script>alert('".$_FILES[$userfilename]['error']."اشکال شماره  ".CHR(13).'سيستم قادر به آپلود اين  فايل نمي باشد'."')</script>";
			echo '<script>history.back(-1);</script>';
			exit;
		}

?>