<?php
include('topmain.php');
$bankname='group_main';
if (isset($_REQUEST['id']))   	$id = intval($_REQUEST['id']);		else $id = 0;
if (isset($_REQUEST['pass']))   $pass = ($_REQUEST['pass']);		else $pass = '';
$query=pdo_query("select * from `$bankname` where id='$id' and type_group='1' and active='1' and isdeleted=0 ",'',0);
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
	$title_main=$name;
	if ($meta_title!='') {$meta = $meta_title;		$title_main=$meta_title;}
	if ($meta_description!='') $meta_dsc = $meta_description;
	//-------------------
	$canonical  = "$canonical/content/$id";
	
}
$schema = $row_content['schema_type'];
include('top.php');
if ($password!='') { 	if (CheckPassword($password) === false) { include('bottom.php');  return;} }
?>
<main>
	<div class="col-12">
		<div id="content">
			<div class="about">
				<div class="page-content-about">
                <h2 class='m-4 text-center'><?php echo $name;?></h2>
					<div class="page-content-about-paragraph text-justify">
					<?php echo $dsc;?>
					</div>
                </div>
            </div>
        </div>
    </div>
</main>
<?php
include('bottom.php');
?>

			
				

