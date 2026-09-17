<?php
$ReferPage  = $_SERVER['HTTP_REFERER'];		
$HostScript = '://'.$_SERVER["HTTP_HOST"];
$ok_refer = strpos($ReferPage, $HostScript);	
if ($ok_refer === false) { echo json_encode(array(-1,'mismatch1'));	return; }// 'hacking';
if(empty($_POST['action'])) {echo json_encode(array(-1,'mismatch action'));	return; }// 
require_once('includee/cn_front.php');
require_once('includee/function.php');
require_once('includee/date2.php');
//----------------------------
$idnews = intval($_POST['key']);
$uid=intval($_POST['uid']);
$idgroup = intval($_POST['groupid']);
$star = intval($_POST['stars']);
$time0=time();
$date1 = date("Y-m-d H:i:s",$time0);  
$nowweek = date("W",(time()+2*24*60*60));// äÌ ÑæÒ ÚÞÈ ˜ÔíÏã æ ÏÑÓÊ ÔÏ ÓÇá ÔÏ 53 åÝÊå æ Çæá åÝÊå ÈÇ ÔäÈå ÔÑæÚ ãí ÔæÏ
$votedate=new Date($date1); 
$nowyear  = $votedate->format("%Y");
$nowmonth = $votedate->format("%Y/%m");
$nowweek = $nowmonth. '/'.$nowweek;	
$nowday   = $votedate->format("%Y/%m/%d");
//-----------------------------------------------------------------------	

if($_POST['action']=='set') {
	$vote = intval($_POST['vote']);
	if (($vote<0) or ($vote>5) ) $vote=1;
	$timeread = time();
	$timebase = time() - (24*60*60);
//		$timebase = time() ;
	$ipvote  = $_SERVER["REMOTE_ADDR"];
	$query = pdo_query("select count(*) from `logvote` where ip='$ipvote' and idnews='$idnews' and timeread>'$timebase' ;");
	$num = pdo_count($query);
	if($num==0) {
		$query2=pdo_query("insert into `logvote` (idnews, idgroup, timeread, ip, vote) values ('$idnews', '$idgroup', '$timeread', '$ipvote', '$vote');");
		$query3=pdo_query("update `product` set  vote_sum=(vote_sum+$vote), vote_count=(vote_count+1) where id='$idnews' ");
		
	}
}
$query =pdo_query("select vote_sum, vote_count from `product` where id='$idnews' ");
$row_vote = pdo_fetch($query);
if($row_vote!='') {
	// number1: uid: number fixed for sign server and client
	// number2: vote: 
	$vote_sum   = $row_vote['vote_sum'];
	$vote_count = $row_vote['vote_count'];
	$sum_star = ($vote_sum / $vote_count);
	echo "$uid;$vote_sum;0;$sum_star"; //  $ary['countvote'];
} else {
	echo $_POST['uid'].';0;0;0'; 
}

?>