<?php 
// افزودن به علاقه مندی ها --- اگر از عقب بود اضافه شود نبود کم شود و پیغام متناسب بدهد
// افزودن به سبد خرید --- اگر بود به تعداد یکی اضافه شود و اگر نبود یک رکورد اضافه شود
// افزودن برای مقایسه --- اگر بود از مقایسه کم شود و اگر نبود اضافه شود و محدودیت 3 تا باشد
// صفحه فول می باشد و دارای چندین قالب نیست
//تک عکس سمت راست توضیحات بیایید و ذیلش اگر گالری تصاویر داشت بیاید
// سمت چپ عنوان و بعد عنوان دوم
// ذیلش اگر داشت مشخصات محصول با عرض کم و در کنار تصاویر
// با تغییر هر مشخصات محصول قیمت نیز تغییر می کند
// ذیلش توضیحات متن به صورت کامل ولی با سربرگ های 
//معرفی محصول / مشخصات / نظرات کاربران
// ذیلش محتواهای محصول به صورت هر سربرگ جدا اگر کلیک شود باز می شود نمایش دهد با مثبت و منفی
// ذیلش محصولات مرتبط
include('topmain.php');
$schema = 'Product';
if (isset($_REQUEST['id']))  			$id = intval($_REQUEST['id']);  	else $id = 0;
$ErrorMsg='';
$bankname  = 'product';

