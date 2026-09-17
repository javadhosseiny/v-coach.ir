<?php 
include 'topmain.php';
$servername = $sitenamelink; //'https://'.$_SERVER['HTTP_HOST'] . $folder_source;
header("Content-Type: application/xml; charset=utf-8");
$date = date('Y-m-d');
echo "<?xml version=\"1.0\" encoding=\"UTF-8\" ?> \r\n";
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <?php
	$countrec = 1000;
	$array = $GroupAll;
	if (is_array($array)) {
		foreach ($array as $categoryId => $category) {
			$show = $category['SHOW'];
			$tetr = tetr2link($category['NAME']);
			$link = $category['LINK'];
			if (strpos($link, 'http://') === 0 || strpos($link, 'https://') === 0) {
				//$link = $category['LINK'];
				$show=0;
			}else{
				$link = $servername .  $category['LINK'];
			}
			$link = htmlspecialchars($link, ENT_XML1, 'UTF-8');
			if ($show==1) {
			echo "
			<url>
				<loc>$link</loc>
				<lastmod>$date</lastmod>
				<changefreq>always</changefreq>
				<priority>$row_setting[rate_page]</priority>
			</url>";
			}
			
		}
	}
	//-----------------------------
	$bank1='product'; 	
	$bank2='product_price'; 	
	$shart = " where $bank1.active=2 and $bank1.isdeleted=0 $shartdatetime ";
	$sortquery = " order by $bank1.pdate desc, $bank1.ptime desc ";
	
	$basequary = "select $bank1.*, $bank1.tetr, $bank1.leds, $bank1.photo_product, $bank1.spectioal, $bank1.best_saller, $bank1.vote_sum, $bank1.vote_count, $bank2.id as id_price, $bank2.price1, $bank2.price2, $bank2.cash, $bank2.cash_unlimited from `$bank1` inner join `$bank2` on $bank1.id=$bank2.id_product $shart group by $bank1.id  $sortquery limit 0, $countrec";

	$query=pdo_query($basequary,'',0);
	while ($row=pdo_fetch($query))  {
		$tetr = tetr2link($row['tetr']);
		$link = $servername . "/product/$row[id]/$tetr";
		$link = htmlspecialchars($link, ENT_XML1, 'UTF-8');
		echo "
			<url>
				<loc>$link</loc>
				<lastmod>$date</lastmod>
				<changefreq>always</changefreq>
				<priority>$row_setting[rate_product]</priority>
			</url>";
	}
	//-----------------------------
	$bank1='weblog'; 	
	$shart = " where $bank1.active=2 $shartdatetimeWeblog ";
	$sortquery = " order by date desc, time desc ";
	$basequary = "select * from $bank1  $shart $sortquery limit 0, $countrec ";

	$query=pdo_query($basequary,'',0);
	while ($row=pdo_fetch($query))  {
		$tetr = trim(tetr2link($row['tetr']));
		$link = $servername . "/weblog/$row[id]/$tetr";
		$link = htmlspecialchars($link, ENT_XML1, 'UTF-8');		
		echo "
			<url>
				<loc>$link</loc>
				<lastmod>$date</lastmod>
				<changefreq>always</changefreq>
				<priority>$row_setting[rate_product]</priority>
			</url>";
	}
	
?>


</urlset>

<?php 
function tetr2link($tetr) {
	$search = array('/&lsquo;/u', '/&rsquo;/u', '/&ldquo;/u', '/&rdquo;/u', '/&mdash;/u','/&zwnj;/u','/&raquo;/u','/&laquo;/u','/&quot;/u','/&lrm;/u','/&/u');
	$tetr = preg_replace($search, '_', $tetr); 
	$tetr = str_replace(' ','_',str_replace('/','_',trim($tetr)));
	$tetr  =htmlspecialchars($tetr);
	return $tetr;
}