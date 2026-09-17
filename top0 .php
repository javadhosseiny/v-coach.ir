<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="robots" content="follow, index, max-snippet:-1, max-video-preview:-1, max-image-preview:large"/>	
	<link rel="manifest" href="/manifest.json">
    <!-- setting ----------------------------------->
	<?php
	echo "
	<title>$title_main</title>
	<meta property=\"og:title\" content='$title_main'/> 
	<meta property=\"og:site_name\" content='$title_main'/> 
	<meta property=\"og:type\" content='website' /> 
	<meta property=\"og:url\" content='$main_url_decode' />	  
	<meta name=\"twitter:title\" content=\"$title_main\" >
	<link rel=\"canonical\" href='$canonical' />
	";
	if ($logopage!='') {
	echo "<meta property=\"og:image\" content=\"$logopage\" > 
	  <meta itemprop=\"image\" content=\"$logopage\">
	  <link rel=\"image_src\" href=\"$logopage\" >			  
	  <meta name=\"twitter:card\" content=\"$logopage\" > 
	  <meta name=\"twitter:image:src\" itemprop=\"image\" property=\"og:image\" content=\"$logopage\" > 
	  ";
	}
	if ($meta!='') { 
	echo "<meta name=\"meta\" content=\"$meta\" > 
	  <meta name=\"description\" content=\"$meta_dsc\" > 
	  <meta property=\"og:description\" content=\"$meta_dsc\" > 
	  <meta name=\"twitter:description\" content=\"$meta_dsc\" > 
	  \n";
	}
	// در وبلاگ می خواند - در تنظیمات صفحه اصلی می خواند - در صفحه محصولات می خواند
	if ($keyword!='') { 
		$keyword = str_replace(chr(13).chr(10),",",$keyword);  
		echo "<meta name=\"keywords\" content=\"$keyword\" > \n";
	}
	if ($row_setting['iconfile']!='') {	
		$iconfile = $upload_path_main.$row_setting['iconfile'];
		echo "<link rel='icon' type='image/png' sizes='192x192' href='$iconfile'>\n"; 	
	}
	// G-AAXXXXAAXA --> google analytics 
	if ($row_setting['googlecode']!='') { 
	echo "
	<script async src=\"https://www.googletagmanager.com/gtag/js?id=$row_setting[googlecode]\"></script>
	<script>
	window.dataLayer = window.dataLayer || [];
	function gtag(){dataLayer.push(arguments);}
	gtag('js', new Date());
	gtag('config', '$row_setting[googlecode]');
	</script>
	";
	}
	
	
	//e.g. <meta name="google-site-verification" content="......">
	if ($row_setting['googlemeta']!='') { 		echo "$row_setting[googlemeta]";	}
	echo $row_setting['scriptcode1'];	
	//--------------
	schema_code();
	?>
    <!-- font---------------------------------------->
    <link rel="stylesheet" href="/assets/css/vendor/font-awesome.min.css?1">
    <link rel="stylesheet" href="/assets/css/vendor/materialdesignicons.css">
    <!-- plugin-------------------------------------->
    <link rel="stylesheet" href="/assets/css/vendor/bootstrap.css">
	
    <link rel="stylesheet" href="/assets/css/vendor/bootstrap-extended.css?1">	
    <link rel="stylesheet" href="/assets/css/vendor/colors.css">	
	
    <link rel="stylesheet" href="/assets/css/vendor/owl.carousel.min.css">
    <link rel="stylesheet" href="/assets/css/vendor/nice-select.css">
    <link rel="stylesheet" href="/assets/css/vendor/jquery.jqZoom.css">
    <link rel="stylesheet" href="/assets/css/vendor/sweetalert2.min.css">
	<link rel="stylesheet" href="/vendor/toastr/toastr.css" > 	
	<link rel="stylesheet" href="/assets/css/vendor/select2.min.css">	
    <!-- main-style---------------------------------->
    <link rel="stylesheet" href="/assets/css/main.css?0">
    <link rel="stylesheet" href="/assets/css/responsive.css?0">
    <link rel="stylesheet" href="/assets/css/starvote.css">
	<script src="/assets/js/starvote.js?123"></script>
	<style>
    .actions {
        opacity: 1 !important;
        position: unset !important; 
    }
	.product-box .actions ul {
        margin-top: 20px;
		margin-bottom: 0px;
	}
	.product-box .actions ul li {
        opacity: 1 !important;
	}
	</style>
	