$query=pdo_query("select * from `$bankname` where id='$id' and active='2' and isdeleted=0 $shartdatetime  ",'',0);
$row_content = pdo_fetch($query);
if ($row_content=='') {	$ErrorMsg .= "<div class='ShowError'>کد مورد نظر صحیح نمی باشد</div>";} else{
	$tetr  			= $row_content['tetr'];				$leds 		= $row_content['leds'];
	$date_new		= $row_content['pdate'];			$time_new	= $row_content['ptime'];
	$matn 			= $row_content['matn'];				$photo_matn	= $row_content['photo_product'];
	$keywords  		= $row_content['tags'];				$id_topics	= $row_content['id_topics'];
	$open_comment  	= $row_content['open_comment'];		$countdn	= $row_content['countdn'];
	$nazarcount		= $row_content['nazarcount'];		
	//------------------------------------------------------
	$spectioal  = $row_content['spectioal'];			$best_saller= $row_content['best_saller'];
	$vote_count = $row_content['vote_count'];			$vote_sum 	= $row_content['vote_sum'];
//	$price01	= $row_content['price1'];				$price02	= $row_content['price2'];
	if ($vote_count==0) $vote_final = 0;  else 	$vote_final = ($vote_sum/$vote_count);
	//-------------------------------------------------------
	$password			= $row_content['password'];
	$meta_title			= $row_content['meta_title'];
	$meta_description 	= $row_content['meta_description'];
	$url_redirect 		= $row_content['url_redirect'];
	if ($photo_matn!='') {
		if (!validateURL($photo_matn)) $photo_matn = $upload_path_main.$photo_matn;
	}else{
		$photo_matn=$logopage;
	}
	$title_main = $tetr;
	if ($leds!='') $description= $leds;
	if ($keywords!='') $keyword = str_replace("\r\n",",",$keywords);
	//----------------------------------
	$tetrseo	= $row_content['meta_title'];
	if ($tetrseo=='') $tetrseo=$tetr;
	$canonical  = "$canonical/product/$id/" . LinkSeo($tetrseo);
	//--------------------
	if ($url_redirect!='') 		header("Location: $url_redirect");
	if ($meta_title!='') 		{$meta = $meta_title;			$title_main=$meta_title;		 }
	if ($meta_description!='')  {$meta_dsc = $meta_description;	$description = $meta_description; }
	//--------------------------------------------------
//	$StrTopics = ShowPrdocutTopics($id_topics);
	$ListTopics = array_filter(explode(',',$id_topics));
	$FirstIndex = array_keys($ListTopics)[0];
	$FirstTopic = $ListTopics[$FirstIndex];
//	echo "id_topics=$id_topics <BR> FirstIndex=$FirstIndex <BR> FirstTopic=$FirstTopic";
	$StrTopics = ShowServiceWithParent($FirstTopic);
	$tetr_page = $tetr;
	if ($spectioal==1) $tetr_page .= "<span class='text-danger small'> [ویژه] </span>";
	if ($best_saller==1) $tetr_page .= "<span class='text-danger small'> [پرفروش] </span>";
//	if ($photo_matn!='') {
//		if (!validateURL($photo_matn)) $photo_matn = $upload_path_main.$photo_matn;
//	}
	//-
	$sql=pdo_query("update `$bankname` set countdn=countdn+1 where id='$id' ");
	//----------------------------------------------------------
	$posimg = strpos(strtolower($matn), '<img');
	if ($posimg !== false) {
		$html = $matn;
		libxml_use_internal_errors(true);	
		$dom = new domDocument;
		$dom->loadHTML('<?xml encoding="utf-8" ?>' . $html);
		foreach ($dom->getElementsByTagName('img') as $item) {
			$width = $item->getAttribute('width');
			if ($width == 0 ) {
				$item->setAttribute('style', 'margin-left:auto; margin-right:auto; display:block;');
				$item->setAttribute('width', '90%');
//				$item->setAttribute('height','90%');
			}
		}
		$matn= $dom->saveHTML();
	}
	//--------------------------------------------
	$OkComment = true;
	if ($row_setting['ok_save_comment']==1) {
		if (!$ok_cookie) $OkComment= false;
	}
	if ($open_comment==0) $OkComment= false;
	//----------------------------------
}
//--------------------------------
if ($password!='') { 	if (CheckPassword($password) === false) {include('top.php'); include('bottom.php');  exit;} }
//-------------------------------------
$OkVote = true;
if ($row_setting['ok_save_vote']==1) {	if (!$ok_cookie) $OkVote= false;}
if ($row_setting['ok_save_vote']==2) {	$OkVote= false;}
//-------------------------------------------------------
$query2 = pdo_query("select * from `product_relation` where id_product='$id' order by idsort  ");
$row_relation= pdo_fetchall($query2);
$count_relation=count($row_relation);
//-------------------------------------------------------
$query2 = pdo_query("select * from `product_spec` where id_product='$id'  ");
$row_spec= pdo_fetchall($query2);
$count_spec=count($row_spec);
//-------------------------------------------------------
$query2 = pdo_query("select * from `product_gallery` where id_product='$id' and isdeleted=0 order by idsort  ");
$row_photos= pdo_fetchall($query2);
$count_photos=count($row_photos);
//-----------------------------------------------------
$query_temp = pdo_query("select * from `product_price` where id_product='$id'   ",'',0);
$counter=0;
$ListPrice = array();
while ($row_temp = pdo_fetch($query_temp)) {
	$id_price = $row_temp['id'];
	$Detils ='';
	$ListPrice[$counter] = $row_temp;
	$query_temp2 = pdo_query("select * from `product_price_property` where id_price='$id_price'  ");
	while ($row_temp2 = pdo_fetch($query_temp2)){
		$id_property = $row_temp2['id_property'];
		$query_temp3 = pdo_query("select * from `product_property` where id='$id_property' and id_product='$id'  ");
		$row_temp3 = pdo_fetch($query_temp3);
		if ($row_setting['show_product_property']==1) {
			if ($row_temp3!='') { $Detils .= "$row_temp3[tetr] :$row_temp2[content] ";}
		}else{
			if ($row_temp3!='') { $Detils .= "$row_temp2[content] ";}
		}
	}
	$ListPrice[$counter]['detils'] = $Detils;
	$counter++;
}
//var_dump($ListPrice);
include('top.php');
//-----------------------------------------------------
$query = pdo_query("SELECT * FROM `them_detils`  where id_them='$id_them' and isdeleted=0 and active=1 and id_position=5 order by id_position, idsort",'',0);
$row_abzarak = pdo_fetchall($query);		$count_abzarak = count($row_abzarak);
$StyleDiv1 = "col-lg-12 col-md-12 col-xs-12 pr mt-0";
if ($count_abzarak>0) $StyleDiv1 = "col-lg-9 col-md-8 col-xs-12 pr mt-0";
?>
<main class="main-row mb-2 mt-0 d-block">
<div class="container-main">
<div class="d-block">
	<div class="<?php echo $StyleDiv1;?>"> 
		<section class="blog-home">
			<article class="post-item">
				<header class="entry-header mb-3">
					<div class="post-meta category"><i class="mdi mdi-home"></i> <?php echo $StrTopics;?></div>
					<div class="post-meta Visit"><i class="mdi mdi-folder"></i> <?php echo $tetr;?> </div>
				</header>
				<div class="col-lg-5 col-xs-12 pr d-block" style="padding: 0;">
					<section class="product-gallery">
					<div class="gallery">
						<div class="gallery-item">
							<div><span class='text-white'>1</span>
								<ul class="gallery-actions">
									<li>
										<a onclick="AddWish(<?php echo $id;?>)" class="btn-option" style='cursor:pointer;'>
											<i class="mdi mdi-heart-outline"></i><span>محبوب</span>
										</a>
									</li>
									<li class="option-social">
										<a href="#" class="btn-option btn-option-social"
											data-toggle="modal" data-target="#option-social">
											<i class="mdi mdi-share"></i><span>اشتراک</span>
										</a>
									</li>
									<!--
									<li class="option-alarm">
										<a href="#" class="btn-option btn-option-alarm"
											data-toggle="modal" data-target="#btn-option-alarm">
											<i class="mdi mdi-bell-outline"></i>
											<span>اطلاع‌رسانی</span>
										</a>
									</li>
									-->
									<?php if ($count_photos>0) {?>
									<li class="Three-dimensional">
										<a href="#" class="btn-option btn-Three-dimensional"
											data-toggle="modal" data-target="#more-product">
											<i class="mdi mdi-more"></i><span>نمایش بیشتر</span>
										</a>
									</li>
									<?php } ?>
									<li class="Three-dimensional">
										<a href="#" class="btn-option" onclick='Compare(<?php echo $id;?>,1);'>
											<i class="mdi mdi-compare"></i><span>مقایسه</span>
										</a>
									</li>
								</ul>
							</div>
						</div>
						<div>
							<div class="gallery-item">
								<div class="gallery-img">
									<div id="">
										<a href="#">
											<img class="zoom-img rounded" id="img-product-zoom"
												src="<?php echo $photo_matn?>"
												data-zoom-image = "<?php echo $photo_matn?>"
												style="max-width:300px;" >
										</a>
										<?php
										// لیست تصاویر ضمیمه محصول
										if ($count_photos>0) {
											echo "
											<div id='gallery_01f' style='width:420px;float:right;'>
											<ul class='gallery-items owl-carousel owl-theme' id='gallery-slider'>";
											foreach($row_photos as $key=>$value) {
												$temp=$row_photos[$key];
												$photo_temp = $temp['photo'];
												if ( ($photo_temp!='') and  (!validateURL($photo_matn)) ) {
													$photo_temp = $upload_path_main.$photo_temp;
												}
												$photo_alt  = $temp['name'];
											echo "
											<li class='item'>
												<a href='#' class='elevatezoom-gallery active' data-update='' 
													data-image='$photo_temp'  data-zoom-image='$photo_temp'>
													<img src='$photo_temp' width='100'  alt='$photo_alt'>
												</a>
											</li>";
											}
											echo "
											</ul>
										</div>
										";
										}
										?>
									</div>
								</div>
							</div>
						</div>
					</div>
					</section>
				</div>
				<div class="col-lg-7 col-xs-12 pl d-block">
				<section class="product-info">
					<div class='row'>
					<div class='col-12 col-md-7'>
					<h1 style="margin: 0.8rem 0;  font-size: 20px;  line-height: 1.5;  font-weight: 700;  color:
					#666666; display: inline-block;"><?php echo $tetr_page;?></h1>
					</div>
					<?php		
					$StrVote=''; $counter=1;
					if ($OkVote) {
					echo "
					<div class='col-12 col-md-5 product-guaranteed'>
					<div class='post-vote' id='vote$FirstTopic-$id-container'>
						<span style='font-size:12px;color:#888;' >امتیاز:</span>
						<span style='font-size:12px;color:#888;' id='desc$counter'> </span> 
						<div id='votecontainer$counter' style='display: inline-block;_display: inline;direction:ltr;'></div>
						<span style='font-size:12px;color:#888;' id='1desc$counter'></span>  
					</div>
					</div>
					<script>
					vote$counter =new starVote({ vote:0,curStars:$vote_final,score:$vote_sum,containerId:'votecontainer$counter', descriptionId:'desc$counter', key:'$id', stars:'5', extraDatas:'groupid=$FirstTopic&stars=5'}); 
					</script> \n";
					}
					?>
					<?php 
					$TagLeds='';
					if ($leds!='') { 
						$leds=nl2br($leds);
						$TagLeds ="<div class='col-12 text-center mt-4 mb-5'><h3 class='title-leds'>$leds</h3></div>";
					}
					//--------------------- اگر لیست قیمتی وجود داشت
					if (count($ListPrice)>1) { echo $TagLeds; }
					//----------------------- show list price
					if (count($ListPrice)==0) { 
						echo "<div class='text-danger' style='font-size:13px;'>برای این محصول هنوز قیمت گذاری انجام نشده است</div>";
					}else {
						$TagListPrice = '';
						$counter=0;
						$id_price = 0 ;
						foreach ($ListPrice as $key=>$value) {
							$tetr_price = '';
							$temp = $ListPrice[$key];
							$idval		= $temp['id'];				$darsad  	= 0;
							$price01	= $temp['price1'];			$price02	= $temp['price2'];
							$cash 		= $temp['cash'];			$cash_unlimited=$temp['cash_unlimited'];
							$weight 	= $temp['weight'];			$id_tag 	= $temp['id_tag'];
//							if ($weight>0) $tetr_price .= "وزن [".number_format($weight)."] گرم - ";
							if ($id_tag>0) $tetr_price .= "شناسه [$id_tag]  - ";
							$tetr_price .= $temp['detils'];
							if ($cash_unlimited==1) $cash=999999999;
							if ( ($cash>0) ) $OkCash=true; else $OkCash=false; 
							if ($price02>0) {
								$darsad= number_format(100 - (($price02*100)/$price01),0);
							}
							$price1= number_format($price01);		$price2= number_format($price02);
							if ($price02>0) {
								$priceval   = $price02;
								$TagPriceTemp= "
								<del><span class='text-secondary pl-1 pr-1'>$price1 $price_unit</span></del>  
								<span class='bg-danger rounded p-1 m-1 text-white' style='font-size:smaller;'>$darsad % </span>   
								<BR> $price2";
							}else{
								$priceval   = $price01;
								$TagPriceTemp = $price1;
							}
							//----------------------------
							$selected='';
							if ($counter==0) {
								$selected=' checked ';
								$TagListPrice .= "<input type='hidden' value='$idval' name='idprice' id='idprice'> ";
								if ($cash>0)	{
									$ProductCount=1;
									$TagPrice="<span id='price_final' class='amount'>$TagPriceTemp</span> <span class='amount'>$price_unit</span>";
									$class1='display:block;';		$class2='display:none;';
								}else{
									$ProductCount=0;
									$TagPrice = "<span class='unavailable'>ناموجود</span>";
									$class2='display:block;';		$class1='display:none;';
								}
							}

							$idradio = "idradio_$idval";			$idcash  =  "cash_$idval";	
							$idprice1 = "price1_$idval";			$idprice2 = "price2_$idval";
							if (count($ListPrice)==1) { 
								$TagListPrice .= "
								<input type='hidden' id='$nameradio' name='$nameradio' name=' value='$idval'>
								$TagLeds
								";
							}else{
								$TagListPrice .= "<BR>
								<input type='hidden' id='$idcash'  value='$cash'>
								<input type='hidden' id='$idprice1'  value='$price01'>
								<input type='hidden' id='$idprice2'  value='$price02'>
								<input type='radio' id='$idradio' name='idradio' value='$idval' $selected onchange='ChangePrice(this.value);'>
								<span class='text-secondary' style='font-size:13px;'> $tetr_price </span>
								";
							}
							$counter++;
						}
						echo "
						<div class='col-lg-6 col-md-6 col-xs-12'>$TagListPrice</div>
						<div class='col-lg-6 col-md-6 col-xs-12 lr'>
							<div class='product-seller-info'>
								<div class='seller-info-changable'>
									<div class='product-seller-row price'>
										<span class='title'> قیمت:</span>
										<span id='PriceTag' class='product-name'>
											$TagPrice
										</span>
									</div>
									<div class='product-seller-row guarantee'>
										<span class='title mt-3'> تعداد:</span>
										<div class='quantity pl'>
											<input name='ProductCount' id='ProductCount' type='number' min='1' max='100'  step='1' value='$ProductCount' onkeypress='return isNormalNumber(event)'  oninput='enforceMax(this)' >
										</div>
									</div>
									<div id='TagAddtoCart1' class='product-seller-row add-to-cart text-center' style='$class1'>
									<a  onclick='AddToCart($id,$(\"#idprice\").val(),$(\"#ProductCount\").val());' class='btn btn-primary' style='border-radius: 20px;'> 
										<span class='text-white'>افزودن به سبد خرید</span> 
									</a>
									</div>
									<div id='TagAddtoCart2' class='product-seller-row add-to-cart text-center' style='$class2'>
									<a onclick='NotificationToMe($id)' class='btn-add-to-cart Let-me-know btn btn-primary'> <span class='btn-add-to-cart-txt text-white'>موجود شد به من اطلاع بده</span> 
									</a>
									</div>
								</div>
							</div>
						</div>
						";
					}
					// در صفحه بخش نمایش انواع قیمت ها به صورت رادیو باتن 
					// لیست تمامی قیمت ها را نمایش دهد
					// قسمت پایین صفحه سربرگ های 
					// معرفی محصول - مشخصات - نظرات کاربران - کالاهای مشابه
					// البته بهتره که محصولات مرتبط خودش جدا شود ویه استایل اون پایین داشته باشد به صورت اسکرولی
					// آیتم های محتواهای محصول به صورت پلاس و نگاتیو عمودی در صفحه بخش معرفی محصول نمایش داده شود
					?>
					<div class="col-12">
						<div class="TagsList">
						<?php
						if ($keywords!='') {
							$KeywordList = array_filter(explode("\n", $keywords));
							$JamRec = count($KeywordList);
							$temp = '';
							foreach ($KeywordList as $key => $val)	{
								$word = $KeywordList[$key];
								if ($word != '') {
									$temp .= "<a target=_blank href='/CategoryProduct/4/search/$word'>$word </a>\r\n";
								}
							}
							if ($temp!='') $keywords = $temp;
							echo $keywords;
						}
						?>
						</div>
					</div>
				</div>
				</section>
				</div>
				<div class="col-12 product-usp">
					<div class="row product-feature" style='display: flex;'>
						<div class="col-6 col-md-4 product-feature-col" style='margin:0px;'>
							<a class="product-feature-item">
								<img src="/assets/images/page-single-product/delivery.svg" style='width:40px;' >
								امکان تحویل <br> اکسپرس
							</a>
						</div>
						<div class="col-6 col-md-4 product-feature-col" style='margin:0px;'>
							<a href="#" class="product-feature-item">
								<img src="/assets/images/page-single-product/contact-us.svg">
								۷ روز هفته
								<br>
								۲۴ ساعته
							</a>
						</div>
						<div class="col-6 col-md-4 product-feature-col" style='margin:0px;'>
							<a href="#" class="product-feature-item">
								<img src="/assets/images/page-single-product/origin-guarantee.svg">
								ضمانت
								<br>
								اصل بودن کالا
							</a>
						</div>
					</div>
				</div>
			</article>
		</section>
		<div class='tabs'>
			<div class='tab-box'>
				<ul class='tab nav nav-tabs' id='myTab' role='tablist'>
					<li class='nav-item'>
						<a class='nav-link active' id='Review-tab' data-toggle='tab' href='#Review' role='tab'
							aria-controls='Review' aria-selected='true'>
							<i class='mdi mdi-glasses'></i>
							معرفی محصول
						</a>
					</li>
					<?php if ($count_spec>0) { ?>
					<li class='nav-item'>
						<a class='nav-link' id='Specifications-tab' data-toggle='tab' href='#Specifications'
							role='tab' aria-controls='Specifications' aria-selected='false'>
							<i class='mdi mdi-format-list-checks'></i>
							مشخصات
						</a>
					</li>
					<?php } ?>
					<li class='nav-item'>
						<a class='nav-link' id='User-comments-tab' data-toggle='tab' href='#User-comments'
							role='tab' aria-controls='User-comments' aria-selected='false'>
							<i class='mdi mdi-comment-text-multiple-outline'></i>
							نظرات کاربران
						</a>
					</li>
				</ul>
			</div>
			<div class='col-lg'>
				<div class='tabs-content'>
					<div class='tab-content' id='myTabContent'>
						<div class='tab-pane fade show active' id='Review' role='tabpanel'
							aria-labelledby='Review-tab'>
							<h2 class='params-headline'>معرفی محصول 
							<span  style='font-size:smaller'>[<?php echo $title_main;?>]</span></h2>
							<section class='content-expert-summary'>
								<div class="content-blog"><?php echo $matn;?> </div>
							</section>
							<div class='content-expert-articles'>
								<?php 
								$query3 = pdo_query("select * from `product_content` where id_product='$id' ");
								while ($row3= pdo_fetch($query3)) {
									echo "
									<section class='content-expert-article'>
										<button class='content-expert-button'>
											<span class='show-more'><i class='mdi mdi-plus'></i></span>
											<span class='show-less'><i class='mdi mdi-minus'></i></span>
										</button>
										<h3 class='content-expert-title'>$row3[tetr]</h3>
										<div class='content-expert-text'>
											<p style='text-align:right'> $row3[content] </p>
										</div>
									</section>
									";
								}
								?>
							</div>
						</div>
						<div class='tab-pane fade' id='Specifications' role='tabpanel'
							aria-labelledby='Specifications-tab'>
							<article>
								<h2 class='params-headline'>مشخصات
									<span  style='font-size:smaller'>[<?php echo $title_main;?>]</span>
								</h2>
								<section>
									<ul class='params-list'>
									<?php 
									if ($count_spec>0) { 
										foreach($row_spec as $key=>$value) {
											$temp = $row_spec[$key];
											echo "
											<li class='params-list-item'>
												<div class='params-list-key'>
													<span class='block'>$temp[tetr]</span>
												</div>
												<div class='params-list-value'>
													<span class='block'>$temp[content]</span>
												</div>
											</li>
											";
										}
									}
									?>
									</ul>
								</section>
							</article>
						</div>
						<div class='tab-pane fade' id='User-comments' role='tabpanel'
							aria-labelledby='User-comments-tab'>
						<div class="post-comments">
							<div class="comments-area">
								<?php if ($nazarcount>0) { ?>
								<h2 class="comments-title mb-3">
									<i class="fa fa-comment-o"></i><a onclick='$("#List_Comment").toggle();' style='  cursor: pointer;'>نظرات کاربران</a>
									<p class="count-comment"><?php echo $nazarcount;?> نظر</p>
								</h2>
								<?php } ?>
								<ol id='List_Comment' class="comment-list"><?php echo ListComment();?></ol>
								<?php if ($OkComment) {?>
								<div class="comment-us-section">
									<div class="col-12 box-title mb-5"><a onclick='$("#SaveComment").toggle();' style='  cursor: pointer;'>نظر شما</a></div>
									<div class="row" id='SaveComment'>
									<?php 
									$comment_name = '';		$comment_email   = '';
									if ($ok_cookie) {
										$comment_name = $usernamefarsi;		$comment_email   = $useremail;
										echo "<input type='hidden' name='comment_iduser'  id='comment_iduser' value='$usernameid'>";
									}else{
										echo "<input type='hidden' name='comment_iduser'  id='comment_iduser' value='0'>";
									}
									?>
									<div class="col-12 col-md-6 mb-3">
										<label class="form-label">نام و نام خانوادگی<span class='text-danger'>*</span></label>
										<input class="form-control" value="<?php echo $comment_name;?>" id="comment_name" name="comment_name" type="text" >
									</div>
									<div class="col-12 col-md-6  mb-3">
										<label class="form-label">ایمیل  <span class='text-danger' style='font-size:10px;' >جهت دریافت پاسخ نظر ، ایمیل خود را وارد نمایید</span></label>
										<input class="form-control" value="<?php echo $comment_email;?>" id="comment_email" name="comment_email" type="text" dir='ltr'  >
									</div>
									<div class="col-12">
										<label class="form-label">متن نظر <span class='text-danger'>*</span></label>
										<textarea class="form-control" id="comment_message" name="comment_message" rows=5></textarea>
									</div>
									<?php
									if ($row_setting['ok_capcha_comment']==1) { 
//										session_start();
										echo "
										<div class='col-12 mb-3 mt-3'>
										<label class='form-label'>کد کپچا <span class='text-danger'>*</span></label>
										<input type='text'   name='comment_secCode' id='comment_secCode' class='form-control' size='6' style='width:120px;display:unset;margin-right:10px;'>";
										$verify_string_hash = captcha2('width:80px;',4);							
										echo "
										<input type='hidden' name='comment_sec2Code' value='$verify_string_hash'  id='comment_sec2Code'>
										</div>";
										$token_var = 'token_nazar0_'.$id;  
										$_SESSION[$token_var]= $verify_string_hash; 
									} else{
										echo "
										<input type='hidden' name='comment_secCode' id='comment_secCode'  value='' >
										<input type='hidden' name='comment_sec2Code' id='comment_sec2Code'  value=''>
										";
									}
									?>
									<div class="col-12 text-left mt-2">
										<button class="btn comment-submit-button" onclick="sendAjaxComment(<?php echo $id; ?>, 0)">ثبت نظر</button>
									</div>
									</div>
								</div>
								<?php }?>
							</div>
						</div>

						</div>
					</div>
				</div>
			</div>

		</div>
		<?php if ($count_relation>0) {?>
		<div class='col-lg-12 col-md-12 col-xs-12 pr order-1 d-block'>
			<div class='slider-widget-products slider-content-tabs'>
				<div class='widget widget-product card slider-content-tabs'>
					<header class='card-header header-product'>
						<span class='title-one'>محصولات مرتبط</span>
						<h3 class='card-title'></h3>
					</header>
					<div class='product-carousel owl-carousel owl-theme owl-rtl owl-loaded owl-drag'>
						<div class='owl-stage-outer'>
							<div class='owl-stage'
								style='transform: translate3d(0px, 0px, 0px); transition: all 0s ease 0s; width: 1612px;'>
								<?php 
								foreach ($row_relation as $key=>$value) {
									$temp = $row_relation[$key];
									$id_relation = $temp['id_relation'];
									$bank1 = 'product';	
									$bank2 = 'product_price';
									$shart = " where $bank1.id='$id_relation' and $bank1.active=2 $shartdatetime ";
									$sql_query = "select $bank1.id, $bank1.tetr, $bank1.leds, $bank1.photo_product, $bank2.id as id_price, $bank2.price1, $bank2.price2, $bank2.cash, $bank2.cash_unlimited from `$bank1` inner join `$bank2` on $bank1.id=$bank2.id_product $shart group by $bank1.id order by $bank1.pdate desc, $bank1.ptime desc ";
									$query4 = pdo_query($sql_query);
									$row_product	= pdo_fetch($query4);
									if ($row_product!='') {
										$id_new= $row_product['id'];					
										$tetr  = $row_product['tetr'];			
										$leds  = $row_product['leds'];					
										$id_price = $row_product['id_price'];
										$link = $row_product['url_redirect'];
										if ($link=='') $link  = "/product/$id_new/" . LinkSeo($tetrseo);
										
										$tetr_photo = $tetr;
										$tetr2 = GetProductProperty($id_new, $id_price);
										if ($tetr2!='') {
											$tetr .= "<span class='text-danger' style='font-size:smaller'>$tetr2</span> ";
										}
										$price01= ($row_product['price1']);
										$price02= ($row_product['price2']);
										if ($price02>0) {
											$darsad= number_format(100 - (($price02*100)/$price01),2);
										}
										$price1= number_format($price01);
										$price2= number_format($price02);
										$photo = $row_product['photo_product'];
										if ($photo=='') {$photo=$logofile;} 
											else {$photo=$upload_path_main.$photo; }
										if ($price02>0) {
											$PriceFinal = "<ins><span>$price2<span> $price_unit</span></span></ins>";
											$PriceTag = "
											<del><span>$price1<span> $price_unit </span></span></del>
											<ins><span>$price2<span> $price_unit </span></span></ins>
											";
										}else{
											$PriceFinal = "<ins><span>$price1<span> $price_unit</span></span></ins>";
											$PriceTag = "
											<del><span>&nbsp;</span></del>
											<ins><span>$price1<span> $price_unit </span></span></ins>
											";
										}
										$cash = $row_product['cash'];	
										if ($row_product['cash_unlimited']==1) $cash=999999999;
										if ( $cash<=0 ) {
											$PriceFinal = "<ins><span><span class='btn btn-danger'>ناموجود</span></span></ins>";
											$PriceTag = "<del><span>&nbsp;</span></del><ins><span><span class='btn btn-danger'>ناموجود</span></span></ins>";
										}
										
										echo "
										<div class='owl-item tab-item active' style='width: 312.25px; margin-left: 10px;'>
											<div class='item'>
												<a href='$link' target=_blank class='d-block hover-img-link'>
													<img src='$photo' class='img-fluid' style='min-height:137px;' alt='$tetr_photo'>
												</a>
												<h2 class='post-title'>
													<a href='$link' target=_blank>$tetr</a>
												</h2>
												<div class='price'>$PriceTag</div>
											</div>
										</div>
										";
									}
								}
								?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php } ?>
	</div>
	<?php 
	if ($count_abzarak>0) { 
		echo "<div class='col-lg-3 col-md-4 col-xs-12 pr mt-0 sticky-sidebar'><div class='row'>";
		foreach($row_abzarak as $key=>$value) {
			ShowAbzarak($row_abzarak[$key]);
		}
		echo "</div></div>";
	} 
	?>
