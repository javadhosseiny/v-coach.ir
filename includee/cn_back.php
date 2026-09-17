<?php
$host_name= "localhost";
$user_db= "root";
$pass_db= "";
$db_name= "vcoche_main";
//------------
try{
	$opt = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC);	
	$connection = new PDO("mysql:host=$host_name;dbname=$db_name;charset=utf8",$user_db,$pass_db,$opt);
	$connection -> exec("SET NAMES 'utf8mb4' COLLATE 'utf8mb4_persian_ci'");
//	$connection -> exec('set names utf8');	
}catch(PDOException $e){
	print "Error!: " . $e->getMessage() . "<br/>";  //error text info
	die();
}
