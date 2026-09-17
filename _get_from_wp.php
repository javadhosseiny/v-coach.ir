<?php
//$url = "https://goushtbazar.com//wp-json/custom/v1/users?token=javad2839";
// https://goushtbazar.com/wp-json/custom/v1/addresses-flat?token=javad2839
// https://goushtbazar.com/wp-json/custom/v1/addresses-flat?token=javad2839&page=1&limit=250
//https://goushtbazar.com/wp-json/custom/v1/user-addresses?token=javad2839&page=1&limit=500
// لینک پایین بهترین برای گرفتن آدرس مشتریان
//https://goushtbazar.com/wp-json/custom/v1/user-addresses?token=javad2839&page=1&limit=500

$url = "https://goushtbazar.com/wp-json/custom/v1/orders?token=javad2839";
// صفحه بندی
//  orders?token=javad2839&page=1&limit=20
//  orders?token=javad2839&page=2&limit=20
//بازه زمانی
//  orders?token=javad2839&after=2025-01-01&before=2025-01-31
//فقط یک ماه
//  orders?token=javad2839&after=2025-02-01&before=2025-02-28
//  سال و ماه
//  orders?token=javad2839&year=2025&month=02
//ترکیبی
//  orders?token=javad2839&page=1&limit=50&after=2025-01-01&before=2025-03-01



function get_list_all_product() {
$url = "https://goushtbazar.com/wp-json/custom/v1/products?token=javad2839";

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);

if (curl_errno($ch)) {
    echo 'Error: ' . curl_error($ch);
    exit;
}

curl_close($ch);

$data = json_decode($response, true);

if (!$data) {
    echo "خطا در دریافت JSON";
    exit;
}

// 🔥 نمایش حرفه‌ای
foreach ($data as $product) {

    echo "<hr>";

    echo "<b>نام:</b> " . $product['name'] . "<br>";
    echo "<b>قیمت:</b> " . $product['price'] . "<br>";
    echo "<b>قیمت اصلی:</b> " . $product['regular_price'] . "<br>";
    echo "<b>قیمت با تخفیف:</b> " . $product['sale_price'] . "<br>";

    echo "<b>slug:</b> " . $product['slug'] . "<br>";
    echo "<b>لینک:</b> <a href='{$product['link']}' target='_blank'>مشاهده</a><br>";

    echo "<b>وضعیت موجودی:</b> " . $product['stock_status'] . "<br>";
    echo "<b>فروش کل:</b> " . $product['total_sales'] . "<br>";

    echo "<b>تاریخ ایجاد:</b> " . $product['date_created'] . "<br>";

    // 🗂 دسته‌ها
    echo "<b>دسته‌بندی:</b> ";
    if (!empty($product['categories'])) {
        echo implode(" , ", $product['categories']);
    }
    echo "<br>";

    // 🏷 تگ‌ها
    echo "<b>تگ‌ها:</b> ";
    if (!empty($product['tags'])) {
        echo implode(" , ", $product['tags']);
    }
    echo "<br>";

    // 🎨 ویژگی‌ها
    echo "<b>ویژگی‌ها:</b><br>";
    if (!empty($product['attributes'])) {
        foreach ($product['attributes'] as $key => $attr) {
            echo " - $key : " . implode(", ", $attr) . "<br>";
        }
    }

    // 🔄 variation ها
    if (!empty($product['variations'])) {
        echo "<b>تنوع‌ها:</b><br>";

        foreach ($product['variations'] as $var) {
            echo " ➤ قیمت: " . $var['price'] . "<br>";
            echo " ➤ موجودی: " . $var['stock_status'] . "<br>";

            if (!empty($var['attributes'])) {
                foreach ($var['attributes'] as $k => $v) {
                    echo " &nbsp;&nbsp; $k : $v <br>";
                }
            }

            echo "<br>";
        }
    }

    // 🖼 تصویر
    if (!empty($product['image'])) {
        echo "<img src='" . $product['image'] . "' width='120'><br>";
    }

    echo "<br>==========================<br>";
}
}