</div>
</div>
</main>
<!-- Modal-option-social -->
<div class="modal fade" id="more-product" tabindex="-1"	role="dialog" aria-labelledby="exampleModalCenterTitle"
	aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered more-product"	role="document">
		<div class="modal-content">
			<div class="modal-body">
				<div id="custom-events">
				<?PHP
				if ($count_photos>0) {
					foreach($row_photos as $key=>$value) {
						$temp=$row_photos[$key];
						$photo_temp = $temp['photo'];
						if ( ($photo_temp!='') and  (!validateURL($photo_matn)) ) {
							$photo_temp = $upload_path_main.$photo_temp;
						}
						$photo_alt  = $temp['name'];
						echo "<a href='$photo_temp' target='_blank'><img src='$photo_temp' alt='$photo_alt'></a>";
					}
				}	
				?>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Modal-option-social -->
<div class="modal fade" id="option-social" tabindex="-1"	role="dialog" aria-labelledby="exampleModalCenterTitle"
	aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered"		role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalCenterTitle">اشتراک گذاری </h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close" style='top:0px;margin:0px;'>
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="title" style='font-size:13px;'>با استفاده از روش‌های زیر می‌توانید این صفحه را با دوستان خود به اشتراک بگذارید.</div>
				<div class='col-12 text-left main-social mt-4 mb-4 text-center' dir='ltr' >
					<?php echo ListShare($id,$tetr);?>
				</div>
				<div class="col-12 mt-3 mb-3" >
					<input class="form-control" type="url" dir='ltr' value="<?php echo $MainUrl."/product/$id/";?>" readonly="">
				</div>
			</div>
		</div>
	</div>
