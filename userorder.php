<?php
include('topmain.php');
$title_main = 'سفارشات من';
include('top.php'); 
//------------
if (!$ok_cookie) {	
	echo "
	<div class='row' style='margin:auto; display: inline-block; width:100%;'>
	<div class='col-12 mt-4 mb-4 bg-danger rounded text-center w-50 p-2 text-white' style='margin:auto;'>
	جهت  ابتدا در سایت ثبت نام کرده و یا وارد اکانت کاربری خود شوید <BR> <a href='/login/'>لینک ورود</a>
	</div></div>";
	include('bottom.php');
	exit;
}
if (isset($_REQUEST['id']))  	$id     = intval($_REQUEST['id']);		else $id=0;
if ($id==0) {
	// show list of order
	$query 	= pdo_query("select * from `order_title` where id_customer='$usernameid' and isdeleted=0 order by date_save desc");
	$ShowAll=true;
} else {
	$query 	= pdo_query("select * from `order_title` where id_main='$id' and id_customer='$usernameid' and isdeleted=0 ");
	$row	= pdo_fetch($query);
	if ($row=='') {
		echo "
		<div class='row' style='margin:auto; display: inline-block; width:100%;'>
		<div class='col-12 mt-4 mb-4 bg-danger rounded text-center w-50 p-2 text-white' style='margin:auto;'>
		<BR>این شماره سفارش متعلق به شما نمی باشد، لطفا شماره سفارش صحیح را انتخاب کنید<BR><BR>
		</div></div>";
		include('bottom.php');
		exit;
	}
	$ShowAll=false;
	$date_save	= $row['date_save'];
	list($MaxHour, $StrDifTime) = DifTimeNow($date_save);
	
}

