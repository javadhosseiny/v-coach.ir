<?Php 
schema_code_end();
?>
<footer class="footer-main-site">
    <section class="d-block d-xl-block d-lg-block d-md-block d-sm-block order-1 ">
    <div class='row m-0'>
    <?php
    $query = pdo_query("SELECT * FROM `them_detils`  where id_them='$id_them' and isdeleted=0 and active=1 and id_position=2 order by id_position, idsort",'',0);
    $row_abzarak = pdo_fetchall($query);
    if (count($row_abzarak)>0) {
        foreach($row_abzarak as $key=>$value) {
//            var_dump($row_abzarak[$key]);
            ShowAbzarak($row_abzarak[$key]);
        }
    }
    ?>
    </div>
    </section>
    <?php
    $ShowChat = false;
    if ($row_setting['chattype']==0) { $ShowChat = false; }
    if ($row_setting['chattype']==1) { $ShowChat = true; }
    if ($row_setting['chattype']==2) { if ($ok_cookie) {$ShowChat = true;}}
    if ($ShowChat) echo $row_setting['chatscript'];
    ?>
</footer>

<?php
echo $row_setting['scriptcode2'];
//if (basename($_SERVER["SCRIPT_NAME"])=='index.php')	{  } else  {	echo $row_setting['scriptcode2']; }
//echo "<div style='display: none;'><h1>$title></h1><h2>$keyword</h2> </div>";
pdo_disconnect($connection);
?>
<!-- scroll_Progress------------------------->
<div class="progress-wrap">
    <svg class="progress-circle svg-content" width="100%" height="100%" viewBox="-1 -1 102 102">
        <path d="M50,1 a49,49 0 0,1 0,98 a49,49 0 0,1 0,-98" />
    </svg>
</div>
<!-- scroll_Progress------------------------->
<!-- Page Loader----------------------------->
<div class="P-loader">
    <div class="P-loader-content">
        <div class="logo-loader">
            <img src="<?php echo $logopage;?>" style="max-width: 100px;" alt="<?php echo $title_main;?>">
        </div>
        <div class="pic-loader text-center">
            <img src="/assets/image/three-dots.svg" width="50" alt="">
        </div>
    </div>
</div>
<!-- Page Loader----------------------------->

</body>
<!-- 
-->
</html>