</div>
<!-- Modal-option-alarm -->
<div class="modal fade" id="btn-option-alarm" tabindex="-1"	role="dialog" aria-labelledby="exampleModalCenterTitle"
	aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered"	role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalCenterTitle">به من اطلاع بده </h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="form-notification-title">از طریق:</div>
				<?php if ($useremail!='') { ?>
				<div class="form-auth-row">
					<label for="#" class="ui-checkbox mt-1">
						<input type="checkbox" value="1"name="login" id="remember">
						<span class="ui-checkbox-check"></span>
					</label>
					<label for="remember"class="remember-me mr-0">ایمیل به<?php echo $useremail;?></label>
				</div>
				<?php } ?>
				<?php if ($usermobile!='') { ?>
				<div class="form-auth-row">
					<label for="#" class="ui-checkbox mt-1">
						<input type="checkbox" value="1" name="login" id="remember">
						<span class="ui-checkbox-check"></span>
					</label>
					<label for="remember" class="remember-me mr-0">پیامک به <?php echo $usermobile;?></label>
				</div>
				<?php } ?>
				<div class="form-auth-row">
					<label for="#" class="ui-checkbox mt-1">
						<input type="checkbox" value="1" name="login" id="remember">
						<span class="ui-checkbox-check"></span>
					</label>
					<label for="remember" class="remember-me mr-0">سیستم پیام شخصی <?php echo $row_setting['shop_name'];?></label>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success ml-2" style='padding-left:20px;padding-right:20px;'>   ثبت   </button>
				<button type="button" class="btn btn-secondary" data-dismiss="modal">بازگشت</button>
			</div>
		</div>
	</div>
