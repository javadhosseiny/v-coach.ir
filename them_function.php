<?php
function ShowAbzarak($row) {
	$id_type = $row['id_type'];
	// متن - لینک - متن کامل با ادیتور
	if ( in_array($id_type, array(10,15,16)))  ShowText($row);
	//تبلیغات
	if ($id_type==11) ShowTabligh($row);
	//اسکریپت
    if ($id_type==12) { ShowScript($row); } 
	//شبکه های اجتماعی
    if ($id_type==14) { ShowSocialNetwork($row); } 
	//محصولات و وبلاگ ها
	if ( ($id_type==40) or ($id_type==20) ) ShowProduct($row);
	// تصاویر
	if ($id_type==50) ShowSlider($row);
//	var_dump($row);
}

//-------------------------------------------------------
function ShowScript($row) {
	$id  = $row['id'];					$name=$row['name'];
	$css = $row['col_width'];			$dsc_script = $row['dsc_script'];				
	echo "
	<div class='$css'>
	$dsc_script
	</div>
	";
}
//-------------------------------------------------------
function ShowSocialNetwork($row) {
//	var_dump($row);
	$ListSocialTag=array(
	'<i class="fa fa-instagram"></i>', 
	'<i class="fa fa-telegram"></i>', 
	'<i class="fa fa-facebook"></i>', 
	'<i class="fa fa-twitter"></i>', 
	'<i class="fa fa-linkedin"></i>', 
	'<i class="fa fa-whatsapp"></i>', 
	'<img src="/assets/image/social/aparat.png" style="width:25px;margin-top:-4px;">', 
	'<i class="fa fa-youtube"></i>', 
	'<img src="/assets/image/social/eitta.png" style="width:25px;margin-top:-4px;">' ,
	'<img src="/assets/image/social/baleh.png" style="width:25px;margin-top:-4px;">' ,
	'<img src="/assets/image/social/rubika.png" style="width:25px;margin-top:-4px;">' ,
	'<img src="/assets/image/social/gap.png" style="width:25px;margin-top:-4px;">' ,
	'<img src="/assets/image/social/sorush.png" style="width:25px;margin-top:-4px;">' ,
	);
	$id  = $row['id'];					$name=$row['name'];
	$css = $row['col_width'];			$dsc = $row['dsc'];				
	$dsc_cyber = $row['dsc_cyber'];
	if ($dsc!='') $dsc = "<h5 class='line-height: 2;  margin-bottom: 0;  display: inline-block;  color: #333; margin-left: 20px;  font-size: 16px;'>$dsc</h5>";
	$ListCyber = explode("\n",$dsc_cyber);
	$TagSocial = "";
	foreach($ListCyber as $key=>$value) {
		$icon = $ListSocialTag[$key];
		if ($value!='') $TagSocial .= "<li><a href='$value' target='_blank'>$icon</a></li> \r\n";
	}
	echo "
	<div class='$css'>
		<div class='footer-populated text-left'>
		<div class='footer-social'>
			$dsc
			<ul style='display: flex;float: left;' >
				$TagSocial
			</ul>
		</div>
		</div>
	</div>
	";
}

//-------------------------------------------------------
function ShowText($row) {
//	var_dump($row);
	global $upload_path_main;
	$id_type = $row['id_type'];		$text_align = $row['text_align'];
	$name=$row['name'];
	$id  = $row['id'];				$css = $row['col_width'];
	$link= $row['link'];			$photo = $row['photo_abzar'];
	$dsc = $row['dsc'];				$dsc_link = $row['dsc_link'];
	switch ($text_align) {
		case 1: $StyleCss=" style='text-align:left;' "; 	$PhotoAlign=" align='left' "; break;
		case 2: $StyleCss=" style='text-align:center;' "; 	$PhotoAlign=" align='center' "; break;
		case 3: $StyleCss=" style='text-align:right;' "; 	$PhotoAlign=" align='right' "; break;
		case 4: $StyleCss=" style='text-align:justify;' "; 	$PhotoAlign=" class='img-fluid' "; break;
	}
	if ($photo!='') 	{		$photo = $upload_path_main.$photo;}
	$PhotoTag="";
	if ($photo!='') { 
		$PhotoTag="<img src='$photo' $PhotoAlign title='$name'>";
	}
	// script or editor
	if ( ($id_type==16) ) { 
		echo "	<div class='$css'>$dsc</div>";	
	}	
	// text
	if ( ($id_type==10) ) { 
		$dsc = nl2br($dsc);
		echo "	<div class='$css' $StyleCss>$PhotoTag $dsc</div>";	
	}
	// link
	if ( ($id_type==15))  { 
		if ($PhotoTag=='') $PhotoTag=$name;
		echo "<div class='$css' title='$dsc_link'><a href='$link'>$PhotoTag </div>";
	}
}

