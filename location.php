<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>انتخاب لوکیشن روی نقشه</title>
    
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    
    <style>
        #map { 
            height: 400px; 
            width: 100%; 
            border-radius: 10px;
            margin-bottom: 20px;
        }
        .container { max-width: 800px; margin: 30px auto; font-family: tahoma; }
        input { padding: 8px; width: 200px; text-align: center; direction: ltr; }
        label { display: block; margin-top: 10px; }
    </style>
</head>
<body>

<div class="container">
    <h2>محل تحویل سفارش را روی نقشه انتخاب کنید:</h2>
	<?php
/*	
	$result2 = get_gps_city2("قم", "قم");
	if ($result2['status']=='OK') {
		$latitude	= $result2['location']['y'];
		$longitude	= $result2['location']['x'];
	}else{
		$latitude=0;
		$longitude=0;
	}
	
*/
//	$result = get_gps_city("قم", "قم");
	$result = get_gps_city("تهران", "تهران");
	if ($result['status']=='success') {
		$latitude	= $result['lat'];
		$longitude	= $result['lng'];
	}else{
		$latitude=0;
		$longitude=0;
	}
		echo "latitude=$latitude --- longitude=$longitude";

	?>
    
    <div id="map"></div>

    <form method="POST" action="save_address.php">
        <label>عرض جغرافیایی (Latitude):</label>
        <input type="text" id="lat" name="latitude"  placeholder="روی نقشه کلیک کنید" value='<?php echo $latitude ;?>'>

        <label>طول جغرافیایی (Longitude):</label>
        <input type="text" id="lng" name="longitude"  placeholder="روی نقشه کلیک کنید" value='<?php echo $longitude ;?>'>

        <br><br>
        <button type="submit" style="padding: 10px 20px; cursor: pointer;">ثبت نهایی آدرس</button>
    </form>
</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    // 1. تنظیم مختصات پیش‌فرض روی شهر قم
    var defaultLat = <?php echo $latitude;?>;//34.6416;
    var defaultLng = <?php echo $longitude;?>;//50.8746;

    // 2. ایجاد نقشه
    var map = L.map('map').setView([defaultLat, defaultLng], 13);

    // 3. لود کردن لایه نقشه (OpenStreetMap)
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    // 4. متغیر برای نگه داشتن مارکر (نشانه)
    var marker;

    // 5. رویداد کلیک روی نقشه
    map.on('click', function(e) {
        var lat = e.latlng.lat;
        var lng = e.latlng.lng;

        // قرار دادن مختصات در اینپوت‌های فرم
        document.getElementById('lat').value = lat.toFixed(6);
        document.getElementById('lng').value = lng.toFixed(6);

        // حذف مارکر قبلی و اضافه کردن مارکر جدید در محل کلیک
        if (marker) {
            map.removeLayer(marker);
        }
        marker = L.marker([lat, lng]).addTo(map);
        
        // حرکت دادن نرم نقشه به سمت نقطه انتخاب شده
        map.panTo(new L.LatLng(lat, lng));
    });
</script>

</body>
</html>