<?php
function schema_code_end() {
    global $ListProductSchema;
	global $schema, $sitenamelink, $logopage, $upload_path_main, $row_setting, $main_url, $row_content;
	global $GroupWeblog, $GroupProduct, $ListPrice;
    global $bankname;
	if ($schema=='') return ;
    if (count($ListProductSchema)==0) return '';
    
	$site_url 	= $sitenamelink;
	$site_name 	= $row_setting['tetr_firstpage'];
	$shop_name	= $row_setting['shop_name'];
	$logo_url 	= $sitenamelink.$logopage;
	$meta 		= $row_setting['meta'];
	$price_unit = $row_setting['price_unit'];
    
    if ($schema=='HomePage') {
        $itemlist_schemas = [];
        foreach ($ListProductSchema as $section) {
            // برای هر بخش، یک شیء ItemList جدید ایجاد می‌کنیم
            $item_elements = [];
            $position = 1;

            // پر کردن itemListElement با محصولات آن بخش
            foreach ($section['products'] as $product) {
                $availability=0;
                if ($product['cash']>0) $availability=$product['cash'];
                if ($price_unit=='تومان') $product['price'] = $product['price']* 10;
                $url = $site_url.$product['url'];
                $image_url	= $product['image'];
                if ($image_url=='') {
                    $image_url = $logo_url;
                }else{
                    if (!validateURL($image_url)) $image_url = $sitenamelink. $upload_path_main. $image_url;
                }

                $product_item = [
                    "@type" => "Product",
                    "name" => $product['name'],
                    "url" => $url,
                    "image" => $image_url, 
                    "description" => $product['dsc'],
                    "sku" => $product['id'],
                    "brand" => [
                        "@type" => "Brand",
                        "name" => $shop_name
                    ],
                    "offers" => [
                        "@type" => "Offer",
                        "url" => $url,
                        "priceCurrency" => 'IRR',
                        "price" => (string)$product['price'],
                        "availability" => (string)$availability, // مقدار فعلی شما
                        
                        "itemCondition" => "https://schema.org/NewCondition",
                        "shippingDetails" => [
                            "@type" => "OfferShippingDetails",
                            "shippingRate" => [
                                "@type" => "MonetaryAmount",
                                "value" => 0, // اگر ارسال رایگان است، صفر قرار دهید
                                "currency" => 'IRR' // کد واحد پول (IRR)
                            ],
                            "shippingDestination" => [
                                "@type" => "DefinedRegion",
                                "addressCountry" => "IR" // کد کشور (ایران)
                            ],
                            "deliveryTime" => [
                                "@type" => "ShippingDeliveryTime",
                                "handlingTime" => [ // زمان آماده‌سازی قبل از ارسال
                                    "@type" => "QuantitativeValue",
                                    "minValue" => 1,
                                    "unitCode" => "DAY" // 1 روز کاری
                                ]
                            ]
                        ]
                    ]
                ];        
                if (isset($product['vote_count']) && (int)$product['vote_count'] > 0) {
                    $product_item['aggregateRating'] = [
                        "@type" => "AggregateRating",
                        "ratingValue" => (string)($product['vote_sum'] / $product['vote_count']),
                        "reviewCount" => (int)$product['vote_count'] 
                    ];
                }                

                $item_elements[] = [
                    "@type" => "ListItem",
                    "position" => $position++,
                    "item" => $product_item // استفاده از آرایه شرطی ساخته شده
                ];                
            }

            // ساختار نهایی ItemList برای این بخش
            $itemlist_schemas[] = [
                "@type" => "ItemList",
                "name" => $section['title'], // استفاده از عنوان بخش به عنوان نام لیست
                "numberOfItems" => count($section['products']),
                "itemListElement" => $item_elements
            ];
        }
        $schema_array = [
            "@context" => "https://schema.org",
            "@graph" => $itemlist_schemas
        ];
    }
	if ($schema=='ImageGallery') {
        $tetr = $row_content['name'];
        $ListAllPhoto=[];
        foreach ($ListProductSchema as $row) {
            $image_url = $row['photo'];
            if ($image_url=='') {$image_url = $logo_url;}else{
                if (!validateURL($image_url)) $image_url = $sitenamelink. $upload_path_main. $image_url;
            }
            $url = $row['url'];
            if ($url=='') $url = $image_url;
            $caption = $row['name'];
            if ($caption=='') $caption = $tetr;
            
            
            $ListAllPhoto[] = [
                "@type" => "ImageObject",
                "contentUrl" => $image_url, 
                "url" => $url, 
                "caption" => $caption,
            ];
        }
        
        $schema_array = [
            "@context" => "https://schema.org",
            "@type" => "ImageGallery",
            "name" => $tetr,
            "description" => $row_content['dsc'],
            "associatedMedia" => $ListAllPhoto
        ];        
	}
	if (($schema=='CollectionPage') and ($bankname=='product')) {
        // آرایه برای نگهداری تمام عناصر ListItem
        $item_elements = [];
        $position = 1;

        foreach ($ListProductSchema as $product) {
            
            // --- الف) آماده‌سازی متغیرهای محصول ---
            $product_url_full = $site_url . $product['url'];
            $image_url_full = $site_url . $product['image'];

            // --- ب) ساختاردهی شیء Product ---
            $product_item = [
                "@type" => "Product",
                "name" => $product['name'],
                "url" => $product_url_full,
                "image" => $image_url_full, 
                "description" => $product['dsc'],
                "sku" => (string)$product['id'],
                "brand" => [
                    "@type" => "Brand",
                    "name" => $shop_name
                ],
                
                // بلوک offers (قیمت و موجودی)
                "offers" => [
                    "@type" => "Offer",
                    "url" => $product_url_full,
                    "priceCurrency" => 'IRR',
                    "price" => (string)$product['price'],
                    "availability" => $product['cash'],
                    "itemCondition" => "https://schema.org/NewCondition",
                ]
            ];
            
            // --- ج) اضافه کردن aggregateRating به صورت شرطی ---
            if ((int)$product['vote_count'] > 0) {
                $product_item['aggregateRating'] = [
                    "@type" => "AggregateRating",
                    "ratingValue" => (string)($product['vote_sum'] / $product['vote_count']),
                    "reviewCount" => (int)$product['vote_count']
                ];
            }
            
            // --- د) ساختاردهی ListItem نهایی ---
            $item_elements[] = [
                "@type" => "ListItem",
                "position" => $position++,
                "item" => $product_item
            ];
        }


        // === ۳. ساختار نهایی CollectionPage و ItemList در @graph ===
        $schema_array = [
            "@context" => "https://schema.org",
            "@graph" => [
                [
                    "@type" => "CollectionPage",
                    "name" => $site_name . '- فهرست محصولات',
                    "description" => $meta,
                    "url" => urldecode($main_url)
                ],
                // ب) ItemList (لیست محصولات)
                [
                    "@type" => "ItemList",
                    "name" => $site_name . '- فهرست محصولات',
                    "numberOfItems" => count($ListProductSchema),
                    "itemListElement" => $item_elements
                ]
            ]
        ];

    }
    
	if ( ($schema=='CollectionPage') and ($bankname=='weblog') ) {
        $item_elements = [];
        $position = 1;
        foreach ($ListProductSchema as $article) {
            $published	= jalaliToG($article['date']).'T'.$article['time'];
            // --- ساختاردهی شیء Article (BlogPosting) ---
            $article_item = [
                "@type" => "NewsArticle", // یا Article
                "name" => $article['name'],
                "headline" => $article['name'],
                "image" => $site_url . $article['image'],
                "url" => $site_url . $article['url'],
                "datePublished" => $published, 
                "author" => [
                    "@type" => "Organization",
                    "name" => $site_name,
                    "url" => $site_url
                ]
            ];
            
            // --- ساختاردهی ListItem نهایی ---
            $item_elements[] = [
                "@type" => "ListItem",
                "position" => $position++,
                "item" => $article_item
            ];
        }
        $schema_array = [
            "@context" => "https://schema.org",
            "@graph" => [
                // الف) CollectionPage (نوع صفحه)
                [
                    "@type" => "CollectionPage",
                    "name" => $site_name . '- فهرست مطالب',
                    "description" => $meta,
                    "url" => urldecode($main_url)
                ],
                // ب) ItemList (لیست مقالات)
                [
                    "@type" => "ItemList",
                    "name" => $site_name . '- فهرست مطالب',
                    "numberOfItems" => count($article_item),
                    "itemListElement" => $item_elements
                ]
            ]
        ];
	}
    if (isset($schema_array)) {
    $json_ld = json_encode($schema_array, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    echo "
    <script type='application/ld+json'>
    $json_ld 
    </script>";
    }
    
    
//    echo "<pre>";print_r($ListProductSchema);echo "</pre>";

}
?>