</div>
<?php
include('bottom.php');

if ($OkComment) {
?>
<div  class="modal fade"  id="AnswerComment"  tabindex="-1"   aria-labelledby="FormSeoLabel"  aria-hidden="true">
	<input type='hidden' value='' name='comment_id' id='comment_id'>
	<?php 
	$comment_name2 = '';		$useremail2   = '';
	if ($ok_cookie) {
	$comment_name2 = $usernamefarsi;		$useremail2   = $comment_email;
	echo "<input type='hidden' name='comment_iduser2'  id='comment_iduser2' value='$usernameid'>";
	}else{
	echo "<input type='hidden' name='comment_iduser2'  id='comment_iduser2' value='0'>";
	}
	
	?>
	
	<div class="modal-dialog modal-dialog-centered modal-lg">
		<div class="modal-content" style='border-color:#ffa101;border-block-width: thin;'>
			<div class="modal-header" >
				<h5 class="modal-title" id="AddRecordTetr" style='font-size: 20px;  margin-bottom: 12px;'>
				پاسخ به نظر</h5>
				<header id="LastEditor" class='row comment-meta' style='gap:10px;margin-top:5px;font-size:14px;'></header>
				
				<span style='cursor:pointer;' onclick='$("#AnswerComment").modal("hide");'>
				<i class="fa fa-times" data-bs-dismiss="modal"  aria-label="Close"></i>
				</span>
			</div>
			<div class="modal-body">
			<div class='col-12 mb-3' >
				<div class='row mt-1' style='border-radius: 20px; background-color:#F9F9F9; padding: 5px 5px'>
					<div class='col-6 mt-1 mb-2'>
						<label class="form-label">نام و نام خانوادگی:</label>
						<input type='text' name='comment_name2' class='form-control' id='comment_name2' value='<?php echo $comment_name2;?>' >
					</div>
					<div class='col-6 mb-2'>
						<label class="form-label">پست الکترونیکی:</label>
						<input type='email' dir='ltr' name='comment_email2' class='form-control' id='comment_email2' value='<?php echo $comment_email2;?>' >
					</div>
					<div class='col-12 mb-2'>
						<label class="form-label">متن نظر:</label>
						<textarea class="form-control" cols="20"  id="comment_message2" name="comment_message2"  rows="3" ></textarea>
					</div>
					<?php
					if ($row_setting['ok_capcha_comment']==1) { 
						echo "
						<div class='col-12 mb-3 mt-3'>
						<label class='form-label'>کد کپچا <span class='text-danger'>*</span></label>
						<input type='text'   name='comment_secCode2' id='comment_secCode2' class='form-control' size='6' style='width:120px;display:unset;margin-right:10px;'>";
						$verify_string_hash = captcha2('width:80px;',4);							
						echo "
						<input type='hidden' name='comment_sec2Code2' id='comment_sec2Code2'  value='$verify_string_hash'>
						</div>";
						$token_var = 'token_nazar1_'.$id;  
						$_SESSION[$token_var]= $verify_string_hash; 
					} else{
						echo "
						<input type='hidden' name='comment_secCode2' id='comment_secCode2'  value='' >
						<input type='hidden' name='comment_sec2Code2' id='comment_sec2Code2'  value=''>
						";
					}
					?>
				</div>
			</div>
			</div>
			<div class="modal-header text-left" style='display:unset;'  >
					<a  class='btn btn-success text-white' onclick="sendAjaxComment(<?php echo $id; ?>, $('#comment_id').val())" >
						<i class="fa fa-check mr-1"></i> ارسال
					</a>
					<a class='btn btn-secondary text-white' onclick='$("#AnswerComment").modal("hide");'>
						<i class="fa fa-list  mr-1"></i> بازگشت
					</a>
			</div>
		</div>
	</div>
</div>
<?php } ?>
<script>
function enforceMax(input) {
  const maxValue = parseInt(input.max); // Get the max value as an integer
  const minValue = parseInt(input.min); // Get the min value as an integer
  const currentValue = parseInt(input.value); // Get the current value as an integer

  if (currentValue > maxValue) {
    input.value = maxValue; // Set the value to the max
  }
  if (currentValue < minValue) {
    input.value = minValue; // Set the value to the min
  }
}

