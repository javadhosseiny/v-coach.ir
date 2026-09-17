<?php
ob_start();
$RunTop=1;
include('topmain.php');
$output_check = ob_get_clean();

$primary_color = "#266B2D"; // رنگ برند سبز فعلا برای عطاری 110
// مسیر پوسته آفلاین (Offline Shell)
$offline_shell_url = '/offline-shell.html'; 
$logofile 	 = $row_setting['logofile'];  
if ($logofile!='')  {$logofile 	 = $upload_path_main.$logofile;}

// ۱. === متغیرهای دینامیک از PHP ===
// نسخه کش: هر بار که این متغیر تغییر کند، Service Worker جدید نصب می شود.
$cache_version = "v1.1.0"; 
$cache_name = "static-cache-" . $cache_version;



// لیست URLهایی که باید در مرحله نصب کش شوند (فقط منابع ثابت و پوسته آفلاین)
$urls_to_cache = [
    $offline_shell_url,
	"/assets/css/vendor/font-awesome.min.css",
    "/assets/css/vendor/materialdesignicons.css",
    "/assets/css/vendor/bootstrap.css",
    "/assets/css/vendor/owl.carousel.min.css",
    "/assets/css/vendor/nice-select.css",
    "/assets/css/vendor/jquery.jqZoom.css",
    "/assets/css/vendor/sweetalert2.min.css",
	"/vendor/toastr/toastr.css",
    "/assets/css/main.css?0",
    "/assets/css/responsive.css?0",
    "/assets/css/starvote.css",
	"/assets/js/starvote.js",
	"/assets/js/vendor/jquery-3.2.1.min.js",
	"/assets/js/vendor/bootstrap.js",
	"/assets/js/vendor/owl.carousel.min.js",
	"/assets/js/vendor/jquery.countdown.js",
	"/assets/js/vendor/jquery.nice-select.min.js",
	"/assets/js/vendor/jquery.jqZoom.js",
	"/assets/js/vendor/sweetalert2.all.min.js",
	"/vendor/toastr/toastr.min.js",
	"/assets/js/scripts.js?0",
	"/assets/js/function.js?0",
	"/assets/js/main.js?0",
    $logofile
];
// ==================================

// ۲. ارسال هدر صحیح: محتوای ارسالی جاوا اسکریپت است.
header('Content-Type: application/javascript');

// ۳. تولید کد جاوا اسکریپت
$js_code = "
const CACHE_NAME = '" . $cache_name . "';
const OFFLINE_SHELL = '" . $offline_shell_url . "';
const URLS_TO_CACHE = [
    '" . implode("',\n    '", $urls_to_cache) . "'
];

// ----------------------------------------------------------------------
// الف) INSTALL EVENT: کش کردن منابع ثابت (App Shell)
// ----------------------------------------------------------------------
self.addEventListener('install', event => {
  console.log('Service Worker: Installing cache " . $cache_version . "');
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then(cache => {
        // بازگرداندن منابع ثابت و صفحه آفلاین
        return cache.addAll(URLS_TO_CACHE).catch(err => {
            console.error('Cache addAll failed (Is offline-shell.html available?):', err);
        });
      })
      .then(() => self.skipWaiting()) // برای فعال سازی سریعتر
  );
});

// ----------------------------------------------------------------------
// ب) ACTIVATE EVENT: حذف کش های قدیمی
// ----------------------------------------------------------------------
self.addEventListener('activate', event => {
  console.log('Service Worker: Activating and clearing old caches.');
  event.waitUntil(
    caches.keys().then(cacheNames => {
      return Promise.all(
        cacheNames.filter(cacheName => {
          return cacheName !== CACHE_NAME;
        }).map(cacheName => {
          console.log('Service Worker: Deleting old cache: ' + cacheName);
          return caches.delete(cacheName);
        })
      );
    })
    .then(() => self.clients.claim()) // اطمینان از کنترل فوری صفحات جدید
  );
});

// ----------------------------------------------------------------------
// ج) FETCH EVENT: استراتژی واکشی (ترکیب Cache-First و Network-First)
// ----------------------------------------------------------------------
self.addEventListener('fetch', event => {
    const requestUrl = new URL(event.request.url);

    // ۱. استراتژی Network-Only/Bypass برای درخواست های خاص
    // درخواست های API، صفحات مدیریت یا رهگیری را از Service Worker عبور می دهیم
    if (requestUrl.pathname.startsWith('/api/') || 
        event.request.method !== 'GET') {
        return; 
    }

    // ۲. استراتژی Cache-First برای منابع ثابت (CSS, JS, Fonts, PWA Icons)
    const isStaticAsset = requestUrl.pathname.includes('/assets/') || 
                          requestUrl.pathname.endsWith('.css') || 
                          requestUrl.pathname.endsWith('.js') ||
                          requestUrl.pathname.endsWith('.png') ||
                          requestUrl.pathname.endsWith('.woff2');

    if (isStaticAsset) {
        event.respondWith(
            caches.match(event.request).then(response => {
                // اگر در کش بود برگردان، در غیر این صورت از شبکه واکشی کن
                return response || fetch(event.request); 
            })
        );
        return;
    }
    
    // ۳. استراتژی Network-First برای صفحات HTML (شامل روت '/')
    // این استراتژی همیشه سعی می کند آخرین محتوا را از شبکه بگیرد.
    if (event.request.mode === 'navigate' || requestUrl.pathname === '/') {
        event.respondWith(
            fetch(event.request).catch(error => {
                // اگر شبکه قطع بود، پوسته آفلاین را نمایش بده
                console.log('Service Worker: Fetch failed, returning offline shell.');
                return caches.match(OFFLINE_SHELL);
            })
        );
        return;
    }

    // ۴. سایر موارد: فقط از شبکه واکشی کن
    event.respondWith(fetch(event.request)); 
});
";

echo $js_code;
exit;
?>