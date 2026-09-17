<?php 
include('topmain.php');
include('top.php');
//---------------------
?>
 <div class="container-main">
	<div class="row d-block m-0">

<?php
$query = pdo_query("SELECT * FROM `them_detils`  where id_them='$id_them' and isdeleted=0 and active=1 and id_position=3 order by id_position, idsort",'',0);
$row_abzarak = pdo_fetchall($query);
if (count($row_abzarak)==0) ShowMessage("برای این تم هیچ ابزارکی تعریف نشده است",1,'bg-danger');
foreach($row_abzarak as $key=>$value) {
	ShowAbzarak($row_abzarak[$key]);
}

?>
	</div>
</div>
<?php
include('bottom.php');
?>


