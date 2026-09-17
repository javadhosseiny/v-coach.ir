<?php
$show_gps			= $row_setting['show_gps'];
$type_show_gps 		= $row_setting['type_show_gps'];
$apikey_neshan 		= $row_setting['apikey_neshan'];
$apikey_neshan_web 	= $row_setting['apikey_neshan_web'];
$gps_shop			= $row_setting['gps_shop'];

if ( ($type_show_gps==2) and ($apikey_neshan!='') and ($apikey_neshan_web!='') ) {	$type_show_gps =2;}
	else{$type_show_gps =1;}
	
if ($gps_shop=='') { 
	$lat = '34.641960';  
	$lon = '50.881119'; 
}else{
	list($lat, $lon) = explode(',',$gps_shop);	
}
if ($show_gps==1) {
if ($type_show_gps==1) {	?>


var map = L.map('map').setView([<?php echo $lat;?>, <?php echo $lon;?>], 14);

// لایه OpenStreetMap
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
  attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
}).addTo(map);

let marker = L.marker([<?php echo $lat;?>, <?php echo $lon;?>], { draggable:true }).addTo(map);
updateResult(marker.getLatLng());
reverseGeocode(marker.getLatLng());

//---------------------------------------------- کلیک روی نقشه
map.on('click', function(e){
    marker.setLatLng(e.latlng);
    updateResult(e.latlng);
    reverseGeocode(e.latlng);
});

//---------------------------------------------- کشیدن مارکر
marker.on('dragend', function(e){
    const pos = e.target.getLatLng();
    updateResult(pos);
    reverseGeocode(pos);
});

//---------------------------------------------- آپدیت مختصات در صفحه
function updateResult(pos, addressText='-'){
	
    document.getElementById('lat').innerText = pos.lat.toFixed(6);
    document.getElementById('lng').innerText = pos.lng.toFixed(6);
    document.getElementById('address').innerText = addressText;
	$("#addres").val(addressText);
	var new_gps = pos.lat.toFixed(6) + ',' + pos.lng.toFixed(6);
	$("#gps").val(new_gps);
	var lat1 = <?php echo $lat;?>;
	var lon1 = <?php echo $lon;?>;

	const dist = getDistanceFromLatLonInKm(  lat1, lon1, pos.lat.toFixed(6), pos.lng.toFixed(6));
	var new_dist = dist.toFixed(2); // فاصله هوایی بین نقطه انتخابی تا آدرس فروشگاه
	$("#distance_to_shop").val(new_dist);
/*	
	var new_dist= '';
	getDistanceFromLatLonInKm(lat1, lon1,  pos.lat.toFixed(6), pos.lng.toFixed(6))
	  .then(dist => {  
		alert('salam');
		new_dist = dist.toFixed(2);
		$("#distance_to_shop").val(new_dist);
	  })
	  .catch(err => {console.log("خطا در محاسبه فاصله:"+ err);});

/*
	getRoadDistanceKm(lat1, lon1,  pos.lat.toFixed(6), pos.lng.toFixed(6))
	  .then(dist => {  
		var new_dist = dist.toFixed(2);
		$("#distance_to_shop").val(new_dist);
		})
	  .catch(err => {console.log("خطا در محاسبه فاصله:"+ err);});
*/

}
//---------------------------------------------- جستجو براساس عبارت تایپ شده
function searchLocation(){
    const term = document.getElementById('searchInput').value.trim();
    if(!term){
		message("عبارت جستجو را وارد نمایید", 'error')
        return;
    }

    fetch(`https://nominatim.openstreetmap.org/search?q=${encodeURIComponent(term)}&format=json&limit=5&accept-language=fa`)
    .then(res => res.json())
    .then(data => {
        if(data.length > 0){
            const loc = data[0];
            const latlng = L.latLng(loc.lat, loc.lon);
            map.setView(latlng, 16);
            marker.setLatLng(latlng);
            updateResult(latlng, loc.display_name);
        } else {
			message("نتیجه ای یافت نشد", 'error')
        }
    })
    .catch(err => console.error('Search Error:', err));
}

//----------------------------------------------فراخوانی به هنگام کلیک و تغییر بر روی نقشه
// Reverse Geocoding با Nominatim
function reverseGeocode(pos){
    fetch(`https://nominatim.openstreetmap.org/reverse?lat=${pos.lat}&lon=${pos.lng}&format=json&accept-language=fa`)
    .then(res => res.json())
    .then(data => {
        if(data.display_name){
            updateResult(pos, data.display_name);
        } else {
            updateResult(pos, 'آدرس یافت نشد');
        }
    })
    .catch(err => console.error('Reverse Geocode Error:', err));
}
//----------------------------------------------  بدست آوردن فاصله هوایی بین دو نقطه به کیلومتر
function getDistanceFromLatLonInKm(lat1, lon1, lat2, lon2) {
	
  const R = 6371; // شعاع زمین به کیلومتر
  const dLat = (lat2 - lat1) * Math.PI / 180;
  const dLon = (lon2 - lon1) * Math.PI / 180;
  const a = 
    Math.sin(dLat/2) * Math.sin(dLat/2) +
    Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
    Math.sin(dLon/2) * Math.sin(dLon/2)
    ; 
  const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a)); 
  const d = R * c; 	  // به کیلومتر
  return d;
}

