<?php
include('topmain.php');
$title_main = 'آدرس های من';
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

if (isset($_REQUEST['deleted'])) {
 	$id_deleted =  intval($_REQUEST['deleted']); 
	$query 	= pdo_query("select * from `user_addres` where id_user='$usernameid' and id_main='$id_deleted' and isdeleted=0");
	$row	= pdo_fetch($query);
	if ($row!='') {
		$name = $row['tetr'];
		$query=pdo_query("update `user_addres` set isdeleted=1, ip_last_edit='$ip_last_edit', date_last_edit='$date_last_edit'  where id_main='$id_deleted'"); 
		echo "<script> message('آدرس [$name] با موفقیت حذف شد', 'success') </script>";
	}
}
//-------------------------------
$ListOstan = array();	
$query3  = pdo_query("select id,name from `ostan` ",'',0);
while ($row=pdo_fetch($query3)) {
	$ListOstan[$row['id']] = $row['name'];
}
$id_ostan= $row_setting['id_ostan']; 
$id_city = $row_setting['id_city'];
if ($row_setting['show_ostan_city']==0) {$display_city = 'd-none';} else {$display_city='';}
//----------------------------------------------
$ShowAll=true;
// show list of order
$query 	= pdo_query("select * from `user_addres` where id_user='$usernameid' and isdeleted=0 order by date_last_edit desc",'',0);
$ShowAll=true;



?>
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
//
$show_gps			= $row_setting['show_gps'];
$type_show_gps 		= $row_setting['type_show_gps'];
$apikey_neshan 		= $row_setting['apikey_neshan'];
$apikey_neshan_web 	= $row_setting['apikey_neshan_web'];
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
<div  class="modal fade"  id="DeleteRec"  tabindex="-1"  aria-hidden="true"></div>
<main class="main-row" style='padding:10px;'>
<div class="container-main bg-white text-center p-2 rounded">
<section class="cart-home">
<div class="post-item-cart d-block order-2">
<div class="content-page">
	<div class='col-12 text-left mb-3'>
	<a href='#' class='class="btn btn-success p-2 rounded' onclick='OpenModal1();'>+ افزودن آدرس جدید</a>
	</div>
	<div class="cart-form">
	<table class="table-cart cart table table-borderless">
		<thead>
			<tr>
				<th scope="col" class="product-cart-name text-center" >عنوان</th>
				<th scope="col" class="product-cart-price text-center">تحویل گیرنده</th>
				<th scope="col" class="product-cart-quantity text-center"></th>
				
				<th scope="col" class="product-cart-Total text-center">عملیات</th>
			</tr>
		</thead>
		<tbody >
		<?php
		$counter=0;
		while ($row=pdo_fetch($query))  {
			$counter++;
			$id = $row['id_main'];				$name = $row['tetr'];
			$ret = '';
			$id_ostan = $row['id_ostan'];
			if ($id_ostan!=0) {
				$temp   = pdo_query("select id,name from `ostan` where id='$id_ostan' ",'',0);
				$row2	= pdo_fetch($temp);
				$ret .= " استان $row2[name] - ";
			}
			$id_city  = $row['id_city'];
			if ($id_city!=0) {
				$temp   = pdo_query("select id,name from `city` where id='$id_city' ",'',0);
				$row2	= pdo_fetch($temp);
				$ret .= " شهر $row2[name] ";
			}
			$id_sector= $row['id_sector'];
			if ($id_sector!=0) {
				$temp   = pdo_query("select id,name from `sector` where id='$id_sector' ",'',0);
				$row2	= pdo_fetch($temp);
				$ret .= " [ محله $row2[name] ]";
			}
			//---------------------
			$ret2 = " $row[addres] - پلاک: $row[plaque] - واحد: $row[unit] -  کدپستی: $row[zipcode]";
			//------------------------
			$ret3='';
			if ($row['recip_name']!='')   $ret3 .= " $row[recip_name] ";
			if ($row['recip_mobile']!='') $ret3 .= " موبایل : $row[recip_mobile] ";
			//-----------------------
			echo "
			<tr>
			<th scope='row' class='product-cart-name'>$counter
				<div class='product-title'>
					<a href='/myaddres/$id'>عنوان: $name </a>
					$ret
					<span class=''>$ret2</span>
				</div>
			</th>
			<td class='product-cart-quantity'>
				<span class=''>$ret3</span>
			</td>
			<td class='product-cart-quantity'>
				<span class=''></span>
			</td>
			
			
			<td class='product-cart-Total' align='center'>
				<a href='#'  class='m-2' onclick='DeleteRecordModal(0, $id, \" [آیا نسبت به حذف این آدرس اطمینان دارید؟]  \");' ><i class='fa fa-trash text-secondary' style='font-size:20px;'  title='حذف آدرس' ></i>
				</a>
				<a href='#' class='m-2'  style='cursor:pointer;' onclick='EditAddres($id)'>
					<i class='fa fa-pencil text-secondary'  style='font-size:20px;' title='ویرایش' ></i>
				</a>
			</td>
			</tr>";
		 }	?>		
		</tbody>
	</table>
	</div>
