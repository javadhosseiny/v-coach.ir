<?php 
include('topmain.php');
if (isset($_REQUEST['service']))   	$service = intval($_REQUEST['service']);		else $service = 0;
if (isset($_REQUEST['typesort']))  	$typesort = intval($_REQUEST['typesort']); 		else $typesort = 0;
if (isset($_REQUEST['varsearch']))  $varsearch= $_REQUEST['varsearch'];	 			else $varsearch = '';
if (isset($_REQUEST['pagenumber'])) $pagenumber = intval($_REQUEST['pagenumber']); 	else $pagenumber = 1;
if (isset($_REQUEST['scope']))  	$scope 	= intval($_REQUEST['scope']); 			else $scope = 0;
if (isset($_REQUEST['pagesize']))   $pagesize = intval($_REQUEST['pagesize']);		else $pagesize = 0;
//----------------
$canonical  = "$canonical/CategoryWeblog/";
if (isset($_REQUEST['service'])) $canonical  = $canonical.$service; 
//-------------
$schema='CollectionPage';
$bankname='weblog';

if ($pagenumber==0) $pagenumber=1;
if ( ($service==1) or ($service==2) ) $service=0; 

$type_group=5; // کد اختصاصی گروه فروشگاه در لیست صفحات
$bank1='weblog'; 	
//$bank2=''; 
$fieldlist1 = array('all', 'tetr','leds', 'matn', 'tags', 'date'); 
$fieldlist2 = array('همه', 'عنوان', 'عنوان دوم', 'معرفی محصول',  'برچسب ها','تاریخ انتشار' ); 

$fieldsort1 = array("date desc, time desc", "countdn desc", "nazarcount desc", "tetr desc" ); 
$fieldsort2 = array('تاریخ انتشار', 'پربازدیدترین',  'پربحث ترین', 'عناوین'); 

if ( ($scope<0)		or ($scope > (count($fieldlist1)-1) ) ) $scope=0;
if ( ($typesort<0) 	or ($typesort > (count($fieldsort1)-1) ) ) $typesort=0;
$sortquery = " order by " . $fieldsort1[$typesort] ;

$shart = " where $bank1.active=2 $shartdatetimeWeblog and ";
$MainParentService=",$service,";
$ErrorMsg = '';
$parent_group=0;
$imagesizegroup = "";
switch ($service) {
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
		ShowMessage("طول رشته جستجو درست نمی باشد",1); include('bottom.php'); return;
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
$javacode='';

?>
<style>
.search-box h6 {  font-size: 13px;  white-space: nowrap;}
</style>
<div class='row container-main'>
	<div class='d-block'>
	<!-- search section  -->
	<div class='col-12 archive-header pr mt-1'>
	<form id='myform' action='/CategoryWeblog/' method='post'>
	<section class="search-box row d-md-flex d-lg-flex d-xl-flex" style="margin: 18px 0; width: 100%">
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

$basequary = "select * from $bank1 ";
$countrec = $pagesize;
$query = pdo_query("select count(*) from $bank1 $shart",$pdo_array,0); 
$Ncol  = pdo_count($query);
if ($pagenumber >= ($Ncol/$countrec)+1)  {$pagenumber = 1;}
$ext_buttom  = '<input name="varsearch" value="'.$varsearch.'" type="hidden">';
$ext_buttom .= '<input name="scope" value="'.$scope.'" type="hidden">';
$ext_buttom .= '<input name="service" value="'.$service.'" type="hidden">';
$ext_buttom .= '<input name="typesort" value="'.$typesort.'" type="hidden">';
$jamrec = ceil($Ncol / $countrec) ;
$PanelPageNumber=false;
if (($Ncol / $countrec)>1) { $PanelPageNumber=true;}
//----------شروع پیمایش
$currentpage = $pagenumber;
$pagenumber = ($pagenumber-1) * $countrec ;
$select = "$basequary $shart $sortquery limit $pagenumber, $countrec";
$query=pdo_query($select,$pdo_array,0);
?>
<div class="col-12 pl">
	<div class="shop-archive-content mt-1 d-block">
	<div class="row archive-header m-3">
    	<div class="col-12 col-sm-6">
			<?php if ($service==0) { echo " همه مطالب ";} else {echo ShowServiceWithParent($service,1); }
			echo " - تعداد مطالب: $Ncol";?>
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
	<main class="main-row mb-2">
        <div class="container-main">
            <div class="d-block">	
                <div class="col-lg-12 col-md-12 col-xs-12 pr mt-3">
                    <section class="content-widget">
		<?php
		$counter=$pagenumber;
		while ($row_news=pdo_fetch($query))  {
			$counter++;
			$darsad=0;			$price01=0;		$price02=0;		
			$price1='';			$price2='';		$StrSpectioal='';
			$StrDarsad='';
			//-------------------------------------
//			var_dump($row_news);
			$id    = $row_news['id'];
			$tetr  = $row_news['tetr'];				$leds = $row_news['leds'];
			$date  = $row_news['date'];				$time  = $row_news['time'];				
			$tags  = $row_news['tags'];				$meta_title = $row_news['meta_title'];
			$url   = $row_news['url'];				$url_redirect = $row_news['url_redirect'];	
			$id_topics  = (str_replace(',',"\r\n",$row_news['id_topics']));
			$photo 		= $row_news['photo_news'];
			$date_news = "$time $date ";
			$tetrseo	= $row_news['meta_title'];
			if ($tetrseo=='') $tetrseo=$tetr;
			$link = $row_news['url_redirect'];
			//--------------------------
			
			//--------------------------------
			if ($service<=0)  $idtopics = intval($id_topics);	else $idtopics= $service;
			if ($link=='') $link  = "/weblog/$id/" . LinkSeo($tetrseo);
			if ($leds=='') $leds = "&nbsp;&nbsp;&nbsp;&nbsp;";
			if ($photo=='') {$photo=$logofile;} 	else {$photo=$upload_path_main.$photo; }
			//-----------------------------------
			$ListProductSchema[] = array('name'=> $tetr, 'url'=>$link, 'image'=>$photo, 'id'=>$id, 'dsc'=>$leds, 'date'=>$date, 'time'=>$time);
			//-----------------------------------
			echo  "
			<article class='post-item' style='min-height:200px;'>
				<div class='post-thumb' >
					<a href='$link' class='d-block'><img src='$photo' style='max-height:150px;max-width:200px;' alt='$tetr'></a>
				</div>
				<div class='post-content'>
					<div class='title'>
						<a href='$link'  class='title-tag-tetr'>$tetr</a>
					</div>
					<div class='excerpt'>$leds</div>
					<span class='post-date'>
						<i class='fa fa-calendar'></i>$date_news
					</span>
				</div>
			</article>
			";
		}
		?>
					</section>
				</div>
			</div>
		</div>
	</main>
	</div>
	</div>
</div>
</div>
<?php
include('bottom.php');

?>