<?php
$script_filename =  clean(strtolower(basename($_SERVER["SCRIPT_FILENAME"], '.php')));
$link_logevents = clean($_SERVER['REQUEST_URI']);
if (isset($WebService)) { 
	$link_logevents = clean(json_encode(json_decode(file_get_contents('php://input'),JSON_UNESCAPED_UNICODE),JSON_UNESCAPED_UNICODE));
}

$list_no_save = array("index");
$ListScriptfile = array();
$toz_log = '';
foreach($_POST as $campo => $input_variable){
	$toz_log .= "$campo = $input_variable - ";
}
foreach($_GET as $campo => $input_variable){
	$can_foreach = is_array($input_variable) || is_object($input_variable);	
	if($can_foreach){
		$toz_log .= '[';
		foreach ($input_variable as $campo2 => $input_variable2) {
			$toz_log .= "$campo2 = $input_variable2 = ";
		}
		$toz_log .= ']';
	}
	$toz_log .= "$campo = $input_variable - ";
}
//if ((in_array($script_filename, $list_no_save))) {return;}
//echo "<BR> script_filename = $script_filename <BR>toz_log = $toz_log <BR> idnews_log=$idnews_log <BR>  scriptfilename-->".$ListScriptfile[$script_filename];
if (is_null($usernameid)) $usernameid='';
$part_log_name = @$ListScriptfile[$script_filename];
if ($part_log_name=='') $part_log_name = $script_filename;
$query_array_log = array(':usernamefarsi'=>$usernamefarsi, ':part_log_name'=>$part_log_name, ':toz_log'=>$toz_log, ':link_logevents'=>$link_logevents, ':ddate'=>"$nowdate $nowtime", ':iplogin'=>$ip, ':usernameid'=>$usernameid);	
$logevents_query=pdo_query("insert into `logevents_user` values ('', :usernamefarsi, :part_log_name, :toz_log, :link_logevents, :ddate, :iplogin, :usernameid)",$query_array_log,0);

?>
