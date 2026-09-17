<?php
//https://hajatha.ir/verify/4?&Authority=A0000000000000000000000000006gx11pd1&Status=NOK
//https://hajatha.ir/verify/6&&Authority=A000000000000000000000000000djzqr57m&Status=NOK
//--------------------------------
include('topmain.php');
$title_main = 'تایید پرداخت';
$id_customer=0;             $amount=0;
//---------------------
include('top.php'); 
echo "<div class='row'></div>"; 
//----------------------
if (isset($_GET['id']))         $id_order   = $_GET['id'];          else $id_order  = 0;
if ($id_order==0)       ShowErrorWithExit("شماره سفارش مشخص نشده است");
$query  = pdo_query("select * from `order_title` where id_main='$id_order' and isdeleted=0 ");
$row    = pdo_fetch($query);
if ($row=='')           ShowErrorWithExit("شماره سفارش موردنظر یافت نشد <BR>");
$id_customer    = $row['id_customer'];
$price_payment  = $row['price_payment'];
$id_payment     = $row['id_payment'];
$query3         = pdo_query("select * from `payment_getway` where id_main='$id_payment' and active='1' ");
$row_payment    = pdo_fetch($query3);
if ($row_payment=='')   ShowErrorWithExit("کد درگاه پرداختی برای این سفارش یافت نشد <BR>");
$payment_type 	= $row_payment['id_type'];
$acceptor_code 	= $row_payment['acceptor_code'];

//اگر سفارش برای این کاربر نباشد
if (($id_customer<>$usernameid) or ($usernameid==0)) ShowErrorWithExit("این پرداخت سفارش متعلق به شما نیست <BR>");


//------------------- درگاه زرین پال
if ($payment_type==2) {
    if (isset($_GET['Authority']))  $Authority  = $_GET['Authority'];   else $Authority = '';
    if (isset($_GET['Status']))     $Status     = $_GET['Status'];      else $Status    = '';
    if ($Authority=='')    ShowErrorWithExit("کد ارجاع وجود ندارد");
    if ($Status=='')       ShowErrorWithExit("وضعیت پرداخت مشخص نشده است");
    //---------------------------
    $query2  = pdo_query("select * from `order_receipt` where authority='$Authority' ");
    $row2    = pdo_fetch($query2);
    if ($row2=='') ShowErrorWithExit("کد ارجاع صحیح یافت نشد<BR>");
    $id_receipt = $row2['id_main'];
    $amount     = $row2['amount'];
    // جلوگیری از verify تکراری
    if ($row2['verify_status'] == 1)  ShowErrorWithExit("این سفارش قبلا تأیید شده<BR>");
    // پرداخت کنسل شده
    if ($Status != 'OK') {
        pdo_query("update order_receipt set verify_status=2 where id_main='$id_receipt'");
        ShowErrorWithExit("پرداخت لغو شد<BR>");
    }
//    نیازی نیست چون در مرحله ارسال در صورت ریال نبودن خودش ضربدر 10 کرده است
//    if (trim($row_setting['price_unit'])!='ریال'){	$amount = $amount * 10;	}
    
    $data = array("merchant_id" => $acceptor_code, "authority" => $Authority, "amount" => $amount);
    $jsonData = json_encode($data);
    $ch = curl_init('https://api.zarinpal.com/pg/v4/payment/verify.json');
    curl_setopt($ch, CURLOPT_USERAGENT, 'ZarinPal Rest Api v4');
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'Content-Length: ' . strlen($jsonData)
    ));
    $result = curl_exec($ch);
    $err    = curl_error($ch);
    curl_close($ch);
    $result = json_decode($result, true);
    if ($err) {         ShowErrorWithExit("cURL Error #: $err");    }
    $logresult = @json_encode($result, JSON_UNESCAPED_UNICODE);        
    if ($result['data']['code'] == 100) {
         $id_ref = $result['data']['ref_id'];
        $query  = pdo_query("update `order_title` set status_payment='1', date_payment='$nowdate', status_order='3' where id_main='$id_order' and isdeleted=0 ");
        
        $log = "\n $nowdate $nowtime $jsonData $logresult";
        $query  = pdo_query("update `order_receipt` set id_ref='$id_ref', status='$Status', verify_status=1, log=concat(log,'$log') where id_main='$id_receipt' ");
        
        ShowMessage("پرداخت با موفقیت انجام شد <BR><a href='/myorder/$id_order'> مشاهده سفارش </a>",0,'bg-success'); 
        
    } else {
        
        if (isset($result['errors']['code']))    $Errorcode="Error Code: ".$result['errors']['code']; 
            else { $Errorcode= "data[code]" . $result['data']['code']; }
        if (isset($result['errors']['message'])) $ErrorMessage=$result['errors']['message'];
            else { $ErrorMessage= $result['data']['message']; }
        $ErrorMsg = "$Errorcode - Message=$ErrorMessage";
        ShowMessage($ErrorMsg,0,'bg-danger'); 
        $log = "\n $nowdate $nowtime $ErrorMsg $jsonData $logresult";
        pdo_query("update order_receipt set verify_status=2, log=concat(log,'$log') where id_main='$id_receipt'");
    }
    
}
//var_dump($_REQUEST);
include('bottom.php'); 

//-------------------------
function ShowErrorWithExit($msg) {
    global $row_setting, $id_them, $title, $keyword, $connection ;
    ShowMessage($msg,0,'bg-danger'); 
    include('bottom.php'); 
    exit; 
    
}