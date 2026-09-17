<?php
ob_start();
$RunTop=1;
include('topmain.php');
$output_check = ob_get_clean();
$primary_color = "#266B2D"; // رنگ برند سبز فعلا برای عطاری 110
$logofile 	 = $row_setting['logofile'];  
$manifest_icons = [];
if ($logofile!='')  {
	$pathfull = $_SERVER['DOCUMENT_ROOT'].'/'.$upload_path_main. $logofile;
	$mime_type = mime_content_type($pathfull);	
	$image_size = @getimagesize($pathfull);
	if ($image_size !== false) {	
		$logo_size_string = $image_size[0] . 'x' . $image_size[1];
	} else {
		$logo_size_string='512x512';
	}
	$logofile 	 = $upload_path_main.$logofile;
//	echo "<BR>mime_type=$mime_type <BR> logofile=$logofile <BR> pathfull=$pathfull";	exit;
	$manifest_icons[] = [
            "src" => $logofile,
            "sizes" => $logo_size_string, // مرورگر خودش اندازه را تغییر می دهد با این پیش فرض
            "type" => $mime_type
        ];
}

// =======================================================
// ۱. ارسال هدر صحیح: این حیاتی است! مرورگر باید بداند که محتوای ارسالی JSON است.
header('Content-Type: application/manifest+json; charset=utf-8');

// ۲. ساختاردهی آرایه Manifest
$manifest_data = [
    "name" => $row_setting['tetrsite'],
    "short_name" => $row_setting['shop_name'],
	"dir" => "rtl",
	"start_url" => "/?utm_source=pwa",
	"lang" => "fa-IR",
	"scope" => "/",
	"orientation" => "portrait-primary",
    "display" => "standalone", // نحوه نمایش (بدون نوار مرورگر)
    "background_color" => $primary_color, // رنگ پس‌زمینه هنگام Splash Screen
    "theme_color" => $primary_color, // رنگ نوار بالایی اپلیکیشن
    "description" => $row_setting['meta'],
];
if (!empty($manifest_icons)) {
    $manifest_data["icons"] = $manifest_icons;
}
// ۳. تبدیل به JSON و چاپ
echo json_encode(
    $manifest_data, 
    JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT
);
exit;
?>