//-------------------------------------------------------
function ShowTabligh($row) {
//	var_dump($row);
	global $upload_path_main;
	$name=$row['name'];
	$id  = $row['id'];				$css = $row['col_width'];
	$link= $row['link'];			$photo = $row['photo_abzar'];
	if ($photo!='') {
		$photo = $upload_path_main.$photo;
	echo "
	<div class='$css'>
		<div class='adplacement-container-column mt-4'>
			<a href='$link' class='adplacement-item img-banner'>
				<div class='adplacement-sponsored-box mobile-banner2'>
					<img src='$photo'  title='$name' alt='$name'>
				</div>
			</a>
        </div>
    </div>";
	}
}
//-------------------------------------
function ShowSlider($row) {
	global $upload_path_main;
//	var_dump($row);
	$id  = $row['id'];	
	$css = $row['col_width'];
	$type_slider = $row['type_slider']; //1 --> full slider
	$id_slider = 'slider_'.$id;
	$counter=0;
	$TagUp = ''; 				$TagDown = '';				$TagMain = '';
	$query0 = pdo_query("select * from `them_gallery` where id_them_detils='$id' and isdeleted=0 order by idsort ",'',0);
	while ($row_gallery=pdo_fetch($query0)) {
//		$tetr_photo = $row_gallery['photo_name'];
		$temp_active1 = '';			$temp_active2 = '';
		$StartLink = '';			$EndLink = '';
		$TagHeader="";				$photo = '';
		if ($row_gallery['photo']!='') {
			$photo = $upload_path_main . $row_gallery['photo'];
		}
		if (file_exists(".$photo")) {
			$name  = $row_gallery['name'];
			$link  = $row_gallery['link'];
			if ($link=='') $linktag='#'; else $linktag=$link;
			if ($link!='') { $StartLink = "<a href='$link' target=_blank>"; $EndLink = "</a>";}
			if ($type_slider==1) {
				if ($counter==0) { $temp_active1 = " class='active' "; $temp_active2 = " active "; }
				$TagUp 	 .= "<li data-target='#$id_slider' data-slide-to='$counter' $temp_active1 ></li> \r\n";
				$photo_mobile=$photo;
				$TagDown .= "<div class='carousel-item $temp_active2'>
								$StartLink 
								<picture class='mobile-slider'>
									<source media='(max-width: 767px)' 
									srcset='$photo_mobile'>
									<img src='$photo' class='responsive-banner' alt='$name' title='$name'>
								</picture>								
								<!-- <img src='$photo' class='d-block w-100' alt='$name' title='$name'>  -->
								$EndLink
							</div> \r\n";
			}
			if ($type_slider==2) {
				$TagMain.="
				<div class='owl-item' style='width: 309.083px; margin-left: 10px;'>
					<div class='item'>
						<a href='$linktag' class='d-block hover-img-link' >
							<img src='$photo' class='img-fluid' alt='$name' >
						</a>
					</div>
				</div>
				";
			}
			if ($type_slider==3) {
				if ($name!='') $TagHeader="<h2 class='post-title'><a href='$linktag' style='font-size:16px;'>$name</a></h2>";
				$TagMain.="
				<div class='owl-item' style='width: 309.083px; margin-left: 10px;'>
					<div class='item'>
						<a href='$linktag' class='d-block hover-img-link' >
							<img src='$photo' class='img-fluid' alt='$name' style='border-radius: 50%;max-height:150px;  width:auto;'>
						</a>
						$TagHeader
					</div>
				</div>
				";
			}
			if ($type_slider==4) {
				if ($name!='') $TagHeader="<h3 class='product-title'><a href='$linktag' style='font-size:16px;'>$name</a></h3>";
				$TagMain .= "
				<div class='owl-item' style='width: 273.75px;'>
					<div class='item'>
						<a href='$linktag'><img src='$photo' class='w-100' alt=''></a>
						$TagHeader
					</div>
				</div>
				";
			}
			if ($type_slider==5) {
				if ($name!='') $TagHeader="<h2 class='post-title'><a href='$linktag' style='font-size:16px;'>$name</a></h2>";
				$TagMain .= "
				<div class='owl-item tab-item active' style='width: 222.313px; margin-left: 10px;'>
					<div class='item'>
						<a href='$linktag' class='d-block hover-img-link'>
							<img src='$photo' class='img-fluid' alt='$name' style='border-radius: 50%;max-height:150px;  width:auto;'>
						</a>
						$TagHeader
					</div>
				</div>
				";	
			}
			$counter++;
		}
	}
	if ($type_slider==1) {
	echo "

	
	<div class='$css'>
		<div class='slider-main-container d-block'>
			<div id='$id_slider' class='carousel slide' data-ride='carousel'>
				<ol class='carousel-indicators'>
					$TagUp
				</ol>
				<div class='carousel-inner'>
					$TagDown
				</div>
				<a class='carousel-control-prev' href='#$id_slider' role='button'
					data-slide='prev'>
					<span class='fa fa-angle-left' aria-hidden='true'></span>
					<span class='sr-only'>قبلی</span>
				</a>
				<a class='carousel-control-next' href='#$id_slider' role='button'
					data-slide='next'>
					<span class='fa fa-angle-right' aria-hidden='true'></span>
					<span class='sr-only'>بعدی</span>
				</a>
			</div>
		</div>	
	</div>
	";
	}
	if ($type_slider==2) {
		echo "
		<div class='$css'>
			<div class='slider-widget-products'>
				<div class='widget widget-product card'>
					<div class='product-carousel owl-carousel owl-theme owl-rtl owl-loaded owl-drag'>
						<div class='owl-stage-outer'>
							<div class='owl-stage'
								style='transform: translate3d(0px, 0px, 0px); transition: all 0s ease 0s; width: 2234px;'>
								$TagMain
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	";
	}
	if ($type_slider==3) {
		echo "
		<div class='$css'>
			<div class='slider-widget-products'>
				<div class='widget widget-product card'>
					<div class='product-carousel owl-carousel owl-theme owl-rtl owl-loaded owl-drag'>
						<div class='owl-stage-outer'>
							<div class='owl-stage'
								style='transform: translate3d(0px, 0px, 0px); transition: all 0s ease 0s; width: 2234px;'>
								$TagMain
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	";
	}
	// اسلایدر در جا و فول برای جاهای کوچک
	if ($type_slider==4) {
		echo "
		<div class='$css'>
			<div class='slider-moments'>
				<div class='widget-suggestion widget card'>
					<div id='suggestion-slider' class='owl-carousel owl-theme owl-rtl owl-loaded owl-drag'>
						<div class='owl-stage-outer'>
							<div class='owl-stage'
								style='transform: translate3d(1369px, 0px, 0px); transition: all 0.25s ease 0s; width: 2190px;'>
								$TagMain
							</div>
						</div>
					</div>
					<div id='progressBar'>
						<div class='slide-progress' style='width: 100%; transition: width 5000ms ease 0s;'></div>
					</div>
				</div>
			</div>
		</div>
		";
	}	
	// تیکه تیکه با فاصله و در صورت داشتن عنوان تصویر ذیلش می آید
	if ($type_slider==5) {
		echo "
		<div class='slider-widget-products slider-content-tabs $css'>
			<div class='widget widget-product card slider-content-tabs'>
				<div class='product-carousel product-amazing owl-carousel owl-theme owl-rtl owl-loaded owl-drag'>
					<div class='owl-stage-outer'>
						<div class='owl-stage'
							style='transform: translate3d(0px, 0px, 0px); transition: all 0s ease 0s; width: 1162px;'>
							$TagMain
						</div>
					</div>
				</div>
			</div>
		</div>
		";	
		
	}
		
}
//---------------------------
function ShowProduct($row) {
//	var_dump($row);exit;
	global $upload_path_main;
	global $GroupProduct;
	global $logofile, $price_unit;
	global $nowdate, $nowtime, $nowdatetime, $shartdatetime, $shartdatetimeWeblog;
	global $row_setting;
	$id_type = $row['id_type'];
	if ($id_type==20) { 
		$link_main = '/CategoryWeblog/';
		$bank1='weblog'; 
		$shart = " where $bank1.active=2 $shartdatetimeWeblog ";
		$source_data	= $row['source_data_weblog'];
		$sort_data	  	= $row['sort_data_weblog'];
	} else {	
		$link_main = '/CategoryProduct/';
		$bank1 = 'product';	$bank2 = 'product_price';
		$shart = " where $bank1.active=2  and isdeleted=0 $shartdatetime ";
		$source_data	= $row['source_data_product'];
		$sort_data	  	= $row['sort_data_product'];
	}
//	echo 'aaa';	var_dump($row);
	$id  = $row['id'];					
	$name = $row['name'];
	$css = $row['col_width'];			
	$count_show 		= $row['count_show'];
	if ($count_show<=0 or $count_show>50) $count_show=20;
	$filter_data	= $row['filter_data'];
	$type_slider 	= $row['type_slider']; //5 --> با فاصله
	$orderby = '';
	//example: SELECT product.*, product_price.cash,product_price.price1,product_price.price2 FROM `product` inner join product_price on product.id = product_price.id_product group by product.id order by product.id 
	//-------------
	if ($source_data >0) { // اگر گروهی را انتخاب کرده بود
/*		// در صورتی که بخواهیم هر گروه محصولات با کدهای بالاتریش جستجو شود
		$ListTopic=array();
		$ListTopic = GetParentWithListId($source_data,$GroupProduct);
		$temp = '';
		foreach ($ListTopic as $key=>$value) {
			$temp .= " ('id_topics' like '%,$value,%') or ";
		}
		$temp = mb_substr($temp,0, -4);
*/		
		$shart .= "and ($bank1.id_topics LIKE '%,$source_data,%') ";
		$link_main .=  $source_data;
	}
	if ($bank1=='product') {
		switch ($filter_data) {
			//همه محصولات
			case 1: break;
			//محصولات که موجودی دارند
			case 2: $shart .= " and ($bank2.cash>0 or cash_unlimited=1) ";	break;
			//محصولات دارای تخفیف
			case 3: $shart .= " and ($bank2.price2>0) ";						break;
			//محصولات پرفروش
			case 4: $shart .= " and ($bank1.best_saller>0) ";					break;
			//محصولات ویژه
			case 5: $shart .= " and ($bank1.spectioal>0) ";					break;
		}
		switch ($sort_data) {
			//جدیدترین
			case 1: $orderby = " order by $bank1.date_create desc";	break;
			//الفبا
			case 2: $orderby = " order by $bank1.tetr  ";			break;
			//پربازدیدترین
			case 3: $orderby = " order by $bank1.countdn desc  ";	break;
			//پربحث ترین
			case 4: $orderby = " order by $bank1.nazarcount  desc ";break;
			//تاریخ انتشار
			case 5: $orderby = " order by $bank1.pdate , $bank1.ptime  ";	break;
			//پرامتیازترین
			case 6: $orderby = " order by $bank1.vote_sum  desc ";	break;
			//گرانترین
			case 7: $orderby = " order by $bank2.price1  desc ";	break;
			//ارزانترین
			case 8: $orderby = " order by $bank2.price1  ";			break;
			//پرتخفیف ترین
			case 9: $orderby = " order by ($bank2.price1-$bank2.price2) desc  ";			break;
			//آخرین ویرایش
			case 10: $orderby = " order by $bank1.date_last_edit desc";			break;
		}
		$sql_query = "select $bank1.id, $bank1.tetr, $bank1.leds, $bank1.photo_product, $bank1.vote_sum, $bank1.vote_count, $bank1.url, $bank1.url_redirect, $bank1.pdate, $bank2.id as id_price, $bank2.price1, $bank2.price2, $bank2.cash, $bank2.cash_unlimited from `$bank1` inner join `$bank2` on $bank1.id=$bank2.id_product $shart group by $bank1.id $orderby limit 0,$count_show";
	}
	if ($bank1=='weblog') {
		switch ($sort_data) {
			//جدیدترین
			case 1: $orderby = " order by $bank1.date desc, $bank1.time desc";	break;
			//الفبا
			case 2: $orderby = " order by $bank1.tetr  ";			break;
			//پربازدیدترین
			case 3: $orderby = " order by $bank1.countdn desc  ";	break;
			//پربحث ترین
			case 4: $orderby = " order by $bank1.nazarcount  desc ";break;
		}
		$sql_query = "select $bank1.* from `$bank1` $shart  $orderby limit 0,$count_show";
	}
	//-------------
	$PriceTag= "";			$PriceFinal= "";
	$TagMain = "";			
	$UpTag="";				$DownTag="";
	$counter=0;
	//----------------------------
	$product_temp=array();
//	echo "type_slider=$type_slider";
	$query0 = pdo_query($sql_query,'',0);
	while ($row_product=pdo_fetch($query0)) {
//		var_dump($row_product);
		$darsad=0;	$price01=0;		$price02=0;		$price1='';			$price2='';
		$id    = $row_product['id'];					
		$tetr  = $row_product['tetr'];			
		$leds  = $row_product['leds'];					
		if ($leds=='') $leds = "&nbsp;&nbsp;&nbsp;&nbsp;";
		$tetr_photo = $tetr;
		$tetr_main  = $tetr;
		$TagBuyVisiblity = '';
		if ($bank1=='product') {
			$id_price = $row_product['id_price'];
			//---------------------
			$tetr2 = GetProductProperty($id, $id_price);
			$tetr_schema = $tetr;
			if ($tetr2!='') {
				$tetr .= "<span class='text-danger' style='font-size:smaller'>$tetr2</span> ";
				$tetr_schema .=  " $tetr2";
			}
			
			//---------------------
//			var_dump($row_product);exit;
			$tetrseo	= $row_product['url'];
			if ($tetrseo=='') $tetrseo=$tetr_photo;
			$link = $row_product['url_redirect'];
			if ($link=='') $link  = "/product/$id/" . LinkSeo($tetrseo);;
			$StrDate = $row_product['pdate'];		
			$price01= ($row_product['price1']);
			$price02= ($row_product['price2']);
			if ($price02>0) {
				$darsad= number_format(100 - (($price02*100)/$price01),2);
			}
			if ($price02>0) { $price_product   = $price02;}else{$price_product   = $price01;}
			
			$price1= number_format($price01);
			$price2= number_format($price02);
			$photo = $row_product['photo_product'];
			if ($price02>0) {
				$PriceFinal = "<ins><span>$price2<span> $price_unit </span></span></ins>";
				$PriceTag = "
				<del><span>$price1<span> $price_unit </span></span></del>
				<ins><span>$price2<span> $price_unit </span></span></ins>
				";
			}else{
				$PriceFinal = "<ins><span>$price1<span> $price_unit </span></span></ins>";
				$PriceTag = "
				<del><span>&nbsp;</span></del>
				<ins><span>$price1<span> $price_unit </span></span></ins>
				";
			}
			$cash = $row_product['cash'];	
			if ($row_product['cash_unlimited']==1) $cash=999999999;
			if ( $cash<=0 ) {
				$PriceFinal = "<ins><span><span class='btn btn-danger'>ناموجود</span></span></ins>";
				$PriceTag = "<del><span>&nbsp;</span></del><ins><span><span class='btn btn-danger'>ناموجود</span></span></ins>
				";
				$TagBuyVisiblity = " style='visibility:hidden;' ";
			}
			$PriceDate = $PriceFinal;
			$product_temp[] = array('name'=> $tetr_schema, 'url'=>$link, 'price'=>$price_product, 'cash'=>$cash, 'image'=>$photo, 'id'=>$id, 'dsc'=>$leds, 'vote_sum'=>$row_product['vote_sum'], 'vote_count'=>$row_product['vote_count']);
			//------------------- get list of price product						
			$TagListPrice='';
			$query_temp = pdo_query("select * from `product_price` where id_product='$id'   ",'',0);
			$counter2=0;
			$ListPrice = array();
			$TagDivPrice='';
			while ($row_temp = pdo_fetch($query_temp)) {
				$id_price = $row_temp['id'];
				$Detils ='';
				//-------------------
				$priceP1= $row_temp['price1'];			$priceP2 = $row_temp['price2'];
				$cashP 	= $row_temp['cash'];			if ($row_temp['cash_unlimited']==1) $cashP=999999999;
				
				//--------------------------
				$ListPrice[$counter2] = $row_temp;
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
				$ListPrice[$counter2]['detils'] = $Detils;
				$counter2++;
				$TagListPrice .= "<option value='$id_price' data-price1='$priceP1' data-price2='$priceP2' data-cash='$cashP'> $Detils</option>";
				if ($Detils=='') {$TagDivPrice = "visibility:hidden;";  }
				
			}
			if ($TagListPrice != "") {
				$TagListPrice = "<div class='price-list' style='width:80%;margin:auto; $TagDivPrice' >
				<select id='ProductProce$id' name='ProductProce$id' class='form-control' onchange='UpdatePrice($id);' >
				$TagListPrice
				</select>
					</div>";
			}
		//	var_dump($product_temp);
		}
		if ($bank1=='weblog') {
			$StrDate = $row_product['date'];
			$tetrseo	= $row_product['url'];
			if ($tetrseo=='') $tetrseo=$tetr;
			$link = $row_product['url_redirect'];
			if ($link=='') $link  = "/weblog/$id/" . LinkSeo($tetrseo);;
			$photo = $row_product['photo_news'];
			$PriceFinal = '';		$PriceTag='';
			$PriceDate = "<i class='fa fa-calendar'></i> ".$StrDate;
		}
		if ($photo=='') {$photo=$logofile;} 
			else {$photo=$upload_path_main.$photo; }
		//---------------------------------------------------
		// اطلاعات به صورت زیرهم مخصوص صفحات دوم
		if ($type_slider==6) {
			$TagMain .= "
			<div class='item'>
				<div class='item-inner'>
					<div class='item-thumb'>
						<a href='$link' class='img-holder d-block' target='_blank'>
							<img src='$photo' alt='$tetr_photo'>
						</a>
					</div>
					<div class='title'>
						<a href='$link' target='_blank' class='title-tag-new'>$tetr</a><BR>
						<div class='post-date'>$PriceDate</div>
					</div>
				</div>
			</div>
			";
		}
		
		// اسلایدر در جا و فول برای جاهای کوچک
		if ($type_slider==4) {
			$TagMain .= "
			<div class='owl-item' style='width: 273.75px;'>
				<div class='item'>
					<a href='$link'><img src='$photo' class='w-100' alt='$tetr_photo'></a>
					<h3 class='product-title'><a href='$link'>$tetr</a></h3>
					<div class='price'>$PriceFinal</div>
				</div>
			</div>
			";
		}
		// اسلایدر چند تیکه به صورت ساده
		if ($type_slider==3) {
//			var_dump($row_product);
			$UpTag.="
			<div class='owl-item' style='width: 309.083px; margin-left: 10px;'>
				<div class='item'>
					<a href='$link' class='d-block hover-img-link' >
						<img src='$photo' class='img-fluid' alt='$tetr_photo' style='border-radius: 50%;max-height:150px;  width:auto;'>
					</a>
					<h2 class='post-title'><a href='$link'>$tetr</a></h2>
					<div class='price'>
					$PriceTag
					</div>
				</div>
			</div>
			";
		}

		// اسلایدر چند تیکه به صورت پیشرفته
		if ($type_slider==2) {
			$temp_link = "";
			if ($cash>0 ) {
				$temp_link = "<button class='btn btn-light' onclick='AddToCart($id,$id_price,1);' title='افزودن به سبد خرید'><i class='fa fa-shopping-cart'></i></button>";
			}
			$UpTag.="
			<div class='owl-item' style='width: 309.083px; margin-left: 10px;'>
				<div class='item'>
					<a href='#' class='d-block hover-img-link' data-toggle='modal'
						data-target='#Modal$id'>
						<img src='$photo' class='img-fluid' alt='$tetr_photo' style='border-radius: 50%;max-height:150px;  width:auto;'>
						<span class='icon-view'>
							<strong><i class='fa fa-eye'></i></strong>
						</span>
					</a>
					<h2 class='post-title'><a href='#'>$tetr</a></h2>
					<div class='price'>
					$PriceTag
					</div>
					<div class='actions'>
						<ul>
							<li class='action-item like'>
								<button class='btn btn-light' onclick='AddWish($id);' title='افزودن به علاقه مندی ها'>
									<i class='fa fa-heart-o'></i>
								</button>
							</li>
							<li class='action-item compare'>
								<button class='btn btn-light' onclick='Compare($id);' title='مقایسه'>
									<i class='fa fa-random'></i>
								</button>
							</li>
							<li class='action-item add-to-cart'>$temp_link</li>
						</ul>
					</div>
				</div>
			</div>
			";
			$DownTag.="
			<div class='modal fade' id='Modal$id' tabindex='-1' role='dialog'
				aria-labelledby='exampleModalLabel' aria-hidden='true'>
				<div class='modal-dialog'>
					<div class='modal-content'>
						<div class='modal-header'>
							<button type='button' class='close' data-dismiss='modal' aria-label='Close'>
								<span aria-hidden='true'>&times;</span>
							</button>
						</div>
						<div class='modal-body'>
							<div class='col-lg-6 pr'>
								<div class='thum-img'>
									<div class='widget widget-product card mb-0'>
										<div class='product-carousel-more owl-carousel owl-theme owl-rtl owl-loaded owl-drag'>
											<div class='owl-stage-outer'>
												<div class='owl-stage'
													style='transform: translate3d(1652px, 0px, 0px); transition: all 0.25s ease 0s; width: 2065px;'>
													<div class='owl-item active'
														style='width: 403px; margin-left: 10px;'>
														<div class='item'>
															<a href='/product/$id/' class='d-block hover-img-link'
																data-toggle='modal' data-target='#Modal$id'>
																<div class='zoom-box'>
																	<img src='$photo' width='200' height='150'>
																	<div class='discount-m'>
																		<span> $darsad %</span>
																	</div>
																</div>
															</a>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class='col-lg-6 pr'>
								<div class='product-box-modal-title'>
									<h2 class='post-title'>
										<a href='$link'>$tetr</a>
									</h2>
									<h6 class='post-title' style='color:gray;'>$leds</h6>
								</div>
								<div class='small-gutters align-items-stretch mb-4'>
									<div class='col-lg-12 pr-0 pl-0 pr'>
										<div class='product-box-modal_price mt-12 mt-auto'>
											<div class='price'>
											$PriceTag
											</div>
										</div>
									</div>
									<div class='small-gutters'>
										<div class='col-lg-12 mb-8 pr-0 pl-0 pr mt-3'>
											<div class='product-box_action'>
												<button class='btn btn-gradient-primary add-to-cart'
													onclick='AddToCart($id,$id_price,1);'>افزودن به سبد</button>
												<a href='$link' class='btn btn-outline-dark btn-block'>مشاهده
													جزئیات</a>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			";
		}
		//اسلایدر چند تیکه با فاصله
		if ($type_slider==1) {
			
			if ($counter==0) $Class1 = "active"; else $Class1='';
			$UpTag .= "
			<li class='$Class1' data-target='#amazing-slider' data-slide-to='$counter'>
				<img src='$photo' class='img-fluid'>
			</li>		
			";
			$DownTag.="
			<div class='carousel-item $Class1'>
				<div class='row m-0'>
					<div class='right-col col-5 d-flex align-items-center'>
						<a class='w-100 text-center img-link-amazing' href='$link'>
							<img src='$photo' class='img-fluid' alt='$tetr_photo' style='border-radius: 50%;'>
						</a>
					</div>
					<div class='col-7'>
						<div class='carousel-content'>
							<div class='discount'>
								<span class='discount-percent'>$darsad
									<i class='fa fa-percent'></i>
								</span>
							</div>
							<h2 class='product-title'>
								<a href='$link'>$tetr</a>
							</h2>
							<div class='price text-center'>
								$PriceTag
							</div>
							<ul class='list-group'>
								<li class='list-group-item'>
									<i class='mdi mdi-check text-success'></i>
									<span class='title'>$leds</span>
								</li>
							</ul>
						</div>
					</div>
				</div>
			</div>
			";
		}
		if ($type_slider==5) {
			$TagPhoto = "<img src='$photo' class='img-fluid' alt='$tetr_photo' style='border-radius: 50%;max-height:150px;  width:auto;'>";
			if ($bank1=='weblog') {
				$TagPhoto = "
				<article class='blog-item'>
					<figure class='figure'>
						<div class='post-thumbnail'>
							<img src='$photo' alt='$tetr_photo' style='min-height:227px;'>
						</div>

						<div class='post-title'>
							<a href='$link' class='d-block'>
								<h4>$tetr</h4>
							</a>
							<span class='post-date'>
								<i class='fa fa-calendar'></i>
								$StrDate
							</span>
						</div>
					</figure>
				</article>
				";
//				<img src='$photo' class='img-fluid' alt='$tetr' style='border-radius: 10px;max-height:150px;  width:auto;'>";
			}
			$TagMain .= "
			<div class='owl-item tab-item active' style='width: 222.313px; margin-left: 10px;'>
				<div class='item'>
					<a href='$link' class='d-block hover-img-link'>$TagPhoto</a>
					<h2 class='post-title'>
						<a href='$link' target=_blank style='font-size:16px;'>$tetr</a>
						<div class='title' style='font-size:12px;color:gray;'>$leds</div>
					</h2>
					
					<div class='price'>
					$PriceTag
					</div>
				</div>
			</div>
			";	
		}
		if ($type_slider==7) {
			$TagBuy = "
			<button id='AddCart_$id' $TagBuyVisiblity class='btn btn-success m-1' title='افزودن به سبد خرید' onclick='AddToCart($id,$(\"#ProductProce$id\").val(),1);'>
			<i class='fa fa-shopping-cart'></i> خرید
			</button>
			";
			
			$TagPhoto = "<img src='$photo' class='img-fluid' alt='$tetr_photo' style='border-radius: 50%;max-height:150px;  width:auto;'>";
			if ($bank1=='weblog') {
				$TagPhoto = "
				<article class='blog-item'>
					<figure class='figure'>
						<div class='post-thumbnail'>
							<img src='$photo' alt='$tetr_photo' style='min-height:227px;'>
						</div>

						<div class='post-title'>
							<a href='$link' class='d-block'>
								<h4>$tetr</h4>
							</a>
							<span class='post-date'>
								<i class='fa fa-calendar'></i>
								$StrDate
							</span>
						</div>
					</figure>
				</article>
				";
			}
			$TagMain .= "
			<div class='owl-item tab-item active' style='width: 222.313px; margin-left: 10px;'>
				<div class='item'>
					<a href='$link' class='d-block hover-img-link'>$TagPhoto</a>
					<h2 class='post-title'>
						<a href='$link' target=_blank style='font-size:16px;'>$tetr_main</a>
						<div class='title' style='font-size:12px;color:gray;'>$leds</div>
					</h2>
					$TagListPrice
					<div class='price' id='PriceList_$id'>
					$PriceTag
					</div>
					$TagBuy
				</div>
			</div>
			";	
		}		
		$counter++;
	}
	if ($counter==0) return;
	if (!empty($product_temp)) {		
		add_product_section($name, $product_temp);	
	}
	//-----------------------------------------------show main tag
//	echo "type_slider=$type_slider";
	
	//فول اسلایدر
	if ($type_slider==1) {
		echo "
		<div class='$css'>
			<div class='content-widget-amazing pb-4 mt-2'>
				<section id='amazing-slider' class='carousel slide carousel-fade card' data-ride='carousel'>
					<div class='row m-0'>
						<ol class='carousel-indicators pr-0'>
							$UpTag
							<a class='carousel-control-prev' href='#amazing-slider' role='button' data-slide='prev'>
								<span class='fa fa-angle-left' aria-hidden='true'></span>
								<span class='sr-only'>Previous</span>
							</a>
							<a class='carousel-control-next' href='#amazing-slider' role='button' data-slide='next'>
								<span class='fa fa-angle-right' aria-hidden='true'></span>
								<span class='sr-only'>Next</span>
							</a>
						</ol>
						<div class='carousel-inner p-0 col-12'>
							$DownTag
						</div>
					</div>
				</section>
			</div>
		</div>
		";
	}
	if ($type_slider==2) {
		echo "
		<div class='$css'>
			<div class='slider-widget-products'>
				<div class='widget widget-product card'>
					<header class='card-header'>
						<span class='title-one'><a href='$link_main'>$name</a></span>
						<h3 class='card-title'></h3>
					</header>
					<div class='product-carousel owl-carousel owl-theme owl-rtl owl-loaded owl-drag'>
						<div class='owl-stage-outer'>
							<div class='owl-stage'
								style='transform: translate3d(0px, 0px, 0px); transition: all 0s ease 0s; width: 2234px;'>
								$UpTag
							</div>
						</div>
					</div>
				</div>
				$DownTag
			</div>
		</div>
	";
	}
	if ($type_slider==3) {
		echo "
		<div class='$css'>
			<div class='slider-widget-products'>
				<div class='widget widget-product card'>
					<header class='card-header'>
						<span class='title-one'><a href='$link_main'>$name</a></span>
						<h3 class='card-title'></h3>
					</header>
					<div class='product-carousel owl-carousel owl-theme owl-rtl owl-loaded owl-drag'>
						<div class='owl-stage-outer'>
							<div class='owl-stage'
								style='transform: translate3d(0px, 0px, 0px); transition: all 0s ease 0s; width: 2234px;'>
								$UpTag
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	";
	}
	// اسلایدر در جا و فول برای جاهای کوچک
	if ($type_slider==4) {
		echo "
		<div class='$css'>
			<div class='slider-moments'>
				<div class='widget-suggestion widget card'>
					<header class='card-header promo-single-headline'>
						<h3 class='card-title float-none'><a href='$link_main'>$name</a></h3>
					</header>
					<div id='suggestion-slider' class='owl-carousel owl-theme owl-rtl owl-loaded owl-drag'>
						<div class='owl-stage-outer'>
							<div class='owl-stage'
								style='transform: translate3d(1369px, 0px, 0px); transition: all 0.25s ease 0s; width: 2190px;'>
								$TagMain
							</div>
						</div>
					</div>
					<div id='progressBar'>
						<div class='slide-progress' style='width: 100%; transition: width 5000ms ease 0s;'></div>
					</div>
				</div>
			</div>
		</div>
		";
	}
	
	if ($type_slider==5) {
		echo "
		<div class='slider-widget-products slider-content-tabs $css'>
			<div class='widget widget-product card slider-content-tabs'>
				<header class='card-header'>
					<span class='title-one'><a href='$link_main'>$name</a></span>
					<h3 class='card-title'></h3>
				</header>
				<div class='product-carousel product-amazing owl-carousel owl-theme owl-rtl owl-loaded owl-drag'>
					<div class='owl-stage-outer'>
						<div class='owl-stage'
							style='transform: translate3d(0px, 0px, 0px); transition: all 0s ease 0s; width: 1162px;'>
							$TagMain
						</div>
					</div>
				</div>
			</div>
		</div>
		";	
	}
	if ($type_slider==6) {
		echo "
		<div class='shortcode-widget-area-sidebar $css'>
			<section class='widget-posts'>
				<div class='header-sidebar mb-3'><h3><a href='$link_main'>$name</a></h3></div>
				<div class='content-sidebar'>
				$TagMain
				</div>
			</section>
		</div>
		";
	}
	if ($type_slider==7) {
		echo "
		<div class='slider-widget-products slider-content-tabs $css'>
			<div class='widget widget-product card slider-content-tabs'>
				<header class='card-header'>
					<span class='title-one'><a href='$link_main'>$name</a></span>
					<h3 class='card-title'></h3>
				</header>
				<div class='product-carousel product-amazing owl-carousel owl-theme owl-rtl owl-loaded owl-drag'>
					<div class='owl-stage-outer'>
						<div class='owl-stage'
							style='transform: translate3d(0px, 0px, 0px); transition: all 0s ease 0s; width: 1162px;'>
							$TagMain
						</div>
					</div>
				</div>
			</div>
		</div>
		";	
		echo "
<script>
function UpdatePrice(id) {
	var Main = $('#ProductProce'+id);
    var selected = Main.find('option:selected');
	let price1 = selected.data('price1');
    let price2 = selected.data('price2');
    let cash   = selected.data('cash');
    let value  = selected.val();	
	let PriceTag='';
	let darsad = '';
	let StrDarsad='';
	let price_unit = '$price_unit';
	if (price2>0) {
		PriceTag = '<del><span>'+price1+'<span>  '+price_unit+' </span></span></del><ins><span>'+price2+'<span>  '+price_unit+'</span> </span></ins>';
	}else{
		PriceTag = '<del><span> &nbsp;</span></del><ins><span>'+price1+' <span> '+price_unit+'</span> </span></ins>';
	}	
	if ( cash<=0 ) {
		PriceTag = \"<span class='btn btn-danger'>ناموجود</span>\";
		$('#AddCart_'+id).css('visibility', 'hidden');
	}else {
		$('#AddCart_'+id).css('visibility', 'visible');
	}
	$('#PriceList_'+id).html(PriceTag);
}
</script>		
		";
	}
	
}
//--------------------------------------------
function ShowWeblogTopics($input) {
	global $GroupWeblog;
	$ret = '';

	$ListTopics = array_filter(explode(',',$input));
	if (count($ListTopics)==0) return '';

	foreach($ListTopics as $key=>$value) {
		$temp = $GroupWeblog[$value];
		$id     = $temp['ID'];		$parent = $temp['PARENT'];			$tetr = $temp['NAME'];
		$ret  .= "<a href='/CategoryWeblog/$id'>$tetr</a> - ";
	}
	if ($ret!='') $ret = mb_substr($ret,0,-3);
	return $ret;
}
//--------------------------------------------
function ShowPrdocutTopics($input) {
	global $GroupProduct;
	$ret = '';

	$ListTopics = array_filter(explode(',',$input));
	if (count($ListTopics)==0) return '';

	foreach($ListTopics as $key=>$value) {
		$temp = $GroupProduct[$value];
		$id     = $temp['ID'];		$parent = $temp['PARENT'];			$tetr = $temp['NAME'];
		$ret  .= "<a href='/CategoryProduct/$id'>$tetr</a> - ";
	}
	if ($ret!='') $ret = mb_substr($ret,0,-3);
	return $ret;
}
//---------------------------------------------------------------
function ShowServiceWithParent($service, $type=0) {
	global $GroupProduct, $GroupWeblog;
	$ret = '';
	$service=intval($service);
	if ( intval($service)==0) return $ret;
	if ($type==0) {
		$temp = $GroupProduct[$service];
		$id     = $temp['ID'];		$parent = $temp['PARENT'];			$tetr = $temp['NAME'];
		$ret  = "<a href='/CategoryProduct/$id'>$tetr</a>";
		while ($parent!=0) {
			$temp = $GroupProduct[$parent];
			$id     = $temp['ID'];		$parent = $temp['PARENT'];			$tetr = $temp['NAME'];
			$ret = "    <a href='/CategoryProduct/$id'>$tetr</a> » $ret ";
		}
	}else{
		$temp = $GroupWeblog[$service];
		$id     = $temp['ID'];		$parent = $temp['PARENT'];			$tetr = $temp['NAME'];
		$ret  = "<a href='/CategoryWeblog/$id'>$tetr</a>";
		while ($parent!=0) {
			$temp = $GroupWeblog[$parent];
			$id     = $temp['ID'];		$parent = $temp['PARENT'];			$tetr = $temp['NAME'];
			$ret = "    <a href='/$id'>$tetr</a> » $ret ";
		}
	}
	return $ret;
}
//---------------------------------------------------------------
function CheckPassword($password)  {
	$page_pass = $_POST['page_pass'];
	if (($page_pass!=$password) or ($page_pass=='')) {
		echo "
		<div class='row main m-5'>
		<div class='col-12 col-md-1'></div>
		<div class='col-12 col-md-8 text-center bg-danger text-white rounded mt-5 m-5 p-5'>
		این صفحه توسط رمز، مسدود می باشد، در صورت داشتن رمز صفحه آن را وارد نمایید <BR><BR>
		<form method='post' action=''>
		<input type='password' class='form-control' name='page_pass' id='page_pass' style='width:200px;margin: auto;direction:ltr;' >
		</form>
		</div>
		</div>
		";
		return false;
	}
	return true;
	
}
//----------------------------
function GetProductProperty($id, $id_price) {
	global $row_setting;
	$bankname_price 	= 'product_price';
	$bankname_property2 = 'product_price_property';
	$bankname_property	= 'product_property';
	$Detils ='';
	$query_temp2 = pdo_query("select * from `$bankname_property2` where id_price='$id_price'  ",'',0);
	while ($row_temp2 = pdo_fetch($query_temp2)){
		$id_property = $row_temp2['id_property'];
		$query_temp3 = pdo_query("select * from `$bankname_property` where id='$id_property' and id_product='$id'  ",'',0);
		$row_temp3 = pdo_fetch($query_temp3);
		if ($row_setting['show_product_property']==1) {
			if ($row_temp3!='') { $Detils .= " [$row_temp3[tetr] :$row_temp2[content] ] ";}
		}else{
			if ($row_temp3!='') { $Detils .= " [$row_temp2[content] ] ";}
		}
	}
	return $Detils;
}
//------------------------
function add_product_section(string $section_title, array $products_list) {
	global $ListProductSchema;
// اگر لیست محصولات خالی نباشد، آن را اضافه می‌کنیم
    if (empty($products_list)) {
        return;
    }
    
    // ساختاردهی خروجی برای آن بخش
    $section_output = [
        'title' => $section_title, // ستون اول: تیتر بخش
        'products' => []           // ستون دوم: آرایه‌ای از محصولات
    ];

    // افزودن جزئیات نام و آدرس (URL) هر محصول به آرایه products
    foreach ($products_list as $product) {
        // فرض می‌کنیم داده‌های خام محصول، 'name' و 'url' را دارند
        $section_output['products'][] = [
            'name' => $product['name'],
            'url'  => $product['url'],
			'price'=>$product['price'],
			'cash' =>$product['cash'],
			'image'=>$product['image'],
			'id'=>$product['id'],
			'dsc'=>$product['dsc'],
			'vote_sum'=>$product['vote_sum'],
			'vote_count'=>$product['vote_count']
        ];
    }
    
    // اضافه کردن بخش جدید به آرایه نهایی (با ارجاع)
    $ListProductSchema[] = $section_output;	

	
}
?>