<?php
include('topmain.php');
if ( (empty($_SESSION['csrf_token'])) or (!isset($_SESSION['csrf_token'])) ) {    
	$_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if (isset($_REQUEST['level']))  			$level = intval($_REQUEST['level']);  	else $level = 0;
$ErrorMsg='';
$bankname  = 'product';
$title_main='سبد خرید';
//if (!$ok_cookie) { $ErrorMsg = "جهت خرید لطفا ابتدا در سایت ثبت نام کرده و یا وارد اکانت کاربری خود شوید <BR> <a href='/login/'>لینک ورود</a>";}

if ($ok_cookie) { $id_user = $usernameid;} else { 
	if (isset($_SESSION['id_user'])) {    $id_user = $_SESSION['id_user']; } else {
		$ErrorMsg = "مشکلی در ثبت سبد خرید وجود دارد، لطفا بعد از ورود به فروشگاه سبد خرید خود را ثبت نمایید<BR> <a href='/login/'>لینک ورود</a>";
	}
}


include('top.php');


$query = pdo_query("SELECT    uo.id_main, uo.id_product, uo.id_price, uo.number, p.tetr, p.leds, p.photo_product, p.id_topics, pp.weight, pp.price1, pp.price2 	from  user_order uo
JOIN    product p ON uo.id_product = p.id
JOIN    product_price pp ON uo.id_price = pp.id
where uo.id_user='$id_user' order by uo.date desc
",'',0);
$CountAll = pdo_rowcount($query);
if ($CountAll==0) {
	echo "<div class='row'></div>"; 
	ShowMessage("سبد خرید خالی است",0,'bg-danger'); 
	include('bottom.php'); 	exit; 
}	
if ($level==2) {
	$query2	  = pdo_query("select * from `send_title` where active=1 order by idsort",'',0);
	$CoundSend = pdo_rowcount($query2);
	if ($CoundSend==0) {
		echo "<div class='row'></div>"; 
		ShowMessage("روش ارسالی برای سفارشات تعریف نشده است",0,'bg-danger'); 
		include('bottom.php'); 	exit; 
	}	
	$query3	  = pdo_query("select * from `payment_getway` where active=1 order by date_last_edit",'',0);
	$CoundPayment = pdo_rowcount($query3);
	if ($CoundPayment==0) {
		echo "<div class='row'></div>"; 
		ShowMessage("نحوه پرداختی برای سفارشات تعریف نشده است",0,'bg-danger'); 
		include('bottom.php'); 	exit; 
	}	
	//--------------------------------------------------------
	// لیست استان ها و نمایش و یا عدم نمایش استان و شهر در دریافت آدرس
	$ListOstan = array();	
	$query3  = pdo_query("select id,name from `ostan` ",'',0);
	while ($row=pdo_fetch($query3)) {
		$ListOstan[$row['id']] = $row['name'];
	}
	$id_ostan= $row_setting['id_ostan']; 
	$id_city = $row_setting['id_city'];
	if ($row_setting['show_ostan_city']==0) {$display_city = 'd-none';} else {$display_city='';}
	//-----------------------------------------------------------
	$ListAddres = '';
	$idaddres = 0;
	$dsc_addres = '';
	$bordercolor = '';
	$query_addres = pdo_query("select * from `user_addres` where id_user='$id_user' and isdeleted=0 order by date_last_edit desc");
	while ($row_addres = pdo_fetch($query_addres)) {
		$id  = $row_addres['id_main'];
		list($title,$dsc_text,$dsc_web) = GetFullAddres(0,$row_addres);
		if ($idaddres==0) {
			$idaddres = $id;
			$dsc_addres = $dsc_text;
		}
		if ($bordercolor=='') $bordercolor='#008eb2'; else $bordercolor='#e0e0e2'; 
		$ListAddres .= "
		<div class='col-12 mb-4'>
		<div class='d-flex flex-column p-3 rounded-3 position-relative' style='  border: 1px solid $bordercolor ;'>
			<div dir='ltr' class='d-flex justify-content-between align-items-center mb-2'>
				<a href='#' style='cursor:pointer;' onclick='EditAddres($id)'>
					<i class='fa fa-pencil text-secondary'></i>
				</a>
				<div class='d-flex align-items-center'>
                	<label for='list_addres_$id' class='me-2 fw-bold'>$title</label>
                	<input type='radio' id='list_addres_$id' name='list_addres' value='$id' onchange=\"ChangeAddres('$id');\" class='ms-2 ml-2'>
            	</div>	
			</div>			
			<div class='text-end text-secondary' style='font-size: 0.9rem; line-height: 2;'>
            	<div class='d-none' id='dsc_$id'>$dsc_text</div>
				<div >$dsc_web</div>
        	</div>		
		</div>
		</div>";
	}
	//------------------------------------------------------------
	//----------------------
	//-----------------------
	// الوپیک
	//https://api.alopeyk.com/api/v2/ ---> دریافت اطلاعات اولیه
	// الزاما باید روی نقشه مقصد مشخص باشد که دردسر بسیار دارد
	//---
	
}
	

/*
if ($level==0) { 

	function updateCartItems in assest\js\function.js define - this function call in top.php
	in function first fill سبدخرید after fill page usershopping (this page) in level=0 list of product with change number
	after inside function call in ajax code file data_ajax.php with type=12
}

*/
?>
<main class="main-row" style='padding:10px;'>
<div class="container-main bg-white text-center p-2 rounded">
<section class="cart-home">
<div class="post-item-cart d-block order-2">
<div class="content-page">
<?php if ($level==0) { ?>
	<div class="cart-form">
		<form action="#" class="c-form">
			<table class="table-cart cart table table-borderless">
				<thead>
					<tr>
						<th scope="col" class="product-cart-name text-center" >نام محصول</th>
						<th scope="col" class="product-cart-price ">قیمت</th>
						<th scope="col" class="product-cart-quantity">تعداد مورد نیاز</th>
						<th scope="col" class="product-cart-Total">مجموع</th>
					</tr>
				</thead>
				<tbody id='TableOrder'>
				</tbody>
			</table>
		</form>
		<div class="cart-collaterals">
			<div class="row">
				<div class="col-12 col-md-6 " style='font-size:120%;font-weight:bold;'>مجموع کل سبد خرید</div>
				<div class="col-12 col-md-6 text-left">قیمت کل
					<span id='PriceAll1'></span>
				</div>
				<div class='col-6 col-md-9'></div>
				<div class="col-6 col-md-3 proceed-to-checkout mt-5">
				<?php 
				if ($ok_cookie) { 
					echo "<a href='/shoppingcardfinal/' class='d-block'>تسویه حساب</a>"; }
				else {
					echo "<a  onclick='GoCardFinal();' class='d-block' style='color:white;'>تسویه حساب</a>";
				}
				?>
				</div>
			</div>
			
		</div>
	</div>
<?php } if ($level==2) {	?>

	<form action="" id="FormOrder" method="post"  name="FormOrder" onsubmit="return check();" >
	<div class='row' id='main_form'>
		<div class="col-12 col-md-6 d-flex pl-4" >
			<div class='row'>
			<div id="grid-list" class="table-responsive" style="box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);  overflow-y: auto;" >	
			<table class="table card-table table-striped text-center table-hover-animation ">
				<thead>
					<tr>
						<th width='40px'>ردیف</th>
						<th width='220px'>نام محصول</th>
						<th width='80px'>قیمت</th>
						<th width='80px'>تعداد</th>
						<th width='110px'>مجموع</th>
					</tr>
				</thead>
				<tbody >
				<?php
				$counter=0;
				$SumWeight=0;
				$PriceAll=0;
				while ($row=pdo_fetch($query)) {
					$counter++;
					$price=  $row['price1'];
					if ($row['price2'] > 0) $price=$row['price2'];
					//-------------------
					$product_content='';
					$id_price=$row['id_price'];
					$query2 = pdo_query("select content from `product_price_property` where id_price='$id_price'");
					$row2 	= pdo_fetch($query2);
					if ($row2!='') $product_content = $row2['content'];
					
					//-------------------
					$id    = $row['id_main'];		$photo = $row['photo_product'];
					$tetr  = $row['tetr'];			$number = $row['number'];
					$idproduct=$row['id_product'];	$weight = $row['weight'];
					$tetr_seo = LinkSeo($tetr);
					$link = "/product/$idproduct/$tetr_seo";
					
					$SumWeight += ($weight * $number);
					
//					if ($weight!=0) $tetr = "$tetr <span class='text-success' style='font-size:smaller'>[$weight گرم]</span>";
					if ($product_content!='') 	$tetr = "$tetr <span class='text-danger' style='font-size:smaller'>[$product_content]</span>";
					
					if ($photo=='') {$photo=$logofile;} 
						else{	if (!validateURL($photo)) $photo = $upload_path_main.$photo;}
					$price_tag = ' ' . number_format($price);// . ' ' . $price_unit ;
					$PriceAll  += ($price * $number);
					$priceall_tag = number_format($price * $number) . ' ' . $price_unit ;
					echo "
					<tr>
						<td style='text-align:center;' >$counter</td>
						<td>
							<a href='$link' target=_blank><span class='product-name-card'>$tetr</span></a>
						</td>
						<td style='text-align:center;'>$price_tag</td>
						<td style='text-align:center;'>$number</td>
						<td>$priceall_tag</td>
					</tr>
					";
				}
				$PriceAllTag = number_format($PriceAll) . ' ' . $price_unit;			
				echo "
				<tr style='background-color:#ccf2cc;'>
					<td colspan='5'  style='text-align:left;'>مجموع: <span class='text-danger'>$PriceAllTag</span></td>
				</tr>
				";
				?>
				</tbody>
			</table>
			</div>
			<?php
			
			?>
				<input type='hidden' name='type' value='15' >
				<input type='hidden' name='idrec' value='0' >
				<input type='hidden' name='redirect' id='redirect' value='0' >
				<input type='hidden' name='weight_all' value = '<?php echo $SumWeight;?>'>
				<input type='hidden' name='PriceAll' id='PriceAll' value='<?php echo $PriceAll;?>'>
				<input type='hidden' name='PriceKOL' id='PriceKOL' value='<?php echo $PriceAll;?>'>
				<input type='hidden' name='idaddres' id='idaddres' value='<?php echo $idaddres;?>'>
				<input type="hidden" id="csrf_token" name='csrf_token' value="<?php echo $_SESSION['csrf_token']; ?>">						
			<div class='col-12 cart-form mt-4 mb-4'>
			<div class='row'>
				<div class="col-12 mb-4 d-flex justify-content-between align-items-center">
					<span class="box-title">اطلاعات تحویل گیرنده</span>
					<?php 	
					if ($idaddres==0) {  
						echo "<a href='#' class='text-decoration-none' data-toggle='modal' data-target='#addAddressModal'>+ افزودن آدرس جدید</a>";
					}else{
						echo "<a href='#' class='text-decoration-none' id='openManage'>تغییر آدرس</a>";
					}
					?>
				</div>
				<div class="col-12">
					<div class="form-group">
						<label class="form-label">آدرس<span class='text-danger'>*</span>
						</label>
						<textarea id='dsc_addres' name='dsc_addres' class="form-control" rows='4' readonly><?php echo $dsc_addres;?></textarea>
					</div>
				</div>
			</div>
			</div>			
			</div>
			
		</div>
		<div class="col-12 col-md-6  mb-2 d-flex">
			<div class='row' style="flex: 1; overflow-y: auto;">
			<div class='col-12 cart-form'>
				<div class='row'>
					<div class='col-12 mb-4'>
						<span class="box-title">نحوه ارسال</span>
						<?php
						$active_send = '';
//						echo "مبلغ سفارش:". $PriceAll . ' - وزن: ' . $SumWeight;
						$query4 = pdo_query("select * from `send_title` where active='1' order by idsort");
						$count_send = pdo_rowcount($query4);
						while ($row_send = pdo_fetch($query4)) {
							$send_id 	= $row_send['id_main'];			$send_tetr  = $row_send['name'];
							$send_dsc	= $row_send['dsc'];				$send_type	= $row_send['id_type'];
							$min_order 	= $row_send['min_order'];		$max_order  = $row_send['max_order'];
							$min_weight	= $row_send['min_weight'];		$max_weight	= $row_send['max_weight'];
							$OkSend=true;
							if (($min_order>0) and ($PriceAll<$min_order) ) $OkSend=false;
							if (($max_order>0) and ($PriceAll>$max_order) ) $OkSend=false;
							if (($min_weight>0) and ($SumWeight<$min_weight) ) $OkSend=false;
							if (($max_weight>0) and ($SumWeight>$max_weight) ) $OkSend=false;
							
							
							if ($OkSend) {
								if ($send_type==1) $send_price=0;
								if ($send_type==2) $send_price=$row_send['price_fix'];
								if ($send_type==3) {
									$dynamic_zarib	  	= $row_send['dynamic_zarib'];
									$dynamic_minprice 	= $row_send['dynamic_minprice'];
									$dynamic_maxprice 	= $row_send['dynamic_maxprice'];
									$dynamic_round		= $row_send['dynamic_maxprice'];
									$send_price 		= $SumWeight * $dynamic_zarib;
									// جهت رند کردن عدد خروجی
									if ( ($dynamic_round!=0) and ($send_price>$dynamic_round)) {
										$send_price = floor($send_price / $dynamic_round) * $dynamic_round;
									}
									if (($dynamic_minprice!=0) and ($send_price < $dynamic_minprice))
										$send_price = $dynamic_minprice;
									if (($dynamic_maxprice!=0) and ($send_price > $dynamic_maxprice))
										$send_price = $dynamic_maxprice;
								}
								if ($active_send=='') $sel= 'checked'; else $sel='';
								$StrPrice = '';
								if ($send_price==0) $StrPrice = '[رایگان]'; 
									else $StrPrice = '['.number_format($send_price) . " $price_unit ]";
								echo "<BR>
									<input type='radio' id='sender$send_id' name='sender' value='$send_id' $sel onchange='ChangePrice();' data-price='$send_price'>
									<label for='sender$send_id' class='text-secondry' alt='$send_dsc'>
									$send_tetr <span class='text-success'> $StrPrice </span>
									</label>
								";
								$active_send='ok';
//								if ($send_price==0) break;// در صورت رایگان بودن بقیه را نمایش ندهد
							}
						}
						
						?>
						<br>
						<span class="text-secondry">هزینه ارسال:</span>
						<span id='price_send' class="text-danger"></span>
<!--						<input type='hidden' id='send_price' name='send_price' value='<?php ;echo $send_price;?>'>-->
						<input type='hidden' id='send_price' name='send_price' value='0'>
					</div>
				</div>
			</div>
			<div class='col-12 cart-form mt-4'>
				<div class='row'>
					<div class='col-12 mb-4'>
						<span class="box-title">نحوه پرداخت</span>
						<?php
						$active_payment = '';
						$query4 = pdo_query("select * from `payment_getway` where active='1' order by date_last_edit");
						$count_send = pdo_rowcount($query4);
						while ($row_payment = pdo_fetch($query4)) {
							$payment_id = $row_payment['id_main'];		$payment_tetr  	= $row_payment['name'];
							$payment_type	= $row_payment['id_type'];
							$OkPanelVariz = 0;
							if ($payment_type==1) {
								$payment_tetr = "$payment_tetr - [ شماره حساب: $row_payment[account_number] - شماره کارت:$row_payment[card_number] - شماره شبا: $row_payment[sheba_number] بنام: $row_payment[owner] ]";
								$OkPanelVariz = 1;
							}
							if ($active_payment=='') $sel= 'checked'; else $sel='';
							
							echo "<BR>
								<input type='radio' id='payment$payment_id' name='payment' value='$payment_id' $sel onchange='ShowPanelVariz($OkPanelVariz);' data-showvariz='$OkPanelVariz'>
								<span for='payment$payment_id' class='text-secondry' >
								$payment_tetr 
								</span>
							";
							$active_payment='ok';
						}
						?>
						<div id='variz' class='col-12 m-2 d-none'>
							<span class="box-title">اطلاعات واریز مبلغ</span>
							<span class='text-danger' style='font-size:10px;' >لطفا تاریخ و ساعت واریز و شماره کارت و یا شماره حسابی که از آن واریز شده را ثبت نمایید</span>
							<textarea name="dsc_payment" class="form-control" dir="rtl"  rows="2"></textarea>
						</div>
						
					</div>
				</div>
			</div>
			<div class="col-12 cart-form mt-4 <?php if ($row_setting['show_dsc']==0) echo ' d-none';?>" >
				<div class='row'>
					<div class='col-12 mb-4'>
						<?php 
						$content_dsc = $row_setting['content_dsc'];
						if ($content_dsc=='') $content_dsc = 'اگر توضیحی راجع به سفارش خود دارید در این قسمت وارد کنید';
						?>
						<span class="box-title">توضیحات</span>
						<textarea name="dsc" class="form-control" dir="rtl"  rows="2" placeholder='<?php echo $content_dsc?>'></textarea>
					</div>
				</div>
			</div>
			
			<div class='col-12 cart-form mt-4 <?php if ($row_setting['show_discount']==0) echo 'd-none';?>'>
				<div class='row'>
					<div class='col-12 mb-4'>
						<span class="box-title">کد تخفیف</span>
						<br>
						<input class="form-control mt-1" value="" id="discount_code" name="discount_code"  type="text" style='direction:ltr;display:inline;width:unset;' placeholder='کد تخفیف'  >
						<a onclick='CalculateDiscount();' class="checkout-button bg-success p-2 rounded " style='color:white;cursor: pointer;'>اعمال کد تخفیف</a>						
						<span class="text-secondry mr-5">مبلغ تخفیف:</span>
						<span id='price_discount' class="text-danger"></span>
						<input type='hidden' id='discount_price' name='discount_price' value='0'>
					</div>
				</div>
			</div>	
			<div class='col-12 cart-form mt-4 <?php if ($row_setting['show_shipping_time']==0) echo 'd-none';?>'>
				<div class='row'>
					<div class='col-12 mb-4'>
						<span class="box-title">زمان ارسال</span><br>
						<?php
						if ($row_setting['show_shipping_time']==1) {
							$options = getDeliveryOptions(3, 10);
							echo "<select name='shipping_time' id='shipping_time' class='form-control'>";
							foreach ($options as $option_rec) {
								echo "<option value='$option_rec[value]'>$option_rec[label]</option> \r\n";
							}
							echo '</select>';						
							?>
						<?php }else{ ?>
							<input type='hidden' id='shipping_time' name='shipping_time' value=''>
						<?php } ?>
					</div>
				</div>
			</div>	

			
			<div class="cart-collaterals">
				<div class="row">
					<div class="col-12 col-md-6 " style='font-size:120%;font-weight:bold;'>جمع کل (قابل پرداخت)</div>
					<div class="col-12 col-md-6 text-left">
						<span id='PriceKOLStr' class="text-danger"></span>
					</div>
					<div class='col-12 row mt-5'>
						<div class='col-0 col-md-1'></div>
						<div class="col-5 col-md-3 proceed-to-checkout m-1">
						<a onclick='SaveOrder(0);' class="checkout-button d-block" style='color:white;'>ثبت سفارش</a> 
						</div>
						<div class="col-5 col-md-3 proceed-to-checkout m-1" style='background-color:green;'>
						<a onclick='SaveOrder(1);' class="checkout-button d-block" style='color:white;'>ثبت و پرداخت</a> 
						</div>
						<div class="col-5 col-md-4 proceed-to-checkout m-1" style='background-color:gray;'>
						<a href="/shoppingcard/" class="checkout-button d-block">بازگشت به سبد خرید</a> 
						</div>
					</div>
				</div>
				
			</div>
		</div>
	</div>
	</form>
	

<?php } ?>
</div>
</div>
</section>
</div>
</main>
<?php
include('bottom.php');
?>
<script>
function GoCardFinal() {
	var new_message = "جهت ثبت سفارش الزاما باید در سایت ثبت نام کرده و وارد شوید <BR> <hr style='background-color:white;'>	سبد خرید شما ذخیره شده و به محض ثبت نام و یا ورود مجددا برای شما نمایش داده می شود<BR>	<hr style='background-color:white;'> <a href='/register/' style='color:#3673f7;' target=_blank>لینک ثبت نام</a> | <a href='/login/' style='color:#3673f7;' target=_blank>لینک ورود</a>";
	message(new_message,'error',0,'');
}

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
</script>
<?php if ($level==2) { ?>
<script>
$(document).ready(function () {
	ChangeCity(<?php echo $id_ostan;?>,<?php echo $id_city;?>);
	$("#id_ostan").val(<?php echo $id_ostan;?>);
	ChangePrice();
	var Idpayment = document.querySelector('input[name="payment"]:checked');
  	if (Idpayment) {
		var myInput		= $(Idpayment);
	  	var showvariz 	= myInput.data('showvariz');
		ShowPanelVariz(showvariz)

	}else{
		$('#variz').addClass('d-none');
	}

});


function ChangeAddres(id){
	$("#idaddres").val(id);
	var newtxt = $("#dsc_"+id).html();
	$("#dsc_addres").val(newtxt);
    $('#addAddressModal').modal('hide');
    $('#ManageAddressModal').modal('hide');
}

function ChangePrice() {
	var checkedIdSend = document.querySelector('input[name="sender"]:checked');
  	if (checkedIdSend) {
		var iid = checkedIdSend;
		var myInput		= $(iid);
		var newprice 	= myInput.data('price');
		var newpriceStr	= FormatNumberBy3(newprice);
		$("#price_send").html(newpriceStr + '<?php echo $price_unit;?>' );
		$("#send_price").val(newprice);
		var PriceAllKOL = <?php echo $PriceAll;?>;
		//----------------
		var discount_code = $("#discount_code").val();
		if (discount_code!='') {
			CalculateDiscount();
		}
		var discount_price = $("#discount_price").val();
		var newpriceKOL = parseInt(PriceAllKOL) + parseInt(newprice) -  parseInt(discount_price);
		var newpriceKOLStr	= FormatNumberBy3(newpriceKOL);
		$("#PriceKOLStr").html(newpriceKOLStr + '<?php echo $price_unit;?>' );
		$("#PriceKOL").val(newpriceKOL);
	}else{
		$("#price_send").html();
		$("#PriceKOLStr").html();
		$("#send_price").val(0);
		$("#discount_code").val();
		$("#price_discount").html();
		$("#discount_price").val(0);
		$("#PriceKOL").val(<?php echo $PriceAll;?>);
	}
}
function ShowPanelVariz(show) {
	if (show==1) {
		$('#variz').removeClass('d-none');
	}else{
		$('#variz').addClass('d-none');
	}
}
function CalculateDiscount() {
	var discount_code = $('#discount_code').val();
	if (discount_code=='') {
		$("#discount_code").val();
		$("#price_discount").html("");
		$("#discount_price").val(0);
		ChangePrice();
//		message('لطفا کد تخفیف را به درستی وارد نمایید','error',0,'discount_code');
		return false;
	}
	var PriceAll = $('#PriceAll').val();
	var price_send = $('#send_price').val();
	
	$(".checkout-button").css("pointer-events", "none");
	$.ajax({
		type: 'POST',
		url: '/data_ajax.php',
		data: { type:18, discount:discount_code, price_all: PriceAll, price_send: price_send, id_user:<?php echo $id_user;?>},
		dataType: 'json',
		success: function (data, status, xhr) {
			if (data[0]<0) { message(data[1], 'error') }
			if (data[0]==1) { 
				var newdiscount 	= FormatNumberBy3(data[1]) + ' ' + '<?php echo $price_unit;?>';
				var newfinalprice 	= FormatNumberBy3(data[2]) + ' ' + '<?php echo $price_unit;?>';
				$("#discount_price").val(data[1]);
				$("#price_discount").html(newdiscount);
				$("#PriceKOLStr").html(newfinalprice);
			}
		},
		error: function (jqXhr, textStatus, errorMessage) {alert(' textStatus = '+textStatus+' Error: ' + errorMessage);}
	});		

	$(".checkout-button").css("pointer-events", "auto");
	
	return false;	
	
}

function SaveOrder(id) {
	if (id==1) $("#redirect").val(1);
	
	if ($('#idaddres').val()==0) {
		message('لطفا یک آدرس انتخاب (ثبت) کنید','error',0,'name');
		return false;
	}
	//--------------
//	var checkedIdSend = document.querySelector('input[name="sender"]:checked');
// 	if (checkedIdSend) {		ChangePrice(checkedIdSend);	}
	//---------------
	$(".checkout-button").css("pointer-events", "none");
	
	$.ajax({
		type: 'POST',
		url: '/data_ajax.php',
		data: $("#FormOrder").serialize(),
		dataType: 'json',
		success: function (data, status, xhr) {
			if (data[0]<0) { 
				message(data[1], 'error');
			}
			if (data[0]==1) { 
				new_message = "<div class='col-11 text-center bg-success text-white rounded p-5 m-5'><BR>" + data[1] + "<BR><BR></div>";
				$("#main_form").html(new_message);
				$("#style-0").hide();
				$("#count-1").html('0');
				$("#price-1").html('0 <?php echo $price_unit;?>');
			}
			if (data[0]==2) { 
				if (data[1]<=0) { 
					new_message = "<div class='col-11 text-center bg-success text-white rounded p-5 m-5'><BR>" + data[2] + "<BR><BR></div>";
					$("#main_form").html(new_message);
					$("#style-0").hide();
					$("#count-1").html('0');
					$("#price-1").html('0 <?php echo $price_unit;?>');
				}
				if (data[1]==1) 	{ 
            		window.location.href = data[2];
				}
			}
		},
		error: function (jqXhr, textStatus, errorMessage) {alert(' textStatus = '+textStatus+' Error: ' + errorMessage);}
	});		

	$(".checkout-button").css("pointer-events", "auto");
	
	return false;
	
	
}

function ChangeCity(id, idold) {
	$("#loading").show();
	$('#id_city').html("<option value='0'></option>");
	$.ajax({
		type: 'POST',
		url: '/data_ajax.php',
		data: { type: 8, id: id ,idold: idold},
		dataType: 'json',
		success: function (data, status, xhr) {
			$("#loading").hide();
			if (data[0]<0) { message(data[1], 'error') }
			if (data[0]==1) { 	
				$('#id_city').html("");
				$('#id_city').html(data);
			}
			
		},
		error: function (jqXhr, textStatus, errorMessage) {alert(' textStatus = '+textStatus+' Error: ' + errorMessage);}
	});		
}	
</script>

<div id='loading' class="lds-ripple"><div></div><div></div></div>
<style>
.lds-ripple {  color: #f25a41; background-color:white;}
.lds-ripple,.lds-ripple div {  box-sizing: border-box;}
.lds-ripple {  	display:none;	position: absolute;  top: 40%; left:50%;
	border-radius: 50%;  width: 80px;  height: 80px;  margin:auto;  z-index:999;}
.lds-ripple div {
  position: absolute;  border: 4px solid currentColor;  opacity: 1;
  border-radius: 50%;  animation: lds-ripple 1s cubic-bezier(0, 0.2, 0.8, 1) infinite;
}
.lds-ripple div:nth-child(2) {  animation-delay: -0.5s;}
@keyframes lds-ripple {
  0% {    top: 36px;    left: 36px;    width: 8px;    height: 8px;    opacity: 0;  }
  4.9% {    top: 36px;    left: 36px;    width: 8px;    height: 8px;    opacity: 0;  }
  5% {    top: 36px;    left: 36px;    width: 8px;    height: 8px;    opacity: 1;  }
  100% {    top: 0;    left: 0;    width: 80px;    height: 80px;    opacity: 0;  }
}
</style>
<?php 
$show_gps			= $row_setting['show_gps'];
$type_show_gps = $row_setting['type_show_gps'];
$apikey_neshan = $row_setting['apikey_neshan'];
$apikey_neshan_web = $row_setting['apikey_neshan_web'];
if ($show_gps==1) {
	if ( ($type_show_gps==2) and ($apikey_neshan!='') and ($apikey_neshan_web!='')) {	$type_show_gps =2;}
		else{$type_show_gps =1;}
	if ($type_show_gps==2) { // نشان	
		echo "
		<link href='https://static.neshan.org/sdk/leaflet/1.4.0/leaflet.css' rel='stylesheet'>
		<script src='https://static.neshan.org/sdk/leaflet/1.4.0/leaflet.js'></script>
		";
	}else{
		echo "
		<link rel='stylesheet' href='/assets/maps/dist/leaflet.css'>
		<script src='/assets/maps/dist/leaflet.js'></script>
		";
	}
}
?>

<div class="modal fade" id="ManageAddressModal" tabindex="-1" aria-labelledby="ManageAddressModalLabel" aria-hidden="true" >
	<div class="modal-dialog modal-dialog-centered modal-lg">
			<div class="modal-content" >
            <div class="modal-header" style='border-color:#ffa101;border-block-width: thin; background-color:#F8F8F8; margin:0px;'>
                <h5 class="modal-title" id="ManageAddressModalLabel">آدرس های شما</h5>
				<span onclick='CloseModal2();'>
				<i class="fa fa-times" aria-label="Close" style='cursor:pointer;'></i>
				</span>
            </div>
            <div class="modal-body" style='max-height: 70vh;overflow-y: auto;' >
                <form id="addressFormChange" name='addressFormChange'  >
					<input type='hidden' name='type' value='20'>
                    <div class="row">
						<?php echo $ListAddres;?> 
                    </div>
                </form>
            </div>
            <div class="modal-footer">
			
                <button type="button" class="btn btn-secondary ml-4" style='width:150px;' onclick='CloseModal2();'>
				<i class="fa fa-list ml-4"></i>بازگشت 
				</button>

				<button id='openAddFromManage' type="button" class="btn btn-success ml-2" onclick='OpenModal1();'>
				+ افزودن آدرس جدید
				</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="addAddressModal" tabindex="-1" aria-labelledby="addAddressModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-lg">
			<div class="modal-content" >
            <div class="modal-header" style='border-color:#ffa101;border-block-width: thin; background-color:#F8F8F8; margin:0px;'>
                <h5 class="modal-title" id="addAddressModalLabel">افزودن آدرس جدید</h5>
				<span onclick='CloseModal1();'>
				<i class="fa fa-times" aria-label="Close" style='cursor:pointer;'></i>
				</span>
            </div>
            <div class="modal-body" style='    max-height: 70vh;overflow-y: auto;'>
                <form id="addressForm" name='addressForm'  >
					<input type='hidden' name='type' value='19'>
					<input type='hidden' name='idrec_addres' id='idrec_addres' value='0'>
					<input type='hidden' name='gps'   id='gps'   value=''>
					<input type='hidden' name='distance_to_shop'  id='distance_to_shop'   value=''>
					
                    <div class="row">
						<div class="col-12 col-md-6 <?php echo $display_city;?>">
							<div class="form-group">
								<label class="form-label">استان<span class='text-danger'>*</span></label>
								<select class="form-control select2" id="id_ostan" name="id_ostan" onchange="ChangeCity(this.value);" >
								<?php
									foreach($ListOstan as $key=>$value) {
										$sel = ($key==$id_ostan) ? "selected" : "";
										echo "<option value='$key' $sel>$value</option>";
									}
								
								?>
								</select>
							</div>
						</div>
						<div class="col-12 col-md-6 <?php echo $display_city;?>">
							<div class="form-group">
								<label class="form-label">شهر<span class='text-danger'>*</span></label>
								<select class="form-control" id="id_city" name="id_city" >
								<option value=0></option>
								</select>
								<input type='hidden' name='idcity' id='idcity' value='<?php echo $id_city;?>'>
							</div>
						</div>
						<?php if ($row_setting['show_gps']==1) { ?>
                        <div class="col-12" id="address-modal">
							<div class="controls" style='margin:10px;'>
								<input type="text" id="searchInput" class='form-control' style='display:unset;width:80%;' placeholder="جستجوی آدرس...">
								<button type='button' class='btn btn-success' onclick="event.preventDefault();searchLocation();">جستجو</button>
							</div>
							<div id="map" style='width:100%; height:300px; margin-top:10px; border:1px solid #ccc;'>
							</div>
							<div class="result d-none" style='margin-top:10px; background:#f5f5f5; padding:10px;'>
								<p>عرض جغرافیایی: <span id="lat">-</span></p>
								<p>طول جغرافیایی: <span id="lng">-</span></p>
								<p>آدرس: <span id="address">-</span></p>
							</div>
						</div>
						<?php } ?>
                        
                        <div class="col-12">
							<div class="form-group">
								<label class="form-label">آدرس دقیق<span class='text-danger'>*</span></label>
								<textarea id="addres" name="addres" class="form-control" rows="2" ></textarea>
							</div>
                        </div>

                        <div class="col-md-4">
							<div class="form-group">
                            	<label class="form-label">پلاک<span class='text-danger'>*</span></label>
                            	<input type="text" id='plaque' name="plaque" class="form-control" style='direction:ltr;'  >
							</div>
                        </div>
                        <div class="col-md-4">
							<div class="form-group">
	                            <label class="form-label">واحد<span class='text-danger'>*</span></label>
    	                        <input type="text" id="unit" name="unit" class="form-control" style='direction:ltr;' >
							</div>
                        </div>
                        <div class="col-md-4">
							<div class="form-group">
								<label class="form-label">کدپستی</label>
								<input class="form-control" value="" id="zipcode" name="zipcode"  type="text" style='direction:ltr;' onkeypress="return isNormalNumber(event)" maxlength=10 >
							</div>
                        </div>
                        <hr>
						<div class="col-12 col-md-6">
							<div class="form-group">
								<label class="form-label">عنوان آدرس<span class='text-danger'>*</span></label>
								<input class="form-control" value="" id="tetr" name="tetr"  type="text" placeholder='مانند: خانه، محل کار'>
								
							</div>
						</div>
						<div class="col-12 col-md-6">
							<div class="col-12 vs-checkbox-con vs-checkbox-success mt-2">
								<input type="checkbox" name='recip_me' id="recip_me" onchange="toggleReceiverFields(this.id)" >
								<span class="vs-checkbox" >
									<span class="vs-checkbox--check">
										<i class="vs-icon feather icon-check"></i>
									</span>
								</span>
								<span class="form-label">سفارش را به شخص دیگری تحویل می‌دهم</span>
							</div>				
						</div>

                        <div id="receiverFields" style="display: none;" class="row col-12">
                            <div class="col-12 col-md-6 mt-0">
                                <label class="form-label">نام تحویل‌گیرنده <span class='text-danger'>*</span></label>
                                <input type="text" name="recip_name" id='recip_name' class="form-control">
                            </div>
                            <div class="col-12 col-md-6 mt-0">
                                <label class="form-label">شماره موبایل <span class='text-danger'>*</span></label>
                                <input type="text" name="recip_mobile" id="recip_mobile" class="form-control" style='direction:ltr;' onkeypress="return isNormalNumber(event)" >
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary ml-4" style='width:150px;' onclick='CloseModal1();'>
				<i class="fa fa-list ml-2"></i>بازگشت 
				</button>
                <button id='ButtonSaveAddres' type="button" form="addressForm" class="btn btn-success" style='width:150px;' onclick='save_addres();'>
				<i class="fa fa-check ml-2"></i>ذخیره
				</button>
            </div>
        </div>
    </div>
</div>
<style>
.search-box {
    position: absolute;
    top: 10px;
    left: 50%;
    transform: translateX(-50%);
    z-index: 1000;
    background: white;
    padding: 5px;
    border-radius: 8px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.2);
}
#search-input {
    border: none;
    padding: 8px;
    width: 250px;
    outline: none;
}
</style>
<script>
const discount_code_btn = document.getElementById('discount_code');

discount_code_btn.addEventListener('keydown', function(event) {
if (event.key === 'Enter') {
  event.preventDefault(); // جلوگیری از submit شدن فرم
  CalculateDiscount();       // فراخوانی تابع شما
}
});

<?php if ($row_setting['show_gps']==1) { ?>
const searchInput = document.getElementById('searchInput');

searchInput.addEventListener('keydown', function(event) {
if (event.key === 'Enter') {
  event.preventDefault(); // جلوگیری از submit شدن فرم
  searchLocation();       // فراخوانی تابع شما
}
});
<?php } ?>
// وقتی مودال آدرس کاملاً باز شد
$('#addAddressModal').on('shown.bs.modal', function () {
    // نقشه را مجبور کن که ابعاد جدیدش را بفهمد و خودش را بازسازی کند
    setTimeout(function() {        map.invalidateSize();    }, 200); 
    // اگر می‌خواهید هنگام باز شدن، نقشه به مرکز خاصی برود:
    // myMap.setView([35.6997, 51.3380], 14);
});
<?php 
include_once('lib_map.php');
?>
//----------------------------------------------------
function save_addres() {
	//--------------
	//---------------
	$("#ButtonSaveAddres").css("pointer-events", "none");
	$.ajax({
		type: 'POST',
		url: '/data_ajax.php',
		data: $("#addressForm").serialize(),
		dataType: 'json',
		success: function (data, status, xhr) {
			if (data[0]<0) { message(data[1], 'error') }
			if (data[0]==1) { 
				$("#addAddressModal").modal("hide");
				message(data[1], 'success');
				$("#idaddres").val(data[2]);
				$("#dsc_addres").val(data[3]);
			}
		},
		error: function (jqXhr, textStatus, errorMessage) {alert(' textStatus = '+textStatus+' Error: ' + errorMessage);}
	});		

	$("#ButtonSaveAddres").css("pointer-events", "auto");
	
	return false;
	
}
//-------------------------------
function toggleReceiverFields(iid) {
    const checkBox = document.getElementById(iid);
    const fields = document.getElementById("receiverFields");
    
    if (checkBox.checked == true) {
        fields.style.display = "flex";
        // اجباری کردن فیلدها در صورت نمایش
//        fields.querySelectorAll('input').forEach(i => i.setAttribute('required', 'true'));
    } else {
        fields.style.display = "none";
        // برداشتن اجبار فیلدها در صورت عدم نمایش
//        fields.querySelectorAll('input').forEach(i => i.removeAttribute('required'));
    }
}
</script>

<script>
function OpenModal1() {
	//----------------------
	$("#addAddressModalLabel").html("افزودن آدرس جدید");
	$("#idrec_addres").val(0);
	$("#id_ostan").val(<?php echo $id_ostan;?>);
	ChangeCity(<?php echo $id_ostan;?>,<?php echo $id_city;?>);
	$("#addres").val('');
	$("#plaque").val('');
	$("#unit").val('');
	$("#gps").val('');
	$("#distance_to_shop").val('');
	$("#zipcode").val('');
	$("#tetr").val('');
	$('#recip_me').prop('checked', false);
	toggleReceiverFields('recip_me');
	$("#recip_name").val('');
	$("#recip_mobile").val('');
	var lat = '<?php echo $lat;?>';
	var lon = '<?php echo $lon;?>';
	<?php 
	if ($show_gps==1) {
	if ($type_show_gps==1) { 
	?>
		const latlng = L.latLng(lat, lon);
		map.setView(latlng, 16);
		marker.setLatLng(latlng);
	<?php  }else{ ?>
		map.setView([lat, lon], 16);
		setMarker(lat, lon);
	
	<?php 
	}}
	?>
	//-------------------------
    $('#ManageAddressModal').modal('hide');
	$('#ManageAddressModal').on('hidden.bs.modal', function () {
        $('#addAddressModal').modal('show');
        $(this).off('hidden.bs.modal'); 
    });	
}
function CloseModal1() {
    $('#addAddressModal').modal('hide');
}
function CloseModal2() {
    $('#ManageAddressModal').modal('hide');
}
// باز کردن مودال دوم
$('#openManage').on('click', function(e){
    e.preventDefault();
    $('#ManageAddressModal').modal('show');
});
function EditAddres(id) {
//    $('#ManageAddressModal').off('hidden.bs.modal'); // پاک کردن event قبلی
	$('#ManageAddressModal').one('hidden.bs.modal', function () {
		$.ajax({
			type: 'POST',
			url: '/data_ajax.php',
			data: { type:20, idrec:id},
			dataType: 'json',
			success: function (data, status, xhr) {
				if (data[0]<0) { message(data[1], 'error') }
				if (data[0]==1) { 
					var rec = data[1];
					$("#idrec_addres").val(rec['id_main']);
					$("#id_ostan").val(rec['id_ostan']);
					ChangeCity(rec['id_ostan'],rec['id_city']);
					$("#addres").val(rec['addres']);
					$("#plaque").val(rec['plaque']);
					$("#unit").val(rec['unit']);
					$("#gps").val(rec['gps']);
					$("#distance_to_shop").val(rec['distance_to_shop']);
					$("#zipcode").val(rec['zipcode']);
					$("#tetr").val(rec['tetr']);
					if (rec['recip_me']=='1') {
						$('#recip_me').prop('checked', true);
					}
					toggleReceiverFields('recip_me');
					$("#recip_name").val(rec['recip_name']);
					$("#recip_mobile").val(rec['recip_mobile']);
					var lat = '<?php echo $lat;?>';
					var lon = '<?php echo $lon;?>';
					if (rec['gps']!='') {
						var [lat, lon] = rec['gps'].split(',');
					}
					<?php 
					if ($show_gps==1) {
					if ($type_show_gps==1) { 
					?>
						const latlng = L.latLng(lat, lon);
						map.setView(latlng, 16);
						marker.setLatLng(latlng);
					<?php  }else{ ?>
						map.setView([lat, lon], 16);
						setMarker(lat, lon);
					<?php 
					}
					}
					?>
					//-------------------------
					$("#addAddressModalLabel").html("ویرایش آدرس ["+rec['tetr']+"]");
					$('#addAddressModal').modal('show');
				}
			},
			error: function (jqXhr, textStatus, errorMessage) {alert(' textStatus = '+textStatus+' Error: ' + errorMessage);}
		});		
    });	
    $('#ManageAddressModal').modal('hide');
	
}

</script>
<?php } ?>			


<?PHP 
function mapPhpDayToCustom($phpDay) {
    // php: 0=Sunday → custom: 2
    return (($phpDay + 1) % 7) + 1;
}

function getDefaultWorkingHours() {
    $default = [];
    for ($i = 1; $i <= 7; $i++) {
        $default[$i] = [
            'in1'  => '08:00',
            'out1' => '23:59',
            'in2'  => '',
            'out2' => ''
        ];
    }
    return $default;
}

function getDeliveryOptions($limit = 3, $maxDaysCheck = 10) {
	global $row_setting;
	$weekends 				= $row_setting['weekends'];
	$working_hours_json		= $row_setting['working_hours_in_week'];
	$order_preparation		= $row_setting['order_preparation'];

    $workingHours = json_decode($working_hours_json, true);

    // ✅ اگر تعریف نشده → مقدار پیش‌فرض
    if (!is_array($workingHours) || empty($workingHours)) {
        $workingHours = getDefaultWorkingHours();
    }
	
    // 🧠 مدیریت تعطیلی هفتگی
    if ($weekends == 0) {
        $weekendDays = []; // هیچ روزی تعطیل نیست
    } else {
        $weekendDays = is_array($weekends) ? $weekends : [$weekends];
    }

	$nowTimestamp = time();
    $results = [];

    // ⏱️ زمان آماده شدن سفارش
	$readyTimestamp = $nowTimestamp;
	if ($order_preparation > 0 ) { $readyTimestamp = $nowTimestamp + ($order_preparation * 60); }
    for ($i = 0; $i < $maxDaysCheck; $i++) {

        $currentTimestamp = strtotime("+$i day", $nowTimestamp);

        $gDate = date("Y/m/d", $currentTimestamp);
        $jDate = substr(gregorian_to_jalali_str($gDate), 0, 10);

        $phpDay = (int)date("w", $currentTimestamp);
        $configDay = mapPhpDayToCustom($phpDay);

        // 🚫 تعطیلی هفتگی
        if (in_array($configDay, $weekendDays)) {
            continue;
        }

        // 🚫 تعطیلی رسمی
		$query = pdo_query("SELECT COUNT(*) FROM holiday WHERE date = '$jDate' ");
		$stmt  = pdo_count($query);
        if ($stmt > 0) {
            continue;
        }

        if (!isset($workingHours[$configDay])) continue;

        $slots = [
            [$workingHours[$configDay]['in1'], $workingHours[$configDay]['out1']],
            [$workingHours[$configDay]['in2'], $workingHours[$configDay]['out2']]
        ];

        foreach ($slots as $slot) {

            list($start, $end) = $slot;

            if (empty($start) || empty($end)) continue;

            $slotStartTs = strtotime($gDate . " " . $start);
            $slotEndTs   = strtotime($gDate . " " . $end);

            // ⛔ اگر بازه کاملاً گذشته
            if ($slotEndTs <= $nowTimestamp) {
                continue;
            }

            // 🎯 اگر امروز است
            if ($i == 0) {

                // 🔥 اگر آماده‌سازی صفر → رفتار قبلی
                if ($order_preparation == 0) {

                    // داخل بازه → حذف
                    if ($nowTimestamp >= $slotStartTs && $nowTimestamp < $slotEndTs) {
                        continue;
                    }

                    if ($nowTimestamp >= $slotEndTs) {
                        continue;
                    }

                    $finalStart = $start;
                } 
                else {

                    // ⏱️ اگر آماده‌سازی بعد از پایان بازه است → حذف
                    if ($readyTimestamp >= $slotEndTs) {
                        continue;
                    }

                    // اگر آماده‌سازی داخل بازه است → شروع از زمان آماده‌سازی
                    if ($readyTimestamp > $slotStartTs) {
                        $finalStart = date("H:i", $readyTimestamp);
                    } else {
                        $finalStart = $start;
                    }
                }

            } else {
                // روزهای آینده
                $finalStart = $start;
            }

            // 🎯 خروجی
            $text = "تاریخ " . $jDate . " از " . $finalStart . " تا " . $end;

            $results[] = [
                'value' => $text,
                'label' => $text
            ];

            if (count($results) >= $limit) {
                return $results;
            }
        }
    }

    return $results;
}