</head>
<!-- file js---------------------------------------------------->
<script src="/assets/js/vendor/jquery-3.2.1.min.js"></script>
<script src="/assets/js/vendor/bootstrap.js"></script>
<!-- plugin----------------------------------------------------->
<script src="/assets/js/vendor/owl.carousel.min.js"></script>
<script src="/assets/js/vendor/jquery.countdown.js"></script>
<script src="/assets/js/vendor/jquery.nice-select.min.js"></script>
<script src="/assets/js/vendor/jquery.jqZoom.js"></script>
<script src="/assets/js/vendor/sweetalert2.all.min.js"></script>
<!-- main js---------------------------------------------------->
<script src="/vendor/toastr/toastr.min.js"></script> 
<script src="/assets/js/scripts.js?0"></script>
<script src="/assets/js/function.js?0"></script>
<script src="/assets/js/main.js?0"></script>
<script src="/assets/js/vendor/select2.full.min.js"></script>
<script>
<!-- check service-workder.js for pwa export -------------------------------->

if ('serviceWorker' in navigator) {
  window.addEventListener('load', () => {
    navigator.serviceWorker.register('/service-worker.js')
      .then(registration => {
        console.log('Service Worker registered: ', registration);
      })
      .catch(error => {
        console.log('Service Worker registration failed: ', error);
      });
  });
}
</script>
<body>
<!-- header-------------------------------->
<header class="header-main">
	<div class="d-block">
		<section class="h-main-row d-flex align-items-center justify-content-between flex-wrap">
			<!-- بخش راست: لوگو و سرچ -->
			<div class="col-lg-8 col-md-7 col-7 pr-0 pl-0">
				<div class="header-right d-flex align-items-center justify-content-between w-100">
					<!-- لوگو (راست) -->
					<div class="header-logo text-right">
						<a href="/">
							<?php echo "<img src='$logofile' style='max-height:50px; width:auto;' alt='$title'>"; ?>
						</a>
					</div>
					<!-- کادر جستجو (چسبیده به چپِ این بخش - فقط دسکتاپ) -->
					<div class="d-none d-md-block header-search-wrapper mr-auto ml-2">
						<div class="header-search row text-right mb-0">
							<div class="header-search-box">
								<form action="/search/" class="form-search" method='post'>
									<input type="search" class="header-search-input" name="varsearch" placeholder="عبارت جستجو ....">
									<div class="action-btns">
										<button class="btn btn-search" type="submit">
											<img src="/assets/images/search.png" alt="search">
										</button>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>

			<!-- بخش چپ: ورود / ثبت‌نام / آواتار -->
			<div class="col-lg-4 col-md-5 col-5 pl-0 pr-0">
				<div class="header-left d-flex align-items-center justify-content-end">
					<div class="header-account text-left">
						<div class="d-block">
							<div class="account-box">
								<div class="nav-account d-flex align-items-center justify-content-end pl-0" style="white-space: nowrap;  direction: ltr;">
									<?php 
									$TagPhoto = empty($userphoto) ? "/assets/images/man.png" : $userphoto;
									echo "<span class='icon-account ml-1'><img src='$TagPhoto' class='avator'></span>";

									if (!$ok_cookie) { 
										echo "
										<a class='title-account ml-1' href='/register/'>شروع مسیر</a>
										 | 
										<a class='title-account ml-1' href='/login/'>ورود</a>
										"; 
										
									} else { 
									?>
									<div class="dropdown d-inline-block">
										<span class='title-account dropdown-toggle' style='cursor:pointer;' data-toggle='dropdown'>حساب کاربری</span>
										<div class="dropdown-menu dropdown-menu-left">
											<div class='title-account dropdown-header'><?php echo $usernamefarsi;?></div>
											<ul class="account-uls mb-0 list-unstyled">
												<li class="account-item"><a href="/profile/" class="account-link">پروفایل</a></li>
												<li class="account-item"><a href="/mypassword/" class="account-link">تغییر رمز</a></li>
												<li class="account-item"><a href="/myorder/" class="account-link">سفارشات من</a></li>
												<li class="account-item"><a href="/myaddres/" class="account-link">آدرس های من</a></li>
												<li class="account-item"><a href="/myfavorite/" class="account-link">علاقه مندی ها</a></li>
												<li class="account-item"><a href="/logout" class="account-link">خروج</a></li>
											</ul>
										</div>
									</div>
									<?php } ?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>

		<!-- سطر منو و سبد خرید -->
		<nav class="header-main-nav">
			<div class="d-block">
				<div class="row align-items-center" style='margin-right: 15px; margin-left: 0px;'>
					<?php 
					$col1 = 'col-12'; 
					echo "<div class='$col1' style='flex: 1; max-height:60px;'>";
					ListTreeViewTopMenu($GroupMenu, 0, 0, -1, 0); 
					echo "</div>";
					?>
					<div class='col-3 d-none'>
						<ul>
							<li class="divider-space-card">
								<div class="header-cart-basket">
									<a href="/shoppingcard/" class="cart-basket-box">
										<span class="icon-cart">
											<i class="mdi mdi-shopping"></i>
										</span>
										<span class="title-cart">سبد خرید</span>
										<span class="price-cart" id='price-1' style='direction: rtl;'></span>
										<span class="count-cart" id='count-1'></span>
									</a>
									<div class="widget-shopping-cart" id='style-0'>
										<div class="widget-shopping-cart-content">
											<div class="wrapper">
												<div class="scrollbar" id="style-1">
													<div class="force-overflow">
														<ul class="product-list-widget" id="style-2"></ul>
													</div>
												</div>
											</div>
											<div class='mini-card-total'>
												<strong>قیمت کل : </strong>
												<span class='price-total' id='price-2'></span>
											</div>
											<div class="mini-card-button">
												<a href="/shoppingcardfinal/" class="card-checkout">تسویه حساب</a>
											</div>
										</div>
									</div>
								</div>
							</li>
						</ul>
					</div>
				</div>
			</div>
		</nav>

		<!-- سایدبار موبایل -->
		<nav class="sidebar">
			<div class="nav-header">
				<div class="header-cover"></div>
				<div class="logo-wrap logo-icon">
					<a href="/">
						<?php echo "<img src='$logofile' style='max-height:50px;' alt='$title'>"; ?>
					</a>
				</div>
			</div>
			<?php 
			ListTreeViewSideMenu($GroupAll, 0, 0, -1, 0); 
			echo "
			</li>
			<li id='pwa-install-menu-item' style='display:none;'>
				<button type='button' class='btn btn-sm btn-outline-success' id='install-pwa-button'
						style='cursor: pointer; padding-right: 22px; font-size: 0.8em;'>
					نصب اپلیکیشن <i class='fa fa-download mr-2'></i> 
				</button>
			</li>			
			</ul>";
			?>
		</nav>
		<div class="nav-btn nav-slider">
			<span class="linee1"></span>
			<span class="linee2"></span>
			<span class="linee3"></span>
		</div>
		<div class="overlay"></div>

		<!-- منوی شناور موبایل -->
		<div class="bottom-menu-joomy d-none">
			<ul class="mb-0">
				<li>
					<a href="/">
						<i class="mdi mdi-home"></i>
						صفحه اصلی
					</a>
				</li>
				<li>
					<a href="#">
						<div class="nav-btn nav-slider">
							<i class="mdi mdi-menu" aria-hidden="true"></i>
						</div>
						گروه ها
					</a>
				</li>
				<li>
					<a href="/shoppingcard/">
						<i class="mdi mdi-cart"></i>
						سبد خرید
						<div class="shopping-bag-item" id='count-basket-bottom'></div>
					</a>
				</li>
				<li>
					<a href="/search/">
						<i class="mdi mdi-magnify"></i>
						جستجو
					</a>
				</li>
				<li>
					<?php if ($ok_cookie) { ?>
					<a href="/profile/">
						<i class="mdi mdi-account"></i>
						حساب کاربری
					</a>
					<?php } else { ?>
					<a href="/login/">
						<i class="mdi mdi-account"></i>
						ورود
					</a>
					<?php } ?>
				</li>
			</ul>
		</div>
	</div>