//----------------------------------------------  بدست آوردن فاصله زمینی بین دو نقظه
function getRoadDistanceKm(startLat, startLng, endLat, endLng) {
    const url = `https://router.project-osrm.org/route/v1/driving/${startLng},${startLat};${endLng},${endLat}?overview=false`;

    return fetch(url)
        .then(res => res.json())
        .then(data => {
            if(data.routes && data.routes.length > 0){
                const distanceMeters = data.routes[0].distance; // فاصله بر حسب متر
//                return distanceMeters ; // برگرداند به متر
                return distanceMeters / 1000; // تبدیل به کیلومتر
            } else {
                throw new Error('مسیر یافت نشد');
            }
        });
}

<?php } else {?>
var map = new L.Map("map", {
    key: "<?php echo $apikey_neshan_web;?>", // اینجا کلیدت را بگذار
    maptype: "dreamy",
    center: [<?php echo $lat;?>, <?php echo $lon;?>],
    zoom: 14
});

var marker;

map.on('click', function (e) {

    var lat = e.latlng.lat;
    var lng = e.latlng.lng;

    setMarker(lat, lng);

    reverseGeocode(lat, lng);
});

function setMarker(lat, lng){
    if(marker){
        map.removeLayer(marker);
    }
    marker = L.marker([lat, lng]).addTo(map);

    document.getElementById("lat").innerText = lat;
    document.getElementById("lng").innerText = lng;

	var new_gps = lat + ',' + lng;
	$("#gps").val(new_gps);
	var lat1 = <?php echo $lat;?>;
	var lon1 = <?php echo $lon;?>;
    var new_dist = '';

	getNeshanRoadDistanceKm(lat1, lon1, lat, lng)
	  .then(res => {
			new_dist = res.distanceKm.toFixed(2);
			$("#distance_to_shop").val(new_dist);
		})
	  .catch(err => { 	  console.error("خطا در محاسبه فاصله:", err);	  });

}

function reverseGeocode(lat, lng){
    fetch(`https://api.neshan.org/v5/reverse?lat=${lat}&lng=${lng}`, {
        headers: {
            'Api-Key': '<?php echo $apikey_neshan;?>'
        }
    })
    .then(res => res.json())
    .then(data => {
		var new_addres = data.formatted_address;
        document.getElementById("address").innerText = new_addres;
		$("#addres").val(new_addres);
//		var old_addres = $("#addres").val();  if (old_addres=='') {$("#addres").val(new_addres);}
		
    });
}

function searchLocation(){
    var term = document.getElementById("searchInput").value.trim();
    if(!term){
		message("عبارت جستجو را وارد نمایید", 'error')
        return;
    }

    fetch(`https://api.neshan.org/v1/search?term=${encodeURIComponent(term)}&lat=35.699739&lng=51.338097`, {
		headers: { 'Api-Key': '<?php echo $apikey_neshan;?>' }
		
    })
    .then(res => res.json())
    .then(data => {
        console.log("Search Response:", data);
        if(data.items && Array.isArray(data.items) && data.items.length > 0){
            var location = data.items[0].location;
            map.setView([location.y, location.x], 16);
            setMarker(location.y, location.x);

			var new_addres = data.items[0].address;
			document.getElementById("address").innerText = new_addres;
			$("#addres").val(new_addres);
//			var old_addres = $("#addres").val();  if (old_addres=='') {$("#addres").val(new_addres);}
        } else {
			message("نتیجه ای یافت نشد", 'error')
        }
    })
    .catch(err => console.error("Search Error:", err));
}

/**
 * محاسبه فاصله واقعی روی جاده بین دو نقطه با Neshan Routing API
 * @param {number} startLat - عرض جغرافیایی نقطه شروع
 * @param {number} startLng - طول جغرافیایی نقطه شروع
 * @param {number} endLat - عرض جغرافیایی نقطه مقصد
 * @param {number} endLng - طول جغرافیایی نقطه مقصد
 * @param {string} apiKey - کلید Web Service نشان
 * @returns {Promise<number>} فاصله واقعی روی جاده به کیلومتر
 */
function getNeshanRoadDistanceKm(startLat, startLng, endLat, endLng) {
    const url = `https://api.neshan.org/v2/direction?origin=${startLat},${startLng}&destination=${endLat},${endLng}`;

    return fetch(url, {
        method: 'GET',
        headers: { 'Api-Key': '<?php echo $apikey_neshan;?>' }
    })
    .then(res => res.json())
    .then(data => {
		if(data.routes && data.routes.length > 0 && data.routes[0].legs && data.routes[0].legs.length > 0){
            const leg = data.routes[0].legs[0];
            const distanceKm = leg.distance.value / 1000;   // متر -> کیلومتر
            const durationMin = leg.duration.value / 60;    // ثانیه -> دقیقه
            return { distanceKm, durationMin };
        } else {
            throw new Error("مسیر یافت نشد");
        }    });
}
<?php } }?>