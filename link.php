<?php
//$RunTop=1; مجبوریم همه اش رو بخونیم چون بعدش نیاز به چینش top.php داریم
include('topmain.php');
//echo base62_encode(101); exit; //100=aoI      101=aoJ

$title_main='لینک کوتاه';
$canonical  = "$canonical/z/";
if (isset($_REQUEST['code']))  $code = trim($_REQUEST['code']);  	else $code = '';
if ($code=='') {$ErrorMsg = "کد لینک کوتاه ارسال نشده است";  }else{
    $id = floatval(base62_decode($code));
    $query  = pdo_query("select * from `short_links` where id_main='$id' and active=1");
    $row    = pdo_fetch($query);
    if ($row=='') {$ErrorMsg = "لینک کوتاه یافت نشد";  }else{
        $url = $row['long_url'];
        pdo_query("update `short_links` set clicks=(clicks+1) where id_main='$id'");
        if (!validateURL($url)) { $url=$sitenamelink.'/'.$url;}
        header("location: $url");
    }
}
include('top.php');
