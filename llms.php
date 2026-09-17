<?php
$RunTop=1;
include('topmain.php');

// ۱. تنظیم هدر برای خروجی متنی خام و یونیکد
header("Content-Type: text/plain; charset=utf-8");

// ۳. تعریف دامنه اصلی سایت
$siteUrl = $sitenamelink;

// ۴. چاپ هدر اصلی فایل (معرفی سایت به هوش مصنوعی)
echo "Generated dynamically, this is an llms.txt file designed to help LLMs better understand and index this website.\n\n";
echo "# نام فروشگاه شما: $title \n\n";

echo "## Sitemaps\n";
echo "[XML Sitemap]($siteUrl/sitemap.xml): Includes all crawlable and indexable pages.\n\n";


// ۵. استخراج و چاپ مقالات وبلاگ
echo "## نوشته‌ها و مقالات وبلاگ\n";
$countrec=50;
//-----------------------------
$bank1='weblog'; 	
$shart = " where $bank1.active=2 $shartdatetimeWeblog ";
$sortquery = " order by date desc, time desc ";
$basequary = "select * from $bank1  $shart $sortquery limit 0, $countrec ";
$counter=0;
$query=pdo_query($basequary,'',0);
while ($row=pdo_fetch($query))  {
	$counter++;
	$tetr = trim(tetr2link($row['tetr']));
	$title = $row['tetr'];
	$leds = $row['leds'];
	if ($leds!='') {
		$summary = $leds;
	}else{
		$summary = mb_substr(CleanText($row['matn']), 0, 250) . "..."; // خلاصه ۱۵۰ کاراکتری  از متن
	}
	$url = $siteUrl . "/weblog/$row[id]/$tetr";
	echo "- [$title]($url)\n  **توضیحات:** $summary\n\n";	
}
if ($counter==0) {echo "هنوز مقاله‌ای منتشر نشده است.\n";}

// ۶. استخراج و چاپ محصولات فروشگاه
echo "## محصولات فروشگاه\n";
//-----------------------------
$counter=0;
$bank1='product'; 	
$bank2='product_price'; 	
$shart = " where $bank1.active=2 and $bank1.isdeleted=0 $shartdatetime ";
$sortquery = " order by $bank1.pdate desc, $bank1.ptime desc ";

$basequary = "select $bank1.*, $bank1.tetr, $bank1.leds, $bank1.photo_product, $bank1.spectioal, $bank1.best_saller, $bank1.vote_sum, $bank1.vote_count, $bank2.id as id_price, $bank2.price1, $bank2.price2, $bank2.cash, $bank2.cash_unlimited from `$bank1` inner join `$bank2` on $bank1.id=$bank2.id_product $shart group by $bank1.id  $sortquery limit 0, $countrec";

$query=pdo_query($basequary,0);
while ($row=pdo_fetch($query))  {
	$counter++;
	$tetr = tetr2link($row['tetr']);
	$url = $siteUrl . "/product/$row[id]/$tetr";
	
	$title 	= $row['tetr'];
	$leds 	= $row['leds'];
	$price1 = $row['price1'];
	$price2 = $row['price2'];
	$price0 = ($price2==0) ? $price1 : $price2;
    $price = number_format($price0) . $price_unit;
	
	if ($leds!='') {
		$summary = $leds;
	}else{
		$summary = mb_substr(CleanText($row['matn']), 0, 250) . "..."; // خلاصه ۱۵۰ کاراکتری  از متن
	}
    echo "- [$title]($url): قیمت $price\n  **توضیحات:** $summary\n\n";
}
if ($counter==0) { echo "هنوز محصولی ثبت نشده است.\n";}


//-------------------------------------
function tetr2link($tetr) {
	$search = array('/&lsquo;/u', '/&rsquo;/u', '/&ldquo;/u', '/&rdquo;/u', '/&mdash;/u','/&zwnj;/u','/&raquo;/u','/&laquo;/u','/&quot;/u','/&lrm;/u','/&/u');
	$tetr = preg_replace($search, '_', $tetr); 
	$tetr = str_replace(' ','_',str_replace('/','_',trim($tetr)));
	$tetr  =htmlspecialchars($tetr);
	return $tetr;
}
//-------------------------------------
function CleanText($input) {
	
	// ۱. ابتدا تگ‌های HTML را حذف کنید
	$clean_text = strip_tags($input);

	// ۲. کدهای آنرمال مثل &zwnj; را به نیم‌فاصله واقعی یا کاراکترهای استاندارد تبدیل کنید
	$clean_text = html_entity_decode($clean_text, ENT_QUOTES, 'UTF-8');

	// ۳. (اختیاری) اگر ترجیح می‌دهید نیم‌فاصله‌ها کلاً تبدیل به فاصله‌ی معمولی شوند تا در مارک‌داون چسبیدگی ایجاد نکنند:
	$clean_text = str_replace("\xE2\x80\x8C", " ", $clean_text); // حذف کاراکتر مخفی نیم‌فاصله و تبدیل به فاصله

	// ۴. حالا با خیال راحت متن تمیز شده را تا ۲۵۰ کاراکتر برش بزنید
	return $clean_text;
}