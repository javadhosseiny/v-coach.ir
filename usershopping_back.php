<?php
//session_start();
include('topmain.php');
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
	
	
	$ListOstan = array();	
	$query3  = pdo_query("select id,name from `ostan` ",'',0);
	while ($row=pdo_fetch($query3)) {
		$ListOstan[$row['id']] = $row['name'];
	}
	$name 	= $usernamefarsi;
	$mobile = $usermobile;
	$email  = $useremail;
	$id_ostan	= $useridostan;
	$id_city	= $useridcity;
	$addres		= $useraddres;
	$zipcode	= $userzipcode;
	if ($id_ostan==0) {
		$id_ostan= $row_setting['id_ostan']; 
		$id_city = $row_setting['id_city'];
	}
	$display_city='';
	if ($row_setting['show_ostan_city']==0) {
		$display_city = 'd-none';
	}
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
	<div class='row' id='main_form'>
		<div class="col-12 col-md-6 d-flex pl-4" >
			<div class='row'>
			<div id="grid-list" class="table-responsive" style="box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19); flex:1; overflow-y: auto;" >	
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
			<form action="" id="FormOrder" method="post"  name="FormOrder" onsubmit="return check();" >
				<input type='hidden' name='type' value='15' >
				<input type='hidden' name='idrec' value='0' >
				<input type='hidden' name='redirect' id='redirect' value='0' >
				<input type='hidden' name='weight_all' value = '<?php echo $SumWeight;?>'>
				<input type='hidden' name='PriceAll' id='PriceAll' value='<?php echo $PriceAll;?>'>
				<input type='hidden' name='PriceKOL' id='PriceKOL' value='<?php echo $PriceAll;?>'>
			<div class='col-12 cart-form mt-4'>
			<div class='row'>
				<div class='col-12 mb-4'>
					<span class="box-title">اطلاعات تحویل گیرنده</span>
				</div>
				<div class="col-12 col-md-6">
					<div class="form-group">
						<label class="form-label">نام و نام خانوادگی تحویل گیرنده<span class='text-danger'>*</span>
						</label>
						<input class="form-control" value="<?php echo $name;?>" id="name" name="name"  type="text" >
					</div>
				</div>
				<div class="col-12 col-md-6">
					<div class="form-group">
						<label class="form-label">شماره موبایل<span class='text-danger'>*</span></label>
						<input class="form-control" value="<?php echo $mobile;?>" id="mobile" name="mobile"  type="text" style='direction:ltr;' onkeypress="return isNormalNumber(event)" >
						
					</div>
				</div>
				<div class="col-12 col-md-6 <?php echo $display_city;?>">
					<div class="form-group">
						<label class="form-label">استان محل سکونت<span class='text-danger'>*</span></label>
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
						<label class="form-label">شهر محل سکونت<span class='text-danger'>*</span></label>
						<select class="form-control" id="id_city" name="id_city" >
						<option value=0></option>
						</select>
						<input type='hidden' name='idcity' id='idcity' value='<?php echo $id_city;?>'>
					</div>
				</div>
				<div class="col-12">
					<div class="form-group">
						<label class="form-label">آدرس پستی<span class='text-danger'>*</span></label>
						<input class="form-control" value="<?php echo $addres;?>" id="addres" name="addres"  type="text">
						
					</div>
				</div>
				<div class="col-12 col-md-6">
					<div class="form-group">
						<label class="form-label">کدپستی<span class='text-danger'>*</span></label>
						<input class="form-control" value="<?php echo $zipcode;?>" id="zipcode" name="zipcode"  type="text" style='direction:ltr;' onkeypress="return isNormalNumber(event)" maxlength=10 >
					</div>
				</div>
				<div class="col-12 col-md-6">
					<div class="form-group">
						<label class="form-label">پست الکترونیکی</label>
						<input class="form-control" value="<?php echo $email;?>" id="email" name="email" placeholder="" type="email" style='direction:ltr;' >
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
									<input type='radio' id='sender$send_id' name='sender' value='$send_id' $sel onchange='ChangePrice(this);' data-price='$send_price'>
									<label for='sender$send_id' class='text-secondry' alt='$send_dsc'>
									$send_tetr <span class='text-success'> $StrPrice </span>
									</label>
								";
								$active_send='ok';
								if ($send_price==0) break;// در صورت رایگان بودن بقیه را نمایش ندهد
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
			
			<div class='col-12 cart-form mt-4'>
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
			</form>
			
		</div>
	</div>
	

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

<?php if ($level==2) { ?>
$(document).ready(function () {
	ChangeCity(<?php echo $id_ostan;?>,<?php echo $id_city;?>);
	$("#id_ostan").val(<?php echo $id_ostan;?>);
	var checkedIdSend = document.querySelector('input[name="sender"]:checked');
  	if (checkedIdSend) {
		ChangePrice(checkedIdSend);
	} else {
		$("#price_send").html();
		$("#PriceKOLStr").html();
		$("#send_price").val(0);
		$("#PriceKOL").val(<?php echo $PriceAll;?>);
		
	}
	var Idpayment = document.querySelector('input[name="payment"]:checked');
  	if (Idpayment) {
		var myInput		= $(Idpayment);
	  	var showvariz 	= myInput.data('showvariz');
		ShowPanelVariz(showvariz)

	}else{
		$('#variz').addClass('d-none');
	}

});

function ChangePrice(iid) {
	var myInput		= $(iid);
	var newprice 	= myInput.data('price');
	var newpriceStr	= FormatNumberBy3(newprice);
	$("#price_send").html(newpriceStr + '<?php echo $price_unit;?>' );
	$("#send_price").val(newprice);
	var PriceAllKOL = <?php echo $PriceAll;?>;
	var newpriceKOL = parseInt(PriceAllKOL) + parseInt(newprice);
	var newpriceKOLStr	= FormatNumberBy3(newpriceKOL);
	$("#PriceKOLStr").html(newpriceKOLStr + '<?php echo $price_unit;?>' );
	$("#PriceKOL").val(newpriceKOL);
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
		message('لطفا کد تخفیف را به درستی وارد نمایید','error',0,'discount_code');
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
	if ($('#name').val()=='') {
		message('لطفا نام و نام خانوادگی تحویل گیرنده را  وارد نمایید','error',0,'name');
		return false;
	}
	if ($('#mobile').val()=='') {
		message('لطفا شماره موبایل را  وارد نمایید','error',0,'mobile');
		return false;
	}
	if ($('#addres').val()=='') {
		message('لطفا آدرس پستی را  وارد نمایید','error',0,'addres');
		return false;
	}
	if ($('#zipcode').val()=='') {
		message('لطفا کد پستی را  وارد نمایید','error',0,'zipcode');
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
			if (data[0]<0) { message(data[1], 'error') }
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
<?php } ?>
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