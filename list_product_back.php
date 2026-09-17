<?php 

include('topmain.php');

if (isset($_REQUEST['service']))   	$service = intval($_REQUEST['service']);		else $service = 0;

if (isset($_REQUEST['typesort']))  	$typesort = intval($_REQUEST['typesort']); 		else $typesort = 0;

if (isset($_REQUEST['varsearch']))  $varsearch= $_REQUEST['varsearch'];	 			else $varsearch = '';

if (isset($_REQUEST['pagenumber'])) $pagenumber = intval($_REQUEST['pagenumber']); 	else $pagenumber = 1;

if (isset($_REQUEST['scope']))  	$scope 	= intval($_REQUEST['scope']); 			else $scope = 0;

if (isset($_REQUEST['pagesize']))   $pagesize = intval($_REQUEST['pagesize']);		else $pagesize = 0;

$canonical  = "$canonical/CategoryProduct/";

if (isset($_REQUEST['service'])) $canonical  = $canonical.$service; 



$schema='CollectionPage';

$bankname='product';



if ($pagenumber==0) $pagenumber=1;

if ( ($service==1) or ($service==2) ) $service=0; 



$type_group=4; // کد اختصاصی گروه فروشگاه در لیست صفحات

$bank1='product'; 	

$bank2='product_price'; 	

$fieldlist1 = array('all', 'tetr','leds', 'matn', 'tags', 'pdate', 'edate'); 

$fieldlist2 = array('همه', 'عنوان', 'عنوان دوم', 'معرفی محصول',  'برچسب ها','تاریخ انتشار', 'تاریخ انقضا' ); 



$fieldsort1 = array("$bank1.date_create ", "$bank1.countdn desc", "$bank1.nazarcount desc", " ($bank1.vote_sum / $bank1.vote_count) DESC", "$bank1.pdate desc, $bank1.ptime desc", "$bank2.price1", "$bank2.price1  desc ", "$bank1.best_saller desc", "$bank1.spectioal desc" ." ($bank2.price1-$bank2.price2) desc  " ); 

$fieldsort2 = array('تاریخ ثبت', 'پربازدیدترین',  'پربحث ترین', 'محبوب ترین', 'جدیدترین',  'ارزانترین', 'گرانترین', 'محصولات پرفروش', 'محصولات ویژه', 'بیشترین تخفیف' ); 



if ( ($scope<0)		or ($scope > (count($fieldlist1)-1) ) ) $scope=0;



if ( ($typesort<0) 	or ($typesort > (count($fieldsort1)-1) ) ) $typesort=0;

$sortquery = " order by " . $fieldsort1[$typesort] ;



$shart = " where $bank1.active=2 and isdeleted=0 $shartdatetime and ";

$MainParentService=",$service,";

$ErrorMsg = '';

$parent_group=0;

$imagesizegroup = "";

//$ListRpp = array(20,50,100);

switch ($service) {

	case -2:		

		$parent_group=-2;

		$title='جستجو'; 

		break;

	case -1:		 // ویژه برنامه های منتخب

		$parent_group=-1;

		$title='برنامه های منتخب'; 

		$shart .= " special =1 and ";	 

		break;

	case 0:

		$parent_group=0;

		$title='همه گروه ها'; 

		break;

	default:

		$parent=0;

		$query=pdo_query("select * from `$TableGroup` where id='$service' and type_group='$type_group' and active='1' ") ;

		$row=pdo_fetch($query);

		if ($row=='') {

			$ErrorMsg = 'کد گروه مربوطه اشتباه می باشد';

		}else {

			$title = $row['name'];	$parent= $row['parent'];	

		}

		$shart .= " $bank1.id_topics regexp ',$service,' and ";

}

//------------------------

include('top.php');

//نمی خواهد اتوماتیک در فایل top.php متغیر ErrorMsg پر باشد نمایش می دهد

//if ($ErrorMsg!='') {	ShowMessage($ErrorMsg,0); return;}

$pdo_array='';



$fieldsearch = $fieldlist1[$scope];

$SizeVarSearch = mb_strlen($varsearch, 'UTF-8') ;