</header>

<script>
$(document).ready(function(){
	updateCartItems();
	<?php //echo "updateCartItems();";	?>
});
document.addEventListener('DOMContentLoaded', () => {
    let deferredPrompt;
    
    // ۱. ارجاع به المان ها بر اساس ID
    const installButton = document.getElementById('install-pwa-button');
    const installMenuItem = document.getElementById('pwa-install-menu-item'); // آیتم منوی والد (<li>)

    // مطمئن می شویم که هر دو المان وجود دارند
    if (!installButton || !installMenuItem) {
        console.warn('PWA Install elements not found. Check IDs: install-pwa-button and pwa-install-menu-item');
        return;
    }

    // =========================================================
    // الف) گوش دادن به رویداد قبل از نصب (beforeinstallprompt)
    // =========================================================

    window.addEventListener('beforeinstallprompt', (e) => {
        // ۱. جلوگیری از نمایش خودکار پیام مرورگر
        e.preventDefault(); 
        
        // ۲. ذخیره رویداد برای استفاده بعدی
        deferredPrompt = e;
        
        // ۳. نمایش آیتم منو (<li>)
        // آیتم به طور پیش فرض در HTML مخفی شده است (display: none)
        installMenuItem.style.display = 'block'; 
        console.log('PWA Install button shown in menu.');
    });

    // =========================================================
    // ب) فراخوانی رویداد نصب هنگام کلیک کاربر
    // =========================================================
    
    installButton.addEventListener('click', async () => {
        
        if (!deferredPrompt) {
            console.warn('Deferred install prompt is null. PWA may already be installed or conditions not met.');
            return;
        }

        // ۱. نمایش پیام نصب مرورگر
        deferredPrompt.prompt();
        
        // ۲. انتظار برای پاسخ کاربر
        const { outcome } = await deferredPrompt.userChoice;
        
        console.log(`User response to the install prompt: ${outcome}`);

        // ۳. مخفی کردن آیتم منو (<li>) پس از پاسخ کاربر (حتی اگر رد شود)
        if (outcome === 'accepted' || outcome === 'dismissed') {
            installMenuItem.style.display = 'none';
        }
        
        // ۴. متغیر ذخیره شده را خالی می کنیم تا دوباره استفاده نشود
        deferredPrompt = null; 
    });
    
    // =========================================================
    // ج) مدیریت رویداد بعد از نصب (appinstalled)
    // =========================================================
    
    window.addEventListener('appinstalled', (e) => {
        console.log('PWA was successfully installed.');
        // مخفی کردن آیتم منو در صورت نصب موفق
        installMenuItem.style.display = 'none';
        deferredPrompt = null;
    });

});
</script>
<?php 
if ($ErrorMsg!='') { 
	echo "<div class='row'></div>"; 
	ShowMessage($ErrorMsg,0,'bg-danger'); 
	include('bottom.php'); 
	exit; 
}
?>