function ChangePrice(id) {
	$("#idprice").val(id);
	var price1 = $("#price1_"+id).val();
	var price2 = $("#price2_"+id).val();
	var idcash   = $("#cash_"+id).val();
	var darsad   = 0;
	var price_unit = " <?php echo $price_unit;?> ";
	if (idcash<=0) {
		$("#ProductCount").val(0);
		$("#PriceTag").html("<span class='unavailable'>ناموجود</span>");
		$('#TagAddtoCart1').css('display', 'none');
		$('#TagAddtoCart2').css('display', 'block');
		
	}else{
		darsad= parseInt(100 - ((price2*100)/price1));
		price01 = FormatNumberBy3(price1);
		price02 = FormatNumberBy3(price2);
		$("#ProductCount").val(1);
		if (price2 !=0 ) {
			TagPriceTemp= "<span id='price_final' class='amount'><del><span class='text-secondary pl-1 pr-1'>"+price01+ price_unit + "</span></del>  <span class='bg-danger rounded p-1 m-1 text-white' style='font-size:smaller;'>"+darsad+" % </span> <BR> "+price02 + "</span> <span class='amount'>"+price_unit+"</span>";
		}else{
			TagPriceTemp = "<span id='price_final' class='amount'>"+price01 + "</span> <span class='amount'>"+price_unit+"</span>";
		}
		

		
		$("#PriceTag").html(TagPriceTemp);
		$('#TagAddtoCart1').css('display', 'block');
		$('#TagAddtoCart2').css('display', 'none');
	}
}
$(document).ready(function(){
	<?php if ($OkComment) {?>
	$("#comment_message").val('');
	<?php } ?>
	<?php if (!($ok_cookie)) {?>
	$("#comment_name").val('');
	$("#comment_email").val('');
	<?php } ?>
	<?php if ($row_setting['ok_capcha_comment']==1) {  ?>
	$("#comment_secCode").val('');
	<?php } ?>
	 
});
//--------------------------
function ReplayComment(id_tag,id) {
	var HeaderComment = $('#'+id_tag).html();
	$("#LastEditor").html('['+HeaderComment+']');
	$("#comment_id").val(id);
	$("#comment_name2").val('');
	$("#comment_email2").val('');
	$("#comment_message2").val('');
	<?php if ($row_setting['ok_capcha_comment']==1) { ?>
	$("#comment_secCode2").val('');
	<?php } ?>
	$("#AnswerComment").modal("show");
}
//-------------------------------------------------
function sendAjaxComment(idnews,idnazar) {
	tmp = '<input id="idButton" value="'+idnazar+'"  type="hidden">';
	if (idnazar==0) {
		var name = document.getElementById("comment_name");  
		var email = document.getElementById("comment_email");  
		var nazar = document.getElementById("comment_message");  
		var secCode  = document.getElementById("comment_secCode");  
		var sec2Code = document.getElementById("comment_sec2Code");  
		var iduser = document.getElementById("comment_iduser");  
	} else {
		var name = document.getElementById("comment_name2");  
		var email = document.getElementById("comment_email2");  
		var nazar = document.getElementById("comment_message2");  
		var secCode  = document.getElementById("comment_secCode2");  
		var sec2Code = document.getElementById("comment_sec2Code2");  
		var iduser = document.getElementById("comment_iduser2");  
	}
	if (name.value == "") {
		message('لطفا نام و نام خانوادگی خود را  وارد نمایید','error',0,'');
		name.focus();
		return false;
	}
	if ( (email.value!='')) {
		if (!checkEmail(email.value))  { 
			message('لطفا ایمیل خود را به درستی وارد نمایید','error',0,'');
			email.select();			email.focus();
			return false;
		}
	}
	if (nazar.value == "") {
		message('لطفا متن پیام خود را وارد نمایید','error',0,'');
		nazar.focus();
		return false;
	}
	<?php 	if ($row_setting['ok_capcha_comment']==1) { ?>
	if (secCode.value == "") {
		message('لطفا کد کپچا خود را وارد نمایید','error',0,'');
		secCode.focus();
		return false;
	}
	<?php }	?>
	nazar_value = nazar.value;
	$.ajax({
		type: 'POST',
		url: '/data_ajax.php',
		data: { type: 2, typebank: 1, idnews: idnews, idnazar: idnazar, comment_name: name.value, comment_email: email.value, comment_message: nazar_value, secCode: secCode.value, sec2Code: sec2Code.value, iduser:iduser.value, security:<?php echo rand(); ?>},
		dataType: 'json',
		success: function (data, status, xhr) {
			if (data[0]<0) { message(data[1], 'error') }
			if (data[0]==1) { 
				var new_message = data[1];
				message(data[1], 'success') 
				if (idnazar==0) {	
					var newdata = '<div class="col-10 bg-success text-white text-center p-3 rounded" style="margin:auto;">'+data[1]+'</div><BR><BR>';
					$('#SaveComment').html(newdata);
				} else { $("#AnswerComment").modal("hide");}
			}
		},
		error: function (jqXhr, textStatus, errorMessage) {alert(' textStatus = '+textStatus+' Error: ' + errorMessage);}
	});		
}
//-----------------------------------------
//--------------------------
function ReadCommentNew(startrec, idnews, maxread) {
	IdContinue = 'ContinueComment';
	$.ajax({
		type: 'POST',
		url: '/data_ajax.php',
		data: { type: 3, typebank: 2, startrec: startrec, idnews: idnews, maxread: maxread},
		dataType: 'json',
		success: function (data, status, xhr) {
			if (data[0]<0) { message(data[1], 'error') }
			if (data[0]==1) { 
			//	$('#ContinueComment').prop(data[1]);
				document.getElementById('ContinueComment').outerHTML= data[1] ;
			}
		},
		error: function (jqXhr, textStatus, errorMessage) {alert(' textStatus = '+textStatus+' Error: ' + errorMessage);}
	});		
}

