<?php

$username  = "javad_hosseiny";
$password  = '@Javad2839';
$from      = '3000151515';
$to        = '09361535992';
$to        = '09121536501';
$message="سلام \r\n کد ثبت نام: 1354 \r\n مؤسسه ولی عصر \r\n لغو11";
//$message   = 'کد ثبت نام: 2024';

//http://tsms.ir/url/tsmshttp.php?from=3000151515&to=09361535992&username=javad_hosseiny&password=@Javad2839&message=salam

// http://tsms.ir/url/tsmshttp.php?from=3000151515&username=javad_hosseiny&password=@Javad2839&credit=what

$url = "http://tsms.ir/url/tsmshttp.php";
$params = [
    'username'  => "javad_hosseiny",
    'password'  => '@Javad2839',
    'from'      => '3000151515',
//    'credit'    => 'what'
    'to'        => '09361535992',
    'message'   => $message
];

$query = http_build_query($params);
$finalUrl = $url . '?' . $query;
$handler = curl_init($finalUrl);
curl_setopt($handler, CURLOPT_RETURNTRANSFER, true);
curl_setopt($handler, CURLOPT_FOLLOWLOCATION, true);
$response = curl_exec($handler);
$curl_error = @curl_error($handler);
curl_close($handler);
if ($curl_error) {   
    echo "خطای cURL در مرحله دریافت مسیر آپلود: $curl_error"; 
}
if (($response === false) or (is_null($response)) ) {
    echo "هیچ پاسخی از سمت سرور segnalads دریافت نشد"; 
}
echo "response=".$response;
//--------------------------------------

function test1() {
//http://tsms.ir/url/tsmshttp.php?from=3000151515&to=09121536501&username=javad_hosseiny&password=@Javad2839&message=salam
//http://tsms.ir/url/tsmshttp.php?from=3000151515&to=09121536501&username=javad_hosseiny&password=@Javad2839 &message=کد ثبت نام: 2084 \r\n مؤسسه ولی عصر(عج) \r\n لغو11
$username='javad_hosseiny';
$password='@Javad2839';
//$mobile_array=array( '09121536501');
$mobile_array='09361535992';
$message="سلام \r\n کد ثبت نام: 1354 \r\n مؤسسه ولی عصر \r\n لغو11";
$sms_number_array='3000151515';
$messagid=rand();
$mclass=array('');


//?from=3000151515&to=09121536501&username=javad_hosseiny&password=@Javad2839 
//&message=کد ثبت نام: 2084 \r\n مؤسسه ولی عصر(عج) \r\n لغو11

echo (makeHTTPRequest(['username'=>$username,'password'=>$password,'from'=>$sms_number_array
    ,'to'=>$mobile_array
    ,'message'=>$msg_array
]));

//echo (makeHTTPRequest(['method'=>'GetDeliverySms','username'=>$username,'password'=>$password,'messageid'=>['284663176']]));

function makeHTTPRequest( $datas = [])
{
    $url = "http://tsms.ir/url/tsmshttp.php" ;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($datas));
    $res = curl_exec($ch);
    if (curl_error($ch)) {
        var_dump(curl_error($ch));
    } else {
        return $res;
    }
}
}