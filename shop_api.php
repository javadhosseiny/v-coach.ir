<?php
$RunTop=1;
include('topmain.php');
if (isset($_GET['type']))  $type = trim($_GET['type']); else $type='';
if ($type=='') return;
$query = pdo_query(" select * from product inner join `product_price` on product.id=product_price.id_product where `product`.active=2 $shartdatetime order by product.id desc limit 0,1000 ",'',0);
$total = pdo_rowcount($query);
$TorobProducts = array();
$EmallsProducts = array();
while ($row_product=pdo_fetch($query) ) {
	$id 		= $row_product["id"];
	$link 		= $row_product['url_redirect'];
	$tetr  		= $row_product['tetr'];
	$tetrseo	= $row_product['url'];
//	$id_price	= $row_product['id_price'];
	$price1		= $row_product['price1'];		
	$price2		= $row_product['price2'];
	$cash 		= $row_product['cash'];	
	$price		= $price1;
	$old_price 	= null;
	if ($price2>0) {$price= $price2; $old_price=$price1;}
	if ($price_unit!='ریال') {$price= $price*10; $old_price=$old_price*10;}
	if ($tetrseo=='') $tetrseo=$tetr;
	if ($link=='') $link  = "/product/$id/" . LinkSeo($tetrseo);
	if ($row_product['cash_unlimited']==1) $cash=999999999;
	$availablity = true;
	if ( $cash<=0 ) { $availablity = false;}
	
	$TorobProducts[] = array(
		"product_id" => $id,
		"page_url" => $link,
		"price" => $price,
		"availablity" => $availablity,
		"guarantee" => null,
		"old_price" => $old_price
	);
	
	$EmallsProducts[] = array(
		"id" => $id,
		"title"=> $tetr,
		"url" => $link,
		"price" => $price,
		"guarantee" => null,
		"old_price" => $old_price,
		"is_available" => $availablity,
	);
}

header('Content-Type: application/json');
if ($type=='torob') {
	$TorobOutput = array("success" => true,    "products" => $TorobProducts,    "total" => $total);	
	echo json_encode($TorobOutput, JSON_UNESCAPED_UNICODE);
	return;
}

if ($type=='emalls') {
	$EmallsOutput = array("success" => true,    "products" => $EmallsProducts,   "total" => $total);	
	echo json_encode($EmallsOutput, JSON_UNESCAPED_UNICODE);
	return;
}

?>