</script>
<script src="/assets/js/vendor/theia-sticky-sidebar.min.js"></script>
<!-- <script src='/assets/js/vendor/lightgallery-all.js'></script> -->
<script src='/assets/js/vendor/jquery.ez-plus.js'></script>


<?php

function ListShare($id, $tetr) {
	global $sitenamelink;
	$link = $sitenamelink."/product/$id/";//.LinkSeo($tetr);
	$tetr2 = LinkSeo($tetr);
	$ret = "
	<ul style='justify-content: center;  float: unset;'>
	<li>
		<a target='_blank' href='https://pinterest.com/pin/create/link/?url=$link'><i class='fa fa-pinterest-p'></i> </a> 
	</li>
	<li>
		<a target='_blank' href='https://www.facebook.com/share.php?u=$link'><i class='fa fa-facebook'></i></a>  
	</li>
	<li>
		<a target='_blank' href='https://twitter.com/intent/tweet?text=$link'><i class='fa fa-twitter'></i> </a>  
	</li>
	<li>
		<a target='_blank' href='https://telegram.me/share/url?url=$link'><i class='fa fa-telegram'></i> </a>  
	</li>
	<li>
		<a target='_blank' href='https://api.whatsapp.com/send?text=$link'><i class='fa fa-whatsapp'></i> </a>  
	</li>
	<li>
		<a target='_blank' href='https://www.linkedin.com/shareArticle?mini=true&url=$link'><i class='fa fa-linkedin'> </i> </a>  
	</li>
	<li>
		<a target='_blank' href='https://twitter.com/share?url=$link&text=$tetr2'><i class='fa fa-twitter'> </i> </a>  
	</li>
	</ul>
	";
	return $ret;
}

