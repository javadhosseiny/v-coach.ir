<?php
include('topmain.php');
$schema = 'ImageGallery'; 
$bankname='group_main';
if (isset($_REQUEST['id']))   	$id = intval($_REQUEST['id']);		else $id = 0;
if (isset($_REQUEST['pass']))   $pass = ($_REQUEST['pass']);		else $pass = '';
$query=pdo_query("select * from `$bankname` where id='$id' and type_group='2' and active='1' and isdeleted=0 ",'',0);
$row_content = pdo_fetch($query);
if ($row_content=='') {	$ErrorMsg .= "کد مطلب موردنظر صحیح نمی باشد";} else{
	$name  	= $row_content['name'];				$dsc 			= $row_content['dsc'];
	$them	= $row_content['them'];				$photo_group	= $row_content['photo_group'];
	$password			= $row_content['password'];
	$meta_title			= $row_content['meta_title'];
	$meta_description 	= $row_content['meta_description'];
	$url_redirect 		= $row_content['url_redirect'];
	if ($photo_group!='') {
		if (!validateURL($photo_group)) $photo_group = $upload_path_main.$photo_group;
		$photo_group=$logopage;
	}
	if ($url_redirect!='') header("Location: $url_redirect");
	if ($meta_title!='') $meta = $meta_title;
	if ($meta_description!='') $meta_dsc = $meta_description;
}
$title_main=$name;
include('top.php');
if ($password!='') { 	if (CheckPassword($password) === false) { include('bottom.php');  return;} }
$gallery='';			$ListImage='';
$query = pdo_query("select * from `group_gallery` where id_page='$id' and isdeleted=0 order by idsort ");
while ($row2=pdo_fetch($query)) {
	$photo_name	= $row2['name'];
	$photo_image= $row2['photo'];
	$photo_url	= $row2['url'];
	if ($photo_image=='') {$photo=$logofile;} 	else {$photo=$upload_path_main.$photo_image; }
	$tag = "<img class='w-100 rounded m-3 ' src='$photo'>";
	if ($photo_url=='') $tag = "<a href='$photo' target=_blank>$tag</a>"; 
		else $tag = "<a href='$photo_url' target=_blank>$tag</a>"; 
	if ($them==1) {		$gallery .= "<div class='col-12'>$tag</div>";			}
	if ($them==2) {		$gallery .= "<div class='col-12 col-md-3'>$tag</div>";	}
	if ($them==3) {		$gallery .= "<div class='col-12 col-md-4'>$tag</div>";	}
	if ($them==4) {		$gallery .= "<div class='col-12 col-md-6'>$tag</div>";	}
	if ($them==5) {		$ListImage .= "'$sitenamelink$photo',";	}
	$ListProductSchema[] = array('name'=>$photo_name, 'photo'=>$photo_image, 'url'=>$photo_url);
}
if ($them==5) {
	echo "
	<style>
        .thumbnail-img {
            width: 100px;
            height: 70px;
            object-fit: cover;
            cursor: pointer;
            border: 2px solid transparent;
            margin: 5px;
            transition: all 0.2s ease-in-out;
        }
        .thumbnail-img.active {
            border-color: #007bff;
        }
        .carousel-item img {
            max-height: 400px;
            object-fit: contain;
            margin: auto;
            display: block;
        }
        #productCarousel {
            max-width: 600px;
            margin: 20px auto;
        }
        .thumbnails-wrapper {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            margin-top: 15px;
        }
	</style>
	";
	$gallery = "
    <div id='productCarousel' class='carousel slide' data-ride='carousel'>
        <div class='carousel-inner'></div>
        <a class='carousel-control-prev' href='#productCarousel' role='button' data-slide='prev' style='background-color:gray;'>
            <span class='carousel-control-prev-icon' aria-hidden='true'></span>
            <span class='sr-only'>قبلی</span>
        </a>
        <a class='carousel-control-next' href='#productCarousel' role='button' data-slide='next' style='background-color:gray;'>
            <span class='carousel-control-next-icon' aria-hidden='true'></span>
            <span class='sr-only bg-red'>بعدی</span>
        </a>
    </div>
    <div class='thumbnails-wrapper' id='thumbnail-gallery'></div>
	";
}
$gallery = "<div class='row m-2'>$gallery</div>";
?>
<main>
	<div class="col-12">
		<div id="content">
			<div class="about">
				<div class="page-content-about">
                <h2 class='m-4 text-center'><?php echo $name;?></h2>
					<div class="page-content-about-paragraph text-justify">
					<?php 
					echo $dsc;
					echo $gallery;
					?>
					</div>
                </div>
            </div>
        </div>
    </div>
</main>
<?php
if ($them==5) {
?>
<script>
$(document).ready(function() {
	const images = [<?php echo $ListImage;?>];
    let carouselInner = $('#productCarousel .carousel-inner');
    let thumbnailGallery = $('#thumbnail-gallery');

    // پر کردن کاروسل و گالری thumbnails
    images.forEach((src, index) => {
        let carouselItem = `<div class="carousel-item ${index === 0 ? 'active' : ''}">
                                <img src="${src}" class="d-block w-100" alt="Product Image ${index + 1}">
                            </div>`;
        carouselInner.append(carouselItem);

        let thumbnail = `<img src="${src}" class="thumbnail-img ${index === 0 ? 'active' : ''}" data-target="#productCarousel" data-slide-to="${index}" alt="Thumbnail ${index + 1}">`;
        thumbnailGallery.append(thumbnail);
    });

    // اضافه کردن رویداد کلیک برای thumbnails
    $('.thumbnail-img').on('click', function() {
        let slideTo = $(this).data('slide-to');
        // در Bootstrap 4 از .carousel(index) استفاده می‌شود
        $('#productCarousel').carousel(slideTo);
    });

    // مدیریت تغییر کلاس active در thumbnails هنگام تغییر اسلاید
    $('#productCarousel').on('slid.bs.carousel', function() {
        let currentIndex = $('div.carousel-item.active').index();
        $('.thumbnail-img').removeClass('active');
        $(`.thumbnail-img[data-slide-to="${currentIndex}"]`).addClass('active');
    });
});
</script>
<?php 
}
include('bottom.php');
?>

			
				