if ( ($SizeVarSearch != 0) ) { 

	if ( ( ($SizeVarSearch<3) or ($SizeVarSearch>30)) ) { 

		echo "<div class='row'></div>";

		ShowMessage("طول رشته جستجو درست نمی باشد",0); include('bottom.php');  return;

	}

	$ReferPage  = $_SERVER['HTTP_REFERER'];		$HostScript = '://'.$_SERVER["HTTP_HOST"];

	$ok_refer = strpos($ReferPage, $HostScript);	

//	if ($ok_refer === false) { echo 'Error'; exit; }// 'hacking';

}

//----------------	

//----------

$what  = clean(trim($varsearch),1,1);

if ($what != '') { 

	$parent_group=-2;

	$what2=$what;

	$what2 = str_replace('ک','ك',$what2);

	$what2 = str_replace('ی','ي',$what2);

	if ($fieldsearch=='all') {

		$shartvar =" (";

		$pdo_array = array();

		for ($iz=1;$iz<count($fieldlist1);$iz++) {

			$fieldsearch = $fieldlist1[$iz];

			if ($what != $what2) { 

				$shartvar .= "( binary $bank1.$fieldsearch like ? or  binary $bank1.$fieldsearch like ? ) or ";

				array_push($pdo_array, "%$what%", "%$what2%");		

			} else {

				$shartvar .= "(binary $bank1.$fieldsearch like ?) or ";

				array_push($pdo_array, "%$what%");		

			}

		}

		$shart .= mb_substr($shartvar,0,-3,'utf-8') . ')';

	}else{

		if ($what != $what2) { 

			$shartvar ="( binary $bank1.$fieldsearch like ? or  binary $bank1.$fieldsearch like ? ) and ";

			$pdo_array = array("%$what%", "%$what2%");		

		} else {

			$shartvar ="binary $bank1.$fieldsearch like ? and ";

			$pdo_array = array("%$what%");		

		}

		$shart .= " $shartvar  "; 

		$shart .= " 1=1 ";

	}

}else{

	$shart .= " 1=1 ";

}

$countrecAll = "20,40,60,80,100";

$ListCountRec = explode(",", $countrecAll);					

if (!(in_array($pagesize, $ListCountRec))) $pagesize=0;

if ($pagesize==0) { 

	$countrec = $ListCountRec[0];

	$pagesize = $countrec; 

} else { 

	$countrec = $pagesize; 

}

$OkVote = true;

if ($row_setting['ok_save_vote']==1) {	if (!$ok_cookie) $OkVote= false;}
if ($row_setting['ok_save_vote']==2)  $OkVote = false;
$javacode='';

/*

یک مثال از امتیازدهی ستاره ای که سالم کار می کرد در بالای صفحه

$idtopics=194;		$id=265;	$counter=111111;		$vote_final=50;		$vote_sum=250;

$StrVote = "

<div class='post-vote' id='vote$idtopics-$id-container'>

	<span style='font-size:12px;color:#888;' >امتیاز:</span>

	<span style='font-size:12px;color:#888;' id='desc$counter'> </span> 

	<div id='votecontainer$counter' style='display: inline-block;direction:ltr;'></div>

	<span style='font-size:12px;color:#888;' id='1desc$counter'></span>  

</div>

";

$javacode .= " 

vote$counter =new starVote({ vote:0,curStars:$vote_final,score:$vote_sum,containerId:'votecontainer$counter', descriptionId:'desc$counter', key:'$id', stars:'5', extraDatas:'groupid=$idtopics&stars=5'}); \n";

echo $StrVote;

echo "<script>$javacode</script>"; $javacode='';

*/



?>

<style>

.search-box h6 {  font-size: 13px;  white-space: nowrap;}

</style>