function ListComment() {
	global $bankname, $id, $OkComment;
	$MaxReadComment=10;
	$StrReplay='';
	$ret = '';
	$query = pdo_query("select * from `nazar` where idrec='$id' and parentid='0' and deleted='f' and ok='y' and tablename='$bankname' order by date desc, time desc limit 0,$MaxReadComment ",'',0);
	$counter=0;
	while ($row_comment = pdo_fetch($query)) {
		$counter++;
		$id_comment = $row_comment['id'];
		$message = nl2br($row_comment['message']);			$answer = nl2br($row_comment['answer']);
		$country = $row_comment['country'];					$city = $row_comment['city'];
		$email 	 = $row_comment['email'];
		if ($email!='') $email = "<div class='post-meta Visit'><i class='fa fa-envelope'></i> $email </div>";
		if ($answer!='') $answer="<p class='text-danger'><span class='bg-danger text-white p-1'> پاسخ: </span><BR>$answer</p>";
		if ($country!='') $country = "<div class='post-meta'><i class='fa fa-flag'></i> $country</div>";
		if ($city!='') $city = "<div class='post-meta'><i class='fa fa-map-marker'></i> $city</div>";
		if ($OkComment) {
			$StrReplay = "<div class='reply text-left'><a onclick=\"ReplayComment('header$id_comment',$id_comment)\" class='comment-reply-link' style='cursor:pointer;'>پاسخ دادن</a></div>";
		}
		//----------------------
		$ListAnswerThisComment = ListCommentAnswer($id_comment, $bankname, $id, $OkComment);
		//-----------------------
		$ret .= "
		<li class='comment-even'>
			<div class='comment-body'>
				<header id='header$id_comment' class='row comment-meta' style='gap:10px;'>
					<div class='post-meta date'>$counter |</div>
					<div class='post-meta date'><i class='fa fa-calendar'></i> $row_comment[date] </div>
					<div class='post-meta author'><i class='fa fa-clock-o'></i> $row_comment[time] </div>
					<div class='post-meta'><i class='fa fa-user'></i> $row_comment[name]  </div>
					$email
					$country
					$city
				</header>
				<p>$message</p>
				$answer
				$StrReplay
			</div>
		</li>
		$ListAnswerThisComment
		";
	}
	if ($counter>=$MaxReadComment) { 
		$ret .= "
		<span id='ContinueComment'>
		<div class='col-12 text-center bg-success text-white rounded p-2 mt-5'>
		<a onclick='ReadCommentNew($MaxReadComment,$id, $MaxReadComment)' class='mbtn' style='cursor:pointer;'>خواندن نظرات بیشتر</a>
		</div>		
		</span>
		"; 
	} 
	return $ret;
}
//---------------------------------------------------------
function ListCommentAnswer($idcomment, $bankname, $id, $OkComment) { 
	$query2 = pdo_query("select * from `nazar` where idrec='$id' and deleted='f' and ok='y' and tablename='$bankname' and parentid='$idcomment' order by date desc, time desc  ",'',0);
	$counter2=0;
	$ret = '';
	while ($row_comment2 = pdo_fetch($query2)) {
		$counter2++;
		$id_comment = $row_comment2['id'];
		$message = nl2br($row_comment2['message']);			$answer = nl2br($row_comment2['answer']);
		$country = $row_comment2['country'];				$city = $row_comment2['city'];
		$email 	 = $row_comment2['email'];
		if ($email!='') $email = "<div class='post-meta Visit'><i class='fa fa-envelope'></i> $email </div>";
		
		if ($answer!='') $answer="<p class='text-danger'><span class='bg-danger text-white p-1'> پاسخ: </span><BR>$answer</p>";
		if ($country!='') $country = "<div class='post-meta'><i class='fa fa-flag'></i> $country</div>";
		if ($city!='') $city = "<div class='post-meta'><i class='fa fa-map-marker'></i> $city</div>";
		
		$ret .= "
		<li class='comment-even'>
			<div class='comment-body mr-5' style='background-color:#f3f9f1;line-height:20px;padding-top:10px;padding-bottom:1px;'>
				<header id='header$id_comment' class='row comment-meta' style='gap:10px;'>
					<div class='post-meta date'>$counter2 |</div>
					<div class='post-meta date'><i class='fa fa-calendar'></i> $row_comment2[date] </div>
					<div class='post-meta author'><i class='fa fa-clock-o'></i> $row_comment2[time] </div>
					<div class='post-meta'><i class='fa fa-user'></i> $row_comment2[name]  </div>
					$email
					$country
					$city
				</header>
				<p>$message</p>
				$answer
			</div>
		</li>
		";
	}
	return $ret;

}
?>
