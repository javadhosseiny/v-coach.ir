<?php
$MaxRec=4;
include('topmain.php');
$title_main = 'مقایسه محصولات';
$OkCompare=false;
if (isset($_COOKIE['ListCompare'])) {
	$ListCompare = array_filter(explode(',',$_COOKIE['ListCompare']));
	if (count($ListCompare)>0) { $OkCompare=true;		
	}
}
if (!$OkCompare) {	$ErrorMsg = "هیچ محصولی جهت مقایسه انتخاب نشده است";}
include('top.php'); 
//----------------------------------------------
$bank1='product'; 	
$bank2='product_price'; 	
$ListId	 = implode(',',$ListCompare);
$shart = " and  ($bank1.id in ($ListId) )";
$basequary = "select $bank1.*, $bank1.tetr, $bank1.leds, $bank1.photo_product, $bank1.spectioal, $bank1.best_saller, $bank1.vote_sum, $bank1.vote_count, $bank2.id as id_price, $bank2.price1, $bank2.price2, $bank2.cash, $bank2.cash_unlimited from `$bank1` inner join `$bank2` on $bank1.id=$bank2.id_product $shart group by $bank1.id";
$select = "$basequary  limit 0, $MaxRec";
$query	= pdo_query($select,'',0);
$jamkol	= pdo_rowcount($query);
//echo "<div class='row' style='margin:auto; display: inline-block; width:100%;'></div>";
//var_dump($rowall);
?>
<!-- product-comparison-------------------->
<style>
#results-container {
    border: 1px solid #ccc;
    max-height: 200px;
    overflow-y: auto;
    background-color: #fff;
    position: absolute; /* برای قرارگیری روی بقیه عناصر */
/*    width: 250px; */
    z-index: 100;
}
#results-container ul {
    list-style: none;
    padding: 0;
    margin: 0;
}
#results-container li {
    padding: 8px 12px;
    border-bottom: 1px solid #eee;
    cursor: pointer;
}
#results-container li:hover {
    background-color: #f0f0f0;
}
</style>
<main class="main-row mb-4">
<div class="container-main">
<div class="col-12">
<div class="comparison">
<table class="table">
	<thead>
		<tr>
			<td class="align-middle">
				<div class="form-ui">
					<label class="form-label">جستجو براساس عنوان محصول
					</label>
					<input class="form-control" value="" id="search-box" name="name"  type="text" placeholder='' >
				</div>
				<div id="results-container"></div>
			</td>
			<?php 
			$tr1="<tr class='bg-cs-table-tr'><th class='text-uppercase'>خلاصه</th>";
			$tr2="";
			$counter=0;
			$addtd='';
			while ($row=pdo_fetch($query)) {
				$counter++;
				$id 	= $row['id'];					$leds		= $row['leds'];
				$tetr 	= $row['tetr'];					$tetrseo	= $row['url'];
				$photo	= $row['photo_product'];		$link 		= $row['url_redirect'];
				$price01= $row['price1'];				$price02	= $row['price2'];
				
				if ($photo=='') {$photo=$logofile;}	else {$photo=$upload_path_main.$photo; }
				if ($tetrseo=='') $tetrseo=$tetr;
				if ($link=='') $link  = "/product/$id/" . LinkSeo($tetrseo);
				if ($price02>0) $price=$price02;	else $price = $price01;
				$price = number_format($price);
				echo "<td>
					<div class='comparison-item'>
						<span class='remove-item' onclick='AddDelRec($id);'>
							<i class='mdi mdi-close'></i>
						</span>
						<a class='comparison-item-thumb' href='#'>
							<img src='$photo'alt='$tetr' style='border-radius: 50%;max-height:200px;  width:auto;'>
						</a>
						<a class='comparison-item-title' href='$link' target=_blank> $tetr</a>
						<span class='amount'>$price <span> $price_unit </span>
						</span>
					</div>
				</td>
				";
				$tr1 .= "<td class=''><span class='text-medium'>&nbsp;$leds&nbsp;<span></td>";
				//-----------------------------
				$query2 = pdo_query("SELECT    p.tetr AS product_name,    pp.tetr AS property_title,    ppp.content AS property_content 
				FROM product AS p
				JOIN    product_property AS pp ON p.id = pp.id_product
				JOIN    product_price_property AS ppp ON pp.id = ppp.id_property
				WHERE   p.id = 1 and p.isdeleted=0 ORDER BY    p.id,    pp.tetr;");
				while ($row2=pdo_fetch($query2)) {
					$title 	= $row2['property_title'];	
					$content= $row2['property_content'];
					$tr2 .= "<tr><tr class='bg-cs-table-tr'><th class='text-uppercase'>$title</th>";
					$temp1 = str_repeat("<td></td>", ($counter-1));
					$temp2 = str_repeat("<td></td>", ($jamkol-$counter));
					$tr2 .= "$temp1 <td>$content</td> $temp2 </tr>";
				}
				//-----------------------------
			}
			if ($jamkol>$counter) $addtd = str_repeat("<td></td>", ($jamkol-$counter));
			echo $addtd;
			$tr1 .= "$addtd</tr>";
			?>
		</tr>
	</thead>
	<tbody>
		<?php 
		echo $tr1;
		echo $tr2;
		?>
	</tbody>
</table>
</div>
</div>
</div>
</main>
<script>
$(document).ready(function() {
    $("#search-box").keyup(function() {
        var searchTerm = $(this).val();
        if (searchTerm.length >= 3) {
			$.ajax({
				type: 'POST',
				url: '/data_ajax.php',
				data: { type: 17, searchVar: searchTerm},
				dataType: 'json',
				success: function (data, status, xhr) {
					if (data[0]<0) { message(data[1], 'error') }
					if (data[0]==1) { 
						$("#results-container").html(data[1]);
					}
				},
				error: function (jqXhr, textStatus, errorMessage) {alert(' textStatus = '+textStatus+' Error: ' + errorMessage);}
			});		
        } else {
            // اگر تعداد کاراکتر کمتر بود، لیست نتایج را خالی می‌کنیم
            $("#results-container").html("");
        }
    });
});
//---------------------------------
function AddDelRec(id) {
	$.ajax({
		type: 'POST',
		url: '/data_ajax.php',
		data: { type: 11, id: id},
		dataType: 'json',
		success: function (data, status, xhr) {
			if (data[0]<0) { message(data[1], 'error') }
			if (data[0]==1) { 
				message(data[1], 'success');
				window.location='/compare/';
			}
			if (data[0]==2) { 
				message(data[1], 'error');
				window.location='/compare/';
			}
		},
		error: function (jqXhr, textStatus, errorMessage) {alert(' textStatus = '+textStatus+' Error: ' + errorMessage);}
	});		
}

</script>
<!-- product-comparison-------------------->
<?php 
include('bottom.php');
?> 