<div class='row container-main'>

	<div class='d-block'>

	<!-- search section  -->

	<div class='col-12 archive-header pr mt-1'>

	<form id='myform' action='/CategoryProduct/' method='post'>

	<?php  if ( ($service==-2) or ($varsearch!='') ) { // search active ?>

    <section class="search-box row" style="margin: 0; width: 100%">

	<?php }else { ?>

	<section class="search-box row d-md-flex d-lg-flex d-xl-flex" style="margin: 18px 0; width: 100%">

	<?php } ?>

		<div class="col-12 col-sm-6 col-lg-3 mt-2">

			<h6>انتخاب گروه</h6>

			<select name="service"  class="form-control" aria-label="Default select example">

				<option value='0' > همه گروه ها</option>

				<?php 

				$ListCategories = read_create_tree("select * from `$TableGroup` where active=1 and isdeleted=0 and type_group='$type_group' order by parent, idsort", 'id', 'parent', 'name');

				if(isset($ListCategories) and (count($ListCategories)>0) ) { 

					$groupid = $service;

					createCombbox($ListCategories, 0); 

				}

				?>

			</select>

		</div>

		<div class="col-12 col-sm-6 col-lg-3 mt-2">

			<h6>عبارت جستجو</h6>

			<input	name="varsearch"  type="text" value="<?php echo $varsearch;?>"	class="form-control"	aria-label="Sizing example input"	aria-describedby="inputGroup-sizing-default"	placeholder="">

		</div>

		<div class="col-12 col-sm-6 col-lg-2 mt-2">

			<h6>محل جستجو:</h6>

			<select name="scope" class="form-control" aria-label="Default select example">

				<?php 

				for ($iz=0;$iz<count($fieldlist1);$iz++){	

					if ($scope==$iz) $sel=" selected "; else $sel="";

					echo "<option $sel value='$iz'>$fieldlist2[$iz]</option>";

				}

				?>

			</select>

		</div>

		<div class="col-12 col-sm-6 col-lg-2 mt-2">

			<h6>مرتب سازی</h6>

			<select name="typesort" class="form-control" aria-label="Default select example">

				<?php 

				for ($iz=0;$iz<count($fieldsort1);$iz++){	

					if ($typesort==$iz) $sel=" selected "; else $sel="";

					echo "<option value='$iz' $sel>$fieldsort2[$iz]</option>";

				}

				?>

			</select>

		</div>

		<div class="col-12 col-sm-6 col-lg-1 mt-2">

			<h6>تعداد نمایش</h6>

			<select name='pagesize' class="form-control" aria-label="Default select example">

				<?php 

				for ($iz=0;$iz<count($ListCountRec);$iz++) { 

					$VarRpp = $ListCountRec[$iz];

					if ($pagesize==$VarRpp) $selrec = 'selected'; else $selrec = '';

					echo "<option value='$VarRpp' $selrec >".($VarRpp)."</option>";

				}

				?>

			</select>

		</div>

		<div class="col-12 col-sm-6 col-lg-1 mt-2">

			<h6 style="color: transparent">btn</h6>

			<button class="btn btn-danger w-100" type="button" onclick='myform.submit();'>جستجو</button>

		</div>

	</section>

	</form>

	</div>

<?php 

$cpage = basename($_SERVER["PHP_SELF"]) ;



$basequary = "select $bank1.*, $bank1.tetr, $bank1.leds, $bank1.photo_product, $bank1.spectioal, $bank1.best_saller, $bank1.vote_sum, $bank1.vote_count, $bank2.id as id_price, $bank2.price1, $bank2.price2, $bank2.cash, $bank2.cash_unlimited from `$bank1` inner join `$bank2` on $bank1.id=$bank2.id_product $shart group by $bank1.id";

$countrec = $pagesize;

$query = pdo_query("select count(*) from $bank1 inner join `$bank2` on $bank1.id=$bank2.id_product $shart",$pdo_array,0); 

$Ncol  = pdo_count($query);

//echo "countrec=$countrec";

if ($pagenumber >= ($Ncol/$countrec)+1)  {$pagenumber = 1;}

$ext_buttom  = '<input name="varsearch" value="'.$varsearch.'" type="hidden">';

//$ext_buttom .= '<input name="ListRpp" value="'.$ListRpp.'" type="hidden">';

$ext_buttom .= '<input name="scope" value="'.$scope.'" type="hidden">';

$ext_buttom .= '<input name="service" value="'.$service.'" type="hidden">';

$ext_buttom .= '<input name="typesort" value="'.$typesort.'" type="hidden">';

$jamrec = ceil($Ncol / $countrec) ;

$PanelPageNumber=false;

if (($Ncol / $countrec)>1) { $PanelPageNumber=true;}

//----------شروع پیمایش

$currentpage = $pagenumber;

$pagenumber = ($pagenumber-1) * $countrec ;

$select = "$basequary $sortquery limit $pagenumber, $countrec";

$query=pdo_query($select,$pdo_array,0);

?>