<?php
// منوهای آیکون همبرگر
function ListTreeViewSideMenu($array, $currentParent, $currLevel = 0, $prevLevel = -1, $oldid='') {
	global $upload_path_main;
	if (!is_array($array)) return ;

	foreach ($array as $categoryId => $category) {
		if ($currentParent == $category['PARENT']) {
			$OkChild = FoundChild($array,$category['ID']);
			$idd = 'list'.$category['ID'];
			$link = $category['LINK'];
			$class_ul='';		$class_li='';		$class_a='';		$IconTag='';	$DivTag='';
			if ($currLevel==0) {
				$class_ul=" class='nav-categories ul-base' "; 	// tag ul
				if ($OkChild) {
					$class_a ='class="collapsed" type="button" data-toggle="collapse" data-target="#'.$idd.'"
                            aria-expanded="false" aria-controls="'.$idd.'"'; 			
							
					$class_li='';
					$IconTag = '<i class="mdi mdi-chevron-down"></i>';
					$DivTag = '<div id="'.$idd.'" class="collapse" aria-labelledby="heading'.$idd.'"
                            data-parent="#accordionExample" style="">';
					$link = '#';
				}
			}
			if ($currLevel==1) {
				if ($OkChild) {
					$class_li=' class="has-sub" ';
					$class_a = 'class="category-level-2"';
					$link = '#';
				}
			}
			if ($currLevel==2) {
				$class_a = 'class="category-level-3"';
			}
			
			if ($currLevel > $prevLevel) 
				echo "\r\n <ul $class_ul> \r\n"; 
			if ($currLevel == $prevLevel) {
//				if (($currLevel==0) and ($OkChild)) { echo "</div> \r\n ";}
				echo " </li>  \r\n";
			}
			
			$target		= '';
			if ($category['NEWPAGE']==1) $target		= " target = '_blank' ";
			$LinkPhoto = '';
			if ($category['PHOTO']!='') $LinkPhoto = "<img src='$category[PHOTO]' alt='$category[NAME]' width='22px'>";
			$LinkPhoto='';
			echo "
			<li $class_li>
				<a href='$link' $class_a $target>
					$IconTag
					$LinkPhoto
					$category[NAME]
					
				</a>
				$DivTag
			";
			
			if ($currLevel > $prevLevel) { $prevLevel = $currLevel; }
			$currLevel++; 
			ListTreeViewSideMenu($array, $categoryId, $currLevel, $prevLevel, $idd);
			$currLevel--;               
		}
	}
//	echo "currLevel=$currLevel ---- prevLevel== $prevLevel --- ";
	if (($currLevel == $prevLevel) and ($currLevel!=0) ) echo " </li>\r\n </ul> \r\n";
}   
//منوهای بالای سایت
function ListTreeViewTopMenu($array, $currentParent, $currLevel = 0, $prevLevel = -1, $oldid='') {
	global $upload_path_main;
	if (!is_array($array)) return ;

	foreach ($array as $categoryId => $category) {
		if ($currentParent == $category['PARENT']) {
			$OkChild = FoundChild($array,$category['ID']);
			
			$class_ul='';		$class_li='';		$class_a='';		$IconTag='';
			if ($currLevel==0) {
				$class_ul='menu-ul mega-menu-level-one'; 	// tag ul
				$class_li='menu-item'; 					// tag li
				$class_a ='current-link-menu'; 			// tag a href
				if ($OkChild) {
					$class_li='menu-item  nav-overlay';
					$IconTag = '<i class="fa fa-angle-down"></i>';
				}
			}
			if ($currLevel==1) {
				$class_ul='sub-menu is-mega-menu-small';
				$class_li='menu-mega-item menu-item-type-mega-menu item-small';
				$class_a='mega-menu-link';
				if ($OkChild) $IconTag = '<i class="fa fa-angle-left"></i>';
			}
			if ($currLevel==2) {
				$class_ul='sub-menu is-mega-menu-small-three';
				$class_li='menu-mega-item menu-item-type-mega-menu item-small-three';
				$class_a='';
			}
			
			if ($currLevel > $prevLevel) 
				echo "\r\n <ul class='$class_ul'> \r\n"; 
			if ($currLevel == $prevLevel) echo " </li>  \r\n";
			$idd = 'list'.$category['ID'];
			
			$LinkPhoto = '';
			if ($category['PHOTO']!='') $LinkPhoto = "<img src='$category[PHOTO]' alt='$category[NAME]' width='22px'>";
			echo "
			<li class='$class_li'>
				<a href='$category[LINK]' class='$class_a'>
					$LinkPhoto
					$category[NAME]
					$IconTag
				</a>
			";
			
			if ($currLevel > $prevLevel) { $prevLevel = $currLevel; }
			$currLevel++; 
			ListTreeViewTopMenu($array, $categoryId, $currLevel, $prevLevel, $idd);
			$currLevel--;               
		}
	}
	if (($currLevel == $prevLevel) and ($currLevel!=0) ) echo " </li>\r\n </ul> \r\n";
}   