?>
<script>
$(document).ready(function () {
//	ChangeCity($("#id_ostan").val(),10);
})
</script>
<main class="main-row" style='padding:10px;'>
<div class="container-main bg-white text-center p-2 rounded">
<section class="cart-home">
<div class="post-item-cart d-block order-2">
<div class="content-page">
<?php if ($ShowAll){ ?>
<div class="cart-form">
	<table class="table-cart cart table table-borderless">
		<thead>
			<tr>
				<th scope="col" class="product-cart-name text-center" >اطلاعات</th>
				<th scope="col" class="product-cart-price text-center">وضعیت ها</th>
				<th scope="col" class="product-cart-quantity text-center">مبالغ</th>
				<th scope="col" class="product-cart-Total text-center">عملیات</th>
			</tr>
		</thead>
		<tbody >
		<?php
		$counter=0;
		while ($row=pdo_fetch($query))  {
			$counter++;
			$id = $row['id_main'];				
			$date = $row['date'];				$date_save	= $row['date_save'];
			$status_order=$row['status_order'];	$status_payment=$row['status_payment'];	
			$status_send=$row['status_send'];	
			$price_send = number_format($row['price_send']);
			$price_payment = number_format($row['price_payment']);
			$price_discount= number_format($row['price_discount']);
			$price_total = number_format($row['price_total']);
			if ($price_discount!='0') $price_discount = " مبلغ تخفیف: [$price_discount] $price_unit - ";
			
			$StrOrder 	= $ListTypeOrder[$status_order];
			$StrPayment	= $ListTypePayment[$status_payment];
			$StrSend	= $ListTypeSend[$status_send];
			//----------------------
			list($MaxHour, $StrDifTime) = DifTimeNow($date_save);
			
			$OkPayment= false;
			if ($status_payment==2) $OkPayment=true; // منتظر پرداخت
			if ($row_setting['delay_payment_order']>0) {
				if ($row_setting['delay_payment_order']<$MaxHour) {
					$OkPayment=false;
				}
			}
			$PaymentButton='';
			if ($OkPayment) {
				$PaymentButton= "
				<span style='line-height: 300%;'></span>
				<a onclick='PaymentOrder($id);' class='proceed-to-checkout' style='background-color:green;color:white;font-size:12px;margin-top:50px;'>پرداخت</a>
				";
			}
			//-----------------------
			echo "
			<tr>
			<th scope='row' class='product-cart-name'>$counter
				<div class='product-title'>
					<a href='/myorder/$id'>شماره سفارش: $id </a>
					- 
					<span style='direction:ltr;'>تاریخ ثبت: $date_save</span>
					<div class='variation'>
						<div class='seller'>$StrDifTime</div>
					</div>
				</div>
			</th>
			<td class='product-cart-quantity'>
				<span class=''>
					وضعیت سفارش: [$StrOrder] -
					<b>وضعیت پرداخت: [$StrPayment]</b> - 
					وضعیت ارسال: [$StrSend ]
				</span>
			</td>
			<td class='product-cart-quantity'>
				<span class=''>
				مبلغ سفارش: [$price_total] $price_unit -
				هزینه ارسال: [$price_send] $price_unit-
				$price_discount
				مبلغ پرداختی: [$price_payment] $price_unit
				</span>
			</td>
			<td class='product-cart-Total'>
				<a href='/myorder/$id' class='proceed-to-checkout' style='color:white;font-size:12px;'>مشاهده</a>
					$PaymentButton
				</span>
			</td>
			</tr>";
		 }	?>		
		</tbody>
	</table>
</div>
<?php } else {?>
<!-- !-->
<div class='row' id='main_form'>
	<div class="col-12 pl-4" >
		<div id="grid-list" class="table-responsive" style="box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19); flex:1; overflow-y: auto;" >	
		<table class="table card-table table-striped text-center table-hover-animation ">
			<thead>
				<tr>
					<th width='40px'>ردیف</th>
					<th width='220px'>نام محصول</th>
					<th width='80px'>قیمت</th>
					<th width='80px'>تعداد</th>
					<th width='80px'>وزن</th>
					<th width='110px'>مجموع</th>
				</tr>
			</thead>
			<tbody >
			<?php
			$PriceAll=0;
			$counter=0;
			$SumWeight=0;
			$query = pdo_query("select * from `order_detils` where id_order='$id' ");
			while ($row2=pdo_fetch($query)) {
				$counter++;
				$price=  $row2['price'];
				if ($row2['price_discount'] > 0) 			$price=$row2['price_discount'];
				$id_price  	= $row2['id_price'];			$total_price= $row2['total_price'];
				$tetr  		= $row2['name'];				$number 	= $row2['number'];
				$idproduct	= $row2['id_product'];			$weight 	= $row2['weight'];
				$tetr_seo = LinkSeo($tetr);
				$link = "/product/$idproduct/$tetr_seo";
				$price_tag = ' ' . number_format($price);// . ' ' . $price_unit ;
				$priceall_tag = ' ' . number_format($total_price) . ' ' . $price_unit ;
				$PriceAll  += ($price * $number);
				echo "
				<tr>
					<td style='text-align:center;' >$counter</td>
					<td>
						<a href='$link' target=_blank><span class='product-name-card'>$tetr</span></a>
					</td>
					<td style='text-align:center;'>$price_tag</td>
					<td style='text-align:center;'>$number</td>
					<td style='text-align:center;'>$weight</td>
					<td>$priceall_tag</td>
				</tr>
				";
			}
			$PriceAllTag = number_format($PriceAll) . ' ' . $price_unit;			
			echo "
			<tr style='background-color:#ccf2cc;'>
				<td colspan='6'  style='text-align:left;'>مجموع: <span class='text-danger'>$PriceAllTag</span></td>
			</tr>
			";
			?>
			</tbody>
		</table>
		</div>
	</div>
	<div class='col-12 cart-form mt-4'>
		<div class='row'>
			<div class='col-12 mb-4'>
				<span class="box-title">اطلاعات تحویل گیرنده</span>
			</div>
			<div class="col-6 col-md-3">
				<div class="form-group">
					<label class="form-label">نام و نام خانوادگی تحویل گیرنده
					</label>
					<input class="form-control" value="<?php echo $row['name_customer'];?>" id="name" name="name"  type="text" readonly>
				</div>
			</div>
			<div class="col-6 col-md-3">
				<div class="form-group">
					<label class="form-label">شماره موبایل</label>
					<input class="form-control" value="<?php echo $row['mobile_customer'];?>" id="mobile" name="mobile"  type="text" style='direction:ltr;' readonly >
					
				</div>
			</div>
			<div class="col-6 col-md-3">
				<div class="form-group">
					<?php
					//var_dump($row);						
					$name_ostan='';
					$query3 = pdo_query("select id,name from `ostan` where id='$row[id_ostan]' ",'',0);
					$row3 	= pdo_fetch($query3);
					if ($row3!='') $name_ostan=$row3['name'];
					$name_city='';
					$query3 = pdo_query("select id,name from `city` where id='$row[id_city]' ",'',0);
					$row3 	= pdo_fetch($query3);
					if ($row3!='') $name_city=$row3['name'];
					?>
					<label class="form-label">استان محل سکونت</label>
					<input class="form-control" value="<?php echo $name_ostan;?>" id="name_ostan" name="name_ostan"  type="text" readonly>
				</div>
			</div>
			<div class="col-6 col-md-3">
				<div class="form-group">
					<label class="form-label">شهر محل سکونت</label>
					<input class="form-control" value="<?php echo $name_city;?>" id="name_city" name="name_city"  type="text" readonly>
				</div>
			</div>
			<div class="col-12 col-md-6">
				<div class="form-group">
					<label class="form-label">آدرس پستی</label>
					<input class="form-control" value="<?php echo $row['addres'];?>" id="addres" name="addres"  type="text" readonly>
					
				</div>
			</div>
			<div class="col-6 col-md-3">
				<div class="form-group">
					<label class="form-label">کدپستی</label>
					<input class="form-control" value="<?php echo $row['zipcode'];?>" id="zipcode" name="zipcode"  type="text" style='direction:ltr;' readonly>
				</div>
			</div>
			<div class="col-6 col-md-3">
				<div class="form-group">
					<label class="form-label">پست الکترونیکی</label>
					<input class="form-control" value="<?php echo $row['email_customer'];?>" id="email" name="email" placeholder="" type="email" style='direction:ltr;' readonly >
				</div>
			</div>
		</div>
	</div>
	<div class='col-12 cart-form mt-4'>
		<div class='row'>
			<div class='col-12 mb-4'>
				<span class="box-title">نحوه ارسال</span>
			</div>
			<div class="col-6 col-md-4">
				<div class="form-group">
					<label class="form-label">نحوه ارسال</label>
					<input class="form-control" value="<?php echo $row['name_send'];?>" id="name_send" name="name_send"  type="text" readonly>
				</div>
			</div>
			<div class="col-6 col-md-4">
				<div class="form-group">
					<label class="form-label">مبلغ ارسال (<?php echo $price_unit;?>)</label>
					<input class="form-control" value="<?php echo number_format($row['price_send']);?>" id="price_send" name="price_send"  type="text" readonly dir='ltr'>
				</div>
			</div>
			<div class="col-6 col-md-4">
				<div class="form-group">
					<label class="form-label">وضعیت ارسال</label>
					<input class="form-control" value="<?php echo $ListTypeSend[$row['status_send']];?>" id="status_send" name="status_send"  type="text" readonly>
				</div>
			</div>
			<div class="col-6 col-md-4">
				<div class="form-group">
					<label class="form-label">کد رهگیری مرسوله</label>
					<input class="form-control" value="<?php echo ($row['trackingcode_send']);?>" id="trackingcode_send" name="trackingcode_send"  type="text" readonly dir='ltr'>
				</div>
			</div>
			<div class="col-6 col-md-4">
				<div class="form-group">
					<label class="form-label">وزن مرسوله (گرم)</label>
					<input class="form-control" value="<?php echo number_format($row['weight_send']);?>" id="weight_send" name="weight_send"  type="text" readonly dir='ltr'>
				</div>
			</div>
			<div class="col-6 col-md-4">
				<div class="form-group">
					<label class="form-label">تاریخ ارسال مرسوله</label>
					<input class="form-control" value="<?php echo ($row['date_send']);?>" id="date_send" name="date_send"  type="text" readonly dir='ltr'>
				</div>
			</div>
		</div>
	</div>
	<div class='col-12 cart-form mt-4'>
		<div class='row'>
			<div class='col-12 mb-4'>
				<span class="box-title">نحوه پرداخت</span>
			</div>
			<div class="col-6 col-md-3">
				<div class="form-group">
					<label class="form-label">درگاه پرداخت</label>
					<input class="form-control" value="<?php echo $row['name_payment'];?>" id="name_payment" name="name_payment"  type="text" readonly>
				</div>
			</div>
			<div class="col-6 col-md-3">
				<div class="form-group">
					<label class="form-label">مبلغ قابل پرداخت(<?php echo $price_unit;?>)</label>
					<input class="form-control" value="<?php echo number_format($row['price_payment']);?>" id="price_payment" name="price_payment"  type="text" readonly dir='ltr'>
				</div>
			</div>
			<div class="col-6 col-md-3">
				<div class="form-group">
					<label class="form-label">وضعیت پرداخت</label>
					<input class="form-control" value="<?php echo  $ListTypePayment[$row['status_payment']];?>" id="status_payment" name="status_payment"  type="text" readonly>
				</div>
			</div>
			<div class="col-6 col-md-3">
				<div class="form-group">
					<label class="form-label">تاریخ پرداخت</label>
					<input class="form-control" value="<?php echo $row['date_payment'];?>" id="date_payment" name="date_payment"  type="text" readonly dir='ltr'>
				</div>
			</div>
			<?php 
			$StrOkNotPayment='';
			list($MaxHour, $StrDifTime)=DifTimeNow($row['date_save']);
			$OkPayment= false;
			if ($row['status_payment']==2) $OkPayment=true; // منتظر پرداخت
			if ($row_setting['delay_payment_order']>0) {
				if ($row_setting['delay_payment_order']<$MaxHour) {
					$OkPayment=false;
				}
			}

			if ((!($OkPayment)) and ($row['status_payment']!=1)) { $StrOkNotPayment = "[به دلیل گذشتن از زمان اعتبار سفارش، این سفارش دیگر قابلیت پرداخت ندارد]";
			}
			
			if (($row['type_payment']==1) and ($row['status_payment']==2) ) { $class2='';} 
				else {$class2='d-none';}
			if ($OkPayment) $readonly = ""; else $readonly='readonly';
			?>
			<div class="col-12 <?php echo $class2;?>">
				<div class="form-group">
					<label class="form-label">توضیحات پرداخت</label>
					<textarea <?php echo $readonly;?> name="dsc_payment" id='dsc_payment' class="form-control" dir="rtl"  rows="4"><?php echo ($row['dsc_payment']);?></textarea>
				</div>
			</div>
		</div>
	</div>
	<div class='col-12 cart-form mt-4'>
		<div class='row'>
			<div class='col-12 mb-4'>
				<span class="box-title">اطلاعات سفارش</span>
			</div>
			<div class="col-6 col-md-4">
				<div class="form-group">
					<label class="form-label">شماره سفارش</label>
					<input class="form-control" value="<?php echo $id;?>" id="id_order" name="id_order" dir='ltr'  type="text" readonly>
				</div>
			</div>

			<div class="col-6 col-md-4">
				<div class="form-group">
					<label class="form-label">مبلغ کل سفارش(<?php echo $price_unit;?>)</label>
					<input class="form-control" value="<?php echo number_format($row['price_total']);?>" id="price_total" name="price_total"  type="text" readonly dir='ltr'>
				</div>
			</div>

			<div class="col-6 col-md-4">
				<div class="form-group">
					<label class="form-label">هزینه ارسال(<?php echo $price_unit;?>)</label>
					<input class="form-control" value="<?php echo number_format($row['price_send']);?>" id="price_total" name="price_total"  type="text" readonly dir='ltr'>
				</div>
			</div>

			<div class="col-6 col-md-4">
				<div class="form-group">
					<label class="form-label">مبلغ تخفیف(<?php echo $price_unit;?>)</label>
					<input class="form-control" value="<?php echo number_format($row['price_discount']);?>" id="price_total" name="price_total"  type="text" readonly dir='ltr'>
				</div>
			</div>

			<div class="col-6 col-md-4">
				<div class="form-group">
					<label class="form-label">قابل پرداخت(<?php echo $price_unit;?>)</label>
					<input class="form-control" value="<?php echo number_format($row['price_payment']);?>" id="price_total" name="price_total"  type="text" readonly dir='ltr'>
				</div>
			</div>

			
			<div class="col-12 col-md-4">
				<div class="form-group">
					<label class="form-label">وضعیت سفارش</label>
					<input class="form-control" value="<?php echo $ListTypeOrder[$row['status_order']];?>" id="status_order" name="status_order"  type="text" readonly>
				</div>
			</div>
			
			
			
			<?php if ($row['dsc']!='') {?>
			<div class="col-12">
				<div class="form-group">
					<label class="form-label">توضیحات سفارش</label>
					<textarea readonly name="dsc" class="form-control" dir="rtl"  rows="2"><?php echo $row['dsc'];?></textarea>
				</div>
			</div>
			<?php } ?>
			<div class="col-12">
				<div class="form-group">
					<label class="form-label">زمان گذشته از سفارش
					<span class='text-danger'><?php echo $StrOkNotPayment;?></span>
					</label>
					<input class="form-control" value="<?php echo $StrDifTime;?>"type="text" readonly>
				</div>
			</div>
			
		</div>
	</div>
	<div class='col-12 row mt-3 '>
		<?php if ($OkPayment) {?>
		<div class="col-3 col-md-2 proceed-to-checkout m-1 bg-success" >
		<a onclick="PaymentOrder(<?php echo $id;?>);" class="checkout-button d-block" style='color:white;'>پرداخت</a> 
		</div>
		<?php } ?>
		<div class="col-6 col-md-3 proceed-to-checkout m-1" >
		<a href="/myorder/" class="checkout-button d-block">بازگشت به فهرست سفارش ها</a> 
		</div>
	</div>
	
</div>
<?Php } ?>
<!-- !-->

</div>
</div>
</section>
</div>
</main>
<script>
function PaymentOrder(id) {
	var dsc_payment = null;

	if ($("#dsc_payment").length && typeof $("#dsc_payment").val() !== "undefined") {
		dsc_payment = $("#dsc_payment").val();
	} else {
		dsc_payment = '';
	}
	$.ajax({
		type: 'POST',
		url: '/data_ajax.php',
		data: { type: 16, id_order: id, dsc:dsc_payment},
		dataType: 'json',
		success: function (data, status, xhr) {
			if (data[0]<0) { message(data[1], 'error') }
			if (data[0]==0) { message(data[1], 'success') }
			if (data[0]==1) { 
            	window.location.href = data[1];
			}
		},
		error: function (jqXhr, textStatus, errorMessage) {alert(' textStatus = '+textStatus+' Error: ' + errorMessage);}
	});		

	
	return false;
	
	
}
</script>
<?php 
include('bottom.php');

?> 