<?php
function get_gps_city2($ostan,$city) {
	$ADDRESS=urlencode("استان $ostan شهر $city");
	$ApiKey = "service.4cf4338941fe47aeb130ac6db6fb8bcd"; // کلید شما

	$curl = curl_init();

	curl_setopt_array($curl, array(
	CURLOPT_URL => "https://api.neshan.org/v6/geocoding?address=$ADDRESS",
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_ENCODING => '',
	CURLOPT_MAXREDIRS => 10,
	CURLOPT_TIMEOUT => 0,
	CURLOPT_FOLLOWLOCATION => true,
	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	CURLOPT_CUSTOMREQUEST => 'GET',
	CURLOPT_HTTPHEADER => array(
		"Api-Key: $ApiKey"
	),
	));
	$response = curl_exec($curl);
	$httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

	if (curl_errno($curl)) {
        return ['status' => 'error', 'message' => "سرویس موردنظر مقداری برنگردان"];
	}
	curl_close($curl);
	$data = json_decode($response, true);
//	var_dump($data);
	return $data;

}
function get_gps_city($ostan,$city) {
	$jsonParam = json_encode([
		"address" => "$ostan $city",
		"city" => "$city",
		"province" => "$ostan",
	]);
    $apiKey = "service.4cf4338941fe47aeb130ac6db6fb8bcd"; 
	

	$encodedJson = urlencode($jsonParam);
	$url = "https://api.neshan.org/geocoding/v1/plus?json=" . $encodedJson;

	$curl = curl_init();

	curl_setopt_array($curl, array(
	  CURLOPT_URL => $url,
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_ENCODING => '',
	  CURLOPT_MAXREDIRS => 10,
	  CURLOPT_TIMEOUT => 0,
	  CURLOPT_FOLLOWLOCATION => true,
	  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	  CURLOPT_CUSTOMREQUEST => 'GET',
	  CURLOPT_HTTPHEADER => array(
		'Content-Type: application/json',
		"Api-Key: $apiKey"
	  ),
	));

	$response = curl_exec($curl);
	$httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

	if (curl_errno($curl)) {
        return ['status' => 'error', 'message' => "سرویس موردنظر مقداری برنگردان"];
	}
	curl_close($curl);
	$data = json_decode($response, true);
	$data2= $data['items'];
	$target=0;
	$jamrec = count($data['items']);
	if ($jamrec>1) $target = (int)floor($jamrec/2);
	var_dump($data);
//	echo $target;
	if (isset($data['items'][$target])) {
		
		// استخراج اولین مورد
		$firstItem = $data['items'][$target];
		return [
                'status' => 'success',
                'lat'    => $firstItem['location']['latitude'],
                'lng'    => $firstItem['location']['longitude'],
                'full_name' => $firstItem['province'] . '-' . $firstItem['city'] ,
            ];		

	} else {
        return ['status' => 'error', 'message' => "سرویس موردنظر مقداری برنگردان"];
		
	}	
}
//---------------------------------
function get_gps_free($state, $city) {
    $address = "استان " . $state . " " . $city;
    $url = "https://nominatim.openstreetmap.org/search?format=json&q=" . urlencode($address);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    // این سرویس حتما نیاز به User-Agent دارد
    curl_setopt($ch, CURLOPT_USERAGENT, 'MyStoreApp/1.0'); 

    $response = curl_exec($ch);
    curl_close($ch);

    $data = json_decode($response, true);

    if (!empty($data)) {
        return [
            'lat' => $data[0]['lat'],
            'lng' => $data[0]['lon'],
            'display_name' => $data[0]['display_name']
        ];
    }
    return null;
}

// تست
//$res = get_gps_free("قم", "جعفریه");print_r($res);

function get_coordinates_by_city_and_state($stateName, $cityName) {
    // کلید اختصاصی خود را از پنل توسعه‌دهندگان نشان جایگزین کنید
    $apiKey = "service.4cf4338941fe47aeb130ac6db6fb8bcd"; 
    
    // ترکیب نام استان و شهر برای دقت حداکثری (مثلاً: قم، سلفچگان)
    $fullAddress = "استان $stateName شهر $cityName";
    $encodedAddress = urlencode($fullAddress);
    
    // آدرس API نشان
    $url = "https://api.neshan.org/v1/search?term=" . $encodedAddress;

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // اضافه شدن برای پیگیری ریدایرکت‌های احتمالی
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Api-Key: $apiKey"
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

if (curl_errno($ch)) {
    echo 'Curl Error: ' . curl_error($ch);
}

curl_close($ch);

echo "HTTP Code: $httpCode <br>";
echo "Response: <pre>" . print_r(json_decode($response, true), true) . "</pre>";
/*
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Api-Key: $apiKey"
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode == 200) {
        $data = json_decode($response, true);
        
        if (isset($data['items']) && count($data['items']) > 0) {
            // نتیجه اول معمولاً مرکز شهر یا دقیق‌ترین تطبیق است
            $firstResult = $data['items'][0];
            
            return [
                'status' => 'success',
                'lat'    => $firstResult['location']['y'],
                'lng'    => $firstResult['location']['x'],
                'full_name' => $firstResult['title'],
                'address' => $firstResult['address']
            ];
        } else {
            return ['status' => 'error', 'message' => 'مختصاتی برای این شهر در این استان یافت نشد.'];
        }
    } else {
        return ['status' => 'error', 'message' => 'خطا در ارتباط با سرور نشان. کد خطا: ' . $httpCode];
    }
*/	
}


// --- مثال استفاده عملی ---
/*
$result = get_coordinates_by_city_and_state($state, $city);

if ($result['status'] == 'success') {
    echo "<h3>اطلاعات یافت شده:</h3>";
    echo "جستجو براساس: استان $state، شهر $city <br>";
    echo "نام کامل ثبت شده: " . $result['full_name'] . "<br>";
    echo "آدرس دقیق: " . $result['address'] . "<br>";
    echo "<b>Latitude (عرض جغرافیایی):</b> " . $result['lat'] . "<br>";
    echo "<b>Longitude (طول جغرافیایی):</b> " . $result['lng'] . "<br>";
} else {
    echo "خطا: " . $result['message'];
}
*/
?>