function schema_code() {
	global $schema, $sitenamelink, $logopage, $upload_path_main, $row_setting, $main_url, $row_content;
	global $GroupWeblog, $GroupProduct, $ListPrice;
	if ($schema=='') return ;
//	echo "schema=$schema";
	
	$site_url 	= $sitenamelink;
	$site_name 	= $row_setting['tetr_firstpage'];
	$shop_name	= $row_setting['shop_name'];
	$logo_url 	= $sitenamelink.$logopage;
	$meta 		= $row_setting['meta'];
	$price_unit = $row_setting['price_unit'];
	
	if ($schema=='Product') {
		$id_product	= $row_content['id'];
		$title 		= $row_content['tetr'];
		$url 		= urldecode($main_url);
		$image_url	= $row_content['photo_product'];
		if ($image_url=='') {
			$image_url = $logo_url;
		}else{
			if (!validateURL($image_url)) $image_url = $sitenamelink. $upload_path_main. $image_url;
		}
		$description= $row_content['leds'];
		if ($description=='') $description=$meta;
		$id_topics	= $row_content['id_topics'];
		$ListTopics = array_filter(explode(',',$id_topics));
		//--------------------
		if (count($ListPrice)==0) { $price_product='0'; $availability='0';} else{
			$temp 	= $ListPrice[0];
			$idval	= $temp['id'];				
			$price1	= $temp['price1'];			
			$price2	= $temp['price2'];
			$cash 	= $temp['cash'];			
			$cash_unlimited=$temp['cash_unlimited'];
			if ($cash_unlimited==1) $cash=999999999;
			if ($price2>0) { $price_product   = $price2;}else{$price_product   = $price1;}
			$availability = $cash;
			if ($price_unit=='تومان') $price_product = $price_product * 10;
		}
		
		//--------------------
		
		$product_schema = [
				"@context" => "https://schema.org", "@type" => "Product", "name" => $title, "url" => $url,"image" => $image_url,  "description" => $description, "sku" => $id_product,
				"brand" => ["@type" => "Brand","name" => $shop_name],
				"offers" => [
					"@type" => "Offer", "url" => $url, "priceCurrency" => 'IRR',"price" => (string)$price_product,
					"availability" => (string)$availability, "itemCondition" => "https://schema.org/NewCondition",
					"shippingDetails" => [
						"@type" => "OfferShippingDetails",
						"shippingRate" => [ "@type" => "MonetaryAmount","value" => 0,  "currency" => 'IRR' ],
						"shippingDestination" => [ "@type" => "DefinedRegion","addressCountry" => "IR" ],
						"deliveryTime" => [ 
							"@type" => "ShippingDeliveryTime",
							"handlingTime" => [ "@type" => "QuantitativeValue", "minValue" => 1, "unitCode" => "DAY"]
						]
					]
				]
			];
			// --- ب) بخش امتیاز و نظرات (AggregateRating) ---
			if ($row_content['vote_count']!=0) {
				$product_schema['aggregateRating'] = [
					"@type" => "AggregateRating",
					"ratingValue" => (string)($row_content['vote_sum']/$row_content['vote_count']),
					"reviewCount" => (int)$row_content['vote_sum']
				];
			}
			
			// --- ۲. ساختار مسیردهی (BreadcrumbList) ---
			$ListTopicsNew = array_map('intval', $ListTopics);
			sort($ListTopicsNew);
			$breadcrumb_items = [];
			foreach ($ListTopicsNew as $index => $breadcrumb) {
				$MainUrl = $GroupProduct[$breadcrumb];
				$url_topic = $site_url . "/CategoryProduct/".	$MainUrl['ID'];	
				$breadcrumb_items[] = [
					"@type" => "ListItem", "position" => $index+1 , "name" => $MainUrl['NAME'], "item" => $url_topic
				];
			}
			
			$breadcrumb_schema = [ "@type" => "BreadcrumbList", "itemListElement" => $breadcrumb_items];

			$schema_array = [
				"@context" => "https://schema.org",
				"@graph" => [
					$product_schema,
					$breadcrumb_schema
				]
			];
	
	}
	if (($schema=='NewsArticle') or ($schema=='BlogPosting') ) {
		$title 		= $row_content['tetr'] ?? '';
		$date		= $row_content['date'] ?? '';
		$time		= $row_content['time'] ?? '';
		$url 		= $main_url;
		$published	= jalaliToG($date).'T'.$time;
		

		
		$image_url	= $row_content['photo_matn'];
		if ($image_url=='') {
			$image_url = $logo_url;
		}else{
			if (!validateURL($image_url)) $image_url = $sitenamelink. $upload_path_main. $image_url;
		}
		$description= $row_content['leds'];
		if ($description=='') $description=$meta;

		$id_topics	= $row_content['id_topics'];
		$ListTopics = array_filter(explode(',',$id_topics));
		$FirstIndex = array_keys($ListTopics)[0];
		$FirstTopic = $ListTopics[$FirstIndex];
		$category_url  = $site_url . "/CategoryWeblog/".	$FirstTopic;	
		$category_name = $GroupWeblog[$FirstTopic]['NAME'];
	
	// ساختار اسکیمای اصلی (BlogPosting)
		$blog_posting = [
			"@type" => $schema, "headline" => $title, "image" => $image_url, "datePublished" => $published,	"author" => ["@type" => "Organization","name" => $shop_name],			
			"publisher" => [
				"@type" => "Organization", "name" => $site_name,
					"logo" => ["@type" => "ImageObject","url" => $logo_url]
			],
			"mainEntityOfPage" => ["@type" => "WebPage","@id" => $url],
			"description" => $description
		];

		// ساختار اسکیمای مسیر (BreadcrumbList)
		$breadcrumb_list = [
			"@type" => "BreadcrumbList",
			"itemListElement" => [
				["@type" => "ListItem", "position" => 1, "name" => "خانه","item" => $site_url],
				["@type" => "ListItem", "position" => 2, "name" => $category_name, "item" => $category_url],
				["@type" => "ListItem", "position" => 3, "name" => $title, "item" => $url]
			]
		];

		// ترکیب همه اسکیماها در یک @graph
		$schema_array = [
			"@context" => "https://schema.org",
			"@graph" => [
				$blog_posting,
				$breadcrumb_list
			]
		];
	}
	if ($schema=='AboutPage') {
		$organization_schema = [
			"@type" => "Organization", "name" => $site_name, "url" => $site_url, 
			"logo" => ["@type" => "ImageObject","url" => $logo_url],
			"contactPoint" => [ "@type" => "ContactPoint", "telephone" => $row_setting['shop_phone'], "contactType" => "customer service",  "areaServed" => "IR"]
		];

		$about_schema =  [
        	"@type" => "AboutPage",   "url" => $site_url . "/content/". $row_content['id']
    	];
		$schema_array = [
			"@context" => "https://schema.org", // فقط یک بار در سطح بالا
			"@graph" => [
				$organization_schema,
				$about_schema
			]
		];
	}
	if (in_array($schema, array('ImageGallery'))) {
		// تمامی آیتم ها در بخش bottom.php انجام می شود
		return '';
	}
	
	if (in_array($schema, array('HomePage','CollectionPage'))) { //
		$search_url_template = $sitenamelink.'/search/{search_term_string}';
		$website_schema = [
			"@type" => "WebSite", "url" => $site_url, "name" => $site_name,				"potentialAction" => ["@type" => "SearchAction","target" => ["@type" => "EntryPoint","urlTemplate" =>$search_url_template],"query-input" => "required name=search_term_string"]
		];
		$organization_schema = [
			"@type" => "Organization", "name" => $site_name, "url" => $site_url, "logo" => ["@type" => "ImageObject","url" => $logo_url]
		];

		$collection_schema = ["@type" => "CollectionPage", "name" => $site_name, "description" => $meta];

		$schema_array = [
			"@context" => "https://schema.org", // فقط یک بار در سطح بالا
			"@graph" => [
				$website_schema,
				$organization_schema,
				$collection_schema
			]
		];
	}
	
	$json_ld = json_encode($schema_array, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
	echo "
<script type='application/ld+json'>$json_ld </script>";
}
?>