<div class="col-12 pl">

	<div class="shop-archive-content mt-3 d-block">

	<div class="row archive-header mr-1 ml-1">

    	<div class="col-12 col-sm-6">

			<?php if ($service==0) { echo " همه محصولات "; } else {	echo ShowServiceWithParent($service); }

			echo " - تعداد محصول: $Ncol";?>

		</div>

    	<div class="col-12 col-sm-6 ">

		<?php If ($PanelPageNumber) { ?>

		<!-- page number -->

		<form method="post" style="display: inline;" id="listpage" name="listpage" method='post'>	

		<input type='hidden' name='varsearch' value='<?php echo $varsearch;?>'>

		<input type='hidden' name='typesort' value='<?php echo $typesort;?>'>

		<input type='hidden' name='service' value='<?php echo $service;?>'>

		<input type='hidden' name='scope' value='<?php echo $scope;?>'>

		<div style="text-align: end;" >

			<?php 

			if ($currentpage>1) {	

				echo '<a title="صفحه قبل"  href="#" onclick="document.getElementById(\'pagenumber\').value = '. ($currentpage - 1).'; document.getElementById(\'listpage\').submit(); return false;"> <i class="fa fa-chevron-circle-right"></i> </a>'; 

			}

			echo '<span >شماره صفحه</span>

				<input name="pagenumber" id="pagenumber" value="'.$currentpage.'" style="border-radius: 4px;padding: 1px 9px; width:35px;  border: 1px solid;" type="text"> از';

			echo "<span>$jamrec</span>&nbsp;";

			if ($currentpage<$jamrec)  { 

				echo '<a title="صفحه بعد" href="#" onclick="document.getElementById(\'pagenumber\').value = '. ($currentpage + 1).'; document.getElementById(\'listpage\').submit(); return false;"><i class="fa fa-chevron-circle-left"></i></a>&nbsp;'; 

			}

			?>

		</div>

		</form>

		<?php } ?>	

		</div>

	</div>

	<div class="product-items">

		<div class="tab-content" id="myTabContent">

		<div class="tab-pane fade show active" role="tabpanel" aria-labelledby="Most-visited-tab">

		<div class="row">

		<?php
		$counter=$pagenumber;

		while ($row_product=pdo_fetch($query))  {
//			var_dump($row_product);

			$counter++;

			$darsad=0;			$price01=0;		$price02=0;		

			$price1='';			$price2='';		$StrSpectioal='';

			$StrDarsad='';

			//-------------------------------------


			$id    = $row_product['id'];

			$tetr  = $row_product['tetr'];				$leds  	= $row_product['leds'];

			$spectioal  = $row_product['spectioal'];	$best_saller = $row_product['best_saller'];

			$vote_count = $row_product['vote_count'];	$vote_sum = $row_product['vote_sum'];

			$price01	= $row_product['price1'];		$price02= $row_product['price2'];

			if ($vote_count==0) $vote_final = 0;  else 	$vote_final = ($vote_sum/$vote_count);

			$id_topics  = (str_replace(',',"\r\n",$row_product['id_topics']));

			$photo 		= $row_product['photo_product'];

			$tetrseo	= $row_product['url'];

			$id_price = $row_product['id_price'];

			if ($tetrseo=='') $tetrseo=$tetr;

			$link = $row_product['url_redirect'];

			//--------------------------------

			if ($service<=0)  $idtopics = intval($id_topics);	else $idtopics= $service;

			if ($spectioal==1) 		$StrSpectioal .= " محصول ویژه ";

			if ($best_saller==1) 	$StrSpectioal .= " پرفروش ";

			if ($StrSpectioal!='')  $StrSpectioal = "<div class='promotion-badge'>$StrSpectioal</div>";

			if ($link=='') $link  = "/product/$id/" . LinkSeo($tetrseo);

			$price1= number_format($price01);

			$price2= number_format($price02);

			if ($leds=='') $leds = "&nbsp;&nbsp;&nbsp;&nbsp;";

			if ($photo=='') {$photo=$logofile;} 	else {$photo=$upload_path_main.$photo; }

			//---------- تعیین تگ قیمت با تخفیف 

			if ($price02>0) {

				$darsad= number_format(100 - (($price02*100)/$price01),0);

				if ($darsad>0) 	$StrDarsad= "<span>$darsad %</span>";

				$PriceFinal = "<span class='amount'>$price2<span>$price_unit</span></span>";

				$PriceTag = "

				<del><span>$price1<span> $price_unit </span></span></del><BR>

				<ins><span>$price2<span> $price_unit </span></span></ins>

				";

				$price_product = $price02;

			}else{

				$PriceFinal = "<span class='amount'>$price1<span>$price_unit</span></span>";

				$PriceTag = "

				<del><span>&nbsp;</span></del><BR>

				<ins><span>$price1<span> $price_unit </span></span></ins>

				";

				$price_product = $price01;

			}	

			$cash = $row_product['cash'];	

			if ($row_product['cash_unlimited']==1) $cash=999999999;

			$TagBuy = "<li class='action-item add-to-cart'><button class='btn btn-light' title='افزودن به سبد خرید' onclick='AddToCart($id,$(\"#ProductProce$id\").val(),1);'><i class='fa fa-shopping-cart'></i></button></li>";

			if ( $cash<=0 ) {

				$PriceTag = "<span class='btn btn-danger'>ناموجود</span>";

				$TagBuy = "";

			}
			//------------------- get list of price product
			
			$TagListPrice='';
			$query_temp = pdo_query("select * from `product_price` where id_product='$id'   ",'',0);
			$counter=0;
			$ListPrice = array();
			while ($row_temp = pdo_fetch($query_temp)) {
				$id_price = $row_temp['id'];
				$Detils ='';
				$TagDivPrice='';
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
				$TagListPrice .= "<option value='$id_price'>$Detils</option>";
				if ($Detils=='') $TagDivPrice = " style='visibility:hidden;' ";
			}
			if ($TagListPrice != "") {
				$TagListPrice = "<div class='price-list' $TagDivPrice ><select id='ProductProce$id' name='ProductProce$id' class='form-control' >$TagListPrice</select></div>";
			}

			//-------------------- schema array 

			$ListProductSchema[] = array('name'=> $tetr, 'url'=>$link, 'price'=>$price_product, 'cash'=>$cash, 'image'=>$photo, 'id'=>$id, 'dsc'=>$leds, 'vote_sum'=>$row_product['vote_sum'], 'vote_count'=>$row_product['vote_count']);



			//-------------------------------------

			$StrVote='';

			if ($OkVote) {

			$StrVote = "

			<div class='post-vote' id='vote$idtopics-$id-container'>

				<span style='font-size:12px;color:#888;' >امتیاز:</span>

				<span style='font-size:12px;color:#888;' id='desc$counter'> </span> 

				<div id='votecontainer$counter' style='display: inline-block;_display: inline;direction:ltr;'></div>

				<span style='font-size:12px;color:#888;' id='1desc$counter'></span>  

			</div>

			";

			

			$javacode .= " 

			vote$counter =new starVote({ vote:0,curStars:$vote_final,score:$vote_sum,containerId:'votecontainer$counter', descriptionId:'desc$counter', key:'$id', stars:'5', extraDatas:'groupid=$idtopics&stars=5'}); \n";

			}

			//-----------------------------------

			echo  "

			<div class='col-lg-3 col-md-3 col-xs-12 col-6 order-1 d-block mb-3'>

				<section class='product-box product product-type-simple' style='min-height:395px;'>

					<div class='thumb' style='text-align:center;'>

						$StrSpectioal

						<div>$StrVote</div>

						<div class='text-right discount-d'>$StrDarsad</div>

						<div class='col-12 text-center'>

						<a href='$link'>

							<img src='$photo'class='img-fluid' alt='$tetr' style='border-radius: 50%;max-height:200px;  width:auto;'>

						</a>

						</div>

					</div>

					<div class='title'><a href='$link'>$tetr</a></div>

					$TagListPrice

					<div class='price-list'>$PriceTag</div>

					<div class='actions' >

						<ul >

							<li class='action-item like'>

								<button class='btn btn-light' title='افزودن به علاقه مندی ها' onclick='AddWish($id)'>

									<i class='fa fa-heart-o'></i>

								</button>

							</li>

							<!-- <li class='action-item compare'>

								<button class='btn btn-light' title='مقایسه'  onclick='Compare($id);'>

									<i class='fa fa-random'></i>

								</button>

							</li> -->

							$TagBuy

							

						</ul>

					</div>

					

				</section>

			</div>

			";



		}

		echo "<script>$javacode</script>";

		?>

		</div>

		</div>

		</div>

	</div>

	</div>

</div>

</div>

</div>

<?php

include('bottom.php');

?>