</div>
</div>
</section>
</div>
</main>
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
								<button type='button' class='btn btn-success' onclick="event.preventDefault();searchLocation()">جستجو</button>
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
                            	<input type="text" id='plaque' name="plaque" class="form-control" style='direction:ltr;' onkeypress="return isNormalNumber(event)" >
							</div>
                        </div>
                        <div class="col-md-4">
							<div class="form-group">
	                            <label class="form-label">واحد<span class='text-danger'>*</span></label>
    	                        <input type="text" id="unit" name="unit" class="form-control" style='direction:ltr;' onkeypress="return isNormalNumber(event)" >
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
$(document).ready(function () {
	ChangeCity(<?php echo $id_ostan;?>,<?php echo $id_city;?>);
	$("#id_ostan").val(<?php echo $id_ostan;?>);
});
//----------------------------------------------------
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
//----------------------------------------------------
<?php if ($show_gps==1) { ?>
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
<?php include_once('lib_map.php');?>
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
				window.location.reload();
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
    
    if (checkBox.checked == true) {        fields.style.display = "flex";    } 
		else {        fields.style.display = "none";    }
}
//----------------------------------------------------------------------------------------
//----------------------------------------------------------------------------------------

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
	<?php 
	if ($show_gps==1) {
		echo "
		var lat = '$lat';
		var lon = '$lon';
		";
		if ($type_show_gps==1) { 
			echo "		
			const latlng = L.latLng(lat, lon);
			map.setView(latlng, 16);
			marker.setLatLng(latlng);
		";
	 	}else{ 
			echo "
			map.setView([lat, lon], 16);
			setMarker(lat, lon);
		";
		}
	}
	?>
	//-------------------------
    $('#addAddressModal').modal('show');
}
function CloseModal1() {
    $('#addAddressModal').modal('hide');
}
//--------------------------------------------------
function EditAddres(id) {
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
				<?php if ($show_gps==1) { ?>
				var lat = '<?php echo $lat;?>';
				var lon = '<?php echo $lon;?>';
				if (rec['gps']!='') {
					var [lat, lon] = rec['gps'].split(',');
				}
				<?php if ($type_show_gps==1) { ?>
					const latlng = L.latLng(lat, lon);
					map.setView(latlng, 16);
					marker.setLatLng(latlng);
				<?php  }else{ ?>
					map.setView([lat, lon], 16);
					setMarker(lat, lon);
				<?php } } ?>
				//-------------------------
				$("#addAddressModalLabel").html("ویرایش آدرس ["+rec['tetr']+"]");
				$('#addAddressModal').modal('show');
			}
		},
		error: function (jqXhr, textStatus, errorMessage) {alert(' textStatus = '+textStatus+' Error: ' + errorMessage);}
	});		
}

</script>



<?php 
include('bottom.php');

?> 